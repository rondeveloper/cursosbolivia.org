<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(1);
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
$rqdr1 = query("SELECT p.nombres,p.apellidos,p.correo,p.id,c.fecha FROM cursos_participantes p INNER JOIN certificados_culminacion_emisiones e ON e.id_participante=p.id INNER JOIN certificados_culminacion c ON c.id=e.id_certificado_culminacion WHERE e.id='$id_emision_certificado' ORDER BY e.id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);

$id_participante = $rqdr2['id'];
$correo_participante = $rqdr2['correo'];
$receptor_de_certificado = $rqdr2['nombres'].' '.$rqdr2['apellidos'];
$fecha_emision_certificado = $rqdr2['fecha'];

if (strlen($correo_participante) <= 5) {
    echo "Correo invalido!";
    exit;
}

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del certificado de culminaci&oacute;n de estudios, emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision_certificado)) . ' de ' . date("m", strtotime($fecha_emision_certificado)) . ' de ' . date("Y", strtotime($fecha_emision_certificado)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos del certificado correspondiente. 
<br/>
</p>
<br>
<table>
<tr>
<td><b>ID de certificado:</b></td>
<td>ID: CL000' . $id_emision_certificado . '</td>
</tr>
<tr>
<td><b>Receptor del certificado:</b></td>
<td>' . ($receptor_de_certificado) . '</td>
</tr>
<tr>
<td><b>Fecha de emisi&oacute;n:</b></td>
<td>' . date("d / m / Y", strtotime($fecha_emision_certificado)) . '</td>
</tr>
</table>
<br>
<br>
<hr>
';
 

$asunto = 'CERTIFICADO DE CULMINACIÓN DE ESTUDIOS - CL000'.$id_emision_certificado;
$contenido_correo = platillaEmailUno($htm,$asunto,$correo_participante,urlUnsubscribe($correo_participante),$receptor_de_certificado);

/* variables para los datos del archivo */
$nombrearchivo = "certificado-culminacion-estudios-$id_emision_certificado.pdf";
$hash = md5(md5($id_emision_certificado . 'cce5616'));
$url_archivo = $dominio_www . "contenido/paginas/procesos/pdfs/certificado-culminacion-ipelc-digital.php?id_emision=$id_emision_certificado&hash=$hash";

$archivo_cont = file_get_contents($url_archivo);

$nuevoarchivo = fopen($nombrearchivo, "w+");
fwrite($nuevoarchivo, $archivo_cont);
fclose($nuevoarchivo);

/* envio de correo */
SISTsendEmailFULL($correo_participante,$asunto,$contenido_correo,'',array(array($nombrearchivo,$nombrearchivo)));

unlink($nombrearchivo);

logcursos('Envio digital de certificado de culminacion [00000' . $id_emision_certificado . '] [' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);

echo "<b>CERTIFICADO ENVIADO CORRECTAMENTE!</b>";
