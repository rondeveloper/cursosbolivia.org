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
$id_pregunta = post('id_pregunta');

$rqdr1 = query("SELECT respuesta,sw_correcto FROM cursos_onlinecourse_respuestas WHERE id_pregunta='" . $id_pregunta . "' ");
while ($rqdr2 = fetch($rqdr1)) {
    if ($rqdr2['sw_correcto'] == '1') {
        echo "<label class='label label-success'>CORRECTO</label>";
    } else {
        echo "<label class='label label-default'>No correcto</label>";
    }
    echo ' | ' . $rqdr2['respuesta'] . '<br><br>';
}
