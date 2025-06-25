<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
@include('layouts.monitoreo')
<body>
    
    <div class="wrapper">
    <div class="sidebar">
        <form id ="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
       
        <a href="{{ route('Inicio') }}"><i class="fa fa-home"></i> Ir a Inicio</a>
        <a href="{{ route('modelos.crear') }}"><i class="fa fa-object-group"></i> Crear Modelos</a>
        <a href="{{ route('modelos.predecir') }}"><i class="fa-solid fa-image"></i> <i class="fa-solid fa-magnifying-glass"></i> Identificar Imagen</a>
        <a href="{{ route('modelos.index') }}"><i class="fa-solid fa-cubes"></i> Mis Modelos</a>
        <a href="{{ route('modelos.contarVista') }}"><i class="fa-solid fa-circle-nodes"></i> Contar Objetos</a>
       
        <a href="/infoUser"><i class="fas fa-user"></i> Usuario</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-door-closed"></i> Cerrar Sesion </a>
        <a id="btn-asistente" class="nav-link" href="#"><i class="fas fa-microphone"></i> Asistente de Voz</a>
        <a id="btn-video" href="#" class="nav-link"><i class="fas fa-video"></i> Comandos por Video</a>
        <div class="comando" >
            <a onclick="abrir()">Cambiar Comando</a>        
            <div class="menu" id="menu">
                    <input type="text" id="comandoantiguo" placeholder="Introdusca el antiguo comando">
                    <input type="text" id="comandonuevo" placeholder="introdusca el nuevo comando">
                    <button class="cancelar" type="submit">Guardar</button>
                    <button class="cancelar" type="button" onclick="cerrar()">Cancelar</button>
            </div>
        </div>
        <!--<div class="dropdown">
            <a href="#">Servicios ▸</a>
            <div class="submenu">
                <a href="/servicio1">Servicio 1</a>
                <a href="/servicio2">Servicio 2</a>
                <a href="/servicio3">Servicio 3</a>
            </div>
        </div>-->

        
    </div>
    <div class="main-content">
        @yield('content')
    </div>
    </div>
    
    @include('layouts.base')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnAsistente = document.getElementById("btn-asistente");

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert("Tu navegador no soporta reconocimiento de voz.");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "es-ES";
    recognition.continuous = false;
    recognition.interimResults = false;

    function hablar(texto) {
        const synth = window.speechSynthesis;
        const utterance = new SpeechSynthesisUtterance(texto);
        utterance.lang = 'es-ES';
        synth.speak(utterance);
    }

    recognition.onresult = function (event) {
        const comando = event.results[0][0].transcript.toLowerCase();
        console.log("Comando detectado:", comando);

        const textoParaAnalizar = "Este es un texto de ejemplo para contar objetos y verificar si existen palabras.";

        // ✅ 1. Comandos de navegación primero (contar objetos)
        if (comando.includes("contar objetos")) {
            hablar("Redirigiendo a contar objetos");
            window.location.href = "{{ route('modelos.contarVista') }}";

        } else if (comando.includes("usuarios") || comando.includes("usuario")) {
            hablar("Abriendo la sección de usuario");
            window.location.href = "/infoUser";

        } else if (comando.includes("crear modelo")) {
            hablar("Redirigiendo a crear modelo");
            window.location.href = "{{ route('modelos.crear') }}";

        } else if (comando.includes("identificar imagen")) {
            hablar("Redirigiendo a identificar imagen");
            window.location.href = "{{ route('modelos.predecir') }}";

        } else if (comando.includes("mis modelos")) {
            hablar("Mostrando tus modelos");
            window.location.href = "{{ route('modelos.index') }}";

        } else if (comando.includes("inicio")) {
            hablar("Volviendo al inicio");
            window.location.href = "{{ route('Inicio') }}";

        } else if (comando.includes("cerrar sesión") || comando.includes("cerrar sesion")) {
            hablar("Cerrando sesión");
            document.getElementById('logout-form').submit();

        // ✅ 2. Comandos que usan la librería PHP (contar palabra o existencia)
        } else if (comando.includes("contar") || comando.includes("existe")) {
             fetch('/procesarComando.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `comando=${encodeURIComponent(comando)}&texto=${encodeURIComponent(textoParaAnalizar)}`
                })
            .then(response => response.json())
            .then(data => {
                hablar(data.mensaje);
            })
            .catch(error => {
                console.error("Error al procesar comando:", error);
                hablar("Hubo un error al procesar el comando.");
            });

        // ❌ 3. Comando no reconocido
        } else {
            hablar("No reconozco ese comando");
            alert("Comando no reconocido: " + comando);
        }
    };

    recognition.onerror = function (event) {
        console.error("Error de reconocimiento:", event.error);
        alert("Error de reconocimiento de voz: " + event.error);
    };

    btnAsistente.addEventListener("click", function (e) {
        e.preventDefault();
        hablar("Escuchando.");
        recognition.start();
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const btnVideo = document.getElementById('btn-video');
    btnVideo.addEventListener('click', function(e) {
        e.preventDefault();
        window.open(
            "{{ route('comandos.video') }}",
            "ComandosVideo",
            "width=450,height=500,resizable=yes,scrollbars=yes"
        );
    });
});



</script>
<script>
    const cambiarcomando = document.getElementById('menu');

        function abrir() {
            cambiarcomando.style.display = 'block';
        }

        function cerrar() {
            cambiarcomando.style.display = 'none';
        }

        document.getElementById('formComando').addEventListener('submit', function(e){
            cerrar();
        });
        </script>

</body>
</html>