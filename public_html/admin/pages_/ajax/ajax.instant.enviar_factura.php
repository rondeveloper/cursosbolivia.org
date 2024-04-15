<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(1);
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

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='" . administrador('id') . "' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
$correo_administrador = $rqddad2['email'];

$id_factura = get('id_factura');
$email_a_enviar = trim(str_replace(' ', '', get('email_a_enviar')));

$rqf1 = query("SELECT * FROM facturas_emisiones WHERE id='$id_factura' ORDER BY id ASC limit 1 ");
if (num_rows($rqf1) == 0) {
    echo "Factura inexistente!";
    exit;
}
$rqf2 = fetch($rqf1);

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

$codigo_de_control = $rqf2['codigo_de_control'];
$nro_autorizacion = $rqf2['nro_autorizacion'];
$fecha_emision = $rqf2['fecha_emision'];
$fecha_limite_emision = $rqf2['fecha_limite_emision'];
$nit_emisor = $rqf2['nit_emisor'];
$concepto = $rqf2['concepto'];
$nro_factura = $rqf2['nro_factura'];

$nombre_receptor = $rqf2['nombre_receptor'];
$nit_receptor = $rqf2['nit_receptor'];
$total = $rqf2['total'];

$numeral_factura = str_pad($nro_factura, 5, "0", STR_PAD_LEFT);

$htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o de la factura n&uacute;mero ' . $numeral_factura . ' emitida por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision)) . ' de ' . date("M", strtotime($fecha_emision)) . ' de ' . date("Y", strtotime($fecha_emision)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos de la factura correspondiente. 
<br/>
<table>
<tr>
<td><b>Concepto:</b></td>
<td>' . str_replace('.', ' . ', $concepto) . '</td>
</tr>
<tr>
<td><b>Factura a nombre de:</b></td>
<td>' . $nombre_receptor . '</td>
</tr>
<tr>
<td><b>N&uacute;mero de NIT:</b></td>
<td>' . $nit_receptor . '</td>
</tr>
<tr>
<td><b>Monto de facturaci&oacute;n:</b></td>
<td>' . number_format($total, 2, '.', '') . ' Bs.</td>
</tr>
<tr>
<td><b>N&uacute;mero de factura:</b></td>
<td>' . $numeral_factura . '</td>
</tr>
<tr>
<td><b>Fecha de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_emision)) . '</td>
</tr>
<tr>
<td><b>Codigo de control:</b></td>
<td>' . $codigo_de_control . '</td>
</tr>
<tr>
<td><b>N&uacute;mero de autorizaci&oacute;n:</b></td>
<td>' . $nro_autorizacion . '</td>
</tr>
<tr>
<td><b>Fecha limite de emisi&oacute;n:</b></td>
<td>' . date("d / M / Y", strtotime($fecha_limite_emision)) . '</td>
</tr>
<tr>
<td><b>NIT emisor:</b></td>
<td>' . $nit_emisor . '</td>
</tr>
</table>
<br/>

</p>
';

//$enviar_a = "brayan.desteco@gmail.com";
$enviar_a = $email_a_enviar;

$asunto = ('Factura ' . $numeral_factura . ' NEMABOL - Envio digital');
$subasunto = ('Factura n&uacute;mero ' . $numeral_factura . ' - NEMABOL');

$contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
        . "<h2 style='text-align:center;background:#d2861f;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
$contenido_correo .= "<center><img style='background:#FFF;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://www.nemabol.com/img/logo-nemabol.png'/></center>"
        . $htm;
$contenido_correo .= "<h3 style='background:#d2861f;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
        . "</div><hr/>";

/* variables para los datos del archivo */
$nombrearchivo = "factura-$numeral_factura-nemabol.pdf";
$url_archivo = $dominio . "contenido/paginas/procesos/pdfs/factura-1.php?id_factura=$id_factura";

$archivo_cont = file_get_contents($url_archivo);

$correo = $enviar_a;
$subject = $asunto;
$body = $contenido_correo;

$nuevoarchivo = fopen($nombrearchivo, "w+");
fwrite($nuevoarchivo, $archivo_cont);
fclose($nuevoarchivo);

$array_correos_a_enviar = explode(",", $correo);
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    SISTsendEmailFULL($correo_a_enviar, $subject, $body,array('facturacion@infosicoes.com','pagos@infosicoes.com',$correo_administrador),array(array($nombrearchivo,$nombrearchivo)));
}

unlink($nombrearchivo);

movimiento('Envio digital de factura [numero: ' . $numeral_factura . '] [' . $email_a_enviar . ']', 'envio-factura', 'factura', $rqf2['id']);

echo "<b>FACTURA ENVIADA CORRECTAMENTE!</b>";
