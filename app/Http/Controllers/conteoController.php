<?php

namespace App\Http\Controllers;
use App\Models\Conteo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class conteoController extends Controller
{
public function actualizarManual(Request $request)
{
    $request->validate([
        'conteo_id' => 'required|exists:conteos,id',
        'conteo_manual' => 'required|integer|min:0'
    ]);

    $conteo = Conteo::find($request->conteo_id);
    $conteo->conteo_manual = $request->conteo_manual;
    $conteo->save();

    return redirect()->back()->with('success', 'Conteo manual guardado correctamente.');
}

public function verEstadisticas()
{
    $conteos = Conteo::select('modelo_id', DB::raw('SUM(conteo_automatico) as total_automatico'), DB::raw('SUM(conteo_manual) as total_manual'))
        ->with('modelo')
        ->groupBy('modelo_id')
        ->get();

    return view('estadisticas.index', compact('conteos'));
}
}