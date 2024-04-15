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
$htm_titlepage = 'LISTADO DE BLOG\'S';
$qr_busqueda = "";
$qr_fecha = "";
$qr_estado = " AND c.estado IN (0,1,2) AND c.sw_suspendido='0'  ";
$qr_departamento = "";
$qr_docente = "";
$qr_lugar = "";
$qr_modalidad = " AND id_modalidad IN (1) ";

/* estados de curso (+cursos hoy) */
if (isset($get[4])) {
    switch ($get[4]) {
        case 'temporales':
            $qr_modalidad = "";
            $qr_estado = " AND c.estado='2' ";
            $txt_estado = " - TEMPORALES";
            break;
        case 'activos':
            $qr_modalidad = "";
            $qr_estado = " AND c.estado='1' ";
            $txt_estado = " - ACTIVOS";
            break;
        case 'hoy':
            $qr_modalidad = "";
            $qr_estado = " AND c.estado IN (1) AND c.fecha=CURDATE() ";
            $txt_estado = " - HOY";
            break;
        case 'sincierre':
            $qr_modalidad = "";
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
    $htm_titlepage = "CURSOS ".strtoupper($rqdde2['nombre'])." : <a href='".$dominio."cursos-en-".$rqdde2['titulo_identificador'].".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-en-".$rqdde2['titulo_identificador'].".html</a>";
    $htm_titlepage .= " | <a ".loadpage('cursos-infoact/1/no-search/todos/'.$id_departamento.'.adm')." class='btn btn-xs btn-default'>DATA-INFO</a>";
}

/* virtuales */
$_01_cvirt = '0';
if ( (isset($get[5]) && $get[5]=='10') || (isset_post('cvirt')&& post('cvirt')=='1') ) {
    $qr_departamento = "";
    $qr_modalidad = " AND id_modalidad IN (2,3) ";
    $htm_titlepage = "CURSOS VIRTUTALES";
    $_01_cvirt = '1';
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
        $qr_estado = " AND c.estado IN (0,1,2,3) AND c.sw_suspendido IN (0,1) ";
    } else {
        $qr_estado = " AND c.estado IN (0,1,2) AND c.sw_suspendido='0' ";
    }
}

/* registros_pagina */
$registros_a_mostrar = 20;
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
        $rqdcd1 = query("SELECT fecha,numero FROM blog WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        $rqdcd2 = fetch($rqdcd1);
        if ($rqdcd2['numero'] == '0') {
            if ($rqdcd2['fecha'] == (date("Y") . '-12-31')) {
                query("UPDATE blog SET fecha='" . date("Y") . "-01-01' WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
            }
            /* proceso */
            query("UPDATE blog SET estado='3' WHERE id='$id_curso_delete' ORDER BY id DESC ");
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
c.sw_suspendido,c.imagen,c.horarios,c.sw_cierre,c.id_cierre,c.id_modalidad,c.fecha,c.sw_fecha2,c.fecha2,c.sw_fecha3,c.fecha3,c.costo,c.costo2,c.costo3,c.titulo,c.short_link,c.numero,c.id_certificado,c.cnt_reproducciones,c.columna,c.titulo_identificador,
c.laststch_fecha,c.laststch_id_administrador,
(select count(1) from cursos_participantes where id>22000 and id_curso=c.id and estado='1' order by id desc)cnt_participantes_aux,
(concat(d.prefijo,' ',d.nombres))docente,
(cd.nombre)dr_nombre_ciudad,
(l.nombre)dr_nombre_lugar,
(l.salon)dr_nombre_salon,
(c.id)dr_id_curso,
(c.estado)dr_estado_curso
";

$resultado1 = query("SELECT $data_required FROM blog c LEFT JOIN ciudades cd ON c.id_ciudad=cd.id LEFT JOIN cursos_docentes d ON d.id=c.id_docente LEFT JOIN cursos_lugares l ON l.id=c.id_lugar WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_docente $qr_fecha $qr_lugar $qr_modalidad ORDER BY c.fecha DESC,c.id_ciudad ASC,c.id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM blog c WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_docente $qr_fecha $qr_lugar $qr_modalidad ");
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
                <h3 style="padding: 0px; margin: 0px; padding-top: 5px;">
                    <?php echo $htm_titlepage.$txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> 
                    <a <?php echo loadpage('blog-crear'); ?> class='btn btn-sm btn-success active pull-right hidden-sm'> <i class='fa fa-plus'></i> AGREGAR BLOG</a>
                </h3>
                <blockquote class="page-information hidden">
                    <p>
                        Listado de blogs.
                    </p>
                </blockquote>
            </div>
        </div>

        <form action="blog-listar.adm" method="post" style="display:none;">
            <div class="row" style="background: #99ccec;padding: 5px 0px;">
                <div class="col-md-4">
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
                <div class="col-md-2">
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
        
        <?php 
        $aux_sw_virt = 'normal';
        if ($_01_cvirt == '1') {
            $aux_sw_virt = 'virtual';
        }
        ?>
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
                                <th class="" style="font-size:10pt;">#</th>
                                <th class="hidden-sm" style="font-size:10pt;width:100px;">Img.</th>
                                <th class="" style="font-size:10pt;">Ciudad</th>
                                <th class="" style="font-size:10pt;">Fecha</th>
                                <th class="" style="font-size:10pt;">Curso</th>
                                <th class="" style="font-size:10pt;">Visitas</th>
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
                                $url_img_curso = $dominio_www."contenido/imagenes/blog/" . $curso['imagen'];
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_blog('<?php echo $curso['dr_id_curso']; ?>');" data-toggle="modal" data-target="#MODAL-historial_blog"><i class="fa fa-list"></i></b>
                                        <div class="hidden-md hidden-lg" style="width: 65px;overflow: hidden;white-space: initial;">
                                            <br>
                                            <a data-toggle="modal" data-target="#MODAL-show_facebook_post" onclick="show_facebook_post('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;">
                                                <img src="<?php echo $url_img_curso; ?>" style="height:40px;width:65px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                            </a>
                                            <br/><?php echo "<i style='color:gray;font-size: 7pt;'>" . $curso['horarios'] . "</i>"; ?>
                                            <?php
                                            if ($curso['sw_suspendido'] == 1) {
                                                echo "<br/><b style='color:red;font-size: 8pt;'>SUSPENDIDO</b>";
                                            }
                                            if ($curso['sw_cierre'] == 1) {
                                                echo "<br/><b style='color:gray;font-size: 8pt;'>CERRADO</b>";
                                            }
                                            if ($curso['id_cierre'] !== '0' && $curso['sw_cierre'] == 0) {
                                                echo "<br/><b style='color:#730cbe;font-size: 8pt;'>MODIFICADO</b>";
                                            }
                                            if ($curso['dr_estado_curso'] == 3) {
                                                echo "<br/><b style='color:red;font-size: 8pt;'>ELIMINADO</b>";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="hidden-sm">
                                        <a data-toggle="modal" data-target="#MODAL-show_facebook_post" onclick="show_facebook_post('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;">
                                            <img src="<?php echo $url_img_curso; ?>" style="height:50px;width:75px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                        </a>
                                        <br/><?php echo "<i style='color:gray;font-size: 7pt;'>" . $curso['horarios'] . "</i>"; ?>
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
                                    </td>
                                    <td class="">
                                        <?php
                                        echo $curso['dr_nombre_ciudad'];
                                        if ($curso['id_modalidad'] == '2' || $curso['id_modalidad'] == '3') {
                                            echo "<div style='color:red;text-align:center;padding:10px 0px;font-size:11pt;'>";
                                            echo "<b><i class='fa fa-cloud'></i><br/>VIRTUAL</b>";
                                            echo "</div>";
                                        }
                                        ?>         
                                    </td>
                                    <td class="">
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
                                    <td class="">
                                        <?php
                                        echo '<div class="hidden-sm" >'.$curso['titulo'].'</div>';
                                        echo '<div class="hidden-lg hidden-md" style="width: 170px;font-size:9pt;white-space: initial;">'.$curso['titulo'].'</div>';
                                        echo "<br/>";
                                        if ($nivel_administrador == '1') {
                                            /* url_corta */
                                            $url_corta = $dominio . $curso['titulo_identificador']. '/';
                                            echo "<i class='btn btn-warning active btn-xs' onclick='copyToClipboard(\"cu".$curso['dr_id_curso']."\")' id='cu".$curso['dr_id_curso']."'>" . $url_corta . "</i><br>";
                                        }
                                        echo "<span style='color:gray;font-size:7pt;' class='pull-left'>ID de blog: " . $curso['dr_id_curso'] . "</span>";                                        
                                        ?>
                                    </td>
                                    <td class="">
                                    <?php if ($nivel_administrador == '1') { ?>
                                            <?php
                                            echo '<b>' . $curso['cnt_reproducciones'] . '</b> <i style="color:gray;">vistas</i>';
                                            ?>
                                            <div class="" id="box-datapart2-<?php echo $curso['dr_id_curso']; ?>"></div>
                                    <?php } ?>
                                            <hr>
                                        <?php
                                        echo $curso['dr_nombre_lugar'];
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>" . $curso['dr_nombre_salon'] . "</i>";
                                        ?>
                                        <div class="" id="box-datapart3-<?php echo $curso['dr_id_curso']; ?>"></div>
                                    </td>
                                    <td class="" id="td-estado-<?php echo $curso['dr_id_curso']; ?>">
                                        <?php
                                        if ($curso['dr_estado_curso'] !== '3') {

                                            if ($curso['dr_estado_curso'] == '1') {
                                                ?>
                                                <b style='color:green;'>ACTIVADO</b>
                                                <br/><br/>
                                                <?php
                                                /*
                                                switch ($curso['columna']) {
                                                    case '1':
                                                        echo "(1ra) PRIMERA<br/>Columna";
                                                        break;
                                                    case '2':
                                                        echo "(2da) SEGUNDA<br/>Columna";
                                                        break;
                                                    case '3':
                                                        echo "(3ra) TERCERA<br/>Columna";
                                                        break;
                                                    default :
                                                        echo "NO VISIBLE<br/>EN INICIO";
                                                        break;
                                                }
                                                */
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
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $curso['dr_id_curso']; ?>', 'desactivado');">Desactivado</i>
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
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $curso['dr_id_curso']; ?>', 'activado');">Activado</i>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $curso['dr_id_curso']; ?>', 'desactivado');">Desactivado</i>
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
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $curso['dr_id_curso']; ?>', 'activado');">Activado</i>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $curso['dr_id_curso']; ?>', 'temporal');">Temporal</i>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="" style="width:120px;">
                                        <?php
                                        if ($curso['dr_estado_curso'] !== '3') {

                                            if ($nivel_administrador == '1' || $curso['dr_estado_curso'] == '1' || $curso['dr_estado_curso'] == '2') {
                                                ?>
                                                <a href="<?php echo $curso['titulo_identificador']; ?>/" target="_blank" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-eye'></i> Visualizar</a>
                                                <a href="blog-editar/<?php echo $curso['dr_id_curso']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-edit'></i> Editar</a>
                                                <?php
                                            }
                                            ?>
                                            <a onclick="duplicar_blog('<?php echo $curso['dr_id_curso']; ?>', '<?php echo str_replace('"', '', str_replace("'", '', $curso['titulo'])); ?>');" style="cursor:pointer;color: #0089b5;" class="btn btn-xs btn-default btn-block"><i class='fa fa-random'></i> Duplicar</a>
                                            <?php
                                            if (acceso_cod('adm-cursos-eliminar') && (int) $curso['cnt_participantes_aux'] == 0 && $curso['dr_estado_curso'] !== '3' && false) {
                                                ?>
                                                <br/>
                                                <form action="blog-listar.adm" method="post">
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
                                                <a onclick="notificar_blog('<?php echo $curso['dr_id_curso']; ?>');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_blog">
                                                    <i class='fa fa-send'></i> Notificar
                                                </a>
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

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
</script>

<!-- duplicar curso -->
<script>
    function duplicar_blog(id_curso, nombre_curso) {
        if (confirm('DUPLICACION DE BLOG - Desea duplicar el blog ' + nombre_curso + ' ?')) {
            $.ajax({
                url: 'pages/ajax/ajax.blog-listar.duplicar_blog.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open('<?php echo $dominio; ?>blog-editar/'+parseInt(data)+'.adm', '_blank');
                    window.focus();
                }
            });
        }
    }
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
    function notificar_blog(id_curso) {
        $("#AJAXCONTENT-notificar_blog").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.blog-listar.notificar_blog.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-notificar_blog").html(data);
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
            url: 'pages/ajax/ajax.blog-listar.actualiza_segmento_de_notificacion.php',
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
    function enviar_notificacion_blog(dat, bloque) {
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
            url: '<?php echo $dominio_www; ?>pages/ajax/ajax.blog-listar.enviar_notificacion_blog.php?modenv=' + dat + '&bloque=' + bloque + '&ahdmd=<?php echo base64_encode($id_administrador); ?>&title=' + encodeURI(title),
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
    function cambiar_estado_blog(id_curso, estado) {
        $("#td-estado-" + id_curso).html("Actualizando...");
        $.ajax({
            url: 'pages/ajax/ajax.blog-listar.cambiar_estado_blog.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#td-estado-" + id_curso).html(data);
            }
        });
    }
</script>

<!-- historial_blog -->
<script>
    function historial_blog(id_curso) {
        $("#AJAXCONTENT-historial_blog").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.blog-listar.historial_blog.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_blog").html(data);
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
            url: 'pages/ajax/ajax.blog-listar.show_facebook_post.php',
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


<!-- MODAL notificar_blog -->
<div id="MODAL-notificar_blog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NOTIFICACI&Oacute;N DE BLOG</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-notificar_blog"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL historial_blog -->
<div id="MODAL-historial_blog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_blog"></div>
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


<script>
    function buscar_participante(modcourse) {
        $("#AJAXCONTENT-buscar_participante").html("Cargando...");
        let dat = $("#input-busca-participante").val();
        $.ajax({
            url: 'pages/ajax/ajax.blog-listar.buscar_participante.php',
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
