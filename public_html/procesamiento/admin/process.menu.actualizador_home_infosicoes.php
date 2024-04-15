<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


$resp = file_get_contents("https://www.infosicoes.com/contenido/paginas.admin/cron/cron.htmls.actualizador.php");

echo ' : <i class="fa fa-thumbs-up fa-fw sidebar-nav-icon"></i>';