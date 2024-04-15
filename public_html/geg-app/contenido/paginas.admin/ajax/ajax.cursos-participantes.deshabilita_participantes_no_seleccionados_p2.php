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

/* recepcion de datos POST */
$dat = post('dat');

/* limpia datos de id participante */
$ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $dat));
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}

$id_curso = 0;
$cnt_participantes_eliminados = 0;

/* des-habilita participantes */
$rqp1 = query("SELECT id FROM cursos_participantes WHERE id IN($ids_participantes) ");
while ($rqp2 = mysql_fetch_array($rqp1)) {
    $id_participante = $rqp2['id'];
    query("UPDATE cursos_participantes SET estado='0' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de participante [deshabilitacion]', 'partipante-deshabilitacion', 'participante', $id_participante);
    $cnt_participantes_eliminados++;
    
    /* id curso */
    if($id_curso==0){
        $rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
        $rqdc2 = mysql_fetch_array($rqdc1);
        $id_curso = (int)$rqdc2['id_curso'];
    }
}

/* sw cierre */
query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

/* datos curso */
$rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcf2 = mysql_fetch_array($rqdcf1);
$fecha_curso = $rqdcf2['fecha'];
if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
    logcursos('Eliminacion de '.$cnt_participantes_eliminados.' participantes [fuera de fecha]', 'curso-edicion', 'curso', $id_curso);
}
?>

<div class="alert alert-success">
    <strong>Exito!</strong> Los participantes fueron des-habilitados exitosamente.
</div>