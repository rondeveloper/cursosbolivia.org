<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
include_once '../../librerias/correo/class.phpmailer.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* PHPMAILER REQUIRED */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../librerias/test/vendor/autoload.php';

/* ID EMPRESA */
$id_empresa = (int) post('dat');

$rqde1 = query("SELECT id_correo_notificador FROM empresas WHERE id='$id_empresa' ORDER BY id DESC limit 1 ");
$rqde2 = mysql_fetch_array($rqde1);
$id_correo_notificador = $rqde2['id_correo_notificador'];

switch ($id_correo_notificador) {
    case '1':
        $new_id_correo_notificador = '2';
        break;
    case '2':
        $new_id_correo_notificador = '3';
        break;
    case '3':
        $new_id_correo_notificador = '1';
        break;
    default :
        $new_id_correo_notificador = '1';
        break;
}
query("UPDATE empresas SET id_correo_notificador='$new_id_correo_notificador' WHERE id='$id_empresa' ORDER BY id DESC limit 1 ");

$result = enviar_correo_autentificacion_a_empresa($id_empresa);
if ($result) {
    echo "<img src='contenido/imagenes/images/bien.png' style='width:25px;'/> ENVIADO";
} else {
    echo "Error";
}

