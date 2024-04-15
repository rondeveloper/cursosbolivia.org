<?php

/* PROCESO DE CONFIGURACION/PROCESADO DE PUSH-NAVIGATOR */
if ( !isset($_COOKIE["verify_2"]) || (isset($_COOKIE["verify_2"]) && $_COOKIE["verify_2"]!==date("dm") )) {
    /* asociacion de pushnav a cuenta de usuario */
    if (isset_usuario()) {
        $id_usuario = usuario('id');
        if (isset($_COOKIE['token_nav'])) {
            $token_nav = $_COOKIE['token_nav'];
            
            $rqdc1 = query("SELECT id_usuario FROM cursos_suscnav WHERE token='$token_nav' ORDER BY id DESC limit 1 ");
            $rqdc2 = mysql_fetch_array($rqdc1);
            if($rqdc2['id_usuario']=='0'){
                query("UPDATE cursos_suscnav SET id_usuario='$id_usuario' WHERE token='$token_nav' ");
            }
        }
    }
    /* verificacion diaria */
    setcookie("verify_2", date("dm"), time() + 1 * 24 * 60 * 60);
}
