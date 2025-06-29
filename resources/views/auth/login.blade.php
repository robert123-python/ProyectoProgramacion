<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <!-- Imagen superior -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo">

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario de login -->
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar sesión</button>
        </form>

        <!-- Enlace a registro -->
        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="{{ route('usuarios') }}">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>