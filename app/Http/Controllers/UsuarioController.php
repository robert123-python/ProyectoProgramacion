<?php

namespace App\Http\Controllers;

use App\Models\ListaM; // Usamos el modelo Lista que apunta a la tabla user

class UsuarioController extends Controller
{
    public function mostrarUsuarios()
    {
        $usuarios = ListaM::all(); // Obtenemos todos los registros de la tabla 'user'
        return view('Lista', compact('usuarios')); // Pasamos los datos a la vista 'Lista'
    }

    public function info()
    {
        $usuario = ListaM::find(1); // Obtenemos todos los registros de la tabla 'user'
        return view('infoUser', compact('usuario')); // Pasamos los datos a la vista 'Lista'
    }

    public function infoEdit()
    {
        $usuario = ListaM::find(1); // Obtenemos todos los registros de la tabla 'user'
        return view('editPerfil', compact('usuario')); // Pasamos los datos a la vista 'Lista'
    }

}



// read, get you y git  you, full stack, free lanser, ia y automatizacion de procesos, 