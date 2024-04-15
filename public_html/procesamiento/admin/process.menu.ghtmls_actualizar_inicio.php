<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


$resp = file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=inicio");
?>
 .. <i class="fa fa-thumbs-up"></i>