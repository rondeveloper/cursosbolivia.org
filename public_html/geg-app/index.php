<?php
/* error reporting */
error_reporting(0);

/* sessiones */
session_start();

/* datos de configuracion */
include_once 'contenido/configuracion/config.php';
include_once 'contenido/configuracion/funciones.php';

/* paginas estaticas */
static_ghtmls();

/* coneccion a base de datos */
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* content get */
$get = explode('/', str_replace('.html', '', get('seccion')));
for ($cn_ge = count($get); $cn_ge > 0; $cn_ge--) {
    $get[$cn_ge] = $get[$cn_ge - 1];
}

/* content dir */
if (isset_get('dir')) {
    $dir_ = explode("/", get('dir'));
    for ($cn_ge = (count($dir_) - 1); $cn_ge > 0; $cn_ge--) {
        $dir[$cn_ge] = $dir_[$cn_ge - 1];
    }
}

/* estructura */
include_once 'contenido/paginas/templates/header.php';
include_once 'contenido/paginas/principal.php';
include_once 'contenido/paginas/templates/footer.php';
