<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



/* ASIGNACION DE NUMERACION */
$rcdcp1 = query("SELECT * FROM cursos WHERE estado IN ('1','0','2') AND numero='0' AND DATE(fecha) <= CURDATE() ORDER BY fecha ASC,id_ciudad DESC,id ASC limit 50 ");
while ($rcdcp2 = fetch($rcdcp1)) {

    /* data */
    $id_curso = $rcdcp2['id'];
    $fecha_curso = $rcdcp2['fecha'];

    /* obtiene numeracion */
    $rqgnm1 = query("SELECT numero FROM cursos ORDER BY numero DESC limit 1 ");
    $rqgnm2 = fetch($rqgnm1);
    $numero_curso = ((int) $rqgnm2['numero']) + 1;

    query("UPDATE cursos SET numero='$numero_curso' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    logcursos('NUMERACION ASIGNADA [' . $numero_curso . ']', 'curso-numeracion', 'curso', $id_curso);

    echo "CURSO: $id_curso | FECHA: $fecha_curso | N&Uacute;MERO: $numero_curso <br/>";
}
echo "<br/>OK";
