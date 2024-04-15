<?php

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



/* VERIFICACION */
if (!isset_usuario()) {
    echo "DENEGADO";
    exit;
}

/* USUARIO */
$id_usuario = usuario('id');

$displayScore = post('displayScore');
$questionCount = post('questionCount');
$id_onlinecourse = post('id_onlinecourse');

$fecha = date("Y-m-d H:i");

query("INSERT INTO cursos_onlinecourse_evaluaciones (id_onlinecourse,id_usuario,total_correctas,total_preguntas,fecha) VALUES ('$id_onlinecourse','$id_usuario','$displayScore','$questionCount','$fecha') ");
