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

/* data */
$id_emision_certificado = post('id_emision_certificado');

/* registros */
$rqdr1 = query("SELECT p.correo,p.id,e.certificado_id,e.receptor_de_certificado,e.fecha_emision,c.texto_qr FROM cursos_participantes p INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados c ON e.id_certificado=c.id WHERE e.id='$id_emision_certificado' ORDER BY e.id DESC limit 1 ");
$rqdr2 = mysql_fetch_array($rqdr1);

$id_participante = $rqdr2['id'];
$correo_participante = $rqdr2['correo'];
$certificado_id = $rqdr2['certificado_id'];
$receptor_de_certificado = $rqdr2['receptor_de_certificado'];
$fecha_emision_certificado = $rqdr2['fecha_emision'];
$texto_qr_certificado = $rqdr2['texto_qr'];

if (strlen($correo_participante) <= 5) {
    echo "Correo invalido!";
    exit;
}

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del certificado ' . $certificado_id . ' emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision_certificado)) . ' de ' . date("M", strtotime($fecha_emision_certificado)) . ' de ' . date("Y", strtotime($fecha_emision_certificado)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos del certificado correspondiente. 
<br/>
</p>

<table>
<tr>
<td><b>ID de certificado:</b></td>
<td>' . $certificado_id . '</td>
</tr>
<tr>
<tr>
<td><b>Certificado:</b></td>
<td>' . utf8_decode($texto_qr_certificado) . '</td>
</tr>
<tr>
<td><b>Receptor del certificado:</b></td>
<td>' . utf8_decode($receptor_de_certificado) . '</td>
</tr>
<tr>
<td><b>Fecha de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision_certificado)) . '</td>
</tr>
</table>
<br/>
';


$asunto = utf8_decode('CERTIFICADO DIGITAL '.$certificado_id.' - ' . $texto_qr_certificado);
$subasunto = utf8_decode($texto_qr_certificado);

$contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
        . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
        . $htm;
$contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
        . "</div><hr/>";

/* variables para los datos del archivo */
$nombrearchivo = "certificado-$certificado_id.pdf";
$url_archivo = $dominio . "contenido/librerias/fpdf/tutorial/certificado-digital-3.php?id_certificado=$certificado_id";


$archivo_cont = file_get_contents($url_archivo);

$subject = $asunto;
$body = $contenido_correo;

$nuevoarchivo = fopen($nombrearchivo, "w+");
fwrite($nuevoarchivo, $archivo_cont);
fclose($nuevoarchivo);

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
    $mail->addAddress($correo_participante);     // Add a recipient
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('brayan.desteco@gmail.com');
    //$mail->AddCC('certificadocion@infosicoes.com');
    //$mail->AddCC('pagos@infosicoes.com');
    $mail->addCC($correo_administrador);

    /* Content */
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->AddAttachment($nombrearchivo, $nombrearchivo); //adjuntamos archivo

    $mail->Send(); //Enviar
    //return true;
} catch (phpmailerException $e) {
    echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
    //return false;
}

unlink($nombrearchivo);

logcursos('Envio digital de certificado [' . $certificado_id . '] [' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);

echo "<b>CERTIFICADO ENVIADO CORRECTAMENTE!</b>";
