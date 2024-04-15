<?php
session_start();
include_once 'contenido/configuracion/config.php';
include_once 'contenido/configuracion/funciones.php';
include_once 'contenido/librerias/correo/class.phpmailer.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    include_once 'contenido/paginas.admin/no_log.php';
} else {
    include_once 'contenido/paginas.admin/templates.admin/header.php';
    include_once 'contenido/paginas.admin/principal.php';
    include_once 'contenido/paginas.admin/templates.admin/footer.php';
}

