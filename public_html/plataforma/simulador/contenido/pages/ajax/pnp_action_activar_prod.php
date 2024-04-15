<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];

query("UPDATE simulador_prods SET estado='1' WHERE id='$id_prod' ");

$_SESSION['id_prod__CURRENTADD'] = '0';

$qrpr1 = query("SELECT id_cat FROM simulador_prods WHERE id='$id_prod' ");
$qrpr2 = fetch($qrpr1);

echo $qrpr2['id_cat'];