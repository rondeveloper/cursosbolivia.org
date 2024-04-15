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
$id_emision_certificado = post('id_emision_certificado');

/* registros */
$rqdr1 = query("SELECT p.correo,p.id,e.certificado_id,e.receptor_de_certificado,e.fecha_emision,c.texto_qr FROM cursos_participantes p INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados c ON e.id_certificado=c.id WHERE e.id='$id_emision_certificado' ORDER BY e.id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);

$id_participante = $rqdr2['id'];
$correo_participante = $rqdr2['correo'];
$certificado_id = $rqdr2['certificado_id'];
$receptor_de_certificado = $rqdr2['receptor_de_certificado'];
$fecha_emision_certificado = $rqdr2['fecha_emision'];
$texto_qr_certificado = $rqdr2['texto_qr'];

if (strlen($correo_participante) <= 5) {
    echo "Correo invalido!";
    exit;
}

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del certificado ' . $certificado_id . ' emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision_certificado)) . ' de ' . date("M", strtotime($fecha_emision_certificado)) . ' de ' . date("Y", strtotime($fecha_emision_certificado)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos del certificado correspondiente. 
<br/>
</p>

<table>
<tr>
<td><b>ID de certificado:</b></td>
<td>' . $certificado_id . '</td>
</tr>
<tr>
<tr>
<td><b>Certificado:</b></td>
<td>' . ($texto_qr_certificado) . '</td>
</tr>
<tr>
<td><b>Receptor del certificado:</b></td>
<td>' . ($receptor_de_certificado) . '</td>
</tr>
<tr>
<td><b>Fecha de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision_certificado)) . '</td>
</tr>
</table>
<br/>
';

$asunto = ('CERTIFICADO DIGITAL '.$certificado_id.' - ' . $texto_qr_certificado);
$subasunto = ($texto_qr_certificado);
$contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$receptor_de_certificado);

/* variables para los datos del archivo */
$nombrearchivo = "certificado-$certificado_id.pdf";
$url_archivo = $dominio . "contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=$certificado_id";

$archivo_cont = file_get_contents($url_archivo);

$nuevoarchivo = fopen($nombrearchivo, "w+");
fwrite($nuevoarchivo, $archivo_cont);
fclose($nuevoarchivo);

/* envio de correo */
SISTsendEmailFULL($correo_participante,$asunto,$contenido_correo,'',array(array($nombrearchivo,$nombrearchivo)));

unlink($nombrearchivo);

logcursos('Envio digital de certificado [' . $certificado_id . '] [' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);

echo "<b>CERTIFICADO ENVIADO CORRECTAMENTE!</b>";
