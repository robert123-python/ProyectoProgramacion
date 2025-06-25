<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logger extends Controller
{
    //
    public function login_view(){
       return view('auth.login');
    }
    public function login(Request $request){
        $dato = $request->only('email','password');
        if(Auth::attempt($dato)){
            $request->session()->regenerate();
            return redirect()->intended('/inicio');
        }
        return back()->withErrors(['email' => 'Email o contrasena incorrecta']);

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');

    }

}
