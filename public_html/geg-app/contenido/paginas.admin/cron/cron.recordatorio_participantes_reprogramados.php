<?php

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$rqpr1 = query("SELECT * FROM cursos_reprogramacion_participantes WHERE estado='1' ");
echo "<table cellpadding='10' border='1'>";
while ($rqpr2 = mysql_fetch_array($rqpr1)) {
    $id_participante = $rqpr2['id_participante'];
    $id_curso = $rqpr2['id_curso'];
    $codigo_reprogramacion = $rqpr2['codigo'];
    $correo_notificacion = $rqpr2['correo'];

    /* participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdp2 = mysql_fetch_array($rqdp1);
    $nombre_participante = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
    $id_proceso_registro = $rqdp2['id_proceso_registro'];
    
    /* proceso registro */
    $rqdpr1 = query("SELECT codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    $rqdpr2 = mysql_fetch_array($rqdpr1);
    $codigo_proceso_regsitro = $rqdpr2['codigo'];

    /* curso */
    $rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' LIMIT 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);
    $nombre_curso = $rqdc2['titulo'];
    

    /* correo */
    $contenido_correo = "<div style='font-family:Helvetica,sans-serif,Arial;'>";
    $contenido_correo .= "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Reprogramaci&oacute;n de curso</h2>";
    $contenido_correo .= "<center><a href='https://cursos.bo'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
    $contenido_correo .= "<p>Estimad@ ".ucfirst(strtolower($nombre_participante))."</p>";
    $contenido_correo .= "<p>Queremos recordarle que tiene pendiente un curso por realizar de manera presencial, el curso corresponde a '$nombre_curso' y debe ser cursado por '$nombre_participante' en pr&oacute;ximas fechas.</p>";
    $contenido_correo .= "<p>Le invitamos a pasar por nuestras oficinas e inscribirse al curso correspondiente, le mostramos a continuaci&oacute;n los cursos que se encuentran programados para esta semana:</p>";
    
    $rqpc1 = query("SELECT * FROM cursos WHERE estado='1' ");
    $contenido_correo .= "<table style='width: 80%;
    margin: 20px auto;
    background: #FFF;
    border: 2px solid #a3b2c5;
    text-align: center;
    padding: 4px;'>";
    $contenido_correo .= "<tr>";
    $contenido_correo .= "<th style='background: #e3eefb;font-family:arial;padding: 8px 5px;font-size:10pt;'>FECHA</th>";
    $contenido_correo .= "<th style='background: #e3eefb;font-family:arial;padding: 8px 5px;font-size:10pt;'>CURSO</th>";
    $contenido_correo .= "<th style='background: #e3eefb;font-family:arial;padding: 8px 5px;font-size:10pt;'>DETALLES</th>";
    $contenido_correo .= "</tr>";
    while ($rqpc2 = mysql_fetch_array($rqpc1)) {
        $contenido_correo .= "<tr>";
        $contenido_correo .= "<td style='font-family:helvetica;padding: 8px 5px;font-size:11pt;border:1px solid #DDD;'>".date("d / m / Y",strtotime($rqpc2['fecha']))."</td>";
        $contenido_correo .= "<td style='font-family:helvetica;padding: 8px 5px;font-size:11pt;border:1px solid #DDD;'>".$rqpc2['titulo']."</td>";
        $contenido_correo .= "<td style='font-family:helvetica;padding: 8px 5px;font-size:11pt;border:1px solid #DDD;'><a href='https://cursos.bo/".$rqpc2['titulo_identificador'].".html'>Ver detalles</a></td>";
        $contenido_correo .= "</tr>";
    }
    $contenido_correo .= "</table>";
    
    $contenido_correo .= "<div style='font-size:10pt;'>";
    $contenido_correo .= "<b>Participante:</b> " . $nombre_participante . "<br />";
    $contenido_correo .= "<b>Curso:</b> " . $nombre_curso . "<br />";
    $contenido_correo .= "<b>C&oacute;digo reprogramaci&oacute;n:</b> " . $codigo_reprogramacion . "<br />";
    $contenido_correo .= "<b>C&oacute;digo de registro:</b> " . $codigo_proceso_regsitro . "<br />";
    $contenido_correo .= "</div>";
    
    $contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Saludos cordiales</h3>";
    $contenido_correo .= "</div>";
    
    /* envio */
    $asunto = "Curso pendiente a realizar - ".ucfirst(strtolower($nombre_participante));
    enviar_email_2($correo_notificacion, $asunto, $contenido_correo);
    

    echo "<tr>";

    echo "<td>" . $codigo_reprogramacion . "</td>";
    echo "<td>" . $nombre_participante . "</td>";
    echo "<td>" . 'ENVIADO' . "</td>";
   
    echo "</tr>";
}
echo "</table>";


/* enviar_email_2 */
function enviar_email_2($correo_a_enviar, $asunto, $contenido_correo) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "Reply-To: CURSOS.BO <info@nemabol.com>" . "\r\n";
    if($correo_a_enviar!=='brayan.desteco@gmail.com'){
        $headers .= "Bcc: brayan.desteco@gmail.com" . "\r\n";
    }
    $headers .= "From: CURSOS.BO <sistema@cursos.bo>" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    mail($correo_a_enviar, $asunto, $contenido_correo, $headers);
}
