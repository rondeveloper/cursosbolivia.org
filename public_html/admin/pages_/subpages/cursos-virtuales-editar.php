<?php
/* id curso */
$id_curso_virtual = (int) $get[2];

/* mensaje */
$mensaje = '';

/* editar-pagina-principal */
if (isset_post('editar-pagina-principal')) {

    $contenido = post_html('contenido');

    query("UPDATE cursos_onlinecourse SET 
              contenido='$contenido' 
               WHERE id='$id_curso_virtual' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de contenido introductorio de curso virtual', 'curso-virtual-edicion', 'curso-virtual', $id_curso_virtual);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* edicion de curso */
if (isset_post('actualizar-datos')) {

    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $id_categoria = post('id_categoria');
    $imagen = post('imagen_actual');
    $sw_cert = post('sw_cert');
    $sw_enproceso = post('sw_enproceso');
    $sw_examen = post('sw_examen');
    
    if (isset_archivo('imagen')) {
        $imagen = time() . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/cursos/$imagen");
    }

    query("UPDATE cursos_onlinecourse SET 
              titulo='$titulo',
              id_categoria='$id_categoria',
              sw_cert='$sw_cert',
              sw_enproceso='$sw_enproceso',
              sw_examen='$sw_examen',
              imagen='$imagen' 
               WHERE id='$id_curso_virtual' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de datos de curso virtual', 'curso-virtual-edicion', 'curso-virtual', $id_curso_virtual);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos de curso actualizados correctamente.
    </div>';
}


/* agregar-material-onlinecourse */
if (isset_post('agregar-material-onlinecourse')) {
    if (isset_archivo('archivo')) {
        $nombre = post('nombre');
        $tipo_archivo = post('tipo_archivo');

        $id_curso_virtual = $id_curso_virtual;

        $arch = $id_curso_virtual . '-' . str_replace('.php','.txt',strtolower(archivoName('archivo')));
        move_uploaded_file(archivo('archivo'), $___path_raiz."contenido/archivos/cursos/$arch");
        query("INSERT INTO cursos_onlinecourse_material(id_onlinecourse, id_leccion, nombre, formato_archivo, nombre_fisico, estado) VALUES ('$id_curso_virtual','0','$nombre','$tipo_archivo','$arch','1')");

        logcursos('Agregado de material [curso online]['.$nombre.']', 'curso-virtual-edicion', 'curso-virtual', $id_curso_virtual);

        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
    }
}


/* agregar-pregunta-onlinecourse */
if (isset_post('agregar-pregunta-onlinecourse')) {
    $pregunta = post('pregunta');

    $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso_virtual' ");
    $rqmc2 = fetch($rqmc1);
    $id_curso_virtual = $rqmc2['id'];

    query("INSERT INTO cursos_onlinecourse_preguntas(id_onlinecourse, pregunta, estado) VALUES ('$id_curso_virtual','$pregunta','1')");
    $id_pregunta = insert_id();

    for ($i = 1; $i <= 7; $i++) {
        if (isset_post('respuesta-' . $i)) {
            $respuesta = post('respuesta-' . $i);
            if (isset_post('check-respuesta-' . $i)) {
                $sw_respuesta = '1';
            } else {
                $sw_respuesta = '0';
            }
            query("INSERT INTO cursos_onlinecourse_respuestas(id_pregunta, respuesta, sw_correcto) VALUES ('$id_pregunta','$respuesta','$sw_respuesta')");
        }
    }
    logcursos('Agregado de preguntas [curso online]', 'curso-edicion', 'curso', $id_curso_virtual);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
}

/* eliminacion de material curso online */
if(isset($get[5]) && $get[4]=='deletematerial'){
    $id_material = (int)$get[5];
    query("DELETE FROM cursos_onlinecourse_material WHERE id='$id_material' LIMIT 1 ");
    logcursos('Eliminacion de material descargable curso virtual['.$id_material.']', 'curso-virtual-edicion', 'curso-virtual-', $id_curso_virtual);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se elimino correctamente.
    </div>';
}


/* registros */
$resultado_paginas = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_curso_virtual' ORDER BY id DESC limit 1 ");
$curso = fetch($resultado_paginas);


editorTinyMCE('editor1');
$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>
<style>
    .modal-dialog{
        width: 800px !important;
    }
    .panel-primary>.panel-heading {
        border-color: #428bca!important;
    }
</style>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="cursos-virtuales-listar.adm">Cursos virtuales</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header">
            <i class='btn btn-success active hidden-sm'>CURSO VIRTUAL</i> | 
            <?php echo $curso['titulo']; ?>

        </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de Cursos registrados en Infosicoes.
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <?php
                if (acceso_cod('adm-cursos-estado')) {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">ESTADO DEL CURSO - <?php echo $curso['titulo']; ?></div>
                        <div class="panel-body">
                            <div class="row" style="background:#EEE;margin-bottom: 10px;padding:10px 0px;" id="box-cont-estado">
                                <div class="col-md-3">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                </div>
                                <?php
                                if ($curso['estado'] == '1') {
                                    ?>
                                    <div class="col-md-5">
                                        <span class="input-group-addon"><b style='color:green;'>ACTIVADO</b></span>
                                    </div>
                                    <?php
                                    if (acceso_cod('adm-cursos-estado')) {
                                        ?>
                                        <div class="col-md-4">
                                            <div>
                                                <input type="button" value="DESACTIVAR" class="btn btn-default btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'desactivado');"/>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="col-md-5">
                                        <span class="input-group-addon">DESACTIVADO</span>
                                    </div>
                                    <?php
                                    if (acceso_cod('adm-cursos-estado')) {
                                        ?>
                                        <div class="col-md-4">
                                            <div>
                                                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'activado');"/>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            $rqcp1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE id_curso='$id_curso_virtual' AND estado='1' ");
                            $rqcp2 = fetch($rqcp1);
                            $cnt_participantes = $rqcp2['total'];
                            ?>
                            <!--
                            En este curso se registraron <?php echo $cnt_participantes; ?> participantes -> <a href="cursos-participantes/<?php echo $id_curso_virtual; ?>.adm"> <i class="fa fa-group"></i> LISTADO DE PARTICIPANTES</a>
                            &nbsp;|&nbsp;
                            <a href="<?php echo $curso['titulo_identificador']; ?>.html" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR EL CURSO</a>
                            &nbsp;|&nbsp;
                            <a href="cursos-listar.adm"> <i class="fa fa-list"></i> LISTADO DE CURSOS</a>
                            -->
                        </div>
                    </div>
                    <?php
                }
                ?>


                <div class="panel panel-primary">
                    <div class="panel-heading">DATOS DEL CURSO - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">
                        <div>
                            <div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Datos principales del curso</div>
                                    <div class="panel-body">

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <table class="table table-striped">
                                                <?php
                                                $rqmc1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_curso_virtual' ");
                                                $rqmc2 = fetch($rqmc1);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <b>T&iacute;TULO DEL CURSO VIRTUAL:</b>
                                                    </td>
                                                    <td colspan="2">
                                                        <input type="text" class="form-control" value="<?php echo $rqmc2['titulo']; ?>" name="titulo"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>IMAGEN PRINCIPAL:</b>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($rqmc2['imagen'] == '') {
                                                            echo "<b>SIN IMAGEN</b>";
                                                        } else {
                                                            ?>
                                                            <a href="<?php echo $dominio_www; ?>contenido/imagenes/cursos/<?php echo $rqmc2['imagen']; ?>" target="_blank">
                                                                <img src="<?php echo $dominio_www; ?>cursos/<?php echo $rqmc2['imagen']; ?>.size=4.img" style="height:35px;"/>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <input type="file" class="form-control" value="" name="imagen"/>
                                                        <input type="hidden" class="form-control" value="<?php echo $rqmc2['imagen']; ?>" name="imagen_actual"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>CATEGORIA DEL CURSO:</b>
                                                    </td>
                                                    <td colspan="2">
                                                        <select class="form-control" name="id_categoria">
                                                            <?php
                                                            $rqdd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                            while ($rqdd2 = fetch($rqdd1)) {
                                                                $selected = "";
                                                                if ($rqmc2['id_categoria'] == $rqdd2['id']) {
                                                                    $selected = " selected='selected' ";
                                                                }
                                                                echo '<option value="' . $rqdd2['id'] . '" ' . $selected . '>' . $rqdd2['titulo'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>CERTIFICADO:</b>
                                                    </td>
                                                    <td colspan="2" class="text-center">
                                                        <?php
                                                        $check_h1 = '';
                                                        $check_h2 = ' checked="checked" ';
                                                        if ($rqmc2['sw_cert'] == '1') {
                                                            $check_h1 = ' checked="checked" ';
                                                            $check_h2 = '';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_cert" value="1" <?php echo $check_h1; ?> class=""> HABILITADO</label>
                                                        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                                                        <label><input type="radio" name="sw_cert" value="0" <?php echo $check_h2; ?> class=""> NO HABILITADO</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>ESTADO DEL CURSO:</b>
                                                    </td>
                                                    <td colspan="2" class="text-center">
                                                        <?php
                                                        $check_h1 = ' checked="checked" ';
                                                        $check_h2 = '';
                                                        if ($rqmc2['sw_enproceso'] == '1') {
                                                            $check_h1 = '';
                                                            $check_h2 = ' checked="checked" ';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_enproceso" value="0" <?php echo $check_h1; ?> class=""> COMPLETO</label>
                                                        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                                                        <label><input type="radio" name="sw_enproceso" value="1" <?php echo $check_h2; ?> class=""> EN PROCESO</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>EXAMEN:</b>
                                                    </td>
                                                    <td colspan="2" class="text-center">
                                                        <?php
                                                        $check_h1 = '';
                                                        $check_h2 = ' checked="checked" ';
                                                        if ($rqmc2['sw_examen'] == '1') {
                                                            $check_h1 = ' checked="checked" ';
                                                            $check_h2 = '';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_examen" value="1" <?php echo $check_h1; ?> class=""> HABILITADO</label>
                                                        &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                                                        <label><input type="radio" name="sw_examen" value="0" <?php echo $check_h2; ?> class=""> NO HABILITADO</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <input type="submit" class="btn btn-success active btn-block" name="actualizar-datos" value="ACTUALIZAR DATOS DEL CURSO"/>
                                                    </td>
                                                </tr>
                                            </table>

                                            <input type="hidden" name="id_curso_online" value="<?php echo $rqmc2['id']; ?>"/>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">INTRODUCCI&Oacute;N / BIENVENIDA / INICIO </div>
                    <div class="panel-body">

                        <form enctype="multipart/form-data" action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Introducci&oacute;n en texto: </span>
                                    </td>
                                    <td>
                                        <textarea name="contenido" id="editor1" style="width:100% !important;margin:auto;" rows="15"><?php echo $curso['contenido']; ?></textarea>
                                    </td>
                                </tr>
<!--                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp;  Video introductorio: </span>
                                    </td>
                                    <td>
                                        <div class="col-md-8">
                                            <input type="file" name="video_introductorio" class="form-control">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            
                                        </div>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Material descargable: </span>
                                    </td>
                                    <td>
                                        <table class="table table-striped table-condensed">
                                            <tr>
                                                <th colspan="6">MATERIAL DESCARGABLE PRINCIPAL</th>
                                            </tr>
                                            <?php
                                            $rqmc1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id_onlinecourse='$id_curso_virtual' AND estado='1' AND id_leccion='0' ");
                                            if (num_rows($rqmc1) == 0) {
                                                echo '<tr><td colspan="5"><div class="alert alert-info">
  <strong>Aviso!</strong> no se registro material descargable.
</div></td></tr>';
                                            }
                                            $cnt = 1;
                                            while ($producto = fetch($rqmc1)) {
                                                ?>
                                                <tr>
                                                    <td class="visible-lg"><?php echo $cnt++; ?></td>
                                                    <td class="visible-lg">
                                                        <?php
                                                        echo $producto['nombre'];
                                                        ?>         
                                                    </td>
                                                    <td class="visible-lg">
                                                        <?php
                                                        echo $producto['formato_archivo'];
                                                        ?> 
                                                    </td>
                                                    <td class="visible-lg">
                                                        <?php
                                                        echo $producto['nombre_fisico'];
                                                        ?> 
                                                    </td>
                                                    <td class="visible-lg" style="width:120px;">
                                                        <a href="<?php echo $dominio_www; ?>contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-info active"><i class='fa fa-eye'></i> ver/descargar</a>
                                                    </td>
                                                    <td class="visible-lg" style="width:120px;">
                                                        <a href="cursos-virtuales-editar/<?php echo $id_curso_virtual; ?>/0/deletematerial/<?php echo $producto['id']; ?>.adm" class="btn btn-xs btn-danger active" onclick="return confirm('Desea eliminar el archivo?');"><i class='fa fa-eye'></i> eliminar</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                        <a class="btn btn-warning btn-xs active pull-right" data-toggle="modal" data-target="#MODAL-material-onlinecourse"><i class="fa fa-plus"></i> AGREGAR MATERIAL DESCARGABLE</a>


                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="hidden" name="id" value="<?php echo $curso['id']; ?>"/>
                                            <input type="hidden" name="current_video_introductorio" value="<?php echo $curso['video_introductorio']; ?>"/>
                                            <input type="submit" name="editar-pagina-principal" value="ACTUALIZAR DATOS DEL CURSO ONLINE" class="btn btn-success btn-block btn-animate-demo active"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>



<!-- Modal - material-onlinecourse -->
<div id="MODAL-material-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MATERIAL DESCARGABLE</h4>
            </div>
            <div class="modal-body">
                <p>
                    Ingrese a continuaci&oacute;n los datos del material descargable asignado al curso.
                </p>
                <hr/>
                <form action="" method="post" enctype="multipart/form-data">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Nombre:
                                <br/>
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre del material"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Tipo de archivo:
                                <br/>
                                <select class="form-control" name='tipo_archivo'>
                                    <option value="PDF">Archivo PDF</option>
                                    <option value="WORD">Archivo WORD</option>
                                    <option value="PPT">Archivo POWER POINT</option>
                                    <option value="ZIP">Comprimido ZIP</option>
                                    <option value="RAR">Comprimido RAR</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Archivo:
                                <br/>
                                <input type="file" name="archivo" class="form-control" required=""/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="hidden" name="id_leccion" id="id_leccion" value=""/>
                                    <input type="submit" name="agregar-material-onlinecourse" value="AGREGAR ARCHIVO" class="btn btn-success btn-lg btn-animate-demo active"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal - material-onlinecourse -->




<script>
    function actualiza_lugares() {
        $("#select_lugar").html('<option>Cargando...</option>');
        var id_ciudad = $("#select_ciudad").val();
        var current_id_lugar = $("#current_id_lugar").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.actualiza_lugares.php',
            data: {id_ciudad: id_ciudad, current_id_lugar: current_id_lugar},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_lugar").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        var current_id_ciudad = $("#current_id_ciudad").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: current_id_ciudad},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
                actualiza_lugares();
            }
        });
    }
</script>


<script>
    function busca_etiquetas(dat) {
        $("#AJAXBOX-busca_etiquetas").html('Buscando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.busca_etiquetas.php',
            data: {tag: dat, id_curso: '<?php echo $id_curso_virtual; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-busca_etiquetas").html(data);
            }
        });
    }
</script>
<script>
    function asocia_etiqueta(dat) {
        $("#AJAXBOX-asocia_etiqueta").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.asocia_etiqueta.php',
            data: {id_tag: dat, id_curso: '<?php echo $id_curso_virtual; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-asocia_etiqueta").html(data);
                $("#idtag-" + dat).css('display', 'none');
            }
        });
    }
</script>
<script>
    function quitar_etiqueta(dat) {
        $("#tr-tag-" + dat).html('<td colspan="2">Cargando...</td>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.quitar_etiqueta.php',
            data: {id_tag: dat, id_curso: '<?php echo $id_curso_virtual; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#tr-tag-" + dat).css('display', 'none');
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#box-cont-estado").html("<div class='col-md-3'>Actualizando...</div>");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-virtuales-editar.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-cont-estado").html(data);
            }
        });
    }
</script>



<?php

function my_fecha_at1($dat) {
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $y = date("Y", strtotime($dat));
    $mes = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return "$d de " . $mes[(int) $m] . " de $y";
}

function verificador_fecha($dat) {
    if ($dat == '') {
        return "0000-00-00";
    } else {
        return $dat;
    }
}
/*
function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}
*/

function refactor_titcurso($dat) {
    $rqc1 = query("SELECT nombre FROM ciudades ");
    $busc = array();
    while ($rqc2 = fetch($rqc1)) {
        array_push($busc, "en " . $rqc2['nombre']);
    }
    $remm = '';
    return trim(str_replace($busc, $remm, $dat));
}
