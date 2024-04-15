<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

if (!acceso_cod('adm-estadisticas')) {
    echo "DENEGADO";
    exit;
}

/* data post */
$fecha_inicio = post('fecha_inicio');
$fecha_fin = post('fecha_fin');
$id_departamento = (int) post('id_departamento');
$id_ciudad = (int) post('id_ciudad');
$id_lugar = (int) post('id_lugar');
$id_docente = (int) post('id_docente');
$pago = post('pago');
$modalidad = post('modalidad');

//$numero = (int) post('numero');
$numero = 0;
//$nombre = trim(post('nombre'));
$nombre = '';

/* data post export */
$postdata_exort = base64_encode(json_encode($_POST));

/* vista */
$vista = 1;
$registros_a_mostrar = 1500;
$start = ($vista - 1) * $registros_a_mostrar;

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$rq_ciudad_departamento = "";
$qr_nombre = ' 1 ';
$rq_docente = "";
$rq_lugar = "";
$rq_nombre = "";
$rq_fechas = "";
$rq_pago = "";
$rq_modalidad = "";

    $rq_fechas = " AND r.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
    if ($id_departamento !== 0) {
        $rq_ciudad_departamento = " AND d.id='$id_departamento'  ";
    }
    if ($id_ciudad !== 0) {
        $rq_ciudad_departamento = " AND cd.id='$id_ciudad'  ";
    }
    if ($id_lugar !== 0) {
        $rq_lugar = " AND l.id='$id_lugar'  ";
    }
    if ($nombre !== '') {
        $rq_nombre = " AND c.titulo LIKE '%$nombre%'  ";
    }
    if ($pago == 'con') {
        $rq_nombre = " AND r.sw_pago_enviado='1'  ";
    }
    if ($pago == 'sin') {
        $rq_pago = " AND r.sw_pago_enviado='0'  ";
    }
    if ($modalidad == 'virtual') {
        $rq_modalidad = " AND c.id_modalidad IN (2,3)  ";
    }
    if ($modalidad == 'presencial') {
        $rq_modalidad = " AND c.id_modalidad='1' ";
    }



/* data required */
$data_required = "
*,
(c.id)dr_id_curso,
(c.id_modalidad)dr_modalidad_curso,
(p.id)dr_id_participante,
(r.fecha_registro)dr_fecha_registro_participante,
(CONCAT(p.nombres,' ',p.apellidos))dr_nombre_participante,
(cd.nombre)dr_nombre_ciudad,
(d.nombre)dr_nombre_departamento,
(r.monto_deposito)dr_monto_pago
";
/* query */
$query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
LEFT JOIN ciudades cd ON c.id_ciudad=cd.id 
LEFT JOIN departamentos d ON cd.id_departamento=d.id 
WHERE c.estado IN (0,1,2) 
$rq_fechas $rq_ciudad_departamento $rq_docente $rq_nombre $rq_lugar $rq_pago $rq_modalidad 
ORDER BY r.fecha_registro ASC LIMIT $start,$registros_a_mostrar
";
$resultado1 = query($query);
$total_registros = mysql_num_rows($resultado1);

$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th style="font-size:10pt;">#</th>
                <th style="font-size:10pt;">Registro</th>
                <th style="font-size:10pt;">Nombre</th>
                <th style="font-size:10pt;">Curso</th>
                <th style="font-size:10pt;">Modalidad</th>
                <th style="font-size:10pt;">Dep/Ciudad</th>
                <th style="font-size:10pt;" colspan="2">Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total__total_pagos = 0;
            while ($producto = mysql_fetch_array($resultado1)) {
                ?>
                <tr>
                    <td>
                        <?php echo $cnt--; ?>
                    </td>
                    <td>
                        <?php echo date("d/M/Y", strtotime($producto['dr_fecha_registro_participante'])); ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_participante']; ?>
                        <b class="btn btn-default btn-xs pull-right" onclick="historial_participante('<?php echo $producto['dr_id_participante']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                        </b>
                    </td>
                    <td>
                        <?php echo $producto['titulo']; ?> <b class="pull-right">[ID: <?php echo $producto['dr_id_curso']; ?>]</b>
                    </td>
                    <td>
                        <?php echo $producto['dr_modalidad_curso']=='1' ? 'PRESENCIAL' : 'VIRTUAL'; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_modalidad_curso']=='1' ? $producto['dr_nombre_departamento'].'/'.$producto['dr_nombre_ciudad'] : 'Nacional'; ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_monto_pago']); ?>
                    </td>
                    <td>
                        <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $producto['dr_id_participante']; ?>');" class="btn btn-xs btn-default">
                            <i class="fa fa-info"></i> Pago
                        </a>
                    </td>
                    
                </tr>
                <?php
                $total__total_pagos += (int) $producto['dr_monto_pago'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    -
                </td>
                <td colspan="5">
                    Total participantes: <?php echo $total_registros; ?>
                </td>
                <td colspan="2">
                    <?php echo format_monto($total__total_pagos); ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<hr/>

<!--<p class="text-center">Puede exportar los resultados del reporte en los siguientes formatos:</p>
<div class="panel-footer text-center">
    <button class="btn btn-default" onclick="export_reporte('impresion');">IMPRIMIR</button>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button class="btn btn-success active" onclick="export_reporte('excel');">EXCEL</button>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button class="btn btn-info active" onclick="export_reporte('word');">WORD</button>
</div>
<hr/>
<script>
    function export_reporte(formato) {
        window.open('http://cursos.bo/contenido/paginas.admin/ajax/ajax.impresion.estadisticas-cursos.exportar-reporte.php?' + formato + '=true&data=<?php echo $postdata_exort; ?>', 'popup', 'width=700,height=500');
    }
</script>-->

<?php

function format_monto($dat) {
    $monto = (int) $dat;
    if ($monto == 0) {
        return "<span style='color:#DDD;'>$monto</span>";
    } else {
        return $monto;
    }
}
