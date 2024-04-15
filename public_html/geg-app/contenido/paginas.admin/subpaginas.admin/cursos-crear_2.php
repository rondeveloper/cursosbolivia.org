<?php
$mensaje = '';

/* creacion de curso */
if (isset_post('formulario')) {

    $contenido = post_html('formulario');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $id_organizador = post('id_organizador');
    $flag_publicacion = post('flag_publicacion');
    $sw_siguiente_semana = post('sw_siguiente_semana');
    $incrustacion = post_html('incrustacion');
    $google_maps = post_html('google_maps');

    $id_departamento = post('id_departamento');
    $fecha = verificador_fecha(post('fecha'));
    $costo = post('costo');
    $fecha2 = verificador_fecha(post('fecha2'));
    $costo2 = post('costo2');
    $fecha3 = verificador_fecha(post('fecha3'));
    $costo3 = post('costo3');
    $fecha_e = verificador_fecha(post('fecha_e'));
    $costo_e = post('costo_e');
    $fecha_e2 = verificador_fecha(post('fecha_e2'));
    $costo_e2 = post('costo_e2');
    $lugar = post('lugar');
    $salon = post('salon');
    $horarios = post('horarios');
    $expositor = post('expositor');
    $colaborador = post('colaborador');
    $gastos = post('gastos');
    $observaciones = post('observaciones');
    $fecha_registro = date("Y-m-d H:i");

    $id_categoria = post('id_categoria');
    $estado = post('estado');

    $sw_fecha2 = '0';
    if (isset_post('sw_fecha2')) {
        $sw_fecha2 = '1';
    }
    $sw_fecha3 = '0';
    if (isset_post('sw_fecha3')) {
        $sw_fecha3 = '1';
    }
    $sw_fecha_e = '0';
    if (isset_post('sw_fecha_e')) {
        $sw_fecha_e = '1';
    }
    $sw_fecha_e2 = '0';
    if (isset_post('sw_fecha_e2')) {
        $sw_fecha_e2 = '1';
    }

    $imagen = '';
    $imagen2 = '';
    $imagen3 = '';
    $imagen4 = '';

    $archivo1 = '';
    $archivo2 = '';
    $archivo3 = '';
    $archivo4 = '';
    $archivo5 = '';

    $pixelcode = post_html('pixelcode');
    $short_link = post_html('short_link');

    $inicio_numeracion = post('inicio_numeracion');


    if (isset_archivo('imagen')) {
        $imagen = time() . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
        //sube_imagen(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
    }
    if (isset_archivo('imagen2')) {
        $imagen2 = time() . archivoName('imagen2');
        move_uploaded_file(archivo('imagen2'), "contenido/imagenes/paginas/$imagen2");
    }
    if (isset_archivo('imagen3')) {
        $imagen3 = time() . archivoName('imagen3');
        move_uploaded_file(archivo('imagen3'), "contenido/imagenes/paginas/$imagen3");
    }
    if (isset_archivo('imagen4')) {
        $imagen4 = time() . archivoName('imagen4');
        move_uploaded_file(archivo('imagen4'), "contenido/imagenes/paginas/$imagen4");
    }


    if (isset_archivo('archivo1')) {
        $archivo1 = 'C' . $id_curso . '-' . archivoName('archivo1');
        move_uploaded_file(archivo('archivo1'), "contenido/archivos/cursos/$archivo1");
    }
    if (isset_archivo('archivo2')) {
        $archivo2 = 'C' . $id_curso . '-' . archivoName('archivo2');
        move_uploaded_file(archivo('archivo2'), "contenido/archivos/cursos/$archivo2");
    }
    if (isset_archivo('archivo3')) {
        $archivo3 = 'C' . $id_curso . '-' . archivoName('archivo3');
        move_uploaded_file(archivo('archivo3'), "contenido/archivos/cursos/$archivo3");
    }
    if (isset_archivo('archivo4')) {
        $archivo4 = 'C' . $id_curso . '-' . archivoName('archivo4');
        move_uploaded_file(archivo('archivo4'), "contenido/archivos/cursos/$archivo4");
    }
    if (isset_archivo('archivo5')) {
        $archivo5 = 'C' . $id_curso . '-' . archivoName('archivo5');
        move_uploaded_file(archivo('archivo5'), "contenido/archivos/cursos/$archivo5");
    }
    
    if (isset_organizador()) {
        $id_organizador = organizador('id');
        $flag_publicacion = '3';
    }
    
    query("INSERT INTO cursos (
              contenido,
              titulo,
              titulo_identificador,
              flag_publicacion,
              sw_siguiente_semana,
              id_organizador,
              incrustacion,
              google_maps,
              id_ciudad,
              id_categoria,
              lugar,
              salon,
              horarios,
              fecha,
              costo,
              fecha2,
              costo2,
              sw_fecha2,
              fecha3,
              costo3,
              sw_fecha3,
              fecha_e,
              costo_e,
              sw_fecha_e,
              fecha_e2,
              costo_e2,
              sw_fecha_e2,
              expositor,
              colaborador,
              gastos,
              observaciones,
              imagen,
              imagen2,
              imagen3,
              imagen4,
              archivo1,
              archivo2,
              archivo3,
              archivo4,
              archivo5,
              short_link,
              inicio_numeracion,
              pixelcode,
              fecha_registro,
              estado
              )
              VALUES ( 
              '$contenido',
              '$titulo',
              '$titulo_identificador',
              '$flag_publicacion',
              '$sw_siguiente_semana',
              '$id_organizador',
              '$incrustacion',
              '$google_maps',
              '$id_departamento',
              '$id_categoria',
              '$lugar',
              '$salon',
              '$horarios',
              '$fecha',
              '$costo',
              '$fecha2',
              '$costo2',
              '$sw_fecha2',
              '$fecha3',
              '$costo3',
              '$sw_fecha3',
              '$fecha_e',
              '$costo_e',
              '$sw_fecha_e',
              '$fecha_e2',
              '$costo_e2',
              '$sw_fecha_e2',
              '$expositor',
              '$colaborador',
              '$gastos',
              '$observaciones',
              '$imagen',
              '$imagen2',
              '$imagen3',
              '$imagen4',
              '$archivo1',
              '$archivo2',
              '$archivo3',
              '$archivo4',
              '$archivo5',
              '$short_link',
              '$inicio_numeracion',
              '$pixelcode',
              '$fecha_registro',
              '$estado'
              )");
    $id_curso = mysql_insert_id();

    /* cantidad de cursos por categoria */
    $rqdcccc1 = query("SELECT cnt_cursos_total FROM cursos_categorias WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");
    $rqdcccc2 = mysql_fetch_array($rqdcccc1);
    $cnt_cursos_total_n = $rqdcccc2['cnt_cursos_total'];
    query("UPDATE cursos_categorias SET cnt_cursos_total='$cnt_cursos_total_n' WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");

    movimiento('Creacion de datos de curso', 'creacion-curso', 'curso', $id_curso);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el curso fue creado correctamente.
    </div>';
}

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
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> <?php echo $curso['titulo']; ?> </h3>
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

                <div class="panel panel-primary">
                    <div class="panel-heading">DATOS DEL CURSO</div>
                    <form enctype="multipart/form-data" action="" method="post">
                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">DATOS 1</a></li>
                                <li><a data-toggle="tab" href="#menu1">DATOS 2</a></li>
                                <li><a data-toggle="tab" href="#menu2">DATOS 3</a></li>
                                <li><a data-toggle="tab" href="#menu3">DATOS 4</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del Curso: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="titulo" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                            </td>
                                            <td>
                                                <select class="form-control form-cascade-control" name="id_categoria">
                                                    <?php
                                                    $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                    while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                        ?>
                                                        <option value="<?php echo $rqd2['id']; ?>" ><?php echo $rqd2['titulo']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php
                                        if (!isset_organizador()) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Organizador: </span>
                                                </td>
                                                <td>
                                                    <select class="form-control form-cascade-control" name="id_organizador">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_organizadores WHERE estado='1' ");
                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" ><?php echo $rqd2['titulo']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Publicado en: </span>
                                                </td>
                                                <td>
                                                    <p class="form-control text-center">
                                                        <input type="radio" name="flag_publicacion" value="1" id="flag_publicacion1"/> <label for="flag_publicacion1">Infosicoes/Cursos.BO</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="flag_publicacion" value="2" id="flag_publicacion2"/> <label for="flag_publicacion2"> Infosicoes</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="flag_publicacion" value="3" id="flag_publicacion3"/> <label for="flag_publicacion3"> Cursos.BO</label>
                                                    </p>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                            </td>
                                            <td>
                                                <p class="form-control text-center">
                                                    <input type="radio" name="estado" value="1" id="act" checked=""/> <label for="act">Activado</label> 
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="estado" value="0" id="dact"/> <label for="dact"> Desactivado</label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="estado" value="2" id="temp"/> <label for="temp"> Temporal</label>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Semana del curso: </span>
                                            </td>
                                            <td>
                                                <p class="form-control text-center">
                                                    <input type="radio" name="sw_siguiente_semana" value="0" id="act_s" checked=""/> <label for="act_s">Esta Semana</label> 
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="sw_siguiente_semana" value="1" id="dact_s"/> <label for="dact_s"> Siguiente Semana</label>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del curso: </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="col-md-5">
                                                        <input type="date" name="fecha" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-5 text-right">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                            <input type="number" name="costo" value="" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="" class="" value="1" checked="" disabled=""> Habilitado
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 1: </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="col-md-5">
                                                        <input type="date" name="fecha2" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-5 text-right">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                            <input type="number" name="costo2" value="" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="sw_fecha2" class="" value="1"> Habilitado
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 2: </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="col-md-5">
                                                        <input type="date" name="fecha3" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-5 text-right">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                            <input type="number" name="costo3" value="" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="sw_fecha3" class="" value="1"> Habilitado
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha estudiantes: </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="col-md-5">
                                                        <input type="date" name="fecha_e" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-5 text-right">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                            <input type="number" name="costo_e" value="" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="sw_fecha_e" class="" value="1"> Habilitado
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 3: </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="col-md-5">
                                                        <input type="date" name="fecha_e2" class="form-control" value="">
                                                    </div>
                                                    <div class="col-md-5 text-right">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                            <input type="number" name="costo_e2" value="" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="checkbox" name="sw_fecha_e2" class="" value="1"> Habilitado
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Departamento: </span>
                                            </td>
                                            <td>
                                                <select class="form-control" name="id_departamento">
                                                    <?php
                                                    $rqd1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ");
                                                    while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                        echo '<option value="' . $rqd2['id'] . '"  >' . $rqd2['nombre'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Lugar: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="lugar" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Sal&oacute;n: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="salon" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Horarios: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="horarios" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Google Maps: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="google_maps" class="form-control" value='' placeholder='<iframe src="https://www.google.com/maps/embed?pb=!1m17...'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Expositor: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="expositor" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Colaborador: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="colaborador" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Gastos: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="gastos" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Observaciones: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="observaciones" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; URL corta: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="short_link" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Inicio numeraci&oacute;n: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="inicio_numeracion" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Pixel Code: </span>
                                            </td>
                                            <td>
                                                <textarea name="pixelcode" class="form-control" ></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu2" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="imagen" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 2: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="imagen2" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 3: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="imagen3" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 4: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="imagen4" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>1</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="archivo1" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>2</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="archivo2" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>3</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="archivo3" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>4</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="archivo4" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>5</b>: </span>
                                            </td>
                                            <td>
                                                <div class="col-md-8">
                                                    <input type="file" name="archivo5" class="form-control">
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu3" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <br/>
                                                <b>Palabras reservadas:</b>   &nbsp;,&nbsp;  
                                                <span style="font-size:11pt;">[imagen-1]  &nbsp;,&nbsp;  [imagen-2]  &nbsp;,&nbsp;  [imagen-3]  &nbsp;,&nbsp;  [imagen-4]</span> 
                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:#7a54da;">[REPORTE-SU-PAGO]</span>  &nbsp;,&nbsp;  
                                                <span style="font-size:11pt;">[ARCHIVO-1]  &nbsp;,&nbsp;  [ARCHIVO-2]  &nbsp;,&nbsp;  [ARCHIVO-3]  &nbsp;,&nbsp;  [ARCHIVO-4]  &nbsp;,&nbsp;  [ARCHIVO-5]</span> 
                                                <br/>
                                                <br/>
                                                <textarea name="formulario" id="editor1" style="width:100% !important;margin:auto;height:400px;" rows="25"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Codigo de incrustaci&oacute;n</b>
                                                <br/>
                                                <textarea name="incrustacion" class="form-control" style="width:100%;margin:auto;" rows="2"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="submit" value="CREAR CURSO" class="btn btn-success btn-block active"/>
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
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </td>
                            <td>
<!--                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>-->
                                <textarea  class="form-control" name="contenido_uno_certificado" rows="2"><?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </td>
                            <td>
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                            </td>
                            <td>
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> d&iacute;as del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018.</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TEXTO QR:</b></span>
                            </td>
                            <td>
                                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $curso['titulo']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FIRMA 1 :</b></span>
                            </td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FIRMA 2 :</b></span>
                            </td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                            </td>
                            <td>
                                <br/>
                                <input type="radio" name="sw_solo_nombre" value="0" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1" checked="" />
                                Solo Nombre-Fecha
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>Formato:</b></span>
                            </td>
                            <td>
                                <select name="formato" class="form-control">
                                    <option value="3">NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                    <option value="5">Formato 5 | QR en la parte superior</option>
                                    <option value="2">CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                </select> 
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">
                                <input type='submit' name='habilitar-certificado' class="btn btn-success" value="HABILITAR CERTIFICADO"/>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                            </td>
                        </tr>
                    </table>
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
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </td>
                            <td>
<!--                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>-->
                                <textarea  class="form-control" name="contenido_uno_certificado" rows="2"><?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </td>
                            <td>
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                            </td>
                            <td>
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2018</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TEXTO QR:</b></span>
                            </td>
                            <td>
                                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $curso['titulo']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FIRMA 1 :</b></span>
                            </td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FIRMA 2 :</b></span>
                            </td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                            </td>
                            <td>
                                <br/>
                                <input type="radio" name="sw_solo_nombre" value="0" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1" checked=""/>
                                Solo Nombre-Fecha
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>Formato:</b></span>
                            </td>
                            <td>
                                <select name="formato" class="form-control">
                                    <option value="3">NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                    <option value="5">Formato 5 | QR en la parte superior</option>
                                    <option value="2">CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">
                                <input type='submit' name='habilitar-certificado-2' class="btn btn-success" value="HABILITAR 2do CERTIFICADO"/>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                            </td>
                        </tr>
                    </table>

                    <p>
                        En la opci&oacute;n impresion solo Nombre-Fecha, solamente se generara un certificado con unicamente 
                        el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n  el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i></p>


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




<!-- Modal - crear certificado secundario -->
<div id="MODAL-crear-certificado-secundario" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNACI&Oacute;N DE UN NUEVO CERTIFICADO SECUNDARIO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera asignara un nuevo certificado secundario para los participantes inscritos.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Titulo del Curso:
                                <br/>
                                <input type="text" name="cont_titulo_curso" value='<?php echo utf8_encode('"TÉCNICAS DE COMUNICACIÓN ORAL Y CORPORAL EN AULA"'); ?>' class="form-control" id="date" required="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n:
                                <br/>
                                <input type="text" name="cont_dos" value='<?php echo utf8_encode("Con una carga horaria de 10 horas llevado a cabo en un CICLO DE CURSOS DE ACTUALIZACIÓN PEDAGÓGICA, del 04 al 09 del mes de Diciembre del año 2018.") ?>' class="form-control" id="date" required="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Lugar y fecha:
                                <br/>
                                <input type="text" name="cont_tres" value='LA PAZ - BOLIVIA <?php echo date('d'); ?> DE <?php echo strtoupper($array_meses[(int) date('m')]); ?> DEL <?php echo date('Y'); ?>' class="form-control" id="date" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="asignacion-certificado-secundario" value="ASIGNAR CERTIFICADO SECUNDARIO" class="btn btn-success btn-lg btn-animate-demo active"/>
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
<!-- End Modal - crear certificado secundario -->







<!-- Modal - crear turno -->
<div id="MODAL-crear-turno" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREACI&Oacute;N TURNO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario se habilitara para el curso '<?php echo $curso['titulo']; ?>' un nuevo turno para inscripci&oacute;n de participantes.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Titulo del Turno:
                                <br/>
                                <input type="text" name="titulo" value='' class="form-control" id="date" required="" placeholder="NOCHE"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n:
                                <br/>
                                <input type="text" name="descripcion" value='' class="form-control" id="date" required="" placeholder="Desde horas 19:00 a 20:30"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="crear-turno" value="AGREGAR TURNO" class="btn btn-success btn-lg btn-animate-demo active"/>
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
<!-- End Modal - crear turno -->



<!-- Modal - habilitar online course -->
<div id="MODAL-habilitar-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">HABILITACION DE CURSO EN LINEA</h4>
            </div>
            <div class="modal-body">
                <p>
                    Ingrese a continuaci&oacute;n las caracteristicas generales del curso.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n:
                                <br/>
                                <textarea name="descripcion" class="form-control" rows="2" required="" placeholder="Descripci&oacute;n dada a los participantes del curso."></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="habilitar-onlinecourse" value="HABILITAR CURSO ONLINE" class="btn btn-success btn-lg btn-animate-demo active"/>
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
<!-- End Modal - habilitar online course -->









<?php

function verificador_fecha($dat) {
    if ($dat == '') {
        return "0000-00-00";
    } else {
        return $dat;
    }
}
?>
