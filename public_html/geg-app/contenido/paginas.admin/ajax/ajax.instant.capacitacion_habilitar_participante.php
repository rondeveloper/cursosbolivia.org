<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    
    
    $id_participante = post('dat');
    
    query("UPDATE capacitaciones_participantes SET estado='1' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    
    movimiento('Habilitacion de participante de capacitacion [' . $id_participante . ']', 'eliminacion-capacitacion-partipante', 'participante', $id_participante);

    echo "<td colspan='35' style='text-align:center;color:gray;'>Participante habilitado!</td>";
    
} else {
    echo "Denegado!";
}
?>
