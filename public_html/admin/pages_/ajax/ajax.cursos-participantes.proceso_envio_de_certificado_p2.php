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
$id_departamento = post('id_departamento');
$direccion = post('direccion');
$destinatario = post('destinatario');
$celular_destinatario = post('celular_destinatario');
$enviador = post('enviador');
$envio_mediante = post('envio_mediante');
$observaciones = post('observaciones');
$id_administrador = administrador('id');
$fecha = date("Y-m-d H:i");

query("INSERT INTO cursos_envio_certificados(
          id_emision_certificado, 
          id_departamento, 
          id_administrador, 
          direccion, 
          destinatario, 
          celular, 
          enviador, 
          enviado_mediante, 
          observaciones, 
          fecha_asignacion
          ) VALUES (
          '$id_emision_certificado',
          '$id_departamento',
          '$id_administrador',
          '$direccion',
          '$destinatario',
          '$celular_destinatario',
          '$enviador',
          '$envio_mediante',
          '$observaciones',
          '$fecha'
          )");

$rqdp1 = query("SELECT id_participante FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$id_participante = $rqdp2['id_participante'];

logcursos('Asignacion de envio de certificado [cert:'.$id_emision_certificado.']', 'participante-edicion', 'participante', $id_participante);

echo '<div class="alert alert-success">
  <strong>EXITO</strong> asignaci&oacute;n de envio registrado correctamente.
</div>';