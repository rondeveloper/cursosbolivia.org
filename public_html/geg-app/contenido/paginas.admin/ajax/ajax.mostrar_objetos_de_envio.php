<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    $cuces = get('cuces');
    $rqc1 = query("SELECT objeto,cuce FROM convocatorias_nacionales WHERE cuce IN ('".str_replace(",","','",$cuces)."0') limit 70 ");
    echo "<br/>";
    while($rqc2 = mysql_fetch_array($rqc1)){
        echo "".$rqc2['cuce']."<br/>".$rqc2['objeto']."<br/><br/>";
    }
}

?>
