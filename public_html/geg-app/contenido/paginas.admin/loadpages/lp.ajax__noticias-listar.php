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
$htm_titlepage = 'LISTADO DE CURSOS';
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
    $rqdde2 = mysql_fetch_array($rqdde1);
    $htm_titlepage = "CURSOS " . strtoupper($rqdde2['nombre']) . " : <a href='https://cursos.bo/cursos-en-" . $rqdde2['titulo_identificador'] . ".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>https://cursos.bo/cursos-en-" . $rqdde2['titulo_identificador'] . ".html</a>";
}

if (isset_post('realizar-busqueda')) {
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
        /* fecha nula */
        $rqdcd1 = query("SELECT fecha,numero FROM cursos WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        $rqdcd2 = mysql_fetch_array($rqdcd1);
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


/* registros */
$data_required = "
*
";

$resultado1 = query("SELECT $data_required FROM publicaciones d WHERE 1 $qr_busqueda ORDER BY d.id ASC LIMIT $start,$registros_a_mostrar");
$total_registros = mysql_num_rows($resultado1);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="hidden-lg">
    <?php
    include_once '../../paginas.admin/items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../../paginas.admin/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="row" style="padding: 10px 0px;">
            <div class="col-md-12">
                <h3 style="padding: 0px; margin: 0px; padding-top: 5px;">
                    LISTADO DE NOTICIAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> 
                </h3>
                <blockquote class="page-information hidden">
                    <p>
                        Listado de noticias.
                    </p>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<?php echo $mensaje; ?>

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
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-bodyNOT">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;width:100px;">Img.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">T&iacute;tulo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Url</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Reproducciones</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($noticia = mysql_fetch_array($resultado1)) {
                                    ?>
                                    <tr>
                                        <td class="visible-lgNOT">
                                            <?php echo $cnt++; ?>
                                            <br/>
                                            <br/>
                                            <b class="btn btn-default" onclick="historial_noticia('<?php echo $noticia['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_noticia"><i class="fa fa-list"></i></b>
                                        </td>
                                        <td class="visible-lgNOT">
                                            <?php
                                            if($noticia['imagen']!==''){
                                            ?>
                                                <img src="<?php echo "contenido/imagenes/noticias/" . $noticia['imagen']; ?>" style="height:70px;width:70px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                            <?php
                                            }else{
                                                echo "---";
                                            }
                                            ?>
                                        </td>
                                        <td class="visible-lgNOT" style="vertical-align: middle;">
                                            <b style="font-size:20pt;font-weight:bold;color:#00789f;"><?php echo $noticia['titulo']; ?></b>
                                        </td>
                                        <td class="visible-lgNOT">
                                            <?php
                                            /* url */
                                            $url_corta = 'https://cursos.bo/cursos-en-' . $noticia['titulo_identificador'] . '.html';
                                            echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                            ?>
                                        </td>
                                        <td class="visible-lgNOT">
                                            <?php
                                            echo "<b class='text-warning'>".$noticia['cnt_reproducciones']." vistas</b>";
                                            ?>
                                        </td>
                                        <td class="visible-lgNOT" style="width:120px;">
                                            <a href="<?php echo $noticia['titulo_identificador']; ?>.html" class="btn btn-xs btn-default btn-block" target="_blank">
                                                <i class='fa fa-eye'></i> Visualizar
                                            </a>
                                            <a href="noticias-editar/<?php echo $noticia['id']; ?>.adm" class="btn btn-xs btn-info btn-block">
                                                <i class='fa fa-edit'></i> Editar
                                            </a>
                                            
                                            <a onclick="notificar_noticia('<?php echo $noticia['id']; ?>');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_noticia">
                                                <i class='fa fa-send'></i> Notificar
                                            </a>
                                           
                                        </td>
                                    </tr>
                                    <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='contenido/imagenes/images/loader.gif'/></div>";
</script>


<script>
    function notificar_noticia(id_noticia) {
        $("#AJAXCONTENT-notificar_noticia").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.noticias-listar.notificar_noticia.php',
            data: {id_noticia: id_noticia},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-notificar_noticia").html(data);
                actualiza_segmento_de_notificacion();
            }
        });
    }
</script>
<script>
    function actualiza_segmento_de_notificacion() {
        $("#AJAXCONTENT-actualiza_segmento_de_notificacion").html('Cargando...');
        var arraydata = $("#form-segmento-notificacion").serialize();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.noticias-listar.actualiza_segmento_de_notificacion.php',
            data: arraydata,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-actualiza_segmento_de_notificacion").html(data);
            }
        });
    }
</script>
<script>
    function enviar_notificacion_noticia(dat, bloque) {
        var title = $("textarea#texto-push").val();
        $("#boxprocess").addClass("boxproceswake");
        sw_opencloseboxprocess = false;
        opencloseboxprocess();
        var d = new Date();
        var idtime = 'process-' + d.getTime();
        var cont = '<div class="box-process" id="' + idtime + '">' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<div class="loader"></div>' +
                '</div>' +
                '<div class="col-md-9">' +
                '<h3>Procesando notificaciones...</h3>' +
                '</div>' +
                '</div>' +
                '</div>'
                ;
        $("#process-container").append(cont);
        $("#btn-" + dat + "-" + bloque).attr("disabled", true);
        $("#btn-" + dat + "-" + bloque).html("Enviando bloque " + bloque);
        //$("#boxsend-" + dat).html('Enviando...');
        var arraydata = $("#form-segmento-notificacion").serialize();
        $.ajax({
            url: 'https://www.cursos.bo/contenido/paginas.admin/ajax/ajax.noticias-listar.enviar_notificacion_noticia.php?modenv=' + dat + '&bloque=' + bloque + '&ahdmd=<?php echo base64_encode($id_administrador); ?>&title=' + encodeURI(title),
            data: arraydata,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#" + idtime).html(data);
            }
        });
    }
</script>


<!-- historial_noticia -->
<script>
    function historial_noticia(id_noticia) {
        $("#AJAXCONTENT-historial_noticia").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.noticias-listar.historial_noticia.php',
            data: {id_noticia: id_noticia},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_noticia").html(data);
            }
        });
    }
</script>

<!-- MODAL notificar_noticia -->
<div id="MODAL-notificar_noticia" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NOTIFICACI&Oacute;N DE CURSOS</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-notificar_noticia"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL historial_noticia -->
<div id="MODAL-historial_noticia" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_noticia"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- show_metricas -->
<script>
    function show_metricas(id_departamento) {
        $("#AJAXCONTENT-show_metricas").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-departamentos.show_metricas.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-show_metricas").html(data);
            }
        });
    }
</script>
<!-- Modal show_metricas-->
<div id="MODAL-show_metricas" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">METRICAS</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-show_metricas"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php

function my_date_curso($dat) {
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $d . " " . $arraymes[(int) $m];
    }
}
