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

/* datos recibidos */
$id_participante = post('id_participante');
$id_onlinecourse = post('id_onlinecourse');

$rqdcv1 = query("SELECT id_usuario,id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdcv2 = fetch($rqdcv1);
$id_usuario = $rqdcv2['id_usuario'];
query("UPDATE cursos_onlinecourse_acceso SET sw_acceso='0' WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario' LIMIT 1 ");
logcursos('DES-HABILITACION para curso virtual [CV:'.$id_onlinecourse.']', 'partipante-edicion', 'participante', $id_participante);
?>
<div class="alert alert-success">
    <strong>EXITO</strong> participante des-habilitado para curso virtual.
</div>
