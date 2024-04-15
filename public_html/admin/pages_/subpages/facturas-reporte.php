<?php
/* datos de configuracion */
$sw_facturacion_modulos = $__CONFIG_MANAGER->getSw('sw_facturacion_modulos');

$mensaje = '';
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
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
                    <?php if($sw_facturacion_modulos){ ?>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>
                                <td style='width:35%;'>
                                    <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; DOCIFICACI&Oacute;N DE FACTURAS: </span>
                                </td>
                                <td>
                                    <select class="form-control" name='id_dosificacion' id="input-id_dosificacion">
                                        <?php
                                        $rqfac1 = query("SELECT (d.id)dr_id_dosificacion,(a.actividad)dr_actividad,(d.nit_emisor)dr_nit_emisor,(d.nro_autorizacion)dr_nro_autorizacion FROM facturas_dosificaciones d INNER JOIN facturas_actividades a ON d.id_actividad=a.id WHERE d.sw_reporte=1 ORDER BY d.id DESC");
                                        while($rqfac2 = fetch($rqfac1)){
                                            ?>
                                            <option value="<?php echo $rqfac2['dr_id_dosificacion']; ?>"><?php echo $rqfac2['dr_nit_emisor'].' | '.$rqfac2['dr_nro_autorizacion'].' | '.$rqfac2['dr_actividad']; ?></option>
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
                            <button name="" class="btn btn-success active" data-toggle="modal" data-target="#MODAL-generar-reporte-facturas" onclick="generar_reporte();">GENERAR REPORTE</button>
                        </div>
                    </div>
                    <?php } ?>
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
    var text__loading_dos = "Cargando... <img src='<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif'/>";
    
    function generar_reporte() {
        var mes = $("#input-mes").val();
        var anio = $("#input-anio").val();
        var id_dosificacion = $("#input-id_dosificacion").val();
        $("#ajaxloading-generar_reporte").html(text__loading_dos);
        $("#ajaxbox-generar_reporte").html("");
        $.ajax({
            url: 'pages/ajax/ajax.facturas-reporte.generar_reporte.php',
            data: {mes: mes,anio: anio,id_dosificacion: id_dosificacion},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-generar_reporte").html("");
                $("#ajaxbox-generar_reporte").html(data);
            }
        });
    }
</script>