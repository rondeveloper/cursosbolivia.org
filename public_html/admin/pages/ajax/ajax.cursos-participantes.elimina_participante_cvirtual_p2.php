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
query("UPDATE cursos_participantes SET sw_cvirtual='0' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdcv1 = query("SELECT id_usuario,id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdcv2 = fetch($rqdcv1);
$id_usuario = $rqdcv2['id_usuario'];
$id_curso = $rqdcv2['id_curso'];
query("UPDATE cursos_onlinecourse_acceso SET sw_acceso='0',estado='0' WHERE id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') AND id_usuario='$id_usuario' ");
logcursos('DES-HABILITACION para curso virtual', 'partipante-edicion', 'participante', $id_participante);
?>
<div class="alert alert-success">
    <strong>EXITO</strong> participante des-habilitado para curso virtual.
</div>
