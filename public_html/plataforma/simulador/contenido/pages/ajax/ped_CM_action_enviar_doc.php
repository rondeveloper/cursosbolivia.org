<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$cuce = '21-1712-00-1116922-1-1';
query("UPDATE simulador_documentos SET estado='2',fecha_operacion=NOW() WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
