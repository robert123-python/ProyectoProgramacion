<?php
namespace App\Repositories;

use App\Models\Modelo;

class ModeloRepository
{
    public function crear(array $datos): Modelo
    {
        return Modelo::create($datos);
    }

    public function obtenerPorUsuario($userId)
    {
        return Modelo::where('user_id', $userId)->get();
    }

    public function buscarPorId($id): ?Modelo
    {
        return Modelo::find($id);
    }

    public function buscarPorNombreYUsuario($nombre, $userId)
{
    return Modelo::where('nombre', $nombre)
                 ->where('user_id', $userId)
                 ->first();
}
}
?>