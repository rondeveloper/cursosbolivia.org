<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$id_empresa = (int) $_POST['id'];

$r_envios1 = query("SELECT COUNT(*) as total FROM envios WHERE id_empresa='$id_empresa' ");
$r_envios2 = fetch($r_envios1);

echo $r_envios2['total'] . ' envios';


?>
