@extends('layouts.menu')

@section('title', 'Contar Objetos')

@section('content')
<head>
     <link rel="stylesheet" href="{{ asset('css/modeloscss/contar.css') }}">
</head>
<body>
    <div class="center-wrapper">
        <div class="container">
            <h2>Contar Objetos con un Modelo</h2>

            @if(session('success'))
             <div class="alert alert-success">
             {{ session('success') }}
             <br>
              <a href="{{ route('estadisticas.ver') }}" class="btn btn-primary" style="margin-top: 10px;">
            Ver Estadísticas
            </a>
            </div>
            @endif

            @if(session('resultado'))
            <div class="alert alert-success">
             <h5>Resultado:</h5>
                <pre>{{ session('resultado') }}</pre>

            <form action="{{ route('conteos.actualizar') }}" method="POST" style="margin-top: 20px;">
              @csrf
               {{-- Aquí debes enviar el id del conteo guardado --}}
               <input type="hidden" name="conteo_id" value="{{ session('conteo_id') ?? '' }}">

               <label for="conteo_manual">Ingrese el conteo manual:</label>
               <input type="number" name="conteo_manual" id="conteo_manual" min="0" required>

               <button type="submit" class="btn" style="margin-top: 10px;">Guardar Conteo Manual</button>
             </form>
            </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('modelos.contar') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="modelo_id">Selecciona el modelo:</label>
                    <select name="modelo_id" id="modelo_id" required>
                        <option value="">-- Elegir modelo --</option>
                        @foreach($modelos as $modelo)
                            <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagen">Sube una imagen para contar objetos:</label>
                    <input type="file" name="imagen" id="imagen" required>
                </div>

                <button type="submit" class="btn">Contar Objetos</button>
            </form>
        </div>
    </div>
</body>
@endsection
