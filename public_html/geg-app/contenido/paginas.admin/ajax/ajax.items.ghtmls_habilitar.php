<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    exit;
}

/* modifica archivo fisico */
$archivo = fopen("../../configuracion/sw_ghtmls.dat", "w");
fwrite($archivo, '1');
fclose($archivo);
?>
<a ><span class="btn btn-xs btn-success active">HABILITADO</span></a>
<a >
    Estado: 
    <span id="box-ghtmls-d"></span>
    <span class="btn btn-xs btn-default pull-right" style="margin-top: 5px;" onclick="ghtmls_deshabilitar();">Deshabilitar</span>
</a>