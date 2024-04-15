<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


/* modifica archivo fisico */
$archivo = fopen("../../contenido/configuracion/sw_ghtmls.dat", "w");
fwrite($archivo, '0');
fclose($archivo);
?>
<a ><span class="btn btn-xs btn-default active">NO HABILITADO</span></a>
<a >
    Estado: 
    <span id="box-ghtmls-h"></span>
    <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_habilitar();">Habilitar</span>
</a>