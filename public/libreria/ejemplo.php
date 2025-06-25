<?php 
require_once ('libreria.php');
$prueba=new Libreria();

$rutaGram = 'gram.txt';
$prueba->crearGramatica($rutaGram);

$rutaArchivo = 'entrada.txt';
$prueba->leerArchivoEntrada($rutaArchivo);
$prueba->contarPalabraBuscada();
$prueba->esperarComando();