<?php
/* id curso */
$id_blog = (int) $get[2];

$mensaje = '';

/* edicion de blog */
if (isset_post('editar-blog')) {

    $id = post('id');
    $contenido = post_html('formulario');
    $incrustacion = post_html('incrustacion');
    $id_ciudad = post('id_ciudad');
    $id_categoria = post('id_categoria');

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');
    $imagen_gif = post('preimagengif');
    
    $titulo = post('titulo');
    $fecha = post('fecha');
    $id_departamento = post('id_departamento');

    if (isset_archivo('imagen')) {
        $imagen = time() . str_replace("'", "", archivoName('imagen'));
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/blog/$imagen");
    }
    if (isset_archivo('imagen2')) {
        $imagen2 = time() . str_replace("'", "", archivoName('imagen2'));
        move_uploaded_file(archivo('imagen2'), $___path_raiz."contenido/imagenes/blog/$imagen2");
    }
    if (isset_archivo('imagen3')) {
        $imagen3 = time() . str_replace("'", "", archivoName('imagen3'));
        move_uploaded_file(archivo('imagen3'), $___path_raiz."contenido/imagenes/blog/$imagen3");
    }
    if (isset_archivo('imagen4')) {
        $imagen4 = time() . str_replace("'", "", archivoName('imagen4'));
        move_uploaded_file(archivo('imagen4'), $___path_raiz."contenido/imagenes/blog/$imagen4");
    }
    if (isset_archivo('imagen_gif')) {
        $imagen_gif = time() . str_replace("'", "", archivoName('imagen_gif'));
        move_uploaded_file(archivo('imagen_gif'), $___path_raiz."contenido/imagenes/blog/$imagen_gif");
    }

    query("UPDATE blog SET 
              titulo='$titulo',
              fecha='$fecha',
              id_departamento='$id_departamento',
              contenido='$contenido',
              incrustacion='$incrustacion',
              id_ciudad='$id_ciudad',
              id_categoria='$id_categoria',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              imagen_gif='$imagen_gif' 
               WHERE id='$id_blog' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de datos de blog [DATOS GENERALES]', 'blog-edicion', 'blog', $id_blog);

    $mensaje .= '<div class="alert alert-success">
      <strong>EXITO!</strong> datos de blog actualizados correctamente.
    </div>
    ';
}


/* registros */
$resultado_paginas = query("SELECT * FROM blog WHERE id='$id_blog' ORDER BY id DESC limit 1 ");
$blog = fetch($resultado_paginas);

/* departamento */
$blog_id_ciudad = $blog['id_ciudad'];
if ($blog_id_ciudad == '24') {
    $blog_id_departamento = '0';
} else {
    $rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$blog_id_ciudad' LIMIT 1 ");
    $rqdd2 = fetch($rqdd1);
    $blog_id_departamento = $rqdd2['id_departamento'];
}

/* url_corta */
$url_corta = $dominio . $blog['titulo_identificador']. '/';

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
            <li><a href="blog-listar.adm">Blog</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-header" style="padding: 0px;margin: 0px;">
                    <i class='btn btn-warning active hidden-sm'><?php echo my_fecha_at1($blog['fecha']); ?></i> | 
                    <?php echo $blog['titulo']; ?>
                </h3>
            </div>
        </div>
        <blockquote class="page-information hidden">
            <p>
                Edicion de blog.
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
                        <div class="panel-heading">ESTADO DEL BLOG - <?php echo $blog['titulo']; ?></div>
                        <div class="panel-body">

                            <div class="row" style="background:#EEE;margin-bottom: 10px;padding:10px 0px;" id="box-cont-estado">
                                <div class="col-md-3">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                </div>

                                <?php
                                if ($blog['estado'] == '1') {
                                    ?>
                                    <div class="col-md-5">
                                        <span class="input-group-addon"><b style='color:green;'>ACTIVADO</b></span>
                                    </div>
                                    <?php
                                    if (acceso_cod('adm-cursos-estado')) {
                                        ?>
                                        <div class="col-md-4">
                                            <div>
                                                <input type="button" value="DESACTIVAR" class="btn btn-default btn-sm btn-block" onclick="cambiar_estado_blog('<?php echo $blog['id']; ?>', 'desactivado');"/>
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
                                                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_blog('<?php echo $blog['id']; ?>', 'activado');"/>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            Enlaces: &nbsp;&nbsp; 
                            <a href="<?php echo $blog['titulo_identificador']; ?>/" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR EL BLOG</a>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <?php
                $sw_edicion_tituloFechaCostos = false;
                $txt_disabled = ' disabled="" ';
                if ((strtotime($blog['fecha']) >= strtotime(date("Y-m-d"))) || true) {
                    $sw_edicion_tituloFechaCostos = true;
                    $txt_disabled = '';
                }
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        DATOS GENERALES - <?php echo $blog['titulo']; ?>
                        <div class="pull-right hidden-xs" style="width:570px;">
                            <input type='text' class="form-control" value="<?php echo $url_corta; ?>" style="margin-top: -8px;text-align: center;"/>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" action="" method="post">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#titulofechacostos">DATOS PRIMARIOS</a></li>
                                <li><a data-toggle="tab" href="#menu2">DATOS 3</a></li>
                                <li><a data-toggle="tab" href="#menu3">DATOS 4</a></li>
                            </ul>
                            <div class="tab-content">
                                <br/>
                                <div id="titulofechacostos" class="tab-pane fade in active">
                                    <div id="AJAXBOX-edicion_tituloFechaCostos"></div>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Nombre del Blog: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="titulo" value="<?php echo $blog['titulo']; ?>" class="form-control" <?php echo $txt_disabled ?>/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del blog: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha" class="form-control" value="<?php echo $blog['fecha']; ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-cascade-control" name="id_categoria">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($blog['id_categoria'] == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['titulo']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="id_categoria_anterior" value="<?php echo $blog['id_categoria']; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Departamento: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                                                        <?php
                                                        $blog_departamento = 'no-data';
                                                        $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($blog_id_departamento == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                                $blog_departamento = $rqd2['nombre'];
                                                            }
                                                            echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                        }
                                                        $selected = '';
                                                        if ($blog_id_departamento == '0') {
                                                            $selected = ' selected="selected" ';
                                                            $blog_departamento = $rqd2['nombre'];
                                                        }
                                                        echo '<option value="0" ' . $selected . ' >[ NIVEL NACIONAL ]</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Ciudad: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group input-group">
                                                        <select class="form-control" name="id_ciudad" id="select_ciudad">
                                                            <?php
                                                            $blog_ciudad_nombre = 'no-data';
                                                            $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$blog_id_departamento' ORDER BY nombre ASC ");
                                                            while ($rqd2 = fetch($rqd1)) {
                                                                $selected = '';
                                                                if ($blog['id_ciudad'] == $rqd2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                    $blog_ciudad_nombre = $rqd2['nombre'];
                                                                }
                                                                echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="input-group-btn">
                                                            <b class="btn btn-default" onclick="actualiza_ciudades();">
                                                                <i class="fa fa-refresh"></i>
                                                            </b>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" id="current_id_ciudad" value="<?php echo $blog_id_ciudad; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display:none;">
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Whatsapp: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <b class="btn btn-default btn-block" data-toggle="modal" data-target="#MODAL-whatsapp_numeros" onclick="whatsapp_numeros();">(WhatsApp) N&Uacute;MEROS ASIGNADOS</b>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu2" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>GIF</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen_gif" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $url_img = $dominio.'blog/.size=2.img';
                                                        if ($blog['imagen_gif'] !== '') {
                                                            $url_img = $dominio_www."contenido/imagenes/blog/" . $blog['imagen_gif'];
                                                        }
                                                        ?>
                                                        <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/blog/" . $blog['imagen'];
                                                        $url_img = $dominio_www."blog/" . $blog['imagen'] . ".size=2.img";
                                                        ?>
                                                        <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 2: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen2" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/blog/" . $blog['imagen2'];
                                                        $url_img = $dominio_www."blog/" . $blog['imagen2'] . ".size=2.img";
                                                        ?>
                                                        <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 3: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen3" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/blog/" . $blog['imagen3'];
                                                        $url_img = $dominio_www."blog/" . $blog['imagen3'] . ".size=2.img";
                                                        ?>
                                                        <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 4: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen4" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/blog/" . $blog['imagen4'];
                                                        $url_img = $dominio_www."blog/" . $blog['imagen4'] . ".size=2.img";
                                                        ?>
                                                        <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu3" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="panel-group">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading" data-toggle="collapse" href="#collapse_TC" style="cursor:pointer;">
                                                            <h4 class="panel-title text-center">
                                                                <i class="fa fa-bars fa-fw"></i> Contenido din&aacute;mico [TAGs] &nbsp;&nbsp; <i class="fa fa-angle-down"></i>
                                                            </h4>
                                                        </div>
                                                        <div id="collapse_TC" class="panel-collapse collapse">
                                                            <div class="panel-body">

                                                                <div class="panel-group" id="accordion">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                                                                <h4 class="panel-title">
                                                                                    Datos
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse1" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                <table class="table table-striped table-bordered table-hover">
                                                                                    <tr>
                                                                                        <td>[NOMBRE-BLOG]</td>
                                                                                        <td><?php echo $blog['titulo']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[CIUDAD-BLOG]</td>
                                                                                        <td><?php echo $blog_departamento; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[FECHA-A1-BLOG]</td>
                                                                                        <td><?php echo fecha_curso_D_d_m($blog['fecha']); ?></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
<!--                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                                                                <h4 class="panel-title">
                                                                                    Enlaces
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse2" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                &nbsp;&nbsp; 
                                                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:green;">[WHATSAPP]</span>  
                                                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:#222;">[NUMERO-CELULAR]</span>  &nbsp;,&nbsp;  
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                                                                <h4 class="panel-title">
                                                                                    Imagenes
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse3" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                &nbsp;&nbsp; <span style="font-size:11pt;">[imagen-1]  &nbsp;,&nbsp;  [imagen-2]  &nbsp;,&nbsp;  [imagen-3]  &nbsp;,&nbsp;  [imagen-4]</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
<!--                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                                                                <h4 class="panel-title">
                                                                                    Archivos
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse4" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                &nbsp;,&nbsp;  <span style="font-size:11pt;">[ARCHIVO-1]  &nbsp;,&nbsp;  [ARCHIVO-2]  &nbsp;,&nbsp;  [ARCHIVO-3]  &nbsp;,&nbsp;  [ARCHIVO-4]  &nbsp;,&nbsp;  [ARCHIVO-5]</span> 
                                                                            </div>
                                                                        </div>
                                                                    </div>-->
                                                                </div>

                                                            </div>
                                                            <div class="panel-footer"><i>Contenido din&aacute;mico</i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <textarea name="formulario" id="editor1" style="width:100% !important;margin:auto;height:700px;" rows="25"><?php echo $blog['contenido']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Codigo de incrustaci&oacute;n</b>
                                                <br/>
                                                <textarea name="incrustacion" class="form-control" style="width:100%;margin:auto;" rows="2"><?php echo $blog['incrustacion']; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="id" value="<?php echo $blog['id']; ?>"/>
                        <input type="hidden" name="preimagen" value="<?php echo $blog['imagen']; ?>"/>
                        <input type="hidden" name="preimagen2" value="<?php echo $blog['imagen2']; ?>"/>
                        <input type="hidden" name="preimagen3" value="<?php echo $blog['imagen3']; ?>"/>
                        <input type="hidden" name="preimagen4" value="<?php echo $blog['imagen4']; ?>"/>
                        <input type="hidden" name="preimagengif" value="<?php echo $blog['imagen_gif']; ?>"/>
                        
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="submit" value="ACTUALIZAR DATOS DEL BLOG" name="editar-blog" class="btn btn-success btn-block"/>
                            </div>
                        </div>
                    </form>
                </div>

                <br/>
                <hr/>
                <br/>

            </div>
        </div>
    </div>
</div>











<!-- Modal whatsapp_numeros -->
<div id="MODAL-whatsapp_numeros" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">NUMEROS DE WHATSAPP</h4>
      </div>
      <div class="modal-body">
          <div id="AJAXCONTENT-whatsapp_numeros"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- AJAX whatsapp_numeros -->
<script>
    function whatsapp_numeros() {
        $("#AJAXCONTENT-whatsapp_numeros").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.whatsapp_numeros.php',
            data: {id_curso: '<?php echo $id_blog?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-whatsapp_numeros").html(data);
            }
        });
    }
</script>





<!--cursos_gratuitos-->
<script>
    function cursos_gratuitos_agregar(id_curso) {
        $("#AJAXCONTENT-cursos_gratuitos_agregar").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cursos_gratuitos_agregar.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_gratuitos_agregar").html(data);
            }
        });
    }
</script>


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
            success: function (data) {
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
            success: function (data) {
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
            data: {tag: dat, id_curso: '<?php echo $id_blog; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
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
            data: {id_tag: dat, id_curso: '<?php echo $id_blog; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
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
            data: {id_tag: dat, id_curso: '<?php echo $id_blog; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#tr-tag-" + dat).css('display', 'none');
            }
        });
    }
</script>

<script>
    function cambiar_estado_blog(id_curso, estado) {
        $("#box-cont-estado").html("<div class='col-md-3'>Actualizando...</div>");
        $.ajax({
            url: 'pages/ajax/ajax.blog-editar.cambiar_estado_blog.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#box-cont-estado").html(data);
            }
        });
    }
</script>

<!-- AJAX modificar_asignacion_onlinecourse -->
<script>
    function modificar_asignacion_onlinecourse(id_asignacion_onlinecourse) {
        $("#AJAXBOX-modificar_asignacion_onlinecourse").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.modificar_asignacion_onlinecourse.php',
            data: {id_asignacion_onlinecourse: id_asignacion_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-modificar_asignacion_onlinecourse").html(data);
            }
        });
    }
</script>

<!-- AJAX modificar_asignacion_onlinecourse p2 -->
<script>
    function modificar_asignacion_onlinecourse_p2() {
        var formdata = $("#FORM-editar_asignacion_onlinecourse").serialize();
        $("#AJAXBOX-modificar_asignacion_onlinecourse").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.modificar_asignacion_onlinecourse_p2.php',
            data: formdata,
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-modificar_asignacion_onlinecourse").html(data);
            }
        });
    }
</script>


<!-- AJAX genera_htmlcurso_individual -->
<script>
    function genera_htmlcurso_individual(id_curso) {
        $.ajax({
            url: 'pages/cron/cron.genera_htmls.php?page=curso-individual&id_curso=' + id_curso,
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                console.log(data);
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

function verificador_hora($dat) {
    if ($dat == '') {
        return "00:00";
    } else {
        return $dat;
    }
}


function refactor_titcurso($dat) {
    $rqc1 = query("SELECT nombre FROM ciudades ");
    $busc = array();
    while ($rqc2 = fetch($rqc1)) {
        array_push($busc, "en " . $rqc2['nombre']);
    }
    $remm = '';
    return trim(str_replace($busc, $remm, $dat));
}
