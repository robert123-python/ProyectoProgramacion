@extends('layouts.menu')

@section('title', 'Identificar Imagen')

@section('content')    
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    .container {
        max-width: 600px;
        margin: auto;
        background: #ffffffcc;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(4px);
        transition: all 0.3s ease-in-out;
    }

    .center-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        box-sizing: border-box;
    }

    h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
        color: #333;
    }

    .form-label {
        font-weight: bold;
        display: block;
        margin-top: 20px;
        margin-bottom: 8px;
        color: #444;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 15px;
        background: #fdfdfd;
        transition: 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
        background: #fff;
    }

    .btn {
        margin-top: 30px;
        padding: 14px 24px;
        background: linear-gradient(to right, #007bff, #0056b3);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        transition: background 0.3s, transform 0.2s;
    }

    .btn:hover {
        background: linear-gradient(to right, #218838, #19692c);
        transform: scale(1.02);
    }

    .alert {
        padding: 15px 20px;
        background: #f8d7da;
        color: #721c24;
        margin-top: 20px;
        border-radius: 10px;
        border-left: 5px solid #c82333;
    }

    input[type="file"]::file-selector-button {
        background: #0056b3;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        margin-right: 12px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="file"]::file-selector-button:hover {
        background: #0056b3;
    }

    #preview {
        margin-top: 15px;
        text-align: center;
    }

    #preview img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    /* Modal mejorado */
    .modal {
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        display: none;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        animation: fadeIn 0.3s ease-out forwards;
    }

    .modal .btn {
        margin-top: 20px;
        background: #dc3545;
    }

    .modal .btn:hover {
        background: #bd2130;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<div class="center-wrapper">
    <div class="container">
        <h2>Realizar Predicción</h2>

        @if ($errors->any())
            <div class="alert">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('modelos.predecir') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="modelo_id" class="form-label">Seleccionar Modelo</label>
            <select name="modelo_id" id="modelo_id" class="form-control" required>
                @foreach ($modelos as $modelo)
                    <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                @endforeach
            </select>

            <label for="imagen" class="form-label">Imagen a Predecir</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required onchange="mostrarPreview(event)">

            <div id="preview"></div>

            <button type="submit" class="btn">🔍 Predecir</button>
        </form>

        {{-- Modal de resultado --}}
        <div class="modal" id="resultadoModal" role="dialog" aria-labelledby="resultadoTexto" aria-modal="true">
            <div class="modal-content">
                <h2>Resultado de la Predicción</h2>
                <div class="resultado" id="resultadoTexto" style="white-space: pre-wrap;"></div>
                <button class="btn" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarPreview(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function cerrarModal() {
        document.getElementById('resultadoModal').style.display = 'none';
    }

    window.addEventListener('DOMContentLoaded', () => {
        const resultado = @json(session('resultado'));
        if (resultado && resultado.trim() !== "") {
            document.getElementById('resultadoTexto').textContent = resultado;
            document.getElementById('resultadoModal').style.display = 'flex';
        }
    });
</script>
@endsection