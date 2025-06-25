<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\freeService;

class freeController extends Controller
{
    protected $servicio;

    public function __construct(freeService $servicio)
    {
        $this->servicio = $servicio;
    }

    public function count(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image'
        ]);

        try {
            $resultado = $this->servicio->contarObjetos(
                $request->file('imagen')
            );

            return back()->with([
                'message' => 'Procesamiento exitoso',
                'resultado' => $resultado['resultado']
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
