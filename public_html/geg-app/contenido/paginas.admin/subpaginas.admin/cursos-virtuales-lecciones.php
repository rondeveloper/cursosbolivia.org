<?php
/* ID ONLINE COURSE */
$id_onlinecourse = (int) $get[2];

/* ID CURSO */
$qrdc1 = query("SELECT id_curso FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$qrdc2 = mysql_fetch_array($qrdc1);
$id_curso = $qrdc2['id_curso'];

/* CURSO */
$qrdcc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$qrdcc2 = mysql_fetch_array($qrdcc1);
$titulo_identificador_del_curso = $qrdcc2['titulo_identificador'];

/* MENSAJE */
$mensaje = '';


/* editar-leccion */
if (isset_post('editar-leccion')) {

    $id_leccion = post('id_leccion');
    $titulo = post('titulo');
    $minutos = post('minutos');

    query("UPDATE cursos_onlinecourse_lecciones SET 
              titulo='$titulo',  
              minutos='$minutos'  
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    
    logcursos('Edicion de datos de leccion [L:'.$id_leccion.']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-leccion-contenido-HTML */
if (isset_post('editar-leccion-contenido-HTML')) {
    $id_leccion = post('id_leccion');
    $contenido = post_html('contenido');
    query("UPDATE cursos_onlinecourse_lecciones SET 
              contenido='$contenido' 
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-leccion-contenido-VIDEO */
if (isset_post('editar-leccion-contenido-VIDEO')) {
    $id_leccion = post('id_leccion');
    $video = post('video');
    query("UPDATE cursos_onlinecourse_lecciones SET 
              video='$video' 
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de video de leccion [L:'.$id_leccion.']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-leccion-contenido-VIDEO-local */
if (isset_post('editar-leccion-contenido-VIDEO-local')) {
    if (isset_archivo('localvideofile')) {
        $id_leccion = post('id_leccion');
        $arch_vid = 'CV'.$id_onlinecourse . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), "contenido/videos/cursos/$arch_vid");
        query("UPDATE cursos_onlinecourse_lecciones SET 
                  sw_vimeo='0', 
                  localvideofile='$arch_vid' 
                   WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de video de leccion, agregado de video [L:'.$id_leccion.']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
        $mensaje .= '<div class="alert alert-success">
          <strong>Exito!</strong> datos actualizados correctamente.
        </div>';
    }
}

/* editar-leccion-contenido-VIDEO-sw_vimeo */
if (isset_post('editar-leccion-contenido-VIDEO-sw_vimeo')) {
    $id_leccion = post('id_leccion');
    $sw_vimeo = post('sw_vimeo');
    query("UPDATE cursos_onlinecourse_lecciones SET 
              sw_vimeo='$sw_vimeo' 
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de video de leccion [L:'.$id_leccion.'][vimeo/local]', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}


/* eliminacion de material adicional */
if (isset($get[5]) && $get[4] == 'deletematerial') {
    $id_material_adicional_a_eliminar = (int) $get[5];
    $qrma1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id='$id_material_adicional_a_eliminar' AND id_onlinecourse='$id_onlinecourse' LIMIT 1 ");
    if (mysql_num_rows($qrma1) > 0) {
        $qrma2 = mysql_fetch_array($qrma1);
        $filename = $qrma2['nombre_fisico'];
        $urlfile = 'contenido/archivos/cursos/' . $filename;
        if (file_exists($urlfile)) {
            unlink($urlfile);
        }
        query("DELETE FROM cursos_onlinecourse_material WHERE id='$id_material_adicional_a_eliminar' AND id_onlinecourse='$id_onlinecourse' LIMIT 1 ");
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> registro eliminado correctamente.
    </div>';
    }
}



/* agregar-material-onlinecourse */
if (isset_post('agregar-material-onlinecourse')) {
    if (isset_archivo('archivo')) {
        $nombre = post('nombre');
        $tipo_archivo = post('tipo_archivo');
        $id_leccion = post('id_leccion');

        $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
        $rqmc2 = mysql_fetch_array($rqmc1);
        $id_onlinecourse = $rqmc2['id'];

        $arch = $id_curso . '-' . archivoName('archivo');
        move_uploaded_file(archivo('archivo'), "contenido/archivos/cursos/$arch");
        query("INSERT INTO cursos_onlinecourse_material(id_onlinecourse, nombre, formato_archivo, nombre_fisico, id_leccion, estado) VALUES ('$id_onlinecourse','$nombre','$tipo_archivo','$arch','$id_leccion','1')");

        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
    }
}


/* agregar-pregunta-onlinecourse */
if (isset_post('agregar-pregunta-onlinecourse')) {
    $pregunta = post('pregunta');
    $id_leccion = post('id_leccion');

    $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
    $rqmc2 = mysql_fetch_array($rqmc1);
    $id_onlinecourse = $rqmc2['id'];

    query("INSERT INTO cursos_onlinecourse_preguntas(id_onlinecourse,id_leccion,pregunta,estado) VALUES ('$id_onlinecourse','$id_leccion','$pregunta','1')");
    $id_pregunta = mysql_insert_id();

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
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
}

/* crear-leccion-onlinecourse */
if (isset_post('crear-leccion-onlinecourse')) {
    $titulo = post('titulo');
    $sw_vimeo = post('sw_vimeo');
    $rqnl1 = query("SELECT nro_leccion FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion DESC limit 1 ");
    $rqnl2 = mysql_fetch_array($rqnl1);
    $nro_leccion = ((int) $rqnl2['nro_leccion']) + 1;
    $urltag = $id_onlinecourse . '-' . $nro_leccion . '-' . md5(md5(rand(9, 9999)));
    query("INSERT INTO cursos_onlinecourse_lecciones(id_onlinecourse,nro_leccion,urltag,titulo,sw_vimeo,estado) VALUES ('$id_onlinecourse','$nro_leccion','$urltag','$titulo','$sw_vimeo','1')");
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se creo correctamente.
    </div>';
}

/* agregar-audio-presentacion */
if (isset_post('agregar-audio-presentacion')) {
    $id_leccion = post('id_leccion');
    /* audio */
    if (is_uploaded_file(archivo('audio'))) {
        $nombreaudio = 'AP' . $id_leccion . '-' . str_replace(' ', '-', archivoName('audio'));
        move_uploaded_file(archivo('audio'), 'contenido/audios/presentaciones/' . $nombreaudio);
        query("UPDATE cursos_onlinecourse_lecciones SET audio_presentacion='$nombreaudio' WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> <b>AUDIO DE PRESENTACI&Oacute;N SUBIDO EXITOSAMENTE</b>.
    </div>';
    } else {
        $mensaje .= '<div class="alert alert-success">
      <strong>Error!</strong> AUDIO NO SUBIDO.
    </div>';
    }
}

$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);

/* url_corta */
$url_corta = 'https://infosicoes.com/crs-' . $curso['id'] . '.html';

/* editores de texto */
$ids_editores = 'editor1';
$rqlc1 = query("SELECT id FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ");
while ($rqlc2 = mysql_fetch_array($rqlc1)) {
    $ids_editores .= ',editor-leccion-' . $rqlc2['id'];
}
editorTinyMCE($ids_editores);

$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

$rqdco1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$curso = mysql_fetch_array($rqdco1);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="cursos-virtuales-editar/<?php echo $id_onlinecourse; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO</a>
            <!--
            <a href="<?php echo $titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="curso-online/<?php echo $curso['urltag']; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR CURSO ONLINE</a>
            &nbsp;|&nbsp;
            <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="cursos-participantes/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-group"></i> PARTICIPANTES</a>
            -->
        </div>
        <h3 class="page-header"> <i class="btn btn-success active btn-sm">LECCIONES</i> <?php echo $curso['titulo']; ?> </h3>
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
                $rqlc1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                $cnt = 0;
                $cnt_lecciones = mysql_num_rows($rqlc1);
                if ($cnt_lecciones == 0) {
                    ?>
                    <div class="alert alert-info">
                        <strong>AVISO</strong> el curso no tiene asignado ninguna lecci&oacute;n.
                    </div>
                    <?php
                }
                ?>

                <hr/>
                <b>LECCIONES DEL CURSO: </b> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $cnt_lecciones; ?> lecciones &nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-info active"  data-toggle="modal" data-target="#MODAL-crear-leccion-onlinecourse"><i class="fa fa-plus"></i> AGREGAR LECCION</a>
                <hr/>

                <?php
                while ($leccion = mysql_fetch_array($rqlc1)) {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">LECCION/CAPITULO/TEMA <?php echo $leccion['nro_leccion']; ?> - NOMBRE: <?php echo $leccion['titulo']; ?></div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" action="" method="post">
                                <table style="width:100%;" class="table table-striped">
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo / duraci&oacute;n minima (minutos): </span>
                                        </td>
                                        <td>
                                            <input type="text" name="titulo" value="<?php echo $leccion['titulo']; ?>" class="form-control" id="date">
                                        </td>
                                        <td style="width: 80px;">
                                            <input type="number" name="minutos" value="<?php echo $leccion['minutos']; ?>" class="form-control" id="date">
                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $id_curso; ?>"/>
                                            <input type="hidden" name="id_leccion" value="<?php echo $leccion['id']; ?>"/>
                                            <input type="hidden" name="current_video" value="<?php echo $leccion['video']; ?>"/>
                                            <input type="submit" name="editar-leccion" value="ACTUALIZAR T&Iacute;TULO" class="btn btn-success active pull-right"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Presentaci&oacute;n: </span>
                                        </td>
                                        <td colspan="3">
                                            <b class="btn btn-info active" data-toggle="modal" data-target="#MODAL-leccion_contenido_video-<?php echo $leccion['id']; ?>">ADMINISTRAR VIDEO</b>
                                            <b class="btn btn-info active" data-toggle="modal" data-target="#MODAL-leccion_contenido_html-<?php echo $leccion['id']; ?>">ADMINISTRAR HTML</b>
                                            <b class="btn btn-info active" data-toggle="modal" data-target="#MODAL-editar_presentacion_curso" onclick="presentacion_leccion_p1('<?php echo $leccion['id']; ?>');">ADMINISTRAR PRESENTACI&Oacute;N</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Material y preguntas de evaluaci&oacute;n: </span>
                                        </td>
                                        <td colspan="3">
                                            <b class="btn btn-warning" data-toggle="modal" data-target="#MODAL-leccion_material-<?php echo $leccion['id']; ?>">MATERIAL DESCARGABLE</b>
                                            <b class="btn btn-warning" data-toggle="modal" data-target="#MODAL-leccion_preguntas-<?php echo $leccion['id']; ?>">PREGUNTAS DE EVALUACI&Oacute;N</b>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div id="MODAL-leccion_contenido_html-<?php echo $leccion['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">CONTENIDO DE LA LECCION</h4>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" action="" method="post">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Contenido en texto HTML: </span>
                                        <textarea name="contenido" id="editor-leccion-<?php echo $leccion['id']; ?>" style="width:100% !important;margin:auto;height:400px;" rows="10"><?php echo $leccion['contenido']; ?></textarea>
                                        <input type="hidden" name="id_leccion" value="<?php echo $leccion['id']; ?>"/>
                                        <br/>
                                        <input type="submit" name="editar-leccion-contenido-HTML" value="ACTUALIZAR DATOS" class="btn btn-success btn-block active"/>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div id="MODAL-leccion_contenido_video-<?php echo $leccion['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">VIDEO - <?php echo $leccion['titulo']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    
                                    <form enctype="multipart/form-data" action="" method="post">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp;  VIMEO / LOCAL: </span>
                                            </div>
                                            <div class="col-md-8 text-center">
                                                <?php 
                                                $cheched_v = "";
                                                $cheched_l = " checked='checked' ";
                                                if($leccion['sw_vimeo']=='1'){
                                                    $cheched_v = " checked='checked' ";
                                                    $cheched_l = "";
                                                }
                                                ?>
                                                <input type="radio" name="sw_vimeo" value="1" <?php echo $cheched_v; ?> class=""/> VIMEO
                                                &nbsp;&nbsp; | &nbsp;&nbsp;
                                                <input type="radio" name="sw_vimeo" value="0" <?php echo $cheched_l; ?> class=""/> LOCAL
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr/>
                                                <input type="hidden" name="id_leccion" value="<?php echo $leccion['id']; ?>"/>
                                                <input type="submit" name="editar-leccion-contenido-VIDEO-sw_vimeo" value="ACTUALIZAR" class="btn btn-success btn-block active"/>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <?php 
                                    if($leccion['sw_vimeo']=='1'){
                                    ?>
                                        <hr/>

                                        <form enctype="multipart/form-data" action="" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp;  ID DE VIDEO: </span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" name="video" class="form-control" value="<?php echo $leccion['video']; ?>" placeholder="ID de video VIMEO"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <hr/>
                                                    <input type="hidden" name="id_leccion" value="<?php echo $leccion['id']; ?>"/>
                                                    <input type="submit" name="editar-leccion-contenido-VIDEO" value="ACTUALIZAR" class="btn btn-success btn-block active"/>
                                                </div>
                                            </div>
                                        </form>
                                    
                                    <?php 
                                    }else{
                                    ?>

                                        <hr/>

                                        <form enctype="multipart/form-data" action="" method="post">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp;  VIDEO LOCAL: </span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" name="localvideofile" class="form-control" value="" placeholder="VIMEO LOCAL"/>
                                                    <br/>
                                                    <?php echo $leccion['localvideofile']; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <hr/>
                                                    <input type="hidden" name="id_leccion" value="<?php echo $leccion['id']; ?>"/>
                                                    <input type="submit" name="editar-leccion-contenido-VIDEO-local" value="SUBIR" class="btn btn-success btn-block active"/>
                                                </div>
                                            </div>
                                        </form>
                                    <?php 
                                    }
                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div id="MODAL-leccion_material-<?php echo $leccion['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">CONTENIDO DE LA LECCION</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-condensed">
                                        <tr>
                                            <th colspan="6">MATERIAL DESCARGABLE PRINCIPAL</th>
                                        </tr>
                                        <?php
                                        $rqmc1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_leccion='" . $leccion['id'] . "' ");
                                        if (mysql_num_rows($rqmc1) == 0) {
                                            echo '<tr><td colspan="5"><div class="alert alert-info">
  <strong>Aviso!</strong> no se registro material descargable para esta lecci&oacute;n.
</div></td></tr>';
                                        }
                                        $cnt = 1;
                                        while ($producto = mysql_fetch_array($rqmc1)) {
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
                                                    <a href="contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-info active"><i class='fa fa-eye'></i> ver/descargar</a>
                                                </td>
                                                <td class="visible-lg" style="width:120px;">
                                                    <a href="cursos-online-administrar/<?php echo $id_onlinecourse; ?>/0/deletematerial/<?php echo $producto['id']; ?>.adm" class="btn btn-xs btn-danger active" onclick="return confirm('Desea eliminar el archivo?');"><i class='fa fa-eye'></i> eliminar</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <a class="btn btn-warning btn-xs active pull-right" data-toggle="modal" data-target="#MODAL-material-onlinecourse" onclick="material_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-plus"></i> AGREGAR MATERIAL DESCARGABLE</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div id="MODAL-leccion_preguntas-<?php echo $leccion['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">CONTENIDO DE LA LECCION</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped table-condensed table-hover">
                                        <tr>
                                            <th colspan="4">PREGUNTAS DE EVALUACI&Oacute;N</th>
                                        </tr>
                                        <?php
                                        $rqmc1 = query("SELECT * FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='$id_onlinecourse' AND id_leccion='" . $leccion['id'] . "' AND estado='1' ");
                                        if (mysql_num_rows($rqmc1) == 0) {
                                            echo '<div class="alert alert-info">
  <strong>Aviso!</strong> Este curso no tiene preguntas de evaluaci&oacute;n.
</div>';
                                        }
                                        $cnt = 1;
                                        while ($producto = mysql_fetch_array($rqmc1)) {
                                            ?>
                                            <tr>
                                                <td class="visible-lg"><?php echo $cnt++; ?></td>
                                                <td class="visible-lg">
                                                    <?php
                                                    echo $producto['pregunta'];
                                                    ?>         
                                                </td>
                                                <td class="visible-lg">
                                                    <?php
                                                    //echo $producto['formato_archivo'];
                                                    ?> 
                                                </td>
                                                <td class="visible-lg">
                                                    <?php
                                                    //echo $producto['nombre_fisico'];
                                                    ?> 
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <a class="btn btn-warning btn-xs active pull-right" data-toggle="modal" data-target="#MODAL-preguntas-onlinecourse" onclick="pregunta_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-plus"></i> AGREGAR PREGUNTA DE EVALUACI&Oacute;N</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal-1 -->
<div id="MODAL-asignar-certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNACI&Oacute;N DE CERTIFICADO AL CURSO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscritos.
                </p>
                <hr/>
                <form action='' method='post' enctype="multipart/form-data">
                    <div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
<!--                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>-->
                                <textarea  class="form-control" name="contenido_uno_certificado" rows="2"><?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> d&iacute;as del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018.</textarea>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 :</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <select type="text" class="form-control" name="firma1">
                                    <?php
                                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                        $text_img = "Sin imagen";
                                        $url_img = "contenido/imagenes/firmas/" . $rqfc2['imagen'];
                                        if (file_exists($url_img)) {
                                            $text_img = "Imagen disponible";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 :</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <select type="text" class="form-control" name="firma2">
                                    <?php
                                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                        $text_img = "Sin imagen";
                                        $url_img = "contenido/imagenes/firmas/" . $rqfc2['imagen'];
                                        if (file_exists($url_img)) {
                                            $text_img = "Imagen disponible";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                            </div>
                            <div class="col-md-9 text-center">
                                <br/>
                                <input type="radio" name="sw_solo_nombre" value="0" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1" checked="" />
                                Solo Nombre-Fecha
                                <br/>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Formato:</b></span>
                            </div>
                            <div class="col-md-9 text-center">
                                <select name="formato" class="form-control">
                                    <option value="3">NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                    <option value="5">Formato 5 | QR en la parte superior</option>
                                    <option value="2">CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                </select> 
                            </div>
                        </div>
                        <hr/>
                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado' class="btn btn-success" value="HABILITAR CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>
                    <br/>
                    <p>
                        En la opci&oacute;n impresion solo Nombre-Fecha, solamente se generara un certificado con unicamente 
                        el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n  el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
                    </p>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-1 -->

<!-- Modal-2 -->
<div id="MODAL-asignar-certificado-2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNACI&Oacute;N DE 2do CERTIFICADO PARA EL CURSO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscritos.
                </p>
                <hr/>
                <form action='' method='post' enctype="multipart/form-data">
                    <div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
<!--                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>-->
                                <textarea  class="form-control" name="contenido_uno_certificado" rows="2"><?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018</textarea>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 :</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <select type="text" class="form-control" name="firma1">
                                    <?php
                                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                        $text_img = "Sin imagen";
                                        $url_img = "contenido/imagenes/firmas/" . $rqfc2['imagen'];
                                        if (file_exists($url_img)) {
                                            $text_img = "Imagen disponible";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 :</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <select type="text" class="form-control" name="firma2">
                                    <?php
                                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                        $text_img = "Sin imagen";
                                        $url_img = "contenido/imagenes/firmas/" . $rqfc2['imagen'];
                                        if (file_exists($url_img)) {
                                            $text_img = "Imagen disponible";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                            </div>
                            <div class="col-md-9 text-center">
                                <br/>
                                <input type="radio" name="sw_solo_nombre" value="0" checked="" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1" />
                                Solo Nombre-Fecha
                                <br/>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Formato:</b></span>
                            </div>
                            <div class="col-md-9 text-center">
                                <select name="formato" class="form-control">
                                    <option value="3">NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                    <option value="5">Formato 5 | QR en la parte superior</option>
                                    <option value="2">CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                </select>
                            </div>
                        </div>
                        <hr/>
                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado-2' class="btn btn-success" value="HABILITAR 2do CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>
                    <br/>
                    <p>
                        En la opci&oacute;n impresion solo Nombre-Fecha, solamente se generara un certificado con unicamente 
                        el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n  el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
                    </p>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-2 -->

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

<!-- Modal - preguntas-onlinecourse -->
<div id="MODAL-preguntas-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PREGUNTAS DE EVALUACI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <p>
                    Ingrese a continuaci&oacute;n las preguntas de evaluaci&oacute;n del curso.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Pregunta:
                                <br/>
                                <input type="text" name="pregunta" class="form-control" placeholder="Ingresa la pregunta..." required=""/>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px;">
                                <b>RESPUESTAS</b>
                                <div>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-1" class="form-control" placeholder="Ingresa la respuesta..." required=""/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-1" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-2" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-2" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-3" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-3" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-4" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-4" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-5" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-5" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-6" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-6" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-7" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-7" value="1" class="form-control"/>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type='hidden' name='id_leccion' id='pregunta_id_leccion' value=''/>
                                    <input type="submit" name="agregar-pregunta-onlinecourse" value="AGREGAR PREGUNTA" class="btn btn-success btn-lg btn-animate-demo active"/>
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
<!-- End Modal - preguntas-onlinecourse -->

<!-- Modal - crear leccion -->
<div id="MODAL-crear-leccion-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREACI&Oacute;N DE LECCI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <p>
                    Ingrese a continuaci&oacute;n el t&iacute;tulo de la lecci&oacute;n.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; T&iacute;tulo:
                                <br/>
                                <textarea name="titulo" class="form-control" rows="2" required="" placeholder="Descripci&oacute;n dada a los participantes del curso."></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; VIMEO / LOCAL:
                                <br/>
                                <input type="radio" name="sw_vimeo" value="1" class=""/> VIMEO
                                &nbsp;&nbsp; | &nbsp;&nbsp;
                                <input type="radio" name="sw_vimeo" value="0" class="" checked=""/> LOCAL
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="crear-leccion-onlinecourse" value="CREAR LECCI&Oacute;N" class="btn btn-success btn-lg btn-animate-demo active"/>
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
<!-- End Modal - crear leccion -->

<!-- Modal -->
<div id="MODAL-editar_presentacion_curso" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PRESENTACION - TEMA / LECCION DEL CURSO</h4>
            </div>
            <div class="modal-body">
                <!-- DIV CONTENT AJAX :: PRESENTACION ADM  P1 -->
                <div id="ajaxloading-presentacion_leccion_p1"></div>
                <div id="ajaxbox-presentacion_leccion_p1">
                    ....
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function material_leccion(dat) {
        $("#id_leccion").val(dat);
    }
    function pregunta_leccion(dat) {
        $("#pregunta_id_leccion").val(dat);
    }
</script>
<script>
    function presentacion_leccion_p1(id_leccion) {
        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_p1.php',
            data: {id_leccion: id_leccion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    }
</script>
<script>
    function presentacion_leccion_agregar_p1(id_leccion) {
        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_agregar_p1.php',
            data: {id_leccion: id_leccion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    }
</script>
<script>
    function presentacion_audio_agregar_p1(id_leccion) {
        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_audio_agregar_p1.php',
            data: {id_leccion: id_leccion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    }
</script>
<script>
    function presentacion_leccion_eliminar_p1(id_presentacion) {
        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_eliminar_p1.php',
            data: {id_presentacion: id_presentacion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    }
</script>
<script>
    function presentacion_leccion_editar_p1(id_presentacion) {
        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_editar_p1.php',
            data: {id_presentacion: id_presentacion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    }
</script>

<?php

function verificador_fecha($dat) {
    if ($dat == '') {
        return "0000-00-00";
    } else {
        return $dat;
    }
}
