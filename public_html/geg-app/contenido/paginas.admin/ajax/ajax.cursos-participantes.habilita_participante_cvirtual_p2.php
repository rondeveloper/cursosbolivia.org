<?php

/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

//Load Composer's autoloader
require '../../librerias/phpmailer/vendor/autoload.php';

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

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
$password = substr(md5(rand(9, 999)), 2, 5);
$fecha_registro = date("Y-m-d");

/* creacion de usuario */
$rqvpc1 = query("SELECT id,password FROM cursos_usuarios WHERE ci='$ci_participante' AND ci<>'' AND email='$email_participante' ");
if (mysql_num_rows($rqvpc1) == 0) {
    query("INSERT INTO cursos_usuarios(
                       nombres, 
                       apellidos, 
                       ci, 
                       email, 
                       celular, 
                       password, 
                       sw_docente, 
                       fecha_registro, 
                       estado
                       ) VALUES (
                       '$nombres_participante',
                       '$apellidos_participante',
                       '$ci_participante',
                       '$email_participante',
                       '$celular_participante',
                       '$password',
                       '0',
                       '$fecha_registro',
                       '1'
                       )");
    $id_usuario = mysql_insert_id();
    logcursos('Creacion y asignacion de usuario [U:' . $id_usuario . ']', 'partipante-edicion', 'participante', $id_participante);
} else {
    $rqvpc2 = mysql_fetch_array($rqvpc1);
    $id_usuario = $rqvpc2['id'];
    $password = $rqvpc2['password'];
}
query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='$id_participante' ORDER BY id DESC limit 1");


/* datos curso */
$rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$id_curso = $rqdc2['id_curso'];

/* datos curso virtual */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final,r.id_onlinecourse FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' ORDER BY r.id DESC limit 1 ");
$rqdcv2 = mysql_fetch_array($rqdcv1);
$nombre_curso_virtual = $rqdcv2['titulo'];
$id_onlinecourse = $rqdcv2['id_onlinecourse'];
$url_curso_virtual = 'https://cursos.bo/curso-online/' . $rqdcv2['urltag'] . '.html';
$fecha_incio_cursovirtual = date("d/m/Y",strtotime($rqdcv2['fecha_inicio']));
$fecha_final_cursovirtual = date("d/m/Y",strtotime($rqdcv2['fecha_final']));

/* creacion de registro de acceso */
$rqvacc1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario' ");
if(mysql_num_rows($rqvacc1)==0){
    query("INSERT INTO cursos_onlinecourse_acceso(
                id_onlinecourse, 
                id_usuario, 
                sw_acceso, 
                estado
                ) VALUES (
                '$id_onlinecourse',
                '$id_usuario',
                '1',
                '0'
                )");
}else{
    $rqvacc2 = mysql_fetch_array($rqvacc1);
    query("UPDATE cursos_onlinecourse_acceso SET sw_acceso='1' WHERE id='".$rqvacc2['id']."' ");
}


/* content text */
$texto_principal = '<p><span><strong>Bienvenida al curso virtual | ' . utf8_decode($nombre_curso_virtual) . '</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Reciba un especial saludo de bienvenida al ' . utf8_decode($nombre_curso_virtual) . '<em>&nbsp;</em>ofrecido por&nbsp;NEMABOL en convenio con la plataforma on-line CURSOS.BO</span></p>
<p align="justify">Le invitamos a que esta semana ingrese a los recursos del curso y explore las pesta&ntilde;as de capacitaci&oacute;n donde encontrar&aacute; informaci&oacute;n sobre esta propuesta de formaci&oacute;n: los objetivos, la metodolog&iacute;a, el cronograma, en fin el programa completo del curso. Recuerde que el acceso al curso virtual y sus recursos estar&aacute;n disponibles para usted las 24 horas del d&iacute;a desde su primer ingreso hasta 7 d&iacute;as despues, el tiempo l&iacute;mite de ingreso y culminaci&oacute;n de este curso virtual es desde '.$fecha_incio_cursovirtual.' hasta '.$fecha_final_cursovirtual.' por lo que le recomendamos completar el curso dentro de estas fechas.</p>
<br/>
<p align="justify">Para ingresar al curso debe seguir estos sencillos pasos y comenzar a explorar el espacio virtual:</p>
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
<p>Espero que este proceso de formación virtual sea de mucho provecho para usted, y que los contenidos y actividades propuestos en el curso, permitan aprender nuevos conocimientos &uacute;tiles para su desempe&ntilde;o laboral.</p>
<p>Recuerde que la modalidad de este curso es virtual y su &eacute;xito depender&aacute; de su compromiso y disciplina en el seguimiento de todo el proceso de formaci&oacute;n. Distribuya su tiempo de manera adecuada para cumplir con los planes y objetivos establecidos en este proceso de aprendizaje.<br>
<br>Cuenten siempre con mi acompa&ntilde;amiento y apoyo.</p>
<p><br>Atte:<br>Su tutor virtual</em></p>
<p>&nbsp;</p>
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
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>".utf8_decode($nombre_curso_virtual)."</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $texto_principal;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
$asunto = "Curso virtual - $nombre_curso_virtual";
$cabeceras = 'From:' . 'CURSOS.BO <contacto@cursos.bo>' . "\r\n" .
        'Reply-To:' . 'contacto@cursos.bo' . "\r\n" .
        'X-Mailer: PHP/' . phpversion() .
        'Return-Path:' . 'contacto@cursos.bo' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";
envia_email($email_participante, $asunto, $contenido_correo);

/* actualizacion de registro */
query("UPDATE cursos_participantes SET sw_cvirtual='1' WHERE id='$id_participante' ");
logcursos('HABILITACION A CURSO VIRTUAL', 'partipante-cvirtual', 'participante', $id_participante);

logcursos('Envio de correo bienvenida curso-virtual', 'partipante-cvirtual', 'participante', $id_participante);
?>
<div class="alert alert-success">
    <strong>EXITO</strong> participante habilitado correctamente.
    <br/>
    El correo de bienvenida se envio al participante.
</div>


<?php

function envia_email($email_participante, $asunto, $contenido_correo){
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
            $mail->addAddress($email_participante);     // Add a recipient

            /* Content */
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = utf8_decode($asunto);
            $mail->Body = $contenido_correo;

            $mail->Send(); //Enviar
            //return true;
        } catch (phpmailerException $e) {
            echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
            //return false;
        }
}
