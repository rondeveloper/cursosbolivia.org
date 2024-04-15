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
    $rqdde2 = fetch($rqdde1);
    $htm_titlepage = "CURSOS " . strtoupper($rqdde2['nombre']) . " : <a href='".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html</a>";
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
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];



/* agregar-grupo */
if(isset_post('agregar-grupo')){
    $id_departamento = post('id_departamento');
    $nombre = post('nombre_gw');
    $enlace_ingreso = post('enlace_ingreso_gw');
    query("INSERT INTO whatsapp_grupos (id_departamento, nombre, enlace_ingreso, estado) VALUES ('$id_departamento','$nombre','$enlace_ingreso','1')");
    $id_whatsapp_grupo = insert_id();
    query("UPDATE departamentos SET id_whatsapp_grupo='$id_whatsapp_grupo' WHERE id='$id_departamento' LIMIT 1 ");
    logcursos('Creacion de grupo whatsapp [ID:'.$id_whatsapp_grupo.']', 'whatsapp-departamento', 'departamento', $id_departamento);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro creado exitosamente.
</div>
';
}

/* editar-grupo */
if(isset_post('editar-grupo')){
    $id_grupo = post('id_grupo');
    $id_departamento = post('id_departamento');
    $nombre_gw = post('nombre_gw');
    $enlace_ingreso_gw = post('enlace_ingreso_gw');
    query("UPDATE whatsapp_grupos SET nombre='$nombre_gw', enlace_ingreso='$enlace_ingreso_gw' WHERE id='$id_grupo' limit 1 ");
    logcursos('Edicion de grupo whatsapp [ID:'.$id_whatsapp_grupo.']', 'whatsapp-departamento', 'departamento', $id_departamento);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro actualizado exitosamente.
</div>
';
}


/* agregar-fanpage */
if(isset_post('agregar-fanpage')){
    $id_departamento = post('id_departamento');
    $url_facebook = post('url_facebook');
    query("UPDATE departamentos SET url_facebook='$url_facebook' WHERE id='$id_departamento' LIMIT 1 ");
    logcursos('Actualizacion de fanpage Facebook ['.$url_facebook.']', 'facebook-departamento', 'departamento', $id_departamento);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro actualizado exitosamente.
</div>
';
}


/* registros */
$data_required = "
*
";

$resultado1 = query("SELECT $data_required FROM departamentos d WHERE 1 $qr_busqueda ORDER BY d.orden ASC LIMIT $start,$registros_a_mostrar");
$total_registros = num_rows($resultado1);
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
                    NOTIFICACIONES POR DEPARTAMENTOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> 
                </h3>
                <blockquote class="page-information hidden">
                    <p>
                        Listado de cursos.
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
<!--                                <th class="visible-lgNOT" style="font-size:10pt;width:100px;">Img.</th>-->
                                <th class="visible-lgNOT" style="font-size:10pt;">Departamento</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Cursos</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Url</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Hoy</th>
                                <th class="visible-lgNOT" style="font-size:10pt;width: 15px;">WhatsApp</th>
                                <th class="visible-lgNOT" style="font-size:10pt;width: 15px;">Facebook</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Banner</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($departamento = fetch($resultado1)) {
                                $rdc1 = query("SELECT count(*) AS total FROM cursos WHERE estado='1' AND id_modalidad<>'2' AND id_ciudad IN (select id from ciudades where id_departamento='" . $departamento['id'] . "') ");
                                $rdc2 = fetch($rdc1);
                                $cnt_cursosactivos = $rdc2['total'];
                                ?>
                                <tr>
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt++; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('<?php echo $departamento['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
    <!--                                    <td class="visible-lgNOT">
                                        <img src="<?php echo $dominio_www."contenido/imagenes/departamentos/" . $departamento['imagen']; ?>" style="height:65px;width:65px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                    </td>-->
                                    <td class="visible-lgNOT" style="vertical-align: middle;">
                                        <b style="font-size:15pt;font-weight:bold;color:#00789f;"><?php echo $departamento['nombre']; ?></b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $cnt_cursosactivos . ' activos';
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        /* url_corta */
                                        $url_corta = $dominio.'cursos-en-' . $departamento['titulo_identificador'] . '.html';
                                        $url_corta_2 = $dominio . strtolower($departamento['cod']) . '/';
                                        echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        echo "<input type='text' class='form-control' value='" . $url_corta_2 . "'/>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='" . $departamento['id'] . "' AND fecha=CURDATE() ");
                                        $rqcenv2 = fetch($rqcenv1);
                                        $hoy_total = $rqcenv2['total'];
                                        echo "<b class='text-success'>$hoy_total correos</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='" . $departamento['id'] . "' AND fecha_envio=CURDATE() ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_push_total = $rqcenp2['total'];
                                        echo "<b class='text-info'>$hoy_push_total push</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT reproducciones FROM metricas_r_departamentos WHERE id_departamento='" . $departamento['id'] . "' AND fecha=CURDATE() ORDER BY id DESC limit 1 ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_reproducciones = (int) $rqcenp2['reproducciones'];
                                        echo "<b class='text-warning'>$hoy_reproducciones reproducciones</b>";
                                        ?>
                                        <br/>
                                        <b class="btn btn-xs btn-default" data-toggle="modal" data-target="#MODAL-show_metricas" onclick="show_metricas('<?php echo $departamento['id']; ?>');">
                                            Esta semana
                                        </b>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        if ($departamento['id_whatsapp_grupo'] == '0') {
                                            ?>
                                            <b style="color:red;">NO</b>
                                            <br/>
                                            <b class="btn btn-xs btn-default" data-toggle="modal" data-target="#MODAL-grupos_whatsapp" onclick="grupos_whatsapp('<?php echo $departamento['id']; ?>');">
                                                Agregar
                                            </b>
                                            <?php
                                        } else {
                                            ?>
                                            <b style="color:green;">SI</b>
                                            <br/>
                                            <b class="btn btn-xs btn-default" data-toggle="modal" data-target="#MODAL-grupos_whatsapp" onclick="grupos_whatsapp('<?php echo $departamento['id']; ?>');">
                                                Administrar
                                            </b>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        if ($departamento['url_facebook'] == '') {
                                            ?>
                                            <b style="color:red;">NO</b>
                                            <br/>
                                            <b class="btn btn-xs btn-default" data-toggle="modal" data-target="#MODAL-fanpage_facebook" onclick="fanpage_facebook('<?php echo $departamento['id']; ?>');">
                                                Agregar
                                            </b>
                                            <?php
                                        } else {
                                            ?>
                                            <b style="color:green;">SI</b>
                                            <br/>
                                            <b class="btn btn-xs btn-default" data-toggle="modal" data-target="#MODAL-fanpage_facebook" onclick="fanpage_facebook('<?php echo $departamento['id']; ?>');">
                                                Administrar
                                            </b>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        $url_imgbanner = $dominio_www.'contenido/imagenes/banners/1528991771banner-uno.png';
                                        if ($departamento['img_banner'] !== '') {
                                            $url_imgbanner = $dominio_www.'contenido/imagenes/departamentos/' . $departamento['img_banner'];
                                        }
                                        ?>
                                        <img src="<?php echo $url_imgbanner; ?>" style="height:70px;width:110px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($cnt_cursosactivos > 0) {
                                            ?>
                                            <a onclick="notificar_cursos('<?php echo $departamento['id']; ?>');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_cursos">
                                                <i class='fa fa-send'></i> Notificar
                                            </a>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                        <b class="btn btn-xs btn-default pull-right" data-toggle="modal" data-target="#myModalEB-<?php echo $departamento['id']; ?>">Imagen <i class="fa fa-edit"></i></b>
                                        <!-- Modal -->
                                        <div id="myModalEB-<?php echo $departamento['id']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">IMAGEN DE P&Aacute;GINA</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="aux-cursos-departamentos.adm" method="post" enctype="multipart/form-data">
                                                            <table class="table table-striped table-bordered">
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <img src="<?php echo $url_imgbanner; ?>" style="height:auto;width:100%;overflow:hidden;border-radius: 7px;"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>IMAGEN</b>
                                                                    </td>
                                                                    <td>
                                                                        <input type="file" name="imagen" class="form-control"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <input type="hidden" name="id_departamento" value="<?php echo $departamento['id']; ?>"/>
                                                                        <input type="submit" name="actualizar-imagen" class="btn btn-success btn-block" value="ACTUALIZAR IMAGEN"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            if ($cnt > 1 && false) {
                                $rdc1 = query("SELECT count(*) AS total FROM cursos WHERE estado='1' AND id_modalidad<>'2' ");
                                $rdc2 = fetch($rdc1);
                                $cnt_cursosactivos = $rdc2['total'];
                                ?>
                                <tr>
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt++; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('0');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
    <!--                                    <td class="visible-lgNOT">
                                        <img src="<?php echo $dominio_www."contenido/imagenes/departamentos/bolivia.jpg"; ?>" style="height:70px;width:70px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                    </td>-->
                                    <td class="visible-lgNOT" style="vertical-align: middle;">
                                        <b style="font-size:20pt;font-weight:bold;color:#00789f;">Bolivia</b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $cnt_cursosactivos . ' activos';
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        /* url_corta */
                                        $url_corta = $dominio.'cursos-en-bolivia.html';
                                        echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='0' AND fecha=CURDATE() ");
                                        $rqcenv2 = fetch($rqcenv1);
                                        $hoy_total = $rqcenv2['total'];
                                        echo "<b class='text-success'>$hoy_total correos</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='0' AND fecha_envio=CURDATE() ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_push_total = $rqcenp2['total'];
                                        echo "<b class='text-info'>$hoy_push_total push</b>";
                                        ?>
                                        <br/>
                                        <b>Esta semana:</b>
                                        <br/>
                                        <?php
                                        $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='0' AND fecha>DATE_ADD(curdate(), interval -7 day) ");
                                        $rqcenv2 = fetch($rqcenv1);
                                        $hoy_total = $rqcenv2['total'];
                                        echo "<b class='text-success'>$hoy_total correos</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='0' AND fecha_envio>DATE_ADD(curdate(), interval -7 day) ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_push_total = $rqcenp2['total'];
                                        echo "<b class='text-info'>$hoy_push_total push</b>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a onclick="notificar_cursos('0');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_cursos">
                                            <i class='fa fa-send'></i> Notificar
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            
                            /* cursos virtuales */
                            $rqdcv1 = query("SELECT * FROM cursos WHERE estado='1' AND id_modalidad='2' ");
                            if (num_rows($rqdcv1)>0) {
                                $rdc1 = query("SELECT count(*) AS total FROM cursos WHERE estado='1' AND id_modalidad='2' ");
                                $rdc2 = fetch($rdc1);
                                $cnt_cursosactivos = $rdc2['total'];
                                ?>
                                <tr>
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt++; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('10');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
                                    <td class="visible-lgNOT" style="vertical-align: middle;">
                                        <b style="font-size:20pt;font-weight:bold;color:#00789f;">VIRTUALES</b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $cnt_cursosactivos . ' activos';
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        /* url_corta */
                                        $url_corta = $dominio.'cursos-virtuales.html';
                                        echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='10' AND fecha=CURDATE() ");
                                        $rqcenv2 = fetch($rqcenv1);
                                        $hoy_total = $rqcenv2['total'];
                                        echo "<b class='text-success'>$hoy_total correos</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='10' AND fecha_envio=CURDATE() ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_push_total = $rqcenp2['total'];
                                        echo "<b class='text-info'>$hoy_push_total push</b>";
                                        ?>
                                        <br/>
                                        <b>Esta semana:</b>
                                        <br/>
                                        <?php
                                        $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='10' AND fecha>DATE_ADD(curdate(), interval -7 day) ");
                                        $rqcenv2 = fetch($rqcenv1);
                                        $hoy_total = $rqcenv2['total'];
                                        echo "<b class='text-success'>$hoy_total correos</b>";
                                        echo "<br/>";
                                        $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='10' AND fecha_envio>DATE_ADD(curdate(), interval -7 day) ");
                                        $rqcenp2 = fetch($rqcenp1);
                                        $hoy_push_total = $rqcenp2['total'];
                                        echo "<b class='text-info'>$hoy_push_total push</b>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT">

                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a onclick="notificar_cursos('10');" style="cursor:pointer;" class="btn btn-xs btn-success btn-block" data-toggle="modal" data-target="#MODAL-notificar_cursos">
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
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
</script>


<script>
    function notificar_cursos(id_departamento) {
        $("#AJAXCONTENT-notificar_cursos").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.notificar_cursos.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-notificar_cursos").html(data);
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
            url: 'pages/ajax/ajax.cursos-departamentos.actualiza_segmento_de_notificacion.php',
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
    function enviar_notificacion_cursos(dat, bloque) {
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
            url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-departamentos.enviar_notificacion_cursos.php?modenv=' + dat + '&bloque=' + bloque + '&ahdmd=<?php echo base64_encode($id_administrador); ?>&title=' + encodeURI(title),
            data: arraydata,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#" + idtime).html(data);
            }
        });
    }
</script>


<!-- historial_curso -->
<script>
    function historial_curso(id_departamento) {
        $("#AJAXCONTENT-historial_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.historial_departamento.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_curso").html(data);
            }
        });
    }
</script>

<!-- MODAL notificar_cursos -->
<div id="MODAL-notificar_cursos" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NOTIFICACI&Oacute;N DE CURSOS</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-notificar_cursos"></div>
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


<!-- show_metricas -->
<script>
    function show_metricas(id_departamento) {
        $("#AJAXCONTENT-show_metricas").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.show_metricas.php',
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



<!-- grupos_whatsapp -->
<script>
    function grupos_whatsapp(id_departamento) {
        $("#AJAXCONTENT-show_metricas").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.grupos_whatsapp.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-grupos_whatsapp").html(data);
            }
        });
    }
</script>
<!-- Modal grupos_whatsapp-->
<div id="MODAL-grupos_whatsapp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GRUPOS WHATSAPP</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-grupos_whatsapp"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- fanpage_facebook -->
<script>
    function fanpage_facebook(id_departamento) {
        $("#AJAXCONTENT-fanpage_facebook").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.fanpage_facebook.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-fanpage_facebook").html(data);
            }
        });
    }
</script>
<!-- Modal fanpage_facebook-->
<div id="MODAL-fanpage_facebook" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FANPAGE FACEBOOK</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-fanpage_facebook"></div>
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
