<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


$r = file_get_contents('https://infosicoes.com/contenido/paginas.admin/cron/cron.sincroniza_cursos.php');

echo " &nbsp; <i class='fa fa-thumbs-up'></i>";
