<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_emision_certificado = post('id_emision_certificado');
$id_envio_certificado = post('id_envio_certificado');
$codigo_traking = post('codigo_traking');
$observaciones = post('observaciones');

query("UPDATE cursos_envio_certificados SET 
          codigo_traking='$codigo_traking', 
          observaciones='$observaciones', 
          sw_enviado='1' 
          WHERE id='$id_envio_certificado' LIMIT 1 ");

$rqdp1 = query("SELECT id_participante FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$id_participante = $rqdp2['id_participante'];

logcursos('Certificado enviado [traking-cod:'.$codigo_traking.']', 'participante-edicion', 'participante', $id_participante);

echo '<div class="alert alert-success">
  <strong>EXITO</strong> actualizacion de registro realizado correctamente.
</div>';