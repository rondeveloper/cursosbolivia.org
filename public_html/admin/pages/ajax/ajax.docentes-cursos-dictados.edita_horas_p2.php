<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = (int)post('id_curso');
$duracion = (int)post('duracion');

query("UPDATE cursos SET duracion='$duracion' WHERE id='$id_curso' ");
logcursos('Edicion de duracion de curso', 'curso-edicion', 'curso', $id_curso);
?>
<div class="alert alert-success">
  <strong>EXITO</strong> el registro se actualizo correctamente.
</div>
