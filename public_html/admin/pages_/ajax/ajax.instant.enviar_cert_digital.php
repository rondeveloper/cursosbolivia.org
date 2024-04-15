<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_emision_certificado = post('id_emision_certificado');
$email_a_enviar = trim(str_replace(' ', '', post('correo')));

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

$rqf1 = query("SELECT * FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");
if (num_rows($rqf1) == 0) {
    echo "Certificado inexistente!";
    exit;
}
$emision_certificado = fetch($rqf1);
$id_certificado = $emision_certificado['id_certificado'];
$id_participante = $emision_certificado['id_participante'];
$rdcm1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$modelo_certificado = fetch($rdcm1);

/* datos a usar */
$id_de_certificado = $emision_certificado['certificado_id'];
$receptor_de_certificado = $emision_certificado['receptor_de_certificado'];
$fecha_emision = $emision_certificado['fecha_emision'];

$texto_qr_certificado = $modelo_certificado['texto_qr'];
$fecha_qr_certificado = $modelo_certificado['fecha_qr'];
$codigo_certificado = $modelo_certificado['codigo'];

$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Novimebre','Diciembre');

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del certificado ' . $id_de_certificado . ' emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision)) . ' de ' . $meses[(int)date("m", strtotime($fecha_emision))] . ' de ' . date("Y", strtotime($fecha_emision)) . ' en formato PDF adjuntado en este correo, 
este certificado puede ser impreso por el receptor y ser validado en cualquier momento en la p&aacute;gina <a href="'.$dominio.'validacion-de-certificado.html">validaci&oacute;n de certificados</a> de '.$___nombre_del_sitio.', 
a continuaci&oacute;n los datos del certificado correspondiente. 
<br/>
<table>
<tr>
<td><b>Curso:</b></td>
<td>' . $texto_qr_certificado . '</td>
</tr>
<tr>
<td><b>Fecha:</b></td>
<td>' . $fecha_qr_certificado . '</td>
</tr>
<tr>
<td><b>ID de certificado:</b></td>
<td>' . $id_de_certificado . '</td>
</tr>
<tr>
<td><b>Receptor de certificado:</b></td>
<td>' . $receptor_de_certificado . '</td>
</tr>
<tr>
<td><b>Fecha de emision:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision)) . '</td>
</tr>
<tr>
<td><b>Codigo cert:</b></td>
<td>' . $codigo_certificado . '</td>
</tr>
</table>
<br/>

</p>
';

//$enviar_a = "brayan.desteco@gmail.com";
$enviar_a = $email_a_enviar;

$asunto = ('Certificado ' . $id_de_certificado . ' NEMABOL - Envio digital');
$subasunto = ('Certificado digital ' . $id_de_certificado . ' - NEMABOL');

$contenido_correo = platillaEmailUno($htm,$subasunto,'[REMM-CORREO]','[REMM-UNSUB]', 'Usuario');

/* variables para los datos del archivo  */
$nombrearchivo = "certificado-$id_de_certificado-nemabol.pdf";
$url_archivo = $dominio."contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=$id_de_certificado";

$archivo_cont = file_get_contents($url_archivo);

$correo = $enviar_a;
$subject = $asunto;
$body = $contenido_correo;

$nuevoarchivo = fopen($nombrearchivo, "w+");
fwrite($nuevoarchivo, $archivo_cont);
fclose($nuevoarchivo);

$array_correos_a_enviar = explode(",", $correo);
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    SISTsendEmailFULL($correo_a_enviar,$subject,str_replace(array('[REMM-CORREO]','[REMM-UNSUB]'),array($correo_a_enviar,urlUnsubscribe($correo_a_enviar)),$body),'',array(array($nombrearchivo,$nombrearchivo)));
}

unlink($nombrearchivo);

logcursos('Envio de certificado [correo]['.$id_de_certificado.']['.$email_a_enviar.']', 'participante-edicion', 'participante', $id_participante);

echo "<b>CERTIFICADO ENVIADO CORRECTAMENTE!</b>";
