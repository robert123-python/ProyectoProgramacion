<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="{{'css/perfil.css'}}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="salir">
        <a href="/infoUser"><img src="{{'../images/atras.png'}}"> </a>
        <div>
            <center><h2> salir</h2></center>
        </div>
    </div>
    <div class="profile">
        <div class="photo">
            <img src="{{'../images/logo.png'}}">
        </div>
        
        <div class="profile-info">
            <i class="fas fa-circule-user"></i><h1>{{ Auth::user()->name }}</h1>            
            <div class="details">                
                <i class="fas fa-circle-user"></i><span>
                    <input type="user" name="user" placeholder=" Nombre o apodo" required>
                </span></p>
                <i class="fas fa-envelope"></i><span>
                    <input type="email" name="email" placeholder=" Correo electrónico" required>
                </span></p>
                <i class="fas fa-lock"></i><span>
                    <input type="password" name="password" placeholder=" Contraseña" required>
                </span>
            </div>
            <div class="editGuard">
                    <button onclick="window.location.href='/infoUser'">CANCELAR</button>
                    <button onclick="window.location.href='/infoUser'">GUARDAR</button>
            </div>            
        </div>
    </div>
</body>
</html>