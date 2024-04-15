<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$r = file_get_contents('http://infosicoes.com/contenido/paginas.admin/cron/cron.sincroniza_cursos.php');

echo " &nbsp; <i class='fa fa-thumbs-up'></i>";
