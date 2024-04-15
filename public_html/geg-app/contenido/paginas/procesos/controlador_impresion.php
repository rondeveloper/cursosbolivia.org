<?php

error_reporting(1);

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


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
