<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

/* data */
$id_emision_certificado = post('id_emision_certificado');
$email_a_enviar = trim(str_replace(' ', '', post('correo')));

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

$rqf1 = query("SELECT * FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqf1) == 0) {
    echo "Certificado inexistente!";
    exit;
}
$emision_certificado = mysql_fetch_array($rqf1);
$id_certificado = $emision_certificado['id_certificado'];
$id_participante = $emision_certificado['id_participante'];
$rdcm1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$modelo_certificado = mysql_fetch_array($rdcm1);

/* datos a usar */
$id_de_certificado = $emision_certificado['certificado_id'];
$receptor_de_certificado = $emision_certificado['receptor_de_certificado'];
$fecha_emision = $emision_certificado['fecha_emision'];

$texto_qr_certificado = $modelo_certificado['texto_qr'];
$fecha_qr_certificado = $modelo_certificado['fecha_qr'];
$codigo_certificado = $modelo_certificado['codigo'];

$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Novimebre','Diciembre');

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del certificado ' . $id_de_certificado . ' emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision)) . ' de ' . $meses[(int)date("m", strtotime($fecha_emision))] . ' de ' . date("Y", strtotime($fecha_emision)) . ' en formato PDF adjuntado en este correo, 
este certificado puede ser impreso por el receptor y ser validado en cualquier momento en la p&aacute;gina <a href="https://cursos.bo/validacion-de-certificado.html">validaci&oacute;n de certificados</a> de CURSOS.BO, 
a continuaci&oacute;n los datos del certificado correspondiente. 
<br/>
<table>
<tr>
<td><b>Curso:</b></td>
<td>' . $texto_qr_certificado . '</td>
</tr>
<tr>
<td><b>Fecha:</b></td>
<td>' . $fecha_qr_certificado . '</td>
</tr>
<tr>
<td><b>ID de certificado:</b></td>
<td>' . $id_de_certificado . '</td>
</tr>
<tr>
<td><b>Receptor de certificado:</b></td>
<td>' . $receptor_de_certificado . '</td>
</tr>
<tr>
<td><b>Fecha de emision:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision)) . '</td>
</tr>
<tr>
<td><b>Codigo cert:</b></td>
<td>' . $codigo_certificado . '</td>
</tr>
</table>
<br/>

</p>
';

//$enviar_a = "brayan.desteco@gmail.com";
$enviar_a = $email_a_enviar;

$asunto = utf8_encode('Certificado ' . $id_de_certificado . ' NEMABOL - Envio digital');
$subasunto = utf8_encode('Certificado digital ' . $id_de_certificado . ' - NEMABOL');

$contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
        . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
        . $htm;
$contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
        . "</div><hr/>";

//variables para los datos del archivo 
$nombrearchivo = "certificado-$id_de_certificado-nemabol.pdf";
$url_archivo = "https://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-digital-3.php?id_certificado=$id_de_certificado";

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
            $mail->setFrom('sistema@cursos.bo', 'CURSOS.BO');
            //$mail->addAddress($correo_a_enviar, 'Nombre');     // Add a recipient
            $mail->addAddress($correo_a_enviar);     // Add a recipient
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //$mail->AddCC('facturacion@infosicoes.com');
            //$mail->AddCC('pagos@infosicoes.com');




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

logcursos('Envio de certificado [correo]['.$id_de_certificado.']['.$email_a_enviar.']', 'participante-edicion', 'participante', $id_participante);

echo "<b>CERTIFICADO ENVIADO CORRECTAMENTE!</b>";
