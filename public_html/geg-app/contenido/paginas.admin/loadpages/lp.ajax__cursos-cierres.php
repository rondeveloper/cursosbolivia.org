<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);
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
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* acceso */
if (!acceso_cod('adm-cursos-cierre')) {
    echo "DENEGADO";
    exit;
}

/* mensaje */
$mensaje = '';

//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$id_departamento = "0";
$fecha_busqueda = "";
$qr_fecha = "";
$qr_estado = "";
$txt_estado = "";
$qr_departamento = "";

if (isset($get[3]) && $get[3] !== 'no-search') {
    $_POST['input-buscador'] = $get[3];
    $_POST['id_ciudad'] = '0';
    $_POST['id_departamento'] = '0';
}

/* estados de curso (+modificados) */
if (isset($get[4])) {
    switch ($get[4]) {
        case 'modificados':
            $qr_estado = " AND sw_cierre='0' ";
            $txt_estado = " - MODIFICADOS";
            break;
        case 'activos':
            $qr_estado = " AND estado='1' ";
            $txt_estado = " - ACTIVOS";
            break;
        case 'hoy':
            $qr_estado = " AND fecha=CURDATE() ";
            $txt_estado = " - HOY";
            break;
        default:
            break;
    }
}

/* get departamento */
if (isset($get[5])) {
    $id_departamento = abs((int) ($get[5]));
    $qr_departamento = " AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
}

if (isset_post('input-buscador') || isset_post('id_departamento') || (isset($get[3]) && $get[3] !== 'no-search')) {
    if (isset_post('input-buscador')) {
        $busqueda = post('input-buscador');
    } else {
        $busqueda = $get[3];
    }

    /* fecha */
    if (post('fecha') !== '') {
        $fecha_busqueda = post('fecha');
        $qr_fecha = " AND DATE(c.fecha)='$fecha_busqueda' ";
    }

    //*$vista = 1;
    if (post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_busqueda = " AND (cc.numeracion='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND c.id_ciudad='$id_ciudad' $qr_fecha ";
    } elseif (post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " AND (cc.numeracion='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') $qr_fecha ";
    } else {
        $qr_busqueda = " AND (cc.numeracion='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) $qr_fecha ";
    }
}

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = mysql_fetch_array($rqda1);
$nivel_administrador = $rqda2['nivel'];


/* eliminacion de curso */
if (isset_post('delete-course')) {
    if ($nivel_administrador == '1' || isset_organizador()) {
        $id_curso_delete = post('id_curso');
        query("DELETE FROM cursos WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        query("DELETE FROM cursos_rel_cursostags WHERE id_curso='$id_curso_delete' ORDER BY id DESC ");
        //movimiento('Eliminacion de curso', 'eliminacion-curso', 'curso', $id_curso_delete);
        logcursos('Eliminacion de curso', 'curso-eliminacion', 'curso', $id_curso_delete);
        $mensaje = '<br/><div class="alert alert-success">
  <strong>Exito!</strong> registro eliminado.
</div>';
    }
}


/* qr organizador */
$qr_organizador = "";
if (isset_organizador()) {
    $id_organizador = organizador('id');
    $qr_organizador = " AND id_organizador='$id_organizador' ";
}


/* registros */
$resultado1 = query("SELECT 
              *,
              (select concat(prefijo,' ',nombres) from cursos_docentes where id=c.id_docente limit 1)docente,
              (select nombre from administradores where id=cc.id_administrador limit 1)admin_cierre,
              (cc.fecha)fecha_cierre, 
              (cc.id)id_cierre 
               FROM cursos_cierres cc INNER JOIN cursos c ON c.id_cierre=cc.id WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ORDER BY cc.numeracion DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_cierres cc INNER JOIN cursos c ON c.id_cierre=cc.id WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="hidden-lg">
    <?php
    include_once '../../paginas.admin/items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include '../../paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a <?php echo loadpage('inicio'); ?>>Panel Principal</a></li>
            <li><a <?php echo loadpage('cursos-listar'); ?>>Cursos</a></li>
            <li><a <?php echo loadpage('cursos-cierres'); ?>>Cierres</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right hidden-sm">
            <?php
            $rqdec1 = query("SELECT count(*) AS total FROM cursos_cierres ");
            $rqdec2 = mysql_fetch_array($rqdec1);
            $cnt_cursos_cerrados = $rqdec2['total'];

            $rqdec1b = query("SELECT count(*) AS total FROM cursos WHERE id_cierre>'0' AND sw_cierre='0' ");
            $rqdec2b = mysql_fetch_array($rqdec1b);
            $cnt_cursos_modificados = $rqdec2b['total'];

            $rqdec1c = query("SELECT count(*) AS total FROM cursos WHERE id_cierre='0' AND sw_cierre='0' ");
            $rqdec2c = mysql_fetch_array($rqdec1c);
            $cnt_cursos_sin_cierre = $rqdec2c['total'];
            ?>
            <a class="btn btn-info active" <?php echo loadpage('cursos-cierres'); ?>>(<?php echo $cnt_cursos_cerrados; ?>) CURSOS CERRADOS</a>&nbsp;
            <a class="btn btn-info active" <?php echo loadpage('cursos-cierres/1/no-search/modificados'); ?>>(<?php echo $cnt_cursos_modificados; ?>) CURSOS MODIFICADOS</a>&nbsp;
            <a class="btn btn-info active" <?php echo loadpage('cursos-listar/1/no-search/sincierre'); ?>>(<?php echo $cnt_cursos_sin_cierre; ?>) CURSOS SIN CIERRE</a>&nbsp;
        </div>
        <h3 class="page-header"> CIERRE DE CURSOS <?php echo $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <form action="cursos-cierres.adm" method="post">
            <div class="col-md-4">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Buscar por # Cierre / Curso / Docente ..."/>
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                    while ($rqd2 = mysql_fetch_array($rqd1)) {
                        $text_check = '';
                        if ($id_departamento == $rqd2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_ciudad" id="select_ciudad">
                    <?php
                    echo "<option value='0'>Todos las ciudades...</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-2 hidden-sm">
                <input type="date" name="fecha" value="<?php echo $fecha_busqueda; ?>" class="form-control" placeholder="Fecha especifica..."/>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" class="btn btn-warning btn-block active"/>
            </div>
        </form>
    </div>
</div>


<?php echo $mensaje; ?>

<hr/>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-cierres.data_participantes.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                var data1 = data_json_parsed['data1'];
                $("#box-datapart-" + id_curso).html(data1);
            }
        });
    }
</script>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td{
        background: #ebefdd !important;
    }
</style>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-bodyNOT">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">CIERRE</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Documentos</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Imagen / Horarios</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Fecha</th>
                                <th class="visible-lgNOT" style="font-size:10pt;width: 50px;">Costo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <?php if ($nivel_administrador == '1' || isset_organizador()) { ?>
                                    <th class="visible-lgNOT" style="font-size:10pt;">Registrados</th>
                                <?php } ?>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($curso = mysql_fetch_array($resultado1)) {
                                $tr_class = '';
                                /* curso suspendido */
                                if ($curso['sw_suspendido'] == 1) {
                                    $tr_class .= ' tr_curso_suspendido';
                                }
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('<?php echo $curso['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo "<b style='font-size:14pt;color:#00789f;'>CIERRE " . $curso['numeracion'] . "</b>";
                                        echo "<br/>";
                                        echo "<span style='font-size:9pt;color:gray'>Fecha: " . my_date_curso2($curso['fecha_cierre']) . "</span>";
                                        echo "<br/>";
                                        if ($curso['admin_cierre'] == '') {
                                            $admin_cierre = 'Sistema';
                                        } else {
                                            $admin_cierre = $curso['admin_cierre'];
                                        }
                                        echo "<span style='font-size:9pt;color:gray'>" . $admin_cierre . "</span>";
                                        
                                        if ($curso['sw_cierre'] == '0') {
                                            echo "<br/>";
                                            echo "<b style='font-size:15pt;color:#730cbe'>MODIFICADO</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <table class="table table-bordered table-striped">
                                            <?php
                                            $rqddc1 = query("SELECT *,(select nombre from administradores where id=dc.id_administrador)admin_doc_cierre FROM cursos_cierres_documentos dc WHERE id_cierre='" . $curso['id_cierre'] . "' ORDER BY id ASC ");
                                            while ($rqddc2 = mysql_fetch_array($rqddc1)) {
                                                if ($rqddc2['admin_doc_cierre'] == '') {
                                                    $admin_doc_cierre = 'Sistema';
                                                } else {
                                                    $admin_doc_cierre = $rqddc2['admin_doc_cierre'];
                                                }
                                                echo "<tr>";
                                                echo "<td style='background:#FFF !important;'>" . $rqddc2['apartado'] . "</td>";
                                                echo "<td style='background:#FFF !important;'><a href='https://cursos.bo/contenido/archivos/documentos/" . $rqddc2['nombre_archivo'] . "' target='_blank'>" . $rqddc2['codigo'] . "</a></td>";
                                                echo "<td style='background:#FFF !important;'>" . $admin_doc_cierre . "<br/>" . my_date_curso2($rqddc2['fecha']) . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </table>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $url_img_curso = "https://www.infosicoes.com/paginas/" . $curso['imagen'] . ".size=2.img";
                                        $url_img_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                                        //$url_img_curso = "paginas/" . $curso['imagen'] . ".size=2.img";
                                        $url_img_curso = "contenido/imagenes/paginas/" . $curso['imagen'];
                                        if (!file_exists("../../imagenes/paginas/" . $curso['imagen'])) {
                                            $url_img_curso = "https://www.infosicoes.com/paginas/" . $curso['imagen'] . ".size=2.img";
                                        }
                                        ?>
                                        <img src="<?php echo $url_img_curso; ?>" style="height:50px;width:75px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                        <br/><?php echo "<i style='color:gray;font-size: 7pt;'>" . $curso['horarios'] . "</i>"; ?>
                                        <?php
                                        if ($curso['sw_suspendido'] == 1) {
                                            echo "<br/><b style='color:red;font-size: 15pt;'>SUSPENDIDO</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo my_date_curso($curso['fecha']);
                                        if ($curso['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . my_date_curso($curso['fecha2']) . "</i>";
                                        }
                                        if ($curso['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . my_date_curso($curso['fecha3']) . "</i>";
                                        }
                                        if ($curso['fecha'] == date("Y-m-d")) {
                                            echo "<br/><b style='background: #00a500;font-size: 10pt;color: #FFF;padding: 1px 5px;border-radius: 5px;'>HOY</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $curso['costo'] . ' Bs';
                                        if ($curso['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . $curso['costo2'] . " Bs</span>";
                                        }
                                        if ($curso['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . $curso['costo3'] . " Bs</span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo "<span style='font-size:11pt;'>" . ($curso['titulo']) . "</span>";
                                        echo "<br/>";

                                        $rqciudad1 = query("SELECT nombre FROM ciudades WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                        if (mysql_num_rows($rqciudad1) == 0) {
                                            echo "<b style='color:#428bca;'>" . "Sin ciudad registrada" . "</b>";
                                            echo "<br/>";
                                        } else {
                                            $rqciudad2 = mysql_fetch_array($rqciudad1);
                                            echo "<b style='color:#428bca;'>" . strtoupper($rqciudad2['nombre']) . "</b>";
                                            echo "<br/>";
                                        }
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>Docente: " . utf8_encode($curso['docente']) . "</i>";
                                        ?>
                                        <br/>
                                        <?php
                                        $rqciudad1 = query("SELECT nombre,salon FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' LIMIT 1 ");
                                        if (mysql_num_rows($rqciudad1) == 0) {
                                            echo "Sin dato registrado";
                                        } else {
                                            $rqciudad2 = mysql_fetch_array($rqciudad1);
                                            echo $rqciudad2['nombre'] . " &nbsp; <i style='color:gray;'>" . $rqciudad2['salon'] . "</i>";
                                        }
                                        ?>
                                    </td>
                                    <?php if ($nivel_administrador == '1' || isset_organizador()) { ?>
                                        <td class="visible-lgNOT" id="box-datapart-<?php echo $curso['id']; ?>" style="min-height: 130px;">
                                            <div style="width:120px;">
                                                <div style="float:left;width:35px;text-align:center;padding-top:20px;">
                                                    <b style="font-size:12pt;color:#00789f;">..</b>
                                                </div>
                                                <div style="float:left;width:85px;">
                                                    <span style="font-size:8pt;color:gray;">. transferencia</span>
                                                    <br/>
                                                    <span style="font-size:8pt;color:gray;">. oficina</span>
                                                    <br/>
                                                    <span style="font-size:8pt;color:gray;">. khipu</span>
                                                    <br/>
                                                    <span style="font-size:8pt;color:gray;">. dia del curso</span>
                                                    <br>
                                                    <span style="font-size:8pt;color:gray;">. deposito</span>
                                                    <br>
                                                    <span style="font-size:8pt;color:gray;">. tigomoney</span>
                                                    <br>
                                                    <span style="font-size:8pt;color:gray;">. sin pago</span>
                                                </div>
                                                <div style="clear:both;"></div>
                                            </div>
                                            <script>data_participantes('<?php echo $curso['id']; ?>');</script>
                                        </td>
                                    <?php } ?>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a href="cursos-editar/<?php echo $curso['id']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-edit'></i> Editar</a>
                                        <a <?php echo loadpage('cursos-participantes/' . $curso['id']); ?> class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-users'></i> Inscritos</a>
                                        <a data-toggle="modal" data-target="#MODAL-generar-reporte" onclick="reporte_cierre_p1('<?php echo $curso['id']; ?>');" class="btn btn-xs btn-info btn-block" style="color: #FFF;"><i class='gi gi-cogwheel'></i> Reporte</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <?php
                            $urlget3 = '';
                            /*
                            $urlget4 = '';
                            if (isset($busqueda)) {
                                $urlget3 = '/' . $busqueda;
                                if (isset($id_ciudad)) {
                                    $urlget4 = '/' . $id_ciudad;
                                }
                            }
                            */
                            
                            /* get 3 */
                            if(isset($get[3])){
                                $urlget3 .= '/'.$get[3];
                            }
                            /* get 4 */
                            if(isset($get[4])){
                                $urlget3 .= '/'.$get[4];
                            }
                            ?>

                            <li><a <?php echo loadpage('cursos-cierres/1' . $urlget3); ?>>Primero</a></li>                           
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
                                        echo '<li class="active"><a ' . loadpage('cursos-cierres/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a ' . loadpage('cursos-cierres/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a <?php echo loadpage('cursos-cierres/' . $total_cursos . $urlget3); ?>>Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='contenido/imagenes/images/loader.gif'/></div>";
</script>

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>

<!-- historial_curso -->
<script>
    function historial_curso(id_curso) {
        $("#AJAXCONTENT-historial_curso").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.historial_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_curso").html(data);
            }
        });
    }
</script>

<script>
    function reporte_cierre_p1(id_curso) {
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.reporte_cierre_p1.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-reporte_cierre").html(data);
            }
        });
    }
    function reporte_cierre_p2(dat) {
        var data_form = $("#FORM-reporte_cierre").serialize();
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.reporte_cierre_p2.php?dat=' + dat,
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-reporte_cierre").html(data);
            }
        });
    }
</script>



<!-- MODAL historial_curso -->
<div id="MODAL-historial_curso" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_curso"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- AJAX CONTENT -->
                        <div id="AJAXBOX-reporte_cierre"></div>
                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->




<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}

function my_date_curso2($dat) {
    if ($dat == '0000-00-00 00:00:00') {
        return "00 Mes 00";
    } else {
        $ar1a = explode(' ', $dat);
        $ar1 = explode('-', $ar1a[0]);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1a[1], 0, 5);
    }
}
