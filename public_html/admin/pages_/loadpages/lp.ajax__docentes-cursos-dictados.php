<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

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

/* post search */
$postjson = json_encode($_POST);
if ($postjson == '[]') {
    $post_search_data = 'no-search';
} else {
    $post_search_data = base64_encode($postjson);
}

/* get search */
if (isset($get[3]) && $get[3] !== 'no-search') {
    $_POST = json_decode(base64_decode($get[3]), true);
}

/* busqueda */
$busqueda = "";
$id_departamento = "0";
$fecha_busqueda = "";
$fecha_final_busqueda = "";
$txt_estado = "";
$htm_titlepage = 'CURSOS DICTADOS';
$qr_busqueda = "";
$qr_fecha = "";
$qr_estado = " AND c.estado IN (0,1,2)  ";
$qr_departamento = "";
$qr_docente = "";
$qr_lugar = "";


/* estados de curso (+cursos hoy) */
if (isset($get[4])) {
    switch ($get[4]) {
        case 'temporales':
            $qr_estado = " AND c.estado='2' ";
            $txt_estado = " - TEMPORALES";
            break;
        case 'activos':
            $qr_estado = " AND c.estado='1' ";
            $txt_estado = " - ACTIVOS";
            break;
        case 'hoy':
            $qr_estado = " AND c.estado IN (1) AND c.fecha=CURDATE() ";
            $txt_estado = " - HOY";
            break;
        case 'sincierre':
            $qr_estado = " AND c.estado IN (0,1,2) AND c.sw_cierre='0' AND c.id_cierre='0' ";
            $txt_estado = " - SIN CIERRE";
            break;
        default:
            break;
    }
}

/* get departamento */
if (isset($get[5])) {
    $id_departamento = abs((int) ($get[5]));
    $qr_departamento = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
    $rqdde1 = query("SELECT nombre,titulo_identificador FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
    $rqdde2 = fetch($rqdde1);
    $htm_titlepage = "CURSOS " . strtoupper($rqdde2['nombre']) . " : <a href='".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html</a>";
    $htm_titlepage .= " | <a " . loadpage('cursos-infoact/1/no-search/todos/' . $id_departamento . '.adm') . " class='btn btn-xs btn-default'>DATA-INFO</a>";
}

$sw_busqueda = false;
if (isset_post('realizar-busqueda')) {
    $sw_busqueda = true;
    /* titulo id numero */
    if (post('input-buscador') !== '') {
        $busqueda = post('input-buscador');
        /* qr numero */
        $qr_numero = '';
        if ((int) $busqueda > 0) {
            $qr_numero = " OR c.numero='$busqueda' ";
        }
        $qr_busqueda = " AND (c.id='$busqueda' $qr_numero OR c.titulo LIKE '%$busqueda%' ) ";
    }

    /* ciudad */
    if (post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_departamento = " AND c.id_ciudad='$id_ciudad' ";
    } elseif (post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_departamento = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
    }

    /* fecha */
    if (post('fecha') !== '') {
        $fecha_busqueda = post('fecha');
        $fecha_final_busqueda = post('fecha_final');
        $qr_fecha = " AND DATE(c.fecha)>='$fecha_busqueda' AND DATE(c.fecha)<='$fecha_final_busqueda' ";
    }

    /* docente */
    if (post('id_docente') !== '0') {
        $id_docente = post('id_docente');
        $qr_docente = " AND c.id_docente='$id_docente' ";
    }

    /* docente */
    if (post('id_lugar') !== '0') {
        $id_lugar = post('id_lugar');
        $qr_lugar = " AND c.id_lugar='$id_lugar' ";
    }


    /* estado */
    if (post('inluir_eliminados') == '1') {
        $qr_estado = " AND c.estado IN (0,1,2,3) ";
    } else {
        $qr_estado = " AND c.estado IN (0,1,2) ";
    }
}

/* registros_pagina */
$registros_a_mostrar = 20;
if (isset_post('registros_pagina')) {
    $registros_a_mostrar = (int) post('registros_pagina');
}
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];


/* eliminacion de curso */
if (isset_post('delete-course')) {
    if ($nivel_administrador == '1' || isset_organizador()) {
        $id_curso_delete = post('id_curso');
        /* fecha nula */
        $rqdcd1 = query("SELECT fecha,numero FROM cursos WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        $rqdcd2 = fetch($rqdcd1);
        if ($rqdcd2['numero'] == '0') {
            if ($rqdcd2['fecha'] == (date("Y") . '-12-31')) {
                query("UPDATE cursos SET fecha='" . date("Y") . "-01-01' WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
            }
            /* proceso */
            query("UPDATE cursos SET estado='3' WHERE id='$id_curso_delete' ORDER BY id DESC ");
            logcursos('Eliminacion de curso', 'curso-eliminacion', 'curso', $id_curso_delete);
            $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO!</strong> curso eliminado correctamente.
</div>';
        } else {
            $mensaje = '<br/><div class="alert alert-danger">
  <strong>ERROR!</strong> el curso ya tiene numeraci&oacute;n y no puede ser eliminado.
</div>';
        }
    }
}


/* qr organizador */
$qr_organizador = "";
if (isset_organizador()) {
    $id_organizador = organizador('id');
    $qr_organizador = " AND c.id_organizador='$id_organizador' ";
}

/* virtuales */
$qr_modalidad = "";
if (isset($get[5]) && $get[5] == '10') {
    $qr_departamento = "";
    $qr_modalidad = " AND id_modalidad IN (2,3) ";
    $htm_titlepage = "CURSOS VIRTUTALES";
}


/* registros */
$data_required = "
c.sw_suspendido,c.imagen,c.duracion,c.horarios,c.sw_cierre,c.id_cierre,c.id_modalidad,c.id_abreviacion,c.fecha,c.sw_fecha2,c.fecha2,c.sw_fecha3,c.fecha3,c.costo,c.costo2,c.costo3,c.titulo,c.short_link,c.numero,c.id_certificado,c.cnt_reproducciones,c.columna,c.titulo_identificador,
c.laststch_fecha,c.laststch_id_administrador,
(select count(1) from cursos_participantes where id>22000 and id_curso=c.id and estado='1' order by id desc)cnt_participantes_aux,
(concat(d.prefijo,' ',d.nombres))docente,
(cd.id)dr_id_ciudad,
(cd.ident)dr_ident_ciudad,
(l.nombre)dr_nombre_lugar,
(l.salon)dr_nombre_salon,
(c.id)dr_id_curso,
(c.estado)dr_estado_curso
";

if ($sw_busqueda) {
    $resultado1 = query("SELECT $data_required FROM cursos c LEFT JOIN ciudades cd ON c.id_ciudad=cd.id LEFT JOIN cursos_docentes d ON d.id=c.id_docente LEFT JOIN cursos_lugares l ON l.id=c.id_lugar WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_docente $qr_fecha $qr_lugar $qr_modalidad ORDER BY c.fecha ASC,c.id_ciudad ASC,c.id ASC LIMIT 100 ");
    $resultado2 = query("SELECT $data_required FROM cursos c LEFT JOIN ciudades cd ON c.id_ciudad=cd.id LEFT JOIN cursos_docentes d ON d.id=c.id_docente LEFT JOIN cursos_lugares l ON l.id=c.id_lugar WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_docente $qr_fecha $qr_lugar $qr_modalidad ORDER BY c.fecha ASC,c.id_ciudad ASC,c.id ASC LIMIT 100 ");
    $resultado3 = query("SELECT count(*) AS total FROM cursos c WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_docente $qr_fecha $qr_lugar $qr_modalidad ");
    $resultado3b = fetch($resultado3);

    $total_registros = $resultado3b['total'];
    $cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
}
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
            <div class="col-md-12">
                <h3 style="padding: 0px; margin: 0px; padding-top: 5px;">
                    <?php echo $htm_titlepage . $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> 
                    <a <?php echo loadpage('cursos-crear'); ?> class='btn btn-sm btn-success active pull-right hidden-sm'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
                </h3>
                <blockquote class="page-information hidden">
                    <p>
                        Listado de cursos.
                    </p>
                </blockquote>
            </div>
        </div>

        <form action="docentes-cursos-dictados.adm" id="FORM-principal" method="post" style="border-radius: 5px;
              overflow: hidden;
              padding: 5px;
              background: #e6e6e6;">
            <div class="row" style="background: #d4d4d4;padding: 5px 0px;">
                <div class="col-md-4">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Docente: </span>
                        <select class="form-control" name="id_docente">
                            <?php
                            echo "<option value='0'>Seleccione el docente...</option>";
                            $rqd1 = query("SELECT id,nombres,prefijo FROM cursos_docentes WHERE estado='1' ORDER BY nombres ASC ");
                            while ($rqd2 = fetch($rqd1)) {
                                $selected = '';
                                if ($id_docente == $rqd2['id']) {
                                    $selected = ' selected="selected" ';
                                }
                                echo "<option value='" . $rqd2['id'] . "' $selected>" . $rqd2['prefijo'] . ' ' . $rqd2['nombres'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 hidden-sm">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Fecha inicio: </span>
                        <input type="date" name="fecha" value="<?php echo $fecha_busqueda; ?>" class="form-control" placeholder="Fecha de inicio..." onchange="$('#_fecha_final').val(this.value);"/>
                    </div>
                </div>
                <div class="col-md-4 hidden-sm">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Fecha final: </span>
                        <input type="date" name="fecha_final" value="<?php echo $fecha_final_busqueda; ?>" class="form-control" placeholder="Fecha final..." id="_fecha_final"/>
                    </div>
                </div>
            </div>
            <div class="row" style="background: #d4d4d4;padding: 5px 0px;">
                <div class="col-md-4">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Departamento: </span>
                        <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                            <?php
                            echo "<option value='0'>Todos los departamentos...</option>";
                            $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                            while ($rqd2 = fetch($rqd1)) {
                                $text_check = '';
                                if ($id_departamento == $rqd2['id']) {
                                    $text_check = ' selected="selected" ';
                                }
                                echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Ciudad: </span>
                        <select class="form-control" name="id_ciudad" id="select_ciudad" onchange="actualiza_lugares();">
                            <?php
                            echo "<option value='0'>Todos las ciudades...</option>";
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Lugar: </span>
                        <select class="form-control" name="id_lugar" id="select_lugar">
                            <option value='0'>Todos los lugares...</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="background: #d4d4d4;padding: 5px 0px;">
                <div class="col-md-12">
                    <input type="submit" name="realizar-busqueda" value="BUSCAR" class="btn btn-warning btn-block active" style="background: #44ab39;border: 1px solid #FFF;"/>
                </div>
            </div>
        </form>
    </div>
</div>


<?php echo $mensaje; ?>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: '<?php echo $dominio_www; ?>pages/ajax/ajax.cursos-listar.data_participantes.php',
            data: {id_curso: id_curso, hash: '<?php echo md5(md5("7" . $ip_coneccion . "d" . date("Y-m-d H") . "0" . $user_agent)); ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                var data_json_parsed = JSON.parse(data);
                var data1 = data_json_parsed['data1'];
                var data2 = data_json_parsed['data2'];
                var data3 = data_json_parsed['data3'];

                $("#box-datapart-" + id_curso).html(data1);
                $("#box-datapart2-" + id_curso).html(data2);
                $("#box-datapart3-" + id_curso).html(data3);
            }
        });
    }
</script>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td{
        background: #ebefdd !important;
    }
    .tr_curso_cerrado td{
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }
    .tr_curso_cerrado:hover td{
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }
    .tr_curso_eliminado td{
        background: #f3e3e3 !important;
    }
</style>

<div class="row">

    <?php
    if ($sw_busqueda) {
        ?>
        <div class="col-md-5">
            <div style="padding: 10px 0px 10px 30px;
                 border: 1px solid #dadada;
                 margin: 20px 0px;
                 font-size: 10pt;">
                 <?php
                 /* docente */
                 $rqd1 = query("SELECT id,nombres,prefijo,id_modalidad_pago,pago_hora,pago2_hora,paydata_limite FROM cursos_docentes WHERE id='$id_docente' LIMIT 1 ");
                 $rqd2 = fetch($rqd1);
                 $docente_id_modalidad_pago = $rqd2['id_modalidad_pago'];
                 $docente_pago_hora = (int) $rqd2['pago_hora'];
                 $docente_pago2_hora = (int) $rqd2['pago2_hora'];
                 $docente_paydata_limite = (int) $rqd2['paydata_limite'];
                 $id_docente = $rqd2['id'];
                 echo "<h3>" . $rqd2['prefijo'] . ' ' . $rqd2['nombres'] . "</h3>";

                 /* cursos */
                 $preday = '';
                 while ($curso = fetch($resultado1)) {
                     $id_curso = $curso['dr_id_curso'];
                     
                     $rqcpc1 = query("SELECT COUNT(*) AS total FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ");
                     $rqcpc2 = fetch($rqcpc1);
                     $cnt_participantes = (int) $rqcpc2['total'];
                     
                     
                     /* curso suspendido */
                     if ($curso['sw_suspendido'] == 1) {
                         $tr_class .= ' tr_curso_suspendido';
                     }
                     /* curso eliminado */
                     if ($curso['dr_estado_curso'] == 3) {
                         $tr_class .= ' tr_curso_eliminado';
                     }
                     $day = date("d", strtotime($curso['fecha']));
                     if ($day !== $preday) {
                         echo '<br/>';
                         $preday = $day;
                     }
                     /* abreviacion curso */
                     if ((int) $curso['id_abreviacion'] != 0) {
                         $rqdac1 = query("SELECT titulo FROM cursos_abreviaciones WHERE id='" . $curso['id_abreviacion'] . "' LIMIT 1 ");
                         $rqdac2 = fetch($rqdac1);
                         $abrev_curso = $rqdac2['titulo'];
                     } else {
                         $abrev_curso = getIdentCurso($curso['titulo']);
                     }

                     $cur_duracion_valido = $curso['duracion'];
                     $cur_duracion_valido_descuento = $curso['duracion'];
                     if ($docente_id_modalidad_pago == '2') {
                         if ($cnt_participantes <= $docente_paydata_limite) {
                             $cur_duracion_valido_descuento = ((int) $curso['duracion'] - 1);
                         }
                     }
                     ?>
                     <?php echo $curso['dr_ident_ciudad']; ?>(<?php echo $day; ?>)&nbsp;<?php echo $cur_duracion_valido; ?>H&nbsp;<?php echo $cnt_participantes; ?>p&nbsp;<?php echo $abrev_curso; ?>&nbsp;
                    <?php
                    if ($curso['sw_suspendido'] == 1) {
                        echo "<b>SUSPENDIDO</b>";
                    }
                    ?>
                    <?php echo $cur_duracion_valido_descuento; ?>H
                    <br/>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-md-7">
            <div style="padding: 10px 10px 10px 10px;
                 border: 1px solid #dadada;
                 margin: 20px 0px;
                 font-size: 12pt;">
                 <?php
                 if ($docente_id_modalidad_pago == '0') {
                     ?>
                    <h4>DOCENTE SIN MODALIDAD DE PAGO</h4>
                    <?php
                } elseif ($docente_id_modalidad_pago == '1') {
                    ?>
                    <h4>PAGOS - MODALIDAD 1</h4>
                    <b>Pago por hora:</b> <?php echo $docente_pago_hora . ' BS'; ?>
                    <br/>
                    <br/>
                    <table class="table table-bordered table-striped table-responsive table-hover">
                        <tr>
                            <th>Ciudad</th>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>Duraci&oacute;n</th>
                            <th colspan="2">Pago</th>
                        </tr>
                        <?php
                        $pago_total = 0;
                        $horas_total = 0;
                        while ($curso = fetch($resultado2)) {
                            $duracion_curso = $curso['duracion'];
                            $id_curso = $curso['dr_id_curso'];
                            ?>
                            <tr>
                                <td><?php echo $curso['dr_ident_ciudad']; ?></td>
                                <td><?php echo date("d/M", strtotime($curso['fecha'])); ?></td>
                                <td>
                                    <?php echo getIdentCurso($curso['titulo']); ?>
                                </td>
                                <?php
                                if ($curso['sw_suspendido'] == 1) {
                                    ?>
                                    <td colspan="3">SUSPENDIDO</td>
                                    <?php
                                } else {
                                    $horas_total += $duracion_curso;
                                    $monto_pago = ($duracion_curso * $docente_pago_hora);
                                    $pago_total += $monto_pago;
                                    ?>
                                    <td><?php echo $duracion_curso . ' H'; ?></td>
                                    <td><?php echo $monto_pago . ' BS'; ?></td>
                                    <td>
                                        <?php
                                        $rqvp1 = query("SELECT id FROM cursos_docentes_pagos WHERE id_docente='$id_docente' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                        $check = '';
                                        if (num_rows($rqvp1) > 0) {
                                            $check = ' checked="" ';
                                        }
                                        ?>
                                        <label class="switch">
                                            <input type="checkbox" <?php echo $check; ?> onclick="pago_curso('<?php echo $id_curso; ?>', '<?php echo $id_docente; ?>');">
                                            <div class="slider round"></div>
                                        </label>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right">Total:</th>
                            <th><?php echo $horas_total . ' H'; ?></th>
                            <th><?php echo $pago_total . ' BS'; ?></th>
                            <th></th>
                        </tr>
                    </table>
                    <?php
                } elseif ($docente_id_modalidad_pago == '2') {
                    ?>
                    <h4>PAGOS - MODALIDAD 2</h4>
                    <b>Pago por hora:</b> <?php echo $docente_pago_hora . ' BS'; ?>
                    <br/>
                    <b>Limite m&iacute;nimo:</b> <?php echo $docente_paydata_limite . ' participantes'; ?>
                    <br/>
                    <br/>
                    <table class="table table-bordered table-striped table-responsive table-hover">
                        <tr>
                            <th>Ciudad</th>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>Part.</th>
                            <th>Duraci&oacute;n</th>
                            <th colspan="2">Pago</th>
                        </tr>
                        <?php
                        $pago_total = 0;
                        $horas_total = 0;
                        while ($curso = fetch($resultado2)) {
                            $duracion_curso = $curso['duracion'];
                            $id_curso = $curso['dr_id_curso'];

                            $rqcpc1 = query("SELECT COUNT(*) AS total FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ");
                            $rqcpc2 = fetch($rqcpc1);
                            $cnt_participantes = (int) $rqcpc2['total'];
                            ?>
                            <tr>
                                <td><?php echo $curso['dr_ident_ciudad']; ?></td>
                                <td><?php echo date("d/M", strtotime($curso['fecha'])); ?></td>
                                <td>
                                    <?php echo getIdentCurso($curso['titulo']); ?>
                                    <a data-toggle="modal" data-target="#MODAL-edita_horas" onclick="edita_horas('<?php echo $id_curso; ?>');" class="btn btn-xs btn-info pull-right"><i class="fa fa-edit"></i></a>
                                </td>
                                <?php
                                if ($curso['sw_suspendido'] == 1) {
                                    ?>
                                    <td colspan="4">SUSPENDIDO</td>
                                    <?php
                                } else {
                                    ?>
                                    <td><?php echo $cnt_participantes . 'p'; ?></td>
                                    <td>
                                        <?php
                                        if ($cnt_participantes > $docente_paydata_limite) {
                                            $horas_total += $duracion_curso;
                                            $monto_pago = ($duracion_curso * $docente_pago_hora);
                                            $pago_total += $monto_pago;

                                            echo $duracion_curso . 'h';
                                        } else {
                                            $aux_duracion_curso = ($duracion_curso - 1);
                                            if ($aux_duracion_curso < 0) {
                                                $aux_duracion_curso = 0;
                                            }

                                            $horas_total += $aux_duracion_curso;
                                            $monto_pago = ($aux_duracion_curso * $docente_pago_hora);
                                            $pago_total += $monto_pago;

                                            echo $aux_duracion_curso . 'h';
                                            echo '<br/>';
                                            echo '<span style="font-size:9pt;color:gray;">' . $duracion_curso . 'h-1h</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $monto_pago; ?></td>
                                    <td>
                                        <?php
                                        $rqvp1 = query("SELECT id FROM cursos_docentes_pagos WHERE id_docente='$id_docente' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                        $check = '';
                                        if (num_rows($rqvp1) > 0) {
                                            $check = ' checked="" ';
                                        }
                                        ?>
                                        <label class="switch">
                                            <input type="checkbox" <?php echo $check; ?> onclick="pago_curso('<?php echo $id_curso; ?>', '<?php echo $id_docente; ?>');">
                                            <div class="slider round"></div>
                                        </label>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th class="text-right" colspan="4">Total:</th>
                            <th><?php echo $horas_total . ' H'; ?></th>
                            <th colspan="2"><?php echo $pago_total . ' BS'; ?></th>
                        </tr>
                    </table>
                    <?php
                } elseif ($docente_id_modalidad_pago == '3') {
                    ?>
                    <h4>PAGOS - MODALIDAD 3</h4>
                    <b>Pago por hora:</b> <?php echo $docente_pago_hora . ' BS'; ?>
                    <br/>
                    <b>Pago por hora (Interior):</b> <?php echo $docente_pago2_hora . ' BS'; ?>
                    <br/>
                    <br/>
                    <table class="table table-bordered table-striped table-responsive table-hover">
                        <tr>
                            <th>Ciudad</th>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>Duraci&oacute;n</th>
                            <th colspan="2">Pago</th>
                        </tr>
                        <?php
                        $pago_total = 0;
                        $horas_total = 0;
                        while ($curso = fetch($resultado2)) {
                            $duracion_curso = $curso['duracion'];
                            $id_curso = $curso['dr_id_curso'];
                            $id_ciudad = $curso['dr_id_ciudad'];
                            ?>
                            <tr>
                                <td><?php echo $curso['dr_ident_ciudad']; ?></td>
                                <td><?php echo date("d/M", strtotime($curso['fecha'])); ?></td>
                                <td><?php echo getIdentCurso($curso['titulo']); ?></td>
                                <?php
                                if ($curso['sw_suspendido'] == 1) {
                                    ?>
                                    <td colspan="3">SUSPENDIDO</td>
                                    <?php
                                } else {
                                    /* verfi local-interior*/
                                    $rqvi1 = query("SELECT id FROM ciudades WHERE id='$id_ciudad' AND id_departamento='3' ");
                                    if(num_rows($rqvi1)>0){
                                        $horas_total += $duracion_curso;
                                        $monto_pago = ($duracion_curso * $docente_pago_hora);
                                        $pago_total += $monto_pago;
                                    }else{
                                        $horas_total += $duracion_curso;
                                        $monto_pago = ($duracion_curso * $docente_pago2_hora);
                                        $pago_total += $monto_pago;
                                    }
                                    ?>
                                    <td><?php echo $duracion_curso . ' H'; ?></td>
                                    <td><?php echo $monto_pago . ' BS'; ?></td>
                                    <td>
                                        <?php
                                        $rqvp1 = query("SELECT id FROM cursos_docentes_pagos WHERE id_docente='$id_docente' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                        $check = '';
                                        if (num_rows($rqvp1) > 0) {
                                            $check = ' checked="" ';
                                        }
                                        ?>
                                        <label class="switch">
                                            <input type="checkbox" <?php echo $check; ?> onclick="pago_curso('<?php echo $id_curso; ?>', '<?php echo $id_docente; ?>');">
                                            <div class="slider round"></div>
                                        </label>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right">Total:</th>
                            <th><?php echo $horas_total . ' H'; ?></th>
                            <th><?php echo $pago_total . ' BS'; ?></th>
                            <th></th>
                        </tr>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
    } else {
        echo "<br><hr><br><p>Ingrese un criterio de busqueda.</p>";
    }
    ?>

</div>

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_lugares() {
        $("#select_lugar").html('<option>Cargando...</option>');
        var id_ciudad = $("#select_ciudad").val();
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'pages/ajax/ajax.estadisticas-cursos.actualiza_lugares.php',
            data: {id_ciudad: id_ciudad, id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#select_lugar").html(data);
            }
        });
    }
</script>

<script>
    function pago_curso(id_curso, id_docente) {
        $.ajax({
            url: 'pages/ajax/ajax.docentes-cursos-dictados.pago_curso.php',
            data: {id_curso: id_curso, id_docente: id_docente},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                console.log(data);
            }
        });
    }
</script>

<script>
    function edita_horas(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.docentes-cursos-dictados.edita_horas_p1.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-edita_horas").html(data);
            }
        });
    }
</script>

<!-- Modal edita_horas -->
<div id="MODAL-edita_horas" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">EDICI&Oacute;N DE HORAS</h4>
      </div>
      <div class="modal-body">
        <div class="modal-body" id="AJAXCONTENT-edita_horas"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php
function getIdentCurso($dat) {
    $r1 = explode(' en ', $dat);
    return trim(str_replace('Curso ', '', $r1[0]));
}
