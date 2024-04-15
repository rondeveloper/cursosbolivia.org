<?php

/* PROCESO DE PERSISTENCIA DE SESION */
if (isset($_COOKIE['datasesion_idtracking']) && !isset_usuario()) {
    $datasesion_idtracking = $_COOKIE['datasesion_idtracking'];
    $rqdul1 = query("SELECT id FROM usuarios WHERE hash_usuario='$datasesion_idtracking' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqdul1) > 0) {
        $rqdul2 = mysql_fetch_array($rqdul1);
        usuarioSet('id', $rqdul2['id']);
    }
    echo "<!-- UNO -->";
}else{
    echo "<!-- DOS -->";
}
