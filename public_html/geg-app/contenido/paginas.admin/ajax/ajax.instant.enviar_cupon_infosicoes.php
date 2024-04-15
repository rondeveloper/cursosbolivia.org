<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(1);
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
//include_once '../../librerias/correo/class.phpmailer.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


//Load Composer's autoloader
require '../../librerias/phpmailer/vendor/autoload.php';

if (isset_administrador()) {

    $id_emision_cupon_infosicoes = post('id_emision_cupon_infosicoes');
    
    $rqf1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id='$id_emision_cupon_infosicoes' ORDER BY id ASC limit 1 ");
    if (mysql_num_rows($rqf1) == 0) {
        echo "Cupon inexistente! [$id_emision_cupon_infosicoes]";
        exit;
    }
    $rqf2 = mysql_fetch_array($rqf1);
    
    $id_cupon = $rqf2['id_cupon'];
    $id_curso = $rqf2['id_curso'];
    $id_participante = $rqf2['id_participante'];
    $codigo = $rqf2['codigo'];
    $fecha_registro = $rqf2['fecha_registro'];
    $estado = $rqf2['estado'];
    
    $rqcp1 = query("SELECT correo,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC ");
    if (mysql_num_rows($rqcp1) == 0) {
        echo "Participante inexistente! [$id_participante]";
        exit;
    }
    $rqcp2 = mysql_fetch_array($rqcp1);    
    $nombre = $rqcp2['nombres'] . ' ' . $rqcp2['apellidos'];
    $correo = $rqcp2['correo'];
    
    $numeral_factura = str_pad($nro_factura, 5, "0", STR_PAD_LEFT);

    $htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del cup&oacute;n ' . $codigo . ' emitida por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_registro)) . ' de ' . date("M", strtotime($fecha_registro)) . ' de ' . date("Y", strtotime($fecha_registro)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos del cup&oacute;n correspondiente. 
<br/>
<table>
<tr>
<td><b>Receptor:</b></td>
<td>' . $nombre . '</td>
</tr>
<tr>
<td><b>Correo:</b></td>
<td>' . $correo . '</td>
</tr>
<tr>
<td><b>Cup&oacute;n:</b></td>
<td>' . $codigo . '</td>
</tr>
</table>
<br/>
</p>
';

    //$correo = "brayan.desteco@gmail.com";

    $asunto = utf8_encode('Cupon ' . $codigo . ' INFOSICOES - Envio digital');
    $subasunto = utf8_encode('Cupon ' . $codigo . ' - INFOSICOES');

    $contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
            . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
    $contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
            . $htm;
    $contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
            . "</div><hr/>";

    //variables para los datos del archivo 
    $nombrearchivo = "cupon-infosicoes-$id_cupon-nemabol.pdf";
    $url_archivo = $dominio."contenido/librerias/fpdf/tutorial/cupon-infosicoes.php?id_cupon=$id_cupon&id_participante=$id_participante";

    $archivo_cont = file_get_contents($url_archivo);

    $subject = $asunto;
    $body = $contenido_correo;

    $nuevoarchivo = fopen($nombrearchivo, "w+");
    fwrite($nuevoarchivo, $archivo_cont);
    fclose($nuevoarchivo);



    $array_correos_a_enviar = explode(",", $correo);
    foreach ($array_correos_a_enviar as $correo_a_enviar) {

        if (strlen($correo_a_enviar) > 3) {

            try {
                
                $mail->Host = 'cursos.bo';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'sistema@cursos.bo';                 // SMTP username
                $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465; 
                

                $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'cursos.bo';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'sistema@cursos.bo';                 // SMTP username
                $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to
                //Recipients
                $mail->setFrom('sistema@cursos.bo', 'Facturaciones Cursos.BO');
                //$mail->addAddress($correo_a_enviar, 'Nombre');     // Add a recipient
                $mail->addAddress($correo_a_enviar);     // Add a recipient
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');
                //*$mail->AddCC('facturacion@infosicoes.com');
                //*$mail->AddCC('pagos@infosicoes.com');

                
                /* Content */
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body = $body;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $mail->AddAttachment($nombrearchivo, $nombrearchivo); //adjuntamos archivo

/*

                $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas

                $mail->IsSMTP();                           // Usamos el metodo SMTP de la clase PHPMailer
                $mail->SMTPAuth = true;                  // habilitado SMTP autentificación
                $mail->SMTPSecure = "tls";
                $mail->Timeout = 30;                  // habilitado SMTP autentificación

                $mail->SMTPDebug = 2;

                $mail->Port = 587;                // puerto del server SMTP
                $mail->Host = "mail.infosicoes.com";  // SMTP server
                $mail->Username = "infosicoes@infosicoes.com";     // SMTP server Usuario
                $mail->Password = "Pw4w3BXpZ$5";            // SMTP server password
                $mail->From = "infosicoes@infosicoes.com"; //Remitente de Correo
                $mail->FromName = "InfoSICOES"; //Nombre del remitente
                $to = $correo_a_enviar; //Para quien se le va enviar
                $mail->AddAddress($to);
                $mail->AddCC('facturacion@infosicoes.com');
                $mail->AddCC('pagos@infosicoes.com');
                $mail->Subject = $subject; //Asunto del correo
                $mail->AddAttachment($nombrearchivo, $nombrearchivo); //adjuntamos archivo
                $mail->MsgHTML($body);
                $mail->IsHTML(true); // Enviar como HTML
                */
                
                
                $mail->Send(); //Enviar
                //return true;
            } catch (phpmailerException $e) {
                echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
                //return false;
            }
        }
    }

    unlink($nombrearchivo);

    movimiento('Envio digital de cupon [ID-emision: ' . $id_emision_cupon_infosicoes . '] [' . $correo . ']', 'envio-cupon', 'cupon', $id_emision_cupon_infosicoes);

    echo "<b style='color:green;'>CUPON ENVIADO</b>";
} else {
    echo "Denegado!";
}
