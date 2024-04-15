<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$id_empresa = (int) $_POST['id'];

$r_envios1 = query("SELECT COUNT(*) as total FROM envios WHERE id_empresa='$id_empresa' ");
$r_envios2 = mysql_fetch_array($r_envios1);

echo $r_envios2['total'] . ' envios';


?>
