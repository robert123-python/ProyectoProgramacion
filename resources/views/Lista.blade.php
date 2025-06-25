<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil General</title>
    <link rel="stylesheet" href="{{'css/Listacss.css'}}">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="salir">
        <a href="/inicio">  <img src="{{'../images/atras.png'}}">  </a>
        <div>
            <center><h2> salir</h2></center>
        </div>
    </div>
    <div class="List">        
        <h1>LISTA</h1> 
            @foreach ($usuarios as $usuario)
                <p>Nombre: {{ $usuario->name }} <a href="#"><i class="fas fa-trash"></i></a></p>
                <hr>
            @endforeach             
        <a href="/infoUser"><button type="submit">EDITAR</button><a>                
        <button type="submit">ELIMINAR</button>
        <a href="/usuarios/crear"><button type="submit">AGREGAR</button></a>
    </div>
</body>
</html>