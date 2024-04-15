<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_usuario()) {
    //query("UPDATE empresas SET cookie='". substr( md5(rand(100, 1000)), 1, 9)."', conectado='0' WHERE id='".usuario('id')."' ");
    
    //session_unset();
    //session_destroy();
    
    unset($_SESSION['user_id']);
    
    /* cookie principal */
    setcookie("hash_usuario", "", time() - 100 , "/");
    setcookie("id_usuario", "", time() - 100 , "/");
    
    /* cookie curso-online (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 100 , "/curso-online/");
    setcookie("id_usuario", "", time() - 100 , "/curso-online/");
    /* cookie curso-online-leccion (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-1-288cc0ff022877bd3df94bc9360b9c5d/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-2-b73dfe25b4b8714c029b37a6ad3006fa/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-3-6081594975a764c8e3a691fa2b3a321d/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-4-fc3cf452d3da8402bebb765225ce8c0e/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-5-e995f98d56967d946471af29d7bf99f1/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-6-98f13708210194c475687be6106a3b84/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-7-26657d5ff9020d2abefe558796b99584/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-evaluacion/");
           
    
//    setcookie("cookie_is_id_usuario", "", time() - 1);
//    setcookie("cookie_is_cod", "", time() - 1 );
    
//    unset($_COOKIE['cookie_is_id_usuario']);  
//    unset($_COOKIE['cookie_is_cod']);  
}

if (isset_docente()) {
    
    unset($_SESSION['doc_id']);
    
    /* cookie principal */
    setcookie("hash_usuario", "", time() - 100 , "/");
    setcookie("id_usuario", "", time() - 100 , "/");
    
    /* cookie curso-online (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 100 , "/curso-online/");
    setcookie("id_usuario", "", time() - 100 , "/curso-online/");
    /* cookie curso-online-leccion (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-1-288cc0ff022877bd3df94bc9360b9c5d/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-2-b73dfe25b4b8714c029b37a6ad3006fa/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-3-6081594975a764c8e3a691fa2b3a321d/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-4-fc3cf452d3da8402bebb765225ce8c0e/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-5-e995f98d56967d946471af29d7bf99f1/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-6-98f13708210194c475687be6106a3b84/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-leccion/0-7-26657d5ff9020d2abefe558796b99584/");
    setcookie("hash_usuario", "", time() - 1000 , "/curso-online-evaluacion/");
    
    
    //session_unset();
    //session_destroy();
}
header("location: ../../../");
