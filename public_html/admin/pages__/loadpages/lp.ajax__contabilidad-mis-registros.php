<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
/* datos de control de consulta */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = real_escape_string($_SERVER['HTTP_USER_AGENT']);
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

/* tipo de movimiento */
$id_tipo_movimiento = 1;

/* registros_pagina */
$registros_a_mostrar = 20;
if (isset_post('registros_pagina')) {
    $registros_a_mostrar = (int) post('registros_pagina');
}
$start = ($vista - 1) * $registros_a_mostrar;

/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];

/* registros */
$data_required = "
ct.*,
(rf.titulo)dr_referencia,
(a.nombre)dr_nombre_administrador,
(rd.id_factura)dr_id_factura,
(mp.titulo)dr_modo_pago
";

$resultado1 = query("SELECT $data_required 
FROM contabilidad ct 
INNER JOIN contabilidad_referencias rf ON ct.id_referencia=rf.id 
INNER JOIN administradores a ON a.id=ct.id_administrador 
INNER JOIN modos_de_pago mp ON mp.id=ct.id_modo_pago 
LEFT JOIN contabilidad_rel_data rd ON rd.id_contabilidad=ct.id 
WHERE ct.estado='1' AND ct.id_administrador='$id_administrador' 
ORDER BY ct.fecha DESC,ct.id DESC 
LIMIT $start,$registros_a_mostrar");

$resultado2 = query("SELECT count(*) AS total FROM contabilidad ct WHERE ct.estado='1' AND ct.id_administrador='$id_administrador' ");
$resultado2b = fetch($resultado2);
$total_registros = $resultado2b['total'];

$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="row" style="padding: 10px 0px;">
            <div class="col-md-5">
                <b style="font-size: 15pt;color: #3283ca;">
                    MIS REGISTROS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
                </b>
            </div>
            <div class="col-md-7 text-right">
                <?php include '../items/item.enlaces_contabilidad.php';?>
            </div>
        </div>
    </div>
</div>

<?php echo $mensaje; ?>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td {
        background: #ebefdd !important;
    }

    .tr_curso_cerrado td {
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }

    .tr_curso_cerrado:hover td {
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }

    .tr_curso_eliminado td {
        background: #f3e3e3 !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">MODULO DE MIS REGISTROS</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = fetch($resultado1)) {
                                $dr_id_factura = (int)$row['dr_id_factura'];
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
                                        <?php echo date("H:i", strtotime($row['fecha_registro'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $row['detalle']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['id_tipo_movimiento'] == '1' ? '<span style="color:#0fbb93;">INGRESO</span>' : '<span style="color:red;">SALIDA</span>'; ?>
                                    </td>
                                    <td class="text-right" style="padding: 7px 12px 7px 2px;">
                                        <span style="font-size: 11pt;">
                                            <?php echo $row['id_tipo_movimiento'] == '2' ? '-' : ''; ?>
                                            <?php echo $row['monto']; ?>
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
                                        if ($dr_id_factura == 0) {
                                        ?>
                                            <b class="btn btn-xs btn-default btn-block" onclick="factura('<?php echo $row['id']; ?>');">&nbsp;Emitir&nbsp;</b>
                                        <?php
                                        } else {
                                        ?>
                                            <b class="btn btn-xs btn-success btn-block" onclick="factura('<?php echo $row['id']; ?>');">&nbsp;EMITIDA&nbsp;</b>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $row['dr_nombre_administrador']; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
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

            </div>
        </div>
    </div>
</div>

<!-- factura -->
<script>
    function factura(id_contabilidad) {
        $("#TITLE-modgeneral").html('FACTURA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-ingresos.factura.php',
            data: {
                id_contabilidad: id_contabilidad
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

