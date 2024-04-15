<?php
$mensaje = '';

/* crear registro */
if (isset_post('emitir-factura')) {

    $concepto = post('concepto');
    $total = post('total');
    $nombre_receptor = post('nombre_receptor');
    $nit_receptor = post('nit_receptor');


    $nombre_a_facturar = $nombre_receptor;
    $nit_a_facturar = $nit_receptor;
    $monto_a_facturar = $total;

    $id_administrador = administrador('id');

    /* datos para emision de factura */
    $rqdf1 = query("SELECT * FROM facturas_dosificaciones WHERE estado='1' ORDER BY id DESC limit 1 ");
    $rqdf2 = mysql_fetch_array($rqdf1);

    $nro_autorizacion = $rqdf2['nro_autorizacion'];
    $nit_emisor = $rqdf2['nit_emisor'];
    $fecha_limite_emision = $rqdf2['fecha_limite_emision'];
    $llave_dosificacion = $rqdf2['llave_dosificacion'];

    $fecha_emision = date("Y-m-d");
    $fecha_registro = date("Y-m-d H:i");

    /* numero de factura */
    $rqfea1 = query("SELECT nro_factura FROM facturas_emisiones ORDER BY nro_factura DESC limit 1 ");
    $rqfea2 = mysql_fetch_array($rqfea1);
    $nro_factura = (int) ($rqfea2['nro_factura'] + 1);

    /* generacion de codigo de control */
    $codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), $monto_a_facturar, $llave_dosificacion);

    /* estado */
    $estado = '1';

    $archivo_visualizador = 'factura-1';

    /* prefactura */
    $sw_prefactura = post('sw_prefactura');
    if ($sw_prefactura == '1') {
        $estado = '3';
        $archivo_visualizador = 'factura-2';
    }

    query("INSERT INTO facturas_emisiones(
           id_administrador, 
           nro_factura, 
           nro_autorizacion, 
           nit_emisor, 
           fecha_limite_emision, 
           codigo_de_control, 
           nombre_receptor, 
           nit_receptor, 
           total, 
           concepto, 
           fecha_emision, 
           ciudad_emision, 
           fecha_registro, 
           estado
           ) VALUES (
           '$id_administrador',
           '$nro_factura',
           '$nro_autorizacion',
           '$nit_emisor',
           '$fecha_limite_emision',
           '$codigo_de_control',
           '$nombre_a_facturar',
           '$nit_a_facturar',
           '$monto_a_facturar',
           '$concepto',
           '$fecha_emision',
           'LA PAZ',
           '$fecha_registro',
           '$estado'
           )");

    /* id de emision de factura */
    $rqef1 = query("SELECT id FROM facturas_emisiones WHERE nro_factura='$nro_factura' AND codigo_de_control='$codigo_de_control' ORDER BY id DESC limit 1 ");
    $rqef2 = mysql_fetch_array($rqef1);
    $id_emision_factura = $rqef2['id'];



    $mensaje .= '<div class="alert alert-success">
        <strong>Exito!</strong> Factura emitida exitosamente.
    </div>

    <table class="table table-striped">
        <tr>
            <td>Nro. de Factura: </td>
            <td>' . $nro_factura . '</td>
        </tr>
        <tr>
            <td>Factura a nombre de: </td>
            <td>' . $nombre_a_facturar . '</td>
        </tr>
        <tr>
            <td>NIT: </td>
            <td>' . $nit_a_facturar . '</td>
        </tr>
        <tr>
            <td>Monto facturado: </td>
            <td>' . $monto_a_facturar . '</td>
        </tr>
        <tr>
            <td>Fecha de emision: </td>
            <td>' . $fecha_emision . '</td>
        </tr>
        <tr>
            <td>Codigo de control: </td>
            <td>' . $codigo_de_control . '</td>
        </tr>
        <tr>
            <td>Nro. de autorizaci&oacute;n: </td>
            <td>' . $nro_autorizacion . '</td>
        </tr>
        <tr>
            <td colspan="2">
                <br/>
                <br/>
                <b>Impresi&oacute;n -> </b> <button onclick="window.open(\'http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/' . $archivo_visualizador . '.php?nro_factura=' . $nro_factura . '\', \'popup\', \'width=700,height=500\');" class="btn btn-default btn-xs">IMPRIMIR FACTURA</button>

            </td>
        </tr>
    </table>';
}
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Reporte de facturas</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> REPORTE DE FACTURAS MENSUALES</h3>
        <blockquote class="page-information hidden">
            <p>
                Reporte de facturas mensuales
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">REPORTE DE FACTURAS MENSUALES</div>
                    <div class="panel-body">

                        <table class="table table-striped">
                            <tr>
                                <td style='width:35%;'>
                                    <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; DOCIFICACI&Oacute;N DE FACTURAS: </span>
                                </td>
                                <td>
                                    <select class="form-control" name='id_actividad' id="input-id_actividad">
                                        <?php
                                        $rqfac1 = query("SELECT * FROM facturas_actividades ORDER BY id DESC");
                                        while($rqfac2 = mysql_fetch_array($rqfac1)){
                                            ?>
                                            <option value="<?php echo $rqfac2['id']; ?>"><?php echo $rqfac2['actividad']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='width:35%;'>
                                    <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; MES DE FACTURACIONES: </span>
                                </td>
                                <td>
                                    <select class="form-control" name='mes' id="input-mes">
                                        <?php
                                        $array_meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                        $array_meses_val = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                                        for ($i = 0; $i < 12; $i++) {
                                            $txt_selected = '';
                                            if (((int) date("m") - 2) == $i) {
                                                $txt_selected = ' selected="selected" ';
                                            }
                                            ?>
                                            <option value="<?php echo $array_meses_val[$i]; ?>" <?php echo $txt_selected; ?> ><?php echo $array_meses[$i]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='width:35%;'>
                                    <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; A&Ntilde;O: </span>
                                </td>
                                <td>
                                    <input type="number" name="anio"  id="input-anio" value="<?php echo date("Y"); ?>" min="2016" max="2050" maxlength="4" class="form-control" required=""/>
                                </td>
                            </tr>
                        </table>
                        
                        
                        
                    </div>
                    <div class="panel-footer">

                        <div style="text-align: center;padding:20px;">
                            
<!--                            <input type="submit" name="emitir-factura" value="GENERAR REPORTE" class="btn btn-success btn-lg active"/>-->
                            <button name="emitir-factura" class="btn btn-success active" data-toggle="modal" data-target="#MODAL-generar-reporte-facturas" onclick="generar_reporte();">GENERAR REPORTE</button>
                        </div>

                    </div>
                </div>
            </div>


    </div>
</div>



<!-- Modal-1 -->
<div id="MODAL-generar-reporte-facturas" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERACI&Oacute;N DE REPORTE</h4>
            </div>
            <div class="modal-body">
                
                <div id="ajaxloading-generar_reporte"></div>
                <div id="ajaxbox-generar_reporte"></div>

                <hr/>                                        

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-1 -->


<script>
    var text__loading_dos = "Cargando... <img src='contenido/imagenes/images/load_ajax.gif'/>";
    
    function generar_reporte() {
        var mes = $("#input-mes").val();
        var anio = $("#input-anio").val();
        var id_actividad = $("#input-id_actividad").val();
        $("#ajaxloading-generar_reporte").html(text__loading_dos);
        $("#ajaxbox-generar_reporte").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.facturas-reporte.generar_reporte.php',
            data: {mes: mes,anio: anio,id_actividad: id_actividad},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-generar_reporte").html("");
                $("#ajaxbox-generar_reporte").html(data);
            }
        });
    }
</script>