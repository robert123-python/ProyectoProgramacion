<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Monitoreo')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  @stack('styles')
</head>
<encabezado class="encabezado">
 <h2 style="font-family: 'Playfair Display', serif;">Mentes Brillantes</h2>

  <header>
    
    <div class="usuario">
      <button class="boton-usuario">👤 Usuario</button>
      <div class="info-usuario">
        <p><strong>Estado:</strong> 🟢 En línea</p>
        <p><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Correo:</strong> {{ Auth::user()->email }}</p>
      </div>
    </div>
  </header>

  <main style="padding: 20px;">
  </main>

</encabezado>
</html>



