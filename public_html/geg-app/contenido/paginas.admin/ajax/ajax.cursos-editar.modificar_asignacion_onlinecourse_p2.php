<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* data */
$id_asignacion_onlinecourse = post('id_asignacion_onlinecourse');
$id_onlinecourse = post('id_onlinecourse');
$estado = post('estado');
$id_docente = post('id_docente');
$id_certificado = post('id_certificado');
$id_certificado_2 = post('id_certificado_2');
$fecha_inicio = post('fecha_inicio');
$fecha_final = post('fecha_final');

/* curso */
$rqdc1 = query("SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' LIMIT 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$id_curso = $rqdc2['id_curso'];

/* proceso */
query("UPDATE cursos_rel_cursoonlinecourse SET 
       id_onlinecourse='$id_onlinecourse',
       id_docente='$id_docente',
       id_certificado='$id_certificado',
       id_certificado_2='$id_certificado_2',
       fecha_inicio='$fecha_inicio',
       fecha_final='$fecha_final',
       estado='$estado' 
       WHERE id='$id_asignacion_onlinecourse' LIMIT 1 ");

logcursos('Modificacion de datos de curso virtual', 'curso-virtual-modificacion', 'curso', $id_curso);
?>
<div class="alert alert-success">
  <strong>Exito!</strong> regitro modificado correctamente.
</div>