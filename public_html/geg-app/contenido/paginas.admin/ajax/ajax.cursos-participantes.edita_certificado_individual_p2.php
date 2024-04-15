<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_certificado = post('id_certificado');
$cont_titulo = post('cont_titulo');
$cont_uno = post('cont_uno');
$cont_dos = post('cont_dos');
$cont_tres = post('cont_tres');
$texto_qr = post('texto_qr');
$fecha_qr = post('fecha_qr');

/* verif */
$rqv1 = query("SELECT COUNT(*) AS total FROM cursos_emisiones_certificados WHERE id_certificado='$id_certificado' ");
$rqv2 = mysql_fetch_array($rqv1);
if ($rqv2['total'] > 1) {
    echo "<b>ESTE CERTIFICADO NO PUEDE SER EDITADO INDIVIDUALMENTE.</b>";
    exit;
}

/* query */
query("UPDATE cursos_certificados SET cont_titulo='$cont_titulo',cont_uno='$cont_uno',cont_dos='$cont_dos',cont_tres='$cont_tres',texto_qr='$texto_qr',fecha_qr='$fecha_qr' WHERE id='$id_certificado' LIMIT 1 ");

$rqdp1 = query("SELECT id_participante FROM cursos_emisiones_certificados WHERE id_certificado='$id_certificado' ");
$rqdp2 = mysql_fetch_array($rqdp1);
$id_participante = $rqdp2['id_participante'];
logcursos('Edicion de certificado individual[ccid:'.$id_certificado.']', 'partipante-edicion', 'participante', $id_participante);

echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro fue actualizado correctamente.
</div>';
