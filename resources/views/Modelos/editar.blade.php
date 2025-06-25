@extends('layouts.menu')

@section('title', 'Subir imágenes')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/modeloscss/modelos.css') }}">
</head>
<div class="container mt-4">
    <h2>Subir imágenes al modelo: {{ $modelo->nombre }}</h2>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Subir imágenes --}}
    <form action="{{ route('modelos.subirImagenes') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="hidden" name="nombre_modelo" value="{{ $modelo->nombre }}">

        <div class="form-group mb-3">
            <label for="clase">Selecciona una clase:</label>
            <select name="clase" class="form-control" required>
                @foreach($clases as $clase)
                    <option value="{{ $clase }}">{{ $clase }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="imagenes">Selecciona las imágenes:</label>
            <input type="file" name="imagenes[]" multiple class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Subir imágenes</button>
    </form>

    {{-- Agregar nuevas clases --}}
    <hr>
    <h4>Añadir nuevas clases</h4>
    <form action="{{ route('modelos.agregarClases', $modelo->id) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nuevas_clases">Nombres de las clases (separados por comas):</label>
            <input type="text" name="nuevas_clases" class="form-control" placeholder="Ej: Gato, Perro, Ave" required>
        </div>
        <button type="submit" class="btn btn-secondary">Agregar clases</button>
    </form>

    {{-- Entrenamiento final --}}
    <hr>
    <form action="{{ route('modelos.entrenarFinal') }}" method="POST">
        @csrf
        <input type="hidden" name="nombre_modelo" value="{{ $modelo->nombre }}">
        <button type="submit" class="btn btn-success mt-4">Reentrenar modelo</button>
    </form>

    <a href="{{ route('modelos.index') }}" class="btn btn-link mt-3">Volver</a>
</div>
@endsection