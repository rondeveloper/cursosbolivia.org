<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_departamento = post('id_departamento');
$current_id_ciudad = post('current_id_ciudad');

$rqdl1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$id_departamento' AND estado='1' ORDER BY nombre ASC ");
if (isset_post('sw_option_todos')) {
    echo '<option value="0">Todas las ciudades</option>';
} elseif (num_rows($rqdl1) == 0) {
    echo '<option value="0">Sin resultados</option>';
}
while ($rqdl2 = fetch($rqdl1)) {
    $selected = '';
    if ($current_id_ciudad == $rqdl2['id']) {
        $selected = ' selected="selected" ';
    }
    echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . $rqdl2['nombre'] . '</option>';
}
