<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$titulo_identificador_curso = post('dat');

$rqdc1 = query("SELECT id,cnt_reproducciones FROM cursos WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (0,1,2) ORDER BY FIELD(estado,1,2,0),id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$id_curso = $rqdc2['id'];

/* incremento reproducciones */
$cnt_reproducciones = $rqdc2['cnt_reproducciones'] + 1;
query("UPDATE cursos SET cnt_reproducciones='$cnt_reproducciones' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

echo 'View updated';