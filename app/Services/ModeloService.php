<?php
namespace App\Services;

use App\Models\ModeloImagen;
use App\Models\Conteo;
use App\Repositories\ModeloRepository;
use Illuminate\Support\Facades\Storage;

class ModeloService
{
    protected $repositorio;

    public function __construct(ModeloRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function entrenar($user, $nombre, $imagenes = null)
{
    ini_set('max_execution_time', 1200);
    $carpeta = "usuarios/{$user->id}/entrenamiento/{$nombre}";
    $ruta = storage_path("app/$carpeta");

    $imagenesInfo = [];

    // Recorrer subcarpetas (clases) para registrar las imágenes
    $clases = Storage::directories($carpeta);

    foreach ($clases as $clasePath) {
        $claseNombre = basename($clasePath);
        $archivos = Storage::files($clasePath);

        foreach ($archivos as $rutaRelativa) {
            $imagenesInfo[] = [
                'ruta' => $rutaRelativa,
                'clase' => $claseNombre,
            ];
        }
    }

    // Ejecutar script de entrenamiento
    $cmd = "\"" . base_path("Scripts/run_Model.bat") . "\" \"$ruta\" \"$nombre\" \"{$user->id}\"";
    exec($cmd);

    // Guardar modelo en BD
    $modelo = $this->repositorio->buscarPorNombreYUsuario($nombre, $user->id);
    if (!$modelo) {
        $modelo = $this->repositorio->crear([
            'user_id' => $user->id,
            'nombre' => $nombre,
            'ruta_modelo' => "usuarios/{$user->id}/modelos/{$nombre}.h5"
        ]);
    }

    // Registrar imágenes en la base de datos
     foreach ($imagenesInfo as $img) {
        $modelo->imagenes()->updateOrCreate(
            ['ruta_imagen' => $img['ruta'], 'modelo_id' => $modelo->id],
            ['clase' => $img['clase']]
        );
    }
    return $modelo;
}

public function definirClasesYCarpetas($user, $nombreModelo, array $clases)
{
    foreach ($clases as $clase) {
        $path = "usuarios/{$user->id}/entrenamiento/{$nombreModelo}/{$clase}";
        Storage::makeDirectory($path);
    }
}

public function subirImagenesAClase($user, $nombreModelo, $clase, $imagenes)
{
    $ruta = "usuarios/{$user->id}/entrenamiento/{$nombreModelo}/{$clase}";

    foreach ($imagenes as $imagen) {
        $nombreUnico = uniqid() . '_' . $imagen->getClientOriginalName();
        $imagen->storeAs($ruta, $nombreUnico);
    }
}

    public function predecir($user, $modeloId, $imagen)
    {
        $modelo = $this->repositorio->buscarPorId($modeloId);

        if ($modelo->user_id !== $user->id) {
            throw new \Exception("No autorizado.");
        }

        $rutaImagen = $imagen->storeAs("usuarios/{$user->id}/pruebas", $imagen->getClientOriginalName());

        $script = base_path("Scripts/run_predecir.bat");
        $rutaAbsoluta = storage_path("app/$rutaImagen");
        $modeloAbsoluto = storage_path("app/{$modelo->ruta_modelo}");
        $rutaClases = storage_path("app/usuarios/{$user->id}/entrenamiento/{$modelo->nombre}");

        $cmd = "\"$script\" \"$rutaAbsoluta\" \"$modeloAbsoluto\" \"$rutaClases\"";
        exec($cmd, $output, $status);

        return implode("\n", $output);
    }

    public function contarObjetos($user, $modeloId, $imagen)
    {
        ini_set('max_execution_time', 300);
        $modelo = $this->repositorio->buscarPorId($modeloId);

        if (!$modelo || $modelo->user_id !== $user->id) {
            throw new \Exception('No autorizado.');
        }

        $rutaRelativa = $imagen->storeAs("usuarios/{$user->id}/conteo", $imagen->getClientOriginalName());
        $rutaImagen = storage_path("app/$rutaRelativa");
        $rutaModelo = storage_path("app/{$modelo->ruta_modelo}");

        // Obtener clases desde base de datos
        $clases = $modelo->imagenes->pluck('clase')->unique()->values()->all();
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
        $totalConteo = 0;
        foreach ($output as $linea) {
        if (preg_match('/Total validos:\s*(\d+)/', $linea, $coincidencia)) {
        $totalConteo = (int) $coincidencia[1];
        break;
         }
        }
        $conteo=Conteo::create([
        'user_id' => $user->id,
        'modelo_id' => $modelo->id,
        'ruta_imagen' => $rutaRelativa,
        'conteo_automatico' => $totalConteo,
        ]);

        //return implode("\n", $output);
        //"$modelo->nombre: " . implode("\n", $output)
        return [
        'resultado' => "$modelo->nombre: " . implode("\n", $output),
        'conteo_id' => $conteo->id,
        ];
    }
}
