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

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;

/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = mysql_fetch_array($rqda1);
$nivel_administrador = $rqda2['nivel'];

/* creacion de grupo */
if (isset_post('creacion-grupo')) {
    $titulo = post('titulo');
    query("INSERT INTO cursos_agrupaciones (titulo,estado) VALUES ('$titulo','2') ");
    $id_grupo = mysql_insert_id();
    logcursos('Creacion de grupo', 'grupo-creacion', 'grupo', $id_grupo);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}

/* actualizar-grupo */
if (isset_post('actualizar-grupo')) {
    $id_grupo = post('id_grupo');
    $estado_grupo = post('estado');
    $titulo = post('titulo');
    query("UPDATE cursos_agrupaciones SET titulo='$titulo',estado='$estado_grupo' WHERE id='$id_grupo' LIMIT 1 ");
    logcursos('Edicion de grupo', 'grupo-edicion', 'grupo', $id_grupo);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro modificado correctamente.
</div>';
}

/* eliminar-grupo */
if (isset_post('eliminar-grupo')) {
    $id_grupo = post('id_grupo');
    query("UPDATE cursos_agrupaciones SET estado='0' WHERE id='$id_grupo' ORDER BY id DESC ");
    logcursos('Eliminacion de grupo', 'grupo-eliminacion', 'grupo', $id_grupo);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro eliminado correctamente.
</div>';
}

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

/* duplicacion de grupo */
if (isset_post('duplicar-grupo')) {
    $id_grupo_dc = post('id_grupo');
    $cnt_cursos_dc = post('cnt_cursos');
    $fecha_dc = post('fecha');
    $horarios_dc = post('horarios');
    
    if ((strtotime($fecha_dc) < strtotime(date("Y-m-d")))) {
        $mensaje = '<br/><div class="alert alert-danger">
  <strong>ERROR</strong> la fecha no puede ser inferior a la fecha de hoy.
</div>';
    }
    
    $rqc1 = query("SELECT * FROM cursos_agrupaciones WHERE id='$id_grupo_dc' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);
    query("INSERT INTO cursos(
           id_ciudad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_material, 
           id_abreviacion, 
           fecha, 
           titulo_identificador, 
           titulo, 
           contenido, 
           lugar, 
           horarios, 
           duracion, 
           imagen, 
           imagen2, 
           imagen3, 
           imagen4, 
           imagen_gif, 
           google_maps, 
           expositor, 
           colaborador, 
           id_whats_numero, 
           whats_mensaje, 
           fb_txt_requisitos, 
           fb_txt_dirigido, 
           fb_hashtags, 
           observaciones, 
           incrustacion, 
           seccion, 
           pixelcode, 
           texto_qr, 
           sw_siguiente_semana, 
           short_link, 
           fecha_registro, 
           estado
           ) VALUES (
           '" . $rqc2['id_ciudad'] . "',
           '" . $rqc2['id_organizador'] . "',
           '" . $rqc2['id_lugar'] . "',
           '" . $rqc2['id_docente'] . "',
           '" . $rqc2['id_material'] . "',
           '" . $rqc2['id_abreviacion'] . "',
           '" . $fecha_dc . "',
           '" . str_replace('-tmp','',$rqc2['titulo_identificador']).'-tmp' . "',
           '" . $rqc2['titulo'] . "',
           '" . $rqc2['contenido'] . "',
           '" . $rqc2['lugar'] . "',
           '" . $horarios_dc . "',
           '" . $rqc2['duracion'] . "',
           '" . $rqc2['imagen'] . "',
           '" . $rqc2['imagen2'] . "',
           '" . $rqc2['imagen3'] . "',
           '" . $rqc2['imagen4'] . "',
           '" . $rqc2['imagen_gif'] . "',
           '" . $rqc2['google_maps'] . "',
           '" . $rqc2['expositor'] . "',
           '" . $rqc2['colaborador'] . "',
           '" . $rqc2['id_whats_numero'] . "',
           '" . $rqc2['whats_mensaje'] . "',
           '" . $rqc2['fb_txt_requisitos'] . "',
           '" . $rqc2['fb_txt_dirigido'] . "',
           '" . $rqc2['fb_hashtags'] . "',
           '" . $rqc2['observaciones'] . "',
           '" . $rqc2['incrustacion'] . "',
           '" . $rqc2['seccion'] . "',
           '" . addslashes($rqc2['pixelcode']) . "',
           '" . $rqc2['texto_qr'] . "',
           '" . $rqc2['sw_siguiente_semana'] . "',
           '" . $rqc2['short_link'] . "',
           '" . $fecha_registro . "',
           '2'
           )");
    $id_grupo_nuevo = mysql_insert_id();
    logcursos('Duplicacion de grupo', 'grupo-creacion', 'grupo', $id_grupo_nuevo);
    
    for($i=0;$i<=(int)$cnt_cursos_dc;$i++){
        if(isset_post('c-'.$i)){
            $id_curso_dc = post('c-'.$i);
            $rqc1 = query("SELECT * FROM cursos_agrupaciones WHERE id='$id_curso_dc' ORDER BY id DESC limit 1 ");
            $rqc2 = mysql_fetch_array($rqc1);
            query("INSERT INTO cursos(
           id_ciudad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_material, 
           id_abreviacion, 
           fecha, 
           titulo_identificador, 
           titulo, 
           contenido, 
           lugar, 
           horarios, 
           duracion, 
           imagen, 
           imagen2, 
           imagen3, 
           imagen4, 
           imagen_gif, 
           google_maps, 
           expositor, 
           colaborador, 
           id_whats_numero, 
           whats_mensaje, 
           fb_txt_requisitos, 
           fb_txt_dirigido, 
           fb_hashtags, 
           observaciones, 
           incrustacion, 
           seccion, 
           pixelcode, 
           texto_qr, 
           sw_siguiente_semana, 
           short_link, 
           fecha_registro, 
           estado
           ) VALUES (
           '" . $rqc2['id_ciudad'] . "',
           '" . $rqc2['id_organizador'] . "',
           '" . $rqc2['id_lugar'] . "',
           '" . $rqc2['id_docente'] . "',
           '" . $rqc2['id_material'] . "',
           '" . $rqc2['id_abreviacion'] . "',
           '" . $fecha_dc . "',
           '" . str_replace('-tmp','',$rqc2['titulo_identificador']).'-tmp' . "',
           '" . $rqc2['titulo'] . "',
           '" . $rqc2['contenido'] . "',
           '" . $rqc2['lugar'] . "',
           '" . $horarios_dc . "',
           '" . $rqc2['duracion'] . "',
           '" . $rqc2['imagen'] . "',
           '" . $rqc2['imagen2'] . "',
           '" . $rqc2['imagen3'] . "',
           '" . $rqc2['imagen4'] . "',
           '" . $rqc2['imagen_gif'] . "',
           '" . $rqc2['google_maps'] . "',
           '" . $rqc2['expositor'] . "',
           '" . $rqc2['colaborador'] . "',
           '" . $rqc2['id_whats_numero'] . "',
           '" . $rqc2['whats_mensaje'] . "',
           '" . $rqc2['fb_txt_requisitos'] . "',
           '" . $rqc2['fb_txt_dirigido'] . "',
           '" . $rqc2['fb_hashtags'] . "',
           '" . $rqc2['observaciones'] . "',
           '" . $rqc2['incrustacion'] . "',
           '" . $rqc2['seccion'] . "',
           '" . addslashes($rqc2['pixelcode']) . "',
           '" . $rqc2['texto_qr'] . "',
           '" . $rqc2['sw_siguiente_semana'] . "',
           '" . $rqc2['short_link'] . "',
           '" . $fecha_registro . "',
           '2'
           )");
            $id_curso_nuevo = mysql_insert_id();
            query("INSERT INTO cursos_rel_agrupcursos (id_grupo,id_curso) VALUES ('$id_grupo_nuevo','$id_curso_nuevo') ");
            logcursos('Duplicacion de curso por grupo', 'curso-creacion', 'curso', $id_curso_nuevo);
        }
    }
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> grupo duplicado correctamente.
</div>';
}

/* registros */
$resultado1 = query("SELECT * FROM cursos_agrupaciones WHERE estado IN (0,1,2) ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_agrupaciones WHERE estado='1' ");
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
            <li class="active">Grupos</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right hidden-sm">
            <a class='btn btn-success active' data-toggle="modal" data-target="#MODAL-agregar_grupo"> <i class='fa fa-plus'></i> AGREGAR GRUPO</a>
        </div>
        <h3 class="page-header"> LISTADO DE AGRUPACIONES DE CURSOS <?php echo $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de agrupaciones de cursos.
            </p>
        </blockquote>
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
                                <th class="visible-lgNOT" style="font-size:10pt;">Imagen</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Grupo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Info</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Estado</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cntg = 1;
                            while ($grupo = mysql_fetch_array($resultado1)) {
                                $tr_class = '';
                                /* curso suspendido */
                                if ($grupo['sw_suspendido'] == 1) {
                                    $tr_class .= ' tr_curso_suspendido';
                                }
                                /* curso eliminado */
                                if ($grupo['estado'] == 3) {
                                    $tr_class .= ' tr_curso_eliminado';
                                }
                                /*
                                  if ($grupo['sw_cierre'] == 1) {
                                  $tr_class .= ' tr_curso_cerrado';
                                  }
                                 */
                                /* imagen */
                                $url_imagen = 'https://cursos.bo/contenido/imagenes/banners/1528991771banner-uno.png';
                                if ($grupo['imagen'] !== '') {
                                    $url_imagen = 'contenido/imagenes/paginas/' . $grupo['imagen'];
                                }
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="visible-lgNOT">
                                        <?php echo $cntg++; ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width: 200px;text-align: center;">
                                        <img src="<?php echo $url_imagen; ?>" style="width: 200px;"/>
                                        <br/>
                                        <br/>
                                        <?php echo '<b>'.date("d/M/Y",strtotime($grupo['fecha'])).'</b>'; ?>
                                        <br/>
                                        <?php echo $grupo['horarios']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo "<span style='font-size:14pt;'>" . ($grupo['titulo']) . "</span>";
                                        echo "<br/><br/>";
                                        /* url_corta */
                                        $url_corta = 'https://cursos.bo/g/' . $grupo['id'] . '/';
                                        echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>CURSOS</i>";
                                        echo "<br/>";
                                        /* cursos */
                                        $rqda1 = query("SELECT c.id,c.titulo,c.titulo_identificador,c.fecha,c.estado FROM cursos_rel_agrupcursos r INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id_grupo='" . $grupo['id'] . "' ");
                                        if (mysql_num_rows($rqda1) == 0) {
                                            ?>
                                            <p>Sin cursos registrados.</p>
                                            <?php
                                        }
                                        ?>
                                        <table class="table table-striped table-bordered">
                                            <?php
                                            while ($rqda2 = mysql_fetch_array($rqda1)) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $rqda2['titulo']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        switch ($rqda2['estado']) {
                                                            case 'l':
                                                                echo 'Activado';
                                                                break;
                                                            case '2':
                                                                echo 'Temporal';
                                                                break;
                                                            default:
                                                                echo 'Desactivado';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date("d/M/y", strtotime($rqda2['fecha'])); ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?php echo $rqda2['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-info" title="Visualizar"><i class="fa fa-eye"></i></a>
                                                        &nbsp;
                                                        <a href="cursos-editar/<?php echo $rqda2['id']; ?>.adm" class="btn btn-xs btn-info" title="Editar"><i class="fa fa-edit"></i></a>
                                                        &nbsp;
                                                        <a href="cursos-participantes/<?php echo $rqda2['id']; ?>.adm" class="btn btn-xs btn-info" title="Participantes"><i class="fa fa-group"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                        <?php
                                        echo "<span style='color:gray;font-size:8pt;' class='pull-right'>ID de grupo: " . $grupo['id'] . "</span>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        $rqdcl1 = query("SELECT count(*) AS total FROM cursos_rel_agrupcursos WHERE id_grupo='" . $grupo['id'] . "' ");
                                        $rqdcl2 = mysql_fetch_array($rqdcl1);
                                        ?>
                                        <b style="color:#1d6381;font-size: 14pt;"><?php echo $rqdcl2['total']; ?></b>
                                        <br/>
                                        CURSOS
                                        <br/>
                                        <br/>
                                        <br/>
                                        <?php echo $grupo['cnt_reproducciones']; ?>
                                        <br/>
                                        vistas
                                        <?php
                                        $rqdcpig1 = query("SELECT DISTINCT nombres,apellidos FROM cursos_participantes WHERE estado IN (0,1) AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='" . $grupo['id'] . "') ");
                                        $cnt_participantes = mysql_num_rows($rqdcpig1);
                                        echo '<br/>';
                                        echo '<br/>';
                                        echo '<br/>';
                                        echo $cnt_participantes;
                                        echo '<br/>';
                                        echo 'participantes';
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" id="td-estado-<?php echo $grupo['id']; ?>">
                                        <?php
                                        if ($grupo['estado'] == '1') {
                                            ?>
                                            <b style='color:green;'>ACTIVADO</b>
                                            <br/><br/>
                                            <?php
                                            if (acceso_cod('adm-cursos-estado')) {
                                                ?>
                                                <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                <br/>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_grupo('<?php echo $grupo['id']; ?>', 'desactivado');">Desactivado</i>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            DESACTIVADO
                                            <br/>
                                            <br/>
                                            <?php
                                            if (acceso_cod('adm-cursos-estado')) {
                                                ?>
                                                <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
                                                <br/>
                                                <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_grupo('<?php echo $grupo['id']; ?>', 'activado');">Activado</i>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a href="grupo/<?php echo $grupo['titulo_identificador']; ?>.html" class="btn btn-xs btn-default btn-block" style="color: #0089b5;" target="_blank"><i class='fa fa-eye'></i> Visualizar</a>
                                        <a href="cursos-grupos-editar/<?php echo $grupo['id']; ?>.adm" class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-edit'></i> Edici&oacute;n</a>
                                        <a class="btn btn-xs btn-default btn-block" style="color: #0089b5;" data-toggle="modal" data-target="#MODAL-mostrar_cursos" onclick="mostrar_cursos('<?php echo $grupo['id']; ?>');"><i class='fa fa-file-text'></i> Cursos</a>
                                        <a <?php echo loadpage('cursos-grupos-participantes/' . $grupo['id']); ?> class="btn btn-xs btn-default btn-block" style="color: #0089b5;"><i class='fa fa-group'></i> Participantes</a>
                                        <a onclick="duplicar_grupo('<?php echo $grupo['id']; ?>');" style="cursor:pointer;color: #0089b5;" class="btn btn-xs btn-default btn-block" data-toggle="modal" data-target="#MODAL-duplicar_grupo" ><i class='fa fa-random'></i> Duplicar</a>
                                        <a onclick="operaciones_grupo('<?php echo $grupo['id']; ?>');" style="cursor:pointer;color: #0089b5;" class="btn btn-xs btn-default btn-block" data-toggle="modal" data-target="#MODAL-operaciones_grupo" ><i class='fa fa-cog'></i> Operaciones</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <hr/>

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='contenido/imagenes/images/loader.gif'/></div>";
</script>


<script>
    function cambiar_estado_grupo(id_grupo, estado) {
        $("#td-estado-" + id_grupo).html("Actualizando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.cambiar_estado_grupo.php',
            data: {id_grupo: id_grupo, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#td-estado-" + id_grupo).html(data);
            }
        });
    }
</script>

<!-- historial_curso -->
<script>
    function historial_curso(id_curso) {
        $("#AJAXCONTENT-historial_curso").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-gruposes.historial_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-gruposes.cursos_asociados.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asociados").html(data);
            }
        });
    }
</script>

<!-- mostrar_cursos -->
<script>
    function mostrar_cursos(id_grupo) {
        $("#AJAXCONTENT-mostrar_cursos").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.mostrar_cursos.php',
            data: {id_grupo: id_grupo},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-mostrar_cursos").html(data);
            }
        });
    }
</script>

<!-- editar_grupo -->
<script>
    function editar_grupo(id_grupo) {
        $("#AJAXCONTENT-editar_grupo").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.editar_grupo.php',
            data: {id_grupo: id_grupo},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-editar_grupo").html(data);
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

<!-- MODAL mostrar_cursos -->
<div id="MODAL-mostrar_cursos" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CURSOS CORRESPONDIENTES A LA AGRUPACI&Oacute;N</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-mostrar_cursos"></div>

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



<!-- MODAL-agregar_grupo -->
<div id="MODAL-agregar_grupo" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NUEVO PAQUETE DE GRUPO DIGITAL</h4>
            </div>
            <div class="modal-body">
                <p>Ingresa los datos del nuevo paquete de grupos digitales.</p>
                <hr/>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="titulo">Nombre del paquete de grupos:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Nombre del paquete de grupos..." required=""/>
                    </div>
                    <input type="submit" class="btn btn-default" name="creacion-grupo" value="CREAR GRUPO"/>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal editar_grupo -->
<div id="MODAL-editar_grupo" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE DATOS DE AGRUPACI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-editar_grupo"><div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- duplicar_grupo -->
<script>
    function duplicar_grupo(id_grupo) {
        $("#AJAXCONTENT-duplicar_grupo").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.duplicar_grupo.php',
            data: {id_grupo: id_grupo},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-duplicar_grupo").html(data);
            }
        });
    }
</script>

<!-- Modal duplicar_grupo -->
<div id="MODAL-duplicar_grupo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DUPLICACI&Oacute;N DE GRUPO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-duplicar_grupo"><div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- operaciones_grupo -->
<script>
    function operaciones_grupo(id_grupo) {
        $("#AJAXCONTENT-operaciones_grupo").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.operaciones_grupo.php',
            data: {id_grupo: id_grupo},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-operaciones_grupo").html(data);
            }
        });
    }
</script>

<!-- Modal operaciones_grupo -->
<div id="MODAL-operaciones_grupo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">OPERACIONES DE GRUPO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-operaciones_grupo"><div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
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
