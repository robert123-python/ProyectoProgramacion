import sys
import tensorflow as tf
import cv2
import numpy as np
import os
import heapq
from tensorflow.keras.preprocessing.image import ImageDataGenerator

# Leer argumentos: imagen, modelo, clases_dir (opcional)
img_path = sys.argv[1]
model_path = sys.argv[2]
clases_dir = sys.argv[3] if len(sys.argv) > 3 else None

clases = None

# Cargar nombres de clases si se proporciona clases_dir
if clases_dir and os.path.exists(clases_dir):
    datagen = ImageDataGenerator(rescale=1./255)
    gen = datagen.flow_from_directory(clases_dir, target_size=(128, 128), batch_size=1, shuffle=False)
    indices = gen.class_indices
    clases = {v: k for k, v in indices.items()}

try:
    # Leer imagen
    img = cv2.imread(img_path)
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = cv2.resize(img, (128, 128))
    img = (img / 127.5) - 1.0
    img = np.expand_dims(img, axis=0)

    # Cargar modelo y predecir
    model = tf.keras.models.load_model(model_path)
    pred = model.predict(img, verbose=0)[0]

    # Crear una cola de prioridad (heap) con todas las clases y sus probabilidades
    # Cada entrada será una tupla: (-probabilidad, índice)
    heap = [(-prob, i) for i, prob in enumerate(pred)]
    heapq.heapify(heap)  # convierte la lista en un heap válido

    print("Probabilidades de a que clase pertenece la imagen:")
    while heap:
        neg_prob, i = heapq.heappop(heap)  # saca el más probable
        prob = -neg_prob  # invertimos el signo para obtener la probabilidad real
        nombre = clases[i] if clases else f"Clase {i}"
        print(f"- {nombre}: {prob * 100:.2f}%")

except Exception as e:
    print("Error durante la predicción:", str(e))
