<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $cuces = get('cuces');
    $rqc1 = query("SELECT objeto,cuce FROM convocatorias_nacionales WHERE cuce IN ('".str_replace(",","','",$cuces)."0') limit 70 ");
    echo "<br/>";
    while($rqc2 = fetch($rqc1)){
        echo "".$rqc2['cuce']."<br/>".$rqc2['objeto']."<br/><br/>";
    }
}

?>
