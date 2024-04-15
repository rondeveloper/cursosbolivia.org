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
$rqddad1 = query("SELECT email FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqddad2 = mysql_fetch_array($rqddad1);
$correo_administrador = $rqddad2['email'];

/* datos recibidos */
$id_participante = post('id_participante');
$id_onlinecourse = post('id_onlinecourse');
$email_a_enviar = trim(str_replace(' ', '', post('correo')));

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

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

/* datos curso virtual */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final,r.id_onlinecourse FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND cv.id='$id_onlinecourse' ORDER BY r.id DESC limit 1 ");
$rqdcv2 = mysql_fetch_array($rqdcv1);
$nombre_curso_virtual = $rqdcv2['titulo'];
$url_curso_virtual = 'https://cursos.bo/curso-online/' . $rqdcv2['urltag'] . '.html';
$fecha_incio_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_inicio']));
$fecha_final_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_final']));

/* avance */
$tabla_avance = '<table style="width: 80%;margin: auto;">';
$rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
while ($rqlcv2 = mysql_fetch_array($rqlcv1)) {
    $id_leccion = $rqlcv2['id'];
    $titulo_leccion = $rqlcv2['titulo'];
    $minutos_leccion = $rqlcv2['minutos'];
    $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' ");
    $tt_leccion = '0/'.$minutos_leccion;
    $p = 0;
    if (mysql_num_rows($rqdavl1) > 0) {
        $rqdavl2 = mysql_fetch_array($rqdavl1);
        $t = $rqdavl2['minutos'] * 60;
        $s = $rqdavl2['segundos'];
        $p = round($s * 100 / $t);
        if ($p > 100) {
            $p = 100;
            $rqdavl2['segundos'] = $t;
        }
        $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
    }
    $tabla_avance .= '<tr>';
    $tabla_avance .= '<td style="text-align:left;">' . $titulo_leccion . '</td>';
    $tabla_avance .= '<td style="text-align:right;">' . $tt_leccion . ' minutos</td>';
    $tabla_avance .= '</tr>';
    $tabla_avance .= '<tr>';
    if ($p > 0) {
        $tabla_avance .= '<td colspan="2"><div style="background: #d2d8dc;border-radius:5px;margin-bottom: 10px;">
                 <div style="width:' . $p . '%;background: #2ecc71;padding:3px;color:#FFF;text-align:center;height: 22px;overflow: hidden;border-radius:5px;">
                     ' . $p . '% Completo (terminado)
                 </div>
            </div>
        </td>';
    } else {
        $tabla_avance .= '<td colspan="2"><div style="background: #d2d8dc;border-radius:5px;margin-bottom: 10px;">
                 <div style="width:100%;padding:3px;color:#FFF;text-align:center;">
                     ' . $p . '% Completo (terminado)
                 </div>
            </div>
        </td>';
    }
    $tabla_avance .= '</tr>';
}
$tabla_avance .= '</table>';
//<p align="justify">Recuerde que el acceso al curso virtual y sus recursos estar&aacute;n disponibles para usted las 24 horas del d&iacute;a desde su primer ingreso hasta 7 d&iacute;as despues, el tiempo l&iacute;mite de ingreso y culminaci&oacute;n de este curso virtual es desde '.$fecha_incio_cursovirtual.' hasta '.$fecha_final_cursovirtual.' por lo que le recomendamos completar el curso dentro de estas fechas.</p>
/* content text */
$texto_principal = '<p><span><strong>Seguimiento en curso virtual</strong></span></p>
<p><span><br>Saludos ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Notamos que a&uacute;n no has completado con todas las lecciones del curso virtual: ' . utf8_decode($nombre_curso_virtual) . ', por lo cual te invitamos a que ingreses a la plataforma virtual y concluyas con todas las lecciones asignadas, para as&iacute; terminar el curso satisfactoriamente.</span></p>
<br/>
<p align="justify"><b>Tu avance en el curso:</b></p>
' . $tabla_avance . '
<br/>
<p align="justify">Para ingresar al curso debes seguir estos sencillos pasos:</p>
<p>1. Desde el navegador web ingrese a <a href="' . $url_curso_virtual . '">' . $url_curso_virtual . '</a><br>
<p>2. Ingrese el usuario y contrase&ntilde;a de ingreso a su cuenta</p>
<p>3. Presione el boton "INGRESAR"</p>
<br/>
<br/>
<b>DATOS DE ACCESO</b>
<br/>
<table>
<tr>
<td style="padding:7px 10px;border: 1px solid gray;">Usuario:</td>
<td style="padding:7px 10px;border: 1px solid gray;">' . $email_participante . '</td>
</tr>
<tr>
<td style="padding:7px 10px;border: 1px solid gray;">Contrase&ntilde;a:</td>
<td style="padding:7px 10px;border: 1px solid gray;">' . $password . '</td>
</tr>
</table>
<br/>
<br/>
<p align="justify">Cada estudiante debe seguir estos pasos para completar el curso:</p>
<p>1. Ingresar a la plataforma.</p>
<p>2. Descargar el material disponible el parte inferior de la pagina de bienvenida al curso.</p>
<p>3. Visualizar cada uno de los capitulos en formato de video disponibles en la plataforma.</p>
<p>5. Preguntar sus dudas directamente al docente via el chat de mensajeria del curso.</p>
<p>3. Realizar un repaso de los conocimientos respondiendo el cuestionario de preguntas de examen.</p>
<p>&nbsp;</p>
<p>Esperamos que este proceso de formación virtual sea de mucho provecho para usted, y que los contenidos y actividades propuestos en el curso, permitan aprender nuevos conocimientos &uacute;tiles para su desempe&ntilde;o laboral.</p>
<p>Recuerda que la modalidad de este curso es virtual y su &eacute;xito depender&aacute; de su compromiso y disciplina en el seguimiento de todo el proceso de formaci&oacute;n. Distribuya su tiempo de manera adecuada para cumplir con los planes y objetivos establecidos en este proceso de aprendizaje.<br>
<p>&nbsp;</p>
<p>Plataforma virtual Cursos.BO</p>

                                    <div style="text-align:center;">
                                        <a href="' . $url_curso_virtual . '" style="border-radius: 15px;
    padding: 10px 30px;
    border: 1px solid #c5edff;
    font-size: 17pt;
    background: #5cabb8;
    color: #FFF;
    text-decoration: none;">
                                            <i class="fa fa-caret-square-o-right"></i> &nbsp; INGRESAR AL CURSO VIRTUAL
                                        </a>
                                    </div>
                                    <br/>
                                    <br/>
                                    ';
/* cont correo */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>" . utf8_decode($nombre_curso_virtual) . "</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $texto_principal;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
$asunto = "Seguimiento en curso virtual - $nombre_curso_virtual";

$correo = $email_a_enviar;
$subject = utf8_decode($asunto);
$body = $contenido_correo;

//echo $contenido_correo;
//exit;

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
            $mail->setFrom('sistema@cursos.bo', 'CURSOS.BO');
            //$mail->addAddress($correo_a_enviar, 'Nombre');     // Add a recipient
            $mail->addAddress($correo_a_enviar);     // Add a recipient
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
    }
}
logcursos('Envio correo de seguimiento (solicitud de avance)', 'partipante-cvirtual', 'participante', $id_participante);

echo "<b>CORREO ENVIADO CORRECTAMENTE!</b>";

