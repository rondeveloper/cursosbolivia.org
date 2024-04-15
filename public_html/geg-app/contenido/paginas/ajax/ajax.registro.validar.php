<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* recepcion de datos POST */
$user_nombre = post('user_nombre');

echo "0413216513".$user_nombre;