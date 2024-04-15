<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/* id curso */
$id_curso = get('id_curso');

/* data post */
$fecha_inicio = post('fecha_inicio');
$fecha_fin = post('fecha_fin');
$id_departamento = (int) post('id_departamento');
$id_docente = (int) post('id_docente');
$pago = post('pago');
$modalidad = post('modalidad');
$sw_gratuitos = post('gratuitos');

/* busqueda */
$rq_departamento_participante = "";
$rq_docente = "";
$rq_nombre = "";
$rq_fechas = "";
$rq_pago = "";
$rq_modalidad = "";
$qr_gratuitos = "";
$qr_curso_especifico = "";

/* filtros */
$rq_fechas = " AND DATE(r.fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
if ($id_departamento !== 0) {
    $rq_departamento_participante = " AND p.id_departamento='$id_departamento'  ";
    $rq_departamento_curso = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento')  ";
}
if ($nombre !== '') {
    $rq_nombre = " AND c.titulo LIKE '%$nombre%'  ";
}
if ($pago == 'con') {
    $rq_pago = " AND r.sw_pago_enviado='1'  ";
}
if ($pago == 'sin') {
    $rq_pago = " AND r.sw_pago_enviado='0'  ";
}
if ($modalidad == 'virtual') {
    $rq_modalidad = " AND c.id_modalidad IN (2,3,4,5,6,7)  ";
}
if ($modalidad == 'presencial') {
    $rq_modalidad = " AND c.id_modalidad='1' ";
}
if ($sw_gratuitos == '0') {
    $qr_gratuitos = " AND r.monto_deposito<>'0' ";
}
if ($id_curso != '0') {
    $qr_curso_especifico = " AND c.id='$id_curso' ";
    $sw_grupo = false;
}

/* datos en forma detalle individual */
$data_required = "
(count(*))dr_cantidad,
(SUM(r.monto_deposito))dr_total,
(c.id)dr_id_curso,
(c.id_modalidad)dr_modalidad_curso,
(p.id)dr_id_participante,
(mp.titulo)dr_modo_pago,
(c.titulo)dr_titulo_curso,
(r.fecha_registro)dr_fecha_registro_participante,
(r.monto_deposito)dr_monto_pago
";
$query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
INNER JOIN modos_de_pago mp ON p.id_modo_pago=mp.id 
WHERE c.estado IN (0,1,2) 
AND c.id='$id_curso' 
$rq_fechas $rq_departamento_participante $rq_docente $rq_nombre $rq_pago $rq_modalidad $qr_gratuitos $qr_curso_especifico 
GROUP BY mp.id ORDER BY dr_cantidad DESC
";
$resultado1 = query($query);
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped">
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

<?php

function format_monto($dat){
    $monto = (int) $dat;
    if ($monto == 0) {
        return "<span style='color:#DDD;'>$monto</span>";
    } else {
        return $monto;
    }
}
?>