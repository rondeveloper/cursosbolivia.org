<?php
/* id noticia */
$id_noticia = (int) $get[2];

$mensaje = '';

/* edicion de curso */
if (isset_post('editar-curso')) {

    $id = post('id');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $contenido = post_html('formulario');
    $incrustacion = post_html('incrustacion');

    $id_ciudad = post('id_ciudad');
    $whats_numero = post('whats_numero');
    $whats_mensaje = post('whats_mensaje');

    $id_categoria = post('id_categoria');

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');

    $archivo1 = post('prearchivo1');
    $archivo2 = post('prearchivo2');
    $archivo3 = post('prearchivo3');
    $archivo4 = post('prearchivo4');
    $archivo5 = post('prearchivo5');

    if (isset_archivo('imagen')) {
        $imagen = time() . str_replace("'", "", archivoName('imagen'));
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/noticias/$imagen");
    }
    if (isset_archivo('imagen2')) {
        $imagen2 = time() . str_replace("'", "", archivoName('imagen2'));
        move_uploaded_file(archivo('imagen2'), $___path_raiz."contenido/imagenes/noticias/$imagen2");
    }
    if (isset_archivo('imagen3')) {
        $imagen3 = time() . str_replace("'", "", archivoName('imagen3'));
        move_uploaded_file(archivo('imagen3'), $___path_raiz."contenido/imagenes/noticias/$imagen3");
    }
    if (isset_archivo('imagen4')) {
        $imagen4 = time() . str_replace("'", "", archivoName('imagen4'));
        move_uploaded_file(archivo('imagen4'), $___path_raiz."contenido/imagenes/noticias/$imagen4");
    }


    if (isset_archivo('archivo1')) {
        $archivo1 = 'C' . $id_noticia . '-' . str_replace("'", "", archivoName('archivo1'));
        move_uploaded_file(archivo('archivo1'), $___path_raiz."contenido/archivos/noticias/$archivo1");
    }
    if (isset_archivo('archivo2')) {
        $archivo2 = 'C' . $id_noticia . '-' . str_replace("'", "", archivoName('archivo2'));
        move_uploaded_file(archivo('archivo2'), $___path_raiz."contenido/archivos/noticias/$archivo2");
    }
    if (isset_archivo('archivo3')) {
        $archivo3 = 'C' . $id_noticia . '-' . str_replace("'", "", archivoName('archivo3'));
        move_uploaded_file(archivo('archivo3'), $___path_raiz."contenido/archivos/noticias/$archivo3");
    }
    if (isset_archivo('archivo4')) {
        $archivo4 = 'C' . $id_noticia . '-' . str_replace("'", "", archivoName('archivo4'));
        move_uploaded_file(archivo('archivo4'), $___path_raiz."contenido/archivos/noticias/$archivo4");
    }
    if (isset_archivo('archivo5')) {
        $archivo5 = 'C' . $id_noticia . '-' . str_replace("'", "", archivoName('archivo5'));
        move_uploaded_file(archivo('archivo5'), $___path_raiz."contenido/archivos/noticias/$archivo5");
    }


    query("UPDATE publicaciones SET 
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              contenido='$contenido',
              incrustacion='$incrustacion',
              id_ciudad='$id_ciudad',
              id_categoria='$id_categoria',
              whats_numero='$whats_numero',
              whats_mensaje='$whats_mensaje',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              archivo1='$archivo1',
              archivo2='$archivo2',
              archivo3='$archivo3',
              archivo4='$archivo4',
              archivo5='$archivo5' 
               WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de datos de noticia', 'noticia-edicion', 'noticia', $id_noticia);

    $mensaje .= '<div class="alert alert-success">
      <strong>EXITO!</strong> datos de noticia actualizados correctamente.
    </div>';
}

/* registros */
$resultado_paginas = query("SELECT * FROM publicaciones WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
$noticia = fetch($resultado_paginas);

/* departamento */
$noticia_id_ciudad = $noticia['id_ciudad'];
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$noticia_id_ciudad' LIMIT 1 ");
$rqdd2 = fetch($rqdd1);
$noticia_id_departamento = $rqdd2['id_departamento'];

/* url_corta */
$url_corta = $dominio . $noticia['titulo_identificador'] . '.html';

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
            <li><a href="noticias-listar.adm">Noticias</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-header" style="padding: 0px;margin: 0px;">
                    <i class='btn btn-success active hidden-sm'><?php echo my_fecha_at1($noticia['fecha']); ?></i> | 
                    <?php echo $noticia['titulo']; ?>
                </h3>
            </div>
        </div>
        <blockquote class="page-information hidden">
            <p>
                Edicion de noticia.
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
                if (acceso_cod('adm-cursos-estado') || isset_organizador()) {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">ESTADO DEL CURSO - <?php echo $noticia['titulo']; ?></div>
                        <div class="panel-body">

                            <div class="row" style="background:#EEE;margin-bottom: 10px;padding:10px 0px;" id="box-cont-estado">
                                <div class="col-md-3">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                </div>

                                <?php
                                if ($noticia['estado'] == '1') {
                                    ?>
                                    <div class="col-md-5">
                                        <span class="input-group-addon"><b style='color:green;'>ACTIVADO</b></span>
                                    </div>
                                    <?php
                                    if (acceso_cod('adm-cursos-estado')) {
                                        ?>
                                        <div class="col-md-4 text-right">
                                            <input type="button" value="DESACTIVAR" class="btn btn-default btn-xs" onclick="cambiar_estado_noticia('<?php echo $noticia['id']; ?>', 'desactivado');"/>
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
                                        <div class="col-md-4 text-right">
                                            <input type="button" value="ACTIVAR" class="btn btn-success btn-xs" onclick="cambiar_estado_noticia('<?php echo $noticia['id']; ?>', 'activado');"/>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <a href="<?php echo $noticia['titulo_identificador']; ?>.html" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR LA NOTICIA</a>
                            &nbsp;|&nbsp;
                            <a <?php echo loadpage('noticias-listar'); ?>> <i class="fa fa-list"></i> LISTADO DE NOTICIAS</a>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <?php
                $sw_edicion_tituloFechaCostos = true;
                $txt_disabled = '';
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        DATOS GENERALES - <?php echo $noticia['titulo']; ?>
                        <div class="pull-right" style="width:170px;">
                            <input type='text' class="form-control" value="<?php echo $url_corta; ?>" style="margin-top: -8px;text-align: center;"/>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" action="" method="post" id="FORM-edicion_tituloFechaCostos">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">DATOS 1</a></li>
                                <li><a data-toggle="tab" href="#menu1">DATOS 2</a></li>
                                <li><a data-toggle="tab" href="#menu2">DATOS 3</a></li>
                                <li><a data-toggle="tab" href="#menu3">DATOS 4</a></li>
                            </ul>
                            <div class="tab-content">
                                <br/>
                                <div id="home" class="tab-pane active">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Nombre de la noticia: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="titulo" value="<?php echo $noticia['titulo']; ?>" class="form-control"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control form-cascade-control" name="id_categoria">
                                                        <option value="0">Sin categoria</option>
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($noticia['id_categoria'] == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?>><?php echo $rqd2['titulo']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Departamento: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                                                        <?php
                                                        $noticia_departamento = 'no-data';
                                                        $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($noticia_id_departamento == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                                $noticia_departamento = $rqd2['nombre'];
                                                            }
                                                            echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Ciudad: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group input-group">
                                                        <select class="form-control" name="id_ciudad" id="select_ciudad" onchange="actualiza_lugares();">
                                                            <?php
                                                            $noticia_ciudad_nombre = 'no-data';
                                                            $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$noticia_id_departamento' ORDER BY nombre ASC ");
                                                            while ($rqd2 = fetch($rqd1)) {
                                                                $selected = '';
                                                                if ($noticia['id_ciudad'] == $rqd2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                    $noticia_ciudad_nombre = $rqd2['nombre'];
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
                                                    <input type="hidden" id="current_id_ciudad" value="<?php echo $noticia_id_ciudad; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Whatsapp: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="whats_numero" value="<?php echo $noticia['whats_numero']; ?>" class="form-control" id="date" placeholder="Numero..."/>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="whats_mensaje" value="<?php echo $noticia['whats_mensaje']; ?>" class="form-control" id="date" placeholder="Mensaje..."/>
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
                                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="imagen" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['imagen'] == '') {
                                                            echo "<b>Sin imagen</b>";
                                                        } else {
                                                            $url_img = $dominio_www."contenido/imagenes/noticias/" . $noticia['imagen'];
                                                            ?>
                                                            <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                            <?php
                                                        }
                                                        ?>
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
                                                        if ($noticia['imagen2'] == '') {
                                                            echo "<b>Sin imagen</b>";
                                                        } else {
                                                            $url_img = $dominio_www."contenido/imagenes/noticias/" . $noticia['imagen2'];
                                                            ?>
                                                            <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                            <?php
                                                        }
                                                        ?>
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
                                                        if ($noticia['imagen3'] == '') {
                                                            echo "<b>Sin imagen</b>";
                                                        } else {
                                                            $url_img = $dominio_www."contenido/imagenes/noticias/" . $noticia['imagen3'];
                                                            ?>
                                                            <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                            <?php
                                                        }
                                                        ?>
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
                                                        if ($noticia['imagen4'] == '') {
                                                            echo "<b>Sin imagen</b>";
                                                        } else {
                                                            $url_img = $dominio_www."contenido/imagenes/noticias/" . $noticia['imagen4'];
                                                            ?>
                                                            <img src="<?php echo $url_img; ?>" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>1</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo1" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['archivo1'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists($___path_raiz."contenido/archivos/noticias/" . $noticia['archivo1'])) {
                                                                $url_arch = $dominio_www."contenido/archivos/noticias/" . $noticia['archivo1'];
                                                            } else {
                                                                $url_arch = "https://www.infosiscon.com/" . "contenido/archivos/noticias/" . $noticia['archivo1'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $noticia['archivo1'] . "</a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>2</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo2" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['archivo2'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists($___path_raiz."contenido/archivos/noticias/" . $noticia['archivo2'])) {
                                                                $url_arch = $dominio_www."contenido/archivos/noticias/" . $noticia['archivo2'];
                                                            } else {
                                                                $url_arch = "https://www.infosiscon.com/" . "contenido/archivos/noticias/" . $noticia['archivo2'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $noticia['archivo2'] . "</a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>3</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo3" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['archivo3'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists($___path_raiz."contenido/archivos/noticias/" . $noticia['archivo3'])) {
                                                                $url_arch = $dominio_www."contenido/archivos/noticias/" . $noticia['archivo3'];
                                                            } else {
                                                                $url_arch = "https://www.infosiscon.com/" . "contenido/archivos/noticias/" . $noticia['archivo3'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $noticia['archivo3'] . "</a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>4</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo4" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['archivo4'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists($___path_raiz."contenido/archivos/noticias/" . $noticia['archivo4'])) {
                                                                $url_arch = $dominio_www."contenido/archivos/noticias/" . $noticia['archivo4'];
                                                            } else {
                                                                $url_arch = "https://www.infosiscon.com/" . "contenido/archivos/noticias/" . $noticia['archivo4'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $noticia['archivo4'] . "</a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>5</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo5" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($noticia['archivo5'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists($___path_raiz."contenido/archivos/noticias/" . $noticia['archivo5'])) {
                                                                $url_arch = $dominio_www."contenido/archivos/noticias/" . $noticia['archivo5'];
                                                            } else {
                                                                $url_arch = "https://www.infosiscon.com/" . "contenido/archivos/noticias/" . $noticia['archivo5'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $noticia['archivo5'] . "</a>";
                                                        }
                                                        ?>
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
                                                                                        <td>[TITULO-NOTICIA]</td>
                                                                                        <td><?php echo $noticia['titulo']; ?></td>
                                                                                    </tr>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                                                                <h4 class="panel-title">
                                                                                    Enlaces
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse2" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                <span style="font-size:11pt;color:green;">[WHATSAPP]</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                                                    <div class="panel panel-default">
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
                                                                    </div>
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
                                                <textarea name="formulario" id="editor1" style="width:100% !important;margin:auto;height:700px;" rows="25"><?php echo $noticia['contenido']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Codigo de incrustaci&oacute;n</b>
                                                <br/>
                                                <textarea name="incrustacion" class="form-control" style="width:100%;margin:auto;" rows="2"><?php echo $noticia['incrustacion']; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>


                        </div>
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="submit" value="ACTUALIZAR DATOS DEL CURSO" name="editar-curso" class="btn btn-success btn-block"/>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>"/>
                        <input type="hidden" name="preimagen" value="<?php echo $noticia['imagen']; ?>"/>
                        <input type="hidden" name="preimagen2" value="<?php echo $noticia['imagen2']; ?>"/>
                        <input type="hidden" name="preimagen3" value="<?php echo $noticia['imagen3']; ?>"/>
                        <input type="hidden" name="preimagen4" value="<?php echo $noticia['imagen4']; ?>"/>
                        <input type="hidden" name="preimagengif" value="<?php echo $noticia['imagen_gif']; ?>"/>
                        <input type="hidden" name="prearchivo1" value="<?php echo $noticia['archivo1']; ?>"/>
                        <input type="hidden" name="prearchivo2" value="<?php echo $noticia['archivo2']; ?>"/>
                        <input type="hidden" name="prearchivo3" value="<?php echo $noticia['archivo3']; ?>"/>
                        <input type="hidden" name="prearchivo4" value="<?php echo $noticia['archivo4']; ?>"/>
                        <input type="hidden" name="prearchivo5" value="<?php echo $noticia['archivo5']; ?>"/>
                    </form>
                </div>


                <br/>
                <hr/>
                <br/>




            </div>
        </div>
    </div>
</div>


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


<!--cambiar_estado_noticia-->
<script>
    function cambiar_estado_noticia(id_noticia, estado) {
        $("#box-cont-estado").html("<div class='col-md-3'>Actualizando...</div>");
        $.ajax({
            url: 'pages/ajax/ajax.noticias-editar.cambiar_estado_noticia.php',
            data: {id_noticia: id_noticia, estado: estado},
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
