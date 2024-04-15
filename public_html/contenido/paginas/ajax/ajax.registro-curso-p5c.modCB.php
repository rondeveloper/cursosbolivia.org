<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$id_banco = post('cod');

$rqdcb1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
while($rqdcb2 = fetch($rqdcb1)){
    echo '<option value="'.$rqdcb2['id'].'">'.$rqdcb2['numero_cuenta'].' &nbsp; '.$rqdcb2['titular'].'</option>';
}


