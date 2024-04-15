<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



/* datos recibidos */
$ci = post('dat');

$array_respuesta = array();

$array_respuesta['estado'] = 0;
$array_respuesta['nombres'] = '';
$array_respuesta['apellidos'] = '';
$array_respuesta['correo'] = '';
$array_respuesta['prefijo'] = '';
$array_respuesta['mensaje'] = '';

if (strlen($ci) >= 5) {

    /* verificacion de existencia */
    $rqpcv1 = query("SELECT nombres,apellidos,correo,prefijo FROM cursos_participantes WHERE ci LIKE '$ci' ORDER BY id DESC limit 1 ");
    if (num_rows($rqpcv1) > 0) {
        $rqpcv2 = fetch($rqpcv1);
        $array_respuesta['estado'] = 1;
        $array_respuesta['nombres'] = $rqpcv2['nombres'];
        $array_respuesta['apellidos'] = $rqpcv2['apellidos'];
        $array_respuesta['correo'] = $rqpcv2['correo'];
        $array_respuesta['prefijo'] = $rqpcv2['prefijo'];
    }
} else {
    $array_respuesta['mensaje'] = '<div class="alert alert-danger">
        <strong>Error!</strong> se debe agregar nombre y apellidos.
    </div>';
}

echo json_encode($array_respuesta);
