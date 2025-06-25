<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Comandos por Video - Detección de Dedos</title>
    <style>
        body {
            margin: 0;
            background: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            font-family: sans-serif;
            position: relative;
        }
        video {
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 15px #0ff;
        }
        canvas {
            position: absolute;
            top: 0; left: 0;
            width: 400px;
            height: 300px;
            pointer-events: none;
        }
        #mensaje {
            margin-top: 10px;
            font-size: 1.2em;
        }
    </style>

    <!-- MediaPipe -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>

    <!-- Rutas de Laravel para redirección -->
    <script>
        const rutas = {
            inicio: "{{ route('Inicio') }}",
            crearModelos: "{{ route('modelos.crear') }}",
            identificarImagen: "{{ route('modelos.predecir') }}",
            contarObjetos: "{{ route('modelos.contarVista') }}"
        };
    </script>
</head>
<body>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas" width="400" height="300"></canvas>
    <div id="mensaje">Cámara activada. Levanta dedos para ejecutar comandos (1 a 5).</div>

<script>
    const videoElement = document.getElementById('video');
    const canvasElement = document.getElementById('canvas');
    const canvasCtx = canvasElement.getContext('2d');
    const mensaje = document.getElementById('mensaje');

    function ejecutarComandoPorNumero(numero) {
        if (!window.opener || window.opener.closed) {
            mensaje.textContent = "No se detectó ventana principal o está cerrada.";
            return;
        }

        mensaje.textContent = `Dedos levantados: ${numero}. Ejecutando comando...`;

        switch (numero) {
            case 1:
                window.opener.location.href = rutas.inicio;
                mensaje.textContent = "Ir a Inicio";
                break;
            case 2:
                window.opener.location.href = rutas.crearModelos;
                mensaje.textContent = "Crear Modelos";
                break;
            case 3:
                window.opener.location.href = rutas.identificarImagen;
                mensaje.textContent = "Identificar Imagen";
                break;
            case 4:
                window.opener.location.href = rutas.contarObjetos;
                mensaje.textContent = "Contar Objetos";
                break;
            case 5:
                const enlaceUsuario = window.opener.document.querySelector('a[href="/infoUser"]');
                if (enlaceUsuario) {
                    enlaceUsuario.click();
                    mensaje.textContent = "Abrir sección Usuario";
                } else {
                    mensaje.textContent = "Enlace de Usuario no encontrado.";
                }
                break;
            default:
                mensaje.textContent = "Número de dedos no válido para comando.";
        }
    }

    function contarDedos(landmarks) {
        let count = 0;
        if (landmarks[4].x < landmarks[3].x) count++;        // Pulgar
        if (landmarks[8].y < landmarks[6].y) count++;        // Índice
        if (landmarks[12].y < landmarks[10].y) count++;      // Medio
        if (landmarks[16].y < landmarks[14].y) count++;      // Anular
        if (landmarks[20].y < landmarks[18].y) count++;      // Meñique
        return count;
    }

    let ultimaEjecucion = 0;
    const intervalo = 3000;

    function onResults(results) {
        canvasCtx.save();
        canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
        canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);

        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            const landmarks = results.multiHandLandmarks[0];
            window.drawConnectors(canvasCtx, landmarks, window.HAND_CONNECTIONS, { color: '#00FF00', lineWidth: 5 });
            window.drawLandmarks(canvasCtx, landmarks, { color: '#FF0000', lineWidth: 2 });

            const dedosLevantados = contarDedos(landmarks);
            const ahora = Date.now();

            if (dedosLevantados > 0 && dedosLevantados <= 5 && (ahora - ultimaEjecucion > intervalo)) {
                ultimaEjecucion = ahora;
                ejecutarComandoPorNumero(dedosLevantados);
            } else if (dedosLevantados === 0) {
                mensaje.textContent = "No se detectan dedos levantados.";
            }
        } else {
            mensaje.textContent = "Esperando detección de mano...";
        }

        canvasCtx.restore();
    }

    const hands = new Hands({
        locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
    });

    hands.setOptions({
        maxNumHands: 1,
        modelComplexity: 1,
        minDetectionConfidence: 0.7,
        minTrackingConfidence: 0.7
    });

    hands.onResults(onResults);

    const camera = new Camera(videoElement, {
        onFrame: async () => {
            await hands.send({ image: videoElement });
        },
        width: 400,
        height: 300
    });

    camera.start();
</script>
</body>
</html>


