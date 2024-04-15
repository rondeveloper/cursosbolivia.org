<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$id_departamento = post('id_departamento');

$rqdl1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$id_departamento' AND estado='1' ORDER BY nombre ASC ");
echo '<option value="0">Todas las ciudades</option>';
while ($rqdl2 = fetch($rqdl1)) {
    echo '<option value="' . $rqdl2['id'] . '" >' . $rqdl2['nombre'] . '</option>';
}
