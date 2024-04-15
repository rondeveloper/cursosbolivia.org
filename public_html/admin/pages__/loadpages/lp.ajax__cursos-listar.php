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
$htm_titlepage = 'LISTADO DE CURSOS';
$qr_busqueda = "";
$qr_fecha = "";
$qr_estado = "";
$qr_departamento = "";
$qr_lugar = "";
$qr_modalidad = "";
$qr_docente = "";
$qr_tienda = " AND c.sw_tienda=0 ";
$_01_cvirt = '0';
$sw_search_general = true;
$qr_ids = " 1 ";

/* estados de curso (+cursos hoy) */
if (isset($get[4])) {
    switch ($get[4]) {
        case 'temporales':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='temporal' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - TEMPORALES";
            $sw_search_general = false;
            break;
        case 'activos':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='activo' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - ACTIVOS";
            $sw_search_general = false;
            break;
        case 'hoy':
            $qr_modalidad = "";
            $qr_estado = " AND c.estado IN (1) AND c.fecha=CURDATE() ";
            $txt_estado = " - HOY";
            $sw_search_general = false;
            break;
        case 'virtual':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='virtual' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - VIRTUALES";
            $sw_search_general = false;
            $_01_cvirt = '1';
            break;
        case 'presencial':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='presencial' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - PRESENCIALES";
            $sw_search_general = false;
            break;
        case 'pregrabado':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='pregrabado' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - PREGRABADOS";
            $sw_search_general = false;
            break;
        case 'en-vivo':
            $rqids1 = query("SELECT ids FROM ids_bloques WHERE codigo='en-vivo' LIMIT 1 ");
            $rqids2 = fetch($rqids1);
            $qr_ids = " c.id IN (".$rqids2['ids'].")";
            $txt_estado = " - SESIONES EN VIVO";
            $sw_search_general = false;
            break;
        case 'sincierre':
            $qr_modalidad = "";
            $qr_estado = " AND c.estado IN (0,1,2) AND c.sw_cierre='0' AND c.id_cierre='0' ";
            $txt_estado = " - SIN CIERRE";
            $sw_search_general = false;
            break;
        case 'mis-cursos':
            $id_administrador = administrador('id');
            $rqmcqr_a1 = query("SELECT ids FROM ids_bloques WHERE id IN (1,2) ");
            $rqmcqr_a2 = fetch($rqmcqr_a1);
            $ids_a = $rqmcqr_a2['ids'];
            $rqmcqr_a2 = fetch($rqmcqr_a1);
            $ids_b = $rqmcqr_a2['ids'];
            $qr_ids = " c.id IN (SELECT id_curso AS total FROM cursos_rel_cursowapnum WHERE ( id_curso IN ($ids_a) OR id_curso IN ($ids_b) ) AND id_whats_numero IN (select id from whatsapp_numeros where id_administrador='$id_administrador') )";
            $txt_estado = " - MIS CURSOS";
            $sw_search_general = false;
            break;
        case 'por-activar':
            $rqmcqr_a1 = query("SELECT ids FROM ids_bloques WHERE id IN (1,2) ");
            $rqmcqr_a2 = fetch($rqmcqr_a1);
            $ids_a = $rqmcqr_a2['ids'];
            $rqmcqr_a2 = fetch($rqmcqr_a1);
            $ids_b = $rqmcqr_a2['ids'];
            $ids_ab = $ids_a.','.$ids_b;
            $ids_p_ac = '0';
            $rqdcv1 = query("SELECT id FROM cursos c WHERE id IN ($ids_ab) AND ( (select count(1) from cursos_rel_cursoonlinecourse where id_curso=c.id)>0 OR (select count(1) from sesiones_zoom where id_curso=c.id)>0 )");
            while($rqdcv2 = fetch($rqdcv1)){
                $id_ob = $rqdcv2['id'];
                $rqvecv1 = query("SELECT id FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_ob' ORDER BY id DESC limit 1 ");
                if(num_rows($rqvecv1)>0){
                    $rqdpffs1 = query("SELECT cp.id,cp.id_usuario FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_ob' AND cp.estado='1' AND pr.sw_pago_enviado='1' AND cp.id_usuario='0' ORDER BY cp.id DESC limit 1 ");
                    if(num_rows($rqdpffs1)>0){
                        $ids_p_ac .= ','.$id_ob;
                    }
                }else{
                    $rqvsz1 = query("SELECT id FROM sesiones_zoom WHERE id_curso='$id_ob' AND estado=1 ");
                    if(num_rows($rqvsz1)>0){
                        $rqdpffs1 = query("SELECT cp.id FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_ob' AND cp.estado='1' AND pr.sw_pago_enviado='1' AND cp.id NOT IN (select id_participante from rel_partszoom where id_curso='$id_ob') LIMIT 1 ");
                        if(num_rows($rqdpffs1)>0){
                            $ids_p_ac .= ','.$id_ob;
                        }
                    }
                }
            }
            $qr_ids = " c.id IN ($ids_p_ac)";
            $txt_estado = " - PARTICIPANTES POR ACTIVAR";
            $sw_search_general = false;
            break;
        default:
            if(substr($get[4],0,2)=='ID'){
                $aux_id_curso = str_replace('ID','',$get[4]);
                $qr_ids = " c.id='$aux_id_curso' ";
                $sw_search_general = false;
            }
            if(substr($get[4],0,5)=='resp_'){
                $aux_id_responsable = str_replace('resp_','',$get[4]);
                $qr_ids = " c.id IN (select id_curso from cursos_rel_cursowapnum where id_whats_numero='$aux_id_responsable' ) ";
                $sw_search_general = false;
            }
            break;
    }
}

/* get departamento */
if (isset($get[5]) && false ) {
    $id_departamento = abs((int) ($get[5]));
    $qr_departamento = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
    $rqdde1 = query("SELECT nombre,titulo_identificador FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
    $rqdde2 = fetch($rqdde1);
    $htm_titlepage = "CURSOS ".strtoupper($rqdde2['nombre'])." : <a href='".$dominio."cursos-en-".$rqdde2['titulo_identificador'].".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-en-".$rqdde2['titulo_identificador'].".html</a>";
    $htm_titlepage .= " | <a ".loadpage('cursos-infoact/1/no-search/todos/'.$id_departamento.'.adm')." class='btn btn-xs btn-default'>DATA-INFO</a>";
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

    /* lugar */
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
$registros_a_mostrar = 15;
if(isset_post('registros_pagina')){
    $registros_a_mostrar = (int)post('registros_pagina');
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


/* registros */
$data_required = "
c.sw_suspendido,c.imagen,c.horarios,c.sw_cierre,c.id_cierre,c.id_modalidad,c.fecha,c.sw_fecha2,c.fecha2,c.sw_fecha3,c.fecha3,c.costo,c.costo2,c.costo3,c.titulo,c.short_link,c.numero,c.id_certificado,c.cnt_reproducciones,c.columna,c.titulo_identificador,c.sw_tienda,
c.laststch_fecha,c.laststch_id_administrador,
(cd.nombre)dr_nombre_ciudad,
(c.id)dr_id_curso,
(c.estado)dr_estado_curso,
(md.nombre)dr_modalidad_curso,
(dc.nombres)dr_docente_curso
";

$resultado1 = query("SELECT $data_required 
FROM cursos c 
LEFT JOIN ciudades cd ON c.id_ciudad=cd.id 
LEFT JOIN cursos_modalidades md ON c.id_modalidad=md.id 
LEFT JOIN cursos_docentes dc ON c.id_docente=dc.id 
WHERE $qr_ids $qr_busqueda $qr_estado $qr_departamento $qr_fecha $qr_lugar $qr_modalidad $qr_docente $qr_tienda 
ORDER BY c.fecha DESC,c.id_ciudad ASC,c.id DESC 
LIMIT $start,$registros_a_mostrar");

$resultado2 = query("SELECT count(*) AS total 
FROM cursos c 
WHERE $qr_ids $qr_busqueda $qr_estado $qr_departamento $qr_fecha $qr_lugar $qr_modalidad $qr_docente $qr_tienda ");

$resultado2b = fetch($resultado2);
$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
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
                <h3 class="titulo-head-principal">
                    <?php echo $htm_titlepage.$txt_estado; ?>
                </h3>
            </div>
        </div>

        <?php if($sw_search_general){ ?>
            <form action="cursos-listar.adm" method="post">
            <div class="row" style="background: #99ccec;padding: 5px 0px;">
                <div class="col-md-4 col-xs-8">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Buscar por titulo / Numeraci&oacute;n / ID ..."/>
                        <span class="input-group-addon hidden-sm" data-toggle="collapse" data-target="#boxsearch2" style="cursor:pointer"><i class="fa fa-download"></i></span>
                    </div>
                </div>
                <div class="col-md-3 hidden-sm">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Fecha inicio: </span>
                        <input type="date" name="fecha" value="<?php echo $fecha_busqueda; ?>" class="form-control" placeholder="Fecha de inicio..." onchange="$('#_fecha_final').val(this.value);"/>
                    </div>
                </div>
                <div class="col-md-3 hidden-sm">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon">Fecha final: </span>
                        <input type="date" name="fecha_final" value="<?php echo $fecha_final_busqueda; ?>" class="form-control" placeholder="Fecha final..." id="_fecha_final"/>
                    </div>
                </div>
                <div class="col-md-2 col-xs-4">
                    <input type="submit" name="realizar-busqueda" value="BUSCAR" class="btn btn-warning btn-block active" style="background: #44ab39;border: 1px solid #FFF;"/>
                </div>
            </div>
            <div id="boxsearch2" class="collapse">
                <div class="row" style="background: #99ccec;padding: 5px 0px;">
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
                <div class="row" style="background: #99ccec;padding: 5px 0px;">
                    <div class="col-md-4">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon">Docente: </span>
                            <select class="form-control" name="id_docente">
                                <?php
                                echo "<option value='0'>Todos los docentes...</option>";
                                $rqd1 = query("SELECT id,nombres,prefijo FROM cursos_docentes WHERE estado='1' ORDER BY nombres ASC ");
                                while ($rqd2 = fetch($rqd1)) {
                                    $selected = '';
                                    if($id_docente==$rqd2['id']){
                                        $selected = ' selected="selected" ';
                                    }
                                    echo "<option value='" . $rqd2['id'] . "' $selected>" . $rqd2['prefijo'] . ' ' . $rqd2['nombres'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon">Eliminados/susp: </span>
                            <select class="form-control" name="inluir_eliminados">
                                <option value='0'>No incluir eliminados/suspendidos</option>
                                <option value='1'>Incluir eliminados/suspendidos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon">Registros/p&aacute;gina: </span>
                            <input type="text" name="registros_pagina" value="<?php echo $registros_a_mostrar; ?>" class="form-control" placeholder="Nro. de registros a mostrar..."/>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="cvirt" value="<?php echo $_01_cvirt; ?>"/>
        </form>
        <?php } ?>
        
        <?php 
        $aux_sw_virt = 'normal';
        if ($_01_cvirt == '1') {
            $aux_sw_virt = 'virtual';
        }
        ?>
        <div class="row">
            <div class="col-md-2 hidden-xs">
                <?php
                if ($_01_cvirt == '1') {
                    /* actualiza avance */
                    $flag_point = date("Y-m-d H:i:s",strtotime('-1 hour'));
                    $rqvlecav1 = query("SELECT id,flag,segundos FROM cursos_onlinecourse_lec_avance WHERE flag>'$flag_point' ");
                    echo '<h4 style="color: white;
                    font-weight: bold;
                    background: #9a38cc;
                    text-align: center;
                    padding: 5px 7px;
                    border-radius: 10px;
                    font-size: 14pt;">'.num_rows($rqvlecav1).' en leccion online</h4>';
                }
                ?>
            </div>
            <div class="col-md-8" style="margin-top: 12px;">
                <table class="table table-bordered hidden-xs" style="background: #f9f9f9;">
                    <tr>
                        <td class="hidden-xs" style="width: 110px;vertical-align: middle;font-weight: bold;font-size: 11pt;color: #3885b7;">BUSCAR:</td>
                        <td><input type="text" class="form-control" id="input-busca-participante"/></td>
                        <td style="width: 200px;"><b class="btn btn-block btn-info" onclick="buscar_participante('<?php echo $aux_sw_virt; ?>');">PARTICIPANTE</b></td>
                        <td style="width: 200px;"><b class="btn btn-block btn-warning" onclick="buscar_curso();">CURSO</b></td>
                    </tr>
                </table>
                <div class="hidden-sm hidden-md hidden-lg" style="background: #f9f9f9;padding: 10px;border: 1px solid #abd8ab;line-height: 0.7;">
                    <input type="text" class="form-control" id="input-busca-participante-movil" style="height: auto;font-size: 11pt;padding: 12px 10px;" placeholder="..."/>
                    <br>
                    <b class="btn btn-xs btn-block btn-info" onclick="buscar_participante('<?php echo $aux_sw_virt; ?>');">PARTICIPANTE</b>
                    <br>
                    <b class="btn btn-xs btn-block btn-warning" onclick="buscar_curso();">CURSO</b>
                </div>
            </div>
        </div>
        <div id="AJAXCONTENT-buscar_participante"></div>        
    </div>
</div>


<?php echo $mensaje; ?>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-listar.data_participantes.php',
            data: {id_curso: id_curso,hash: '<?php echo md5(md5("7".$ip_coneccion."d".date("Y-m-d H")."0".$user_agent)); ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
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
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-bodyNOT">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="" style="font-size:10pt;">#</th>
                                <th class="hidden-sm" style="font-size:10pt;width:100px;">&nbsp;</th>
                                <th class="" style="font-size:10pt;" colspan="2">Curso</th>
                                <?php if ($nivel_administrador == '1') { ?>
                                    <th class="" style="font-size:10pt;">Registrados</th>
                                <?php } ?>
                                <th class="" style="font-size:10pt;">Estado</th>
                                <th class="" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($curso = fetch($resultado1)) {
                                $tr_class = '';
                                /* curso suspendido */
                                if ($curso['sw_suspendido'] == 1) {
                                    $tr_class .= ' tr_curso_suspendido';
                                }
                                /* curso eliminado */
                                if ($curso['dr_estado_curso'] == 3) {
                                    $tr_class .= ' tr_curso_eliminado';
                                }
                                $url_img_curso = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen'];
                                $modalidad_del_curso = $curso['dr_modalidad_curso'];
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default btn-sm" onclick="historial_curso('<?php echo $curso['dr_id_curso']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                        <div class="hidden-md hidden-lg" style="width: 65px;overflow: hidden;white-space: initial;">
                                            <br>
                                            <a data-toggle="modal" data-target="#MODAL-show_facebook_post" onclick="show_facebook_post('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;">
                                                <img src="<?php echo $url_img_curso; ?>" style="height:40px;width:65px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                            </a>
                                        </div>
                                        <br>
                                        <br>
                                        <b class="btn btn-default btn-sm" onclick="generador_post('<?php echo $curso['dr_id_curso']; ?>');"><i class="fa fa-flag"></i></b>
                                    </td>
                                    <td class="hidden-sm">
                                        <div style="font-weight: bold;color: #ff6161;font-size: 15pt;border: 1px solid #ffb8b8;text-align: center;background: white;">
                                            <span id="id-curso-<?php echo $curso['dr_id_curso']; ?>"><?php echo $curso['dr_id_curso']; ?></span>
                                            <b class="btn btn-xs btn-default" onclick="copyToClipboard('id-curso-<?php echo $curso['dr_id_curso']; ?>');" style="color:red;padding: 0px 3px;font-size: 8pt;"><i class="fa fa-copy"></i></b>
                                        </div>
                                        <?php 
                                        if ($curso['numero'] == '0') {
                                            echo "<span style='color:#d7d7d7;font-size:8pt;'>Sin numeraci&oacute;n</span>";
                                        } else {
                                            echo "<span style='color:gray;font-size:8pt;'>Numeraci&oacute;n: <b style='color:#394263;font-size:9pt;'>" . $curso['numero'] . "</b>&nbsp; </span>";
                                        }
                                        ?>
                                        <a data-toggle="modal" data-target="#MODAL-show_facebook_post" onclick="show_facebook_post('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;">
                                            <img src="<?php echo $url_img_curso; ?>" style="height: 50px;width: 75px;overflow: hidden;border-radius: 7px;opacity: .8;margin: 10px 0px;border: 1px solid #7abce7;padding: 1px;"/>
                                        </a>
                                        <?php
                                        if ($curso['sw_suspendido'] == 1) {
                                            echo "<br/><b style='color:red;font-size: 15pt;'>SUSPENDIDO</b>";
                                        }
                                        if ($curso['sw_cierre'] == 1) {
                                            echo "<br/><b style='color:gray;font-size: 12pt;'>CERRADO</b>";
                                        }
                                        if ($curso['id_cierre'] !== '0' && $curso['sw_cierre'] == 0) {
                                            echo "<br/><b style='color:#730cbe;font-size: 12pt;'>MODIFICADO</b>";
                                        }
                                        if ($curso['dr_estado_curso'] == 3) {
                                            echo "<br/><b style='color:red;font-size: 12pt;'>ELIMINADO</b>";
                                        }
                                        ?>
                                        <div style='margin-top: 10px;font-size: 7pt;width: 100%;background: #eaedf1;text-align: center;border: 1px solid #adadad;border-radius: 5px;padding: 2px 5px;'><?php echo $modalidad_del_curso; ?></div>
                                    </td>
                                    <td class="">
                                        <?php
                                        echo '<div class="hidden-xs" style="font-size: 11pt;"><b class="btn btn-xs btn-default" onclick="copyToClipboard(\'titulo-curso-'.$curso['dr_id_curso'].'\');"><i class="fa fa-copy"></i></b> &nbsp; <span id="titulo-curso-'.$curso['dr_id_curso'].'" style="color: #0c67aa;">'.$curso['titulo'].'</span></div>';
                                        echo '<div class="hidden-sm hidden-lg hidden-md" style="width: 170px;font-size:9pt;white-space: initial;"><b class="btn btn-xs btn-default" onclick="copyToClipboard(\'titulo-curso-'.$curso['dr_id_curso'].'\');"><i class="fa fa-copy"></i></b> &nbsp; '.$curso['titulo'].'</div>';
                                        
                                        echo "<i style='color:gray;font-size: 7pt;'>" . $curso['horarios'] . "</i> &nbsp; <b>" . fecha_curso_d_m_Y($curso['fecha']) . "</b><br>";

                                        echo "<br/>";
                                        if ($nivel_administrador == '1') {
                                            /* url_corta */
                                            $url_corta = $dominio . numIDcurso($curso['dr_id_curso']) . '/';
                                            $url_corta_registro = $dominio . 'R/'.$curso['dr_id_curso'].'/';
                                            $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$curso['dr_id_curso']."' AND r.estado=1 ");
                                            if(num_rows($rqenc1)>0){
                                                $rqenc2 = fetch($rqenc1);
                                                $url_corta = $dominio.$rqenc2['enlace'] . "/";
                                                $url_corta_registro = $dominio.'R/'.$rqenc2['enlace'] . "/";
                                            }
                                            echo '<b class="btn btn-xs btn-default" onclick="copiar_info_curso('.$curso['dr_id_curso'].');"><i class="fa fa-copy"></i></b> &nbsp; ';
                                            echo "<i class='btn btn-danger active btn-xs' onclick='copyToClipboard(\"cu".$curso['dr_id_curso']."\")' id='cu".$curso['dr_id_curso']."'>" . $url_corta . "</i>";
                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                            echo "<i class='btn btn-warning active btn-xs' onclick='copyToClipboard(\"cureg".$curso['dr_id_curso']."\")' id='cureg".$curso['dr_id_curso']."'>".$url_corta_registro."</i><br>";
                                        }
                                        ?>
                                        <br>
                                        <div>
                                            <?php
                                            if($curso['dr_docente_curso']!=''){
                                                echo '<i style="font-size:9pt;color:gray;">Docente:</i> '.$curso['dr_docente_curso'].'<br>';
                                            }

                                            /* soporte */
                                            echo '<i style="font-size:9pt;color:gray;">Soporte:</i>';
                                            $rqdsw1 = query("SELECT w.id,w.responsable,w.numero FROM cursos_rel_cursowapnum r INNER JOIN whatsapp_numeros w ON r.id_whats_numero=w.id WHERE r.id_curso='".$curso['dr_id_curso']."' ");
                                            while($rqdsw2 = fetch($rqdsw1)){
                                                echo '<span id="link-wap-soporte-'.$curso['dr_id_curso'].'-'.$rqdsw2['numero'].'" style="display:none;">https://wa.me/591'.$rqdsw2['numero'].'</span>';
                                                echo '<br><b class="btn btn-xs btn-default" onclick="copyToClipboard(\'link-wap-soporte-'.$curso['dr_id_curso'].'-'.$rqdsw2['numero'].'\');"><i class="fa fa-copy"></i></b> &nbsp; <a style="font-weight:bold;color: #0a7a94;cursor:pointer;" '.loadpage('cursos-listar/1/no-search/resp_'.$rqdsw2['id']).'>- '.$rqdsw2['responsable'].'</a> <a href="https://api.whatsapp.com/send?phone=591'.$rqdsw2['numero'].'&text=" target="_blank"><i>('.$rqdsw2['numero'].')</i></a>';
                                            }
                                            ?>
                                        </div>
                                        
                                        <?php if($id_administrador==11 || $id_administrador==10 || $id_administrador==16){ ?>
                                        <div class="text-right" id="ajaxbox-info-recaudacion-<?php echo $curso['dr_id_curso']; ?>">
                                            <b class="btn btn-md btn-default" onclick="info_recaudacion('<?php echo $curso['dr_id_curso']; ?>');">Ver recaudaci&oacute;n</b>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        if ($curso['costo']==0) {
                                            ?>
                                            <h4>CURSO GRATUITO</h4>
                                            <br>
                                            <b><i>Fecha</i></b>
                                            <br>
                                            <?php
                                            echo my_date_curso($curso['fecha']);
                                            ?>
                                            <br>
                                            <br>
                                        <?php 
                                        } else {
                                        ?>
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <b><i>Fecha</i></b>
                                                        <br>
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
                                                    <td>
                                                    <b><i>Costo</i></b>
                                                        <br>
                                                        <?php
                                                        echo "<b style='font-size:9pt;color:#0c67a2;'>".$curso['costo']. "</b><i style='color:gray;font-size: 7pt;'>_Bs</span>";
                                                        if ($curso['sw_fecha2'] == '1') {
                                                            echo "<br/>";
                                                            echo "<b style='font-size:9pt;color:#0c67a2;'>" . $curso['costo2'] . "</b><i style='color:gray;font-size: 7pt;'>_Bs</span>";
                                                        }
                                                        if ($curso['sw_fecha3'] == '1') {
                                                            echo "<br/>";
                                                            echo "<b style='font-size:9pt;color:#0c67a2;'>" . $curso['costo3'] . "</b><i style='color:gray;font-size: 7pt;'>_Bs</span>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php 
                                        }
                                        ?>
                                        <?php
                                        echo $curso['dr_nombre_ciudad'];
                                        if ($curso['id_modalidad'] != '1') {
                                            echo "<div style='color:red;text-align:center;padding:2px 0px 5px 0px;font-size:11pt;'>";
                                            echo "<b><i class='fa fa-cloud'></i><br/>VIRTUAL</b>";
                                            echo "</div>";
                                        }
                                        $rqdcad1 = query("SELECT COUNT(1) FROM cursos_rel_cursocertificado WHERE id_curso='".$curso['dr_id_curso']."' ORDER BY id DESC limit 1 ");
                                        if ((num_rows($rqdcad1)==0)) {
                                            echo "<div><b style='background: gray;color: white;padding: 2px 5px;border-radius: 5px;font-size: 7pt;'>Sin CERT</b></div>";
                                        } else {
                                            echo "<div><b style='background: #27ae60;color: white;padding: 2px 5px;border-radius: 5px;font-size: 7pt;'>Con CERT</b></div>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($nivel_administrador == '1') { ?>
                                            <div style="background: white;border: 1px solid #c3c3c3;text-align: center;border-radius: 10px;margin-bottom: 7px;"><b><?php echo $curso['cnt_reproducciones']; ?></b> <i style="color:gray;">vistas</i></div>
                                        <?php } ?>
                                        <div id="box-datapart-<?php echo $curso['dr_id_curso']; ?>" style="min-height: 130px;">
                                            <script>data_participantes('<?php echo $curso['dr_id_curso']; ?>');</script>
                                        </div>
                                        <div id="box-datapart2-<?php echo $curso['dr_id_curso']; ?>" style="margin: 5px 0px;border: 1px solid #d0d0d0;text-align: center;"></div>
                                        <div id="box-datapart3-<?php echo $curso['dr_id_curso']; ?>"></div>
                                    </td>
                                    <td class="" id="td-estado-<?php echo $curso['dr_id_curso']; ?>">
                                        <?php
                                        if($curso['sw_tienda'] == '1') {
                                            echo '<b style="background: #3e83d9;color: white;padding: 2px 5px;border-radius: 5px;font-size: 7pt;">TIENDA</b>';
                                        } else if ($curso['dr_estado_curso'] !== '3') {
                                            if ($curso['dr_estado_curso'] == '1') {
                                                ?>
                                                <b style='color:green;'>ACTIVADO</b>
                                                <br/><br/>
                                                <?php
                                                if($curso['laststch_fecha']!=='0000-00-00 00:00:00'){
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='".$curso['laststch_id_administrador']."' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>'.date("H:i",  strtotime($curso['laststch_fecha'])).'</b> &nbsp; '.date("d/M",  strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
                                                if (acceso_cod('adm-cursos-estado')) {
                                                    ?>
                                                    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                    <br/>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'temporal');">Temporal</i>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'desactivado');">Desactivado</i>
                                                    <?php
                                                }
                                            } elseif ($curso['dr_estado_curso'] == '2') {
                                                ?>
                                                <b style='color:red;'>TEMPORAL</b>
                                                <br/>
                                                <br/>
                                                <?php
                                                if($curso['laststch_fecha']!=='0000-00-00 00:00:00'){
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='".$curso['laststch_id_administrador']."' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>'.date("H:i",  strtotime($curso['laststch_fecha'])).'</b> &nbsp; '.date("d/M",  strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
                                                if (acceso_cod('adm-cursos-estado')) {
                                                    ?>
                                                    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                    <br/>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'activado');">Activado</i>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'desactivado');">Desactivado</i>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                DESACTIVADO
                                                <br/>
                                                <br/>
                                                <?php
                                                if($curso['laststch_fecha']!=='0000-00-00 00:00:00'){
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='".$curso['laststch_id_administrador']."' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>'.date("H:i",  strtotime($curso['laststch_fecha'])).'</b> &nbsp; '.date("d/M",  strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
                                                if (acceso_cod('adm-cursos-estado')) {
                                                    ?>
                                                    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                    <br/>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'activado');">Activado</i>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['dr_id_curso']; ?>', 'temporal');">Temporal</i>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="" style="width:120px;">
                                        <?php
                                        if($curso['sw_tienda'] == '1') {
                                            echo '<b style="background: #3e83d9;color: white;padding: 2px 5px;border-radius: 5px;font-size: 7pt;">TIENDA</b>';
                                        } else if ($curso['dr_estado_curso'] !== '3') {
                                            if ($nivel_administrador == '1' || $curso['dr_estado_curso'] == '1' || $curso['dr_estado_curso'] == '2') {
                                                ?>
                                                <a href="<?php echo $dominio.$curso['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-eye'></i> Visualizar</a>
                                                <a href="cursos-editar/<?php echo $curso['dr_id_curso']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-edit'></i> Editar</a>
                                                <a <?php echo loadpage('cursos-participantes/' . $curso['dr_id_curso'].'/no-turn/no-id/conpago'); ?> class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-users'></i> Con pago</a>
                                                <a <?php echo loadpage('cursos-participantes/' . $curso['dr_id_curso'].'/no-turn/no-id/sinpago'); ?> class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-users'></i> Sin pago</a>
                                                <a onclick="interesados_whatsapp('<?php echo $curso['dr_id_curso']; ?>');" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-bell-o'></i> Interesados</a>
                                                <?php
                                            }
                                            ?>
                                            <a onclick="duplicar_curso('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;color: #0089b5;" class="btn btn-xs btn-default btn-block"><i class='fa fa-random'></i> Duplicar</a>
                                            <?php
                                            if (acceso_cod('adm-cursos-eliminar') && $curso['dr_estado_curso'] !== '3') {
                                                ?>
                                                <br/>
                                                <form action="cursos-listar.adm" method="post">
                                                    <input type="hidden" name="id_curso" value="<?php echo $curso['dr_id_curso']; ?>"/>
                                                    <input type="hidden" name="delete-course" value="true"/>
                                                    <button type="submit" style="cursor:pointer;" class="btn btn-danger btn-xs btn-block" onclick="return confirm('Desea eliminar el curso?');">
                                                        <i class='fa fa-ban'></i> Eliminar
                                                    </button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($curso['dr_estado_curso'] == '1') {
                                                ?>
                                                <br/>
                                                <a onclick="notificar_curso('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_curso">
                                                    <i class='fa fa-send'></i> Notificar
                                                </a>
                                                <?php
                                            }
                                            if (isset($get[4]) && $get[4] == 'sincierre') {
                                                ?>
                                                <a data-toggle="modal" data-target="#MODAL-generar-reporte" onclick="reporte_cierre_p1('<?php echo $curso['dr_id_curso']; ?>');" class="btn btn-xs btn-info btn-block" style="color: #FFF;"><i class='gi gi-cogwheel'></i> Reporte</a>
                                                <?php
                                            }
                                        }
                                        ?>
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

                            <li><a <?php echo loadpage('cursos-listar/1' . $urlget3); ?>>Primero</a></li>                           
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
                                        echo '<li class="active"><a ' . loadpage('cursos-listar/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a ' . loadpage('cursos-listar/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a <?php echo loadpage('cursos-listar/' . $total_cursos . $urlget3); ?>>Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
</script>

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
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
            success: function(data) {
                $("#select_lugar").html(data);
            }
        });
    }
</script>


<script>
    function notificar_curso(id_curso) {
        $("#AJAXCONTENT-notificar_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.notificar_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-notificar_curso").html(data);
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
            url: 'pages/ajax/ajax.cursos-listar.actualiza_segmento_de_notificacion.php',
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
    function enviar_notificacion_curso(dat, bloque) {
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
            url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-listar.enviar_notificacion_curso.php?modenv=' + dat + '&bloque=' + bloque + '&ahdmd=<?php echo base64_encode($id_administrador); ?>&title=' + encodeURI(title),
            data: arraydata,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#" + idtime).html(data);
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#td-estado-" + id_curso).html("Actualizando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#td-estado-" + id_curso).html(data);
            }
        });
    }
</script>

<!-- historial_curso -->
<script>
    function historial_curso(id_curso) {
        $("#AJAXCONTENT-historial_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.historial_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_curso").html(data);
            }
        });
    }
</script>

<!-- reporte_cierre -->
<script>
    function reporte_cierre_p1(id_curso) {
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reporte_cierre_p1.php',
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
            url: 'pages/ajax/ajax.cursos-participantes.reporte_cierre_p2.php?dat=' + dat,
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-reporte_cierre").html(data);
            }
        });
    }
</script>

<script>
    function show_facebook_post(id_curso) {
        $("#AJAXCONTENT-show_facebook_post").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.show_facebook_post.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-show_facebook_post").html(data);
            }
        });
    }
</script>

<!-- MODAL show_facebook_post -->
<div id="MODAL-show_facebook_post" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FACEBOOK-POST DE CURSO</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-show_facebook_post"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL notificar_curso -->
<div id="MODAL-notificar_curso" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NOTIFICACI&Oacute;N DE CURSO</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-notificar_curso"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


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


<!-- duplicar_curso -->
<script>
    function duplicar_curso(id_curso) {
        $("#TITLE-modgeneral").html('DUPLICAR CURSO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.duplicar_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<!-- interesados_whatsapp -->
<script>
    function interesados_whatsapp(id_curso) {
        $("#TITLE-modgeneral").html('INTERESADOS WHATSAPP');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.interesados_whatsapp.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<script>
    function buscar_participante(modcourse) {
        $("#AJAXCONTENT-buscar_participante").html("Cargando...");
        let dat = $("#input-busca-participante").val();
        if(dat==''){
            dat = $("#input-busca-participante-movil").val();
        }
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.buscar_participante.php',
            data: {dat: dat, modcourse: modcourse},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-buscar_participante").html(data);
            }
        });
    }
</script>
<script>
    function buscar_curso() {
        $("#AJAXCONTENT-buscar_curso").html("Cargando...");
        let dat = $("#input-busca-participante").val();
        if(dat==''){
            dat = $("#input-busca-participante-movil").val();
        }
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.inicio.buscar_curso.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-buscar_participante").html(data);
            }
        });
    }
</script>

<script>
    function copyToClipboard(phoneid) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById(phoneid).innerHTML;
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
    }
</script>

<div id="CONT-copiar_info_curso" style="display: none;"></div>
<script>
    function copiar_info_curso(id_curso) {
        $("#CONT-copiar_info_curso").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.copiar_info_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#CONT-copiar_info_curso").html(data);
                copyToClipboard("CONT-copiar_info_curso");
                alert('Se ha copiado la informacion del curso al portapapeles (Ctrl + C)');
            }
        });
    }
</script>


<!-- generar_post -->
<script>
    function generador_post(id_curso) {
        $("#TITLE-modgeneral").html('GENERADOR DE POST');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.generador_post.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- info_recaudacion -->
<script>
    function info_recaudacion(id_curso) {
        $("#ajaxbox-info-recaudacion-"+id_curso).html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.info_recaudacion.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxbox-info-recaudacion-"+id_curso).html(data);
            }
        });
    }
</script>


<?php
function my_date_curso($dat) {
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $y = date("Y", strtotime($dat));
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return "<b style='font-size:9pt;color:#0c67a2;'>".$d . "</b><span style='font-size:8pt;color:gray;'>_" . $arraymes[(int) $m]."_$y</span>";
    }
}
/* fecha_curso_d_m_Y */
function fecha_curso_d_m_Y($fecha) {
    $dia = date("d", strtotime($fecha));
    $anio = date("Y", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$dia de $nombremes de $anio";
}
