<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* recepcion de datos POST */
$Carrito = new Carrito();
?>
Total costo: <?= $Carrito->getCostoTotal() ?> BS
<br>
Total cursos: <?= $Carrito->totalCursos() ?>