<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_asignacion_onlinecourse = (int)post('id_asignacion_onlinecourse');

$rqdp1 = query("SELECT id_curso,id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$id_curso = (int)$rqdp2['id_curso'];
$id_onlinecourse = (int)$rqdp2['id_onlinecourse'];


$ids_usuarios = '0';
$rqaux1 = query("SELECT id_usuario FROM cursos_participantes WHERE id_curso='$id_curso' ");
while($rqaux2 = fetch($rqaux1)){
    $ids_usuarios .= ','.$rqaux2['id_usuario'];
}

//echo "test2 [$id_curso][$id_onlinecourse][$ids_usuarios]";exit;

//echo "DELETE FROM cursos_onlinecourse_acceso WHERE id_usuario IN ($ids_usuarios) AND id_onlinecourse='$id_onlinecourse' ";exit;

//query("DELETE FROM cursos_onlinecourse_acceso WHERE id_usuario IN ($ids_usuarios) AND id_onlinecourse='$id_onlinecourse' ");
//query("DELETE FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' ");

query("DELETE FROM cursos_onlinecourse_acceso WHERE id_usuario IN ($ids_usuarios) AND id_onlinecourse='$id_onlinecourse' ");
//query("UPDATE cursos_rel_cursoonlinecourse SET estado='0' WHERE id='$id_asignacion_onlinecourse' ");
query("DELETE FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' ");

logcursos('Eliminacion de curso virtual', 'curso-edicion', 'curso', $id_curso);

echo '<div class="alert alert-success">
<strong>EXITO</strong> accesos y asignacion eliminados.
</div>';


