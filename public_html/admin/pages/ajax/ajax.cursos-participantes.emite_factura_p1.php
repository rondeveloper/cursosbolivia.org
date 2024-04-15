<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* datos de participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($resultado1);

/* datos de curso */
$id_curso = $participante['id_curso'];
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,estado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$url_imagen_del_curso = $dominio_www."paginas/" . $rqc2['imagen'] . ".size=4.img";

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
$rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);
$id_proceso_de_registro = $data_registro['id'];
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_emision_factura = $data_registro['id_emision_factura'];

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

/* deshabilitacion de facturacion */
if(isset_post('sw_desactivacion')){
    query("UPDATE cursos_proceso_registro SET id_emision_factura='99' WHERE id='$id_proceso_de_registro' ORDER BY id DESC limit 1 ");
    echo '<div class="alert alert-success">Des-habilitacion exitosa.</div>';
    exit;
}

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
if ($id_emision_factura == '99') {
    echo '<div class="alert alert-warning">FACTURACION DES-HABILITADA</div>';
} elseif ($id_emision_factura == '0') {
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
        $enlace_autofacturacion = $dominio.'aut-fact/'.$id_participante.'/'.md5(md5('autfact1015121'.$id_participante)).'.html';
        $txt_wap_autfact = "Hola ".trim($participante['nombres'] . ' ' . $participante['apellidos'])."__ __Puedes generar tu *factura de ".$monto_de_pago." BS* por tu participación en el curso, desde el siguiente enlace:__ __".$enlace_autofacturacion;
        ?>
        <h3 style="text-align: center;background: #00789f;color: white;padding: 5px;">AUTO-FACTURACI&Oacute;N</h3>
        <?php 
        if(isset_post('sw_autofact')){ 
            logcursos('Generacion de enlace de auto-facturacion', 'partipante-edicion', 'participante', $id_participante);
            ?>
            <div>
                Enlace de auto-facturaci&oacute;n: 
                &nbsp;&nbsp;&nbsp; 
                <b style="font-size: 15pt;color: #327532;"><?php echo $monto_de_pago; ?> BS</b> 
                &nbsp;&nbsp;&nbsp; 
                <a href="https://api.whatsapp.com/send?phone=591<?php echo $participante['celular']; ?>&text=<?php echo str_replace(' ','%20',str_replace('__','%0A',$txt_wap_autfact)); ?>" target="_blank"><img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;cursor:pointer;position: absolute;"></a>
            </div>
            <br>
            <input type="text" class="form-control" value="<?php echo $enlace_autofacturacion; ?>"/>
        <?php 
        }else{ 
        ?>
            <div class="text-center"><b class="btn btn-warning btn-sm" onclick="autofact();">GENERAR ENLACE DE AUTO-FACTURACI&Oacute;N</b></div><br><br>
            <?php 
        } 
        ?>

        <h3 style="text-align: center;background: #00789f;color: white;padding: 5px;">EMISI&Oacute;N DE FACTURA</h3>
        <!-- DIV CONTENT AJAX :: EMITE FACTURA P2 -->
        <div id="ajaxloading-emite_factura_p2"></div>
        <div class="text-center" id='ajaxbox-emite_factura_p2'>
            <form id="form-emite-factura-<?php echo $participante['id']; ?>">
            <table class="table table-striped table-bordered">
                <tr>
                    <td><b>Facturar a nombre de :</b></td>
                    <td><input type="text" class="form-control" name="nombre_a_facturar" value="<?php echo $razon_social_de_registro; ?>"/></td>
                </tr>
                <tr>
                    <td><b>N&uacute;mero de NIT:</b></td>
                    <td><input type="text" class="form-control" name="nit_a_facturar" value="<?php echo $nit_de_registro; ?>"/></td>
                </tr>
                <tr>
                    <td><b>Monto a facturar:</b></td>
                    <td><input type="text" class="form-control" name="monto_a_facturar" value="<?php echo $monto_de_pago; ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id_certificado" value="<?php echo $id_certificado_curso; ?>"/>
                        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                        <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>"/>
                        <br>
                        <b class="btn btn-success" onclick="emite_factura_p2(<?php echo $participante['id']; ?>);">EMITIR FACTURA</b>
                        &nbsp;&nbsp;&nbsp;
                        <b class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</b>
                        <br>
                        &nbsp;
                    </td>
                </tr>
            </table>

            </form>
        </div>

        <br>
        <br>

        <h3 style="text-align: center;background: #00789f;color: white;padding: 5px;">DESACTIVACI&Oacute;N</h3>
        <div class="text-center">
            <p>&iquest; Desea desactivar la facturaci&oacute;n para este participante ?</p>
            <table class="table table-striped table-bordered">
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>"/>
                        <br>
                        <b class="btn btn-danger" onclick="desabilitar_facturacion(<?php echo $participante['id']; ?>);">DESACTIVAR</b>
                        <br>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>

        <?php
    }
    ?>

    <?php
} else {
    $rqef1 = query("SELECT id,nro_factura,nombre_receptor,nit_receptor,total FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
    $rqef2 = fetch($rqef1);
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
            <b>Visualizaci&oacute;n -> </b> <button type="button" class="btn btn-default btn-xs" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1.php?id_factura=<?php echo $rqef2['id']; ?>', 'popup', 'width=700,height=500');">VISUALIZAR FACTURA</button>


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

<script>
    function autofact() {
        $("#ajaxloading-emite_factura_p1").html('Cargando...');
        $("#ajaxbox-emite_factura_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p1.php',
            data: {id_participante: '<?php echo $id_participante; ?>', sw_autofact: 1},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p1").html("");
                $("#ajaxbox-emite_factura_p1").html(data);
            }
        });
    }
</script>

<script>
    function desabilitar_facturacion($id_participante) {
        if(confirm('ESTA SEGURO DE DESHABILITAR LA FACTURACION ?')){
            $("#ajaxloading-emite_factura_p1").html('Cargando...');
            $("#ajaxbox-emite_factura_p1").html("");
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p1.php',
                data: {id_participante: '<?php echo $id_participante; ?>', sw_desactivacion: 1},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxloading-emite_factura_p1").html("");
                    $("#ajaxbox-emite_factura_p1").html(data);
                    lista_participantes('<?php echo $id_curso; ?>', 0);
                }
            });
        }
    }
</script>
