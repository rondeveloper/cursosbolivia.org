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
/* presentacion */
$rqdtp1 = query("SELECT id_leccion FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ORDER BY id ASC ");
$rqdtp2 = fetch($rqdtp1);
$id_leccion = $rqdtp2['id_leccion'];
query("DELETE FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ");
?>
<b>REGISTRO ELIMINADO EXITOSAMENTE</b>
<button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_p1('<?php echo $id_leccion; ?>');">
    CONTINUAR
</button>



