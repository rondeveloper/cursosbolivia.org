<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (post('keyaccess')!='5rw4t6gd1') {
    echo "DENEGADO";
    exit;
}

$id_administrador = post('id_administrador');

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
$correo_administrador = $rqddad2['email'];

/* data */
$id_participante = post('id_participante');

/* registros */
$rqdr1 = query("SELECT correo, nombres, apellidos, id_curso FROM cursos_participantes WHERE id='1' ORDER BY id DESC limit 1");
$rqdr2 = fetch($rqdr1);

$correo_participante = $rqdr2['correo'];
$nombre_participante = $rqdr2['nombres'].' '.$rqdr2['apellidos'];
$id_curso = $rqdr2['id_curso'];

$link_asistencia = $dominio.'asistencia/'.$id_participante.'.html';

/* curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo'];

if (!emailValido($correo_participante)) {
    echo "Correo invalido!";
    exit;
}

$htm = '
<p>
Saludos '.$nombre_participante.'
<br/>
Se le hace el env&iacute;o del link de asistencia para el curso que se registro. 
<br/>
</p>

<table style="width:100%;border: 2px solid #9cc79c;padding: 10px 5px;">
<tr>
<td style="padding-bottom: 10px;"><b>CURSO:</b></td>
<td>' . $nombre_curso . '</td>
</tr>
<tr>
<td style="padding-bottom: 10px;"><b>LINK DE ASISTENCIA:</b></td>
<td>' . $link_asistencia . '</td>
</tr>
<tr>

</table>
<br/>
';

$asunto = ('LINK DE ASISTENCIA - '.$nombre_curso);
$subasunto = ('LINK DE ASISTENCIA');
$contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);



/* envio de correo */
SISTsendEmail($correo_participante,$asunto,$contenido_correo);

logcursos('Envio datos de acceso', 'participante-envio', 'participante', $id_participante);

echo "<b>ACCESOS ENVIADOS CORRECTAMENTE!</b>";


