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

if (isset_administrador()) {

    $id_emision_cupon_infosicoes = post('id_emision_cupon_infosicoes');
    
    $rqf1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id='$id_emision_cupon_infosicoes' ORDER BY id ASC limit 1 ");
    if (num_rows($rqf1) == 0) {
        echo "Cupon inexistente! [$id_emision_cupon_infosicoes]";
        exit;
    }
    $rqf2 = fetch($rqf1);
    
    $id_cupon = $rqf2['id_cupon'];
    $id_curso = $rqf2['id_curso'];
    $id_participante = $rqf2['id_participante'];
    $codigo = $rqf2['codigo'];
    $fecha_registro = $rqf2['fecha_registro'];
    $estado = $rqf2['estado'];
    
    $rqcp1 = query("SELECT correo,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC ");
    if (num_rows($rqcp1) == 0) {
        echo "Participante inexistente! [$id_participante]";
        exit;
    }
    $rqcp2 = fetch($rqcp1);    
    $nombre = $rqcp2['nombres'] . ' ' . $rqcp2['apellidos'];
    $correo = $rqcp2['correo'];
    
    $numeral_factura = str_pad($nro_factura, 5, "0", STR_PAD_LEFT);

    $htm = '
<p>
Saludos cordiales
<br/>
Se le hace el env&iacute;o del cup&oacute;n ' . $codigo . ' emitida por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_registro)) . ' de ' . date("M", strtotime($fecha_registro)) . ' de ' . date("Y", strtotime($fecha_registro)) . ' en formato PDF adjuntado en este correo, 
a continuaci&oacute;n los datos del cup&oacute;n correspondiente. 
<br/>
<table>
<tr>
<td><b>Receptor:</b></td>
<td>' . $nombre . '</td>
</tr>
<tr>
<td><b>Correo:</b></td>
<td>' . $correo . '</td>
</tr>
<tr>
<td><b>Cup&oacute;n:</b></td>
<td>' . $codigo . '</td>
</tr>
</table>
<br/>
</p>
<br>
<hr>
';

    //$correo = "brayan.desteco@gmail.com";

    $asunto = ('Cupon ' . $codigo . ' INFOSICOES - Envio digital');
    $subasunto = ('Cupon ' . $codigo . ' - INFOSICOES');
    
    $contenido_correo = platillaEmailUno($htm,$subasunto,$correo,urlUnsubscribe($correo),$nombre);

    /* variables para los datos del archivo */
    $nombrearchivo = "cupon-infosicoes-$id_cupon-nemabol.pdf";
    $url_archivo = $dominio."contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=$id_cupon&id_participante=$id_participante";

    $archivo_cont = file_get_contents($url_archivo);

    $subject = $asunto;
    $body = $contenido_correo;

    $nuevoarchivo = fopen($nombrearchivo, "w+");
    fwrite($nuevoarchivo, $archivo_cont);
    fclose($nuevoarchivo);

    $sw_correo_invalido = false;


    $array_correos_a_enviar = explode(",", $correo);
    foreach ($array_correos_a_enviar as $correo_a_enviar) {
        if (emailValido($correo_a_enviar)) {
            SISTsendEmailFULL($correo_a_enviar,$subject,$body,'',array(array($nombrearchivo,$nombrearchivo)));
        }else{
            echo "Email invalido: $correo_a_enviar";
            $sw_correo_invalido = true;
        }
    }

    unlink($nombrearchivo);

    movimiento('Envio digital de cupon [ID-emision: ' . $id_emision_cupon_infosicoes . '] [' . $correo . ']', 'envio-cupon', 'cupon', $id_emision_cupon_infosicoes);

    if(!$sw_correo_invalido){
        echo "<b style='color:green;'>CUPON ENVIADO</b>";
    }
    
} else {
    echo "Denegado!";
}
