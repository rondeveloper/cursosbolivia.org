<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/* data */
$id_curso = post('id_curso');
?>


<?php
/* datos en forma detalle individual */
$query = "
SELECT count(*) AS total FROM 
cursos_participantes p 
WHERE p.id_curso='$id_curso' AND p.estado=1 AND p.sw_pago=1 AND p.id_modo_pago<>10 
";
$resultado1 = query($query);
$resultado2 = fetch($resultado1);
$total_conpago = $resultado2['total'];

$query = "
SELECT count(*) AS total FROM 
cursos_participantes p 
WHERE p.id_curso='$id_curso' AND p.estado=1 AND p.sw_pago=1 AND p.id_modo_pago=10 
";
$resultado1 = query($query);
$resultado2 = fetch($resultado1);
$total_gratuitos = $resultado2['total'];

$query = "
SELECT count(*) AS total FROM 
cursos_participantes p 
WHERE p.id_curso='$id_curso' AND p.estado=1 AND p.sw_pago=0 
";
$resultado1 = query($query);
$resultado2 = fetch($resultado1);
$total_sinpago = $resultado2['total'];
?>
<div class="panel panel-info">
    <div class="panel-heading">REGISTROS POR ESTADO DE PAGO</div>
    <div class="panel-body">
        <table class="table table-hover table-bordered table-stripedtable-responsive">
            <thead>
                <tr>
                    <th style="font-size:10pt;">Estado de pago</th>
                    <th style="font-size:10pt;">Cantidad registrados</th>
                </tr>
            </thead>
            <tr>
                <td>
                    CON PAGO
                </td>
                <td>
                    <?php echo $total_conpago; ?> participantes
                </td>
            </tr>
            <tr>
                <td>
                    SIN PAGO
                </td>
                <td>
                    <?php echo $total_sinpago; ?> participantes
                </td>
            </tr>
            <tr>
                <td>
                    GRATUITO
                </td>
                <td>
                    <?php echo $total_gratuitos; ?> participantes
                </td>
            </tr>
            <tr>
                <td colspan="2">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Total:</b>
                </td>
                <td>
                    <b><?php echo ($total_conpago+$total_gratuitos+$total_sinpago); ?> participantes</b>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
/* datos en forma detalle individual */
$data_required = "
(count(*))dr_cantidad,
(SUM(r.monto_deposito))dr_total,
(p.id)dr_id_participante,
(mp.titulo)dr_modo_pago,
(r.fecha_registro)dr_fecha_registro_participante,
(r.monto_deposito)dr_monto_pago
";
$query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN modos_de_pago mp ON p.id_modo_pago=mp.id 
WHERE p.id_curso='$id_curso' AND p.estado=1 AND p.sw_pago=1
GROUP BY mp.id ORDER BY dr_cantidad DESC
";
$resultado1 = query($query);
?>
<div class="panel panel-info">
    <div class="panel-heading">REGISTROS POR MODALIDAD DE PAGO</div>
    <div class="panel-body">
        <table class="table table-hover table-bordered table-stripedtable-responsive">
            <thead>
                <tr>
                    <th style="font-size:10pt;">Modo de pago</th>
                    <th style="font-size:10pt;">Cantidad registrados</th>
                    <th style="font-size:10pt;">Monto de pago</th>
                </tr>
            </thead>
            <?php
            $total__total_pagos = 0;
            $total__total_registrados = 0;
            while ($producto = fetch($resultado1)) {
            ?>
                <tr>
                    <td>
                        <?php echo $producto['dr_modo_pago']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_cantidad']; ?> participantes
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total']); ?> BS
                    </td>
                </tr>
            <?php
                $total__total_pagos += (int) $producto['dr_total'];
                $total__total_registrados += (int) $producto['dr_cantidad'];
            }
            ?>
            <tr>
                <td colspan="3">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Total:</b>
                </td>
                <td>
                    <b><?php echo format_monto($total__total_registrados); ?> participantes</b>
                </td>
                <td>
                    <b><?php echo format_monto($total__total_pagos); ?> BS</b>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php

function format_monto($dat)
{
    $monto = (int) $dat;
    if ($monto == 0) {
        return "<span style='color:#DDD;'>$monto</span>";
    } else {
        return $monto;
    }
}
?>

<?php
/* datos en forma detalle individual */
$data_required = "
(count(*))dr_cantidad,
(SUM(r.monto_deposito))dr_total,
(p.id)dr_id_participante,
(d.nombre)dr_departamento,
(r.fecha_registro)dr_fecha_registro_participante,
(r.monto_deposito)dr_monto_pago
";
$query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN departamentos d ON p.id_departamento=d.id 
WHERE p.id_curso='$id_curso' AND p.estado=1 AND p.sw_pago=1 
GROUP BY d.id ORDER BY dr_cantidad DESC
";
$resultado1 = query($query);
?>
<div class="panel panel-info">
    <div class="panel-heading">REGISTROS POR DEPARTAMENTO</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th style="font-size:10pt;">Departamento</th>
                        <th style="font-size:10pt;">Cantidad registrados</th>
                        <th style="font-size:10pt;">Monto de pago</th>
                    </tr>
                </thead>
                <?php
                $total__total_pagos = 0;
                $total__total_registrados = 0;
                while ($producto = fetch($resultado1)) {
                ?>
                    <tr>
                        <td>
                            <?php echo strtoupper($producto['dr_departamento']); ?>
                        </td>
                        <td>
                            <?php echo $producto['dr_cantidad']; ?> participantes
                        </td>
                        <td>
                            <?php echo format_monto($producto['dr_total']); ?> BS
                        </td>
                    </tr>
                <?php
                    $total__total_pagos += (int) $producto['dr_total'];
                    $total__total_registrados += (int) $producto['dr_cantidad'];
                }
                ?>
                <tr>
                    <td colspan="3">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Total:</b>
                    </td>
                    <td>
                        <b><?php echo format_monto($total__total_registrados); ?> participantes</b>
                    </td>
                    <td>
                        <b><?php echo format_monto($total__total_pagos); ?> BS</b>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

