<?php

/* REQUERIDO PHP MAILER */

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
    echo "Denegado!";
    exit;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='" . administrador('id') . "' LIMIT 1 ");
$rqddad2 = mysql_fetch_array($rqddad1);
$correo_administrador = $rqddad2['email'];

/* datos recibidos */
$id_participante = post('id_participante');

/* datos participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$ci_participante = $rqdp2['ci'];
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$email_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_usuario = $rqdp2['id_usuario'];

/* password */
$rqvpc1 = query("SELECT password FROM cursos_usuarios WHERE id='$id_usuario' ");
$rqvpc2 = mysql_fetch_array($rqvpc1);
$password = $rqvpc2['password'];


/* datos curso */
$rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$id_curso = $rqdc2['id_curso'];
$qrddcdp1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$qrddcdp2 = mysql_fetch_array($qrddcdp1);
$titulo_curso = $qrddcdp2['titulo'];


/* content text */
$texto_principal = '<p><span><strong>Datos de ingreso a cursos virtuales</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Le enviamos en este correo los datos de acceso a los cursos virtuales a los cuales se registro.</span> Recuerde que el acceso al curso y sus recursos estar&aacute;n disponibles para usted las 24 horas del d&iacute;a desde su primer ingreso hasta 8 semanas despu&eacute;s.</p>
<br/>
<p align="justify">Para ingresar al curso debe seguir estos sencillos pasos y comenzar a explorar el espacio virtual:</p>
<p>1. Desde el navegador web ingrese a la URL de ingreso<br>
<p>2. Ingrese el usuario y contrase&ntilde;a de ingreso a su cuenta</p>
<p>3. Presione el boton "INGRESAR"</p>
<br/>
<br/>
<b>DATOS DE ACCESO</b>
<br/>
<table>';

/* datos cursos virtuales */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND cv.id IN (select id_onlinecourse from cursos_onlinecourse_acceso where id_usuario='$id_usuario') ORDER BY r.id ASC ");
while($rqdcv2 = mysql_fetch_array($rqdcv1)){
    $nombre_curso_virtual = $rqdcv2['titulo'];
    $url_curso_virtual = 'https://cursos.bo/curso-online/' . $rqdcv2['urltag'] . '.html';
    $fecha_incio_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_inicio']));
    $fecha_final_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_final']));
    
    $texto_principal .= '
    <tr>
    <td style="padding:7px 10px;border: 1px solid gray;">Curso:</td>
    <td style="padding:7px 10px;border: 1px solid gray;">' . $nombre_curso_virtual . '</td>
    </tr>
    <tr>
    <td style="padding:7px 10px;border: 1px solid gray;">Url de ingreso:</td>
    <td style="padding:7px 10px;border: 1px solid gray;"><a href="'.$url_curso_virtual.'">' . $url_curso_virtual . '</a></td>
    </tr>
    <tr>
    <td style="padding:7px 10px;border: 1px solid gray;">Usuario:</td>
    <td style="padding:7px 10px;border: 1px solid gray;">' . $email_participante . '</td>
    </tr>
    <tr>
    <td style="padding:7px 10px;border: 1px solid gray;">Contrase&ntilde;a:</td>
    <td style="padding:7px 10px;border: 1px solid gray;">' . $password . '</td>
    </tr>
    <tr>
    <td style="padding:7px 10px;border: 1px solid gray;">&nbsp;</td>
    <td style="padding:7px 10px;border: 1px solid gray;">&nbsp;</td>
    </tr>';
}

$texto_principal .= '</table>
<br/>
<br/>
<p>Recuerde que la modalidad de este curso es virtual y su &eacute;xito depender&aacute; de su compromiso y disciplina en el seguimiento de todo el proceso de formaci&oacute;n. Distribuya su tiempo de manera adecuada para cumplir con los planes y objetivos establecidos en este proceso de aprendizaje.<br>
<br>
<p>Saludos cordiales</em></p>
<p>&nbsp;</p>
                                    ';
/* cont correo */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>" . utf8_decode($titulo_curso) . "</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $texto_principal;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
$asunto = "DATOS DE INGRESO cursos virtuales - ".$titulo_curso;

$subject = utf8_decode($asunto);
$body = $contenido_correo;
try {

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
    $mail->addAddress($email_participante);     // Add a recipient
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
    //$mail->AddCC('facturacion@infosicoes.com');
    //$mail->AddCC('pagos@infosicoes.com');
    $mail->addCC($correo_administrador);




    /* Content */
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->Send(); //Enviar
    //return true;
} catch (phpmailerException $e) {
    echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
    //return false;
}
logcursos('Envio de correo acceos cursos-virtuales', 'partipante-cvirtual', 'participante', $id_participante);

echo "<b>CORREO ENVIADO CORRECTAMENTE!</b>";

