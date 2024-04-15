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
$bus_fecha_inicio = post('fecha_inicio');
$bus_fecha_fin = post('fecha_fin');
$bus_id_sucursal = post('id_sucursal');

$bus_id_modo_pago = 0;

$qr_adminsitrador = '';
$qr_sucursal = '';
$qr_fechas = " AND DATE(c.fecha)>='$bus_fecha_inicio' AND DATE(c.fecha)<='$bus_fecha_fin' ";

if($bus_id_administrador!='0'){
    $qr_adminsitrador = " AND c.id_administrador='$bus_id_administrador' ";
}
if($bus_id_sucursal!='0'){
    $qr_sucursal = " AND c.id_sucursal='$bus_id_sucursal' ";
}


/* registros */
$data_required = "
c.*,
(a.nombre)dr_nombre_administrador,
(s.nombre)dr_nombre_sucursal
";

$resultado1 = query("SELECT $data_required 
FROM caja c 
INNER JOIN administradores a ON a.id=c.id_administrador 
LEFT JOIN sucursales s ON c.id_sucursal=s.id 
WHERE c.estado IN (1,2) $qr_adminsitrador $qr_sucursal $qr_fechas 
ORDER BY c.fecha DESC,c.id DESC 
LIMIT $start,$registros_a_mostrar ");

$resultado2 = query("SELECT count(*) AS total FROM caja c WHERE c.estado IN (1,2) $qr_adminsitrador $qr_sucursal $qr_fechas ");
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
            <th style="font-size:10pt;">Sucursal</th>
            <th style="font-size:10pt;">Administrador</th>
            <th style="font-size:10pt;">Monto apertura</th>
            <th style="font-size:10pt;">Monto cierre</th>
            <?php if(false){ ?>
            <th style="font-size:10pt;">Acci&oacute;nes</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = fetch($resultado1)) {
        ?>
            <tr>
                <td>
                    <?php echo $cnt--; ?>
                </td>
                <td>
                    <span class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['id']; ?></span>
                </td>
                <td>
                    <?php echo date("d/m/y", strtotime($row['fecha'])); ?>
                </td>
                <td>
                    <?php echo $row['dr_nombre_sucursal']; ?>
                </td>
                <td>
                    <?php echo $row['dr_nombre_administrador']; ?>
                </td>
                <td>
                    <?php echo $row['monto_apertura']; ?>
                </td>
                <td>
                    <?php echo $row['monto_cierre']; ?>
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

