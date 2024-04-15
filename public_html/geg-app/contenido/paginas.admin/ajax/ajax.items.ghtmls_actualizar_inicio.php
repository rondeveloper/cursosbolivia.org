<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    exit;
}

$resp = file_get_contents("https://cursos.bo/contenido/paginas.admin/cron/cron.genera_htmls.php?page=inicio");
?>
 .. <i class="fa fa-thumbs-up"></i>