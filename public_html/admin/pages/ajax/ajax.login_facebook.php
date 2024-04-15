<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {


    $response = json_decode(stripcslashes(post('response')));

    echo print_r($response,true);
    
    
    
    
}
