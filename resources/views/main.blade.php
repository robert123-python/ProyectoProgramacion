@extends('layouts.menu')

@section('title', 'Inicio')

@section('content')
<style>
    .main-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 20px;
        background-color: #f4f6f8;
    }

    .intro-card {
        max-width: 1000px;
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .intro-card h1 {
        color: #333;
        margin-bottom: 20px;
    }

    .intro-card p {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.6;
    }

    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
    }

    .feature-card {
        width: 280px;
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .feature-card img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .feature-card h3 {
        margin-bottom: 10px;
        color: #222;
    }

    .feature-card p {
        font-size: 0.95rem;
        color: #666;
    }
</style>

<div class="main-container">
    <div class="intro-card">
        <h1>Bienvenido a nuestro Proyecto de Procesamiento de Imágenes</h1>
        <p>
            Este sistema ha sido diseñado para facilitar tareas avanzadas de análisis de imágenes, utilizando inteligencia artificial y tecnologías modernas. 
            Entre sus principales funciones se incluyen la creación de modelos personalizados, el conteo de objetos en imágenes, la identificación automática de contenidos visuales y la integración de un asistente de voz interactivo.
        </p>
    </div>

    <div class="features">
        <div class="feature-card">
            <img src="https://img.icons8.com/color/100/ai.png" alt="Crear modelo">
            <h3>Crear Modelo</h3>
            <p>Entrena modelos personalizados para reconocer objetos específicos en imágenes usando aprendizaje automático.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/color/100/image.png" alt="Contar objetos">
            <h3>Contar Objetos</h3>
            <p>Detecta y cuenta automáticamente objetos en imágenes cargadas por el usuario.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/color/100/visual-recognition.png" alt="Identificación de imágenes">
            <h3>Identificación</h3>
            <p>Reconoce el contenido principal de una imagen y proporciona una descripción de lo que muestra.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/color/100/microphone--v1.png" alt="Asistente de voz">
            <h3>Asistente de Voz</h3>
            <p>Interacción por voz para ejecutar funciones del sistema de forma accesible y dinámica.</p>
        </div>
        <div class="feature-card">
            <img src="https://img.icons8.com/color/100/microphone--v1.png" alt="Asistente de voz">
            <h3>Asistente de Voz</h3>
            <p>Interacción por voz para ejecutar funciones del sistema de forma accesible y dinámica.</p>
        </div>
    </div>
</div>


@endsection
