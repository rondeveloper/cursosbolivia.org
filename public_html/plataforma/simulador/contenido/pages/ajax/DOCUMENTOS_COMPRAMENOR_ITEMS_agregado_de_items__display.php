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

$rqit1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='0' ");
$cnt = 1;
while ($rqit2 = fetch($rqit1)) {
    /* verificacion de existencia */
    $rqverif1 = query("SELECT id FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' AND descripcion LIKE '".$rqit2['descripcion']."' AND cantidad='".$rqit2['cantidad']."' ORDER BY id DESC limit 1 ");
    if(isset_post('item-'.$rqit2['id']) && (num_rows($rqverif1)==0)){
        query("INSERT INTO simulador_items
        (id_usuario, cuce, descripcion, unidad_medida, cantidad, precio_unitario, precio_total) 
        VALUES 
        ('$id_usuario','$cuce','".$rqit2['descripcion']."','".$rqit2['unidad_medida']."','".$rqit2['cantidad']."','".$rqit2['precio_unitario']."','".$rqit2['precio_total']."')");
    }
}

echo "OK";
