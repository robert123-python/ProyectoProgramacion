@extends('layouts.menu')

@section('title', 'Mis Modelos')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/modeloscss/modelos.css') }}">
</head>
<div class="center-wrapper">
    <div class="container">
        <h2>Mis Modelos Entrenados</h2>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="alert alert-success fade-out">{{ session('success') }}</div>
        @endif

        {{-- Mensajes de error --}}
        @if($errors->any())
            <div class="alert alert-danger fade-out">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($modelos->isEmpty())
            <p>No tienes modelos entrenados aún.</p>
        @else
            @foreach($modelos as $modelo)
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">{{ $modelo->nombre }}</h3>

                        <a href="{{ route('modelos.seleccionarClase', $modelo->id) }}" class="btn btn-primary mb-2">Gestionar clases / imágenes</a>

                        <form action="{{ route('modelos.eliminar', $modelo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este modelo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar modelo</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection


