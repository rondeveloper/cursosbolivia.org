<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    
    
    $id_participante = post('dat');
    
    query("UPDATE cursos_participantes SET estado='0' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    
    movimiento('Eliminacion de participante de curso [' . $id_participante . ']', 'eliminacion-curso-partipante', 'participante', $id_participante);

    echo "<td colspan='35' style='text-align:center;color:gray;'>Participante eliminado!</td>";
    
} else {
    echo "Denegado!";
}
?>
