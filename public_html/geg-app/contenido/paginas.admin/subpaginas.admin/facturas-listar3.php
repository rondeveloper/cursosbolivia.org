<?php
//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 1500;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


if (isset_post('buscarr') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
} else {
    $sw_busqueda = false;
}

$estado_fact = '1';
$fecha_emision_fact = '2018-04-%'; 
echo "<h1>SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC LIMIT $start,$registros_a_mostrar</h1>";
exit;
$resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT id FROM facturas_emisiones WHERE estado='$estado_fact' AND fecha_emision LIKE '$fecha_emision_fact' ");

$total_registros = mysql_num_rows($resultado2);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Facturas emitidass</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="facturas-emitir.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> EMITIR NUEVA FACTURA</a>
        </div>
        <h3 class="page-header"> LISTADO DE FACTURAS EMITIDAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Facturas emitidas
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">Fecha</th>
                            <th class="visible-lg" style="font-size:10pt;">Nro Factura</th>
                            <th class="visible-lg" style="font-size:10pt;">Nro autorizaci&oacute;n</th>
                            <th class="visible-lg" style="font-size:10pt;">Nombre Cliente</th>
                            <th class="visible-lg" style="font-size:10pt;">Nit Cliente</th>
                            <th class="visible-lg" style="font-size:10pt;">Monto</th>
                            <th class="visible-lg" style="font-size:10pt;">Codigo de Control</th>
                            <th class="visible-lg" style="font-size:10pt;">Fecha limite de emisi&oacute;n</th>
                            <th class="visible-lg" style="font-size:10pt;">Nro Tramite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = mysql_fetch_array($resultado1)) {
                            ?>
                            <tr>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d/m/Y", strtotime($producto['fecha_emision']));
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo str_pad($producto['nro_factura'], 5, "0", STR_PAD_LEFT);
                                    ?>         
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo utf8_encode($producto['nro_autorizacion']).'&nbsp;';
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo ($producto['nombre_receptor']);
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo utf8_encode($producto['nit_receptor']).'&nbsp;';
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['total'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['codigo_de_control'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d/m/Y", strtotime($producto['fecha_limite_emision']));
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    200001154001&nbsp;
                                </td>
                            </tr>
                            
                            
                            <!-- Modal-1 -->
                        <div id="MODAL-envia-factura-<?php echo $producto['nro_factura']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">ENVIO DE FACTURA DIGITAL</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <h3 class="text-center">
                                                    FACTURA <?php echo str_pad($producto['nro_factura'], 5, "0", STR_PAD_LEFT); ?>
                                                </h3>
                                                <p><?php echo $producto['concepto']; ?></p>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="text-center" id='box-modal_envia-factura-<?php echo $producto['nro_factura']; ?>'>
                                            <h5 class="text-center">
                                                Ingrese el correo al cual se hara el envio de la factura
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <input type="text" id="correo-de-envio-<?php echo $producto['nro_factura']; ?>" class="form-control text-center" value="correo@email.com"/>
                                                </div>
                                            </div>
                                            <br/>
                                            <br/>

                                            <button class="btn btn-success" onclick="enviar_factura('<?php echo $producto['nro_factura']; ?>');">ENVIAR FACTURA</button>
                                            &nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                                        </div>
                                        <hr/>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-1 -->
                            
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <?php
                            $urlget3 = '';
                            if (isset($get[3])) {
                                $urlget3 = '/' . $get[3];
                            }
                            $urlget4 = '';
                            if (isset($get[4])) {
                                $urlget4 = '/' . $get[4];
                            }
                            $urlget5 = '';
                            if (isset($buscar)) {
                                if ($urlget3 == '') {
                                    $urlget3 = '/--';
                                }
                                if ($urlget4 == '') {
                                    $urlget4 = '/--';
                                }
                                $urlget5 = '/' . $buscar;
                            }
                            ?>

                            <li><a href="certificados-emisiones/1.adm">Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_cursos = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_cursos) {
                                $fin_paginador = $total_cursos;
                            }

                            if ($total_cursos > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="certificados-emisiones/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="certificados-emisiones/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>

<!-- anulacion de facrura -->
<script>
    function anular_factura(id) {

        if (confirm('ESTA SEGURO DE ANULAR LA FACTURA ?')) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.facturas-listar.anular_factura.php',
                data: {id: id},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#show-v-div-" + objeto + "-" + id).html(data);
                    //alert(data);
                    location.href='facturas-listar.adm';
                }
            });
        }
    }
</script>


<!-- envio de facrura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-"+id).val();
        $("#box-modal_envia-factura-"+ id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura='+id+'&email_a_enviar='+email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-"+ id).html(data);
            }
        });
    }
</script>



<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}
?>