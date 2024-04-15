<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);



$fecha_reporte = date("d / m / Y",  strtotime('-1 day'));
$asunto = 'LOGS DE MOVIMIENTO DE ADMINISTRADORES ['.$fecha_reporte.'] - CURSOS.BO';

$content = '<h2>LOGS DE MOVIMIENTO DE ADMINISTRADORES ['.$fecha_reporte.'] - CURSOS.BO</h2><hr/>';

$content .= '<div style="font-family:arial;">';

$rqda1 = query("SELECT * FROM administradores WHERE estado='1' AND sw_cursosbo='1' ");
while ($rqda2 = mysql_fetch_array($rqda1)) {
    $nombre_administrador = strtoupper($rqda2['nombre']);
    $id_administrador = $rqda2['id'];

    $content .= '<table border="1" cellpadding="10" style="width:100%;font-size:10pt;">';

    $content .= '<tr>';
    $content .= '<td colspan="7"><b style="font-size:15pt;">' . $nombre_administrador . '</b></td>';
    $content .= '</tr>';

    $rqdl1 = query("SELECT * FROM cursos_log WHERE usuario='administrador' AND id_usuario='$id_administrador' AND DATE(fecha)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) ");
    if (mysql_num_rows($rqdl1)==0) {
        $content .= '<tr>';
        $content .= '<td colspan="7">SIN MOVIMIENTOS</td>';
        $content .= '</tr>';
    }else{
        $content .= '<tr>';
        $content .= '<th>Fecha</th>';
        $content .= '<th>Movimiento</th>';
        $content .= '<th>Proceso</th>';
        $content .= '<th>Entidad</th>';
        $content .= '<th>ID entidad</th>';
        $content .= '<th>Nombre entidad</th>';
        $content .= '<th>IP</th>';
        $content .= '</tr>';
    }
    while ($rqdl2 = mysql_fetch_array($rqdl1)) {

        $id_movimiento = $rqdl2['id'];
        $movimiento = $rqdl2['movimiento'];
        $proceso = $rqdl2['proceso'];
        $objeto = $rqdl2['objeto'];
        $id_objeto = $rqdl2['id_objeto'];
        $ip = $rqdl2['ip'];
        $fecha = $rqdl2['fecha'];
        
        switch ($objeto) {
            case 'curso':
                $rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                if(mysql_num_rows($rqdc1)==0){
                    continue;
                }
                $rqdc2 = mysql_fetch_array($rqdc1);
                $nombre_entidad = $rqdc2['titulo'];
                break;
            case 'participante':
                $rqdc1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                if(mysql_num_rows($rqdc1)==0){
                    continue;
                }
                $rqdc2 = mysql_fetch_array($rqdc1);
                $nombre_entidad = $rqdc2['nombres'].' '.$rqdc2['apellidos'];
                break;
            default:
                $nombre_entidad = 'No aplica';
                break;
        }

        $content .= '<tr>';
        $content .= '<td>' . $fecha . '</td>';
        $content .= '<td>' . $movimiento . '</td>';
        $content .= '<td>' . $proceso . '</td>';
        $content .= '<td>' . $objeto . '</td>';
        $content .= '<td>' . $id_objeto . '</td>';
        $content .= '<td>' . $nombre_entidad . '</td>';
        $content .= '<td>' . $ip . '</td>';
        $content .= '</tr>';
    }

    $content .= '</table>';

    $content .= '<br/><br/><hr/><br/><br/>';
}

$content .= '</div>';

//echo $content;


$subject = $asunto;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: CURSOS.BO <sistema@cursos.bo>" . "\r\n";
 
$message = $content;
 
mail("desteco@gmail.com", $subject, $message, $headers);
mail("brayan.desteco@gmail.com", $subject, $message, $headers);

echo "<hr/><h2>CORREO ENVIADO</h2>";
