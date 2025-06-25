<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesamiento de Imágenes - Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
   

    <!-- Barra superior -->
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-600">Mentes Brillantes</h1>
            <a href="{{ url('/login/log') }}" class="text-blue-600 font-semibold hover:underline">Iniciar Sesión</a>
    </nav>

     @if(session('message'))
    <div class="mt-6 p-4 bg-green-100 text-green-800 rounded">
        <strong>{{ session('message') }}</strong><br>
        <pre class="mt-2">{{ session('resultado') }}</pre>
    </div>
@endif

@if(session('error'))
    <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
        <strong>Error:</strong> {{ session('error') }}
    </div>
@endif
    <!-- Contenido principal -->
    <div class="container mx-auto mt-10 px-4">
        <h2 class="text-3xl font-bold mb-4">Procesamiento Inteligente de Imágenes</h2>
        <p class="mb-6 text-gray-700">
            Nuestra plataforma te permite analizar imágenes con inteligencia artificial. Regístrate para acceder a múltiples herramientas como detección de objetos, clasificación y más.
        </p>

        <!-- Botón de prueba gratuita -->
        <button onclick="openModal()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Prueba Gratuita: Contar Perros o Gatos
        </button>

        @auth
            <!-- Opciones adicionales para usuarios registrados -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-2">Opciones Premium</h3>
                <ul class="list-disc list-inside text-gray-800">
                    <li>Detección avanzada de razas</li>
                    <li>Procesamiento por lotes</li>
                    <li>Historial de imágenes procesadas</li>
                </ul>
            </div>
        @endauth
    </div>

    <!-- Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <button onclick="closeModal()" class="absolute top-2 right-4 text-gray-600 hover:text-red-500 text-xl">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Sube una imagen</h3>
            <form action="{{ route('image.count') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="imagen" accept="image/*" class="mb-4" required>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Contar</button>
</form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
</body>
</html>