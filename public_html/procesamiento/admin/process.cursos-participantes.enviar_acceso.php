<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
require '../../contenido/librerias/phpmailer/vendor/autoload.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


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
$rqdr1 = query("SELECT u.*,p.correo,p.nombres,p.apellidos,p.id_curso FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.id='$id_participante' ORDER BY p.id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);

$user = $rqdr2['email'];
$password = $rqdr2['password'];
$correo_participante = $rqdr2['correo'];
$nombre_participante = $rqdr2['nombres'].' '.$rqdr2['apellidos'];
$id_curso = $rqdr2['id_curso'];

$url_acceso = 'https://plataforma.cursos.bo/';

$rqdc1 = query("SELECT o.urltag FROM cursos_rel_cursoonlinecourse r INNER JOIN cursos_onlinecourse o ON r.id_onlinecourse=o.id WHERE r.id_curso='$id_curso' AND r.estado='1' ");
if(num_rows($rqdc1)==1){
    $rqdc2 = fetch($rqdc1);
    $url_acceso = 'https://plataforma.cursos.bo/ingreso/'.$rqdc2['urltag'].'.html';
}

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
Se le hace el env&iacute;o de sus datos de acceso a la plataforma. 
<br/>
</p>

<table style="width:100%;border: 2px solid #9cc79c;padding: 10px 5px;">
<tr>
<td style="padding-bottom: 10px;"><b>CURSO:</b></td>
<td>' . $nombre_curso . '</td>
</tr>
<tr>
<td style="padding-bottom: 10px;"><b>URL DE ACCESO:</b></td>
<td>' . $url_acceso . '</td>
</tr>
<tr>
<tr>
<td style="padding-bottom: 10px;"><b>USUARIO:</b></td>
<td>' . ($user) . '</td>
</tr>
<tr>
<td style="padding-bottom: 10px;"><b>CONTRASE&Ntilde;A:</b></td>
<td>' . ($password) . '</td>
</tr>
</table>
<br/>
';

$asunto = ('ACCESO A LA PLATAFORMA - '.$nombre_curso);
$subasunto = ('ACCESO A LA PLATAFORMA');
$contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);



/* envio de correo */
SISTsendEmail($correo_participante,$asunto,$contenido_correo);

logcursos('Envio datos de acceso', 'participante-envio', 'participante', $id_participante);

echo "<b>ACCESOS ENVIADOS CORRECTAMENTE!</b>";
