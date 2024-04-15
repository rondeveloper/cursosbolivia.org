<?php

/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';


if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
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
$rqdp2 = fetch($rqdp1);
$ci_participante = $rqdp2['ci'];
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$email_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_usuario = $rqdp2['id_usuario'];

/* password */
$rqvpc1 = query("SELECT password FROM cursos_usuarios WHERE id='$id_usuario' ");
$rqvpc2 = fetch($rqvpc1);
$password = $rqvpc2['password'];



/* datos curso */
$rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$id_curso = $rqdc2['id_curso'];

$hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);

/* datos curso virtual */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND cv.id='$id_onlinecourse' ORDER BY r.id DESC limit 1 ");
$rqdcv2 = fetch($rqdcv1);
$nombre_curso_virtual = $rqdcv2['titulo'];
$url_curso_virtual = $dominio_plataforma.'ingreso/' . $rqdcv2['urltag'] . '/'.$hash_iduser.'.html';
$fecha_incio_cursovirtual = date("d/m/Y",strtotime($rqdcv2['fecha_inicio']));
$fecha_final_cursovirtual = date("d/m/Y",strtotime($rqdcv2['fecha_final']));


/* content text */
$texto_principal = '<p><span><strong>Bienvenida al curso virtual | ' . ($nombre_curso_virtual) . '</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Reciba un especial saludo de bienvenida al ' . ($nombre_curso_virtual) . '<em>&nbsp;</em>ofrecido por&nbsp;NEMABOL en convenio con la plataforma on-line '.$___nombre_del_sitio.'</span></p>
<p align="justify">Le invitamos a que esta semana ingrese a los recursos del curso y explore las pestañas&nbsp;de capacitaci&oacute;n donde encontrar&aacute; informaci&oacute;n sobre esta propuesta de formaci&oacute;n: los objetivos, la metodolog&iacute;a, el cronograma, en fin el programa completo del curso. Recuerde que el acceso al curso virtual y sus recursos estar&aacute;n disponibles para usted las 24 horas del d&iacute;a desde su primer ingreso hasta 8 semanas despues, el tiempo sugerido de ingreso y culminaci&oacute;n de este curso virtual es desde '.$fecha_incio_cursovirtual.' hasta '.$fecha_final_cursovirtual.' por lo que le recomendamos completar el curso dentro de estas fechas.</p>
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
<p>4. Preguntar sus dudas directamente al docente via el chat de mensajeria del curso.</p>
<p>5. Realizar un repaso de los conocimientos respondiendo el cuestionario de preguntas de examen.</p>
<p>&nbsp;</p>
<p>Espero que este proceso de FORMACIÓN virtual sea de mucho provecho para usted, y que los contenidos y actividades propuestos en el curso, permitan aprender nuevos conocimientos &uacute;tiles para su desempe&ntilde;o laboral.</p>
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
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>".($nombre_curso_virtual)."</h2>";
$contenido_correo .= "<center><a href='".$dominio."'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='".$dominio_www."contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $texto_principal;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
$asunto = "Curso virtual - $nombre_curso_virtual";

$correo = $email_a_enviar;
$subject = ($asunto);
$body = $contenido_correo;

$array_correos_a_enviar = explode(",", $correo);
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    SISTsendEmailFULL($correo_a_enviar, $subject, $body,array($correo_administrador),'');
}
logcursos('Envio de correo bienvenida curso-virtual', 'partipante-cvirtual', 'participante', $id_participante);

echo "<b>CORREO ENVIADO CORRECTAMENTE!</b>";

