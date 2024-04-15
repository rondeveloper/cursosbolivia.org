<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
include_once '../../librerias/correo/class.phpmailer.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* PHPMAILER REQUIRED */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (isset_administrador() && post('dat')) {

    $id_empresa = post('dat');
    $result = enviar_correo_datos_de_ingreso_a_empresa($id_empresa);
    if ($result) {
        echo " <img src='".$dominio_www."contenido/imagenes/images/bien.png' style='width:25px;'>  Datos enviados!";
    } else {
        echo "Error.";
    }
} else {
    echo "Denegado!";
}
