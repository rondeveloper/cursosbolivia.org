<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

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
