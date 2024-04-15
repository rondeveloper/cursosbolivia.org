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


if (isset_administrador()) {
    
    $id_proceso_de_registro = post('sendficha_id_proceso_registro');
    $email_a_enviar = trim(str_replace(' ','',post('sendficha_correo')));

    if (strlen($email_a_enviar) <= 5) {
        echo "Correo invalido!";
        exit;
    }

    $htm = '
<p>
Estimad@
<br/>
Se le hace el env&iacute;o de la ficha de registro, para el curso al cual usted realizo una inscripci&oacute;n. 
<br/>
<br/>
Saludos cordiales
<br/>
<br/>
</p>
';

    //$enviar_a = "brayan.desteco@gmail.com";
    $enviar_a = $email_a_enviar;

    $asunto = utf8_encode('FICHA DE REGISTRO PARA CURSO');
    $subasunto = utf8_encode('FICHA DE REGISTRO PARA CURSO');

    $contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
            . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
    $contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
            . $htm;
    $contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
            . "</div><hr/>";

    //variables para los datos del archivo 
    $nombrearchivo = "ficha-de-registro-$id_proceso_de_registro.pdf";
    $url_archivo = "https://cursos.bo/".encrypt('registro-participantes-curso/' . $id_proceso_de_registro . '/pdf').".impresion";

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
                $mail->setFrom('sistema@cursos.bo', 'Cursos BOLIVIA');
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

                
                $mail->Send(); //Enviar
                //return true;
            } catch (phpmailerException $e) {
                echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
                //return false;
            }
        }
    }

    unlink($nombrearchivo);

    echo "<b>FICHA ENVIADA CORRECTAMENTE</b>";
} else {
    echo "Denegado!";
}
