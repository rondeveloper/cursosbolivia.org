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
$qr_estado = " AND c.estado IN (1)  ";
$qr_departamento = "";
$qr_docente = "";
$qr_lugar = "";
$qr_modadlidad = "";


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
    if($id_departamento=='10'){
        $qr_departamento = "";
        $htm_titlepage = "CURSOS VIRTUTALES : <a href='".$dominio."cursos-virtuales.html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-virtuales.html</a>";
        $qr_modadlidad = " AND c.id_modalidad IN (2,3) ";
    }else{
        $qr_departamento = " AND c.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
        $rqdde1 = query("SELECT nombre,titulo_identificador FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
        $rqdde2 = fetch($rqdde1);
        $htm_titlepage = "CURSOS " . strtoupper($rqdde2['nombre']) . " : <a href='".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html' style='font-size: 14pt;font-weight:bold;' target='_blank' class='hidden-sm'>".$dominio."cursos-en-" . $rqdde2['titulo_identificador'] . ".html</a>";
    }
    
  
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


/* registros */
$data_required = "
c.sw_suspendido,c.imagen,c.horarios,c.sw_cierre,c.id_cierre,c.id_modalidad,c.fecha,c.sw_fecha2,c.fecha2,c.sw_fecha3,c.fecha3,c.costo,c.costo2,c.costo3,c.titulo,c.short_link,c.numero,c.id_certificado,c.cnt_reproducciones,c.columna,c.titulo_identificador,
c.laststch_fecha,c.laststch_id_administrador,
(select count(1) from cursos_participantes where id>22000 and id_curso=c.id and estado='1' order by id desc)cnt_participantes_aux,
(concat(d.prefijo,' ',d.nombres))docente,
(cd.nombre)dr_nombre_ciudad,
(l.nombre)dr_nombre_lugar,
(l.salon)dr_nombre_salon,
(l.direccion)dr_nombre_direccion,
(c.id)dr_id_curso,
(c.estado)dr_estado_curso
";

$resultado1 = query("SELECT $data_required FROM cursos c LEFT JOIN ciudades cd ON c.id_ciudad=cd.id LEFT JOIN cursos_docentes d ON d.id=c.id_docente LEFT JOIN cursos_lugares l ON l.id=c.id_lugar WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_modadlidad $qr_docente $qr_fecha $qr_lugar ORDER BY c.fecha DESC,c.id_ciudad ASC,c.id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos c WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento $qr_modadlidad $qr_docente $qr_fecha $qr_lugar ");
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
                                <th class="hidden-sm" style="font-size:10pt;">#</th>
                                <th class="" style="font-size:10pt;width:100px;">Img.</th>
                                <th class="hidden-sm" style="font-size:10pt;">Ciudad</th>
                                <th class="hidden-sm" style="font-size:10pt;">Fecha</th>
                                <th class="" style="font-size:10pt;">Curso</th>
                                <th class="hidden-sm" style="font-size:10pt;">Lugar</th>
                                <th class="hidden-sm" style="font-size:10pt;">Estado</th>
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
                                /*
                                  if ($curso['sw_cierre'] == 1) {
                                  $tr_class .= ' tr_curso_cerrado';
                                  }
                                 */
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="hidden-sm">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('<?php echo $curso['dr_id_curso']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
                                    <td class="">
                                        <?php
                                        $url_img_curso = "https://www.infosicoes.com/paginas/" . $curso['imagen'] . ".size=2.img";
                                        $url_img_curso = $dominio_www."contenido/imagenes/paginas/" . $rc2['imagen'];
                                        //$url_img_curso = "paginas/" . $curso['imagen'] . ".size=2.img";
                                        $url_img_curso = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen'];
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
                                    <td class="hidden-sm">
                                        <?php
                                        echo $curso['dr_nombre_ciudad'];
                                        if ($curso['id_modalidad'] == '2' || $curso['id_modalidad'] == '3') {
                                            echo "<div style='color:red;text-align:center;padding:10px 0px;font-size:11pt;'>";
                                            echo "<b><i class='fa fa-cloud'></i><br/>VIRTUAL</b>";
                                            echo "</div>";
                                        }
                                        ?>         
                                    </td>
                                    <td class="hidden-sm">
                                        <?php
                                        echo my_date_curso($curso['fecha']);
                                        if ($curso['fecha'] == date("Y-m-d")) {
                                            echo "<br/><b style='background: #00a500;font-size: 10pt;color: #FFF;padding: 1px 5px;border-radius: 5px;'>HOY</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="">
                                        <?php
                                        echo ($curso['titulo']);
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>Docente: " . ($curso['docente']) . "</i>";
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>" . $curso['short_link'] . "</i>";
                                        echo "<br/>";

                                        $url_corta = $dominio . numIDcurso($curso['dr_id_curso']) . '/';
                                        $costo = $curso['costo'];
                                        $sw_descuento_costo2 = false;
                                        $f_h = date("H:i", strtotime($curso['fecha2']));
                                        if ($f_h !== '00:00') {
                                            $f_actual = strtotime(date("Y-m-d H:i"));
                                            $f_limite = strtotime($curso['fecha2']);
                                        } else {
                                            $f_actual = strtotime(date("Y-m-d"));
                                            $f_limite = strtotime(substr($curso['fecha2'], 0, 10));
                                        }
                                        if ($curso['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
                                            $sw_descuento_costo2 = true;
                                            $costo2 = $curso['costo2'];
                                        }
                                        $sw_descuento_costo3 = false;
                                        if ($curso['sw_fecha3'] == '1' && ( date("Y-m-d") <= $curso['fecha3'])) {
                                            $sw_descuento_costo3 = true;
                                            $costo3 = $curso['costo3'];
                                        }
                                        $sw_descuento_costo_e2 = false;
                                        if ($curso['sw_fecha_e2'] == '1' && (date("Y-m-d") <= $curso['fecha_e2'])) {
                                            $sw_descuento_costo_e2 = true;
                                            $costo_e2 = $curso['costo_e2'];
                                        }
                                        $textinfo_modalidad_curso = 'Presencial';
                                        if ($curso['id_modalidad'] == '2' || $curso['id_modalidad'] == '3') {
                                            $textinfo_modalidad_curso = 'VIRTUAL';
                                        }
                                        /* fecha de inicio */
                                        $arf1 = explode('-', $curso['fecha']);
                                        $arf2 = date("N", strtotime($curso['fecha']));
                                        $array_dias = array('none', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
                                        $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                        $fecha_de_inicio = $arf1[2] . " de " . $array_meses[(int) $arf1[1]] . " de " . $arf1[0];
                                        $dia_de_inicio = $array_dias[$arf2];
                                        
                                        /* banco */
                                        $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                                        $rqdtcb2 = fetch($rqdtcb1);
                                        $data_banco = $rqdtcb2['banco'];
                                        $data_numero_cuenta = $rqdtcb2['numero_cuenta'];
                                        $data_titular = $rqdtcb2['titular'];

                                        /* info_curso */
                                        $info_curso = '*'.$curso['titulo'] . '*

*Fecha:* ' . $dia_de_inicio . ', ' . $fecha_de_inicio . '

*Duraci&oacute;n:* ' . $curso['horarios'] . '

*Modalidad:* ' . $textinfo_modalidad_curso . '

*Sitio web:* ' . $url_corta;
                                        if ($curso['id_modalidad'] == '1') {
                                     $info_curso .= '

*Ciudad:* ' . $curso['dr_nombre_ciudad'] . '

*Lugar:* ' . $curso['dr_nombre_lugar'] . '

*Sal&oacute;n:* ' . $curso['dr_nombre_salon'] . '

*Direcci&oacute;n:* ' . $curso['dr_nombre_direccion'];
                                        }
                                     $info_curso .= '

*Inversi&oacute;n:* ' . $curso['costo'] . 'Bs.';
                                        if ($curso['sw_fecha_e'] == '1' && (date("Y-m-d") <= $curso['fecha_e'])) {
                                            $info_curso .= '
                                            
*Estudiantes:* ' . $curso['costo_e'] . ' Bs.';
                                        }
                                        if ($sw_descuento_costo2) {
                                            $info_curso .= '

*Descuento:*
POR PAGO ANTICIPADO
' . $costo2 . ' Bs. hasta el ' . mydatefechacurso2($curso['fecha2']);

                                            if ($sw_descuento_costo3) {
                                                $info_curso .= '

' . $costo3 . ' Bs. hasta el ' . mydatefechacurso2($curso['fecha3']);
                                            }
                                            if ($sw_descuento_costo_e2) {
                                                $info_curso .= '

*Estudiantes:* ' . $costo_e2 . ' Bs. hasta el ' . mydatefechacurso2($curso['fecha_e2']);
                                            }
                                        }
                                        $info_curso .= '

*Pagos:* ' . $data_banco .' cuenta '.$data_numero_cuenta.'  Titular '.$data_titular.'
    
*TigoMoney: 69714008* (sin recargo)
';

                                        echo "<textarea class='form-control' style='height:280px;'>" . $info_curso . "</textarea>";
                                        echo "<span style='color:gray;font-size:7pt;' class='pull-left'>ID de curso: " . $curso['dr_id_curso'] . "</span>";
                                        if ($curso['numero'] == '0') {
                                            echo "<span style='color:#d7d7d7;font-size:8pt;' class='pull-right'>Sin numeraci&oacute;n</span>";
                                        } else {
                                            echo "<span style='color:gray;font-size:8pt;' class='pull-right'>Numeraci&oacute;n: <b style='color:#394263;font-size:9pt;'>" . $curso['numero'] . "</b>&nbsp; </span>";
                                        }
                                        /* curso virtual */
                                        if ($curso['id_modalidad'] == '2' || $curso['id_modalidad'] == '3') {
                                            $rqdcv1 = query("SELECT fecha_inicio,fecha_final,(cv.titulo)dr_titulo_curso_virtual,(d.nombres)dr_nombre_docente FROM cursos_rel_cursoonlinecourse r INNER JOIN cursos_onlinecourse cv ON r.id_onlinecourse=cv.id INNER JOIN cursos_docentes d ON r.id_docente=d.id WHERE r.id_curso='" . $curso['dr_id_curso'] . "' ");
                                            if (num_rows($rqdcv1) > 0) {
                                                $rqdcv2 = fetch($rqdcv1);
                                                ?>
                                                <div style='color: #484848;padding: 10px 0px;font-size: 8pt;clear: both;background: #bae4ff;border-radius: 5px;padding: 5px;'>
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td>
                                                                <b style="font-size: 7pt;">C-VIRTUAL:</b>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <?php echo $rqdcv2['dr_titulo_curso_virtual']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b style="font-size: 7pt;">TUTOR:</b>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <?php echo $rqdcv2['dr_nombre_docente']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b style="font-size: 7pt;">DISPONIBLE:</b>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <?php echo date("d/m/Y", strtotime($rqdcv2['fecha_inicio'])); ?> hasta <?php echo date("d/m/Y", strtotime($rqdcv2['fecha_final'])); ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="hidden-sm">
                                        <?php
                                        echo $curso['dr_nombre_lugar'];
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>" . $curso['dr_nombre_salon'] . "</i>";
                                        ?>
                                        <div class="" id="box-datapart3-<?php echo $curso['dr_id_curso']; ?>"></div>
                                    </td>
                                    <td class="hidden-sm" id="td-estado-<?php echo $curso['dr_id_curso']; ?>">
                                        <?php
                                        if ($curso['dr_estado_curso'] !== '3') {

                                            if ($curso['dr_estado_curso'] == '1') {
                                                ?>
                                                <b style='color:green;'>ACTIVADO</b>
                                                <br/><br/>
                                                <?php
                                                if ($curso['laststch_fecha'] !== '0000-00-00 00:00:00') {
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='" . $curso['laststch_id_administrador'] . "' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>' . date("H:i", strtotime($curso['laststch_fecha'])) . '</b> &nbsp; ' . date("d/M", strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
                                            } elseif ($curso['dr_estado_curso'] == '2') {
                                                ?>
                                                <b style='color:red;'>TEMPORAL</b>
                                                <br/>
                                                <br/>
                                                <?php
                                                if ($curso['laststch_fecha'] !== '0000-00-00 00:00:00') {
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='" . $curso['laststch_id_administrador'] . "' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>' . date("H:i", strtotime($curso['laststch_fecha'])) . '</b> &nbsp; ' . date("d/M", strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
                                            } else {
                                                ?>
                                                DESACTIVADO
                                                <br/>
                                                <br/>
                                                <?php
                                                if ($curso['laststch_fecha'] !== '0000-00-00 00:00:00') {
                                                    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='" . $curso['laststch_id_administrador'] . "' LIMIT 1 ");
                                                    $rqalc2 = fetch($rqalc1);
                                                    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
                                                    echo '<b>' . date("H:i", strtotime($curso['laststch_fecha'])) . '</b> &nbsp; ' . date("d/M", strtotime($curso['laststch_fecha']));
                                                    echo "<br/>";
                                                    echo $rqalc2['nombre'];
                                                    echo "</div>";
                                                }
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

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
</script>

<!-- duplicar curso -->
<script>
    function duplicar_curso(id_curso, nombre_curso) {
        if (confirm('DUPLICACION DE CURSO - Desea duplicar el curso ' + nombre_curso + ' ?')) {
            $.ajax({
                url: 'pages/ajax/ajax.cursos-listar.duplicar_curso.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    location.href = 'cursos-listar/1.adm';
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
            url: '<?php echo $dominio_www; ?>pages/ajax/ajax.cursos-listar.enviar_notificacion_curso.php?modenv=' + dat + '&bloque=' + bloque + '&ahdmd=<?php echo base64_encode($id_administrador); ?>&title=' + encodeURI(title),
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

function mydatefechacurso2($dat) {
    $ds = date("w", strtotime($dat));
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $h = date("H:i", strtotime($dat));
    $txt_hour = '';
    if ($h !== '00:00') {
        $txt_hour = ' hasta ' . $h;
    }
    $array_dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $array_meses = array('NONE', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $array_dias[$ds] . " " . $d . " de " . ucfirst($array_meses[(int) ($m)]) . '' . $txt_hour;
}
