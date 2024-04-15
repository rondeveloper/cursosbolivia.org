<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_presentacion = post('id_presentacion');
$id_leccion = post('id_leccion');

$duracion1 = (int) post('duracion1');
$duracion2 = (int) post('duracion2');
$duracion = ($duracion1 * 60) + $duracion2;

$nombreimagen = post('imagen_actual');

/* imagen */
if (is_uploaded_file(archivo('imagen'))) {
    $nombreimagen = 'L' . $id_leccion . '-' . str_replace(' ', '-', archivoName('imagen'));
    move_uploaded_file(archivo('imagen'), '../../imagenes/presentaciones/' . $nombreimagen);
}
query("UPDATE cursos_onlinecourse_presentaciones SET imagen='$nombreimagen',duracion_audio='$duracion' WHERE id='$id_presentacion' ORDER BY id DESC limit 1 ");
?>

<b>PRESENTACION ACTUALIZADA EXITOSAMENTE</b>
<hr/>
<button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_p1('<?php echo $id_leccion; ?>');">
    CONTINUAR
</button>

<br/>
<br/>

