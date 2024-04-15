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
$id_participante = post('id_participante');
$id_rel_cursoonlinecoursenotas = post('id_rel_cursoonlinecoursenotas');
$calificacion = post('calificacion')>100?100:post('calificacion');
$rqdnad1 = query("SELECT id FROM rel_notacalificacion WHERE id_rel_cursoonlinecoursenotas='$id_rel_cursoonlinecoursenotas' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
if (num_rows($rqdnad1) == 0) {
    query("INSERT INTO rel_notacalificacion (calificacion,id_rel_cursoonlinecoursenotas,id_participante) VALUES ('$calificacion','$id_rel_cursoonlinecoursenotas','$id_participante') ");
} else {
    $rqdnad2 = fetch($rqdnad1);
    $id_rel = $rqdnad2['id'];
    query("UPDATE rel_notacalificacion SET calificacion='$calificacion' WHERE id='$id_rel' ORDER BY id DESC limit 1 ");
}
echo '<i style="color: #0eb50e;font-size: 8pt;">ok</i>';
