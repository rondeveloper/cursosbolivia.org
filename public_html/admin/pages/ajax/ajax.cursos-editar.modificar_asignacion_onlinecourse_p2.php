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

/* data */
$id_asignacion_onlinecourse = post('id_asignacion_onlinecourse');
//$id_onlinecourse = post('id_onlinecourse');
$estado = post('estado');
$id_docente = post('id_docente');
$id_certificado = post('id_certificado');
$id_certificado_2 = post('id_certificado_2');
$fecha_inicio = post('fecha_inicio');
$fecha_final = post('fecha_final');
$sw_cod_asistencia = post('sw_cod_asistencia');

/* curso */
$rqdc1 = query("SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' LIMIT 1 ");
$rqdc2 = fetch($rqdc1);
$id_curso = $rqdc2['id_curso'];

/* proceso */
query("UPDATE cursos_rel_cursoonlinecourse SET 
       id_docente='$id_docente',
       id_certificado='$id_certificado',
       id_certificado_2='$id_certificado_2',
       fecha_inicio='$fecha_inicio',
       fecha_final='$fecha_final',
       sw_cod_asistencia='$sw_cod_asistencia',
       estado='$estado' 
       WHERE id='$id_asignacion_onlinecourse' LIMIT 1 ");

logcursos('Modificacion de datos de curso virtual', 'curso-virtual-modificacion', 'curso', $id_curso);
?>
<div class="alert alert-success">
  <strong>Exito!</strong> regitro modificado correctamente.
</div>