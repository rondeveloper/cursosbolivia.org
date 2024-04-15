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

    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma1_imagen = "";
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = "";
    
    /* imagen firma */
    if(is_uploaded_file(archivo('firma1_imagen'))){
        $firma1_imagen = time().archivoName('firma1_imagen');
        move_uploaded_file(archivo('firma1_imagen'), "contenido/imagenes/firmas/$firma1_imagen");
    }
    if(is_uploaded_file(archivo('firma2_imagen'))){
        $firma2_imagen = time().archivoName('firma2_imagen');
        move_uploaded_file(archivo('firma2_imagen'), "contenido/imagenes/firmas/$firma2_imagen");
    }

    $codigo_certificado = "CERT-1-$id_curso";

    /* verificacion de existencia */
    $rqve1 = query("SELECT id FROM cursos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
    if (mysql_num_rows($rqve1) == 0) {
        query("INSERT INTO cursos_certificados
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres, firma1_nombre, firma1_cargo, firma1_imagen, firma2_nombre, firma2_cargo, firma2_imagen, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$firma1_nombre','$firma1_cargo','$firma1_imagen','$firma2_nombre','$firma2_cargo','$firma2_imagen','1')");
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

    $firma1_nombre = post('firma1_nombre');
    $firma1_cargo = post('firma1_cargo');
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');

    $codigo_certificado = "CERT-2-$id_curso";

    /* verificacion de existencia */
    $rqve1 = query("SELECT id FROM cursos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
    if (mysql_num_rows($rqve1) == 1) {
        query("INSERT INTO cursos_certificados
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres, firma1_nombre, firma1_cargo, firma2_nombre, firma2_cargo, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$firma1_nombre','$firma1_cargo','$firma2_nombre','$firma2_cargo','1')");
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
    
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');
    $firma2_imagen = post('firma2_imagen_previo');
    
    /* imagen firma */
    if(is_uploaded_file(archivo('firma1_imagen'))){
        $firma1_imagen = time().archivoName('firma1_imagen');
        move_uploaded_file(archivo('firma1_imagen'), "contenido/imagenes/firmas/$firma1_imagen");
    }
    if(is_uploaded_file(archivo('firma2_imagen'))){
        $firma2_imagen = time().archivoName('firma2_imagen');
        move_uploaded_file(archivo('firma2_imagen'), "contenido/imagenes/firmas/$firma2_imagen");
    }

    $sw_solo_nombre = post('sw_solo_nombre');

    $id_cert = post('id_certificado');

    query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           firma1_nombre='$firma1_nombre',  
           firma1_cargo='$firma1_cargo',  
           firma1_imagen='$firma1_imagen',  
           firma2_nombre='$firma2_nombre',  
           firma2_cargo='$firma2_cargo', 
           firma2_imagen='$firma2_imagen', 
           sw_solo_nombre='$sw_solo_nombre' 
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
    $firma2_nombre = post('firma2_nombre');
    $firma2_cargo = post('firma2_cargo');

    $sw_solo_nombre = post('sw_solo_nombre');

    $id_cert = post('id_certificado');

    query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo', 
           cont_uno='$cont_uno', 
           cont_dos='$cont_dos', 
           cont_tres='$cont_tres', 
           firma1_nombre='$firma1_nombre', 
           firma1_cargo='$firma1_cargo', 
           firma2_nombre='$firma2_nombre', 
           firma2_cargo='$firma2_cargo',
           sw_solo_nombre='$sw_solo_nombre' 
           WHERE id='$id_cert' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
}

/* edicion de estado */
if (isset_post('actualizar-estado')) {
    if (acceso_cod('adm-cursos-estado')) {

        $estado = post('estado');
        query("UPDATE cursos SET estado='$estado' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
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
    $fecha = post('fecha');
    $lugar = post('lugar');
    $horarios = post('horarios');
    $expositor = post('expositor');
    $colaborador = post('colaborador');
    $gastos = post('gastos');
    $observaciones = post('observaciones');

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');

    $pixelcode = post_html('pixelcode');
    $short_link = post_html('short_link');

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
              horarios='$horarios',
              fecha='$fecha',
              expositor='$expositor',
              colaborador='$colaborador',
              gastos='$gastos',
              observaciones='$observaciones',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              short_link='$short_link',
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


$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);

editorTinyMCE('editor1');
$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a >Cursos</a></li>
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
                                En este curso se tienen <?php echo $cnt_participantes; ?> participantes -> <a href="cursos-participantes/<?php echo $id_curso; ?>.adm">LISTADO DE PARTICIPANTES</a>
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
                                        <input type="date" name="fecha" class="form-control" value="<?php echo $curso['fecha']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Departamento: </span>
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
                            - FIRMA 1 : <?php echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo']; ?>
                            <br/>
                            - FIRMA 2 : <?php echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo']; ?>
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
                                            <h4 class="modal-title">EDICI&Oacute;N ::: CERTIFICADO <?php echo $rqc2['codigo']; ?></h4>
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
                                                            <span class="input-group-addon"><b>FIRMA 1 Nombre:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
                                                            <input type="text" class="form-control" name="firma1_nombre" value='<?php echo $rqc2['firma1_nombre']; ?>'/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>FIRMA 1 Cargo:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
                                                            <input type="text" class="form-control" name="firma1_cargo" value='<?php echo $rqc2['firma1_cargo']; ?>'/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>FIRMA 1 Imagen:</b></span>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <input type="file" class="form-control" name="firma1_imagen" value=''/>
                                                            <input type="hidden" name="firma1_imagen_previo" value='<?php echo $rqc2['firma1_imagen']; ?>'/>
                                                        </div>
                                                        <div class="col-md-3 text-center" style="padding-top: 4px;">
                                                            <?php
                                                            $url_firma = "contenido/imagenes/firmas/".$rqc2['firma1_imagen'];
                                                            if(file_exists($url_firma)){
                                                                echo "<a href='$url_firma' target='_blank'>Imagen actual</a>";
                                                            }else{
                                                                echo "<b>Sin imagen</b>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>FIRMA 2 Nombre:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
                                                            <input type="text" class="form-control" name="firma2_nombre" value='<?php echo $rqc2['firma2_nombre']; ?>'/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>FIRMA 2 Cargo:</b></span>
                                                        </div>
                                                        <div class="col-md-9 text-left">
                                                            <input type="text" class="form-control" name="firma2_cargo" value='<?php echo $rqc2['firma2_cargo']; ?>'/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-right">
                                                            <span class="input-group-addon"><b>FIRMA 2 Imagen:</b></span>
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <input type="file" class="form-control" name="firma2_imagen" value=''/>
                                                            <input type="hidden" name="firma2_imagen_previo" value='<?php echo $rqc2['firma2_imagen']; ?>'/>
                                                        </div>
                                                        <div class="col-md-3 text-center" style="padding-top: 4px;">
                                                            <?php
                                                            $url_firma = "contenido/imagenes/firmas/".$rqc2['firma2_imagen'];
                                                            if(file_exists($url_firma)){
                                                                echo "<a href='$url_firma' target='_blank'>Imagen actual</a>";
                                                            }else{
                                                                echo "<b>Sin imagen</b>";
                                                            }
                                                            ?>
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
                                                            <input type="radio" name="sw_solo_nombre" value="1" <?php echo $checked_dos; ?> disabled/>
                                                            Solo Nombre-Fecha
                                                            <br/>
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
                                - FIRMA 1 : <?php echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo']; ?>
                                <br/>
                                - FIRMA 2 : <?php echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo']; ?>
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
                                                <p>
                                                    Certificado: <?php echo $rqc2['codigo']; ?>
                                                </p>
                                                <hr/>
                                                <form action='' method='post'>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>TITULO:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>FIRMA 1 Nombre:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="firma1_nombre" value='<?php echo $rqc2['firma1_nombre']; ?>'/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>FIRMA 1 Cargo:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="firma1_cargo" value='<?php echo $rqc2['firma1_cargo']; ?>'/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>FIRMA 2 Nombre:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="firma2_nombre" value='<?php echo $rqc2['firma2_nombre']; ?>'/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <span class="input-group-addon"><b>FIRMA 2 Cargo:</b></span>
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <input type="text" class="form-control" name="firma2_cargo" value='<?php echo $rqc2['firma2_cargo']; ?>'/>
                                                            </div>
                                                        </div>
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

                                                    </div>
                                                    <br/>
                                                    <div class="text-center">
                                                        <input type='hidden' name='id_certificado' value='<?php echo $curso['id_certificado_2']; ?>'/>
                                                        <input type='submit' name='editar-certificado-2' class="btn btn-success" value="ACTUALIZAR CERTIFICADO"/>
                                                    </div>
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
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscriptos.
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
                                <span class="input-group-addon"><b>CONT. 3:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2017"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2017</textarea>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 Nombre:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma1_nombre" value='Ing. Edgar Aliaga Chipana'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 Cargo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma1_cargo" value='Gerente Ejecutivo'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 Imagen:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="file" class="form-control" name="firma1_imagen" value=''/>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 Nombre:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma2_nombre" value='Lic. Alain Estevez Sandi'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 Cargo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma2_cargo" value='Facilitador'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 Imagen:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="file" class="form-control" name="firma2_imagen" value=''/>
                            </div>
                        </div>

                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado' class="btn btn-success" value="HABILITAR CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>

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
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscriptos.
                </p>
                <hr/>
                <form action='' method='post'>
                    <div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 3:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <?php
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2017"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 Nombre:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma1_nombre" value='Ing. Edgar Aliaga Chipana'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 1 Cargo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma1_cargo" value='Gerente Ejecutivo'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 Nombre:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma2_nombre" value='Lic. Alain Estevez Sandi'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>FIRMA 2 Cargo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma2_cargo" value='Facilitador'/>
                            </div>
                        </div>

                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado-2' class="btn btn-success" value="HABILITAR 2do CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>

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
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera asignara un nuevo certificado secundario para los participantes inscriptos.
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
                                <input type="text" name="cont_dos" value='<?php echo utf8_encode("Con una carga horaria de 10 horas llevado a cabo en un CICLO DE CURSOS DE ACTUALIZACIÓN PEDAGÓGICA, del 04 al 09 del mes de Diciembre del año 2017.") ?>' class="form-control" id="date" required="">
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



