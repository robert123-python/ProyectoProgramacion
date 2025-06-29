<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Logger;
use App\Http\Controllers\Usuario_control;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Opencv\ModeloController;
use App\Http\Controllers\conteoController;
use App\Http\Controllers\freeController;
use Illuminate\Http\Request;

Route::get('/login/log', [Logger::class, 'login_view'])->name('auth.login');
Route::post('/login', [Logger::class, 'login']);
Route::post('/logout', [Logger::class, 'logout'])->name('logout');


Route::get('/comandos-video', function () {
        return view('comandosVideo');
    })->name('comandos.video');

//use App\Http\Controllers\freeController;
Route::post('/image-count', [freeController::class, 'count'])->name('image.count');
    

//use App\Http\Controllers\Usuario_control;
Route::get('/usuarios/crear', [Usuario_control::class, 'crear'])->name('usuarios');
Route::post('/usuarios', [Usuario_control::class, 'guardar'])->name('users.store');
//Route::post()

//use App\Http\Controllers\UsuarioController;
Route::get('/Lista', [UsuarioController::class, 'mostrarUsuarios'])->name('Lista');
Route::get('/editPerfil', [UsuarioController::class, 'infoEdit']);
Route::get('/infoUser', [UsuarioController::class, 'info']);

//use App\Http\Controllers\Opencv\ModeloController;
Route::middleware(['auth'])->group(function () {
    Route::get('/modelo/crear', [ModeloController::class, 'crear'])->name('modelos.crear');
    Route::post('/modelos/definir-clases', [ModeloController::class, 'definirClases'])->name('modelos.definirClases');
    Route::get('/modelos/subir-imagenes-clase', [ModeloController::class, 'mostrarFormularioSubida'])->name('modelos.formularioSubida');
    Route::post('/modelos/subir-imagenes-clase', [ModeloController::class, 'subirImagenes'])->name('modelos.subirImagenes');
    Route::post('/modelos/entrenar', [ModeloController::class, 'entrenarFinal'])->name('modelos.entrenarFinal');
    Route::get('/modelos/{modelo}/clases', [ModeloController::class, 'seleccionarClase'])->name('modelos.seleccionarClase');
    Route::post('/modelos/{modelo}/agregar-clases', [ModeloController::class, 'agregarClases'])->name('modelos.agregarClases');
    Route::post('/modelos/subir-imagenes', [ModeloController::class, 'subirImagenes'])->name('modelos.subirImagenes');
    Route::post('/modelo/guardar', [ModeloController::class, 'guardar'])->name('modelos.guardar');
    Route::get('/modelo/predecir', [ModeloController::class, 'predecirVista'])->name('modelos.predecirVista');
    Route::post('/modelo/predecir', [ModeloController::class, 'predecir'])->name('modelos.predecir');
    Route::post('/modelos/{modeloId}/reentrenar', [ModeloController::class, 'reentrenar'])->name('modelos.reentrenar');
    Route::get('/modelos', [ModeloController::class, 'index'])->name('modelos.index');
    Route::delete('/modelos/{modeloId}', [ModeloController::class, 'eliminar'])->name('modelos.eliminar');
    Route::get('/modelo/contar', [ModeloController::class, 'contarVista'])->name('modelos.contarVista');
    Route::post('/modelos/contar', [ModeloController::class, 'contarObjetos'])->name('modelos.contar');
    
});

//use App\Http\Controllers\conteoController;
Route::middleware(['auth'])->group(function () {
    Route::post('/conteos/actualizar', [conteoController::class, 'actualizarManual'])->name('conteos.actualizar');
    Route::get('/estadisticas', [conteoController::class, 'verEstadisticas'])->name('estadisticas.ver');
});

//use Illuminate\Http\Request;
Route::get('/CambiarComando', function () {
    return view('CambiarComando');
});
Route::post('/actualizar-xml', function (Request $request) {
    $request->validate([
        'comando' => 'required|string',
        'nuevo_valor' => ['required', 'regex:/^[a-záéíóúñ\s]+$/i'],
    ]);
    
    $xmlPath = storage_path('app/datos.xml');
    $xml = new DOMDocument();
    $xml->load($xmlPath);
    
    // Actualizar solo el campo seleccionado
    $campo = $xml->getElementsByTagName($request->comando)->item(0);
    if (!$campo) {
        return back()->withErrors(['comando' => 'El campo no existe en el XML.']);
    }
    $campo->nodeValue = $request->nuevo_valor;
    
    $xml->save($xmlPath);
    
    return back()->with([
        'xml_actualizado' => $xml->saveXML()
    ]);
})->name('actualizar.xml');


Route::get('/', function () {
    return view('free');
})->name('home');

Route::get('/inicio', function () {
    return view('main');
})->middleware('auth')->name('Inicio');

Route::get('/dashboard', function () {
    return redirect('/inicio');
});

//Route::view('dashboard', 'dashboard')
//    ->middleware(['auth', 'verified'])
//    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
