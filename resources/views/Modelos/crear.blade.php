@extends('layouts.menu')

@section('title', 'Crear Modelo')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/modeloscss/crear.css') }}">
</head>

<div class="center-wrapper">
    <div class="container">
        <h2>Nuevo Modelo</h2>

        @if ($errors->any())
            <div class="alert">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('modelos.definirClases') }}" >
            @csrf

            <div class="form-group">
                <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
                <input type="text" name="nombre_modelo" id="nombre_modelo" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="clases" class="form-label">Clases (separadas por comas)</label>
                <input type="text" name="clases" id="clases" class="form-control" placeholder="gato, perro, pajaro" required>
            </div>

            <button type="submit" class="btn">Definir clases y continuar</button>
        </form>
    </div>
</div>

<script>
    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('preview');
        previewContainer.innerHTML = '';

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection