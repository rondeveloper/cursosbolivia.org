<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/* acceso */
if (!acceso_cod('adm-contable-adm')) {
    echo "DENEGADO";
    exit;
}

/* vista */
$vista = 1;

/* tipo de movimiento */
$id_tipo_movimiento = 1;

/* registros_pagina */
$registros_a_mostrar = 2000;
$start = ($vista - 1) * $registros_a_mostrar;

/* busqueda */
$bus_id_administrador = post('id_administrador');
$bus_id_referencia = post('id_referencia');
$bus_fecha_inicio = post('fecha_inicio');
$bus_fecha_fin = post('fecha_fin');
$bus_id_modo_pago = post('id_modo_pago');
$bus_id_sucursal = post('id_sucursal');

$qr_adminsitrador = '';
$qr_referencia = '';
$qr_modo_pago = '';
$qr_sucursal = '';
$qr_fechas = " AND DATE(ct.fecha_registro)>='$bus_fecha_inicio' AND DATE(ct.fecha_registro)<='$bus_fecha_fin' ";

if($bus_id_administrador!='0'){
    $qr_adminsitrador = " AND ct.id_administrador='$bus_id_administrador' ";
}
if($bus_id_referencia!='0'){
    $qr_referencia = " AND ct.id_referencia='$bus_id_referencia' ";
}
if($bus_id_modo_pago!='0'){
    $qr_modo_pago = " AND ct.id_modo_pago='$bus_id_modo_pago' ";
}
if($bus_id_sucursal!='0'){
    $qr_sucursal = " AND ct.id_sucursal='$bus_id_sucursal' ";
}


/* registros */
$data_required = "
ct.*,
(rf.titulo)dr_referencia,
(a.nombre)dr_nombre_administrador,
(rd.id_factura)dr_id_factura,
(rd.id_participante)dr_id_participante,
(mp.titulo)dr_modo_pago,
(s.nombre)dr_nombre_sucursal
";

$resultado1 = query("SELECT $data_required 
FROM contabilidad ct 
INNER JOIN contabilidad_referencias rf ON ct.id_referencia=rf.id 
INNER JOIN administradores a ON a.id=ct.id_administrador 
INNER JOIN modos_de_pago mp ON mp.id=ct.id_modo_pago 
LEFT JOIN contabilidad_rel_data rd ON rd.id_contabilidad=ct.id 
LEFT JOIN sucursales s ON ct.id_sucursal=s.id 
WHERE ct.estado='1' $qr_adminsitrador $qr_referencia $qr_fechas $qr_modo_pago $qr_sucursal 
ORDER BY ct.fecha DESC,ct.id DESC 
LIMIT $start,$registros_a_mostrar");

$resultado2 = query("SELECT count(*) AS total FROM contabilidad ct WHERE ct.estado='1' $qr_adminsitrador $qr_referencia $qr_fechas $qr_modo_pago $qr_sucursal ");
$resultado2b = fetch($resultado2);
$total_registros = $resultado2b['total'];

$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);

/* totales */
$total_ingresos = 0;
$total_salidas = 0;
?>
<table class="table users-table table-condensed table-hover table-striped table-bordered table-responsive">
    <thead>
        <tr>
            <th style="font-size:10pt;">#</th>
            <th style="font-size:10pt;">ID</th>
            <th style="font-size:10pt;">Fecha</th>
            <th style="font-size:10pt;">Hora</th>
            <th style="font-size:10pt;">Detalle</th>
            <th style="font-size:10pt;">I/E</th>
            <th style="font-size:10pt;" colspan="2">Monto</th>
            <th style="font-size:10pt;">Referencia</th>
            <th style="font-size:10pt;">Factura</th>
            <th style="font-size:10pt;">Administrador</th>
            <th style="font-size:10pt;">Sucursal</th>
            <?php if(false){ ?>
            <th style="font-size:10pt;">Acci&oacute;nes</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = fetch($resultado1)) {
            $dr_id_factura = (int)$row['dr_id_factura'];
            $dr_id_participante = (int)$row['dr_id_participante'];

            if($row['id_tipo_movimiento']=='1'){
                $total_ingresos += $row['monto'];
            }else{
                $total_salidas += $row['monto'];
            }
        ?>
            <tr>
                <td>
                    <?php echo $cnt--; ?>
                </td>
                <td>
                    <span class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['id']; ?></span>
                </td>
                <td>
                    <?php echo date("d/m/y", strtotime($row['fecha_registro'])); ?>
                </td>
                <td>
                    <?php echo date("H:i:s", strtotime($row['fecha_registro'])); ?>
                </td>
                <td>
                    <?php echo $row['detalle']; ?>
                    <?php 
                    if($dr_id_participante>0){
                        ?>
                        <span class="pull-right">
                            &nbsp;
                            &nbsp;
                            <a class="btn btn-xs btn-default" onclick="pago_participante('<?php echo $dr_id_participante; ?>');">
                                <i class="fa fa-info"></i> Info Pago
                            </a>
                            &nbsp;
                            &nbsp;
                            <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $dr_id_participante; ?>');">
                                <i class="fa fa-list" style="color:#8f8f8f;"></i>
                            </b>
                            &nbsp;
                            &nbsp;
                        </span>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <?php echo $row['id_tipo_movimiento'] == '1' ? '<span style="color:#0fbb93;">INGRESO</span>' : '<span style="color:red;">SALIDA</span>'; ?>
                </td>
                <td class="text-right" style="padding: 7px 12px 7px 2px;">
                    <span style="font-size: 11pt;">
                        <?php echo $row['id_tipo_movimiento'] == '2' ? '-' : ''; ?>
                        <?php echo number_format($row['monto'],2); ?>
                    </span>
                </td>
                <td>
                    <span class="label label-default" style="font-size: 7pt;background: #84ce84;"><?php echo $row['dr_modo_pago']; ?></span>
                </td>
                <td>
                    <span class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['dr_referencia']; ?></span>
                </td>
                <td id="td-idcont-<?php echo $row['id']; ?>">
                    <?php
                    if ($dr_id_factura != '0') {
                    ?>
                        <b class="btn btn-xs btn-success btn-block" onclick="factura('<?php echo $row['id']; ?>');">Emitida</b>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php echo $row['dr_nombre_administrador']; ?>
                </td>
                <td>
                    <?php echo $row['dr_nombre_sucursal']; ?>
                </td>
                <?php if(false){ ?>
                <td id="eliminar_mov_contabilidad__<?php echo $row['id']; ?>">
                    <b class="btn btn-xs btn-block btn-danger" onclick="eliminar_mov_contabilidad('<?php echo $row['id']; ?>');">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </b>
                </td>
                <?php } ?>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="col-md-6">
    <table class="table table-striped table-bordered">
        <tr>
            <td>
                <b>INGRESOS:</b>
            </td>
            <td class="text-right">
                <?php echo number_format($total_ingresos,2); ?> BS
            </td>
        </tr>
        <tr>
            <td>
                <b>SALIDAS:</b>
            </td>
            <td class="text-right">
                <?php echo number_format($total_salidas,2); ?> BS
            </td>
        </tr>
        <tr>
            <td colspan="2">
            </td>
        </tr>
        <tr>
            <td>
                <b>TOTAL:</b>
            </td>
            <td class="text-right">
                <?php echo number_format($total_ingresos - $total_salidas,2); ?> BS
            </td>
        </tr>
    </table>
</div>

<?php if (false) { ?>
    <div class="row">
        <div class="col-md-12">
            <ul class="pagination">
                <?php
                $urlget3 = '';

                /* get 3 */
                if (isset($get[3])) {
                    $urlget3 .= '/' . $get[3];
                } else {
                    $urlget3 .= '/' . $post_search_data;
                }
                /* get 4 */
                if (isset($get[4])) {
                    $urlget3 .= '/' . $get[4];
                }
                /* get 5 */
                if (isset($get[5])) {
                    $urlget3 .= '/' . $get[5];
                }
                ?>

                <li><a <?php echo loadpage('blog-listar/1' . $urlget3); ?>>Primero</a></li>
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
                            echo '<li class="active"><a ' . loadpage('blog-listar/' . $i . $urlget3) . '>' . $i . '</a></li>';
                        } else {
                            echo '<li><a ' . loadpage('blog-listar/' . $i . $urlget3) . '>' . $i . '</a></li>';
                        }
                    }
                }
                ?>
                <li><a <?php echo loadpage('blog-listar/' . $total_cursos . $urlget3); ?>>Ultimo</a></li>
            </ul>
        </div><!-- /col-md-12 -->
    </div>
<?php } ?>


<script>
    function historial_participante(id_participante) {
        $("#TITLE-modgeneral").html('HISTORIAL DE PARTICIPANTE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.historial_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
    function pago_participante(id_participante) {
        $("#TITLE-modgeneral").html('INFORMACI&Oacute;N DE PAGO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.pago_participante.php',
            data: {id_participante: id_participante, sw_solo_info:1},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data+'<style>@media (min-width: 768px){.modal-dialog {width: 800px !important;}}</style>');
            }
        });
    }
</script>

