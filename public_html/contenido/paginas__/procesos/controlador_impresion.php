<?php

error_reporting(1);

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



$data = get('seccion');

$cleardata = decrypt($data);

$ar1 = explode("/", $cleardata);

$page_impresion = $ar1[0];

if(file_exists("impresiones/$page_impresion.php")){
    include_once "impresiones/$page_impresion.php";
}else{
    echo "Acceso denegado!";
}

?>
