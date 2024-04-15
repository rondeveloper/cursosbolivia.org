<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/* page */
$hora = '0';
if (isset($_GET['hora'])) {
    $hora = $_GET['hora'];
}
$actual_time = date("d/M/Y H:i");

$subject = "Cron informe - ".$hora." - ".$actual_time;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: CURSOS.BO <sistema@cursos.bo>" . "\r\n";
 
$message = "Informe cron enviado codigo-hora: ".$hora." , hora en el server ".$actual_time;
 
mail("brayan.desteco@gmail.com", $subject, $message, $headers);

echo "Mensaje enviado";