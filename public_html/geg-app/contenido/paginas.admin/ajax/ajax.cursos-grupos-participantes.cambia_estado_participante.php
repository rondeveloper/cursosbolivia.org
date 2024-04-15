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
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT estado FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$estado_participante = $rqdp2['estado'];

if($estado_participante=='1'){
    query("UPDATE cursos_participantes SET estado='0' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de participante [deshabilitacion]', 'partipante-deshabilitacion', 'participante', $id_participante);

    /* id curso */
    $rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);
    $id_curso = (int) $rqdc2['id_curso'];

    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* datos curso */
    $rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = mysql_fetch_array($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        $rqdcp1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
        $rqdcp2 = mysql_fetch_array($rqdcp1);
        $nombre_participante = trim($rqdcp2['nombres'] . ' ' . $rqdcp2['apellidos']);
        logcursos('Eliminacion de participante [fuera de fecha][' . $nombre_participante . ']', 'curso-edicion', 'curso', $id_curso);
    }
    echo '['.$id_participante.'] Des-habilitado';
}else{
    query("UPDATE cursos_participantes SET estado='1' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Habilitacion de participante', 'partipante-deshabilitacion', 'participante', $id_nuevo);

    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* datos curso */
    $rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = mysql_fetch_array($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        $nombre_participante = trim($nombres . ' ' . $apellidos);
        logcursos('Habilitacion de participante [fuera de fecha][' . $nombre_participante . ']', 'curso-edicion', 'curso', $id_curso);
    }
    echo '['.$id_participante.'] Habilitado';
}