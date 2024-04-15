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
$qr_estado = " AND estado IN (0,1,2)  ";
$txt_estado = "";
$qr_departamento = "";

if (isset($get[3]) && $get[3] !== 'no-search') {
    $_POST['input-buscador'] = $get[3];
    $_POST['id_ciudad'] = '0';
    $_POST['id_departamento'] = '0';
}

/* estados de curso (+cursos hoy) */
if (isset($get[4])) {
    switch ($get[4]) {
        case 'temporales':
            $qr_estado = " AND estado='2' ";
            $txt_estado = " - TEMPORALES";
            break;
        case 'activos':
            $qr_estado = " AND estado='1' ";
            $txt_estado = " - ACTIVOS";
            break;
        case 'hoy':
            $qr_estado = " AND estado IN (0,1) AND fecha=CURDATE() ";
            $txt_estado = " - HOY";
            break;
        case 'sincierre':
            $qr_estado = " AND estado IN (0,1) AND sw_cierre='0' AND id_cierre='0' ";
            $txt_estado = " - SIN CIERRE";
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
        $qr_fecha = " AND DATE(fecha)='$fecha_busqueda' ";
    }

    //*$vista = 1;
    $qr_estado = " AND estado IN (0,1,2,3) ";
    if (post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_busqueda = " AND (id='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND id_ciudad='$id_ciudad' $qr_fecha ";
    } elseif (post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " AND (id='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') $qr_fecha ";
    } else {
        $qr_busqueda = " AND (id='$busqueda' OR titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) $qr_fecha ";
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
        //query("DELETE FROM cursos WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        //query("DELETE FROM cursos_rel_cursostags WHERE id_curso='$id_curso_delete' ORDER BY id DESC ");
        query("UPDATE cursos SET estado='3' WHERE id='$id_curso_delete' ORDER BY id DESC ");
        logcursos('Eliminacion de curso', 'curso-eliminacion', 'curso', $id_curso_delete);
        $mensaje = '<br/><div class="alert alert-success">
  <strong>Exito!</strong> curso eliminado.
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
$resultado1 = query("SELECT * FROM cursos_onlinecourse c WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_onlinecourse WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ");
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
            <li><a <?php echo loadpage('cursos-virtuales-listar'); ?>>Cursos virtuales</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right hidden-sm">
            <a <?php echo loadpage('cursos-virtuales-crear'); ?> class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
        </div>
        <h3 class="page-header"> LISTADO DE CURSOS VIRTUALES <?php echo $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de cursos.
            </p>
        </blockquote>

        <form action="cursos-listar.adm" method="post">
            <div class="col-md-10">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Buscar por Curso / Docente / ID ..."/>
                </div>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" class="btn btn-warning btn-block active"/>
            </div>
        </form>
    </div>
</div>

<?php echo $mensaje; ?>

<hr/>

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
                                <th class="visible-lgNOT" style="font-size:10pt;">Img.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Lecciones</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Estado</th>
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
                                /* curso eliminado */
                                if ($curso['estado'] == 3) {
                                    $tr_class .= ' tr_curso_eliminado';
                                }
                                /*
                                  if ($curso['sw_cierre'] == 1) {
                                  $tr_class .= ' tr_curso_cerrado';
                                  }
                                 */
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
                                        $url_img_curso = "contenido/imagenes/cursos/" . $curso['imagen'];
                                        if (!file_exists("../../imagenes/cursos/" . $curso['imagen'])) {
                                            $url_img_curso = "https://www.infosicoes.com/cursos/" . $curso['imagen'] . ".size=3.img";
                                        }
                                        ?>
                                        <img src="<?php echo $url_img_curso; ?>" style="height:80px;width:130px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo "<span style='font-size:14pt;'>" . ($curso['titulo']) . "</span>";
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>CURSO VIRTUAL</i>";
                                        echo "<br/>";
                                        if ($nivel_administrador == '1') {
                                            /* url_ingreso */
                                            $url_corta = 'https://cursos.bo/curso-online/' . $curso['urltag'].'.html';
                                            echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        }
                                        echo "<span style='color:gray;font-size:8pt;' class='pull-right'>ID de curso virtual: " . $curso['id'] . "</span>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        $rqdcl1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='".$curso['id']."' ");
                                        $rqdcl2 = mysql_fetch_array($rqdcl1);
                                        ?>
                                        <b style="color:#1d6381;font-size: 14pt;"><?php echo $rqdcl2['total']; ?></b>
                                        <br/>
                                        LECCIONES
                                        <br>
                                        <br>
                                        <?php
                                        $rqdclm1 = query("SELECT SUM(minutos) AS total_minutos FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='".$curso['id']."' ");
                                        $rqdclm2 = mysql_fetch_array($rqdclm1);
                                        echo (int)($rqdclm2['total_minutos']/60).' H.  |  '.($rqdclm2['total_minutos']%60).' m.';
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" id="td-estado-<?php echo $curso['id']; ?>">
                                        <?php
                                        if ($curso['estado'] !== '3') {

                                            if ($curso['estado'] == '1') {
                                                echo "<b style='color:green;'>ACTIVADO</b>";
                                                echo "<br/>";
                                                if (acceso_cod('adm-cursos-estado')) {
                                                    ?>
                                                    <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                    <br/>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'desactivado');">Desactivado</i>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                DESACTIVADO<br/>
                                                <?php
                                                if (acceso_cod('adm-cursos-estado')) {
                                                    ?>
                                                    <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                    <br/>
                                                    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'activado');">Activado</i>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($curso['estado'] !== '3') {
                                            if ($nivel_administrador == '1' || $curso['estado'] == '1' || $curso['estado'] == '2') {
                                                ?>
                                                <a href="<?php echo $curso['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default btn-block" style="color: #0089b5;" disabled><i class='fa fa-eye'></i> Visualizar</a>
                                                <a href="cursos-virtuales-editar/<?php echo $curso['id']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-edit'></i> Edici&oacute;n</a>
                                                <a href="cursos-virtuales-lecciones/<?php echo $curso['id']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-file-text'></i> Lecciones</a>
                                                <a data-toggle="modal" data-target="#MODAL-cursos_asociados" class="btn btn-xs btn-default btn-block" style="color: #0089b5;" onclick="cursos_asociados('<?php echo $curso['id']; ?>');"><i class='fa fa-university'></i> CURSOS</a>
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
                            if (isset($get[3])) {
                                $urlget3 .= '/' . $get[3];
                            }
                            /* get 4 */
                            if (isset($get[4])) {
                                $urlget3 .= '/' . $get[4];
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
    var text__loading_uno = "<div style='text-align:center;'><img src='contenido/imagenes/images/loader.gif'/></div>";
</script>


<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#td-estado-" + id_curso).html("Actualizando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-virtuales-listar.cambiar_estado_curso.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-virtuales-listar.historial_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_curso").html(data);
            }
        });
    }
</script>

<!-- cursos_asociados -->
<script>
    function cursos_asociados(id_curso) {
        $("#AJAXCONTENT-cursos_asociados").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-virtuales-listar.cursos_asociados.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-cursos_asociados").html(data);
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

<!-- Modal  -->
<div id="MODAL-cursos_asociados" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CURSOS ASOCIADOS</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-cursos_asociados"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

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
