import sys
import tensorflow as tf
import os
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.models import load_model
from tensorflow.keras.applications import ResNet50
from tensorflow.keras.models import Model
from tensorflow.keras.layers import GlobalAveragePooling2D, Dense, Dropout, Input
from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint

# Parámetros desde la línea de comandos
ruta = sys.argv[1]       # Ruta de las imágenes
nombre = sys.argv[2]     # Nombre del modelo
user_id = sys.argv[3]    # ID del usuario

# Ruta donde se guardará o buscará el modelo
base_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
ruta_modelo = os.path.join(base_dir, "storage", "app", "usuarios", str(user_id), "modelos", f"{nombre}.h5")
os.makedirs(os.path.dirname(ruta_modelo), exist_ok=True)

# Preprocesamiento de imágenes con validación
datagen = ImageDataGenerator(
    validation_split=0.2,
    preprocessing_function=lambda x: (x / 127.5) - 1.0  # Normalización para ResNet
)

train_gen = datagen.flow_from_directory(
    ruta, target_size=(128, 128), class_mode='categorical', subset='training'
)

val_gen = datagen.flow_from_directory(
    ruta, target_size=(128, 128), class_mode='categorical', subset='validation'
)

# Usamos la misma ruta para checkpoint y modelo final (sobrescribe el mismo archivo)
checkpoint_path = ruta_modelo

# Verificar si existe un modelo ya entrenado
if os.path.exists(ruta_modelo):
    print(f"Cargando modelo existente desde {ruta_modelo}...")
    modelo = load_model(ruta_modelo)

    # Verificar número de clases coincide
    if modelo.output_shape[-1] != train_gen.num_classes:
        print("El número de clases no coincide. No se puede reentrenar este modelo con nuevas clases.")
        exit(1)
else:
    print("Creando nuevo modelo con ResNet50 como base...")
    base_model = ResNet50(
        input_shape=(128, 128, 3),
        include_top=False,
        weights='imagenet'
    )
    base_model.trainable = False  # congelar base

    inputs = Input(shape=(128, 128, 3))
    x = base_model(inputs, training=False)
    x = GlobalAveragePooling2D()(x)
    x = Dropout(0.3)(x)
    x = Dense(64, activation='relu')(x)
    outputs = Dense(train_gen.num_classes, activation='softmax')(x)

    modelo = Model(inputs, outputs)

# Compilar modelo
modelo.compile(
    optimizer='adam',
    loss='categorical_crossentropy',
    metrics=['accuracy', tf.keras.metrics.Precision(), tf.keras.metrics.Recall()]
)

# Callbacks
early_stop = EarlyStopping(monitor='val_loss', patience=3, restore_best_weights=True, verbose=1)
model_checkpoint = ModelCheckpoint(checkpoint_path, monitor='val_loss', save_best_only=True, verbose=1)

# Entrenar
modelo.fit(
    train_gen,
    validation_data=val_gen,
    epochs=10,
    callbacks=[early_stop, model_checkpoint],
    verbose=2
)

print("Modelo guardado en:", ruta_modelo)