<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$resp = file_get_contents("https://www.infosicoes.com/contenido/paginas.admin/cron/cron.actualizar_ids_convocatorias.php");

echo ' : <i class="fa fa-thumbs-up fa-fw sidebar-nav-icon"></i>';