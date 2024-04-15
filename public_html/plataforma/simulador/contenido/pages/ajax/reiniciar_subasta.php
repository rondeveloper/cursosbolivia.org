<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$dat = get('dat');
$id_usuario = usuario('id_sim');

$rqdv1 = query("SELECT id FROM simulador_sigep_propuestas WHERE item=0 AND fecha>=(DATE_SUB(NOW(), INTERVAL 1 HOUR)) AND id_usuario<>'$id_usuario' ");
if (num_rows($rqdv1) == 0) {
    if ($dat == 'total') {
        query("DELETE FROM simulador_sigep_propuestas WHERE item=0 ");
    } else {
        query("DELETE FROM simulador_sigep_propuestas WHERE item<>0 ");
    }
}


if ($dat == 'total') {
    $rqdv1 = query("SELECT id FROM simulador_sigep_propuestas WHERE item=0 AND fecha>=(DATE_SUB(NOW(), INTERVAL 1 HOUR)) AND id_usuario<>'$id_usuario' ");
    if (num_rows($rqdv1) == 0) {
        query("DELETE FROM simulador_sigep_propuestas WHERE item=0 ");
    }
} else {
    $rqdv1 = query("SELECT id FROM simulador_sigep_propuestas WHERE item<>0 AND fecha>=(DATE_SUB(NOW(), INTERVAL 1 HOUR)) AND id_usuario<>'$id_usuario' ");
    if (num_rows($rqdv1) == 0) {
        query("DELETE FROM simulador_sigep_propuestas WHERE item<>0 ");
    }
}