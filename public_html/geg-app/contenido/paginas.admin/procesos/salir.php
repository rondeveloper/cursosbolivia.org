<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


query("UPDATE administradores SET cookie='" . substr(md5(rand(100, 1000)), 1, 9) . "' WHERE id='" . administrador('id') . "' ");

session_unset();
session_destroy();

setcookie("hsygbaj", "", time() - 1);
setcookie("stedfyc", "", time() - 1);

unset($_COOKIE['hsygbaj']);
unset($_COOKIE['stedfyc']);

header("location: ../../../");
