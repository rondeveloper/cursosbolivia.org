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
$f_mes = str_pad(post('mes'), 2, '0', STR_PAD_LEFT);
$f_anio = post('anio');

$fecha_inicio = "$f_anio-$f_mes-01";
$fecha_fin = "$f_anio-$f_mes-31";
$id_departamento = (int) post('id_departamento');
$id_ciudad = (int) post('id_ciudad');
$id_lugar = (int) post('id_lugar');
$id_docente = (int) post('id_docente');
$numero = (int) post('numero');
$nombre = trim(post('nombre'));

/* data post export */
$postdata_exort = base64_encode(json_encode($_POST));

/* vista */
$vista = 1;
$registros_a_mostrar = 500;
$start = ($vista - 1) * $registros_a_mostrar;

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$rq_ciudad_departamento = "";
$qr_nombre = ' 1 ';
$rq_docente = "";
$rq_numero = "";
$rq_lugar = "";
$rq_nombre = "";
$rq_fechas = "";
if ($numero !== 0) {
    $rq_numero = " AND c.numero='$numero'  ";
} else {
    $rq_fechas = " AND c.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
    if ($id_departamento !== 0) {
        $rq_ciudad_departamento = " AND d.id='$id_departamento'  ";
    }
    if ($id_docente !== 0) {
        $rq_docente = " AND dc.id='$id_docente'  ";
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
}


/* data required */
$data_required = "
*,
(c.id)dr_id_curso,
(cd.nombre)dr_nombre_ciudad,
(d.nombre)dr_nombre_departamento,
(dc.nombres)dr_nombre_docente,
(l.nombre)dr_nombre_lugar,
(l.salon)dr_nombre_salon
";
/* query */
$resultado1 = query("
SELECT $data_required FROM 
cursos c 
INNER JOIN ciudades cd ON c.id_ciudad=cd.id 
INNER JOIN departamentos d ON cd.id_departamento=d.id 
LEFT JOIN cursos_docentes dc ON c.id_docente=dc.id  
LEFT JOIN cursos_lugares l ON c.id_lugar=l.id 
WHERE c.estado IN (0,1,2) 
$rq_fechas $rq_numero $rq_ciudad_departamento $rq_docente $rq_nombre $rq_lugar 
ORDER BY c.numero DESC LIMIT $start,$registros_a_mostrar
");
$total_registros = num_rows($resultado1);

$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );

$array_dia = array();
for ($i = 1; $i <= 31; $i++) {
    $array_dia[$i] = array();
}
while ($producto = fetch($resultado1)) {
    $aux_d = (int) date("d", strtotime($producto['fecha']));
    array_push($array_dia[$aux_d], $producto);
}

$fm_next = ((int) $f_mes) + 1;
$fm_prev = ((int) $f_mes) - 1;
?>

<br/>
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




<?php
# Obtenemos el dia de la semana del primer dia
# Devuelve 0 para domingo, 6 para sabado
$diaSemana = date("w", mktime(0, 0, 0, $f_mes, 1, $f_anio)) + 7;
# Obtenemos el ultimo dia del mes
$ultimoDiaMes = date("d", (mktime(0, 0, 0, $f_mes + 1, 1, $f_anio) - 1));
$meses = array("Diciembre", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Enero");
?>

<style>
    #calendar {
        font-family:Arial;
        font-size:12px;
        width: 100%;
    }
    #calendar caption {
        text-align:left;
        padding:5px 10px;
        background-color:#076f1c;
        color:#fff;
        font-weight:bold;
    }
    #calendar th {
        background-color: #92bf57;
        color: #fff;
        width: 40px;
        border: 1px solid #FFF;
    }
    #calendar td {
        text-align:center;
        vertical-align: baseline;
        padding:2px 5px;
        background-color: white;
        border: 1px solid silver;
        height: 90px;
    }
    #calendar .hoy {
        background-color:#e2ffbd;
    }
</style>
<table id="calendar">
    <caption><?php echo $meses[(int) $f_mes] . " " . $f_anio ?></caption>
    <tr>
        <th>Lun</th><th>Mar</th><th>Mie</th><th>Jue</th>
        <th>Vie</th><th>Sab</th><th>Dom</th>
    </tr>
    <?php
    $cnt_celdavacia = 0;
    $nro_fila = 1;
    ?>
    <tr id='fila-<?php echo $nro_fila; ?>'>
        <?php
        $last_cell = $diaSemana + $ultimoDiaMes;
        /* hacemos un bucle hasta 42, que es el máximo de valores que puede */
        /* haber... 6 columnas de 7 dias */
        for ($i = 1; $i <= 42; $i++) {
            if ($i == $diaSemana) {
                /* determinamos en que dia empieza */
                $day = 1;
            }
            if ($i < $diaSemana || $i >= $last_cell) {
                /* celda vacia */
                echo "<td style='background:#e2e2e2;'>&nbsp;</td>";
                $cnt_celdavacia++;
            } else {
                /*  mostramos el dia */
                $class_today = '';
                if ($day == date("j") && $f_mes == date("m")) {
                    $class_today = 'hoy';
                }
                ?>
                <td class="<?php echo $class_today; ?>">
                    <div style="text-align: left">
                        <b style="font-size: 18pt;color: #076f1c;"><?php echo $day; ?></b>
                    </div>
                    <div style="text-align: center">
                        <?php if (count($array_dia[$day]) > 0) { ?>
                            <span style="font-size:11pt;"><?php echo count($array_dia[$day]); ?></span> cursos
                            <br/>
                            <i class="btn btn-xs btn-info" data-toggle="modal" data-target="#MODAL-cursos_dia" onclick="cursos_dia('<?php echo $f_anio . '-' . $f_mes . '-' . $day; ?>');">
                                Listar
                            </i>
                        <?php } ?>
                    </div>
                </td>
                <?php
                $day++;
                $cnt_celdavacia = 0;
            }
            if ($cnt_celdavacia >= 7) {
                echo "<style>#fila-$nro_fila{display:none;}</style>";
                $cnt_celdavacia = 0;
            }
            /* cuando llega al final de la semana, iniciamos una columna nueva */
            if ($i % 7 == 0) {
                $nro_fila++;
                echo "</tr><tr id='fila-$nro_fila'>";
            }
        }
        ?>
    </tr>
</table>
<div class="box-m-meses">
    <div class="box-m-left" onclick="nextprev_mes('<?php echo $fm_prev; ?>');">
        <i class="fa fa-toggle-left"></i> &nbsp; <?php echo strtoupper($meses[((int) $f_mes) - 1]); ?>
    </div>
    <div class="box-m-center">
        Total cursos: <?php echo $total_registros; ?>
    </div>
    <div class="box-m-right" onclick="nextprev_mes('<?php echo $fm_next; ?>');">
        <?php echo strtoupper($meses[((int) $f_mes) + 1]); ?> &nbsp; <i class="fa fa-toggle-right"></i>
    </div>
    <div style="clear:both"></div>
</div>

<style>
    .box-m-left{
        background: #92bf57;
        border-bottom: 2px solid #13a913;
        margin-top: 2px;
        width: 30%;
        color: #FFF;
        font-weight: bold;
        padding: 4px 20px;
        float: left;
        border-radius: 0px 0px 0px 20px;
        text-align: left;
        cursor: pointer;
        border-right: 5px solid #c3d2b0;
    }
    .box-m-right{
        background: #92bf57;
        border-bottom: 2px solid #13a913;
        margin-top: 2px;
        width: 30%;
        color: #FFF;
        font-weight: bold;
        padding: 4px 20px;
        float: right;
        border-radius: 0px 0px 20px 0px;
        text-align: right;
        cursor:pointer;
        border-left: 5px solid #c3d2b0;
    }
    .box-m-center{
        width: 35%;
        float: left;
        text-align: center;
        margin-top: 2px;
        padding: 4px 20px;
    }
</style>

<br/>
<hr/>
<br/>


<!-- Modal -->
<div id="MODAL-cursos_dia" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 95%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CURSOS DEL D&Iacute;A</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-cursos_dia"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function cursos_dia(fecha) {
        $("#AJAXCONTENT-cursos_dia").html("Cargando...");
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'pages/ajax/ajax.calendario-cursos.cursos_dia.php?fecha=' + fecha,
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-cursos_dia").html(data);
            }
        });
    }
</script>

<script>
    function nextprev_mes(nextprevmes) {
        if (nextprevmes < 1) {
            nextprevmes = 12;
            $("#fnp_anio").val(parseInt($("#fnp_anio").val()) - 1);
        }
        if (nextprevmes > 12) {
            nextprevmes = 1;
            $("#fnp_anio").val(parseInt($("#fnp_anio").val()) + 1);
        }
        $("#fnp_mes").val(nextprevmes);
        listado();
    }
</script>