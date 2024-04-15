<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {


    $response = json_decode(stripcslashes(post('response')));

    echo print_r($response,true);
    
    
    
    
}
