<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* DATOS A UTILIZAR*/
$id_tienda_registro = 1;

/* PLATILLA A UTILIZAR*/
$email_template = 'enviarInfoCursos';

/* ESTRUCTURA DEL ARRAY DATA */
$data = [
    '_subtitulo' => 'CURSOS QUE LE PODRÍAN INTERESAR',
    '_email_unsubscribe' => 'test@email.com',
    '_nombre_referencia' => 'Usuario',
];
$contenido_correo = EmailUtil::generarContenidoEmailHTML($email_template, $data);

/* IMPRESION DEL CORREO */
echo $contenido_correo;

/* ESTILO BASE (NO MODIFICAR) */
echo '<style>body{padding:0;margin:0;}</style>';
