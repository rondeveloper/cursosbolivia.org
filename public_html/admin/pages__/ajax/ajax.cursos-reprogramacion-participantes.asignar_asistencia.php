<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_reprogramacion = post('id_reprogramacion');
$id_curso_asignacion = post('id_curso_asignacion');

/* registro reprogramacion */
$rqdrr1 = query("SELECT id_participante FROM cursos_reprogramacion_participantes WHERE id='$id_reprogramacion' LIMIT 1 ");
$rqdrr2 = fetch($rqdrr1);
$id_participante = $rqdrr2['id_participante'];

/* asignacion */
query("UPDATE cursos_reprogramacion_participantes SET id_curso_asignado='$id_curso_asignacion',estado='2' WHERE id='$id_reprogramacion' LIMIT 1 ");

logcursos('Confirmacion de asistencia a curso reprogramado [C:'.$id_curso_asignacion.']', 'reprogramacion-asistencia', 'participante', $id_participante);
logcursos('Asignacion de participante de curso reprogramado [IdRep:'.$id_reprogramacion.']', 'reprogramacion-asistencia', 'curso', $id_curso_asignacion);
?>
<div class="alert alert-success">
  <strong>Exito!</strong> el registro se modifico correctamente.
</div>