<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class freeService{

     public function contarObjetos($imagen)
    {
        ini_set('max_execution_time', 300);

        
        $rutaRelativa = $imagen->storeAs("free/conteo", $imagen->getClientOriginalName());
        $rutaImagen = storage_path("app/$rutaRelativa");
        $rutaModelo = storage_path("app/free/modelos/mascotas.h5");

        // Obtener clases desde base de datos
        $clases = ['perro', 'gato'];
        $etiquetas = implode(',', $clases);

        $cmd = "\"" . base_path("Scripts/run_contar.bat") . "\" \"$rutaImagen\" \"$rutaModelo\" \"$etiquetas\"";
        exec($cmd, $output, $status);

        if ($status !== 0) {
            throw new \Exception("Error al ejecutar el conteo.");
        }

        //$totalConteo = 0;
        //foreach ($output as $linea) {
        //    if (preg_match('/:\s*(\d+)/', $linea, $coincidencia)) {
        //       $totalConteo += (int) $coincidencia[1];
        //    }
        //}
        //return implode("\n", $output);
        //"$modelo->nombre: " . implode("\n", $output)
        return [
        'resultado' => implode("\n", $output)
        ];
    }
}