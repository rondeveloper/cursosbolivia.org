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
$rqdr1 = query("SELECT p.correo,p.nombres,p.apellidos,p.id_curso FROM cursos_participantes p WHERE p.id='$id_participante' ORDER BY p.id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);

$correo_participante = $rqdr2['correo'];
$nombre_participante = $rqdr2['nombres'].' '.$rqdr2['apellidos'];
$id_curso = $rqdr2['id_curso'];

/* sesion zoom */
$rqvsz1 = query("SELECT * FROM sesiones_zoom WHERE id_curso='$id_curso' LIMIT 1 ");
if(num_rows($rqvsz1)==0){
    echo '<strong>AVISO</strong> el curso no tiene asignado la sesion ZOOM.';
    exit;
}

$rqvsz2 = fetch($rqvsz1);
$url = $rqvsz2['url'];
$descripcion = $rqvsz2['descripcion'];
$reunion_id = $rqvsz2['reunion_id'];
$codigo_acceso = $rqvsz2['codigo_acceso'];
$fecha_hora = date("d/m/y H:i",strtotime($rqvsz2['fecha']));

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
Se le env&iacute;a los datos de la sesi&oacute;n ZOOM configurada para su curso. 
<br/>
</p>

<div style="
    text-align: center;
    border: 3px solid #2196f3;
    border-bottom: 0px;
    padding: 10px 5px;
    font-size: 25pt;
    color: #2196f3;
    font-weight: bold;
    background: #ececec;
    font-family: Google Sans;
    ">
    zoom
</div>
<table style="width: 100%;border: 3px solid #2196f3;padding: 10px 5px;">
<tr>
<td style="padding: 7px;"><b>CURSO:</b></td>
<td style="padding: 7px;">' . $nombre_curso . '</td>
</tr>
<tr>
<td style="padding: 7px;"><b>DESCRIPCI&Oacute;N DE LA SESI&Oacute;N:</b></td>
<td style="padding: 7px;">' . $descripcion . '</td>
</tr>
<tr>
<td style="padding: 7px;"><b>URL DE ACCESO:</b></td>
<td style="padding: 7px;">' . $url . '</td>
</tr>
<tr>
<tr>
<td style="padding: 7px;"><b>ID DE REUNI&Oacute;N:</b></td>
<td style="padding: 7px;">' . ($reunion_id) . '</td>
</tr>
<tr>
<td style="padding: 7px;"><b>C&Oacute;DIGO DE ACCESO:</b></td>
<td style="padding: 7px;">' . ($codigo_acceso) . '</td>
</tr>
<tr>
<td style="padding: 7px;"><b>FECHA Y HORA:</b></td>
<td style="padding: 7px;">' . ($fecha_hora) . '</td>
</tr>
</table>
<br/>
';

$asunto = ('Datos de sesion ZOOM - '.$nombre_curso);
$subasunto = ('DATOS DE SESI&Oacute;N ZOOM');
$contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);



/* envio de correo */
SISTsendEmail($correo_participante,$asunto,$contenido_correo);

logcursos('Envio datos de acceso ZOOM', 'participante-envio', 'participante', $id_participante);

query("INSERT INTO rel_partszoom(id_participante,id_curso) VALUES ('$id_participante','$id_curso') ");

echo "<b>ACCESOS ENVIADOS CORRECTAMENTE!</b>";
