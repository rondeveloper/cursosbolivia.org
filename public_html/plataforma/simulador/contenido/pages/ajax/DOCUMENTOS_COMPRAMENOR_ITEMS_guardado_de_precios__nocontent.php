<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$cuce = '21-0513-00-1151485-1-1';

$rqit1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
$cnt = 1;
while ($rqit2 = fetch($rqit1)) {
    /* verificacion de existencia */
    if (isset_post('item-' . $rqit2['id'])) {
        $valor = post('item-' . $rqit2['id']);
        query("UPDATE simulador_items SET precio_ofertado='$valor' WHERE id='" . $rqit2['id'] . "' ");
    }
}

echo "OK";
