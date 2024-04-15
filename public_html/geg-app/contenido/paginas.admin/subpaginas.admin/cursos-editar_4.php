<?php
//id pagina
$id_curso = (int) $get[2];

$mensaje = '';

/* habilitacion de certificado */
if (isset_post('habilitar-certificado')) {

    $cont_titulo = post('titulo_certificado');
    $cont_uno = post('contenido_uno_certificado');
    $cont_dos = post('contenido_dos_certificado');
    $cont_tres = post('contenido_tres_certificado');

    $sw_solo_nombre = post('sw_solo_nombre');
    $formato = post('formato');

    $id_firma1 = post('firma1');
    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma1_imagen = "";
    $id_firma2 = post('firma2');
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = "";

    /* imagen firma */
    if (is_uploaded_file(archivo('firma1_imagen'))) {
        $firma1_imagen = time() . archivoName('firma1_imagen');
        move_uploaded_file(archivo('firma1_imagen'), "contenido/imagenes/firmas/$firma1_imagen");
    }
    if (is_uploaded_file(archivo('firma2_imagen'))) {
        $firma2_imagen = time() . archivoName('firma2_imagen');
        move_uploaded_file(archivo('firma2_imagen'), "contenido/imagenes/firmas/$firma2_imagen");
    }

    $codigo_certificado = "CERT-1-$id_curso";

    /* verificacion de existencia */
    $rqve1 = query("SELECT id FROM cursos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
    if (mysql_num_rows($rqve1) == 0) {
        query("INSERT INTO cursos_certificados
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres,sw_solo_nombre,formato,id_firma1, firma1_nombre, firma1_cargo, firma1_imagen, id_firma2, firma2_nombre, firma2_cargo, firma2_imagen, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$sw_solo_nombre','$formato','$id_firma1','$firma1_nombre','$firma1_cargo','$firma1_imagen','$id_firma2','$firma2_nombre','$firma2_cargo','$firma2_imagen','1')");
        $rqirc1 = query("SELECT id FROM cursos_certificados WHERE codigo='$codigo_certificado' ORDER BY id DESC limit 1 ");
        $rqirc2 = mysql_fetch_array($rqirc1);
        $id_certificado = $rqirc2['id'];
        query("UPDATE cursos SET id_certificado='$id_certificado' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue habilitado exitosamente. 
</div>';
    }
}


/* habilitacion de 2do certificado */
if (isset_post('habilitar-certificado-2')) {

    $cont_titulo = post('titulo_certificado');
    $cont_uno = post('contenido_uno_certificado');
    $cont_dos = post('contenido_dos_certificado');
    $cont_tres = post('contenido_tres_certificado');

    $sw_solo_nombre = post('sw_solo_nombre');
    $formato = post('formato');

    $id_firma1 = post('firma1');
    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma1_imagen = "";
    $id_firma2 = post('firma2');
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = "";

    $codigo_certificado = "CERT-2-$id_curso";

    /* verificacion de existencia */
    $rqve1 = query("SELECT id FROM cursos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
    if (mysql_num_rows($rqve1) == 1) {
        query("INSERT INTO cursos_certificados
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres,sw_solo_nombre,formato,id_firma1, firma1_nombre, firma1_cargo, firma1_imagen, id_firma2, firma2_nombre, firma2_cargo, firma2_imagen, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$sw_solo_nombre','$formato','$id_firma1','$firma1_nombre','$firma1_cargo','$firma1_imagen','$id_firma2','$firma2_nombre','$firma2_cargo','$firma2_imagen','1')");
        $rqirc1 = query("SELECT id FROM cursos_certificados WHERE codigo='$codigo_certificado' ORDER BY id DESC limit 1 ");
        $rqirc2 = mysql_fetch_array($rqirc1);
        $id_certificado = $rqirc2['id'];
        query("UPDATE cursos SET id_certificado_2='$id_certificado' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El 2do certificado fue habilitado exitosamente. 
</div>';
    }
}

/* edicion certificado */
if (isset_post('editar-certificado')) {

    $cont_titulo = post('titulo_certificado');
    $cont_uno = post('contenido_uno_certificado');
    $cont_dos = post('contenido_dos_certificado');
    $cont_tres = post('contenido_tres_certificado');

    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma1_imagen = post('firma1_imagen_previo');
    $id_firma1 = post('firma1');

    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = post('firma2_imagen_previo');
    $id_firma2 = post('firma2');

    /* imagen firma */
    if (is_uploaded_file(archivo('firma1_imagen'))) {
        $firma1_imagen = time() . archivoName('firma1_imagen');
        move_uploaded_file(archivo('firma1_imagen'), "contenido/imagenes/firmas/$firma1_imagen");
    }
    if (is_uploaded_file(archivo('firma2_imagen'))) {
        $firma2_imagen = time() . archivoName('firma2_imagen');
        move_uploaded_file(archivo('firma2_imagen'), "contenido/imagenes/firmas/$firma2_imagen");
    }

    $sw_solo_nombre = post('sw_solo_nombre');
    $formato = post('formato');

    $id_cert = post('id_certificado');

    query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           id_firma1='$id_firma1',  
           firma1_nombre='$firma1_nombre',  
           firma1_cargo='$firma1_cargo',  
           firma1_imagen='$firma1_imagen',  
           id_firma2='$id_firma2',  
           firma2_nombre='$firma2_nombre',  
           firma2_cargo='$firma2_cargo', 
           firma2_imagen='$firma2_imagen', 
           sw_solo_nombre='$sw_solo_nombre', 
           formato='$formato' 
           WHERE id='$id_cert' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
}

/* edicion certificado 2 */
if (isset_post('editar-certificado-2')) {

    $cont_titulo = post('titulo_certificado');
    $cont_uno = post('contenido_uno_certificado');
    $cont_dos = post('contenido_dos_certificado');
    $cont_tres = post('contenido_tres_certificado');

    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma1_imagen = post('firma1_imagen_previo');
    $id_firma1 = post('firma1');

    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = post('firma2_imagen_previo');
    $id_firma2 = post('firma2');


    $sw_solo_nombre = post('sw_solo_nombre');
    $formato = post('formato');

    $id_cert = post('id_certificado');

    query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           id_firma1='$id_firma1',  
           firma1_nombre='$firma1_nombre',  
           firma1_cargo='$firma1_cargo',  
           firma1_imagen='$firma1_imagen',  
           id_firma2='$id_firma2',  
           firma2_nombre='$firma2_nombre',  
           firma2_cargo='$firma2_cargo', 
           firma2_imagen='$firma2_imagen', 
           sw_solo_nombre='$sw_solo_nombre',
           formato='$formato' 
           WHERE id='$id_cert' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
}

/* edicion de estado */
if (isset_post('actualizar-estado')) {
    if (acceso_cod('adm-cursos-estado')) {

        $estado = post('estado');

        /* datos de curso */
        $rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        $rqdc2 = mysql_fetch_array($rqdc1);
        $titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);
        if ($estado == '2') {
            $titulo_identificador .= '-tmp';
        }
        query("UPDATE cursos SET estado='$estado',titulo_identificador='$titulo_identificador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        movimiento('Edicion de estado de curso', 'edicion-curso', 'curso', $id_curso);

        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> estado de curso actualizado correctamente.
    </div>';
    }
}


/* edicion de curso */
if (isset_post('formulario')) {

    $id = post('id');
    $contenido = post_html('formulario');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
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
    $lugar = post('lugar');
    $salon = post('salon');
    $horarios = post('horarios');
    $expositor = post('expositor');
    $colaborador = post('colaborador');
    $gastos = post('gastos');
    $observaciones = post('observaciones');


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

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');

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

    query("UPDATE cursos SET 
              contenido='$contenido',
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              sw_siguiente_semana='$sw_siguiente_semana',
              incrustacion='$incrustacion',
              google_maps='$google_maps',
              id_ciudad='$id_departamento',
              lugar='$lugar',
              salon='$salon',
              horarios='$horarios',
              fecha='$fecha',
              costo='$costo',
              fecha2='$fecha2',
              costo2='$costo2',
              sw_fecha2='$sw_fecha2',
              fecha3='$fecha3',
              costo3='$costo3',
              sw_fecha3='$sw_fecha3',
              fecha_e='$fecha_e',
              costo_e='$costo_e',
              sw_fecha_e='$sw_fecha_e',
              expositor='$expositor',
              colaborador='$colaborador',
              gastos='$gastos',
              observaciones='$observaciones',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              short_link='$short_link',
              inicio_numeracion='$inicio_numeracion',
              pixelcode='$pixelcode' 
               WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    movimiento('Edicion de datos de curso', 'edicion-curso', 'curso', $id_curso);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos de curso actualizados correctamente.
    </div>';
}


/* asignacion-certificado-secundario */
if (isset_post('asignacion-certificado-secundario')) {

    $cont_titulo_curso = post('cont_titulo_curso');
    $cont_dos = post('cont_dos');
    $cont_tres = post('cont_tres');

    query("INSERT INTO cursos_modelos_certificados(
          id_curso,
          descripcion, 
          cont_titulo_curso, 
          cont_dos, 
          cont_tres, 
          estado
          ) VALUES (
          '$id_curso',
          '$cont_titulo_curso',
          '$cont_titulo_curso',
          '$cont_dos',
          '$cont_tres',
          '1'
          )");

    $rqmci1 = query("SELECT id FROM cursos_modelos_certificados WHERE descripcion='$cont_titulo_curso' ORDER BY id DESC limit 1 ");
    $rqmci2 = mysql_fetch_array($rqmci1);

    $codd = "CS" . str_pad($rqmci2['id'], 3, "0", STR_PAD_LEFT);
    query("UPDATE cursos_modelos_certificados SET codigo='$codd' WHERE id='" . $rqmci2['id'] . "' ");

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> Registro agregado correctamente.
    </div>';
}

/* edicion-certificado-secundario */
if (isset_post('edicion-certificado-secundario')) {

    $id_modelo_certificado = post('id_modelo_certificado');
    $cont_titulo_curso = post('cont_titulo_curso');
    $cont_dos = post('cont_dos');
    $cont_tres = post('cont_tres');

    query("UPDATE cursos_modelos_certificados SET 
          descripcion='$cont_titulo_curso', 
          cont_titulo_curso='$cont_titulo_curso', 
          cont_dos='$cont_dos', 
          cont_tres='$cont_tres' 
           WHERE id='$id_modelo_certificado' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> Registro editado correctamente.
    </div>';
}


/* creaciond de turno */
if (isset_post('crear-turno')) {

    $titulo = post('titulo');
    $descripcion = post('descripcion');

    query("INSERT INTO cursos_turnos (
          id_curso,
          titulo, 
          descripcion,
          estado
          ) VALUES (
          '$id_curso',
          '$titulo',
          '$descripcion',
          '1'
          )");

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> El turno se agrego correctamente.
    </div>';
}

/* edicion turno */
if (isset_post('edicion-turno')) {

    $id_turno = post('id_turno');
    $titulo = post('titulo');
    $descripcion = post('descripcion');
    $estado = post('estado');

    query("UPDATE cursos_turnos SET 
          titulo='$titulo', 
          descripcion='$descripcion', 
          estado='$estado' 
           WHERE id='$id_turno' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> Registro editado correctamente.
    </div>';
}

/* habilitar-onlinecourse */
if (isset_post('habilitar-onlinecourse')) {
    $descripcion = post('descripcion');
    $rqmc1 = query("SELECT * FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
    if (mysql_num_rows($rqmc1) == 0) {
        $urltag = $id_curso . '-' . md5(rand(9, 99));
        query("INSERT INTO cursos_onlinecourse(id_curso, descripcion, estado) VALUES ('$id_curso','$descripcion','$urltag','1')");
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> El curso en linea se habilito correctamente.
    </div>';
    }
}


/* agregar-material-onlinecourse */
if (isset_post('agregar-material-onlinecourse')) {
    if (isset_archivo('archivo')) {
        $nombre = post('nombre');
        $tipo_archivo = post('tipo_archivo');

        $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
        $rqmc2 = mysql_fetch_array($rqmc1);
        $id_onlinecourse = $rqmc2['id'];

        $arch = $id_curso . '-' . archivoName('archivo');
        move_uploaded_file(archivo('archivo'), "contenido/archivos/cursos/$arch");
        query("INSERT INTO cursos_onlinecourse_material(id_onlinecourse, nombre, formato_archivo, nombre_fisico, estado) VALUES ('$id_onlinecourse','$nombre','$tipo_archivo','$arch','1')");

        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
    }
}



$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);


/* url_corta */
$url_corta = 'https://infosicoes.com/crs-' . $curso['id'] . '.html';

editorTinyMCE('editor1');
$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
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

                <?php
                if (acceso_cod('adm-cursos-estado')) {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">ESTADO DEL CURSO - <?php echo $curso['titulo']; ?></div>
                        <div class="panel-body">

                            <form enctype="multipart/form-data" action="" method="post">
                                <table style="width:100%;" class="table table-striped">
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                        </td>
                                        <td>
                                            <p class="form-control text-center">
                                                <?php
                                                $check = '';
                                                if ($curso['estado'] == '1') {
                                                    $check = 'checked="checked"';
                                                }
                                                ?>
                                                <input type="radio" name="estado" <?php echo $check; ?> value="1" id="act"/> <label for="act">Activado</label> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php
                                                $check2 = '';
                                                if ($curso['estado'] == '0') {
                                                    $check2 = 'checked="checked"';
                                                }
                                                ?>
                                                <input type="radio" name="estado" <?php echo $check2; ?> value="0" id="dact"/> <label for="dact"> Desactivado</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php
                                                $check2 = '';
                                                if ($curso['estado'] == '2') {
                                                    $check2 = 'checked="checked"';
                                                }
                                                ?>
                                                <input type="radio" name="estado" <?php echo $check2; ?> value="2" id="temp"/> <label for="temp"> Temporal</label>
                                            </p>
                                        </td>
                                        <td>
                                            <div>
                                                <input type="submit" name="actualizar-estado" value="ACTUALIZAR ESTADO" class="btn btn-success btn-sm active"/>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                                $rqcp1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ");
                                $rqcp2 = mysql_fetch_array($rqcp1);
                                $cnt_participantes = $rqcp2['total'];
                                ?>
                                En este curso se registraron <?php echo $cnt_participantes; ?> participantes -> <a href="cursos-participantes/<?php echo $id_curso; ?>.adm"> <i class="fa fa-group"></i> LISTADO DE PARTICIPANTES</a>
                                &nbsp;|&nbsp;
                                <a href="cursos/<?php echo $curso['titulo_identificador']; ?>.html" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR EL CURSO</a>
                                &nbsp;|&nbsp;
                                <a href="cursos-listar.adm"> <i class="fa fa-list"></i> LISTADO DE CURSOS</a>
                                &nbsp;|&nbsp;
                                <i class="fa fa-link"></i>
                                &nbsp;&nbsp;
                                <a href="<?php echo $url_corta; ?>" target="_blank"><?php echo $url_corta; ?></a>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <div class="panel panel-primary">
                    <div class="panel-heading">DATOS DEL CURSO - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">


                        <form enctype="multipart/form-data" action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del Curso: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="titulo" value="<?php echo $curso['titulo']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Semana del curso: </span>
                                    </td>
                                    <td>
                                        <p class="form-control text-center">
                                            <?php
                                            $check = '';
                                            if ($curso['sw_siguiente_semana'] == '0') {
                                                $check = 'checked="checked"';
                                            }
                                            ?>
                                            <input type="radio" name="sw_siguiente_semana" <?php echo $check; ?> value="0" id="act_s"/> <label for="act_s">Esta Semana</label> 
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php
                                            $check2 = '';
                                            if ($curso['sw_siguiente_semana'] == '1') {
                                                $check2 = 'checked="checked"';
                                            }
                                            ?>
                                            <input type="radio" name="sw_siguiente_semana" <?php echo $check2; ?> value="1" id="dact_s"/> <label for="dact_s"> Siguiente Semana</label>
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
                                                <input type="date" name="fecha" class="form-control" value="<?php echo $curso['fecha']; ?>">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                    <input type="number" name="costo" value="<?php echo $curso['costo']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
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
                                                <input type="date" name="fecha2" class="form-control" value="<?php echo $curso['fecha2']; ?>">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                    <input type="number" name="costo2" value="<?php echo $curso['costo2']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                $check_f = '';
                                                if ($curso['sw_fecha2'] == '1') {
                                                    $check_f = ' checked="" ';
                                                }
                                                ?>
                                                <input type="checkbox" name="sw_fecha2" class="" <?php echo $check_f; ?> value="1"> Habilitado
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
                                                <input type="date" name="fecha3" class="form-control" value="<?php echo $curso['fecha3']; ?>">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                    <input type="number" name="costo3" value="<?php echo $curso['costo3']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                $check_f = '';
                                                if ($curso['sw_fecha3'] == '1') {
                                                    $check_f = ' checked="" ';
                                                }
                                                ?>
                                                <input type="checkbox" name="sw_fecha3" class="" <?php echo $check_f; ?> value="1"> Habilitado
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
                                                <input type="date" name="fecha_e" class="form-control" value="<?php echo $curso['fecha_e']; ?>">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                    <input type="number" name="costo_e" value="<?php echo $curso['costo_e']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                $check_f = '';
                                                if ($curso['sw_fecha_e'] == '1') {
                                                    $check_f = ' checked="" ';
                                                }
                                                ?>
                                                <input type="checkbox" name="sw_fecha_e" class="" <?php echo $check_f; ?> value="1"> Habilitado
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Departamento: </span>
                                    </td>
                                    <td>
                                        <select class="form-control" name="id_departamento">
                                            <?php
                                            $rqd1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ");
                                            while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                $selected = '';
                                                if ($curso['id_ciudad'] == $rqd2['id']) {
                                                    $selected = ' selected="selected" ';
                                                }
                                                echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
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
                                        <input type="text" name="lugar" value="<?php echo $curso['lugar']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Sal&oacute;n: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="salon" value="<?php echo $curso['salon']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Horarios: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="horarios" value="<?php echo $curso['horarios']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Google Maps: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="google_maps" class="form-control" value='<?php echo ($curso['google_maps']); ?>' placeholder='<iframe src="https://www.google.com/maps/embed?pb=!1m17...'>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Expositor: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="expositor" value="<?php echo $curso['expositor']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Colaborador: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="colaborador" value="<?php echo $curso['colaborador']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Gastos: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="gastos" value="<?php echo $curso['gastos']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Observaciones: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="observaciones" value="<?php echo $curso['observaciones']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; URL corta: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="short_link" value="<?php echo $curso['short_link']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Inicio numeraci&oacute;n: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="inicio_numeracion" value="<?php echo $curso['inicio_numeracion']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Pixel Code: </span>
                                    </td>
                                    <td>
                                        <textarea name="pixelcode" class="form-control" ><?php echo $curso['pixelcode']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                    </td>
                                    <td>
                                        <div class="col-md-8">
                                            <input type="file" name="imagen" class="form-control">
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <img src="paginas/<?php echo $curso['imagen']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
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
                                            <img src="paginas/<?php echo $curso['imagen2']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
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
                                            <img src="paginas/<?php echo $curso['imagen3']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
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
                                            <img src="paginas/<?php echo $curso['imagen4']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <br/>
                                        <b>Palabras reservadas:</b> [imagen-1]  ,  [imagen-2]  ,  [imagen-3]  ,  [imagen-4] 
                                        <br/>
                                        <br/>
                                        <textarea name="formulario" id="editor1" style="width:100% !important;margin:auto;" rows="25"><?php echo $curso['contenido']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <br/>
                                        <b>Codigo de incrustaci&oacute;n</b>
                                        <br/>
                                        <textarea name="incrustacion" class="form-control" style="width:100%;margin:auto;" rows="2"><?php echo $curso['incrustacion']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="hidden" name="id" value="<?php echo $curso['id']; ?>"/>
                                            <input type="hidden" name="preimagen" value="<?php echo $curso['imagen']; ?>"/>
                                            <input type="hidden" name="preimagen2" value="<?php echo $curso['imagen2']; ?>"/>
                                            <input type="hidden" name="preimagen3" value="<?php echo $curso['imagen3']; ?>"/>
                                            <input type="hidden" name="preimagen4" value="<?php echo $curso['imagen4']; ?>"/>
                                            <input type="submit" value="ACTUALIZAR DATOS DEL CURSO" class="btn btn-success btn-lg btn-animate-demo active"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">CERTIFICADO PRINCIPAL - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">
                        <?php
                        if ($curso['id_certificado'] == '0') {
                            ?>
                            <p>
                                El curso actual no tiene certificado principal asociado.
                            </p>
                            <a class="btn btn-warning" data-toggle="modal" data-target="#MODAL-asignar-certificado"><i class="fa fa-plus"></i> HABILITAR CERTIFICADO</a>
                            <?php
                        } else {
                            $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='" . $curso['id_certificado'] . "' ORDER BY id DESC limit 1 ");
                            $rqc2 = mysql_fetch_array($rqc1);
                            ?>

                            <b>Primer Certificado</b>
                            <p>El curso si tiene el primer certificado</p>
                            - <?php echo $rqc2['codigo']; ?>
                            <br/>
                            - <?php echo $rqc2['cont_titulo']; ?>
                            <br/>
                            - <?php echo $rqc2['cont_uno']; ?>
                            <br/>
                            - <?php echo $rqc2['cont_dos']; ?>
                            <br/>
                            - <?php echo $rqc2['cont_tres']; ?>
                            <br/>
                            - FIRMA 1 : 
                            <?php
                            if ($rqc2['id_firma1'] !== '0') {
                                $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma1'] . "' ");
                                $rqf2 = mysql_fetch_array($rqf1);
                                $url_firma = "Sin imagen de firma!";
                                $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                if (file_exists($url_img)) {
                                    $url_firma = $url_img;
                                }
                                echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . " <br/> <img src='$url_firma' style='height:25px;'/>";
                            } else {
                                echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo'];
                            }
                            ?>
                            <br/>
                            - FIRMA 2 : 
                            <?php
                            if ($rqc2['id_firma2'] !== '0') {
                                $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma2'] . "' ");
                                $rqf2 = mysql_fetch_array($rqf1);
                                $url_firma = "Sin imagen de firma!";
                                $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                if (file_exists($url_img)) {
                                    $url_firma = $url_img;
                                }
                                echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . " <br/> <img src='$url_firma' style='height:25px;'/>";
                            } else {
                                echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo'];
                            }
                            ?>
                            <br/>
                            - IMPRESION : 
                            <?php
                            if ($rqc2['sw_solo_nombre'] == '1') {
                                echo "Solo Nombre-Fecha";
                            } else {
                                echo "COMPLETA";
                            }
                            ?>
                            <br/>
                            - FORMATO : 
                            <?php
                            if ($rqc2['formato'] == '2') {
                                echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte lateral derecha';
                            } else {
                                echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte superior';
                            }
                            ?>

                            <br/>
                            <br/>
                            <a class='btn-sm btn-success' data-toggle="modal" data-target="#MODAL-editar-certificado">Editar datos de certificado</a>
                            <br/>


                            <!-- Modal-3 -->
                            <div id="MODAL-editar-certificado" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">EDICI&Oacute;N DE DATOS CERTIFICADO 1 : <?php echo $rqc2['codigo']; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action='' method='post' enctype="multipart/form-data">
                                                <div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>TITULO:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
                                                            <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>CONT. 1:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
    <!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                                                            <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>CONT. 2:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
    <!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                                                            <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
    <!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                                                            <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
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
                                                                    $selected_f1 = "";
                                                                    if ($rqc2['id_firma1'] == $rqfc2['id']) {
                                                                        $selected_f1 = " selected='selected' ";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f1; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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
                                                                    $selected_f2 = "";
                                                                    if ($rqc2['id_firma2'] == $rqfc2['id']) {
                                                                        $selected_f2 = " selected='selected' ";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f2; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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
                                                            <?php
                                                            $checked_uno = ' checked="" ';
                                                            $checked_dos = '';
                                                            if ($rqc2['sw_solo_nombre'] == '1') {
                                                                $checked_uno = '';
                                                                $checked_dos = ' checked="" ';
                                                            }
                                                            ?>
                                                            <input type="radio" name="sw_solo_nombre" value="0" <?php echo $checked_uno; ?> /> 
                                                            Completa
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="sw_solo_nombre" value="1" <?php echo $checked_dos; ?> />
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
                                                                <?php
                                                                $selected_f = '';
                                                                if ($rqc2['formato'] == '2') {
                                                                    $selected_f = ' selected="selected" ';
                                                                }
                                                                ?>
                                                                <option value="2" <?php echo $selected_f; ?> >CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                                                <?php
                                                                $selected_f = '';
                                                                if ($rqc2['formato'] == '3') {
                                                                    $selected_f = ' selected="selected" ';
                                                                }
                                                                ?>
                                                                <option value="3" <?php echo $selected_f; ?> >NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                                                <?php
                                                                $selected_f = '';
                                                                if ($rqc2['formato'] == '5') {
                                                                    $selected_f = ' selected="selected" ';
                                                                }
                                                                ?>
                                                                <option value="5" <?php echo $selected_f; ?> >Formato 5 | QR en la parte superior</option>
                                                            </select> 
                                                        </div>
                                                    </div>

                                                    <hr/>

                                                </div>
                                                <br/>
                                                <div class="text-center">
                                                    <input type='hidden' name='id_certificado' value='<?php echo $curso['id_certificado']; ?>'/>
                                                    <input type='submit' name='editar-certificado' class="btn btn-success" value="ACTUALIZAR CERTIFICADO"/>
                                                </div>


                                                <br/>
                                                <br/>
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
                            <!-- End Modal-3 -->


                            <hr/>

                            <b>Segundo Certificado</b>
                            <?php
                            if ($curso['id_certificado_2'] == '0') {
                                ?>
                                <p>El curso no tiene asignado un segundo certificado</p>
                                <a href="" class="btn-sm btn-warning" data-toggle="modal" data-target="#MODAL-asignar-certificado-2"><i class="fa fa-plus"></i> AGREGAR 2do CERTIFICADO</a>
                                <?php
                            } else {
                                $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='" . $curso['id_certificado_2'] . "' ORDER BY id DESC limit 1 ");
                                $rqc2 = mysql_fetch_array($rqc1);
                                ?>
                                <p>El curso si tiene asignado un segundo certificado</p>
                                - <?php echo $rqc2['codigo']; ?>
                                <br/>
                                - <?php echo $rqc2['cont_titulo']; ?>
                                <br/>
                                - <?php echo $rqc2['cont_uno']; ?>
                                <br/>
                                - <?php echo $rqc2['cont_dos']; ?>
                                <br/>
                                - <?php echo $rqc2['cont_tres']; ?>
                                <br/>
                                - FIRMA 1 : 
                                <?php
                                if ($rqc2['id_firma1'] !== '0') {
                                    $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma1'] . "' ");
                                    $rqf2 = mysql_fetch_array($rqf1);
                                    $url_firma = "Sin imagen de firma!";
                                    $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                    if (file_exists($url_img)) {
                                        $url_firma = $url_img;
                                    }
                                    echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . " <br/> <img src='$url_firma' style='height:25px;'/>";
                                } else {
                                    echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo'];
                                }
                                ?>
                                <br/>
                                - FIRMA 2 : 
                                <?php
                                if ($rqc2['id_firma2'] !== '0') {
                                    $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma2'] . "' ");
                                    $rqf2 = mysql_fetch_array($rqf1);
                                    $url_firma = "Sin imagen de firma!";
                                    $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                    if (file_exists($url_img)) {
                                        $url_firma = $url_img;
                                    }
                                    echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . " <br/> <img src='$url_firma' style='height:25px;'/>";
                                } else {
                                    echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo'];
                                }
                                ?>
                                <br/>
                                - IMPRESION : 
                                <?php
                                if ($rqc2['sw_solo_nombre'] == '1') {
                                    echo "Solo Nombre-Fecha";
                                } else {
                                    echo "COMPLETA";
                                }
                                ?>
                                <br/>
                                - FORMATO : 
                                <?php
                                if ($rqc2['formato'] == '2') {
                                    echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte lateral derecha';
                                } else {
                                    echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte superior';
                                }
                                ?>

                                <br/>
                                <br/>
                                <a class='btn-sm btn-success' data-toggle="modal" data-target="#MODAL-editar-certificado-2">Editar datos del 2do certificado</a>
                                <br/>


                                <!-- Modal-4 -->
                                <div id="MODAL-editar-certificado-2" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">EDICION DE DATOS DEL 2do CERTIFICADO</h4>
                                            </div>                                            
                                            <div class="modal-body">
                                                <form action='' method='post' enctype="multipart/form-data">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>TITULO:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
        <!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                                                                <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
        <!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                                                                <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
        <!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                                                                <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
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
                                                                        $selected_f1 = "";
                                                                        if ($rqc2['id_firma1'] == $rqfc2['id']) {
                                                                            $selected_f1 = " selected='selected' ";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f1; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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
                                                                        $selected_f2 = "";
                                                                        if ($rqc2['id_firma2'] == $rqfc2['id']) {
                                                                            $selected_f2 = " selected='selected' ";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f2; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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
                                                                <?php
                                                                $checked_uno = ' checked="" ';
                                                                $checked_dos = '';
                                                                if ($rqc2['sw_solo_nombre'] == '1') {
                                                                    $checked_uno = '';
                                                                    $checked_dos = ' checked="" ';
                                                                }
                                                                ?>
                                                                <input type="radio" name="sw_solo_nombre" value="0" <?php echo $checked_uno; ?> /> 
                                                                Completa
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="sw_solo_nombre" value="1" <?php echo $checked_dos; ?> />
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
                                                                    <?php
                                                                    $selected_f = '';
                                                                    if ($rqc2['formato'] == '2') {
                                                                        $selected_f = ' selected="selected" ';
                                                                    }
                                                                    ?>
                                                                    <option value="2" <?php echo $selected_f; ?> >CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                                                                    <?php
                                                                    $selected_f = '';
                                                                    if ($rqc2['formato'] == '3') {
                                                                        $selected_f = ' selected="selected" ';
                                                                    }
                                                                    ?>
                                                                    <option value="3" <?php echo $selected_f; ?> >NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                                                                    <?php
                                                                    $selected_f = '';
                                                                    if ($rqc2['formato'] == '5') {
                                                                        $selected_f = ' selected="selected" ';
                                                                    }
                                                                    ?>
                                                                    <option value="5" <?php echo $selected_f; ?> >Formato 5 | QR en la parte superior</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <hr/>

                                                    </div>
                                                    <br/>
                                                    <div class="text-center">
                                                        <input type='hidden' name='id_certificado' value='<?php echo $curso['id_certificado_2']; ?>'/>
                                                        <input type='submit' name='editar-certificado-2' class="btn btn-success" value="ACTUALIZAR CERTIFICADO"/>
                                                    </div>


                                                    <br/>
                                                    <br/>
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
                                <!-- End Modal-4 -->

                                <hr/>

                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </div>
                </div>






                <div class="panel panel-primary">
                    <div class="panel-heading">CERTIFICADOS SECUNDARIOS - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">

                        <?php
                        $rqmc1 = query("SELECT id FROM cursos_modelos_certificados WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");

                        if (mysql_num_rows($rqmc1) == 0) {
                            ?>
                            <p>
                                El curso actual no tiene certificados secundarios asociados.
                            </p>
                            <a class="btn btn-primary active" data-toggle="modal" data-target="#MODAL-crear-certificado-secundario"><i class="fa fa-plus"></i> AGREGAR NUEVO CERTIFICADO SECUNDARIO</a>
                            <?php
                        } else {
                            ?>
                            <table class="table table-striped table-condensed table-hover">
                                <?php
                                $resultado1 = query("SELECT * FROM cursos_modelos_certificados WHERE id_curso='$id_curso' ORDER BY id ASC ");
                                while ($producto = mysql_fetch_array($resultado1)) {
                                    ?>
                                    <tr>
                                        <td class="visible-lg"><?php echo $cnt--; ?></td>
                                        <td class="visible-lg">
                                            <?php
                                            echo "<b>" . $producto['codigo'] . "</b>";
                                            echo "<br/>";
                                            echo $producto['cont_titulo_curso'];
                                            ?>         
                                        </td>
                                        <td class="visible-lg">
                                            <?php
                                            echo $producto['cont_dos'];
                                            echo "<br/>";
                                            echo $producto['cont_tres'];
                                            ?> 
                                        </td>
                                        <td class="visible-lg" style="width:120px;">
                                            <a data-toggle="modal" data-target="#MODAL-editar-certificado-secundario-<?php echo $producto['id']; ?>" class="btn btn-xs btn-info active"><i class='fa fa-edit'></i> Editar</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <?php
                            $resultado1 = query("SELECT * FROM cursos_modelos_certificados WHERE id_curso='$id_curso' ORDER BY id ASC ");
                            while ($producto = mysql_fetch_array($resultado1)) {
                                ?>
                                <!-- Modal - crear certificado secundario -->
                                <div id="MODAL-editar-certificado-secundario-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">EDICI&Oacute;N DE CERTIFICADO SECUNDARIO</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <table style="width:100%;" class="table table-striped">
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Titulo del Curso:
                                                                <br/>
                                                                <input type="text" name="cont_titulo_curso" value='<?php echo $producto['cont_titulo_curso']; ?>' class="form-control" id="date" required="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n:
                                                                <br/>
                                                                <input type="text" name="cont_dos" value='<?php echo $producto['cont_dos']; ?>' class="form-control" id="date" required="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Lugar y fecha:
                                                                <br/>
                                                                <input type="text" name="cont_tres" value='<?php echo $producto['cont_tres']; ?>' class="form-control" id="date" required="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="text-align: center;padding:20px;">
                                                                    <input type="hidden" name="id_modelo_certificado" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="edicion-certificado-secundario" value="EDITAR CERTIFICADO SECUNDARIO" class="btn btn-success btn-lg btn-animate-demo active"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal - crear certificado secundario -->
                                <?php
                            }
                            ?>
                            <p>
                                Para agregar un nuevo certificado secundario haga click en el siguiente boton:
                            </p>
                            <a class="btn btn-primary active" data-toggle="modal" data-target="#MODAL-crear-certificado-secundario"><i class="fa fa-plus"></i> AGREGAR NUEVO CERTIFICADO SECUNDARIO</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>






                <div class="panel panel-primary">
                    <div class="panel-heading">TURNOS AGREGADOS - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">

                        <?php
                        $rqmc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");

                        if (mysql_num_rows($rqmc1) == 0) {
                            ?>
                            <p>
                                El curso actual no tiene turnos agregados.
                            </p>
                            <a class="btn btn-primary active" data-toggle="modal" data-target="#MODAL-crear-turno"><i class="fa fa-plus"></i> AGREGAR NUEVO CERTIFICADO SECUNDARIO</a>
                            <?php
                        } else {
                            ?>
                            <table class="table table-striped table-condensed table-hover">
                                <?php
                                while ($producto = mysql_fetch_array($rqmc1)) {
                                    ?>
                                    <tr>
                                        <td class="visible-lg"><?php echo $cnt--; ?></td>
                                        <td class="visible-lg">
                                            <?php
                                            echo "<b><i>Titulo:</i></b> &nbsp; " . $producto['titulo'];
                                            ?>         
                                        </td>
                                        <td class="visible-lg">
                                            <?php
                                            echo '<b><i>Descripci&oacute;n:</i> &nbsp; </b> ' . $producto['descripcion'];
                                            ?> 
                                        </td>
                                        <td class="visible-lg">
                                            <?php
                                            echo '<b><i>Estado:</i> &nbsp; </b> ';
                                            if ($producto['estado'] == '1') {
                                                echo "<b style='color:green;'>Habilitado</b>";
                                            } else {
                                                echo "<b style='color:red;'>Deshabilitado</b>";
                                            }
                                            ?> 
                                        </td>
                                        <td class="visible-lg" style="width:120px;">
                                            <a data-toggle="modal" data-target="#MODAL-editar-turno-<?php echo $producto['id']; ?>" class="btn btn-xs btn-warning active"><i class='fa fa-edit'></i> Editar</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <?php
                            $resultado1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                            while ($producto = mysql_fetch_array($resultado1)) {
                                ?>
                                <!-- Modal - crear certificado secundario -->
                                <div id="MODAL-editar-turno-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">EDICI&Oacute;N DE TURNO</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <table style="width:100%;" class="table table-striped">
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Titulo del Turno:
                                                                <br/>
                                                                <input type="text" name="titulo" value='<?php echo $producto['titulo']; ?>' class="form-control" id="date" required="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n:
                                                                <br/>
                                                                <input type="text" name="descripcion" value='<?php echo $producto['descripcion']; ?>' class="form-control" id="date" required="">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-tags"></i> &nbsp; Estado:
                                                                <p class='text-center'>
                                                                    <?php
                                                                    $text_check = '';
                                                                    $text_check_2 = ' checked="checked "';
                                                                    if ($producto['estado'] == '1') {
                                                                        $text_check_2 = '';
                                                                        $text_check = ' checked="checked "';
                                                                    }
                                                                    ?>
                                                                    <input type='radio' name='estado' value='1' <?php echo $text_check; ?> /> Habilitado
                                                                    &nbsp;&nbsp; | &nbsp;&nbsp;
                                                                    <input type='radio' name='estado' value='0' <?php echo $text_check_2; ?>/> Deshabilitado
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div style="text-align: center;padding:20px;">
                                                                    <input type="hidden" name="id_turno" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="edicion-turno" value="EDITAR TURNO" class="btn btn-success btn-lg btn-animate-demo active"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal - crear certificado secundario -->
                                <?php
                            }
                            ?>

                            <p>
                                Para agregar un nuevo turno haga click en el siguiente boton:
                            </p>
                            <a class="btn btn-primary active" data-toggle="modal" data-target="#MODAL-crear-turno"><i class="fa fa-plus"></i> AGREGAR NUEVO TURNO</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>




                <div class="panel panel-primary">
                    <div class="panel-heading">CURSO ONLINE - <?php echo $curso['titulo']; ?></div>
                    <div class="panel-body">

                        <?php
                        $rqmc1 = query("SELECT * FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
                        if (mysql_num_rows($rqmc1) == 0) {
                            $sw_onlinecourse = false;
                        } else {
                            $rqmc2 = mysql_fetch_array($rqmc1);
                            if ($rqmc2['estado'] == '0') {
                                $sw_onlinecourse = false;
                            } else {
                                $sw_onlinecourse = true;
                            }
                        }

                        if (!$sw_onlinecourse) {
                            ?>
                            <p>
                                El curso actual no tiene habilitado la modalidad de 'Curso en linea'.
                            </p>
                            <a class="btn btn-primary active" data-toggle="modal" data-target="#MODAL-habilitar-onlinecourse"><i class="fa fa-plus"></i> HABILITAR CURSO EN LINEA</a>
                            <?php
                        } else {
                            $rqmc2['descripcion'];
                            ?>
                            <p>
                                CURSO EN LINEA HABILITADO
                            </p>
                            <b>Descripci&oacute;n:</b> <?php echo $rqmc2['descripcion']; ?>
                            <hr/>
                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <th colspan="5">MATERIAL DESCARGABLE</th>
                                </tr>
                                <?php
                                $rqmc1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id_onlinecourse='" . $rqmc2['id'] . "' AND estado='1' ");
                                if (mysql_num_rows($rqmc1) == 0) {
                                    echo '<div class="alert alert-info">
  <strong>Aviso!</strong> Este curso no tiene material descargable.
</div>';
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
                                            <a href="contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-warning active"><i class='fa fa-eye'></i> ver/descargar</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <a class="btn btn-warning active" data-toggle="modal" data-target="#MODAL-material-onlinecourse"><i class="fa fa-plus"></i> AGREGAR MATERIAL DESCARGABLE</a>

                            <hr/>
                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <th colspan="5">PREGUNTAS DE EVALUACI&Oacute;N</th>
                                </tr>
                                <?php
                                $rqmc1 = query("SELECT * FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='" . $rqmc2['id'] . "' AND estado='1' ");
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
                                            <a href="contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-warning active"><i class='fa fa-eye'></i> ver/descargar</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <a class="btn btn-warning active" data-toggle="modal" data-target="#MODAL-preguntas-onlinecourse"><i class="fa fa-plus"></i> AGREGAR PREGUNTA DE EVALUACI&Oacute;N</a>


                            <?php
                        }
                        ?>
                    </div>
                </div>




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
                                <input type="text" name="nombre" class="form-control" placeholder="Ingresa la pregunta..."/>
                            </td>
                        </tr>
                        <tr>
                            <td>

                                <div>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <input type="text" name="nombre" class="form-control" placeholder="Ingresa la pregunta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="nombre" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="nombre" class="form-control" placeholder="Ingresa la pregunta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="nombre" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="nombre" class="form-control" placeholder="Ingresa la pregunta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="nombre" class="form-control"/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>


                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
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
<!-- End Modal - preguntas-onlinecourse -->






<?php

function verificador_fecha($dat) {
    if ($dat == '') {
        return "0000-00-00";
    } else {
        return $dat;
    }
}
?>
