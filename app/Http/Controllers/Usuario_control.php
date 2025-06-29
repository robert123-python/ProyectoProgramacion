<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Usuario_control extends Controller
{
    //
    public function crear(){
        return view('usuarios');
    }
    public function atras(){
         return view('/Lista_us');
    }

    public function guardar(Request $requerido) {
        $requerido->validate([
            'name'=> 'required',
            'email' => ['required', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/', 'unique:users'],
            'password' => 'required|min:6',

        ]);

        User::create([
            'name' => $requerido->name,
            'email' => $requerido->email,
            'password' => Hash::make($requerido->password),
        ]);
        return redirect()->back()->with('success', 'Usuario creado correctamente');
    }
}
