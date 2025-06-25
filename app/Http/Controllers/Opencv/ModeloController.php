<?php
namespace App\Http\Controllers\Opencv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ModeloService;
use Illuminate\Support\Facades\Auth;

class ModeloController extends Controller
{
    protected $servicio;

    public function __construct(ModeloService $servicio)
    {
        $this->servicio = $servicio;
    }

    public function crear()
    {
        return view('Modelos.crear');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre_modelo' => 'required|string',
            'imagenes.*' => 'required|image'
        ]);

        $this->servicio->entrenar(
            Auth::user(),
            $request->input('nombre_modelo'),
            $request->file('imagenes')
        );

        return redirect()->route('modelos.index')->with('success', 'Modelo entrenado.');
    }

    public function reentrenar(Request $request, $modeloId)
{
    $request->validate([
        'imagenes.*' => 'required|image|max:4096'
    ]);

    $usuario = Auth::user();

    $modelo = $usuario->modelos()->find($modeloId);

    if (!$modelo) {
        abort(403, 'No autorizado para reentrenar este modelo.');
    }

    $this->servicio->entrenar($usuario, $modelo->nombre, $request->file('imagenes'));

    return redirect()->route('Modelos.modelos')->with('success', 'Modelo reentrenado correctamente.');
}

public function definirClases(Request $request)
{
    $request->validate([
        'nombre_modelo' => 'required|string',
        'clases' => 'required|string'
    ]);

    $nombre = $request->input('nombre_modelo');
    $clases = array_map('trim', explode(',', $request->input('clases')));
    $user = Auth::user();

    $this->servicio->definirClasesYCarpetas($user, $nombre, $clases);

    return redirect()->route('modelos.formularioSubida', [
        'nombre_modelo' => $nombre,
        'clases' => implode(',', $clases)
    ]);
}

public function mostrarFormularioSubida(Request $request)
{
    $nombre = $request->query('nombre_modelo');
    $clases = explode(',', $request->query('clases'));

    return view('Modelos.subirImagenes', [
        'nombre_modelo' => $nombre,
        'clases' => $clases
    ]);
}

public function subirImagenes(Request $request)
{
    $request->validate([
        'nombre_modelo' => 'required|string',
        'clase' => 'required|string',
        'imagenes.*' => 'required|image|max:4096'
    ]);

    $user = Auth::user();
    $this->servicio->subirImagenesAClase(
        $user,
        $request->input('nombre_modelo'),
        $request->input('clase'),
        $request->file('imagenes')
    );

    return back()->with('success', "Imágenes de la clase '{$request->input('clase')}' subidas correctamente.");
}

public function entrenarFinal(Request $request)
{
    $request->validate([
        'nombre_modelo' => 'required|string'
    ]);

    $user = Auth::user();
    $modelo = $request->input('nombre_modelo');
    $basePath = "usuarios/{$user->id}/entrenamiento/{$modelo}";
    $fullPath = storage_path("app/{$basePath}");

    // Validar que hay al menos 2 clases con imágenes
    $carpetas = \Storage::directories($basePath);
    $clasesValidas = 0;

    foreach ($carpetas as $clasePath) {
        if (count(\Storage::files($clasePath)) > 0) {
            $clasesValidas++;
        }
    }

    if ($clasesValidas < 2) {
        return back()->withErrors(['Se necesitan al menos dos clases con imágenes para entrenar el modelo.']);
    }

    // Convertir archivos a objetos File para pasarlos al servicio
    $imagenes = collect(\Storage::allFiles($basePath))
        ->map(fn($path) => new \Illuminate\Http\File(storage_path("app/" . $path)));

    // Entrenar usando el servicio
    $this->servicio->entrenar($user, $modelo, $imagenes);

    return redirect()->route('modelos.index')->with('success', 'Modelo entrenado exitosamente.');
}
public function predecirVista()
    {
        $modelos = Auth::user()->modelos;
        return view('modelos.predecir', compact('modelos'));
    }

    public function predecir(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image',
            'modelo_id' => 'required|exists:modelos,id'
        ]);

        $resultado = $this->servicio->predecir(Auth::user(), $request->modelo_id, $request->file('imagen'));

        return redirect()->route('modelos.predecirVista')->with('resultado', $resultado);
    }

    public function index()
    {
        $modelos = Auth::user()->modelos;
        return view('Modelos.modelos', compact('modelos'));
    }

    public function eliminar($id)
{
    $modelo = Auth::user()->modelos()->find($id);

    if (!$modelo) {
        abort(403, 'No autorizado.');
    }

    // Eliminar archivo .h5 si existe
    $ruta = storage_path("app/{$modelo->ruta_modelo}");
    if (file_exists($ruta)) {
        unlink($ruta);
    }
    $modelo->delete();

    return redirect()->route('Modelos.modelos')->with('success', 'Modelo eliminado correctamente.');
}

public function contarVista()
    {
        $modelos = Auth::user()->modelos;
        return view('modelos.contar', compact('modelos'));
    }

public function contarObjetos(Request $request)
{
    $request->validate([
        'imagen' => 'required|image',
        'modelo_id' => 'required|exists:modelos,id'
    ]);

    try {
        $resultado = $this->servicio->contarObjetos(
            Auth::user(),
            $request->modelo_id,
            $request->file('imagen')
        );

         return back()->with([
            'resultado' => $resultado['resultado'],
            'conteo_id' => $resultado['conteo_id'],
        ]);
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}



public function seleccionarClase($modeloId)
{
    $user = Auth::user();
    $modelo = $user->modelos()->findOrFail($modeloId);

    $basePath = "usuarios/{$user->id}/entrenamiento/{$modelo->nombre}";
    $clases = \Storage::directories($basePath);
    $clases = array_map('basename', $clases);

    return view('Modelos.editar', compact('modelo', 'clases'));
}

public function agregarClases(Request $request, $modeloId)
{
    $request->validate([
        'nuevas_clases' => 'required|string'
    ]);

    $usuario = Auth::user();
    $modelo = $usuario->modelos()->findOrFail($modeloId);
    $nombreModelo = $modelo->nombre;

    $clases = array_map('trim', explode(',', $request->input('nuevas_clases')));

    $this->servicio->definirClasesYCarpetas($usuario, $nombreModelo, $clases);

    return back()->with('success', 'Clases añadidas correctamente.');
}
}