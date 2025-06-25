<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil General</title>
    <link rel="stylesheet" href="{{'css/perfil.css'}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
</head>
<body>
    <div class="salir">
        <a href="/inicio"><img src="{{'../images/atras.png'}}"> </a>
        <div>
            <center><h2> salir</h2></center>
        </div>
    </div>
    <div class="profile">
        <div class="photo">
            <img src="{{'../images/logo.png'}}"> 
        </div>
        
        <div class="profile-info">
            <i class="fas fa-user"></i><h1> {{ Auth::user()->name }}</h1>            
            <div class="details">
                <i class="fas fa-envelope"></i><span>  Correo: {{ Auth::user()->email }}</span></p>
                <i class="fas fa-house"></i><span>Ciudad: Cochabamba</span></p>
                <i class="fas fa-building-columns"></i><span>Univesidad:San Simon</span>
            </div>
            <div class="edit">
                    <a href="/editPerfil"><img src="{{'../images/EditImg.png'}}"> </a>
            </div>            
        </div>
    </div>
</body>
</html>