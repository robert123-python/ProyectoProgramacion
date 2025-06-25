<?php
require_once __DIR__ . '/libreria/libreria.php';

header('Content-Type: application/json');

$comando = $_POST['comando'] ?? '';
$texto = $_POST['texto'] ?? '';

try {
    $lib = new Libreria();

    ob_start(); // Captura lo que imprime la librería
    $lib->procesarComandoVoz($comando, $texto);
    $salida = ob_get_clean();

    echo json_encode(['mensaje' => trim($salida)]);
} catch (Exception $e) {
    echo json_encode(['mensaje' => 'Error: ' . $e->getMessage()]);
}
