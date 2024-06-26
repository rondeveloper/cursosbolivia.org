<?php

use Dompdf\Dompdf;
use Dompdf\Options;

/* data */
$id_proceso_registro = $ar1[1];

$content_html_final = "<div style='font-family:Helvetica;'>";

$rqrpc1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqrpc2 = fetch($rqrpc1);
$codigo_registro = $rqrpc2['codigo'];
$cnt_participantes_registro = $rqrpc2['cnt_participantes'];
$razon_social_registro = $rqrpc2['razon_social'];
$nit_registro = $rqrpc2['nit'];
$fecha_registro = $rqrpc2['fecha_registro'];
$id_modo_pago = $rqrpc2['id_modo_pago'];
$id_cobro_khipu = $rqrpc2['id_cobro_khipu'];
$monto_deposito_registro = $rqrpc2['monto_deposito'];
$imagen_deposito_registro = $rqrpc2['imagen_deposito'];
$sw_pago_enviado = $rqrpc2['sw_pago_enviado'];
$id_turno = $rqrpc2['id_turno'];
$id_administrador_registro = $rqrpc2['id_administrador'];
$id_curso = $rqrpc2['id_curso'];

/* curso */
$rqc1 = query("SELECT *,(select nombre from ciudades where id=c.id_ciudad limit 1)ciudad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
if(num_rows($rqc1)==0){
    echo "<h3>ERROR</h3>Por favor vuelva a realizar su inscripci&oacute;n";
    exit;
}
$rqc2 = fetch($rqc1);
$nombre_curso = $rqc2['titulo_formal'];
$costo_curso = $rqc2['costo'];
$id_modalidad_curso = $rqc2['id_modalidad'];
$url_curso = $dominio . $rqc2['titulo_identificador'] . ".html";

/* lugar */
$rqcl1 = query("SELECT nombre FROM cursos_lugares WHERE id='" . $rqc2['id_lugar'] . "' ORDER BY id DESC limit 1 ");
$rqcl2 = fetch($rqcl1);
$lugar_curso = $rqcl2['nombre'];

$fecha_curso = $rqc2['fecha'];
$horario_curso = $rqc2['horarios'];
$ciudad_curso = $rqc2['ciudad'];

$url_imagen_deposito = $dominio."contenido/imagenes/depositos/$imagen_deposito_registro";

/* helpers */
$array_expedido_base = array('LP','OR','PS','CB','SC','BN','PD','TJ','CH');
$array_expedido_short = array('LP','OR','PT','CB','SC','BN','PA','TJ','CH');

/* https://www.infosicoes.com/contenido/imagenes/depositos/$imagen_deposito_registro */


if ($id_turno == '0') {
    $tr_turno = "";
} else {
    $rqdt1 = query("SELECT titulo,descripcion FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
    $rqdt2 = fetch($rqdt1);

    $tr_turno = "<tr><td style='padding:5px;font-size: 9pt;'><b>Turno:</b></td><td style='padding:5px;font-size: 9pt;' colspan='3'>" . $rqdt2['titulo'] . " | " . $rqdt2['descripcion'] . "</td></tr>";
}


/* cabecera */
$content_html_final .= "<div style='border:1px dashed #DDD;padding:10px;'>
  
<table style='width:100%;'>
<tr>
<td>
<h3 style='padding-bottom:5px;margin-bottom:0px;text-align:left;color:#003e54;padding-left:0px;'>
            FICHA DE INSCRIPCI&Oacute;N $codigo_registro
</h3>
<p style='text-align: left;'><span style='font-size: 12pt; color: #ff0000;'><strong>$nombre_curso<br></strong></span></p>
</td>
<td style='text-align:center;width:30%;'>
<img src='".$dominio."contenido/alt/logotipo-v3.png' style='width:200px;background: ".$___color_base.";padding: 15px 20px;border-radius: 5px;' alt='".$___nombre_del_sitio."'>
</td>
</tr>
</table>
<div>";

/* parrafo descripcion */
if($id_modalidad_curso=='2' || $id_modalidad_curso=='3'){
    $content_html_final .= "<p style='text-align: left;'>
                <span style='font-size: 10pt; color: #000000;'>
                    La presente ficha hace constar la inscripci&oacute;n al curso '$nombre_curso' a llevarse a cabo de forma virtual, a continuaci&oacute;n se muestran detalles de la inscripci&oacute;n. 
                </span>
            </p>";
}else{
    $content_html_final .= "<p style='text-align: left;'>
                <span style='font-size: 10pt; color: #000000;'>
                    La presente ficha hace constar la inscripci&oacute;n al curso '$nombre_curso' a llevarse a cabo 
                    en fecha $fecha_curso en la ciudad de $ciudad_curso, a continuaci&oacute;n se muestran detalles de la inscripci&oacute;n. 
                </span>
            </p>";
}

/* datos de curso */
$content_html_final .= "
<div style='padding:10px 30px;'>
                <div>
                    <table style='width:100%;'>
                        <tr>
                        <td style='padding:5px;font-size: 9pt;'><b>Codigo de registro:</b></td><td style='padding:5px;font-size: 9pt;'>$codigo_registro</td>
                        <td style='padding:5px;font-size: 9pt;'><b>Nro de participantes:</b></td><td style='padding:5px;font-size: 9pt;'>1 participante(s)</td>
                        </tr>
                        <tr><td style='padding:5px;font-size: 9pt;'><b>Curso:</b></td><td style='padding:5px;font-size: 9pt;' colspan='3'>$nombre_curso</td></tr>
                        $tr_turno
                        ";
if($id_modalidad_curso!=='2' && $id_modalidad_curso!=='3'){
    $content_html_final .=  "<tr>
                        <td style='padding:5px;font-size: 9pt;'><b>Lugar:</b></td><td style='padding:5px;font-size: 9pt;'>$lugar_curso</td>
                        <td style='padding:5px;font-size: 9pt;'><b>Fecha:</b></td><td style='padding:5px;font-size: 9pt;'>".date("d / m / Y",strtotime($fecha_curso))."</td>
                        </tr>";
}
$content_html_final .= "<tr>
                        <td style='padding:5px;font-size: 9pt;'><b>Fecha de registro:</b></td><td style='padding:5px;font-size: 9pt;'>".date("d / m / Y H:i",strtotime($fecha_registro))."</td>
                        <td style='padding:5px;font-size: 9pt;'><b>Monto de pago:</b></td><td style='padding:5px;font-size: 9pt;'>".(int)$monto_deposito_registro." Bolivianos</td>
                        </tr>
                    </table>
                </div>
            </div>";



$content_html_final .= "<hr>
            <table style='width:100%;'>
                <tr>
                    <td style='width:20%;'>";

$sw_facturacion = false;
if ((strlen($razon_social_registro) > 3) && (strlen($nit_registro) > 3)) {
    $sw_facturacion = true;
}
/* codigo QR */
include_once '../../librerias/phpqrcode/qrlib.php';
$file = 'qrcode-registro-curso-' . $id_proceso_registro . '.png';
$file_qr_deposito = '../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_deposito)) {
    copy('../../imagenes/qrcode/jr-qrcode.png', $file_qr_deposito);
}
$data = $codigo_registro . ',' . $nombre_curso . ',' . $fecha_curso;
QRcode::png($data, $file_qr_deposito);
$content_html_final .= "<img src='".$dominio."contenido/imagenes/qrcode/$file' style='width:120px;height:120px;'/>
                        </td>
                        <td style='width:60%;'>

                            <table style='width:100%;font-size:11pt;'>";

if ($sw_facturacion) {
    $content_html_final .= "
                                    <tr>
                                        <td><b>Factura a nombre de:</b></td>
                                        <td>$razon_social_registro</td>
                                    </tr>
                                    <tr>
                                        <td><b>N&uacute;mero de NIT:</b></td>
                                        <td>$nit_registro</td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' style='text-align:center;padding:15px;'><b>Participante(s):</b></td>
                                    </tr>";
}

$rqpc1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 20 ");
while ($rqpc2 = fetch($rqpc1)) {
    $nombres_participante = $rqpc2['nombres'];
    $apellidos_participante = $rqpc2['apellidos'];
    $prefijo_participante = $rqpc2['prefijo'];
    $celular_participante = $rqpc2['celular'];
    $correo_participante = $rqpc2['correo'];
    $ci_participante = $rqpc2['ci'];
    $ci_expedido_participante = $rqpc2['ci_expedido'];

    $content_html_final .= "
                                    <tr>
                                        <td><b>Nombres:</b></td>
                                        <td>$nombres_participante</td>
                                    </tr>
                                    <tr>
                                        <td><b>Apellidos:</b></td>
                                        <td>$apellidos_participante</td>
                                    </tr>";
    if ($prefijo_participante !== '') {
        $content_html_final .= "<tr>
                                        <td><b>Prefijo:</b> (Profesi&oacute;n)</td>
                                        <td>$prefijo_participante</td>
                                    </tr>";
    }
    if ($ci_participante !== '') {
        $content_html_final .= "<tr>
                                        <td><b>C.I.:</b></td>
                                        <td>$ci_participante ".str_replace($array_expedido_base, $array_expedido_short, $ci_expedido_participante)."</td>
                                    </tr>";
    }
    if ($correo_participante !== '') {
        $content_html_final .= "<tr>
                                        <td><b>Correo:</b></td>
                                        <td>$correo_participante</td>
                                    </tr>";
    }
    if ($celular_participante !== '') {
        $content_html_final .= "<tr>
                                        <td><b>Celular:</b></td>
                                        <td>$celular_participante</td>
                                    </tr>";
    }
}
$content_html_final .= "</table>

                        </td>
                        <td style='width:20%;text-align:right;'>";
if ($sw_facturacion) {
    //codigo QR
    include_once '../../librerias/phpqrcode/qrlib.php';
    $file = 'qrcode-registro-curso-fact-' . $id_proceso_registro . '.png';
    $file_qr_deposito = '../../imagenes/qrcode/' . $file;
    if (!is_file($file_qr_deposito)) {
        copy('../../imagenes/qrcode/jr-qrcode.png', $file_qr_deposito);
    }
    $data = $razon_social_registro . ',' . $nit_registro;
    QRcode::png($data, $file_qr_deposito);
    $content_html_final .= "
                                <img src='".$dominio."contenido/imagenes/qrcode/$file' style='width:120px;height:120px;'/>";
}

if($costo_curso==0){
    $cont_comprobante_de_pago = "<h3 style='text-align:center;'>CURSO GRATUITO</h3>";
} elseif ($id_modo_pago == '1') {
    $cont_comprobante_de_pago = "<div style='text-align:center;border:2px solid blue;padding:10px;border-radius:10px;'>";
    $cont_comprobante_de_pago .= "<b>NOTA:</b> registro realizado por un administrador.";
    $cont_comprobante_de_pago .= "</div>";
} else {

    if ($id_modo_pago == '6') {
        $rqck1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1");
        $rqck2 = fetch($rqck1);
        if ($rqck2['estado'] == '1') {
            $cont_comprobante_de_pago = "<div style='text-align:center;border:2px solid green;padding:10px;border-radius:10px;'>";
            $cont_comprobante_de_pago .= "<b>PAGO VERIFICADO:</b> el pago por Khipu fue completado correctamente.";
            $cont_comprobante_de_pago .= "<br/><br/>URL: https://khipu.com/payment/info/" . $rqck2['payment_id'];
            $cont_comprobante_de_pago .= "</div>";
        } else {
            $cont_comprobante_de_pago = "<div style='text-align:center;border:2px solid red;padding:10px;border-radius:10px;'>";
            $cont_comprobante_de_pago .= "<b>AVISO:</b> el pago por Khipu no fue completado.";
            $cont_comprobante_de_pago .= "<br/><br/>URL: https://khipu.com/payment/info/" . $rqck2['payment_id'];
            $cont_comprobante_de_pago .= "</div>";
        }
    } else {
        if ($sw_pago_enviado == '1' && $imagen_deposito_registro!=='') {
            $cont_comprobante_de_pago = "<div style='text-align:center;'>";
            $cont_comprobante_de_pago .= "<img src='$url_imagen_deposito' style='max-width:100%;max-height:450px;'/>";
            $cont_comprobante_de_pago .= "</div>";
        } elseif ($sw_pago_enviado != '1') {
            $cont_comprobante_de_pago = "<div style='text-align:center;border:2px solid red;padding:10px;border-radius:10px;'>";
            $cont_comprobante_de_pago .= "<b>AVISO:</b> la imagen del comprobante de deposito no fue enviado al sistema.";
            $cont_comprobante_de_pago .= "</div>";
        }
    }
}

$content_html_final .= "</td>
                </tr>
            </table>
            <br/>
            ";

/* sesion de zoom */
/*
$rqvsz1 = query("SELECT * FROM sesiones_zoom WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqvsz1)>0 && $sw_pago_enviado == '1') {
    $rqvsz2 = fetch($rqvsz1);
    $content_html_final .= '
    <br><div style="padding:0px 0px 30px 0px;">
    <div>
    <table style="width:100%;">
    <tr>
        <td style="padding:7px 10px;border:1px solid #444;">
            <b>ZOOM</b> | '.$rqvsz2['descripcion'].'
        </td>
    </tr>
    <tr>
        <td style="padding:15px;border:1px solid #444;">
            Unirse a la reunión Zoom
            <br>
            <a href="'.$rqvsz2['url'].'">'.$rqvsz2['url'].'</a>
            <br>
            <br>
            <br>
            ID de reuni&oacute;n: '.$rqvsz2['reunion_id'].'
            <br>
            C&oacute;digo de acceso: '.$rqvsz2['codigo_acceso'].'
            <br>
            <br>
            Fecha y Hora: '.date("d/m/Y H:i",strtotime($rqvsz2['fecha'])).'
        </td>
    </tr>
</table>
</div>
</div>';
}
*/

$content_html_final .= "<hr><div style='clear:both;'></div>
$cont_comprobante_de_pago
<div style='clear:both;'></div>";

$content_html_final .= "<p style='text-align: left;'>
                <span style='font-size: 10pt; color: #000000;'>";

                if($costo_curso>0){
if($id_modalidad_curso!='1'){
    $content_html_final .= "Una vez validado el reporte de pago por uno de nuestros administradores se le hara env&iacute;o de un correo de activaci&oacute;n con los datos de acceso al curso virtual.";
}else{
    $content_html_final .= "Para poder hacer el ingreso el d&iacute;a del curso es necesario llevar esta ficha previamente impresa junto con el deposito original del pago realizado.";
}
                }

$content_html_final .= "<br/>
                    <br/>
                    Muchas gracias por realizar tu inscripci&oacute;n.
                </span>
            </p>";
            
//if(isset_administrador() && administrador('id')==$id_administrador_registro){
if($id_administrador_registro!=0){
    $rqdad1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador_registro' LIMIT 1 ");
    $rqdad2 = fetch($rqdad1);
    $content_html_final .=  "<br><br><center>--------------------------------<br><b style='font-size:8pt;'>REGISTRO EN OFICINA</b><br>".$rqdad2['nombre']."</center><br><br>";
}

$content_html_final .=  "</div>
    </div>
</div>";


if (isset($ar1[2]) && $ar1[2] == 'pdf') {
    /* carga composer autoload */
    require_once $___path_raiz . '../vendor/autoload.php';
    define("DOMPDF_ENABLE_CSS_FLOAT", true);
    $busc_imagenes = array('img/logotipo.png1221');
    $remm_imagenes = array('/proy-SIF/fc-bcb/img/logotipo.png');
    //*$dompdf = new Dompdf();

    $options = new Options();
    $options->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml((str_replace($busc_imagenes, $remm_imagenes, $content_html_final)));
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream(limpiar_enlace(trim($nombres_participante.' '.$apellidos_participante)).'-R00'.$id_proceso_registro.'.pdf');
} else {


    echo $content_html_final;
    ?>

    <script>
        window.print();
    </script>

    <?php

}
?>
