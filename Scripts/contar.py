import sys
import cv2
import numpy as np
from tensorflow.keras.models import load_model

def preprocesar_imagen(img_path, blur_ksize=3, morph_kernel_size=3, morph_iter_open=2, morph_iter_dilate=2):
    imagen = cv2.imread(img_path)
    if imagen is None:
        raise FileNotFoundError(f"No se pudo cargar la imagen: {img_path}")
    gris = cv2.cvtColor(imagen, cv2.COLOR_BGR2GRAY)
    gris = cv2.GaussianBlur(gris, (blur_ksize, blur_ksize), 0)
    _, binaria = cv2.threshold(gris, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    kernel = np.ones((morph_kernel_size, morph_kernel_size), np.uint8)
    binaria = cv2.morphologyEx(binaria, cv2.MORPH_OPEN, kernel, iterations=morph_iter_open)
    binaria = cv2.dilate(binaria, kernel, iterations=morph_iter_dilate)
    return imagen, binaria
#
def filtrar_contornos(contornos, area_min=400, area_max=10000, w_min=15, h_min=15, aspect_ratio_min=0.3, aspect_ratio_max=3.0):
    contornos_filtrados = []
    for c in contornos:
        area = cv2.contourArea(c)
        x, y, w, h = cv2.boundingRect(c)
        aspect_ratio = w / h if h > 0 else 0
        if (area_min <= area <= area_max) and w >= w_min and h >= h_min:
            if aspect_ratio_min <= aspect_ratio <= aspect_ratio_max:
                contornos_filtrados.append(c)
    return contornos_filtrados

def normalizar_imagen(img):
    img = img.astype(np.float32) / 255.0
    img = (img - 0.5) / 0.5
    return img

def extraer_recortes(imagen, contornos, tamaño=128, interpolacion=cv2.INTER_CUBIC):
    recortes = []
    bboxs = []
    for contorno in contornos:
        x, y, w, h = cv2.boundingRect(contorno)
        recorte = imagen[y:y+h, x:x+w]
        # Convertir de BGR a RGB para que el modelo reciba el formato esperado
        recorte = cv2.cvtColor(recorte, cv2.COLOR_BGR2RGB)
        h_org, w_org = recorte.shape[:2]
        max_dim = max(w_org, h_org)
        padded = np.zeros((max_dim, max_dim, 3), dtype=np.uint8)
        x_offset = (max_dim - w_org) // 2
        y_offset = (max_dim - h_org) // 2
        padded[y_offset:y_offset+h_org, x_offset:x_offset+w_org] = recorte
        recorte_redim = cv2.resize(padded, (tamaño, tamaño), interpolation=interpolacion)
        recorte_norm = normalizar_imagen(recorte_redim)
        recortes.append(recorte_norm)
        bboxs.append((x, y, w, h))
    return recortes, bboxs


def eliminar_solapamientos(bboxs, umbral_iou=0.2):
    def iou(box1, box2):
        x1, y1, w1, h1 = box1
        x2, y2, w2, h2 = box2
        xa = max(x1, x2)
        ya = max(y1, y2)
        xb = min(x1 + w1, x2 + w2)
        yb = min(y1 + h1, y2 + h2)
        inter_area = max(0, xb - xa) * max(0, yb - ya)
        union_area = w1 * h1 + w2 * h2 - inter_area
        return inter_area / union_area if union_area > 0 else 0

    eliminados = set()
    for i in range(len(bboxs)):
        for j in range(i + 1, len(bboxs)):
            if iou(bboxs[i], bboxs[j]) > umbral_iou:
                area_i = bboxs[i][2] * bboxs[i][3]
                area_j = bboxs[j][2] * bboxs[j][3]
                if area_i < area_j:
                    eliminados.add(i)
                else:
                    eliminados.add(j)
    return eliminados

def main():
    ruta_imagen = sys.argv[1]
    ruta_modelo = sys.argv[2]
    etiquetas = sys.argv[3].split(',')

    imagen, binaria = preprocesar_imagen(ruta_imagen)
    contornos, _ = cv2.findContours(binaria, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    contornos_filtrados = filtrar_contornos(contornos)

    if not contornos_filtrados:
        print("No se detectaron objetos tras el filtrado.")
        sys.exit(0)

    recortes, bboxs = extraer_recortes(imagen, contornos_filtrados)

    modelo = load_model(ruta_modelo)
    batch = np.array(recortes)
    predicciones = modelo.predict(batch, verbose=0)

    umbral_confianza = 0.7
    objetos_validos = []
    for i, pred in enumerate(predicciones):
        max_conf = np.max(pred)
        if max_conf >= umbral_confianza:
            clase_idx = np.argmax(pred)
            clase = etiquetas[clase_idx]
            objetos_validos.append((clase, bboxs[i], max_conf))

    if not objetos_validos:
        print("No se detectaron objetos con suficiente confianza.")
        sys.exit(0)

    bboxs_validos = [obj[1] for obj in objetos_validos]
    indices_eliminados = eliminar_solapamientos(bboxs_validos)

    #conteo = {}
    #for idx, (clase, _, _) in enumerate(objetos_validos):
    #    if idx not in indices_eliminados:
    #        conteo[clase] = conteo.get(clase, 0) + 1

    #if conteo:
    #    for clase, cantidad in conteo.items():
    #        print(f"{clase}: {cantidad}")
    #else:
    #    print("No se detectaron objetos finales después del filtrado de solapamientos.")
    conteo_total = 0
    for idx, _ in enumerate(objetos_validos):
        if idx not in indices_eliminados:
            conteo_total += 1
    if conteo_total > 0:
        print(f"Total validos: {conteo_total}")
    else:
        print("No se detectaron objetos válidos después del filtrado.")

if __name__ == "__main__":
    main()