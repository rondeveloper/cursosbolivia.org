<?php

/* datos de configuracion */
$sw_facturacion_modulos = $__CONFIG_MANAGER->getSw('sw_facturacion_modulos');

/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

/* dosificacion */
$id_dosificacion = 0;
if (isset($get[3])) {
    $id_dosificacion = (int)$get[3];
}

/* ANULACION DE FACTURA */
if(isset_post('anular-factura')){
    $id_factura = post('id_factura');
    $motivo_anulacion = post('motivo_anulacion');
    $id_administrador = administrador('id');
    query("UPDATE facturas_emisiones SET estado='2' WHERE id='$id_factura' ");
    query("INSERT INTO facturas_anulaciones (id_factura,motivo_anulacion,fecha,id_administrador) VALUES ('$id_factura','$motivo_anulacion',NOW(),'$id_administrador') ");
    logcursos('Anulacion de factura [ID FACT:'.$id_factura.']', 'anulacion-factura', 'factura', $id_factura);
    $mensaje = '<div class="alert alert-success">
    <strong>EXITO</strong> la factura fue anulada correctamente.
  </div>';
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('input-buscador')) {
    $busqueda = post('input-buscador');
    $qr_busqueda = " AND nro_factura='$busqueda' OR concepto LIKE '%$busqueda%' OR total LIKE '$busqueda' OR nombre_receptor LIKE '%$busqueda%' OR nit_receptor LIKE '%$busqueda%' ";
    $vista = 1;
}

$registros_a_mostrar = 150;
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

/* qr dosificacion */
$qr_dosificacion = "";
if($id_dosificacion>0){
    $qr_dosificacion = " AND id_dosificacion='$id_dosificacion' ";
}

$resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador,(select descripcion from facturas_actividades where id=f.id_actividad limit 1)actividad FROM facturas_emisiones f WHERE 1 $qr_busqueda $qr_dosificacion ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT id FROM facturas_emisiones WHERE 1 $qr_busqueda $qr_dosificacion ");

$total_registros = num_rows($resultado2);
$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
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
        <?php echo $mensaje; ?>


        <div class="row">
            <div class="col-md-8">
                <a class="btn btn-sm btn-info <?= ($id_dosificacion==0)?'active':'' ?>" title="<?php echo $rqac2['id']; ?>" href="facturas-listar/1/0.adm">
                    <b>MOSTRAR TODOS</b>
                    <br>
                    Todas las dosificaciones
                </a>
                <?php
                $rqac1 = query("SELECT d.*,(e.nombre)dr_nombre_emisor,(a.actividad)dr_actividad FROM facturas_dosificaciones d LEFT JOIN facturas_datos_emisor e ON e.id=d.id_datos_emisor LEFT JOIN facturas_actividades a ON a.id=d.id_actividad WHERE d.estado IN (1,2) AND d.fecha_limite_emision>=CURDATE() ORDER BY d.id ASC ");
                while($rqac2 = fetch($rqac1)){
                ?>
                    <a class="btn btn-sm btn-info <?= ($id_dosificacion==$rqac2['id'])?'active':'' ?>" href="facturas-listar/1/<?php echo $rqac2['id']; ?>.adm">
                        <b><?php echo $rqac2['dr_nombre_emisor']; ?></b>
                        <br>
                        <?php echo $rqac2['dr_actividad']; ?>
                    </a>
                <?php
                }
                ?>
            </div>
            <div class="col-md-4">
                <form action="" method="post">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                        <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Nro de factura / Concepto / Detalle / Total / Nombre / Nit ..." />
                    </div>
                </form>
            </div>
        </div>
    
    </div>
</div>
<br>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="font-size:10pt;">#</th>
            <th style="font-size:10pt;">Factura</th>
            <th style="font-size:10pt;">Detalle</th>
            <th style="font-size:10pt;">Monto</th>
            <th style="font-size:10pt;">Cliente</th>
            <th style="font-size:10pt;">Comprobante</th>
            <th style="font-size:10pt;">Estado</th>
            <th style="font-size:10pt;">Acci&oacute;n</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($producto = fetch($resultado1)) {
            if(!$sw_facturacion_modulos){ break; }
        ?>
            <tr>
                <td><?php echo $cnt--; ?></td>
                <td class="text-center">
                    <b style="font-size: 14pt;color: #19a7b5;"><?php echo str_pad($producto['nro_factura'], 5, "0", STR_PAD_LEFT); ?></b>
                    <br>
                    <br>
                    <i style="font-size: 8pt;color:gray;">Emitido en:</i>
                    <br>
                    <?php echo my_date_factura($producto['fecha_emision']); ?>
                </td>
                <td>
                    <?php echo $producto['concepto']; ?>
                    <br>
                    <br>
                    <b style="background: #57c3e6;padding: 2px 10px;color: white;font-size: 7pt;"><?php echo $producto['actividad']; ?></b>
                    &nbsp;&nbsp;&nbsp;
                    <b><?php echo $producto['nro_autorizacion']; ?></b>
                    &nbsp;&nbsp;&nbsp;
                    <i>FLE: <?php echo $producto['fecha_limite_emision']; ?></i>
                    <br>
                    <br>
                    <a href='<?php echo $dominio; ?>F/<?php echo $producto['id']; ?>/' style="font-size: 11pt;color: #2a7fbb;"><?php echo $dominio; ?>F/<?php echo $producto['id']; ?>/</a>
                </td>
                <td class="text-center">
                    <span style="font-size: 14pt;color: #156dab;"><?php echo $producto['total']; ?></span>
                </td>
                <td>
                    <b><?php echo $producto['nit_receptor']; ?></b>
                    <br>
                    <?php echo $producto['nombre_receptor']; ?>
                    <br>
                </td>
                <td>
                <?php 
                    if($producto['archivo_comprobante'] != null){ 
                        $img_comprobante = $dominio.'contenido/archivos/pdf_comprobante/'.$producto['archivo_comprobante'];
                        ?>
                        <a onclick="window.open('<?= $img_comprobante ?>','popup','width=400,height=200,top=300,left=500')" target="_blank" stylesheet="curos-bo-img_comprobante_factura">
                        <img src="<?= $img_comprobante ?>" alt="comprobante"/>
                        </a>
                        <?php 
                    } else{
                        echo '<div style="text-align: center;
                        line-height: 89px">Sin comprobante</div>';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($producto['estado'] == '1') {
                        echo "<b style='color:green;'>Emitido</b>";
                    } else {
                        echo "<b style='color: red;border: 1px solid red;padding: 0px 5px;'>Anulado</b>";
                        $rqdan1 = query("SELECT fa.motivo_anulacion,fa.fecha,(a.nombre)dr_nombre_administrador FROM facturas_anulaciones fa INNER JOIN administradores a ON a.id=fa.id_administrador WHERE fa.id_factura='".$producto['id']."' ORDER BY fa.id DESC limit 1 ");
                        if(num_rows($rqdan1)>0){
                            $rqdan2 = fetch($rqdan1);
                            echo "<div style='background: white;border: 1px solid #c1bebe;padding: 5px;margin: 10px 0px;font-size: 7pt;width: 120px;'>";
                            echo "<b>Motivo:</b><br>".$rqdan2['motivo_anulacion'];
                            echo "<br><b>En fecha:</b><br>".$rqdan2['fecha'];
                            echo "<br><b>Administrador:</b><br>".$rqdan2['dr_nombre_administrador'];
                            echo "</div>";
                        }
                    }
                    echo "<br><br><i style='font-size: 8pt;color:gray;'>Emitido por:</i><br>";
                    if ($producto['id_administrador'] == '90') {
                        echo "<span style='font-size:7pt;'>WebService Infosicoes</span>";
                    }elseif ($producto['id_administrador'] == '99') {
                        echo "<span style='font-size:7pt;'>El participante <br>(modulo auto-factura)</span>";
                    } else {
                        echo "<span style='font-size:7pt;'>" . $producto['administrador'] . "</span>";
                    }
                    ?>
                </td>
                <td style="width:120px;">
                    <a class="btn btn-xs btn-default btn-block" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1.php?id_factura=<?php echo $producto['id']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;"><i class='fa fa-file-pdf-o'></i> Visualizar</a>
                    <a class="btn btn-xs btn-default btn-block" style="cursor:pointer;" data-toggle="modal" data-target="#MODAL-envia-factura-<?php echo $producto['id']; ?>"><i class='fa fa-send-o'></i> Enviar</a>
                    <?php
                    if ($producto['estado'] == '1') {
                    ?>
                        <br>
                        <a class="btn btn-xs btn-danger btn-block" onclick="anular_factura('<?php echo $producto['id']; ?>');" style="cursor:pointer;"><i class='fa fa-times-circle'></i> Anular</a>
                    <?php
                    }
                    ?>
                </td>
            </tr>


            <!-- Modal-1 -->
            <div id="MODAL-envia-factura-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
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
                            <hr />
                            <div class="text-center" id='box-modal_envia-factura-<?php echo $producto['id']; ?>'>
                                <h5 class="text-center">
                                    Ingrese el correo al cual se hara el envio de la factura
                                </h5>
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <input type="text" id="correo-de-envio-<?php echo $producto['id']; ?>" class="form-control text-center" value="correo@email.com" />
                                    </div>
                                </div>
                                <br />
                                <br />

                                <button class="btn btn-success" onclick="enviar_factura('<?php echo $producto['id']; ?>');">ENVIAR FACTURA</button>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                            </div>
                            <hr />
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

            <li><a href="facturas-listar/1.adm">Primero</a></li>
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
                        echo '<li><a href="facturas-listar/' . $i . '.adm">' . $i . '</a></li>';
                    }
                }
            }
            ?>
            <li><a href="facturas-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
        </ul>
    </div><!-- /col-md-12 -->
</div>



<!-- factura -->
<script>
    function anular_factura(id_factura) {
        $("#TITLE-modgeneral").html('ANULACI&Oacute;N DE FACTURA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.facturas-listar.anular_factura.php',
            data: {id_factura: id_factura},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<!-- envio de facrura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_factura.php?id_factura=' + id + '&email_a_enviar=' + email,
            data: {
                id: id
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-" + id).html(data);
            }
        });
    }
</script>



<?php

function my_date_factura($dat)
{
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]]." ".$ar1[0];
    }
}
?>

