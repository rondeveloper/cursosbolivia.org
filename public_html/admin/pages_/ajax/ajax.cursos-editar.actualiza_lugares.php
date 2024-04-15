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
$id_ciudad = post('id_ciudad');
$current_id_lugar = post('current_id_lugar');

$rqdl1 = query("SELECT id,nombre,salon,(select nombre from ciudades where id=cursos_lugares.id_ciudad)ciudad FROM cursos_lugares WHERE id_ciudad='$id_ciudad' ORDER BY nombre ASC ");
if(num_rows($rqdl1)==0){
    echo '<option value="0">Sin resultados</option>';
}
while ($rqdl2 = fetch($rqdl1)) {
    $selected = '';
    if ($current_id_lugar == $rqdl2['id']) {
        $selected = ' selected="selected" ';
    }
    echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . $rqdl2['nombre'] . ' - ' . $rqdl2['salon'] . ' - ' . $rqdl2['ciudad'] . '</option>';
}
