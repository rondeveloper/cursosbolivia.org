<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* content get */
$get_ = explode('/', str_replace('.html', '', get('seccion')));
for ($cn_ge = count($get_); $cn_ge > 0; $cn_ge--) {
    if($get_[$cn_ge - 1]!=''){
        $get[$cn_ge] = $get_[$cn_ge - 1];
    }
}

$id_usuario = (int)usuario('id_sim');
if($id_usuario>0){
    include_once 'contenido/pages/home.php';
}else{
    include_once 'contenido/pages/login.php';    
}