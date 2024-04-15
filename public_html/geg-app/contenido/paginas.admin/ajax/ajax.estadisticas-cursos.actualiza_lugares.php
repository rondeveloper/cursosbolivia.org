<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_ciudad = (int) post('id_ciudad');
$id_departamento = (int) post('id_departamento');
if ($id_ciudad !== 0) {
    $rqdl1 = query("SELECT id,nombre,salon,(select nombre from ciudades where id=cursos_lugares.id_ciudad)ciudad FROM cursos_lugares WHERE id_ciudad='$id_ciudad' ORDER BY nombre ASC ");
} elseif ($id_departamento !== 0) {
    $rqdl1 = query("SELECT id,nombre,salon,(select nombre from ciudades where id=cursos_lugares.id_ciudad)ciudad FROM cursos_lugares WHERE id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ORDER BY nombre ASC ");
} else {
    echo '<option value="0">Todos los lugares...</option>';
    exit;
}

if (mysql_num_rows($rqdl1) == 0) {
    echo '<option value="0">Todos los lugares...</option>';
    echo '<option value="0">Sin resultados</option>';
} else {
    echo '<option value="0">Todos los lugares...</option>';
    while ($rqdl2 = mysql_fetch_array($rqdl1)) {
        $selected = '';
        if ($current_id_lugar == $rqdl2['id']) {
            $selected = ' selected="selected" ';
        }
        echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . $rqdl2['nombre'] . ' - ' . $rqdl2['salon'] . ' - ' . $rqdl2['ciudad'] . '</option>';
    }
}
