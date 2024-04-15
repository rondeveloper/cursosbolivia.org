<?php

/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
//include_once '../../librerias/correo/class.phpmailer.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='" . administrador('id') . "' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
$correo_administrador = $rqddad2['email'];

/* datos recibidos */
$id_participante = post('id_participante');

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
$qrddcdp1 = query("SELECT titulo,id_modalidad,sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$qrddcdp2 = fetch($qrddcdp1);
$titulo_curso = $qrddcdp2['titulo'];
$id_modalidad_curso = $qrddcdp2['id_modalidad'];
$sw_ipelc = $qrddcdp2['sw_ipelc'];

/* content text */
$texto_principal = '<p><span><strong>Datos de ingreso a cursos virtuales</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Le enviamos en este correo los datos de acceso a los cursos virtuales a los cuales se registro.</span>.</p>

[MENSAJE-CURSO-PREGRABADO]

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
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.estado='1' AND r.id_curso='$id_curso' AND cv.id IN (select id_onlinecourse from cursos_onlinecourse_acceso where id_usuario='$id_usuario') ORDER BY r.id ASC ");
while($rqdcv2 = fetch($rqdcv1)){
    $hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);
    $nombre_curso_virtual = $rqdcv2['titulo'];
    $url_curso_virtual = $dominio_plataforma.'ingreso/' . $rqdcv2['urltag'] . '/'.$hash_iduser.'.html';
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
    
    $txt_mensajecursopregrabado = ('<p>El curso est&aacute; activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.$fecha_final_cursovirtual.' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.</p>');
}

$txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, podrá dar Examen en Linea, podrá enviar los documentos para la certificación de la IPELC, podrá hacer seguimiento a la certificación IPELC.';

if($id_modalidad_curso=='2'){
    $texto_principal = str_replace('[MENSAJE-CURSO-PREGRABADO]',$txt_mensajecursopregrabado,$texto_principal);
} elseif($sw_ipelc=='1'){
    $texto_principal = str_replace('[MENSAJE-CURSO-PREGRABADO]',$txt_mensajecursoipelc,$texto_principal);
} else {
    $texto_principal = str_replace('[MENSAJE-CURSO-PREGRABADO]','',$texto_principal);
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

/* datos de correo */
$contenido_correo = platillaEmailUno($texto_principal,($titulo_curso),$email_participante,urlUnsubscribe($email_participante),trim($nombres_participante . ' ' . $apellidos_participante));


/* datos de correo */
$asunto = "DATOS DE INGRESO cursos virtuales - ".$titulo_curso;

$subject = str_replace('?','',($asunto));
$body = $contenido_correo;

SISTsendEmail($email_participante, $subject, $body);

logcursos('Envio de correo acceos cursos-virtuales', 'partipante-cvirtual', 'participante', $id_participante);

echo "<b>CORREO ENVIADO CORRECTAMENTE!</b>";

