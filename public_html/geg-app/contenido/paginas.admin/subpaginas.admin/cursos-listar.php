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
$qr_estado = "";
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
            $qr_estado = " AND estado='1' AND fecha=CURDATE() ";
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
        $qr_fecha = " AND DATE(fecha)='$fecha_busqueda' ";
    }

    //*$vista = 1;
    if (post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_busqueda = " AND (titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND id_ciudad='$id_ciudad' $qr_fecha ";
    } elseif (post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " AND (titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') $qr_fecha ";
    } else {
        $qr_busqueda = " AND (titulo LIKE '%$busqueda%' OR id_docente IN (select id from cursos_docentes where concat(prefijo,' ',nombres) like '%$busqueda%') ) $qr_fecha ";
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
$resultado1 = query("SELECT *,(select count(1) from cursos_participantes where id>19000 and id_curso=c.id and estado='1' order by id desc)cnt_participantes_aux,(select concat(prefijo,' ',nombres) from cursos_docentes where id=c.id_docente limit 1)docente FROM cursos c WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ORDER BY fecha DESC,id_ciudad ASC,id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos WHERE 1 $qr_busqueda $qr_organizador $qr_estado $qr_departamento ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <?php
            $active_todos = 'active';
            $active_temp = '';
            if ($qr_estado !== '') {
                $active_todos = '';
                $active_temp = 'active';
            }
            ?>

            <?php
            $rqddc1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
            while ($rqddc2 = mysql_fetch_array($rqddc1)) {
                $aux_class_btndefault = 'btn-default';
                if ($id_departamento == $rqddc2['id']) {
                    $aux_class_btndefault = 'btn-info active';
                }
                ?>
                <a href="cursos-listar/1/no-search/todos/<?php echo $rqddc2['id']; ?>.adm" class='btn <?php echo $aux_class_btndefault; ?> btn-xs'><?php echo $rqddc2['nombre']; ?></a>
                &nbsp;
                <?php
            }
            ?>
            &nbsp;|&nbsp;
            <a href="cursos-crear.adm" class='btn btn-success active hidden-sm'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
        </div>
        <h3 class="page-header"> LISTADO DE CURSOS <?php echo $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <div style="background:#BBB;">
             <form action="cursos-listar.adm" method="post">
                <div class="col-md-4">
                    <div class="input-group col-sm-12">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Buscar por Curso / Docente ..."/>
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
</div>


<?php echo $mensaje; ?>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.data_participantes.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                var data1 = data_json_parsed['data1'];
                var data2 = data_json_parsed['data2'];

                $("#box-datapart-" + id_curso).html(data1);
                $("#box-datapart2-" + id_curso).html(data2);
            }
        });
    }
</script>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td{
        background: red;
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
                                <th class="visible-lgNOT" style="font-size:10pt;">Ciudad</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Fecha</th>
                                <th class="visible-lgNOT" style="font-size:10pt;width: 50px;">Costo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <?php if ($nivel_administrador == '1' || isset_organizador()) { ?>
                                    <th class="visible-lgNOT" style="font-size:10pt;">Registrados</th>
                                <?php } ?>
                                <th class="visible-lgNOT" style="font-size:10pt;">Cert.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Visitas</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Lugar</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Estado</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {
                                $tr_class = '';
                                /* curso suspendido */
                                if ($producto['sw_suspendido'] == 1) {
                                    $tr_class .= ' tr_curso_suspendido';
                                }
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default" onclick="historial_curso('<?php echo $producto['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $url_img_curso = "https://www.infosicoes.com/paginas/" . $producto['imagen'] . ".size=2.img";
                                        $url_img_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                                        $url_img_curso = "paginas/" . $producto['imagen'] . ".size=2.img";
                                        if (!file_exists("contenido/imagenes/paginas/" . $producto['imagen'])) {
                                            $url_img_curso = "https://www.infosicoes.com/paginas/" . $producto['imagen'] . ".size=2.img";
                                        }
                                        ?>
                                        <img src="<?php echo $url_img_curso; ?>" style="height:50px;width:75px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                        <br/><?php echo "<i style='color:gray;font-size: 7pt;'>" . $producto['horarios'] . "</i>"; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqciudad1 = query("SELECT nombre FROM ciudades WHERE id='" . $producto['id_ciudad'] . "' LIMIT 1 ");
                                        if (mysql_num_rows($rqciudad1) == 0) {
                                            echo "Sin dato registrado";
                                        } else {
                                            $rqciudad2 = mysql_fetch_array($rqciudad1);
                                            echo $rqciudad2['nombre'];
                                        }
                                        ?>         
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo my_date_curso($producto['fecha']);
                                        if ($producto['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . my_date_curso($producto['fecha2']) . "</i>";
                                        }
                                        if ($producto['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . my_date_curso($producto['fecha3']) . "</i>";
                                        }
                                        if ($producto['fecha'] == date("Y-m-d")) {
                                            echo "<br/><b style='background: #00a500;font-size: 10pt;color: #FFF;padding: 1px 5px;border-radius: 5px;'>HOY</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $producto['costo'] . ' Bs';
                                        if ($producto['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . $producto['costo2'] . " Bs</span>";
                                        }
                                        if ($producto['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;font-size: 7pt;'>" . $producto['costo3'] . " Bs</span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo ($producto['titulo']);
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>Docente: " . utf8_encode($producto['docente']) . "</i>";
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>" . $producto['short_link'] . "</i>";
                                        echo "<br/>";
                                        /* url_corta */
                                        $url_corta = 'https://cursos.bo/' . abs((int) $producto['id'] - 1000) . '/';
                                        echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        ?>
                                    </td>
                                    <?php if ($nivel_administrador == '1' || isset_organizador()) { ?>
                                        <td class="visible-lgNOT" id="box-datapart-<?php echo $producto['id']; ?>" style="min-height: 130px;">
                                            <script>data_participantes('<?php echo $producto['id']; ?>');</script>
                                        </td>
                                    <?php } ?>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['id_certificado'] == '0') {
                                            echo "No";
                                        } else {
                                            echo "<b class='text-success'>Si</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo '<b>' . $producto['cnt_reproducciones'] . '</b> <i style="color:gray;">vistas</i>';
                                        ?>
                                        <div class="visible-lgNOT" id="box-datapart2-<?php echo $producto['id']; ?>"></div>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqciudad1 = query("SELECT nombre,salon FROM cursos_lugares WHERE id='" . $producto['id_lugar'] . "' LIMIT 1 ");
                                        if (mysql_num_rows($rqciudad1) == 0) {
                                            echo "Sin dato registrado";
                                        } else {
                                            $rqciudad2 = mysql_fetch_array($rqciudad1);
                                            echo $rqciudad2['nombre'];
                                            echo "<br/>";
                                            echo "<i style='color:gray;'>" . $rqciudad2['salon'] . "</i>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" id="td-estado-<?php echo $producto['id']; ?>">
                                        <?php
                                        if ($producto['estado'] == '1') {
                                            echo "<b style='color:green;'>ACTIVADO</b>";
                                            echo "<br/>";
                                            switch ($producto['columna']) {
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
                                            if (acceso_cod('adm-cursos-estado')) {
                                                ?>
                                                <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                <br/>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');">Temporal</i>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
                                                <?php
                                            }
                                        } elseif ($producto['estado'] == '2') {
                                            ?>
                                            <b style='color:red;'>TEMPORAL</b><br/>
                                            <?php
                                            if (acceso_cod('adm-cursos-estado')) {
                                                ?>
                                                <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                <br/>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
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
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');">Temporal</i>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($nivel_administrador == '1' || $producto['estado'] == '1') {
                                            ?>
                                            <a href="<?php echo $producto['titulo_identificador']; ?>.html" target="_blank"><i class='fa fa-eye'></i> Visualizar</a>
                                            <br/>
                                            <a href="cursos-editar/<?php echo $producto['id']; ?>.adm"><i class='fa fa-edit'></i> Editar</a>
                                            <br/>
                                            <a href="cursos-participantes/<?php echo $producto['id']; ?>.adm"><i class='fa fa-users'></i> Inscritos</a>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                        <a onclick="duplicar_curso('<?php echo $producto['id']; ?>', '<?php echo str_replace('"', '', str_replace("'", '', $producto['titulo'])); ?>');" style="cursor:pointer;"><i class='fa fa-random'></i> Duplicar</a>
                                        <?php
                                        if (($nivel_administrador == '1' || isset_organizador()) && (int) $producto['cnt_participantes_aux'] == 0) {
                                            ?>
                                            <br/>
                                            <form action="" method="post">
                                                <input type="hidden" name="id_curso" value="<?php echo $producto['id']; ?>"/>
                                                <input type="hidden" name="delete-course" value="true"/>
                                                <button type="submit" style="cursor:pointer;" class="btn btn-default btn-xs" onclick="return confirm('Desea eliminar el curso?');">
                                                    <i class='fa fa-ban text-danger'></i> Eliminar
                                                </button>
                                            </form>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($producto['estado'] == '1') {
                                            ?>
                                            <br/>
                                            <a onclick="notificar_curso('<?php echo $producto['id']; ?>');" style="cursor:pointer;" class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-notificar_curso">
                                                <i class='fa fa-send'></i> Notificar
                                            </a>
                                            <?php
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
                            $urlget4 = '';
                            if (isset($busqueda)) {
                                $urlget3 = '/' . $busqueda;
                                if (isset($id_ciudad)) {
                                    $urlget4 = '/' . $id_ciudad;
                                }
                            }
                            ?>

                            <li><a href="cursos-listar/1<?php echo $urlget3; ?>.adm">Primero</a></li>                           
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
                                        echo '<li class="active"><a href="cursos-listar/' . $i . $urlget3 . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="cursos-listar/' . $i . $urlget3 . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="cursos-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>

<!-- duplicar curso -->
<script>
    function duplicar_curso(id_curso, nombre_curso) {
        if (confirm('DUPLICACION DE CURSO - Desea duplicar el curso ' + nombre_curso + ' ?')) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.duplicar_curso.php',
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


<script>
    function notificar_curso(id_curso) {
        $("#AJAXCONTENT-notificar_curso").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.notificar_curso.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.actualiza_segmento_de_notificacion.php',
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
    function enviar_notificacion_curso(dat) {
        $("#boxsend-" + dat).html('Enviando...');
        var arraydata = $("#form-segmento-notificacion").serialize();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.enviar_notificacion_curso.php?modenv=' + dat,
            data: arraydata,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#boxsend-" + dat).html(data);
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#td-estado-" + id_curso).html("Actualizando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.cambiar_estado_curso.php',
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
