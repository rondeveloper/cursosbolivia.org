<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* datos de participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = mysql_fetch_array($resultado1);

/* datos de curso */
$id_curso = $participante['id_curso'];
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,estado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];
/* sw de habilitacion de procesos */
if ($rqc2['estado'] == '1' || $rqc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

/* codigo de certificado */
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];


/* datos de registro */
$rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = mysql_fetch_array($rqrp1);
$id_proceso_de_registro = $data_registro['id'];
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_modo_de_registro = $data_registro['id_modo_de_registro'];
$id_emision_factura = $data_registro['id_emision_factura'];

$metodo_de_pago = $data_registro['metodo_de_pago'];
$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];
?>
<div class="row">
    <div class="col-md-6 text-left">
        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
        <br/>
        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
        <br/>
        <b>REGISTRO:</b> &nbsp; <?php echo $codigo_de_registro; ?>
        <br/>
        <b>PARTICIPANTE:</b> &nbsp; <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        <br/>
        <b>CORREO:</b> &nbsp; <?php echo trim($correo_de_registro); ?>
    </div>
    <div class="col-md-6 text-right">
        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;font-weight: bold;">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>                                      
<hr/>
<?php
if ($id_emision_factura == '0') {
    ?>
    <div class="row">
        <div class="col-md-12 text-left">
            <?php
            $text_aux_solictud_factura = "NO SOLICITO FACTURA";
            if (($razon_social_de_registro !== '') && ($nit_de_registro !== '')) {
                $text_aux_solictud_factura = "SI SOLICITO FACTURA";
            }
            ?>
            <b>Solicitud de factura :</b> &nbsp; <?php echo $text_aux_solictud_factura; ?>
            <br/>
            <b>Proceso de emision :</b> &nbsp; FACTURA NO EMITIDA
            <br/>
            <b>Factura a nombre de :</b> &nbsp; <?php echo $razon_social_de_registro; ?>
            <br/>
            <b>N&uacute;mero de NIT:</b> &nbsp; <?php echo $nit_de_registro; ?>
        </div>
    </div>
    <hr/>
    <?php
    if ($sw_habilitacion_procesos) {
        ?>
        <!-- DIV CONTENT AJAX :: EMITE FACTURA P2 -->
        <div id="ajaxloading-emite_factura_p2"></div>
        <div class="text-center" id='ajaxbox-emite_factura_p2'>
            <form id="form-emite-factura-<?php echo $participante['id']; ?>">
                
                <div class="row">
                    <div class="col-md-4 text-right">
                        <span class="input-group-addon"><b>Facturar a nombre de :</b></span>
                    </div>
                    <div class="col-md-8 text-left">
                        <input type="text" class="form-control" name="nombre_a_facturar" value="<?php echo $razon_social_de_registro; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        <span class="input-group-addon"><b>N&uacute;mero de NIT:</b></span>
                    </div>
                    <div class="col-md-8 text-left">
                        <input type="text" class="form-control" name="nit_a_facturar" value="<?php echo $nit_de_registro; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        <span class="input-group-addon"><b>Monto a facturar:</b></span>
                    </div>
                    <div class="col-md-8 text-left">
                        <input type="text" class="form-control" name="monto_a_facturar" value="<?php echo $monto_de_pago; ?>"/>
                    </div>
                </div>
                
                <input type="hidden" name="id_certificado" value="<?php echo $id_certificado_curso; ?>"/>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>"/>

                <br/>
                
                <div class="text-center">
                    <b class="btn btn-success" onclick="emite_factura_p2(<?php echo $participante['id']; ?>);">EMITIR FACTURA</b>
                    &nbsp;&nbsp;&nbsp;
                    <b class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</b>
                </div>

            </form>
        </div>
        <?php
    }
    ?>

    <?php
} else {
    $rqef1 = query("SELECT id,nro_factura,nombre_receptor,nit_receptor,total FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
    $rqef2 = mysql_fetch_array($rqef1);
    ?>
    <div class="row">
        <div class="col-md-12 text-left">
            <b>Solicitud de factura :</b> &nbsp; SI SOLICITO FACTURA
            <br/>
            <b>Proceso de emision :</b> &nbsp; FACTURA EMITIDA
            <br/>
            <br/>
            <b>Facturado a nombre de :</b> &nbsp; <?php echo $rqef2['nombre_receptor']; ?>
            <br/>
            <b>N&uacute;mero de NIT:</b> &nbsp; <?php echo $rqef2['nit_receptor']; ?>
            <br/>
            <b>Monto facturado:</b> &nbsp; <?php echo $rqef2['total']; ?>
            <br/>
            <br/>
            <b>Nro. de factura:</b> &nbsp; <?php echo $rqef2['nro_factura']; ?>
            <br/>
            <b>Visualizaci&oacute;n -> </b> <button type="button" class="btn btn-default btn-xs" onclick="window.open('<?php echo $dominio; ?>contenido/librerias/fpdf/tutorial/factura-1.php?id_factura=<?php echo $rqef2['id']; ?>', 'popup', 'width=700,height=500');">VISUALIZAR FACTURA</button>


            <?php
            if ($sw_habilitacion_procesos) {
                ?>
                <br/>
                <hr/>
                <h4 class="text-center">ENVIO DE FACTURA DIGITAL</h4>
                <div class="text-center" id='box-modal_envia-factura-<?php echo $rqef2['id']; ?>'>
                    <h5 class="text-center">
                        Ingrese el correo al cual se hara el envio de la factura
                    </h5>
                    <div class="row">
                        <div class="col-md-8 text-right">
                            <input type="text" id="correo-de-envio-<?php echo $rqef2['id']; ?>" class="form-control text-center" value="<?php echo $participante['correo']; ?>"/>
                        </div>
                        <div class="col-md-3 text-left">
                            <button class="btn btn-success" onclick="enviar_factura('<?php echo $rqef2['id']; ?>');"><i class='fa fa-send'></i> ENVIAR</button>
                        </div>
                    </div>
                    <br/>
                    <br/>
                </div>
                <hr/>
                <?php
            }
            ?>


        </div>
    </div>
    <?php
}
?>

<hr/>



