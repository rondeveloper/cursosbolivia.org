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

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;

/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];

/* creacion de material */
if (isset_post('creacion-material')) {
    $nombre_material = post('nombre_material');
    query("INSERT INTO cursos_material (nombre_material) VALUES ('$nombre_material') ");
    $id_material = insert_id();
    logcursos('Creacion de material', 'material-creacion', 'material', $id_material);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}

/* actualizar-material */
if (isset_post('actualizar-material')) {
    $id_material = post('id_material');
    $estado_material = post('estado');
    $nombre_material = post('nombre_material');
    query("UPDATE cursos_material SET nombre_material='$nombre_material',estado='$estado_material' WHERE id='$id_material' LIMIT 1 ");
    logcursos('Edicion de material', 'material-edicion', 'material', $id_material);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro modificado correctamente.
</div>';
}

/* eliminar-material */
if (isset_post('eliminar-material')) {
    $id_material = post('id_material');
    query("UPDATE cursos_material SET estado='0' WHERE id='$id_material' ORDER BY id DESC ");
    logcursos('Eliminacion de material', 'material-eliminacion', 'material', $id_material);
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

/* registros */
$resultado1 = query("SELECT * FROM cursos_material WHERE estado IN (1,2) ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_material WHERE estado='1' ");
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
        <ul class="breadcrumb">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
            <li><a <?php echo loadpage('inicio'); ?>>Panel Principal</a></li>
            <li><a <?php echo loadpage('cursos-materiales'); ?>>Cursos virtuales</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right hidden-sm">
            <a class='btn btn-success active' data-toggle="modal" data-target="#MODAL-agregar_material"> <i class='fa fa-plus'></i> AGREGAR MATERIAL</a>
        </div>
        <h3 class="page-header"> LISTADO DE MATERIALES DE CURSOS <?php echo $txt_estado; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de materiales de cursos.
            </p>
        </blockquote>
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
                                <th class="visible-lgNOT" style="font-size:10pt;">Material</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Archivos</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Estado</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($material = fetch($resultado1)) {
                                $tr_class = '';
                                /* curso suspendido */
                                if ($material['sw_suspendido'] == 1) {
                                    $tr_class .= ' tr_curso_suspendido';
                                }
                                /* curso eliminado */
                                if ($material['estado'] == 3) {
                                    $tr_class .= ' tr_curso_eliminado';
                                }
                                /*
                                  if ($material['sw_cierre'] == 1) {
                                  $tr_class .= ' tr_curso_cerrado';
                                  }
                                 */
                                ?>
                                <tr class="<?php echo $tr_class; ?>">
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo "<span style='font-size:14pt;'>" . ($material['nombre_material']) . "</span>";
                                        echo "<br/><br/>";
                                        echo "<i style='color:gray;'>ENALCE P&Uacute;BLICO</i>";
                                        echo "<br/>";
                                        if ($nivel_administrador == '1') {
                                            /* url_ingreso */
                                            $url_corta = $dominio.'material-digital/' . $material['id'] . '/' . md5(md5($material['id'] . '-168')) . '.html';
                                            echo "<input type='text' class='form-control' value='" . $url_corta . "'/>";
                                        }
                                        echo "<span style='color:gray;font-size:8pt;' class='pull-right'>ID de material: " . $material['id'] . "</span>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT text-center">
                                        <?php
                                        $rqdcl1 = query("SELECT count(*) AS total FROM cursos_material_archivos WHERE id_material='" . $material['id'] . "' ");
                                        $rqdcl2 = fetch($rqdcl1);
                                        ?>
                                        <b style="color:#1d6381;font-size: 14pt;"><?php echo $rqdcl2['total']; ?></b>
                                        <br/>
                                        LECCIONES
                                    </td>
                                    <td class="visible-lgNOT" id="td-estado-<?php echo $material['id']; ?>">
                                        <?php
                                        if ($material['estado'] == '1') {
                                            echo "<b style='color:green;'>ACTIVADO</b>";
                                        } else {
                                            echo "DESACTIVADO";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($material['estado'] !== '3') {
                                            if ($nivel_administrador == '1' || $material['estado'] == '1' || $material['estado'] == '2') {
                                                ?>
            <!--                                                <a href="<?php echo $material['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default btn-block" style="color: #0089b5;" disabled><i class='fa fa-eye'></i> Visualizar</a>-->
                                                <a class="btn btn-xs btn-default btn-block" style="color: #0089b5;" data-toggle="modal" data-target="#MODAL-editar_material" onclick="editar_material('<?php echo $material['id']; ?>');"><i class='fa fa-edit'></i> Edici&oacute;n</a>
                                                <a class="btn btn-xs btn-default btn-block" style="color: #0089b5;" data-toggle="modal" data-target="#MODAL-mostrar_archivos" onclick="mostrar_archivos('<?php echo $material['id']; ?>');"><i class='fa fa-file-text'></i> Archivos</a>
            <!--                                                <a data-toggle="modal" data-target="#MODAL-cursos_asociados" class="btn btn-xs btn-default btn-block" style="color: #0089b5;" onclick="cursos_asociados('<?php echo $material['id']; ?>');"><i class='fa fa-university'></i> CURSOS</a>-->
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

                <hr/>

            </div>
        </div>
    </div>
</div>

<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
</script>


<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#td-estado-" + id_curso).html("Actualizando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-materiales.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
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
            url: 'pages/ajax/ajax.cursos-materiales.historial_curso.php',
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
            url: 'pages/ajax/ajax.cursos-materiales.cursos_asociados.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asociados").html(data);
            }
        });
    }
</script>

<!-- mostrar_archivos -->
<script>
    function mostrar_archivos(id_material) {
        $("#AJAXCONTENT-mostrar_archivos").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-material.mostrar_archivos.php',
            data: {id_material: id_material},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-mostrar_archivos").html(data);
            }
        });
    }
</script>

<!-- editar_material -->
<script>
    function editar_material(id_material) {
        $("#AJAXCONTENT-editar_material").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-material.editar_material.php',
            data: {id_material: id_material},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-editar_material").html(data);
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

<!-- MODAL mostrar_archivos -->
<div id="MODAL-mostrar_archivos" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ARCHIVOS DEL PAQUETE DE MATERIAL</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-mostrar_archivos"></div>

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



<!-- MODAL-agregar_material -->
<div id="MODAL-agregar_material" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NUEVO PAQUETE DE MATERIAL DIGITAL</h4>
            </div>
            <div class="modal-body">
                <p>Ingresa los datos del nuevo paquete de materiales digitales.</p>
                <hr/>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="nombre_material">Nombre del paquete de materiales:</label>
                        <input type="text" class="form-control" name="nombre_material" id="nombre_material" placeholder="Nombre del paquete de materiales..." required=""/>
                    </div>
                    <button type="submit" class="btn btn-default" name="creacion-material">CREAR MATERIAL</button>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- Modal editar_material -->
<div id="MODAL-editar_material" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE DATOS DE MATERIAL DIGITAL</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-editar_material"><div>
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
        