<?php

/* PROCESO DE PERSISTENCIA DE SESION */
if ( !isset($_COOKIE["verify"]) || (isset($_COOKIE["verify"]) && $_COOKIE["verify"]!==date("dm") ) || true) {
    if (isset_usuario()) {
        
        $id_usuario = usuario('id');
        $rqdul1 = query("SELECT id,hash_usuario FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
        if (num_rows($rqdul1) > 0) {
            $rqdul2 = fetch($rqdul1);
            $hash_usuario = $rqdul2['hash_usuario'];
            setcookie("id_usuario", $id_usuario, time() + 100 * 24 * 60 * 60, "/");
            setcookie("hash_usuario", $hash_usuario, time() + 100 * 24 * 60 * 60, "/");
        }
        
    } elseif (isset($_COOKIE["hash_usuario"]) && isset($_COOKIE["id_usuario"])) {
        //echo "<br/><hr/>FLAG - B <br/><hr/>";exit;
        $id_usuario = $_COOKIE["id_usuario"];
        $hash_usuario = $_COOKIE["hash_usuario"];
        
        setcookie("id_usuario", $id_usuario, time() + 100 * 24 * 60 * 60);
        setcookie("hash_usuario", $hash_usuario, time() + 100 * 24 * 60 * 60);

        /* login de sesion */
        if (!isset_usuario()) {
            $rqdul1 = query("SELECT id FROM cursos_usuarios WHERE id='$id_usuario' AND hash_usuario='$hash_usuario' ORDER BY id DESC limit 1 ");
            if (num_rows($rqdul1) > 0) {
                usuarioSet('id', $id_usuario);
            }
        }

        /* token nav */
        if (isset($_COOKIE['token_nav'])) {
            setcookie("token_nav", $_COOKIE['token_nav'], time() + 100 * 24 * 60 * 60, "/");
        }
        
    }
    /* verificacion diaria */
    //setcookie("verify", date("dm"), time() + 1 * 24 * 60 * 60);
}
