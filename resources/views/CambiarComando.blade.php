@extends('layouts.menu')

@section('title', 'CambiarComando')

@section('content')

<link rel="stylesheet" href="{{'css/CC.css'}}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<body>
    <div class="container">
        <div class="card">
            <h2>Datos Actuales</h2>
            <table>
                <thead>
                    <tr>
                        <th>Comando</th>
                        <th>Acción</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datosC as $campo => $valor)
                    <tr>
                        <td>{{ $campo }}</td>
                        <td>{{ $valor }}</td>
                        <td>
                            <button class="btn editar" data-campo="{{ $campo }}">Editar</button>                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card hidden" id="formulario-edicion">
            <h2>Editar Campo</h2>
            <form action="{{ route('actualizar.xml') }}" method="POST">
                @csrf
                <input type="text" id="comando" name="comando" readonly>
                <input type="text" id="nuevo_valor" name="nuevo_valor" placeholder="Ingrese el nuevo comando">
                <button type="submit" class="btn">Guardar Cambios</button>
                <button type="button" class="btn cancelar">Cancelar</button>
            </form>
        </div>
        
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('nuevo_valor');

            input.addEventListener('input', function () {
                // Eliminar todo lo que no sean letras y pasar a minúsculas
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toLowerCase();
            });
        });
        
        $(document).ready(function() {
            // Mostrar formulario al hacer clic en Editar
            $('.editar').click(function() {
                const campo = $(this).data('campo');
                
                $('#comando').val(campo);
                $('#nuevo_valor').val(''); // inicia en blanco o pon un valor si lo tienes
                $('#formulario-edicion').removeClass('hidden');
                
                // Desplazarse al formulario
                $('html, body').animate({
                    scrollTop: $('#formulario-edicion').offset().top - 20
                }, 500);
            });

            // Cancelar edición
            $('.cancelar').click(function() {
                $('#formulario-edicion').addClass('hidden');
            });
        });

    </script>
</body>
</html>
@endsection