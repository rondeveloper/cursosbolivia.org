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
$id_material = post('id_material');
$nombre_digital = post('nombre_digital');
$name_archivo = 'archivo';

if (is_uploaded_file($_FILES[$name_archivo]['tmp_name'])) {

    $name_file = $_FILES[$name_archivo]['name'];
    $extension = pathinfo($name_file, PATHINFO_EXTENSION);
    if($extension=='php'){
        exit;
    }
    $nombre_fisico = md5($id_material . $name_file) . '.' . $extension;
    $dest_archivo = '../../archivos/material/' . $nombre_fisico;
    if (move_uploaded_file($_FILES[$name_archivo]['tmp_name'], $dest_archivo)) {
        query("INSERT INTO cursos_material_archivos (id_material,nombre_digital,nombre_fisico) VALUES ('$id_material','$nombre_digital','$nombre_fisico') ");
        $id_archivo = mysql_insert_id();
        logcursos('Agregado de archivo ARCH:'.$id_archivo, 'material-addarch', 'material', $id_material);
        echo '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
        echo '<br><b class="btn btn-block btn-default" onclick="mostrar_archivos('.$id_material.');">CONTINUAR</b>';
    } else {
        echo '<div class="alert alert-danger">
  <strong>ERROR</strong> no se pudo procesar.
</div>';
    }
} else {
    echo '<div class="alert alert-danger">
  <strong>ERROR</strong> no se subio el archivo.
</div>';
}