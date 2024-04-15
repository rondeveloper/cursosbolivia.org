<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* verificador de acceso */
if (!isset_administrador() && (post('keyaccess')!='5rw4t6gd1') ) {
    echo "DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_curso = post('id_curso');
$id_participante = post('id_participante');

/* curso */
$rqdc1 = query("SELECT titulo,fecha,horarios FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = str_replace('?','',($rqdc2['titulo']));
$fecha_curso = $rqdc2['fecha'];
$horarios_curso = $rqdc2['horarios'];

/* participantes */
$rqdp1 = query("SELECT correo,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$correo_participante = $rqdp2['correo'];
$nombre_participante = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];

if (!emailValido($correo_participante)) {
    echo "<h4>CORREO NO VALIDO [$correo_participante]</h4>";
    exit;
}

/* correo */
$htm_cont = "<p>Saludos $nombre_participante <br>Se le hace la notificaci&oacute;n de que su reporte de pago ha sido verificado correctamente, por lo que su inscripci&oacute;n al curso se ha completado de forma exitosa, le recordamos tambi&eacute;n la fecha y hora de inicio de la sesion en vivo del curso.</p>";
$htm_cont .= "<br>";
$htm_cont .= "<b>Curso:</b> " . $nombre_curso . "<br>";
$htm_cont .= "<b>Fecha:</b> " . date("d / m / Y", strtotime($fecha_curso)) . "<br>";
$htm_cont .= "<b>Horario:</b> " . $horarios_curso . "<br><br>";

$htm_cont .= "<p><b>IMPORTANTE:</b> Tenga en cuenta que se le enviar&aacute; el enlace de acceso al curso, media hora antes de la hora de inicio.</p>";
$htm_cont .= "<br/><p>Esperamos que este curso le sea de mucha utilidad.</p>";

$contenido_correo = platillaEmailUno($htm_cont,$nombre_curso,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);

/* envio de correo */
$subject = str_replace('?','','REGISTRO COMPLETADO - '.$nombre_curso);
$body = $contenido_correo;

if (emailValido($correo_participante)) {
    /* envio de correo */
    SISTsendEmail($correo_participante,$subject,$body);
    logcursos('Envio de material de curso', 'material-curso', 'participante', $id_participante);
    query("INSERT INTO rel_partnotifsuev (id_curso,id_participante) VALUES ('$id_curso','$id_participante') ");
    echo '<br><b class="btn btn-success btn-md active">ENVIADO CORRECTAMENTE</b>';
} else {
    echo "CORREO INVALIDO [$correo_participante]";
}
