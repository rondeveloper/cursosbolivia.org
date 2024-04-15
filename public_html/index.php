<?php
/* error reporting */
error_reporting(1);

/* sessiones */
session_start();

/* datos de configuracion */
include_once 'contenido/configuracion/config.php';
include_once 'contenido/configuracion/funciones.php';

/* paginas estaticas */
static_ghtmls();

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* content get */
$get_ = explode('/', str_replace('.html', '', get('seccion')));
for ($cn_ge = count($get_); $cn_ge > 0; $cn_ge--) {
    if($get_[$cn_ge - 1]!=''){
        $get[$cn_ge] = $get_[$cn_ge - 1];
    }
}

/* content dir */
if (isset_get('dir')) {
    $dir = explode("/", get('dir'));
    for ($cn_ge = (count($dir) - 1); $cn_ge > 0; $cn_ge--) {
        $dir[$cn_ge] = $dir[$cn_ge - 1];
    }
}

/* estructura */
include_once 'contenido/paginas/templates/header.php';
include_once 'contenido/paginas/principal.php';
include_once 'contenido/paginas/templates/footer.php';
