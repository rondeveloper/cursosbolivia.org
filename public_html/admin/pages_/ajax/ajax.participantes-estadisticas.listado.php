<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
$id_docente = (int) post('id_docente');
$pago = post('pago');
$modalidad = post('modalidad');
$sw_gratuitos = post('gratuitos');
$id_curso = post('id_curso');

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
$rq_departamento_participante = "";
$rq_departamento_curso = "";
$qr_nombre = ' 1 ';
$rq_docente = "";
$rq_nombre = "";
$rq_fechas = "";
$rq_pago = "";
$rq_modalidad = "";
$qr_gratuitos = "";
$qr_curso_especifico = "";

/* mdo */
$sw_grupo = false;
if(post('modo') == 'grupo'){
    $sw_grupo = true;
}

    $rq_fechas = " AND DATE(r.fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
    if ($id_departamento !== 0) {
        $rq_departamento_participante = " AND p.id_departamento='$id_departamento'  ";
        $rq_departamento_curso = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento')  ";
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


    /* orden */
    $qr_orden = ' c.fecha ASC ';
    if (post('orden') == 'nombre') {
        $qr_orden = " c.titulo ASC";
    }


/* query */
if ($sw_grupo) {
    /* datos en forma de grupo */
    $data_required = "
*,
(count(*))dr_cantidad,
(c.id)dr_id_curso,
(c.id_modalidad)dr_modalidad_curso,
(p.id)dr_id_participante,
(d.nombre)dr_departamento_curso,
(r.fecha_registro)dr_fecha_registro_participante,
(CONCAT(p.nombres,' ',p.apellidos))dr_nombre_participante,
(SUM(r.monto_deposito))dr_monto_pago
";
    $query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
LEFT JOIN ciudades cd ON c.id_ciudad=cd.id 
LEFT JOIN departamentos d ON cd.id_departamento=d.id 
WHERE p.estado=1 AND c.estado IN (0,1,2) 
$rq_fechas $rq_departamento_curso $rq_docente $rq_nombre $rq_pago $rq_modalidad $qr_gratuitos 
GROUP BY c.id LIMIT $start,$registros_a_mostrar
";
}else{
    /* datos en forma detalle individual */
    $data_required = "
*,
(c.id)dr_id_curso,
(c.id_modalidad)dr_modalidad_curso,
(p.id)dr_id_participante,
(r.fecha_registro)dr_fecha_registro_participante,
(CONCAT(p.nombres,' ',p.apellidos))dr_nombre_participante,
(r.monto_deposito)dr_monto_pago,
(d.nombre)dr_departamento_participante
";
    $query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
LEFT JOIN departamentos d ON p.id_departamento=d.id 
WHERE p.estado=1 AND c.estado IN (0,1,2) 
$rq_fechas $rq_departamento_participante $rq_docente $rq_nombre $rq_pago $rq_modalidad $qr_gratuitos $qr_curso_especifico 
ORDER BY $qr_orden LIMIT $start,$registros_a_mostrar
";
}
$resultado1 = query($query);
$total_registros = num_rows($resultado1);

$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );

/* sw_info_reducido */
$_SW_info_reducido = false;
if(isset_post('sw_info_reducido')){
    $_SW_info_reducido = true;
}
?>

<?php
if ($sw_grupo) {
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th style="font-size:10pt;">#</th>
                <th style="font-size:10pt;">Cantidad</th>
                <th style="font-size:10pt;">Curso</th>
                <th style="font-size:10pt;">Modalidad</th>
                <th style="font-size:10pt;">Departamento</th>
                <th style="font-size:10pt;">Pago</th>
                <th style="font-size:10pt;min-width: 310px;">Detalles</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total__total_pagos = 0;
            $total_registros = 0;
            while ($producto = fetch($resultado1)) {
                $total_registros += (int)$producto['dr_cantidad'];
                ?>
                <tr>
                    <td>
                        <?php echo $cnt--; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_cantidad']; ?>
                    </td>
                    <td>
                        <?php echo $producto['titulo']; ?> 
                        <b class="pull-right">[ID: <?php echo $producto['dr_id_curso']; ?>]</b>
                    </td>
                    <td>
                        <?php echo $producto['dr_modalidad_curso']=='1' ? 'PRESENCIAL' : 'VIRTUAL'; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_departamento_curso']=='' ? 'NIVEL NACIONAL' :$producto['dr_departamento_curso']; ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_monto_pago']); ?>
                    </td>
                    <td>
                        <b class="btn btn-default btn-xs" onclick="detalle_pagos('<?php echo $producto['dr_id_curso']; ?>');"><i class="fa fa-money"></i> Pagos</b>
                        &nbsp;&nbsp;
                        <b class="btn btn-default btn-xs" onclick="detalle_departamentos('<?php echo $producto['dr_id_curso']; ?>');"><i class="fa fa-map-marker"></i> Departamentos</b>
                        &nbsp;&nbsp;
                        <b class="btn btn-default btn-xs" onclick="detalle_participantes('<?php echo $producto['dr_id_curso']; ?>');"><i class="fa fa-group"></i> Participantes</b>
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
                <td colspan="4">
                    Total participantes: <?php echo $total_registros; ?>
                </td>
                <td colspan="2">
                    <?php echo format_monto($total__total_pagos); ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
}else{
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th style="font-size:10pt;">#</th>
                <th style="font-size:10pt;">Registro</th>
                <th style="font-size:10pt;">Nombre</th>
                <th style="font-size:10pt;">Departamento</th>
                <?php if(!$_SW_info_reducido){ ?>
                <th style="font-size:10pt;">Curso</th>
                <?php } ?>
                <th style="font-size:10pt;">Modalidad</th>
                <th style="font-size:10pt;" colspan="2">Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total__total_pagos = 0;
            while ($producto = fetch($resultado1)) {
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
                        <?php if(!$_SW_info_reducido){ ?>
                        <b class="btn btn-default btn-xs pull-right" onclick="historial_participante('<?php echo $producto['dr_id_participante']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                        </b>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_departamento_participante']==''?'Sin dato':$producto['dr_departamento_participante']; ?>
                    </td>
                    <?php if(!$_SW_info_reducido){ ?>
                    <td>
                        <?php echo $producto['titulo']; ?> <b class="pull-right">[ID: <?php echo $producto['dr_id_curso']; ?>]</b>
                    </td>
                    <?php } ?>
                    <td>
                        <?php 
                        if(!$_SW_info_reducido){ 
                            ?>
                            <?php echo $producto['dr_modalidad_curso']=='1' ? 'PRESENCIAL' : 'VIRTUAL'; ?>
                            <?php 
                        }else{
                            $rqauxmp1 = query("SELECT titulo FROM modos_de_pago WHERE id='".$producto['id_modo_pago']."' LIMIT 1 ");
                            $rqauxmp2 = fetch($rqauxmp1);
                            echo $rqauxmp2['titulo'];
                        } 
                        ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_monto_pago']); ?>
                    </td>
                    <?php if(!$_SW_info_reducido){ ?>
                    <td>
                        <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $producto['dr_id_participante']; ?>');" class="btn btn-xs btn-default">
                            <i class="fa fa-info"></i> Pago
                        </a>
                    </td>
                    <?php } ?>
                </tr>
                <?php
                $total__total_pagos += (int) $producto['dr_monto_pago'];
            }
            ?>
        </tbody>
        <tfoot>
            <?php if(!$_SW_info_reducido){ ?>
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
            <?php }else{ ?>
                <tr>
                    <td>
                        -
                    </td>
                    <td colspan="4">
                        Total participantes: <?php echo $total_registros; ?>
                    </td>
                    <td colspan="2">
                        <?php echo format_monto($total__total_pagos); ?>
                    </td>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>
<?php
}
?>

<hr/>

<?php

function format_monto($dat) {
    $monto = (int) $dat;
    if ($monto == 0) {
        return "<span style='color:#DDD;'>$monto</span>";
    } else {
        return $monto;
    }
}
?>


<!-- detalle_pagos -->
<script>
    function detalle_pagos(id_curso) {
        var form = $("#FORM-listado").serialize();
        $("#TITLE-modgeneral").html('REGISTROS POR MODALIDADES DE PAGO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.participantes-estadisticas.detalle_pagos.php?id_curso='+id_curso,
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- detalle_departamentos -->
<script>
    function detalle_departamentos(id_curso) {
        var form = $("#FORM-listado").serialize();
        $("#TITLE-modgeneral").html('REGISTROS POR DEPARTAMENTOS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.participantes-estadisticas.detalle_departamentos.php?id_curso='+id_curso,
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- detalle_participantes -->
<script>
    function detalle_participantes(id_curso) {
        $("#TITLE-modgeneral").html('REGISTROS POR PARTICIPANTES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'pages/ajax/ajax.participantes-estadisticas.listado.php',
            data: form+"&id_curso="+id_curso+"&sw_info_reducido=1",
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data+'<style>@media (min-width: 768px){.modal-dialog {width: 1000px !important;}}</style>');
            }
        });
    }
</script>
