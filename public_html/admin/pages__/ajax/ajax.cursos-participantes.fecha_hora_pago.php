<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

$id_participante = post('id_participante');

$rqvhc1 = query("SELECT * FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON p.id_proceso_registro=r.id WHERE p.id='$id_participante' ORDER BY r.id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);

echo "<br><b>Fecha de pago:</b><br>";
if ($rqvhc2['pago_fechahora'] == '0000-00-00 00:00:00') {
    echo "Sin fecha";
} else {
    echo date("d / m / Y - H:i", strtotime($rqvhc2['pago_fechahora']));
}
