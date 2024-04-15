<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

/* datos post */
$id_tienda_registro = post('id_tienda_registro');

$enviar_a = "brayan.desteco@gmail.com";
//$enviar_a = $email_a_enviar;
$aux_id_registro = $id_tienda_registro + 1000;
$asunto = ('URL DE REGISTRO '.$aux_id_registro);

$data = [
    '_subtitulo' => $asunto,
    '_email_unsubscribe' => $enviar_a,
    '_nombre_referencia' => 'Usuario',
    'id_tienda_registro' => $id_tienda_registro,
];
$contenido_correo = EmailUtil::generarContenidoEmailHTML('enviarUrlPago', $data);

/* permite enviar el correo */
SISTsendEmail($enviar_a, $asunto, $contenido_correo);

?>
<div class='alert alert-success'>
    <strong>EXITO</strong> Se envio la URL correctamente.
</div>
