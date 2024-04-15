<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_certificado = post('id_certificado_autorizacion');
$id_administrador = administrador('id');

if(isset_post('aprobar')){ 
    query("UPDATE certificados_autorizaciones SET estado = '1', fecha_aprobacion = NOW(), id_administrador_autorizador='$id_administrador' WHERE id='$id_certificado' ORDER BY id LIMIT 1 ");
    logcursos('Aprobacion de impresion de certificado', 'aprobacion-certificado', 'certificado', $id_certificado);
    echo "<div class='alert alert-success'>
    <strong>Exito!</strong> Registro aprobado.
    </div>";
} 
if(isset_post('denegar')){ 
    query("UPDATE certificados_autorizaciones SET estado = '2' WHERE id='$id_certificado' ORDER BY id LIMIT 1 ");
    logcursos('Denegacion de impresion de certificado', 'denegacion-certificado', 'certificado', $id_certificado);
    echo "<div class='alert alert-success'>
    <strong>Exito!</strong> Registro denegado.
    </div>";
} 


?>


