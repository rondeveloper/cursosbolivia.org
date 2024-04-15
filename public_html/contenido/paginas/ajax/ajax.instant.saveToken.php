<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* internal data */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$fecha_registro = date("Y-m-d H:i");

/* token */
$token = urldecode(post('token'));

if ($token !== '') {
    /* estado activo */
    query("UPDATE cursos_suscnav SET estado='1' WHERE token='$token' AND estado<>'0' ");
    
    /* token registrado */
    $token_nav = '';
    if (isset($_COOKIE['token_nav'])) {
        $token_nav = $_COOKIE['token_nav'];
    }
    
    /* verificacion de nuevo token */
    if ($token !== $token_nav) {

        /* cookie */
        setcookie("token_nav", $token, time() + 100 * 24 * 60 * 60 , "/");

        /* registro */
        $rqdl1 = query("SELECT id FROM cursos_suscnav WHERE token='$token' ORDER BY id DESC limit 1 ");
        if (num_rows($rqdl1) == 0) {
            query("INSERT INTO cursos_suscnav (token,ip,fecha_registro) VALUES ('$token','$ip_coneccion','$fecha_registro') ");
        }else{
            $rqdl2 = fetch($rqdl1);
            $id_token = $rqdl2['id'];
            query("UPDATE cursos_suscnav SET estado='1' WHERE id='$id_token' AND estado<>'0' LIMIT 1 ");
        }
        
        /* desabilitacion de token anterior */
        if ($token_nav !== '') {
            query("UPDATE cursos_suscnav SET estado='3' WHERE token='$token_nav' ");
        }
        
    }
    //echo "$token prev: $token_nav | ";
}

echo $token;

