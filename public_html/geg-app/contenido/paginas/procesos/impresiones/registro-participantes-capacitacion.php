<?php
use Dompdf\Dompdf;
use Dompdf\Options;

/* data */
$id_proceso_registro = $ar1[1];

//echo "Holaa";
//echo "<hr/>";
//echo "-> $data<br/>";
//echo "-> $cleardata<br/>";


$content_html_final = "<div>";


    $rqrpc1 = query("SELECT * FROM capacitaciones_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    $rqrpc2 = mysql_fetch_array($rqrpc1);
    $codigo_registro = $rqrpc2['codigo'];
    $cnt_participantes_registro = $rqrpc2['cnt_participantes'];
    $razon_social_registro = $rqrpc2['razon_social'];
    $nit_registro = $rqrpc2['nit'];
    $fecha_registro = $rqrpc2['fecha_registro'];
    $monto_deposito_registro = $rqrpc2['monto_deposito'];
    $imagen_deposito_registro = $rqrpc2['imagen_deposito'];
    $id_curso = $rqrpc2['id_capacitacion'];
    $rqc1 = query("SELECT *,(select nombre from departamentos where id=c.id_ciudad limit 1)ciudad FROM capacitaciones c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);
    $nombre_curso = $rqc2['titulo'];
    $url_curso = "https://www.infosicoes.com/cursos/" . $rqc2['titulo_identificador'] . ".html";
    $lugar_curso = $rqc2['lugar'];
    $fecha_curso = $rqc2['fecha'];
    $horario_curso = $rqc2['horarios'];
    $ciudad_curso = $rqc2['ciudad'];


$content_html_final .= "<div style='border:1px dashed #DDD;'>
        <h2 style='padding-bottom:5px;margin-bottom:0px;text-align:center;color:#003e54;'>
            FICHA DE INSCRIPCI&Oacute;N PARA CURSO INFOSICOES
        </h2>
        <div style='width:20%;margin:auto;'>
            <img src='https://www.infosicoes.com/images/logo_infosicoes.png.img' style='width:100%;padding:5px 10px;border:1px solid gray;border-radius:10px;' alt='Cursos InfoSICOES'>
        </div>
        <div>
            <br/>
            <p style='text-align: left;'><span style='font-size: 12pt; color: #ff0000;'><strong>$nombre_curso<br></strong></span></p>
            <p style='text-align: left;'>
                <span style='font-size: 10pt; color: #000000;'>
                    La presente ficha hace constar la inscripci&oacute;n al curso '$nombre_curso' a llevarse a cabo 
                    en fecha $fecha_curso en la ciudad de $ciudad_curso. A continuaci&oacute;n se muestran detalles de la inscripci&oacute;n. 
                </span>
                <br/>
                <b>Datos de inscripci&oacute;n</b>
            </p>

            <?php ?>
            <div style='padding:10px 30px;'>
                <div>
                    <table style='width:100%;'>
                        <tr><td style='padding:5px;font-size: 9pt;'>Codigo de registro:</td><td style='padding:5px;font-size: 9pt;'>$codigo_registro</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Nro de participantes:</td><td style='padding:5px;font-size: 9pt;'>$cnt_participantes_registro participante(s)</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Curso:</td><td style='padding:5px;font-size: 9pt;'>$nombre_curso</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Url del curso:</td><td style='padding:5px;font-size: 9pt;'>$url_curso</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Lugar:</td><td style='padding:5px;font-size: 9pt;'>$lugar_curso</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Fecha:</td><td style='padding:5px;font-size: 9pt;'>$fecha_curso</td></tr>
<!--                        <tr><td style='padding:5px;font-size: 9pt;'>Horario:</td><td style='padding:5px;font-size: 9pt;'>$horario_curso</td></tr>-->
                        <tr><td style='padding:5px;font-size: 9pt;'>Fecha de registro:</td><td style='padding:5px;font-size: 9pt;'>$fecha_registro</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Fecha de deposito:</td><td style='padding:5px;font-size: 9pt;'>Seg&uacute;n el comprobante enviado</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Monto de deposito:</td><td style='padding:5px;font-size: 9pt;'>$monto_deposito_registro Bolivianos</td></tr>
                        <tr><td style='padding:5px;font-size: 9pt;'>Deposito:</td><td style='padding:5px;font-size: 9pt;'>https://www.infosicoes.com/contenido/imagenes/depositos/$imagen_deposito_registro</td></tr>
                    </table>
                </div>
            </div>

            <br/>

            <table style='width:100%;'>
                <tr>
                    <td>";

                        $sw_facturacion = false;
                        if ((strlen($razon_social_registro) > 3) && (strlen($nit_registro) > 3)) {
                            $sw_facturacion = true;
                        }
$content_html_final .= "<div style='width:20%;float:left;text-align:left;'>";
                            //codigo QR
                            include_once '../../librerias/phpqrcode/qrlib.php';
                            $file = 'qrcode-registro-capacitacion-' . $id_proceso_registro . '.png';
                            $file_qr_deposito = 'contenido/imagenes/qrcode/' . $file;
                            if (!is_file($file_qr_deposito)) {
                                copy('../../imagenes/qrcode/jr-qrcode.png', $file_qr_deposito);
                            }
                            $data = $codigo_registro . ',' . $nombre_curso . ',' . $fecha_curso;
                            QRcode::png($data, $file_qr_deposito);
$content_html_final .= "<img src='http://www.infosicoes.com/contenido/imagenes/qrcode/$file' style='width:120px;height:120px;'/>
                        </div>
                        <div style='width:60%;float:left;text-align:left;'>

                            <table style='width:100%;'>";
            
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
                                        <td colspan='2' style='text-align:center;'><b>Participante(s):</b></td>
                                    </tr>";
                                }
                                
                                $rqpc1 = query("SELECT * FROM capacitaciones_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 20 ");
                                while ($rqpc2 = mysql_fetch_array($rqpc1)) {
                                    $nombres_participante = $rqpc2['nombres'];
                                    $apellidos_participante = $rqpc2['apellidos'];
                                    $prefijo_participante = $rqpc2['prefijo'];
                                    $celular_participante = $rqpc2['celular'];
                                    $correo_participante = $rqpc2['correo'];
                                    
                          $content_html_final .= "
                                    <tr>
                                        <td><b>Nombres:</b></td>
                                        <td>$nombres_participante</td>
                                    </tr>
                                    <tr>
                                        <td><b>Apellidos:</b></td>
                                        <td>$apellidos_participante</td>
                                    </tr>
                                    <tr>
                                        <td><b>Prefijo:</b> (Profesi&oacute;n)</td>
                                        <td>$prefijo_participante</td>
                                    </tr>
                                    <tr>
                                        <td><b>Celular:</b></td>
                                        <td>$celular_participante</td>
                                    </tr>
                                    <tr>
                                        <td><b>Correo:</b></td>
                                        <td>$correo_participante</td>
                                    </tr>";
                                }
$content_html_final .= "</table>


                        </div>
                        <div style='width:20%;float:left;text-align:right;'>";
                            if ($sw_facturacion) {
                                //codigo QR
                                include_once '../../librerias/phpqrcode/qrlib.php';
                                $file = 'qrcode-registro-capacitacion-fact-' . $id_proceso_registro . '.png';
                                $file_qr_deposito = 'contenido/imagenes/qrcode/' . $file;
                                if (!is_file($file_qr_deposito)) {
                                    copy('../../imagenes/qrcode/jr-qrcode.png', $file_qr_deposito);
                                }
                                $data = $razon_social_registro . ',' . $nit_registro;
                                QRcode::png($data, $file_qr_deposito);
                     $content_html_final .= "
                                <img src='http://www.infosicoes.com/contenido/imagenes/qrcode/$file' style='width:120px;height:120px;'/>";
                            }
                            
         $content_html_final .= "
                        </div>
                    </td>
                </tr>
            </table>

            <br/>
            <div style='clear:both;'></div>

            <p style='text-align: left;'>
                <span style='font-size: 10pt; color: #000000;'>
                    Para poder hacer el ingreso el d&iacute;a del curso es necesario llevar esta ficha previamente impresa junto con el deposito original del pago realizado.
                    <br/>
                    <br/>
                    Muchas gracias por realizar tu inscripci&oacute;n.
                </span>
            </p>

        </div>
    </div>



</div>";


if (isset($ar1[2]) && $ar1[2] == 'pdf') {
    include_once '../../librerias/dompdf/autoload.inc.php';
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
    $dompdf->stream();
} else {


    echo $content_html_final;
    ?>

    <script>
        window.print();
    </script>

    <?php
}
?>
