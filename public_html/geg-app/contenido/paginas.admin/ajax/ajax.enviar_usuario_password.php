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
/* END PHPMAILER REQUIRED */

if (isset_administrador() && post('dat')) {

    $id_empresa = post('dat');
    $result = enviar_correo_datos_de_ingreso_a_empresa($id_empresa);
    if ($result) {
        echo " <img src='contenido/imagenes/images/bien.png' style='width:25px;'>  Datos enviados!";
    } else {
        echo "Error.";
    }
} else {
    echo "Denegado!";
}
