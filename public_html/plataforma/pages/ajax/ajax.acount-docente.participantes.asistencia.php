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
$id_rel_cursoonlinecourse = post('id_rel_cursoonlinecourse');
$id_docente = docente('id');

/* usuario */
$rqdu1 = query("SELECT id_usuario FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$id_usuario = $rqdu2['id_usuario'];

$rqva1 = query("SELECT id FROM cursos_onlinecourse_asistencia WHERE fecha=CURDATE() AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' AND id_participante='$id_participante' ");
if (num_rows($rqva1) == 0) {
    query("INSERT INTO cursos_onlinecourse_asistencia(id_rel_cursoonlinecourse, id_usuario, id_participante,fecha) VALUES ('$id_rel_cursoonlinecourse','$id_usuario','$id_participante',CURDATE())");
    ?>
    <b class="btn btn-success" onclick="asistencia('<?php echo $id_participante; ?>');">SI &nbsp;&nbsp; <i class="fa fa-refresh"></i></b>
    <?php
} else {
    $rqva2 = fetch($rqva1);
    $id_asist = $rqva2['id'];
    query("DELETE FROM cursos_onlinecourse_asistencia WHERE id='$id_asist' ORDER BY id DESC limit 1 ");
    ?>
    <b class="btn btn-warning" onclick="asistencia('<?php echo $id_participante; ?>');">NO &nbsp;&nbsp; <i class="fa fa-refresh"></i></b>
    <?php
}