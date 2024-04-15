<?php
/* id curso */
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

    $texto_qr = post('texto_qr');
    $fecha_qr = post('fecha_qr');

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
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres,texto_qr,fecha_qr,sw_solo_nombre,formato,id_firma1, firma1_nombre, firma1_cargo, firma1_imagen, id_firma2, firma2_nombre, firma2_cargo, firma2_imagen, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$texto_qr','$fecha_qr','$sw_solo_nombre','$formato','$id_firma1','$firma1_nombre','$firma1_cargo','$firma1_imagen','$id_firma2','$firma2_nombre','$firma2_cargo','$firma2_imagen','1')");
        $rqirc1 = query("SELECT id FROM cursos_certificados WHERE codigo='$codigo_certificado' ORDER BY id DESC limit 1 ");
        $rqirc2 = mysql_fetch_array($rqirc1);
        $id_certificado = $rqirc2['id'];
        query("UPDATE cursos SET id_certificado='$id_certificado',sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        
        logcursos('Asignacion de 1er certificado de curso', 'certificado-curso-asignacion', 'certificado-curso', $id_certificado);
        logcursos('Asignacion de 1er certificado de curso', 'curso-edicion', 'curso', $id_curso);
        
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

    $texto_qr = post('texto_qr');
    $fecha_qr = post('fecha_qr');

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
           (id_curso, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres,texto_qr,fecha_qr,sw_solo_nombre,formato,id_firma1, firma1_nombre, firma1_cargo, firma1_imagen, id_firma2, firma2_nombre, firma2_cargo, firma2_imagen, estado) 
            VALUES 
           ('$id_curso','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$texto_qr','$fecha_qr','$sw_solo_nombre','$formato','$id_firma1','$firma1_nombre','$firma1_cargo','$firma1_imagen','$id_firma2','$firma2_nombre','$firma2_cargo','$firma2_imagen','1')");
        $rqirc1 = query("SELECT id FROM cursos_certificados WHERE codigo='$codigo_certificado' ORDER BY id DESC limit 1 ");
        $rqirc2 = mysql_fetch_array($rqirc1);
        $id_certificado = $rqirc2['id'];
        query("UPDATE cursos SET id_certificado_2='$id_certificado',sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        
        logcursos('Asignacion de 2do certificado de curso', 'certificado-curso-asignacion', 'certificado-curso', $id_certificado);
        logcursos('Asignacion de 2do certificado de curso', 'curso-edicion', 'curso', $id_curso);
        
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

    $texto_qr = post('texto_qr');
    $fecha_qr = post('fecha_qr');

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
           texto_qr='$texto_qr', 
           fecha_qr='$fecha_qr', 
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
    
    logcursos('Edicion de 1er certificado de curso', 'certificado-curso-edicion', 'certificado-curso', $id_cert);
    logcursos('Edicion de 1er certificado de curso', 'curso-edicion', 'curso', $id_curso);

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

    $texto_qr = post('texto_qr');
    $fecha_qr = post('fecha_qr');

    $sw_solo_nombre = post('sw_solo_nombre');
    $formato = post('formato');

    $id_cert = post('id_certificado');

    query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           texto_qr='$texto_qr', 
           fecha_qr='$fecha_qr', 
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
    
    logcursos('Edicion de 2do certificado de curso', 'certificado-curso-edicion', 'certificado-curso', $id_cert);
    logcursos('Edicion de 2do certificado de curso', 'curso-edicion', 'curso', $id_curso);

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
}


/* edicion de curso */
if (isset_post('formulario')) {

    $id = post('id');
    $contenido = post_html('formulario');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $id_organizador = post('id_organizador');
    $flag_publicacion = post('flag_publicacion');
    $sw_siguiente_semana = post('sw_siguiente_semana');
    $sw_suspendido = post('sw_suspendido');
    $columna = post('columna');
    $incrustacion = post_html('incrustacion');

    $id_ciudad = post('id_ciudad');
    $fecha = verificador_fecha(post('fecha'));
    $costo = post('costo');
    $fecha2 = verificador_fecha(post('fecha2')).' '.verificador_hora(post('fecha2_hour'));
    $costo2 = post('costo2');
    $fecha3 = verificador_fecha(post('fecha3')).' '.verificador_hora(post('fecha3_hour'));
    $costo3 = post('costo3');
    $fecha_e = verificador_fecha(post('fecha_e'));
    $costo_e = post('costo_e');
    $fecha_e2 = verificador_fecha(post('fecha_e2')).' '.verificador_hora(post('fecha_e2_hour'));
    $costo_e2 = post('costo_e2');
    $id_lugar = post('id_lugar');
    $horarios = post('horarios');
    $id_docente = post('id_docente');
    $colaborador = post('colaborador');
    $whats_numero = post('whats_numero');
    $whats_mensaje = post('whats_mensaje');
    $gastos = post('gastos');
    $texto_qr = post('texto_qr');
    $observaciones = post('observaciones');

    $id_categoria = post('id_categoria');
    $id_categoria_anterior = post('id_categoria_anterior');

    $sw_v_expositor = '0';
    if (isset_post('sw_v_expositor')) {
        $sw_v_expositor = '1';
    }

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

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');
    $imagen_gif = post('preimagengif');

    $archivo1 = post('prearchivo1');
    $archivo2 = post('prearchivo2');
    $archivo3 = post('prearchivo3');
    $archivo4 = post('prearchivo4');
    $archivo5 = post('prearchivo5');

    $pixelcode = post_html('pixelcode');
    $tagmgr_body = post_html('tagmgr_body');
    $short_link = post_html('short_link');

    $inicio_numeracion = post('inicio_numeracion');

    $rqinp1 = query("SELECT inicio_numeracion FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqinp2 = mysql_fetch_array($rqinp1);
    $inicio_numeracion_previo = $rqinp2['inicio_numeracion'];
    if ((int) $inicio_numeracion !== (int) $inicio_numeracion_previo) {
        /* actualizar numeracion */
        $aux_numeracion = $inicio_numeracion;
        $rqan1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ORDER BY id ASC ");
        while ($rqan2 = mysql_fetch_array($rqan1)) {
            $id_part_temp = $rqan2['id'];
            query("UPDATE cursos_participantes SET numeracion='$aux_numeracion' WHERE id='$id_part_temp' ORDER BY id DESC limit 1 ");
            $aux_numeracion++;
        }
    }

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
    if (isset_archivo('imagen_gif')) {
        $imagen_gif = time() . archivoName('imagen_gif');
        move_uploaded_file(archivo('imagen_gif'), "contenido/imagenes/paginas/$imagen_gif");
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


    query("UPDATE cursos SET 
              contenido='$contenido',
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              flag_publicacion='$flag_publicacion',
              sw_siguiente_semana='$sw_siguiente_semana',
              sw_suspendido='$sw_suspendido',
              columna='$columna',
              id_organizador='$id_organizador',
              incrustacion='$incrustacion',
              id_ciudad='$id_ciudad',
              id_categoria='$id_categoria',
              id_lugar='$id_lugar',
              id_docente='$id_docente',
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
              fecha_e2='$fecha_e2',
              costo_e2='$costo_e2',
              sw_fecha_e2='$sw_fecha_e2',
              sw_v_expositor='$sw_v_expositor',
              colaborador='$colaborador',
              whats_numero='$whats_numero',
              whats_mensaje='$whats_mensaje',
              gastos='$gastos',
              observaciones='$observaciones',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              imagen_gif='$imagen_gif',
              archivo1='$archivo1',
              archivo2='$archivo2',
              archivo3='$archivo3',
              archivo4='$archivo4',
              archivo5='$archivo5',
              short_link='$short_link',
              inicio_numeracion='$inicio_numeracion',
              pixelcode='$pixelcode', 
              tagmgr_body='$tagmgr_body', 
              texto_qr='$texto_qr',
              sw_cierre='0' 
               WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    if ($id_categoria !== $id_categoria_anterior) {
        $rqdccc1 = query("SELECT cnt_cursos_total FROM cursos_categorias WHERE id='$id_categoria_anterior' ORDER BY id DESC limit 1 ");
        $rqdccc2 = mysql_fetch_array($rqdccc1);
        $cnt_cursos_total = abs((int) $rqdccc2['cnt_cursos_total'] - 1);
        query("UPDATE cursos_categorias SET cnt_cursos_total='$cnt_cursos_total' WHERE id='$id_categoria_anterior' ORDER BY id DESC limit 1 ");

        $rqdcccc1 = query("SELECT cnt_cursos_total FROM cursos_categorias WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");
        $rqdcccc2 = mysql_fetch_array($rqdcccc1);
        $cnt_cursos_total_n = abs((int) $rqdcccc2['cnt_cursos_total'] + 1);
        query("UPDATE cursos_categorias SET cnt_cursos_total='$cnt_cursos_total_n' WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");
    }

    //movimiento('Edicion de datos de curso', 'edicion-curso', 'curso', $id_curso);
    logcursos('Edicion de datos de curso', 'curso-edicion', 'curso', $id_curso);

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
    $id_modelo_certificado = mysql_insert_id();

    $codd = "CS" . str_pad($id_modelo_certificado, 3, "0", STR_PAD_LEFT);
    query("UPDATE cursos_modelos_certificados SET codigo='$codd' WHERE id='$id_modelo_certificado' ");
    
    logcursos('Asignacion de modelo de certificado', 'modelo-certificado-asignacion', 'modelo-certificado', $id_modelo_certificado);
    logcursos('Asignacion de modelo de certificado', 'curso-edicion', 'curso', $id_curso);

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
    
    logcursos('Edicion de modelo de certificado', 'modelo-certificado-edicion', 'modelo-certificado', $id_modelo_certificado);
    logcursos('Edicion de modelo de certificado', 'curso-edicion', 'curso', $id_curso);

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
    
    logcursos('Creacion de curso', 'turnocurso-creacion', 'turno-curso', $id_turno);

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
    
    logcursos('Edicion de turno', 'turnocurso-edicion', 'turno-curso', $id_turno);
    logcursos('Edicion de curso', 'curso-edicion', 'curso', $id_curso);

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
        query("INSERT INTO cursos_onlinecourse(id_curso, descripcion, urltag, estado) VALUES ('$id_curso','$descripcion','$urltag','1')");
        logcursos('Habilitacion de CURSO ONLINE', 'curso-edicion', 'curso', $id_curso);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> El curso en linea se habilito correctamente.
    </div>';
    }
}

/* desactivar-curso-online */
if (isset_post('desactivar-curso-online')) {
    $id_curso_online = post('id_curso_online');
    query("UPDATE cursos_onlinecourse SET estado='0' WHERE id='$id_curso_online' ");
    logcursos('DES-ACTIVACION de curso online', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('DES-ACTIVACION de curso online', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* activar-curso-online */
if (isset_post('activar-curso-online')) {
    $id_curso_online = post('id_curso_online');
    query("UPDATE cursos_onlinecourse SET estado='1' WHERE id='$id_curso_online' ");
    logcursos('ACTIVACION de curso online', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('ACTIVACION de curso online', 'curso-edicion', 'curso', $id_curso);
    
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* asignar-docente-curso-online */
if (isset_post('asignar-docente-curso-online')) {
    $id_curso_online = post('id_curso_online');
    $id_docente = post('id_docente');
    query("UPDATE cursos_onlinecourse SET id_docente='$id_docente' WHERE id='$id_curso_online' ");
    logcursos('Asignacion de docente a curso online', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('Asignacion de docente a curso online', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* editar-titulo-curso-online */
if (isset_post('editar-titulo-curso-online')) {
    $id_curso_online = post('id_curso_online');
    $titulo_curso_online = post('titulo_curso_online');
    query("UPDATE cursos_onlinecourse SET titulo='$titulo_curso_online',descripcion='$titulo_curso_online' WHERE id='$id_curso_online' ");
    logcursos('Edicion de datos curso online', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('Edicion de datos curso online', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* editar-imagen-curso-online */
if (isset_post('editar-imagen-curso-online')) {
    $id_curso_online = post('id_curso_online');
    $imagen = post('imagen_actual');
    if (isset_archivo('imagen')) {
        $imagen = 'CO' . $id_curso_online . '-' . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), "contenido/imagenes/cursos/$imagen");
    }
    query("UPDATE cursos_onlinecourse SET imagen='$imagen' WHERE id='$id_curso_online' ");
    logcursos('Actualizacion de imagen', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('Actualizacion de imagen', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
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

        logcursos('Agregado de material [curso online]', 'curso-edicion', 'curso', $id_curso);
        
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
    }
}


/* agregar-pregunta-onlinecourse */
if (isset_post('agregar-pregunta-onlinecourse')) {
    $pregunta = post('pregunta');

    $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
    $rqmc2 = mysql_fetch_array($rqmc1);
    $id_onlinecourse = $rqmc2['id'];

    query("INSERT INTO cursos_onlinecourse_preguntas(id_onlinecourse, pregunta, estado) VALUES ('$id_onlinecourse','$pregunta','1')");
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
    logcursos('Agregado de preguntas [curso online]', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
}

/* agregar cupon-descuento */
if (isset_post('asignar-cupon-descuento')) {
    $detalle_cupon = post('detalle_cupon');
    $descuento_cupon = post('descuento_cupon');
    $expiracion_cupon = post('expiracion_cupon');
    $fecha_registro = date("Y-m-d H:i");
    $id_administrador = administrador('id');
    if (acceso_cod('adm-crea-cuponcurso')) {
        query("INSERT INTO cursos_cupones (
               id_curso, 
               id_administrador, 
               detalle_cupon, 
               porcentaje_descuento, 
               fecha_expiracion, 
               fecha_registro, 
               estado
               ) VALUES (
               '$id_curso',
               '$id_administrador',
               '$detalle_cupon',
               '$descuento_cupon',
               '$expiracion_cupon',
               '$fecha_registro',
               '1'
               )");
        $id_cupon = mysql_insert_id();
        logcursos('Asignacion de cupon descuento curso[C' . $id_curso . ']', 'curso-edicion', 'curso', $id_curso);
        logcursos('Asignacion de cupon descuento curso[C' . $id_curso . ']', 'cupon-asignacion', 'cupon', $id_cupon);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se completo correctamente.
    </div>';
    }
}

/* actualizar-cupon-descuento */
if (isset_post('actualizar-cupon-descuento')) {
    $id_cupon = post('id_cupon');
    $detalle_cupon = post('detalle_cupon');
    $descuento_cupon = post('descuento_cupon');
    $expiracion_cupon = post('expiracion_cupon');
    $id_administrador = administrador('id');
    if (acceso_cod('adm-crea-cuponcurso')) {
        query("UPDATE cursos_cupones SET 
               detalle_cupon='$detalle_cupon', 
               porcentaje_descuento='$descuento_cupon', 
               fecha_expiracion='$expiracion_cupon'  
                WHERE id='$id_cupon' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de cupon descuento curso', 'cupon-edicion', 'cupon', $id_cupon);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
    }
}

/* registros */
$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);

/* departamento */
$curso_id_ciudad = $curso['id_ciudad'];
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$curso_id_ciudad' LIMIT 1 ");
$rqdd2 = mysql_fetch_array($rqdd1);
$curso_id_departamento = $rqdd2['id_departamento'];

/* url_corta */
$url_corta = 'https://cursos.bo/' . abs((int) $curso['id'] - 1000) . '/';

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
        <h3 class="page-header">
            <i class='btn btn-success active hidden-sm'><?php echo my_fecha_at1($curso['fecha']); ?></i> | 
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
                if (acceso_cod('adm-cursos-estado') || isset_organizador()) {
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
                                        <div class="col-md-2">
                                            <div>
                                                <input type="button" value="TERMPORALIZAR" class="btn btn-danger btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'temporal');"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div>
                                                <input type="button" value="DESACTIVAR" class="btn btn-default btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'desactivado');"/>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } elseif ($curso['estado'] == '2') {
                                    ?>
                                    <div class="col-md-5">
                                        <span class="input-group-addon"><b style='color:red;'>TEMPORAL</b></span>
                                    </div>
                                    <?php
                                    if (acceso_cod('adm-cursos-estado')) {
                                        ?>
                                        <div class="col-md-2">
                                            <div>
                                                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'activado');"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
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
                                        <div class="col-md-2">
                                            <div>
                                                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'activado');"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div>
                                                <input type="button" value="TERMPORALIZAR" class="btn btn-danger btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $curso['id']; ?>', 'temporal');"/>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            $rqcp1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ");
                            $rqcp2 = mysql_fetch_array($rqcp1);
                            $cnt_participantes = $rqcp2['total'];
                            ?>
                            En este curso se registraron <?php echo $cnt_participantes; ?> participantes -> <a href="cursos-participantes/<?php echo $id_curso; ?>.adm"> <i class="fa fa-group"></i> LISTADO DE PARTICIPANTES</a>
                            &nbsp;|&nbsp;
                            <a href="<?php echo $curso['titulo_identificador']; ?>.html" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR EL CURSO</a>
                            &nbsp;|&nbsp;
                            <a href="cursos-listar.adm"> <i class="fa fa-list"></i> LISTADO DE CURSOS</a>
                            &nbsp;|&nbsp;
                            <i class="fa fa-link"></i>
                            &nbsp;&nbsp;
                            <a href="<?php echo $url_corta; ?>" target="_blank"><?php echo $url_corta; ?></a>

                        </div>
                    </div>
                    <?php
                }
                ?>


                <div class="panel panel-primary">
                    <div class="panel-heading">DATOS DEL CURSO - <?php echo $curso['titulo']; ?></div>
                    <form enctype="multipart/form-data" action="" method="post">
                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">DATOS 1</a></li>
                                <li><a data-toggle="tab" href="#menu1">DATOS 2</a></li>
                                <li><a data-toggle="tab" href="#menu2">DATOS 3</a></li>
                                <li><a data-toggle="tab" href="#menu3">DATOS 4</a></li>
                                <li><a data-toggle="tab" href="#menu4">TAGS</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del Curso: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="titulo" value="<?php echo $curso['titulo']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-cascade-control" name="id_categoria">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                            $selected = '';
                                                            if ($curso['id_categoria'] == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['titulo']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="id_categoria_anterior" value="<?php echo $curso['id_categoria']; ?>"/>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Organizador: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-cascade-control" name="id_organizador">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_organizadores WHERE estado='1' OR id='" . $curso['id_organizador'] . "' ");
                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                            $selected = '';
                                                            if ($curso['id_organizador'] == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['titulo']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Texto QR: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="texto_qr" value="<?php //echo $curso['texto_qr']; ?>" class="form-control"/>
                                                </div>
                                            </td>
                                        </tr>-->
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Publicado en: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['flag_publicacion'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="flag_publicacion" <?php echo $check; ?> value="1" id="flag_publicacion1"/> <label for="flag_publicacion1">Infosicoes/Cursos.BO</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['flag_publicacion'] == '2') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="flag_publicacion" <?php echo $check2; ?> value="2" id="flag_publicacion2"/> <label for="flag_publicacion2"> Infosicoes</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['flag_publicacion'] == '3') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="flag_publicacion" <?php echo $check2; ?> value="3" id="flag_publicacion3"/> <label for="flag_publicacion3"> Cursos.BO</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['flag_publicacion'] == '4') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="flag_publicacion" <?php echo $check2; ?> value="4" id="flag_publicacion4"/> <label for="flag_publicacion4"> Ninguno</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Semana del curso: </span>
                                                </div>
                                                <div class="col-md-9">
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
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Columna: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['columna'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="columna" <?php echo $check; ?> value="1" id="columna1"/> <label for="columna1"> 1ra columna</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['columna'] == '2') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="columna" <?php echo $check2; ?> value="2" id="columna2"/> <label for="columna2"> 2da columna</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['columna'] == '3') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="columna" <?php echo $check2; ?> value="3" id="columna3"/> <label for="columna3"> 3ra columna</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check3 = '';
                                                        if ($curso['columna'] == '0') {
                                                            $check3 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <input type="radio" name="columna" <?php echo $check3; ?> value="0" id="columna4"/> <label for="columna4"> No visible en Inicio</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Horarios: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="horarios" value="<?php echo $curso['horarios']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del curso: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha" class="form-control" value="<?php echo $curso['fecha']; ?>">
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo" value="<?php echo $curso['costo']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="checkbox" name="" class="" value="1" checked="" disabled=""> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 1: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha2" class="form-control" value="<?php echo date("Y-m-d",strtotime($curso['fecha2'])); ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha2_hour" class="form-control" value="<?php echo date("H:i",strtotime($curso['fecha2'])); ?>">
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo2" value="<?php echo $curso['costo2']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha2'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha2" class="" <?php echo $check_f; ?> value="1"> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 2: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha3" class="form-control" value="<?php echo date("Y-m-d",strtotime($curso['fecha3'])); ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha3_hour" class="form-control" value="<?php echo date("H:i",strtotime($curso['fecha3'])); ?>">
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo3" value="<?php echo $curso['costo3']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha3'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha3" class="" <?php echo $check_f; ?> value="1"> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha estudiantes: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha_e" class="form-control" value="<?php echo $curso['fecha_e']; ?>">
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo_e" value="<?php echo $curso['costo_e']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha_e'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha_e" class="" <?php echo $check_f; ?> value="1"> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 3: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha_e2" class="form-control" value="<?php echo date("Y-m-d",strtotime($curso['fecha_e2'])); ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha_e2_hour" class="form-control" value="<?php echo date("H:i",strtotime($curso['fecha_e2'])); ?>">
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo_e2" value="<?php echo $curso['costo_e2']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha_e2'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha_e2" class="" <?php echo $check_f; ?> value="1"> Habilitado
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
                                                        $curso_departamento = 'no-data';
                                                        $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                            $selected = '';
                                                            if ($curso_id_departamento == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                                $curso_departamento = $rqd2['nombre'];
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
                                                            $curso_ciudad_nombre = 'no-data';
                                                            $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$curso_id_departamento' ORDER BY nombre ASC ");
                                                            while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                                $selected = '';
                                                                if ($curso['id_ciudad'] == $rqd2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                    $curso_ciudad_nombre = $rqd2['nombre'];
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
                                                    <input type="hidden" id="current_id_ciudad" value="<?php echo $curso_id_ciudad; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i> &nbsp; Lugar: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group input-group">
                                                        <select class="form-control" name="id_lugar" id="select_lugar">
                                                            <?php
                                                            $txt_lugar_curso = 'no-data';
                                                            $txt_lugar_salon_curso = 'no-data';
                                                            $txt_direccion_lugar_curso = 'no-data';
                                                            $rqdl1 = query("SELECT id,nombre,salon,direccion FROM cursos_lugares WHERE id_ciudad='$curso_id_ciudad' ORDER BY nombre ASC ");
                                                            while ($rqdl2 = mysql_fetch_array($rqdl1)) {
                                                                $selected = '';
                                                                if ($curso['id_lugar'] == $rqdl2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                    $txt_lugar_curso = $rqdl2['nombre'];
                                                                    $txt_lugar_salon_curso = $rqdl2['nombre'] . ' - ' . $rqdl2['salon'];
                                                                    $txt_direccion_lugar_curso = $rqdl2['direccion'];
                                                                }
                                                                echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . $rqdl2['nombre'] . ' - ' . $rqdl2['salon'] . ' - ' . $curso_departamento . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="input-group-btn">
                                                            <b class="btn btn-default" onclick="actualiza_lugares();">
                                                                <i class="fa fa-refresh"></i>
                                                            </b>
                                                        </span>
                                                    </div>
                                                    <input type="hidden" id="current_id_lugar" value="<?php echo $curso['id_lugar']; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Docente: </span>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group input-group">
                                                        <select class="form-control" name="id_docente" id="select_docente">
                                                            <option value="0">Sin docente</option>
                                                            <?php
                                                            $rqdl1 = query("SELECT id,prefijo,nombres FROM cursos_docentes WHERE estado='1' ORDER BY id ASC ");
                                                            while ($rqdl2 = mysql_fetch_array($rqdl1)) {
                                                                $selected = '';
                                                                if ($curso['id_docente'] == $rqdl2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                }
                                                                echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . trim($rqdl2['prefijo'] . ' ' . $rqdl2['nombres']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="input-group-btn">
                                                            <b class="btn btn-default" onclick="actualiza_docentes();">
                                                                <i class="fa fa-refresh"></i>
                                                            </b>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php
                                                    $txt_checkbox = '';
                                                    if ($curso['sw_v_expositor'] == '1') {
                                                        $txt_checkbox = ' checked ';
                                                    }
                                                    ?>
                                                    <label for="sw_v_expositor"><input type="checkbox" name="sw_v_expositor" id="sw_v_expositor" value="1" style="width:17px;height:17px;" <?php echo $txt_checkbox; ?> /> <b style="font-size: 14pt;">VISIBLE EN CURSO</b></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Colaborador: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="colaborador" value="<?php echo $curso['colaborador']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Whatsapp: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="whats_numero" value="<?php echo $curso['whats_numero']; ?>" class="form-control" id="date" placeholder="Numero..."/>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="whats_mensaje" value="<?php echo $curso['whats_mensaje']; ?>" class="form-control" id="date" placeholder="Mensaje..."/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Gastos: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="gastos" value="<?php echo $curso['gastos']; ?>" class="form-control" id="date">
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Observaciones: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="observaciones" value="<?php echo $curso['observaciones']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Inicio numeraci&oacute;n: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="inicio_numeracion" value="<?php echo $curso['inicio_numeracion']; ?>" class="form-control" id="date">
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; URL corta: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="short_link" value="<?php echo $curso['short_link']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Suspendido: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['sw_suspendido'] == '0') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_suspendido" <?php echo $check; ?> value="0"/> No suspendido</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['sw_suspendido'] == '1') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_suspendido" <?php echo $check2; ?> value="1"/> SUSPENDIDO</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        
<!--                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Tag Manager HEAD: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <textarea name="pixelcode" class="form-control" ><?php echo $curso['pixelcode']; ?></textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Tag Manager BODY: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <textarea name="tagmgr_body" class="form-control" ><?php echo $curso['tagmgr_body']; ?></textarea>
                                                </div>
                                            </td>
                                        </tr>-->
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
                                                        $url_img = 'https://cursos.bo/paginas/.size=2.img';
                                                        if ($curso['imagen_gif']!=='') {
                                                            $url_img = $dominio . "contenido/imagenes/paginas/" . $curso['imagen_gif'];
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
                                                        $url_img = "contenido/imagenes/paginas/" . $curso['imagen'];
                                                        if (file_exists($url_img)) {
                                                            $url_img = "paginas/" . $curso['imagen'] . ".size=2.img";
                                                        } else {
                                                            $url_img = "https://www.infosicoes.com/" . "paginas/" . $curso['imagen'] . ".size=2.img";
                                                        }
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
                                                        $url_img = "contenido/imagenes/paginas/" . $curso['imagen2'];
                                                        if (file_exists($url_img)) {
                                                            $url_img = "paginas/" . $curso['imagen2'] . ".size=2.img";
                                                        } else {
                                                            $url_img = "https://www.infosicoes.com/" . "paginas/" . $curso['imagen2'] . ".size=2.img";
                                                        }
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
                                                        $url_img = "contenido/imagenes/paginas/" . $curso['imagen3'];
                                                        if (file_exists($url_img)) {
                                                            $url_img = "paginas/" . $curso['imagen3'] . ".size=2.img";
                                                        } else {
                                                            $url_img = "https://www.infosicoes.com/" . "paginas/" . $curso['imagen3'] . ".size=2.img";
                                                        }
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
                                                        $url_img = "contenido/imagenes/paginas/" . $curso['imagen4'];
                                                        if (file_exists($url_img)) {
                                                            $url_img = "paginas/" . $curso['imagen4'] . ".size=2.img";
                                                        } else {
                                                            $url_img = "https://www.infosicoes.com/" . "paginas/" . $curso['imagen4'] . ".size=2.img";
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
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; Archivo descargable <b>1</b>: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <input type="file" name="archivo1" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        if ($curso['archivo1'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists("contenido/archivos/cursos/" . $curso['archivo1'])) {
                                                                $url_arch = $dominio . "contenido/archivos/cursos/" . $curso['archivo1'];
                                                            } else {
                                                                $url_arch = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo1'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $curso['archivo1'] . "</a>";
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
                                                        if ($curso['archivo2'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists("contenido/archivos/cursos/" . $curso['archivo2'])) {
                                                                $url_arch = $dominio . "contenido/archivos/cursos/" . $curso['archivo2'];
                                                            } else {
                                                                $url_arch = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo2'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $curso['archivo2'] . "</a>";
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
                                                        if ($curso['archivo3'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists("contenido/archivos/cursos/" . $curso['archivo3'])) {
                                                                $url_arch = $dominio . "contenido/archivos/cursos/" . $curso['archivo3'];
                                                            } else {
                                                                $url_arch = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo3'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $curso['archivo3'] . "</a>";
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
                                                        if ($curso['archivo4'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists("contenido/archivos/cursos/" . $curso['archivo4'])) {
                                                                $url_arch = $dominio . "contenido/archivos/cursos/" . $curso['archivo4'];
                                                            } else {
                                                                $url_arch = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo4'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $curso['archivo4'] . "</a>";
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
                                                        if ($curso['archivo5'] == '') {
                                                            echo "<b>SIN ARCHIVO</b>";
                                                        } else {
                                                            if (file_exists("contenido/archivos/cursos/" . $curso['archivo5'])) {
                                                                $url_arch = $dominio . "contenido/archivos/cursos/" . $curso['archivo5'];
                                                            } else {
                                                                $url_arch = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo5'];
                                                            }
                                                            echo "<a href='$url_arch' target='_blank'>" . $curso['archivo5'] . "</a>";
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
                                                                                        <td>[NOMBRE-CURSO]</td>
                                                                                        <td><?php echo $curso['titulo']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[CIUDAD-CURSO]</td>
                                                                                        <td><?php echo $curso_departamento; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[FECHA-A1-CURSO]</td>
                                                                                        <td><?php echo fecha_curso_D_d_m($curso['fecha']); ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[HORARIOS]</td>
                                                                                        <td><?php echo $curso['horarios']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[LUGAR-CURSO]</td>
                                                                                        <td><?php echo $txt_lugar_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[LUGAR-SALON-CURSO]</td>
                                                                                        <td><?php echo $txt_lugar_salon_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[DIRECCION-LUGAR]</td>
                                                                                        <td><?php echo $txt_direccion_lugar_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[COSTO-BS]</td>
                                                                                        <td><?php echo $curso['costo'] . ' Bs'; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[COSTO-LITERAL]</td>
                                                                                        <td><?php echo numToLiteral($curso['costo']); ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $txt_descuento_uno_curso = '';
                                                                                    $txt_descuento_dos_curso = '';
                                                                                    $txt_descuento_est_curso = '';
                                                                                    $txt_descuento_est_pre_curso = '';
                                                                                    if ($curso['sw_fecha2'] == '1') {
                                                                                        $txt_descuento_uno_curso = $curso['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha2']).hora_descuento($curso['fecha2']);
                                                                                    }
                                                                                    if ($curso['sw_fecha3'] == '1') {
                                                                                        $txt_descuento_dos_curso = $curso['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha3']).hora_descuento($curso['fecha3']);
                                                                                    }
                                                                                    if ($curso['sw_fecha_e'] == '1') {
                                                                                        $txt_descuento_est_curso = $curso['costo_e'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e']) . ' (Estudiantes)';
                                                                                    }
                                                                                    if ($curso['sw_fecha_e2'] == '1') {
                                                                                        $txt_descuento_est_pre_curso = $curso['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e2']).hora_descuento($curso['fecha_e2']). ' (Estudiantes)';
                                                                                    }
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>[DESCUENTO-UNO]</td>
                                                                                        <td><?php echo $txt_descuento_uno_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[DESCUENTO-DOS]</td>
                                                                                        <td><?php echo $txt_descuento_dos_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[DESCUENTO-ESTUDIANTES]</td>
                                                                                        <td><?php echo $txt_descuento_est_curso; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[DESCUENTO-ESTUDIANTES-PRE]</td>
                                                                                        <td><?php echo $txt_descuento_est_pre_curso; ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $rqdrd1 = query("SELECT prefijo,nombres,curriculum FROM cursos_docentes WHERE id='" . $curso['id_docente'] . "' ORDER BY id ASC ");
                                                                                    $rqdrd2 = mysql_fetch_array($rqdrd1);
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>[DOCENTE-NOMBRE]</td>
                                                                                        <td><?php echo trim($rqdrd2['prefijo'] . ' ' . $rqdrd2['nombres']); ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[DOCENTE-CURRICULUM]</td>
                                                                                        <td><?php echo $rqdrd2['curriculum']; ?></td>
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
                                                                                &nbsp;&nbsp; 
                                                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:#7a54da;">[REPORTE-SU-PAGO]</span>
                                                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:#428bca;">[INSCRIPCION]</span>   
                                                                                &nbsp;,&nbsp; <span style="font-size:11pt;color:green;">[WHATSAPP]</span>  &nbsp;,&nbsp;  
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
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                                                                                <h4 class="panel-title">
                                                                                    Datos de Organizador
                                                                                </h4>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse5" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                <table class="table table-striped table-bordered table-hover">
                                                                                    <?php
                                                                                    $rqd1 = query("SELECT titulo,nit,telefono FROM cursos_organizadores WHERE estado='1' OR id='" . $curso['id_organizador'] . "' ");
                                                                                    $rqd2 = mysql_fetch_array($rqd1);
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>[NOMBRE-ORGANIZADOR]</td>
                                                                                        <td><?php echo $rqd2['titulo']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[NIT-ORGANIZADOR]</td>
                                                                                        <td><?php echo $rqd2['nit']; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>[TELEFONO-ORGANIZADOR]</td>
                                                                                        <td><?php echo $rqd2['telefono']; ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $rqdpc1 = query("SELECT titulo FROM cursos_organizadores_cont_pr WHERE id_organizador='" . $curso['id_organizador'] . "' ORDER BY id ASC ");
                                                                                    while ($rqdpc2 = mysql_fetch_array($rqdpc1)) {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $rqdpc2['titulo']; ?></td>
                                                                                            <td><?php echo $rqdpc2['contenido']; ?></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </table>
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
                                                <textarea name="formulario" id="editor1" style="width:100% !important;margin:auto;height:700px;" rows="25"><?php echo $curso['contenido']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Codigo de incrustaci&oacute;n</b>
                                                <br/>
                                                <textarea name="incrustacion" class="form-control" style="width:100%;margin:auto;" rows="2"><?php echo $curso['incrustacion']; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="menu4" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="row" style="min-height:350px;">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6" style="background:#FFF;">
                                                        <hr/>
                                                        <b>ETIQUETAS ASOCIADAS AL CURSO</b>
                                                        <hr/>
                                                        <table class="table table-striped table-hover table-bordered table-responsive">
                                                            <tr>
                                                                <td colspan="2">
                                                                    <input type="text" class="form-control" placeholder="Agregar etiqueta" onkeyup="busca_etiquetas(this.value);"/>
                                                                    <div id="AJAXBOX-busca_etiquetas"></div>
                                                                </td>
                                                            </tr>
                                                            <tbody id="AJAXBOX-asocia_etiqueta">
                                                                <?php
                                                                $rqdcct1 = query("SELECT t.id,t.tag FROM cursos_rel_cursostags rt,cursos_tags t WHERE rt.id_tag=t.id AND rt.id_curso='$id_curso' ");
                                                                while ($rqdcct2 = mysql_fetch_array($rqdcct1)) {
                                                                    $id_tag = $rqdcct2['id'];
                                                                    $tag = $rqdcct2['tag'];
                                                                    ?>
                                                                    <tr id="tr-tag-<?php echo $id_tag; ?>">
                                                                        <td>
                                                                            <b class='btn btn-primary active'><?php echo $tag; ?></b>
                                                                        </td>
                                                                        <td>
                                                                            <a class='btn btn-default btn-xs' onclick="quitar_etiqueta('<?php echo $id_tag; ?>');">Quitar</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="hidden" name="id" value="<?php echo $curso['id']; ?>"/>
                                <input type="hidden" name="preimagen" value="<?php echo $curso['imagen']; ?>"/>
                                <input type="hidden" name="preimagen2" value="<?php echo $curso['imagen2']; ?>"/>
                                <input type="hidden" name="preimagen3" value="<?php echo $curso['imagen3']; ?>"/>
                                <input type="hidden" name="preimagen4" value="<?php echo $curso['imagen4']; ?>"/>
                                <input type="hidden" name="preimagengif" value="<?php echo $curso['imagen_gif']; ?>"/>
                                <input type="hidden" name="prearchivo1" value="<?php echo $curso['archivo1']; ?>"/>
                                <input type="hidden" name="prearchivo2" value="<?php echo $curso['archivo2']; ?>"/>
                                <input type="hidden" name="prearchivo3" value="<?php echo $curso['archivo3']; ?>"/>
                                <input type="hidden" name="prearchivo4" value="<?php echo $curso['archivo4']; ?>"/>
                                <input type="hidden" name="prearchivo5" value="<?php echo $curso['archivo5']; ?>"/>
                                <input type="submit" value="ACTUALIZAR DATOS DEL CURSO" class="btn btn-success btn-block active"/>
                            </div>
                        </div>
                    </form>
                </div>


                <?php
                if (!isset_organizador()) {
                    ?>

                    <div class="panel panel-primary">
                        <div class="panel-heading">COMPLEMENTOS DEL CURSO</div>
                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#M2-home">CERTIFICADOS</a></li>
                                <li><a data-toggle="tab" href="#M2-menu1">CERTIFICADOS ADICIONALES</a></li>
                                <li><a data-toggle="tab" href="#M2-menu2">TURNOS AGREGADOS</a></li>
                                <li><a data-toggle="tab" href="#M2-menu3">CURSO ONLINE</a></li>
                                <?php
                                if (acceso_cod('adm-crea-cuponcurso')) {
                                    ?>
                                    <li><a data-toggle="tab" href="#M2-menu4">CUPONES DESCUENTO</a></li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <div class="tab-content">
                                <div id="M2-home" class="tab-pane fade in active">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">CERTIFICADO PRINCIPAL - <?php echo $curso['titulo']; ?></div>
                                        <div class="panel-body">
                                            <?php
                                            if ($curso['id_certificado'] == '0') {
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <b>PRIMER CERTIFICADO</b>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p>
                                                                        El curso actual no tiene certificado principal asociado.
                                                                    </p>
                                                                    <br/>
                                                                </td>
                                                            <tr>
                                                                <td colspan="2">

                                                                    <a class="btn btn-warning btn-block active" data-toggle="modal" data-target="#MODAL-asignar-certificado">
                                                                        <i class="fa fa-plus"></i> HABILITAR PRIMER CERTIFICADO
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <b>SEGUNDO CERTIFICADO</b>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p>
                                                                        El curso actual no tiene segundo certificado principal asociado.
                                                                    </p>
                                                                    <br/>
                                                                </td>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <a class="btn btn-warning btn-block active" onclick="alert('El segundo certificado solo se habilita cuando antes se tiene ya un primer certificado.');">
                                                                        <i class="fa fa-plus"></i> HABILITAR SEGUNDO CERTIFICADO
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='" . $curso['id_certificado'] . "' ORDER BY id DESC limit 1 ");
                                                $rqc2 = mysql_fetch_array($rqc1);
                                                ?>

                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <b>PRIMER CERTIFICADO</b>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Codigo
                                                                </td>
                                                                <td>
                                                                    <?php echo $rqc2['codigo']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Titulo:</td>
                                                                <td>
                                                                    <?php echo $rqc2['cont_titulo']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Texto 1:</td>
                                                                <td>
                                                                    <?php echo $rqc2['cont_uno']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Texto 2</td>
                                                                <td>
                                                                    <?php echo $rqc2['cont_dos']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Texto 3</td>
                                                                <td>
                                                                    <?php echo $rqc2['cont_tres']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Texto QR</td>
                                                                <td>
                                                                    <?php echo $rqc2['texto_qr']; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>FIRMA 1</td>
                                                                <td>
                                                                    <?php
                                                                    if ($rqc2['id_firma1'] !== '0') {
                                                                        $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma1'] . "' ");
                                                                        $rqf2 = mysql_fetch_array($rqf1);
                                                                        $url_firma = "Sin imagen de firma!";
                                                                        $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                                                        if (file_exists($url_img)) {
                                                                            $url_firma = $url_img;
                                                                        }
                                                                        echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . "  <img src='$url_firma' style='height:25px;'/>";
                                                                    } else {
                                                                        echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>FIRMA 2</td>
                                                                <td>
                                                                    <?php
                                                                    if ($rqc2['id_firma2'] !== '0') {
                                                                        $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma2'] . "' ");
                                                                        $rqf2 = mysql_fetch_array($rqf1);
                                                                        $url_firma = "Sin imagen de firma!";
                                                                        $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                                                        if (file_exists($url_img)) {
                                                                            $url_firma = $url_img;
                                                                        }
                                                                        echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . "  <img src='$url_firma' style='height:25px;'/>";
                                                                    } else {
                                                                        echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo'];
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>IMPRESION</td>
                                                                <td>
                                                                    <?php
                                                                    if ($rqc2['sw_solo_nombre'] == '1') {
                                                                        echo "Solo Nombre-Fecha";
                                                                    } else {
                                                                        echo "COMPLETA";
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>FORMATO</td>
                                                                <td>
                                                                    <?php
                                                                    if ($rqc2['formato'] == '2') {
                                                                        echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte lateral derecha';
                                                                    } else {
                                                                        echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte superior';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">

                                                                    <br/>
                                                                    <br/>
                                                                    <button class='btn-sm btn-success btn-block active' data-toggle="modal" data-target="#MODAL-editar-certificado">
                                                                        <i class="fa fa-edit"></i> Editar datos de certificado
                                                                    </button>
                                                                    <br/>
                                                                </td>
                                                            </tr>
                                                        </table>


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
                                                                            <table class="table table-striped table-bordered">
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>TITULO:</b></span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>CONT. 1:</b></span>
                                                                                    </td>
                                                                                    <td>
                                <!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                                                                                        <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>CONT. 2:</b></span>
                                                                                    </td>
                                                                                    <td>
                                <!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                                                                                        <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                                                                                    </td>
                                                                                    <td>
                                <!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                                                                                        <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>TEXTO QR:</b></span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <textarea class="form-control" name="texto_qr" rows="2"><?php echo $rqc2['texto_qr']; ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>FECHA QR:</b></span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="date" class="form-control" name="fecha_qr" value="<?php echo $rqc2['fecha_qr']; ?>"/>
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
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                                                                                    </td>
                                                                                    <td>
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
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><b>Formato:</b></span>
                                                                                    </td>
                                                                                    <td class="text-center">
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
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2" class="text-center">
                                                                                        <input type='hidden' name='id_certificado' value='<?php echo $curso['id_certificado']; ?>'/>
                                                                                        <input type='submit' name='editar-certificado' class="btn btn-success" value="ACTUALIZAR CERTIFICADO"/>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>

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

                                                    </div>
                                                    <div class="col-md-6">

                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <th colspan="2" class="text-center">
                                                                    <b>SEGUNDO CERTIFICADO</b>
                                                                </th>
                                                            </tr>

                                                            <?php
                                                            if ($curso['id_certificado_2'] == '0') {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <p>El curso no tiene asignado un segundo certificado</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <br/>
                                                                        <button class="btn-sm btn-warning btn-block active" data-toggle="modal" data-target="#MODAL-asignar-certificado-2"><i class="fa fa-plus"></i> AGREGAR 2do CERTIFICADO</button>
                                                                        <br/>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                echo "</table>";
                                                            } else {
                                                                $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='" . $curso['id_certificado_2'] . "' ORDER BY id DESC limit 1 ");
                                                                $rqc2 = mysql_fetch_array($rqc1);
                                                                ?>
                                                                <tr>
                                                                    <td>Codigo</td>
                                                                    <td>
                                                                        <?php echo $rqc2['codigo']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Titulo</td>
                                                                    <td>
                                                                        <?php echo $rqc2['cont_titulo']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Texto 1</td>
                                                                    <td>
                                                                        <?php echo $rqc2['cont_uno']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Texto 2</td>
                                                                    <td>
                                                                        <?php echo $rqc2['cont_dos']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Texto 3</td>
                                                                    <td>
                                                                        <?php echo $rqc2['cont_tres']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Texto QR</td>
                                                                    <td>
                                                                        <?php echo $rqc2['texto_qr']; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>FIRMA 1</td>
                                                                    <td>
                                                                        <?php
                                                                        if ($rqc2['id_firma1'] !== '0') {
                                                                            $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma1'] . "' ");
                                                                            $rqf2 = mysql_fetch_array($rqf1);
                                                                            $url_firma = "Sin imagen de firma!";
                                                                            $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                                                            if (file_exists($url_img)) {
                                                                                $url_firma = $url_img;
                                                                            }
                                                                            echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . "  <img src='$url_firma' style='height:25px;'/>";
                                                                        } else {
                                                                            echo $rqc2['firma1_nombre'] . " | " . $rqc2['firma1_cargo'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>FIRMA 2</td>
                                                                    <td>
                                                                        <?php
                                                                        if ($rqc2['id_firma2'] !== '0') {
                                                                            $rqf1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='" . $rqc2['id_firma2'] . "' ");
                                                                            $rqf2 = mysql_fetch_array($rqf1);
                                                                            $url_firma = "Sin imagen de firma!";
                                                                            $url_img = "contenido/imagenes/firmas/" . $rqf2['imagen'];
                                                                            if (file_exists($url_img)) {
                                                                                $url_firma = $url_img;
                                                                            }
                                                                            echo $rqf2['nombre'] . " | " . $rqf2['cargo'] . "  <img src='$url_firma' style='height:25px;'/>";
                                                                        } else {
                                                                            echo $rqc2['firma2_nombre'] . " | " . $rqc2['firma2_cargo'];
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>IMPRESION</td>
                                                                    <td>
                                                                        <?php
                                                                        if ($rqc2['sw_solo_nombre'] == '1') {
                                                                            echo "Solo Nombre-Fecha";
                                                                        } else {
                                                                            echo "COMPLETA";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        FORMATO :
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($rqc2['formato'] == '2') {
                                                                            echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte lateral derecha';
                                                                        } else {
                                                                            echo 'Formato-' . $rqc2['formato'] . ' | QR en la parte superior';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">

                                                                        <br/>
                                                                        <br/>
                                                                        <button class='btn-sm btn-success btn-block active' data-toggle="modal" data-target="#MODAL-editar-certificado-2"><i class="fa fa-edit"></i> Editar datos del 2do certificado</button>
                                                                        <br/>
                                                                    </td>
                                                                </tr>
                                                            </table>


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
                                                                                <table class="table table-striped table-bordered">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>TITULO:</b></span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>CONT. 1:</b></span>
                                                                                        </td>
                                                                                        <td>
                                    <!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                                                                                            <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>CONT. 2:</b></span>
                                                                                        </td>
                                                                                        <td>
                                    <!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                                                                                            <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                                                                                        </td>
                                                                                        <td>
                                    <!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                                                                                            <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>TEXTO QR:</b></span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <textarea class="form-control" name="texto_qr" rows="2"><?php echo $rqc2['texto_qr']; ?></textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>FECHA QR:</b></span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="date" class="form-control" name="fecha_qr" value="<?php echo $rqc2['fecha_qr']; ?>"/>
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
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
                                                                                        </td>
                                                                                        <td>
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
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <span class="input-group-addon"><b>Formato:</b></span>
                                                                                        </td>
                                                                                        <td>
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
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <input type='hidden' name='id_certificado' value='<?php echo $curso['id_certificado_2']; ?>'/>
                                                                                            <input type='submit' name='editar-certificado-2' class="btn btn-success" value="ACTUALIZAR CERTIFICADO"/>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>


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
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="M2-menu1" class="tab-pane fade">
                                    <div class="panel panel-default">
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
                                <div id="M2-menu2" class="tab-pane fade">
                                    <div class="panel panel-default">
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
                                </div>
                                <div id="M2-menu3" class="tab-pane fade">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">CURSO ONLINE - <?php echo $curso['titulo']; ?></div>
                                        <div class="panel-body">

                                            <form action="" method="post" enctype="multipart/form-data">
                                                <table class="table table-striped">
                                                    <?php
                                                    $rqmc1 = query("SELECT * FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
                                                    if (mysql_num_rows($rqmc1) == 0) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                El curso actual no tiene habilitado la modalidad de 'Curso en linea'. 
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-primary active btn-block" data-toggle="modal" data-target="#MODAL-habilitar-onlinecourse"><i class="fa fa-plus"></i> HABILITAR CURSO EN LINEA</a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    } else {
                                                        $rqmc2 = mysql_fetch_array($rqmc1);
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                T&iacute;tulo con el que se muestra en el listado de cursos online
                                                            </td>
                                                            <td colspan="2">
                                                                <input type="text" class="form-control" value="<?php echo $rqmc2['titulo']; ?>" name="titulo_curso_online"/>
                                                            </td>
                                                            <td>
                                                                <input type="submit" class="btn btn-success active btn-block" name="editar-titulo-curso-online" value="ACTUALIZAR T&Iacute;TULO"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Imagen principal del curso
                                                            </td>
                                                            <td>
                                                                <a href="cursos/<?php echo $rqmc2['imagen']; ?>.size=6.img" target="_blank">
                                                                    <img src="cursos/<?php echo $rqmc2['imagen']; ?>.size=4.img" style="height:35px;"/>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <input type="file" class="form-control" value="" name="imagen"/>
                                                                <input type="hidden" class="form-control" value="<?php echo $rqmc2['imagen']; ?>" name="imagen_actual"/>
                                                            </td>
                                                            <td>
                                                                <input type="submit" class="btn btn-success active btn-block" name="editar-imagen-curso-online" value="ACTUALIZAR IMAGEN"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                El curso si tiene habilitado la modalidad 'Curso en linea', ingresa al siguiente enlace para administrar el curso Online. 
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-success active btn-block" href="cursos-online-administrar/<?php echo $rqmc2['id']; ?>.adm">ADMINISTRAR CONTENIDO</a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        if ($rqmc2['estado'] == '1') {
                                                            ?>
                                                            <tr>
                                                                <td colspan="3">
                                                                    El curso online se encuentra <span style="color:green;">'ACTIVADO'</span> y visible en la seccion de cursos Online. 
                                                                </td>
                                                                <td>
                                                                    <input type="submit" class="btn btn-danger btn-block" name="desactivar-curso-online" value="DESACTIVAR"/>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="3">
                                                                    El curso online se encuentra <span style="color:red;">'DESACTIVADO'</span> y no esta visible en la seccion de cursos Online. 
                                                                </td>
                                                                <td>
                                                                    <input type="submit" class="btn btn-success btn-block" name="activar-curso-online" value="ACTIVAR"/>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                Docente asignado para este curso: 
                                                                <?php
                                                                $rqdd1 = query("SELECT nombres,apellidos FROM cursos_docentes WHERE id='" . $rqmc2['id_docente'] . "' ");
                                                                if (mysql_num_rows($rqdd1) > 0) {
                                                                    $rqdd2 = mysql_fetch_array($rqdd1);
                                                                    echo "<b>NOMBRE:</b> " . $rqdd2['nombres'] . " " . $rqdd2['apellidos'] . " ";
                                                                } else {
                                                                    echo "<b>NO SE ASIGNO DOCENTE PARA ESTE CURSO ONLINE</b> ";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td colspan="2">
                                                                <select class="form-control" name="id_docente">
                                                                    <option value="0">NO SE ASIGNO DOCENTE PARA ESTE CURSO ONLINE</option>
                                                                    <?php
                                                                    $rqdd1 = query("SELECT id,nombres,apellidos FROM cursos_docentes WHERE estado='1' ");
                                                                    while ($rqdd2 = mysql_fetch_array($rqdd1)) {
                                                                        $selected = "";
                                                                        if ($rqmc2['id_docente'] == $rqdd2['id']) {
                                                                            $selected = " selected='selected' ";
                                                                        }
                                                                        echo '<option value="' . $rqdd2['id'] . '" ' . $selected . '>' . $rqdd2['nombres'] . ' ' . $rqdd2['apellidos'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="submit" class="btn btn-success active btn-block" name="asignar-docente-curso-online" value="ASIGNAR DOCENTE"/>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>

                                                <input type="hidden" name="id_curso_online" value="<?php echo $rqmc2['id']; ?>"/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (acceso_cod('adm-crea-cuponcurso')) {
                                    ?>
                                    <div id="M2-menu4" class="tab-pane fade">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">CUPONES DESCUENTO - <?php echo $curso['titulo']; ?></div>
                                            <div class="panel-body">
                                                <?php
                                                $rqdc1 = query("SELECT * FROM cursos_cupones WHERE id_curso='$id_curso' LIMIT 1");
                                                if (mysql_num_rows($rqdc1) == 0) {
                                                    ?>
                                                    <div class="alert alert-info">
                                                        <strong>Info!</strong> El curso no tiene asignado ningun cup&oacute;n
                                                    </div>
                                                    <form action="" method="post" enctype="multipart/form-data">
                                                        <table class="table table-striped">
                                                            <tr>
                                                                <td>
                                                                    Detalle del cup&oacute;n :
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" value="" name="detalle_cupon" placeholder="CURSO SICOES E INFOSICOES..."/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Porcentaje de descuento :
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" value="0" name="descuento_cupon"/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    Fecha de expiraci&oacute;n :
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?>" name="expiracion_cupon"/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <input type="submit" class="btn btn-success active btn-block" name="asignar-cupon-descuento" value="ASIGNAR CUP&Oacute;N"/>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </form>
                                                    <?php
                                                } else {
                                                    $rqdc2 = mysql_fetch_array($rqdc1);
                                                    ?>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <td>
                                                                Detalle del cup&oacute;n :
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" disabled value="<?php echo $rqdc2['detalle_cupon']; ?>" name="detalle_cupon" placeholder="CURSO SICOES E INFOSICOES..."/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Porcentaje de descuento :
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" disabled value="<?php echo $rqdc2['porcentaje_descuento']; ?>" name="descuento_cupon"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Fecha de expiraci&oacute;n :
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control" disabled value="<?php echo $rqdc2['fecha_expiracion']; ?>" name="expiracion_cupon"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- Trigger the modal with a button -->
                                                    <button type="button" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalEC">Editar cup&oacute;n</button>

                                                    <!-- Modal -->
                                                    <div id="myModalEC" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">EDICION DE CUP&Oacute;N</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="" method="post" enctype="multipart/form-data">
                                                                        <table class="table table-striped">
                                                                            <tr>
                                                                                <td>
                                                                                    Detalle del cup&oacute;n :
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" value="<?php echo $rqdc2['detalle_cupon']; ?>" name="detalle_cupon" placeholder="CURSO SICOES E INFOSICOES..."/>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    Porcentaje de descuento :
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" class="form-control" value="<?php echo $rqdc2['porcentaje_descuento']; ?>" name="descuento_cupon"/>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    Fecha de expiraci&oacute;n :
                                                                                </td>
                                                                                <td>
                                                                                    <input type="date" class="form-control" value="<?php echo $rqdc2['fecha_expiracion']; ?>" name="expiracion_cupon"/>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <input type="hidden" value="<?php echo $rqdc2['id']; ?>" name="id_cupon"/>
                                                                                    <input type="submit" class="btn btn-success active btn-block" name="actualizar-cupon-descuento" value="ACTUALIZAR CUP&Oacute;N"/>
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
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                    <?php
                }
                ?>



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
                                <textarea  class="form-control" name="contenido_uno_certificado" rows="2"><?php echo utf8_encode("Por cuanto se reconoce que completó satisfactoriamente el curso de capacitación:"); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </td>
                            <td>
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <?php
                                $aux_txt_texto_qr = $curso['texto_qr'];
                                if ($aux_txt_texto_qr == '') {
                                    $aux_txt_texto_qr = refactor_titcurso($curso['titulo']);
                                }
                                ?>
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $aux_txt_texto_qr; ?>", con una carga horaria de 10 horas.</textarea>
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
                                $rqcc1 = query("SELECT nombre FROM ciudades WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de <?php echo date('Y'); ?>"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> d&iacute;as del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de <?php echo date('Y'); ?>.</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TEXTO QR:</b></span>
                            </td>
                            <td>
                                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $aux_txt_texto_qr; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FECHA QR:</b></span>
                            </td>
                            <td>
                                <input type="date" class="form-control" name="fecha_qr" value="<?php echo $curso['fecha']; ?>"/>
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

                                        $selec = "";
                                        if ($rqfc2['id'] == '4') {
                                            $selec = " selected='selected' ";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selec; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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

                                        $selec = "";
                                        if ($rqfc2['id'] == '4') {
                                            $selec = " selected='selected' ";
                                        }
                                        ?>
                                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selec; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
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
                                <input type="radio" name="sw_solo_nombre" value="0" checked="" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1"/>
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
                                <?php
                                $aux_txt_texto_qr = $curso['texto_qr'];
                                if ($aux_txt_texto_qr == '') {
                                    $aux_txt_texto_qr = $curso['titulo'];
                                }
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>-->
                                <textarea  class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $aux_txt_texto_qr; ?>", con una carga horaria de 8 horas.</textarea>
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
                                $rqcc1 = query("SELECT nombre FROM ciudades WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
<!--                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de <?php echo date('Y'); ?>"/>-->
                                <textarea  class="form-control" name="contenido_tres_certificado" rows="2">Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de <?php echo date('Y'); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>TEXTO QR:</b></span>
                            </td>
                            <td>
                                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $aux_txt_texto_qr; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="input-group-addon"><b>FECHA QR:</b></span>
                            </td>
                            <td>
                                <input type="date" class="form-control" name="fecha_qr" value="<?php echo $curso['fecha']; ?>"/>
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
                                <input type="radio" name="sw_solo_nombre" value="0" checked="" /> 
                                Completa
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="sw_solo_nombre" value="1" />
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
                                <input type="text" name="cont_dos" value='<?php echo utf8_encode("Con una carga horaria de 10 horas llevado a cabo en un CICLO DE CURSOS DE ACTUALIZACIÓN PEDAGÓGICA, del 04 al 09 del mes de Diciembre del año ") . date('Y') . '.' ?>' class="form-control" id="date" required="">
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

<script>
    function actualiza_lugares() {
        $("#select_lugar").html('<option>Cargando...</option>');
        var id_ciudad = $("#select_ciudad").val();
        var current_id_lugar = $("#current_id_lugar").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_lugares.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.busca_etiquetas.php',
            data: {tag: dat, id_curso: '<?php echo $id_curso; ?>'},
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.asocia_etiqueta.php',
            data: {id_tag: dat, id_curso: '<?php echo $id_curso; ?>'},
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.quitar_etiqueta.php',
            data: {id_tag: dat, id_curso: '<?php echo $id_curso; ?>'},
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.cambiar_estado_curso.php',
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

function verificador_hora($dat) {
    if ($dat == '') {
        return "00:00";
    } else {
        return $dat;
    }
}

function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}

function hora_descuento($fecha){
    $text = '';
    $h = date("H:i",strtotime($fecha));
    if($h!=='00:00'){
        $text = ' a horas: '.$h;
    }
    return $text;
}

function refactor_titcurso($dat) {
    $rqc1 = query("SELECT nombre FROM ciudades ");
    $busc = array();
    while ($rqc2 = mysql_fetch_array($rqc1)) {
        array_push($busc, "en " . $rqc2['nombre']);
    }
    $remm = '';
    return trim(str_replace($busc, $remm, $dat));
}
