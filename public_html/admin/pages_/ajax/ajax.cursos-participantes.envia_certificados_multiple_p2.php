<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* datos de configuracion */
$img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');

/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* datos recibidos */
$ids_participantes_dat = post('ids_participantes');
$id_certificado = post('id_certificado');

$id_administrador = administrador('id');
$fecha_emision = date("Y-m-d H:i:s");

/* existencia post de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    if(isset_post('idpart-'.$value)){
        $ids_participantes .= "," . (int) $value;
    }
}
/* END existencia post de id participante */

/* get regsitros */
$rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id IN(select id_participante from cursos_rel_partcertadicional where id_certificado='$id_certificado') ORDER BY id DESC ");

?>
<table class="table table-striped table-bordered">
    <?php
    while ($rqcp2 = fetch($rqcp1)) {
        $id_participante = $rqcp2['id'];

        /* registros */
        $rqdr1 = query("SELECT p.correo,p.id,e.certificado_id,e.receptor_de_certificado,e.fecha_emision,c.texto_qr FROM cursos_participantes p INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados c ON e.id_certificado=c.id WHERE e.id_participante='$id_participante' AND c.id='$id_certificado' ORDER BY e.id DESC limit 1 ");
        $rqdr2 = fetch($rqdr1);

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

        $contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante), $receptor_de_certificado);

        /* variables para los datos del archivo */
        $nombrearchivo = "certificado-$certificado_id.pdf";
        $url_archivo = $dominio . "contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=$certificado_id";


        $archivo_cont = file_get_contents($url_archivo);

        $subject = $asunto;
        $body = $contenido_correo;

        $nuevoarchivo = fopen($nombrearchivo, "w+");
        fwrite($nuevoarchivo, $archivo_cont);
        fclose($nuevoarchivo);

        SISTsendEmailFULL($correo_participante,$subject,$body,'',array(array($nombrearchivo,$nombrearchivo)));

        unlink($nombrearchivo);

        logcursos('Envio digital de certificado [' . $certificado_id . '] [' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);       
        ?>
        <tr>
            <td>
                <b style='font-size: 15pt !important;padding-bottom: 7pt;'>
                    <?php echo $receptor_de_certificado; ?>
                </b> 
                <br>
                (<?php echo $correo_participante; ?>)
            </td>
            <td>
                <label class="label label-success">CERTIFICADO ENVIADO!</label>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<br/>

<div class="alert alert-success">
  <strong>EXITO!</strong> certificados enviados correctamente.
</div>
