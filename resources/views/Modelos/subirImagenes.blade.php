@extends('layouts.menu')

@section('title', 'Subir imágenes por clase')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/modeloscss/crear.css') }}">
</head>

<div class="center-wrapper">
    <div class="container">
        <h2>Subir imágenes para el modelo: <strong>{{ $nombre_modelo }}</strong></h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @foreach ($clases as $clase)
    <div class="upload-section">
        <h4>Clase: <span style="color: #007bff">{{ $clase }}</span></h4>
        <form method="POST" action="{{ route('modelos.subirImagenes') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="nombre_modelo" value="{{ $nombre_modelo }}">
            <input type="hidden" name="clase" value="{{ $clase }}">

            <input type="file" name="imagenes[]" multiple required accept="image/*">
            <button type="submit" class="btn">Subir imágenes</button>
        </form>
    </div>
    <hr>
@endforeach

<form method="POST" action="{{ route('modelos.entrenarFinal') }}">
    @csrf
    <input type="hidden" name="nombre_modelo" value="{{ $nombre_modelo }}">
    <button type="submit" class="btn btn-primary">Entrenar Modelo</button>
</form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Ocultar alertas después de 3 segundos
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        setTimeout(() => alert.classList.add("fade-out"), 3000);
    });

    // Previsualización de imágenes
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener("change", function () {
            const previewContainer = document.createElement("div");
            previewContainer.classList.add("image-preview");

            if (this.files.length) {
                for (const file of this.files) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }

                // Limpiar previsualización anterior
                const oldPreview = this.parentElement.querySelector(".image-preview");
                if (oldPreview) oldPreview.remove();

                this.parentElement.appendChild(previewContainer);
            }
        });
    });

    // Loader al enviar formulario
    document.querySelectorAll("form").forEach(form => {
        const loader = document.createElement("div");
        loader.className = "loader";
        loader.innerText = "Subiendo, por favor espera...";
        form.appendChild(loader);

        form.addEventListener("submit", () => {
            loader.style.display = "block";
        });
    });
});
</script>
@endsection