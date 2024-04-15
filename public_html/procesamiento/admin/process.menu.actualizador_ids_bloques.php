<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


/* bloque activo */
$rqbids1 = query("SELECT id FROM cursos WHERE estado=1 ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='1' ");

/* bloque temporal */
$rqbids1 = query("SELECT id FROM cursos WHERE estado=2 ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='2' ");

/* bloque virtual */
$rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad!='1' ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='3' ");

echo " &nbsp;<i class='fa fa-thumbs-up'></i>";
