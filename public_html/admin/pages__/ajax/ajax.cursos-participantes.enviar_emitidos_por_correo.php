<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(1);
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
//include_once '../../librerias/correo/class.phpmailer.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador() && (post('keyaccess')!='5rw4t6gd1') ) {
    echo "DENEGADO";
    exit;
}


$id_administrador = administrador('id');
if(isset_post('keyaccess')){
    $id_administrador = post('id_administrador');
}

$sw_enviarSoloLinks = false;
if(isset_post('solo_links') && post('solo_links')=='1'){
    $sw_enviarSoloLinks = true;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='" . $id_administrador . "' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
$correo_administrador = $rqddad2['email'];


/* data */
$id_participante = post('id_participante');

/* participante */
$rqddprt1 = query("SELECT correo,id_curso,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqddprt2 = fetch($rqddprt1);
$correo_participante = $rqddprt2['correo'];
$id_curso_participante = $rqddprt2['id_curso'];
$nomUsuarioEmail = $rqddprt2['nombres'].' '.$rqddprt2['apellidos'];

/* curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];

if (!emailValido($correo_participante)) {
    echo "<h4>CORREO NO VALIDO [$correo_participante]</h4>";
    exit;
}

$bodyEmail = '
<p>
Saludos cordiales
</p>
';

if($sw_enviarSoloLinks){
    $bodyEmail .= '
Se le hace el env&iacute;o de los certificados obtenidos en la plataforma '.$___nombre_del_sitio.', puede descargarlos desde los enlaces adjuntados en este correo, 
a continuaci&oacute;n los datos de los certificados correspondientes.<br>';
}else{
    $bodyEmail .= '
Se le hace el env&iacute;o de los certificados obtenidos en la plataforma '.$___nombre_del_sitio.' en formato PDF adjuntados en este correo, 
a continuaci&oacute;n los datos de los certificados correspondientes.<br>';
}

$bodyEmail .= '
<br>
<table>
';

$rqdcepp1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_participante='$id_participante' ORDER BY id ASC ");
$array_archivos = array();
while($rqdcepp2 = fetch($rqdcepp1)){

$id_emision_certificado = $rqdcepp2['id'];

/* registros */
$rqdr1 = query("SELECT p.correo,p.id,e.certificado_id,e.receptor_de_certificado,e.fecha_emision,c.texto_qr FROM cursos_participantes p INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados c ON e.id_certificado=c.id WHERE e.id='$id_emision_certificado' ORDER BY e.id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);

$id_participante = $rqdr2['id'];
$certificado_id = $rqdr2['certificado_id'];
$receptor_de_certificado = $rqdr2['receptor_de_certificado'];
$fecha_emision_certificado = $rqdr2['fecha_emision'];
$texto_qr_certificado = $rqdr2['texto_qr'];


$bodyEmail .= '
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
';

if($sw_enviarSoloLinks){
    $bodyEmail .= '
<tr>
<td><b>Enlace de Descarga:</b></td>
<td><a href="https://cursos.bo/C/' . $certificado_id . '/">https://cursos.bo/C/' . $certificado_id . '/</a></td>
</tr>
';
}

$bodyEmail .= '
<tr>
<td colspan="2">&nbsp;</td>
</tr>
';


    if(!$sw_enviarSoloLinks){
        /* variables para los datos del archivo */
        $nombrearchivo = "certificado-$certificado_id.pdf";
        $url_archivo = $dominio . "contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=$certificado_id";
        $archivo_cont = file_get_contents($url_archivo);

        $nuevoarchivo = fopen($nombrearchivo, "w+");
        fwrite($nuevoarchivo, $archivo_cont);
        fclose($nuevoarchivo);
        
        array_push($array_archivos, array($nombrearchivo,$nombrearchivo));
    }

}
$bodyEmail .= '
</table>
<br>
';


$asunto = str_replace('?','',('CERTIFICADOS DIGITALES - '.utf8_encode($titulo_curso)));
$subasunto = ('CERTIFICADOS DIGITALES');
$contenido_correo = platillaEmailUno($bodyEmail,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$nomUsuarioEmail);

$subject = $asunto;
$body = $contenido_correo;

/* envio de correo */
SISTsendEmailFULL($correo_participante, $subject, $body,array($correo_administrador),$array_archivos);

foreach ($array_archivos as $archivo){
    unlink($archivo[1]);
}

logcursos('Envio digital de certificados digitales todos [ID curso:'.$id_curso_participante.'][' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);

echo "<br><h4>CERTIFICADOS ENVIADOS CORRECTAMENTE</h4>";

$rqmcemcrt1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_participante='$id_participante' AND id_curso='$id_curso_participante' ");
$num_emisiones = num_rows($rqmcemcrt1);
$rqcec1 = query("SELECT cnt_impresion_certificados FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqcec2 = fetch($rqcec1);
?>
<br>
<span style="font-size: 8pt;">[Emisiones: <b><?php echo $num_emisiones; ?></b> ]</span>&nbsp;
<span style="font-size: 8pt;">[Impresiones: <b><?php echo $rqcec2['cnt_impresion_certificados']; ?></b> ]</span>
