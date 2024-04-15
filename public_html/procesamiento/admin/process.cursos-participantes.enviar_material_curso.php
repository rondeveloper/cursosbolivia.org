<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
require '../../contenido/librerias/phpmailer/vendor/autoload.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


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
$htm_cont = "<p>Saludos $nombre_participante <br>Se le hace el env&iacute;o en forma digital adjuntado en este correo del material adicional del curso al cual usted realizo el registro, tambi&eacute;n se le recuerda la fecha y hora de inicio de la sesion en vivo del curso.</p>";
$htm_cont .= "<br>";
$htm_cont .= "<b>Curso:</b> " . $nombre_curso . "<br>";
$htm_cont .= "<b>Fecha:</b> " . date("d / m / Y", strtotime($fecha_curso)) . "<br>";
$htm_cont .= "<b>Horario:</b> " . $horarios_curso . "<br><br>";

$rqda1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ");
$htm_cont .= "<table>";
$cnt = 1;
while ($rqda2 = fetch($rqda1)) {
    $dir_file = '../../contenido/archivos/material/' . $rqda2['nombre_fisico'];
    $htm_cont .= "<tr>";
    $htm_cont .= "<td style='border:1px solid gray;padding:10px;'>" . $cnt++ . "</td>";
    $htm_cont .= "<td style='border:1px solid gray;padding:10px;'>" . $rqda2['nombre'] . "</td>";
    $htm_cont .= "<td style='border:1px solid gray;padding:10px;'><a href='".$dominio_www."contenido/archivos/material/" . $rqda2['nombre_fisico'] . "'>".$dominio_www."contenido/archivos/material/" . $rqda2['nombre_fisico'] . "</a></td>";
    $htm_cont .= "</tr>";
}
$htm_cont .= "</table>";
$htm_cont .= "<br/><br/><p>Esperamos que este curso le sea de mucha utilidad.</p>";

$contenido_correo = platillaEmailUno($htm_cont,'MATERIAL DIGITAL - '.$nombre_curso,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);


/* envio de correo */
$subject = str_replace('?','','MATERIAL DIGITAL - '.$nombre_curso);
$body = $contenido_correo;


if (emailValido($correo_participante)) {
    SISTsendEmail($correo_participante,$subject,$body);
    logcursos('Envio de material de curso', 'material-curso', 'participante', $id_participante);
    query("INSERT INTO rel_partmaterialcur (id_curso,id_participante) VALUES ('$id_curso','$id_participante') ");
    echo '<b class="label label-success">ENVIADO CORRECTAMENTE</b>';
} else {
    echo "CORREO INVALIDO [$correo_participante]";
}
