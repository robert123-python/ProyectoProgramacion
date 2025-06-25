<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Asegúrate de que el índice 'data' exista antes de acceder a él
        if (isset($_POST['data'])) {
    $nombre=$_POST['data'];
    $nombre1 = ",".$nombre." ";
    if (isset($_POST['Apellido'])) {
        $apellido=$_POST['Apellido'];
    $fp = fopen('Nombres.txt', 'a');
    fwrite($fp,$nombre1);
    fwrite($fp,$apellido);
    fclose($fp);
        }else{
            fwrite($fp,$nombre1);  
            fclose($fp);      
    }   
}   
    }
?>