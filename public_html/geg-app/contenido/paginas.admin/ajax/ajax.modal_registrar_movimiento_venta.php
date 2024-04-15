<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador() && isset_post('dat')) {
    
    $id_empresa = post('dat');
    $movimiento = post('movimiento');
    
    movimiento($movimiento, 'registro-vent-paq', 'usuario', $id_empresa);

    $rqmemp1 = query("SELECT * FROM movimiento WHERE id_objeto='$id_empresa' AND proceso LIKE '%-vent-paq' ORDER BY id DESC limit 50 ");
    echo "<table class='table'>";
    if(mysql_num_rows($rqmemp1)==0){
        echo "<tr><td>No se tiene regsitros para este cliente.</td></tr>";
    }
    while($rqmemp2 = mysql_fetch_array($rqmemp1)){
        echo "<tr>";
        echo "<td class='text-left'>".$rqmemp2['movimiento']."</td>";
        echo "<td>".$rqmemp2['fecha']."</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "Denegado!";
}

?>
