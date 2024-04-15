<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_presentacion = post('id_presentacion');
/* presentacion */
$rqdtp1 = query("SELECT id_leccion FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ORDER BY id ASC ");
$rqdtp2 = mysql_fetch_array($rqdtp1);
$id_leccion = $rqdtp2['id_leccion'];
query("DELETE FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ");
?>
<b>REGISTRO ELIMINADO EXITOSAMENTE</b>
<button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_p1('<?php echo $id_leccion; ?>');">
    CONTINUAR
</button>



