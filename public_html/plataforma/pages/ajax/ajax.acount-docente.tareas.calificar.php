<?php
session_start();

include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_docente()) {
    echo "ACCESO DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_tarea = post('id_tarea');
$id_envio = post('id_envio');
$calificacion = post('calificacion');

query("UPDATE cursos_onlinecourse_tareasenvios SET calificacion='$calificacion' WHERE id='$id_envio' AND id_tarea='$id_tarea' ORDER BY id DESC limit 1 ");

if($calificacion=='0'){
    $txt_color = 'red';
}else{
    $txt_color = 'gray';
}

echo '<b class="nota-cal" style="color:'.$txt_color.';">'.$calificacion.'</b>';
