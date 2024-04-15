<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/* datos recibidos */
$ci = post('dat');

$array_respuesta = array();

$array_respuesta['estado'] = 0;
$array_respuesta['nombres'] = '';
$array_respuesta['apellidos'] = '';
$array_respuesta['celular'] = '';
$array_respuesta['correo'] = '';
$array_respuesta['prefijo'] = '';
$array_respuesta['mensaje'] = '';

if (strlen($ci) >= 5) {

    /* verificacion de existencia */
    $rqpcv1 = query("SELECT nombres,apellidos,correo,celular,prefijo,ci_expedido,(select razon_social from cursos_proceso_registro where id=cp.id_proceso_registro order by id desc limit 1)razon_social,(select nit from cursos_proceso_registro where id=cp.id_proceso_registro order by id desc limit 1)nit FROM cursos_participantes cp WHERE ci LIKE '$ci' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqpcv1) > 0) {
        $rqpcv2 = mysql_fetch_array($rqpcv1);
        $array_respuesta['estado'] = 1;
        $array_respuesta['nombres'] = $rqpcv2['nombres'];
        $array_respuesta['apellidos'] = $rqpcv2['apellidos'];
        $array_respuesta['celular'] = $rqpcv2['celular'];
        $array_respuesta['correo'] = $rqpcv2['correo'];
        $array_respuesta['prefijo'] = $rqpcv2['prefijo'];
        $array_respuesta['ci_expedido'] = $rqpcv2['ci_expedido'];
        $array_respuesta['razon_social'] = $rqpcv2['razon_social'];
        $array_respuesta['nit'] = $rqpcv2['nit'];
    }
} else {
    $array_respuesta['mensaje'] = '<div class="alert alert-danger">
        <strong>Error!</strong> se debe agregar nombre y apellidos.
    </div>';
}

echo json_encode($array_respuesta);
