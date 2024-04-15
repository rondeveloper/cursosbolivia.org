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

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='" . administrador('id') . "' LIMIT 1 ");
$rqddad2 = mysql_fetch_array($rqddad1);
$correo_administrador = $rqddad2['email'];

$id_factura = get('id_factura');
$email_a_enviar = trim(str_replace(' ', '', get('email_a_enviar')));

$rqf1 = query("SELECT * FROM facturas_emisiones WHERE id='$id_factura' ORDER BY id ASC limit 1 ");
if (mysql_num_rows($rqf1) == 0) {
    echo "Factura inexistente!";
    exit;
}
$rqf2 = mysql_fetch_array($rqf1);

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

$codigo_de_control = $rqf2['codigo_de_control'];
$nro_autorizacion = $rqf2['nro_autorizacion'];
$fecha_emision = $rqf2['fecha_emision'];
$fecha_limite_emision = $rqf2['fecha_limite_emision'];
$nit_emisor = $rqf2['nit_emisor'];
$concepto = $rqf2['concepto'];
$nro_factura = $rqf2['nro_factura'];

$nombre_receptor = $rqf2['nombre_receptor'];
$nit_receptor = $rqf2['nit_receptor'];
$total = $rqf2['total'];

$numeral_factura = str_pad($nro_factura, 5, "0", STR_PAD_LEFT);

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o de la factura n&uacute;mero ' . $numeral_factura . ' emitida por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision)) . ' de ' . date("M", strtotime($fecha_emision)) . ' de ' . date("Y", strtotime($fecha_emision)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos de la factura correspondiente. 
<br/>
<table>
<tr>
<td><b>Concepto:</b></td>
<td>' . str_replace('.', ' . ', $concepto) . '</td>
</tr>
<tr>
<td><b>Factura a nombre de:</b></td>
<td>' . $nombre_receptor . '</td>
</tr>
<tr>
<td><b>N&uacute;mero de NIT:</b></td>
<td>' . $nit_receptor . '</td>
</tr>
<tr>
<td><b>Monto de facturaci&oacute;n:</b></td>
<td>' . number_format($total, 2, '.', '') . ' Bs.</td>
</tr>
<tr>
<td><b>N&uacute;mero de factura:</b></td>
<td>' . $numeral_factura . '</td>
</tr>
<tr>
<td><b>Fecha de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision)) . '</td>
</tr>
<tr>
<td><b>Codigo de control:</b></td>
<td>' . $codigo_de_control . '</td>
</tr>
<tr>
<td><b>N&uacute;mero de autorizaci&oacute;n:</b></td>
<td>' . $nro_autorizacion . '</td>
</tr>
<tr>
<td><b>Fecha limite de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_limite_emision)) . '</td>
</tr>
<tr>
<td><b>NIT emisor:</b></td>
<td>' . $nit_emisor . '</td>
</tr>
</table>
<br/>

</p>
';

//$enviar_a = "brayan.desteco@gmail.com";
$enviar_a = $email_a_enviar;

$asunto = utf8_encode('Factura ' . $numeral_factura . ' NEMABOL - Envio digital');
$subasunto = utf8_encode('Factura n&uacute;mero ' . $numeral_factura . ' - NEMABOL');

$contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
        . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
        . $htm;
$contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
        . "</div><hr/>";

//variables para los datos del archivo 
$nombrearchivo = "factura-$numeral_factura-nemabol.pdf";
$url_archivo = $dominio . "contenido/librerias/fpdf/tutorial/factura-1.php?id_factura=$id_factura";

$archivo_cont = file_get_contents($url_archivo);

$correo = $enviar_a;
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
            $mail->AddCC('facturacion@infosicoes.com');
            $mail->AddCC('pagos@infosicoes.com');
            $mail->addCC($correo_administrador);




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

movimiento('Envio digital de factura [numero: ' . $numeral_factura . '] [' . $email_a_enviar . ']', 'envio-factura', 'factura', $rqf2['id']);

echo "<b>FACTURA ENVIADA CORRECTAMENTE!</b>";
