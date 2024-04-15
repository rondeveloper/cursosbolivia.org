<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/* get */
$page = get('page');

/* post */
$concepto = post('concepto');
$fecha_inicio = post('fecha_inicio');
$fecha_fin = post('fecha_fin');
$id_administrador = post('id_administrador');


/* busqueda */
$qr_busqueda = "";
$qr_administrador = "";
$qr_fecha = " AND r.fecha_emision BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
if ($concepto != '') {
    $qr_busqueda = " AND ( r.nro_recibo='$concepto' OR r.concepto LIKE '%$concepto%' OR r.nombre_receptor LIKE '%$concepto%' )";
}
if ($id_administrador != '0') {
    $qr_administrador = " AND r.id_administrador='$id_administrador' ";
}

$registros_a_mostrar = 30;
$start = ($page - 1) * $registros_a_mostrar;

/* $id_sucursal */
$id_administrador = administrador('id');
$rqdasadm1 = query("SELECT a.id_sucursal FROM administradores a WHERE a.id='$id_administrador' ");
$rqdasadm2 = fetch($rqdasadm1);
$id_sucursal = $rqdasadm2['id_sucursal'];

/* filtro recibos propios */
$rq_rpropios = " AND r.id_administrador='$id_administrador' ";
if ($id_administrador == '10' || $id_administrador == '11') {
    $rq_rpropios = "";
}


$resultado1 = query("SELECT *,(select nombre from administradores where id=r.id_administrador limit 1)administrador FROM recibos r WHERE 1 $qr_busqueda $qr_administrador $qr_fecha $rq_rpropios ORDER BY r.nro_recibo DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT r.id FROM recibos r WHERE 1 $qr_busqueda $qr_administrador $qr_fecha $rq_rpropios ");

$total_registros = num_rows($resultado2);
$cnt = $total_registros - (($page - 1) * $registros_a_mostrar);
?>
<table class="table users-table table-condensed table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th class="visible-lg" style="font-size:10pt;">#</th>
            <th class="visible-lg" style="font-size:10pt;">Recibo</th>
            <th class="visible-lg" style="font-size:10pt;">Concepto</th>
            <th class="visible-lg" style="font-size:10pt;">Total</th>
            <th class="visible-lg" style="font-size:10pt;">A Nombre</th>
            <th class="visible-lg" style="font-size:10pt;">Fecha</th>
            <th class="visible-lg" style="font-size:10pt;">Administrador</th>
            <th class="visible-lg" style="font-size:10pt;">Estado</th>
            <th class="visible-lg" style="font-size:10pt;">Acci&oacute;n</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($producto = fetch($resultado1)) {
        ?>
            <tr>
                <td class="visible-lg"><?php echo $cnt--; ?></td>
                <td class="visible-lg">
                    <?php
                    echo str_pad($producto['nro_recibo'], 5, "0", STR_PAD_LEFT);
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo $producto['concepto'];
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo $producto['total'];
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo ($producto['nombre_receptor']);
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo date("d/m/Y", strtotime($producto['fecha_emision']));
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo $producto['administrador'];
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    if ($producto['estado'] == '1') {
                        echo "<b style='color:green;'>Emitido</b>";
                    } elseif ($producto['estado'] == '2') {
                        echo "<b style='color:red;'>Anulado</b>";
                    }
                    ?>
                </td>
                <td class="visible-lg" style="width:120px;">
                    <a onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/recibo-1.php?nro_recibo=<?php echo $producto['nro_recibo']; ?>&id_sucursal=<?php echo $id_sucursal; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;" class="btn btn-default btn-xs"><i class='fa fa-file-pdf-o'></i> Visualizar</a>
                </td>
            </tr>


            <!-- Modal-1 -->
            <div id="MODAL-envia-factura-<?php echo $producto['nro_recibo']; ?>" class="modal fade" role="dialog">
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
                                        FACTURA <?php echo str_pad($producto['nro_recibo'], 5, "0", STR_PAD_LEFT); ?>
                                    </h3>
                                    <p><?php echo $producto['concepto']; ?></p>
                                </div>
                            </div>
                            <hr />
                            <div class="text-center" id='box-modal_envia-factura-<?php echo $producto['nro_recibo']; ?>'>
                                <h5 class="text-center">
                                    Ingrese el correo al cual se hara el envio de la factura
                                </h5>
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <input type="text" id="correo-de-envio-<?php echo $producto['nro_recibo']; ?>" class="form-control text-center" value="correo@email.com" />
                                    </div>
                                </div>
                                <br />
                                <br />

                                <button class="btn btn-success" onclick="enviar_factura('<?php echo $producto['nro_recibo']; ?>');">ENVIAR FACTURA</button>
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

            <li><a onclick="listado(1);">Primero</a></li>
            <?php
            $inicio_paginador = 1;
            $fin_paginador = 15;
            $total_pages = ceil($total_registros / $registros_a_mostrar);

            if ($page > 10) {
                $inicio_paginador = $page - 5;
                $fin_paginador = $page + 10;
            }
            if ($fin_paginador > $total_pages) {
                $fin_paginador = $total_pages;
            }

            if ($total_pages > 1) {
                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                    if ($page == $i) {
                        echo '<li class="active"><a onclick="listado(' . $i . ');">' . $i . '</a></li>';
                    } else {
                        echo '<li><a onclick="listado(' . $i . ');" >' . $i . '</a></li>';
                    }
                }
            }
            ?>
            <li><a onclick="listado(<?php echo $total_pages; ?>);" >Ultimo</a></li>
        </ul>
    </div><!-- /col-md-12 -->
</div>