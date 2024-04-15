<?php

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_docente = post('id_docente');
$id_curso = post('id_curso');
$id_administrador = administrador('id');

/* docente */
$rqd1 = query("SELECT id_modalidad_pago,pago_hora FROM cursos_docentes WHERE id='$id_docente' LIMIT 1 ");
$rqd2 = mysql_fetch_array($rqd1);
$docente_id_modalidad_pago = $rqd2['id_modalidad_pago'];
$docente_pago_hora = (int) $rqd2['pago_hora'];

/* curso */
$rqdc1 = query("SELECT duracion FROM cursos WHERE id='$id_curso' LIMIT 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$duracion_curso = $rqdc2['duracion'];

/* monto */
$monto = $docente_pago_hora * $duracion_curso;

$rqvp1 = query("SELECT id FROM cursos_docentes_pagos WHERE id_docente='$id_docente' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqvp1) == 0) {
    /* registro */
    query("INSERT INTO cursos_docentes_pagos(id_docente, id_curso, monto, id_administrador) VALUES ('$id_docente','$id_curso','$monto','$id_administrador')");
    $id_cdp = mysql_insert_id();
    logcursos('Pago curso registrado ['.$id_cdp.']', 'docente-pago', 'docente', $id_docente);
}else{
    $rqvp2 = mysql_fetch_array($rqvp1);
    $id_cdp = $rqvp2['id'];
    query("DELETE FROM cursos_docentes_pagos WHERE id='$id_cdp' ORDER BY id DESC limit 1 ");
    logcursos('Pago curso eliminado ['.$id_cdp.']', 'docente-pago', 'docente', $id_docente);
}
echo 'Pago actualizado';
