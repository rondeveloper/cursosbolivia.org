<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));

$data = post('data');


$id_contabilidad = 0;

$array_respuesta = array('result'=>'1','result_id_contabilidad'=>$id_contabilidad);

echo json_encode($array_respuesta);

