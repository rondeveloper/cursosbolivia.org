<?php
/* id curso */
$id_curso = (int) $get[2];

$mensaje = '';

/* adminsitrador */
$rqdadm1 = query("SELECT nivel FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqdadm2 = fetch($rqdadm1);
$nivel_administrador = $rqdadm2['nivel'];


/* agregar-certificado-adicional */
if (isset_post('agregar-certificado-adicional')) {

    $cont_titulo = post('titulo_certificado');
    $cont_uno = post('contenido_uno_certificado');
    $cont_dos = post('contenido_dos_certificado');
    $cont_tres = post('contenido_tres_certificado');
    $sw_solo_nombre = post('sw_solo_nombre');
    $texto_qr = post('texto_qr');
    $fecha_qr = post('fecha_qr');
    $fecha2_qr = post('fecha2_qr');
    $id_fondo_digital = post('id_fondo_digital');
    $id_fondo_fisico = post('id_fondo_fisico');
    $id_tipo_cert = post('id_tipo_cert');

    $codigo_certificado = "CERT-$id_curso";

    /* validacion fechas */
    if(strtotime($fecha_qr)>strtotime($fecha2_qr)){
        $mensaje .= '<div class="alert alert-danger">
        <strong>ERROR</strong> la fecha inicial no puede ser mayor a la fecha final.
    </div>';
    }else{
        /* registro de certificado */
        query("INSERT INTO cursos_certificados
            (id_curso, id_tipo_cert, codigo, modelo, cont_titulo, cont_uno, cont_dos, cont_tres,texto_qr,fecha_qr,fecha2_qr,sw_solo_nombre,id_fondo_digital,id_fondo_fisico, estado) 
                VALUES 
            ('$id_curso','$id_tipo_cert','$codigo_certificado','1','$cont_titulo','$cont_uno','$cont_dos','$cont_tres','$texto_qr','$fecha_qr','$fecha2_qr','$sw_solo_nombre','$id_fondo_digital','$id_fondo_fisico','1')");
        $id_certificado = insert_id();
        /* registro de relacion de certificado */
        query("INSERT INTO cursos_rel_cursocertificado (id_curso,id_certificado) VALUES ('$id_curso','$id_certificado') ");
        logcursos('Asignacion de certificado adicional [' . $codigo_certificado . '-' . $id_certificado . ']', 'curso-edicion', 'curso', $id_curso);
        query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        query("UPDATE cursos_certificados SET codigo='$codigo_certificado-$id_certificado' WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");

        $mensaje .= '<div class="alert alert-success">
    <strong>Exito!</strong> El certificado fue agregado exitosamente. 
    </div>';
    }
}

/* eliminar-certificado adicional */
if (isset_post('eliminar-certificado')) {
    $id_certificado = post('id_certificado');
    query("DELETE FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    query("DELETE FROM cursos_rel_cursocertificado WHERE id_certificado='$id_certificado' ORDER BY id DESC limit 5 ");
    query("DELETE FROM cursos_emisiones_certificados WHERE id_certificado='$id_certificado' ORDER BY id DESC limit 50 ");
    logcursos('Eliminacion de certificado adicional [ID' . $id_certificado . '][+emisiones]', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue eliminado correctamente. 
</div>';
}

/* edicion de curso */
if (isset_post('editar-curso')) {

    $id = post('id');
    $contenido = post_html('formulario');
    $id_organizador = post('id_organizador');
    $sw_siguiente_semana = post('sw_siguiente_semana');
    $sw_suspendido = post('sw_suspendido');
    $columna = post('columna');
    $id_modalidad = post('id_modalidad');
    $incrustacion = post_html('incrustacion');

    $id_ciudad = post('id_ciudad');
    $id_lugar = post('id_lugar');
    $id_docente = post('id_docente');
    $colaborador = post('colaborador');
    $mailto_subject = str_replace("'","",str_replace('"','',post('mailto_subject')));
    $mailto_content = str_replace("'","",str_replace('"','',post('mailto_content')));
    $fb_txt_requisitos = post('fb_txt_requisitos');
    $fb_txt_dirigido = post('fb_txt_dirigido');
    $fb_hashtags = post('fb_hashtags');
    $texto_qr = post('texto_qr');
    $observaciones = post('observaciones');
    $sw_askfactura = post('sw_askfactura');
    $sw_ipelc = post('sw_ipelc');
    $sw_tienda = post('sw_tienda');
    $sw_ask_datos_opcionales = post('sw_ask_datos_opcionales');
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

    $sw_flag_cursosbo = '0';
    if (isset_post('sw_flag_cursosbo')) {
        $sw_flag_cursosbo = '1';
    }
    $sw_flag_infosicoes = '0';
    if (isset_post('sw_flag_infosicoes')) {
        $sw_flag_infosicoes = '1';
    }
    $sw_flag_cursoscombo = '0';
    if (isset_post('sw_flag_cursoscombo')) {
        $sw_flag_cursoscombo = '1';
    }
    $sw_flag_cursosbocom = '0';
    if (isset_post('sw_flag_cursosbocom')) {
        $sw_flag_cursosbocom = '1';
    }

    $sw_view_home = '0';
    if (isset_post('sw_view_home')) {
        $sw_view_home = '1';
    }
    $sw_view_cat = '0';
    if (isset_post('sw_view_cat')) {
        $sw_view_cat = '1';
    }

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');
    $imagen_gif = post('preimagengif');

    $id_material = post('id_material');

    $pixelcode = post_html('pixelcode');
    $tagmgr_body = post_html('tagmgr_body');
    $short_link = post_html('short_link');

    $inicio_numeracion = post('inicio_numeracion');

    $rqinp1 = query("SELECT inicio_numeracion FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqinp2 = fetch($rqinp1);
    $inicio_numeracion_previo = $rqinp2['inicio_numeracion'];
    if ((int) $inicio_numeracion !== (int) $inicio_numeracion_previo) {
        /* actualizar numeracion */
        $aux_numeracion = $inicio_numeracion;
        $rqan1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ORDER BY id ASC ");
        while ($rqan2 = fetch($rqan1)) {
            $id_part_temp = $rqan2['id'];
            query("UPDATE cursos_participantes SET numeracion='$aux_numeracion' WHERE id='$id_part_temp' ORDER BY id DESC limit 1 ");
            $aux_numeracion++;
        }
    }

    if (isset_archivo('imagen')) {
        $imagen = time() . str_replace("'", "", archivoName('imagen'));
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/paginas/$imagen");
        //sube_imagen(archivo('imagen'), $___path_raiz."contenido/imagenes/paginas/$imagen");
    }
    if (isset_archivo('imagen2')) {
        $imagen2 = time() . str_replace("'", "", archivoName('imagen2'));
        move_uploaded_file(archivo('imagen2'), $___path_raiz."contenido/imagenes/paginas/$imagen2");
    }
    if (isset_archivo('imagen3')) {
        $imagen3 = time() . str_replace("'", "", archivoName('imagen3'));
        move_uploaded_file(archivo('imagen3'), $___path_raiz."contenido/imagenes/paginas/$imagen3");
    }
    if (isset_archivo('imagen4')) {
        $imagen4 = time() . str_replace("'", "", archivoName('imagen4'));
        move_uploaded_file(archivo('imagen4'), $___path_raiz."contenido/imagenes/paginas/$imagen4");
    }
    if (isset_archivo('imagen_gif')) {
        $imagen_gif = time() . str_replace("'", "", archivoName('imagen_gif'));
        move_uploaded_file(archivo('imagen_gif'), $___path_raiz."contenido/imagenes/paginas/$imagen_gif");
    }

    query("UPDATE cursos SET 
              contenido='$contenido',
              sw_siguiente_semana='$sw_siguiente_semana',
              sw_suspendido='$sw_suspendido',
              columna='$columna',
              id_modalidad='$id_modalidad',
              id_organizador='$id_organizador',
              incrustacion='$incrustacion',
              id_ciudad='$id_ciudad',
              id_categoria='$id_categoria',
              id_lugar='$id_lugar',
              id_docente='$id_docente',
              sw_v_expositor='$sw_v_expositor',
              sw_askfactura='$sw_askfactura',
              sw_ipelc='$sw_ipelc',
              sw_tienda='$sw_tienda',
              sw_ask_datos_opcionales='$sw_ask_datos_opcionales',
              colaborador='$colaborador',
              mailto_subject='$mailto_subject',
              mailto_content='$mailto_content',
              fb_txt_requisitos='$fb_txt_requisitos',
              fb_txt_dirigido='$fb_txt_dirigido',
              fb_hashtags='$fb_hashtags',
              observaciones='$observaciones',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              imagen_gif='$imagen_gif',
              id_material='$id_material',
              short_link='$short_link',
              inicio_numeracion='$inicio_numeracion',
              pixelcode='$pixelcode', 
              tagmgr_body='$tagmgr_body', 
              texto_qr='$texto_qr',
              sw_flag_cursosbo='$sw_flag_cursosbo',
              sw_flag_infosicoes='$sw_flag_infosicoes',
              sw_flag_cursoscombo='$sw_flag_cursoscombo',
              sw_flag_cursosbocom='$sw_flag_cursosbocom',
              sw_view_home='$sw_view_home',
              sw_view_cat='$sw_view_cat',
              sw_cierre='0' 
               WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    if ($id_categoria !== $id_categoria_anterior) {
        $rqdccc1 = query("SELECT cnt_cursos_total FROM cursos_categorias WHERE id='$id_categoria_anterior' ORDER BY id DESC limit 1 ");
        $rqdccc2 = fetch($rqdccc1);
        $cnt_cursos_total = abs((int) $rqdccc2['cnt_cursos_total'] - 1);
        query("UPDATE cursos_categorias SET cnt_cursos_total='$cnt_cursos_total' WHERE id='$id_categoria_anterior' ORDER BY id DESC limit 1 ");

        $rqdcccc1 = query("SELECT cnt_cursos_total FROM cursos_categorias WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");
        $rqdcccc2 = fetch($rqdcccc1);
        $cnt_cursos_total_n = abs((int) $rqdcccc2['cnt_cursos_total'] + 1);
        query("UPDATE cursos_categorias SET cnt_cursos_total='$cnt_cursos_total_n' WHERE id='$id_categoria' ORDER BY id DESC limit 1 ");
    }

    logcursos('Edicion de datos de curso [DATOS GENERALES]', 'curso-edicion', 'curso', $id_curso);

    /* actualizacion html curso */
    file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=curso-individual&id_curso=".$id_curso);
    file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=inicio");

    $mensaje .= '<div class="alert alert-success">
      <strong>EXITO!</strong> datos de curso actualizados correctamente.
    </div>
    <script>window.onload = function() {genera_htmlcurso_individual(' . $id_curso . ');}</script></script>
    ';
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
    $id_modelo_certificado = insert_id();

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


/* creacion de turno */
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
    $id_onlinecourse = post('id_onlinecourse');
    $id_docente = post('id_docente');
    $id_certificado = post('id_certificado');
    $id_certificado_2 = post('id_certificado_2');
    $fecha_inicio = post('fecha_inicio');
    $fecha_final = post('fecha_final');
    $sw_cod_asistencia = post('sw_cod_asistencia');
    $estado = post('estado');
    $rqmc1 = query("SELECT * FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' AND id_onlinecourse='$id_onlinecourse' ");
    if (num_rows($rqmc1) == 0) {
        query("INSERT INTO cursos_rel_cursoonlinecourse(
          id_curso, 
          id_onlinecourse, 
          id_docente, 
          id_certificado, 
          id_certificado_2, 
          fecha_inicio, 
          fecha_final, 
          sw_cod_asistencia, 
          estado
          ) VALUES (
          '$id_curso',
          '$id_onlinecourse',
          '$id_docente',
          '$id_certificado',
          '$id_certificado_2',
          '$fecha_inicio',
          '$fecha_final',
          '$sw_cod_asistencia',
          '$estado'
          )");
        logcursos('ASIGNACION DE CURSO VIRTUAL [CV:' . $id_onlinecourse . ']', 'curso-edicion', 'curso', $id_curso);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> registro agregado correctamente.
    </div>';
    }
}

/* desactivar-curso-online */
if (isset_post('desactivar-curso-online')) {
    $id_curso_online = post('id_curso_online');
    query("UPDATE cursos_onlinecourse SET estado='0' WHERE id='$id_curso_online' ");
    logcursos('DES-ACTIVACION de curso virtual', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('DES-ACTIVACION de curso virtual', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* activar-curso-online */
if (isset_post('activar-curso-online')) {
    $id_curso_online = post('id_curso_online');
    query("UPDATE cursos_onlinecourse SET estado='1' WHERE id='$id_curso_online' ");
    logcursos('ACTIVACION de curso virtual', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('ACTIVACION de curso virtual', 'curso-edicion', 'curso', $id_curso);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* asignar-docente-curso-online */
if (isset_post('asignar-docente-curso-online')) {
    $id_curso_online = post('id_curso_online');
    $id_docente = post('id_docente');
    query("UPDATE cursos_onlinecourse SET id_docente='$id_docente' WHERE id='$id_curso_online' ");
    logcursos('Asignacion de docente a curso virtual', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('Asignacion de docente a curso virtual', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
}

/* editar-titulo-curso-online */
if (isset_post('editar-titulo-curso-online')) {
    $id_curso_online = post('id_curso_online');
    $titulo_curso_online = post('titulo_curso_online');
    query("UPDATE cursos_onlinecourse SET titulo='$titulo_curso_online',descripcion='$titulo_curso_online' WHERE id='$id_curso_online' ");
    logcursos('Edicion de datos curso virtual', 'curso-online-edicion', 'curso-online', $id_curso_online);
    logcursos('Edicion de datos curso virtual', 'curso-edicion', 'curso', $id_curso);
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
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/cursos/$imagen");
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
        $rqmc2 = fetch($rqmc1);
        $id_onlinecourse = $rqmc2['id'];

        $arch = $id_curso . '-' . archivoName('archivo');
        move_uploaded_file(archivo('archivo'), $___path_raiz."contenido/archivos/cursos/$arch");
        query("INSERT INTO cursos_onlinecourse_material(id_onlinecourse, nombre, formato_archivo, nombre_fisico, estado) VALUES ('$id_onlinecourse','$nombre','$tipo_archivo','$arch','1')");

        logcursos('Agregado de material [curso virtual]', 'curso-edicion', 'curso', $id_curso);

        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
    }
}


/* agregar-pregunta-onlinecourse */
if (isset_post('agregar-pregunta-onlinecourse')) {
    $pregunta = post('pregunta');

    $rqmc1 = query("SELECT id FROM cursos_onlinecourse WHERE id_curso='$id_curso' ");
    $rqmc2 = fetch($rqmc1);
    $id_onlinecourse = $rqmc2['id'];

    query("INSERT INTO cursos_onlinecourse_preguntas(id_onlinecourse, pregunta, estado) VALUES ('$id_onlinecourse','$pregunta','1')");
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
    logcursos('Agregado de preguntas [curso virtual]', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se completo correctamente.
    </div>';
}

/* agregar cupon-descuento */
if (isset_post('asignar-cupon-infosicoes')) {
    $id_paquete = post('id_paquete');
    $duracion = post('duracion');
    $expiracion_cupon = post('expiracion_cupon');
    $fecha_registro = date("Y-m-d H:i");
    $id_administrador = administrador('id');
    if (acceso_cod('adm-crea-cuponcurso')) {
        query("INSERT INTO cursos_cupones_infosicoes (
               id_curso, 
               id_administrador, 
               id_paquete, 
               duracion, 
               fecha_expiracion, 
               fecha_registro, 
               estado
               ) VALUES (
               '$id_curso',
               '$id_administrador',
               '$id_paquete',
               '$duracion',
               '$expiracion_cupon',
               '$fecha_registro',
               '1'
               )");
        $id_cupon = insert_id();
        logcursos('Asignacion de cupon Infosiscon curso[C' . $id_curso . '][P' . $id_paquete . ']', 'curso-edicion', 'curso', $id_curso);
        logcursos('Asignacion de cupon Infosiscon curso[C' . $id_curso . '][P' . $id_paquete . ']', 'cupon-asignacion', 'cupon', $id_cupon);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se completo correctamente.
    </div>';
    }
}

/* actualizar-cupon-infosicoes */
if (isset_post('actualizar-cupon-infosicoes')) {
    $id_cupon = post('id_cupon');
    $id_paquete = post('id_paquete');
    $duracion = post('duracion');
    $expiracion_cupon = post('expiracion_cupon');
    $id_administrador = administrador('id');
    if (acceso_cod('adm-crea-cuponcurso')) {
        query("UPDATE cursos_cupones_infosicoes SET 
               id_paquete='$id_paquete', 
               duracion='$duracion', 
               fecha_expiracion='$expiracion_cupon'  
                WHERE id='$id_cupon' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de cupon Infosiscon curso', 'cupon-edicion', 'cupon', $id_cupon);
        $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el registro se modifico correctamente.
    </div>';
    }
}

/* registros */
$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($resultado_paginas);

/* departamento */
$curso_id_ciudad = $curso['id_ciudad'];
if ($curso_id_ciudad == '24') {
    $curso_id_departamento = '0';
} else {
    $rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$curso_id_ciudad' LIMIT 1 ");
    $rqdd2 = fetch($rqdd1);
    $curso_id_departamento = $rqdd2['id_departamento'];
}

/* url_corta */
$url_corta = $dominio . numIDcurso($curso['id']) . '/';
$rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$curso['id']."' AND r.estado=1 ");
if(num_rows($rqenc1)>0){
    $rqenc2 = fetch($rqenc1);
    $url_corta = $dominio.$rqenc2['enlace'] . "/";
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

<div class="hidden-lg">
    <?php
    include_once 'pages/items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3 class="titulo-head-principal">
                    <i class='btn btn-success active hidden-sm'><?php echo my_fecha_at1($curso['fecha']); ?></i> 
                    <?php echo $curso['titulo']; ?>
                </h3>
            </div>
        </div>
        <blockquote class="page-information hidden">
            <p>
                Edicion de curso.
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
                            $rqcp2 = fetch($rqcp1);
                            $cnt_participantes = $rqcp2['total'];
                            ?>
                            En este curso se registraron <?php echo $cnt_participantes; ?> participantes -> <a <?php echo loadpage('cursos-participantes/' . $id_curso); ?>> <i class="fa fa-group"></i> LISTADO DE PARTICIPANTES</a>
                            &nbsp;|&nbsp;
                            <a href="<?php echo $dominio.$curso['titulo_identificador']; ?>.html" target="_blank"> <i class="fa fa-eye"></i> VISUALIZAR EL CURSO</a>
                            &nbsp;|&nbsp;
                            <a <?php echo loadpage('cursos-listar'); ?>> <i class="fa fa-list"></i> LISTADO DE CURSOS</a>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <?php
                $sw_edicion_tituloFechaCostos = false;
                $txt_disabled = ' disabled="" ';
                if ((strtotime($curso['fecha']) >= strtotime(date("Y-m-d"))) || $nivel_administrador=='1' || $nivel_administrador=='2') {
                    $sw_edicion_tituloFechaCostos = true;
                    $txt_disabled = '';
                }
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        DATOS GENERALES - <?php echo $curso['titulo']; ?>
                        <div class="pull-right" style="width:170px;">
                            <input type='text' class="form-control" value="<?php echo $url_corta; ?>" style="margin-top: -8px;text-align: center;"/>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" action="" method="post" id="FORM-edicion_tituloFechaCostos">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#titulofechacostos">DATOS PRIMARIOS</a></li>
                                <li><a data-toggle="tab" href="#home">DATOS 1</a></li>
                                <li><a data-toggle="tab" href="#menu1">DATOS 2</a></li>
                                <li><a data-toggle="tab" href="#menu2">DATOS 3</a></li>
                                <li><a data-toggle="tab" href="#menu3">DATOS 4</a></li>
                                <li><a data-toggle="tab" href="#menu4">TAGS</a></li>
                                <li><a data-toggle="tab" href="#menu5" onclick="conf_bancos();">BANCOS / TIGOMONEY</a></li>
                            </ul>
                            <div class="tab-content">
                                <br/>
                                <div id="titulofechacostos" class="tab-pane fade in active">
                                    <div id="AJAXBOX-edicion_tituloFechaCostos"></div>
                                    <table style="width:100%;" class="table table-striped">
                                    <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Nombre del Curso: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <input type="text" name="titulo" value="<?php echo $curso['titulo']; ?>" class="form-control" <?php echo $txt_disabled ?>/>
                                                        <span class="input-group-addon" id="basic-addon1">
                                                            <select name="id_abreviacion" <?php echo $txt_disabled ?>>
                                                                <option value="0">Sin abreviaci&oacute;n</option>
                                                                <?php
                                                                $rqdca1 = query("SELECT id,titulo FROM cursos_abreviaciones WHERE estado='1' ");
                                                                while ($rqdca2 = fetch($rqdca1)) {
                                                                    $selected = '';
                                                                    if ($curso['id_abreviacion'] == $rqdca2['id']) {
                                                                        $selected = ' selected="" ';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $rqdca2['id']; ?>" <?php echo $selected; ?>><?php echo $rqdca2['titulo']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo formal: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="titulo_formal" value="<?php echo $curso['titulo_formal']; ?>" class="form-control" <?php echo $txt_disabled ?>/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Docente asignado: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control" name="id_docente_asignado" <?php echo $txt_disabled ?>  onchange='$("#select_docente").val(this.value);'>
                                                        <option value="0">Sin docente</option>
                                                        <?php
                                                        $rqdl1 = query("SELECT id,prefijo,nombres FROM cursos_docentes WHERE estado='1' ORDER BY id ASC ");
                                                        while ($rqdl2 = fetch($rqdl1)) {
                                                            $selected = '';
                                                            if ($curso['id_docente'] == $rqdl2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . trim($rqdl2['prefijo'] . ' ' . $rqdl2['nombres']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> &nbsp; % DESCUENTO recomendaci&oacute;n:</span>
                                                        <input type="number" name="rec_limitdesc" value="<?php echo $curso['rec_limitdesc']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_recomendaciones'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_recomendaciones" class="" <?php echo $check_f; ?> value="1" <?php echo $txt_disabled ?>/> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Horarios: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="horarios" value="<?php echo $curso['horarios']; ?>" class="form-control" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-5 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-time"></i> Total horas:</span>
                                                        <input type="number" name="duracion" value="<?php echo $curso['duracion']; ?>" class="form-control" placeholder="Duraci&oacute;n del curso..." step="0.1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del curso: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha" class="form-control" value="<?php echo $curso['fecha']; ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo" value="<?php echo $curso['costo']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="checkbox" name="" class="" value="1" checked="" disabled=""> Habilitado
                                                    <!--                                                    <br/>
                                                    <?php
                                                    /*
                                                      $check_f = '';
                                                      if ($curso['sw_fecha'] == '0') {
                                                      $check_f = ' checked="" ';
                                                      }
                                                     */
                                                    ?>
                                                                                                        <input type="checkbox" name="false_sw_fecha" class="" value="1" <?php echo $check_f; ?>> Atemporal-->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 1: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha2" class="form-control" value="<?php echo date("Y-m-d", strtotime($curso['fecha2'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha2_hour" class="form-control" value="<?php echo date("H:i", strtotime($curso['fecha2'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo2" value="<?php echo $curso['costo2']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha2'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha2" class="" <?php echo $check_f; ?> value="1" <?php echo $txt_disabled ?>/> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 2: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha3" class="form-control" value="<?php echo date("Y-m-d", strtotime($curso['fecha3'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha3_hour" class="form-control" value="<?php echo date("H:i", strtotime($curso['fecha3'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo3" value="<?php echo $curso['costo3']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha3'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha3" class="" <?php echo $check_f; ?> value="1" <?php echo $txt_disabled ?>/> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha estudiantes: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha_e" class="form-control" value="<?php echo $curso['fecha_e']; ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo_e" value="<?php echo $curso['costo_e']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha_e'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha_e" class="" <?php echo $check_f; ?> value="1" <?php echo $txt_disabled ?>/> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha previa 3: </span>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="date" name="fecha_e2" class="form-control" value="<?php echo date("Y-m-d", strtotime($curso['fecha_e2'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="time" name="fecha_e2_hour" class="form-control" value="<?php echo date("H:i", strtotime($curso['fecha_e2'])); ?>" <?php echo $txt_disabled ?>/>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Costo del curso:</span>
                                                        <input type="number" name="costo_e2" value="<?php echo $curso['costo_e2']; ?>" class="form-control" placeholder="" aria-describedby="basic-addon1" <?php echo $txt_disabled ?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <?php
                                                    $check_f = '';
                                                    if ($curso['sw_fecha_e2'] == '1') {
                                                        $check_f = ' checked="" ';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="sw_fecha_e2" class="" <?php echo $check_f; ?> value="1" <?php echo $txt_disabled ?>/> Habilitado
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                    if ($sw_edicion_tituloFechaCostos) {
                                        ?>
                                        <div class="panel-footer" style="background: #e8eaef;">
                                            <div style="text-align: center;padding:20px;width: 70%;margin: auto;">
                                                <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                                                <b class="btn btn-primary btn-block" onclick="edicion_tituloFechaCostos();">ACTUALIZAR TITULO/FECHA/COSTOS</b>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div id="home" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Organizador: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-cascade-control" name="id_organizador">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_organizadores WHERE estado='1' OR id='" . $curso['id_organizador'] . "' ");
                                                        while ($rqd2 = fetch($rqd1)) {
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
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-cascade-control" name="id_categoria">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                        while ($rqd2 = fetch($rqd1)) {
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Publicado en: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <table class="table table-striped table-hover table-bordered">
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_flag_cursosbo'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_flag_cursosbo" id="sw_flag_cursosbo" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_flag_cursosbo"><b style="font-size: 14pt;">Cursos . bo</b></label></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_flag_infosicoes'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_flag_infosicoes" id="sw_flag_infosicoes" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_flag_infosicoes"><b style="font-size: 14pt;">www.infosiscon.com</b></label></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_flag_cursoscombo'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_flag_cursoscombo" id="sw_flag_cursoscombo" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_flag_cursoscombo"><b style="font-size: 14pt;">www.cursos.com.bo</b></label></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_flag_cursosbocom'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_flag_cursosbocom" id="sw_flag_cursosbocom" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_flag_cursosbocom"><b style="font-size: 14pt;">www.cursosbo.com</b></label></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-3">
                                                    <br>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Visible en: </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <br>
                                                    <table class="table table-striped table-hover table-bordered">
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_view_home'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_view_home" id="sw_view_home" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_view_home"><b style="font-size: 14pt;">P&aacute;gina inicio</b></label></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $check = '';
                                                            if ($curso['sw_view_cat'] == '1') {
                                                                $check = 'checked="checked"';
                                                            }
                                                            ?>
                                                            <td style='width:25px;'><input type="checkbox" name="sw_view_cat" id="sw_view_cat" value="1" <?php echo $check; ?> style="width:21px;height:21px;"/></td>
                                                            <td><label for="sw_view_cat"><b style="font-size: 14pt;">P&aacute;gina categoria</b></label></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display:none;">
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
                                        <tr style="display:none;">
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
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; MODALIDAD: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control form-cascade-control" name="id_modalidad">
                                                        <?php
                                                        $rqd1 = query("SELECT * FROM cursos_modalidades WHERE estado='1' ");
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($curso['id_modalidad'] == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['nombre']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Solicitar factura: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['sw_askfactura'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_askfactura" <?php echo $check; ?> value="1"/> SI</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['sw_askfactura'] == '0') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_askfactura" <?php echo $check2; ?> value="0"/> NO</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; IPELC: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['sw_ipelc'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_ipelc" <?php echo $check; ?> value="1"/> SI</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['sw_ipelc'] == '0') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_ipelc" <?php echo $check2; ?> value="0"/> NO</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Curso de la Tienda: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['sw_tienda'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_tienda" <?php echo $check; ?> value="1"/> SI</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['sw_tienda'] == '0') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_tienda" <?php echo $check2; ?> value="0"/> NO</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Mostrar datos opcionales de registro: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <p class="form-control text-center">
                                                        <?php
                                                        $check = '';
                                                        if ($curso['sw_ask_datos_opcionales'] == '1') {
                                                            $check = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_ask_datos_opcionales" <?php echo $check; ?> value="1"/> SI</label> 
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                        $check2 = '';
                                                        if ($curso['sw_ask_datos_opcionales'] == '0') {
                                                            $check2 = 'checked="checked"';
                                                        }
                                                        ?>
                                                        <label><input type="radio" name="sw_ask_datos_opcionales" <?php echo $check2; ?> value="0"/> NO</label>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="panel-footer">
                                        <div style="text-align: center;padding:20px;">
                                            <b class="btn btn-success btn-block" onclick="confirmar_edicion();">ACTUALIZAR DATOS DEL CURSO</b>
                                        </div>
                                    </div>
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
                                                        while ($rqd2 = fetch($rqd1)) {
                                                            $selected = '';
                                                            if ($curso_id_departamento == $rqd2['id']) {
                                                                $selected = ' selected="selected" ';
                                                                $curso_departamento = $rqd2['nombre'];
                                                            }
                                                            echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                        }
                                                        $selected = '';
                                                        if ($curso_id_departamento == '0') {
                                                            $selected = ' selected="selected" ';
                                                            $curso_departamento = $rqd2['nombre'];
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
                                                        <select class="form-control" name="id_ciudad" id="select_ciudad" onchange="actualiza_lugares();">
                                                            <?php
                                                            $curso_ciudad_nombre = 'no-data';
                                                            $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$curso_id_departamento' ORDER BY nombre ASC ");
                                                            while ($rqd2 = fetch($rqd1)) {
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
                                                            while ($rqdl2 = fetch($rqdl1)) {
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
                                                <div class="col-md-9">
                                                    <select class="form-control" name="id_docente" id="select_docente">
                                                        <option value="0">Sin docente</option>
                                                        <?php
                                                        $rqdl1 = query("SELECT id,prefijo,nombres FROM cursos_docentes WHERE estado='1' ORDER BY id ASC ");
                                                        while ($rqdl2 = fetch($rqdl1)) {
                                                            $selected = '';
                                                            if ($curso['id_docente'] == $rqdl2['id']) {
                                                                $selected = ' selected="selected" ';
                                                            }
                                                            echo '<option value="' . $rqdl2['id'] . '" ' . $selected . ' >' . trim($rqdl2['prefijo'] . ' ' . $rqdl2['nombres']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="sw_v_expositor" value="<?php echo $curso['sw_v_expositor']; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <input type="hidden" name="colaborador" value="<?php echo $curso['colaborador']; ?>"/>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Whatsapp: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <b class="btn btn-default btn-block" data-toggle="modal" data-target="#MODAL-whatsapp_numeros" onclick="whatsapp_numeros();">(WhatsApp) N&Uacute;MEROS ASIGNADOS</b>
                                                    <br>
                                                    <?php
                                                    $rqnw1 = query("SELECT r.id,w.numero,w.responsable FROM cursos_rel_cursowapnum r INNER JOIN whatsapp_numeros w ON r.id_whats_numero=w.id WHERE r.id_curso='$id_curso' ");
                                                    if (num_rows($rqnw1) == 0) {
                                                        echo '<div class="alert alert-warning">
                                                      <strong>AVISO</strong> no hay numeros asignados.
                                                    </div>';
                                                    }else{
                                                    ?>
                                                    <table class="table table-bordered table-striped">
                                                        <?php
                                                        while ($rqnw2 = fetch($rqnw1)) {
                                                            ?>
                                                            <tr>
                                                                <td style="background: #FFF;"><?php echo $rqnw2['responsable']; ?></td>
                                                                <td style="background: #FFF;"><?php echo $rqnw2['numero']; ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Mail-TO: </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="mailto_subject" value="<?php echo $curso['mailto_subject']; ?>" class="form-control" placeholder="Subject en Mail-TO..."/>
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea name="mailto_content" class="form-control" placeholder="Content en Mail-TO..." style="height: 75px;"><?php echo $curso['mailto_content']; ?></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Texto requisitos (FB): </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="fb_txt_requisitos" value="<?php echo $curso['fb_txt_requisitos']; ?>" class="form-control" id="date">
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Texto dirigido (FB): </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="fb_txt_dirigido" value="<?php echo $curso['fb_txt_dirigido']; ?>" class="form-control" id="date">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-md-3">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Hashtags estaticos (FB): </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="fb_hashtags" value="<?php echo $curso['fb_hashtags']; ?>" class="form-control" id="date" placeholder="palabras,separadas,por,comas...">
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
                                    </table>
                                    <div class="panel-footer">
                                        <div style="text-align: center;padding:20px;">
                                            <b class="btn btn-success btn-block" onclick="confirmar_edicion();">ACTUALIZAR DATOS DEL CURSO</b>
                                        </div>
                                    </div>
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
                                                        $url_img = $dominio.'paginas/.size=2.img';
                                                        if ($curso['imagen_gif'] !== '') {
                                                            $url_img = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen_gif'];
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
                                                        $url_img = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen'];
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
                                                        $url_img = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen2'];
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
                                                        $url_img = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen3'];
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
                                                        $url_img = $dominio_www."contenido/imagenes/paginas/" . $curso['imagen4'];
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
                                                    <span class="input-group-addon"><i class="fa fa-download"></i> &nbsp; MATERIAL DIGITAL: </span>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="col-md-8">
                                                        <select class="form-control" name="id_material" id="select_docente">
                                                            <option value="0">Sin material asigando</option>
                                                            <?php
                                                            $rqmt1 = query("SELECT id,nombre_material FROM cursos_material WHERE estado='1' ORDER BY id DESC ");
                                                            while ($rqmt2 = fetch($rqmt1)) {
                                                                $selected = '';
                                                                if ($curso['id_material'] == $rqmt2['id']) {
                                                                    $selected = ' selected="selected" ';
                                                                }
                                                                echo '<option value="' . $rqmt2['id'] . '" ' . $selected . ' >' . $rqmt2['nombre_material'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="panel-footer">
                                        <div style="text-align: center;padding:20px;">
                                            <b class="btn btn-success btn-block" onclick="confirmar_edicion();">ACTUALIZAR DATOS DEL CURSO</b>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu3" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="panel-group">
                                                    <div class="panel panel-info">
                                                    <a href="tags-personalizados/<?php echo $id_curso; ?>.adm" target="_blank">
                                                        <div class="panel-heading" style="cursor:pointer;">
                                                            <h4 class="panel-title text-center">
                                                                <i class="fa fa-bars fa-fw"></i> Contenido din&aacute;mico [TAGs] &nbsp;&nbsp;
                                                            </h4>
                                                        </div>
                                                    </a>
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
                                    <div class="panel-footer">
                                        <div style="text-align: center;padding:20px;">
                                            <b class="btn btn-success btn-block" onclick="confirmar_edicion();">ACTUALIZAR DATOS DEL CURSO</b>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu4" class="tab-pane fade">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <div class="row" style="min-height:300px;">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6" style="background:#FFF;border: 1px solid #dbe5ef;">
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
                                                                while ($rqdcct2 = fetch($rqdcct1)) {
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
                                <div id="menu5" class="tab-pane fade">
                                    <div id="AJAXCONTENT-panel-m5"></div>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="id" value="<?php echo $curso['id']; ?>"/>
                        <input type="hidden" name="preimagen" value="<?php echo $curso['imagen']; ?>"/>
                        <input type="hidden" name="preimagen2" value="<?php echo $curso['imagen2']; ?>"/>
                        <input type="hidden" name="preimagen3" value="<?php echo $curso['imagen3']; ?>"/>
                        <input type="hidden" name="preimagen4" value="<?php echo $curso['imagen4']; ?>"/>
                        <input type="hidden" name="preimagengif" value="<?php echo $curso['imagen_gif']; ?>"/>
                        
                        <div id="box-confirmacion-edicion" style="display: none;
                            background: rgba(0, 0, 0, 0.5);
                            position: fixed;
                            width: 100%;
                            height: 100%;
                            top: 0px;
                            left: 0px;
                            z-index: 2;">
                            <div style="margin: 150px auto;width: 70%;">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <h4 class="text-center">Confirmaci&oacute;n final de edici&oacute;n de curso.</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Docente: </span>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control" id="aux_select_docente" style="font-size: 17pt;height: 45px;" onchange='$("#select_docente").val($("#aux_select_docente").val());'>
                                                    <option value="0">Sin docente</option>
                                                    <?php
                                                    $rqdl1 = query("SELECT id,prefijo,nombres FROM cursos_docentes WHERE estado='1' ORDER BY id ASC ");
                                                    while ($rqdl2 = fetch($rqdl1)) {
                                                        echo '<option value="' . $rqdl2['id'] . '" >' . trim($rqdl2['prefijo'] . ' ' . $rqdl2['nombres']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                             </div>
                                        </div>
                                        <br/>
                                        <input type="submit" value="CONFIRMAR EDICI&Oacute;N DE CURSO" name="editar-curso" class="btn btn-success btn-block"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">ENLACE DE CURSO</div>
                    <div class="panel-body" id="ajaxcontent-enlace">
                        <?php
                        $rqde1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON r.id_enlace=e.id WHERE r.id_curso='$id_curso' AND r.estado=1 ORDER BY r.id DESC limit 1 ");
                        if(num_rows($rqde1)>0){
                            $rqde2 = fetch($rqde1);
                            $url_enlace = $dominio.$rqde2['enlace'].'/';
                        ?>
                            <b style="color: green;">CON ENLACE</b> &nbsp;&nbsp; [ <?php echo $rqde2['enlace']; ?> ] &nbsp;&nbsp; <b class="btn btn-sm btn-default" onclick="asignar_enlace();">CAMBIAR ENLACE</b> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="<?php echo $url_enlace; ?>" target="_blank"><?php echo $url_enlace; ?></a>
                        <?php
                        }else{
                        ?>
                            <b>SIN ENLACE</b> &nbsp;&nbsp; (Enlace por defecto) &nbsp;&nbsp; <b class="btn btn-sm btn-info" onclick="asignar_enlace();">ASIGNAR ENLACE</b>
                        <?php
                        }
                        ?>
                    </div>
                </div>


                <div class="panel panel-primary">
                    <div class="panel-heading">COMPLEMENTOS DEL CURSO</div>
                    <div class="panel-body">

                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#M2-menu1">CERTIFICADOS</a></li>
                            <li><a data-toggle="tab" href="#M2-menu2">TURNOS AGREGADOS</a></li>
                            <li><a data-toggle="tab" href="#M2-menu3">CURSO VIRTUAL</a></li>
                            <?php
                            if (acceso_cod('adm-crea-cuponcurso')) {
                                ?>
                                <li><a data-toggle="tab" href="#M2-menu4">CUPONES INFOSISCON</a></li>
                                <?php
                            }
                            ?>
                            <li><a data-toggle="tab" href="#M2-menu5" onclick="cursos_gratuitos('<?php echo $id_curso; ?>');">GRATUITOS ASOCIADOS</a></li>
                            <li><a data-toggle="tab" href="#M2-menu6" onclick="material_curso('<?php echo $id_curso; ?>');">MATERIAL DEL CURSO</a></li>
                            <li><a data-toggle="tab" href="#M2-menu7" onclick="notas_curso('<?php echo $id_curso; ?>');">NOTAS</a></li>
                            <li><a data-toggle="tab" href="#M2-menu8" onclick="cert_culminacion('<?php echo $id_curso; ?>');">CERT. CULMINACION</a></li>
                            <li><a data-toggle="tab" href="#M2-menu9" onclick="sesion_zoom('<?php echo $id_curso; ?>');">ZOOM</a></li>
                            <li><a data-toggle="tab" href="#M2-menu10" onclick="acceso_simulador('<?php echo $id_curso; ?>');">SIMULADOR</a></li>
                            <li><a data-toggle="tab" href="#M2-menu11" onclick="envio_certificado_fisico('<?php echo $id_curso; ?>');">ENV&Iacute;O CERTIFICADO FISICO</a></li>
                            <li><a data-toggle="tab" href="#M2-menu12" onclick="modalidades_multiples('<?php echo $id_curso; ?>');">MODALIDADES MULTIPLES</a></li>
                        </ul>
                        <br>
                        <div class="tab-content">                                
                            <div id="M2-menu1" class="tab-pane fade in active">
                                <div class="panel panel-default">
                                    <div class="panel-heading">CERTIFICADOS - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">

                                        <table class="table table-striped table-bordered table-responsive">
                                            <?php
                                            $ids_certs_iniciales = $curso['id_certificado'].','.$curso['id_certificado_2'].','.$curso['id_certificado_3'];
                                            $cnt = 0;
                                                /* CERTIFICADOS INICIALES */
                                                $rqcertin1 = query("SELECT * FROM cursos_certificados WHERE id IN ($ids_certs_iniciales) OR id IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC )");
                                                if (num_rows($rqcertin1) == 0) {
                                                    echo "<tr><td colspan='5'><p>El curso actual no tiene certificados asociados.</p></td></tr>";
                                                }
                                                while ($rqdcrt2 = fetch($rqcertin1)) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$cnt; ?></td>
                                                        <td>
                                                            <?php
                                                            echo "<b>" . $rqdcrt2['codigo'] . "</b>";
                                                            echo "<br/>";
                                                            echo "<b>Texto QR</b> &nbsp; ".$rqdcrt2['texto_qr'];
                                                            echo "<br/>";
                                                            echo "<b>Fecha 1</b> &nbsp; ".date(" d / m / Y",strtotime($rqdcrt2['fecha_qr']));
                                                            echo "<br/>";
                                                            echo "<b>Fecha 2</b> &nbsp; ".date(" d / m / Y",strtotime($rqdcrt2['fecha2_qr']));
                                                            ?> 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($rqdcrt2['id_tipo_cert']=='1'){
                                                                echo "<b class='label label-info'>PARTICIPACI&Oacute;N</b>";
                                                            }else{
                                                                echo "<b class='label label-primary'>APROBACI&Oacute;N</b>";
                                                            }
                                                            ?> 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $rqdcrt2['cont_titulo_curso'];
                                                            echo "<br/>";
                                                            echo $rqdcrt2['cont_dos'];
                                                            echo "<br/>";
                                                            echo $rqdcrt2['cont_tres'];
                                                            ?> 
                                                        </td>
                                                        <td style="width:120px;">
                                                            <a data-toggle="modal" data-target="#MODAL-edita_certificado_general" class="btn btn-xs btn-info active" onclick="edita_certificado_general('<?php echo $rqdcrt2['id']; ?>');"><i class='fa fa-edit'></i> Editar</a>
                                                            <br>
                                                            <form action="" method="post" onsubmit="return confirm('La siguiente accion eliminara el certificado y todas sus emisiones DESEA PROCEDER ?');">
                                                                <input type="hidden" name="id_certificado" value="<?php echo $rqdcrt2['id']; ?>"/>
                                                                <input type="submit" name="eliminar-certificado" value="X Eliminar" class="btn btn-xs btn-danger"/>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                        </table>
                                        <p>
                                            Para agregar un nuevo certificado haga click en el siguiente bot&oacute;n:
                                        </p>
                                        <a class="btn btn-primary active" onclick="agregar_certificado();">
                                            <i class="fa fa-plus"></i> AGREGAR NUEVO CERTIFICADO
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu2" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">TURNOS AGREGADOS - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">

                                        <?php
                                        $rqmc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");

                                        if (num_rows($rqmc1) == 0) {
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
                                                while ($producto = fetch($rqmc1)) {
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
                                            while ($producto = fetch($resultado1)) {
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
                                    <div class="panel-heading">
                                        CURSO VIRTUAL - <?php echo $curso['titulo']; ?> 
                                    </div>
                                    <div class="panel-body">

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <table class="table table-striped table-bordered">
                                                <?php
                                                $rqmc1 = query("SELECT * FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' ORDER BY id DESC ");
                                                if (num_rows($rqmc1) == 0) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            El curso actual no tiene habilitado la modalidad de 'Curso en linea'. 
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                $cnt = 1;
                                                while ($rqmc2 = fetch($rqmc1)) {
                                                    $id_asignacion_onlinecourse = $rqmc2['id'];
                                                    $id_onlinecourse = $rqmc2['id_onlinecourse'];
                                                    $id_docente = $rqmc2['id_docente'];
                                                    $id_certificado = $rqmc2['id_certificado'];
                                                    $id_certificado_2 = $rqmc2['id_certificado_2'];

                                                    /* onlinecourse */
                                                    $rqdco1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
                                                    $rqdco2 = fetch($rqdco1);
                                                    ?>
                                                    <tr>
                                                        <td rowspan="9" class="text-center">
                                                            <h3>CV<?php echo $cnt++; ?></h3>
                                                        </td>
                                                        <td>
                                                            CURSO ASIGNADO:
                                                        </td>
                                                        <td>
                                                            <b style="font-size:11pt;"><?php echo $rqdco2['titulo']; ?></b>
                                                            <a class="btn btn-default btn-xs pull-right" href="cursos-virtuales-editar/<?php echo $id_onlinecourse; ?>.adm" target="_blank" title="EDICION DE DATOS DE CURSO VIRTUAL"><i class="fa fa-edit"></i></a>
                                                            <a class="btn btn-default btn-xs pull-right" href="cursos-virtuales-lecciones/<?php echo $id_onlinecourse; ?>.adm" target="_blank" title="EDICION DE LECCIONES DE CURSO VIRTUAL"><i class="fa fa-list"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            INGRESO DE PARTICIPANTES: 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($rqmc2['estado'] == '1') {
                                                                ?>
                                                                <b style='color:green;'>HABILITADO</b>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <b style='color:red;'>DESHABILITADO</b>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            FECHA INICIO: 
                                                        </td>
                                                        <td>
                                                            <?php echo date("d/m/Y", strtotime($rqmc2['fecha_inicio'])); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            FECHA FINAL: 
                                                        </td>
                                                        <td>
                                                            <?php echo date("d/m/Y", strtotime($rqmc2['fecha_final'])); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            DOCENTE ASIGNADO: 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $rqdd1 = query("SELECT nombres,apellidos FROM cursos_docentes WHERE id='$id_docente' LIMIT 1 ");
                                                            if (num_rows($rqdd1) > 0) {
                                                                $rqdd2 = fetch($rqdd1);
                                                                echo $rqdd2['nombres'] . " " . $rqdd2['apellidos'];
                                                            } else {
                                                                echo "<b>Sin docente asignado</b> ";
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            1er CERTIFICADO ASIGNADO: 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($id_certificado=='0'){
                                                                echo "<b>Sin certificado asignado</b> ";
                                                            }else{
                                                                $rqdc1 = query("SELECT texto_qr FROM cursos_certificados WHERE id='$id_certificado' LIMIT 1 ");
                                                                $rqdc2 = fetch($rqdc1);
                                                                echo $rqdc2['texto_qr'];
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2do CERTIFICADO ASIGNADO: 
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($id_certificado_2=='0'){
                                                                echo "<b>Sin certificado asignado</b> ";
                                                            }else{
                                                                $rqdc1 = query("SELECT texto_qr FROM cursos_certificados WHERE id='$id_certificado_2' LIMIT 1 ");
                                                                $rqdc2 = fetch($rqdc1);
                                                                echo $rqdc2['texto_qr'];
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            C&Oacute;DIGO DE ASISTENCIA:
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($rqmc2['sw_cod_asistencia'] == '1') {
                                                            ?>
                                                                <label>
                                                                    <input type="radio" value="1" name="sw_cod_asistencia" checked=""/> SI
                                                                </label>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <label>
                                                                    <input type="radio" value="0" name="sw_cod_asistencia" /> NO
                                                                </label>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <label>
                                                                    <input type="radio" value="1" name="sw_cod_asistencia" /> SI
                                                                </label>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <label>
                                                                    <input type="radio" value="0" name="sw_cod_asistencia" checked="" /> NO
                                                                </label>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <a class="btn btn-primary active btn-block" data-toggle="modal" data-target="#MODAL-modificar_asignacion_onlinecourse" onclick="modificar_asignacion_onlinecourse('<?php echo $id_asignacion_onlinecourse; ?>');">
                                                                <i class="fa fa-edit"></i> MODIFICAR ASIGNACI&Oacute;N
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <a class="btn btn-danger btn-xs" data-toggle="modal" onclick="eliminar_asignacion_onlinecourse('<?php echo $id_asignacion_onlinecourse; ?>');">
                                                                <i class="fa fa-edit"></i> ELIMINAR ASIGNACI&Oacute;N
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        <br>
                                                        <br>
                                                        <a class="btn btn-warning btn-lg" onclick="agregar_curso_virtual();"><i class="fa fa-plus"></i> AGREGAR CURSO VIRTUAL</a>
                                                        <br>
                                                        <br>
                                                        <br>
                                                    </td>
                                                </tr>
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
                                        <div class="panel-heading">CUPONES PAQUETES INFOSISCON - <?php echo $curso['titulo']; ?></div>
                                        <div class="panel-body">
                                            <?php
                                            $rqdc1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1");
                                            if (num_rows($rqdc1) == 0) {
                                                ?>
                                                <div class="alert alert-info">
                                                    <strong>Info!</strong> El curso no tiene asignado ningun cup&oacute;n de paquetes Infosiscon
                                                </div>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <td>
                                                                Paquete Infosiscon:
                                                            </td>
                                                            <td>
                                                                <select name="id_paquete" class="form-control form-cascade-control">
                                                                    <option value="2" style="padding:4px;">PAQUETE PyME</option>
                                                                    <option value="3" style="padding:4px;">PAQUETE BASICO</option>
                                                                    <option value="4" style="padding:4px;">PAQUETE MEDIO</option>
                                                                    <option value="5" style="padding:4px;">PAQUETE INTERMEDIO</option>
                                                                    <option value="6" style="padding:4px;">PAQUETE EMPRESARIAL</option>
                                                                    <option value="7" style="padding:4px;">PAQUETE COORPORATIVO</option>
                                                                    <option value="10" style="padding:4px;">PAQUETE Consultor - BASICO</option>
                                                                    <option value="11" style="padding:4px;">PAQUETE Consultor - GOLD</option>
                                                                    <option value="12" style="padding:4px;">PAQUETE Consultor - PREMIUM</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Duraci&oacute;n :
                                                            </td>
                                                            <td>
                                                                <select name="duracion" class="form-control form-cascade-control">
                                                                    <option value="3" style="padding:4px;">3 MESES</option>
                                                                    <option value="4" style="padding:4px;">4 MESES</option>
                                                                    <option value="6" style="padding:4px;" selected="selected">6 MESES</option>
                                                                    <option value="9" style="padding:4px;">9 MESES</option>
                                                                    <option value="12" style="padding:4px;">1 A&Ntilde;O</option>
                                                                </select>
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
                                                                <input type="submit" class="btn btn-success active btn-block" name="asignar-cupon-infosicoes" value="ASIGNAR CUP&Oacute;N"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                                <?php
                                            } else {
                                                $rqdc2 = fetch($rqdc1);
                                                ?>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td>
                                                            Paquete Infosiscon :
                                                        </td>
                                                        <td>
                                                            <?php
                                                            switch ($rqdc2['id_paquete']) {
                                                                case '2':
                                                                    $txt_nompaq = "PAQUETE PyME";
                                                                    break;
                                                                case '3':
                                                                    $txt_nompaq = "PAQUETE BASICO";
                                                                    break;
                                                                case '4':
                                                                    $txt_nompaq = "PAQUETE MEDIO";
                                                                    break;
                                                                case '5':
                                                                    $txt_nompaq = "PAQUETE INTERMEDIO";
                                                                    break;
                                                                case '6':
                                                                    $txt_nompaq = "PAQUETE EMPRESARIAL";
                                                                    break;
                                                                case '7':
                                                                    $txt_nompaq = "PAQUETE COORPORATIVO";
                                                                    break;
                                                                case '10':
                                                                    $txt_nompaq = "PAQUETE Consultor - BASICO";
                                                                    break;
                                                                case '11':
                                                                    $txt_nompaq = "PAQUETE Consultor - GOLD";
                                                                    break;
                                                                case '12':
                                                                    $txt_nompaq = "PAQUETE Consultor - PREMIUM";
                                                                    break;
                                                                default:
                                                                    $txt_nompaq = '';
                                                                    break;
                                                            }
                                                            ?>
                                                            <input type="text" class="form-control" disabled value="<?php echo $txt_nompaq; ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Duraci&oacute;n :
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" disabled value="<?php echo $rqdc2['duracion'] . ' MESES'; ?>"/>
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
                                                                                Paquete Infosiscon :
                                                                            </td>
                                                                            <td>
                                                                                <select name="id_paquete" class="form-control form-cascade-control">
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '2') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="2" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE PyME</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '3') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="3" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE BASICO</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '4') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="4" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE MEDIO</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '5') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="5" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE INTERMEDIO</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '6') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="6" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE EMPRESARIAL</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '7') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="7" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE COORPORATIVO</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '10') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="10" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE Consultor - BASICO</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '11') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="11" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE Consultor - GOLD</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['id_paquete'] == '12') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="12" style="padding:4px;" <?php echo $txt_selected; ?>>PAQUETE Consultor - PREMIUM</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                Duraci&oacute;n :
                                                                            </td>
                                                                            <td>
                                                                                <select name="duracion" class="form-control form-cascade-control">
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '3') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="3" style="padding:4px;" <?php echo $txt_selected; ?>>3 MESES</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '4') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="4" style="padding:4px;" <?php echo $txt_selected; ?>>4 MESES</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '6') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="6" style="padding:4px;" <?php echo $txt_selected; ?>>6 MESES</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '9') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="9" style="padding:4px;" <?php echo $txt_selected; ?>>9 MESES</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '12') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="12" style="padding:4px;" <?php echo $txt_selected; ?>>1 A&Ntilde;O</option>
                                                                                    <?php
                                                                                    $txt_selected = '';
                                                                                    if ($rqdc2['duracion'] == '14') {
                                                                                        $txt_selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="14" style="padding:4px;" <?php echo $txt_selected; ?>>1 A&Ntilde;O Y 2 Meses</option>
                                                                                </select>
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
                                                                                <input type="submit" class="btn btn-success active btn-block" name="actualizar-cupon-infosicoes" value="ACTUALIZAR CUP&Oacute;N"/>
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
                            <div id="M2-menu5" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">CURSOS GRATUITOS ASOCIADOS - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-cursos_gratuitos">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu6" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">MATERIAL DEL CURSO - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-material_curso">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu7" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">NOTAS - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-notas_curso">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu8" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">CERT. CULMINACION - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-cert_culminacion">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu9" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">SESION DE ZOOM - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-sesion_zoom">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu10" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">ACCESO AL SIMULADOR - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-acceso_simulador">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu11" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">ENV&Iacute;O CERTIFICADO FISICO - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-envio_certificado_fisico">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                            <div id="M2-menu12" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-heading">MODALIDADES MULTIPLES - <?php echo $curso['titulo']; ?></div>
                                    <div class="panel-body">
                                        <div id="AJAXCONTENT-modalidades_multiples">Cargando...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br/>
                <hr/>
                <br/>




            </div>
        </div>
    </div>
</div>




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
<div id="MODAL-modificar_asignacion_onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MODIFICACI&Oacute;N DE ASIGNACI&Oacute;N  DE CURSO VIRTUAL</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXBOX-modificar_asignacion_onlinecourse"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal - habilitar online course -->


<!-- Modal-edita_certificado_general -->
<div id="MODAL-edita_certificado_general" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXBOX-edita_certificado_general"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-edita_certificado_general -->

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
            data: {id_curso: '<?php echo $id_curso?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-whatsapp_numeros").html(data);
            }
        });
    }
</script>

<!-- AJAX modificar_asignacion_onlinecourse -->
<script>
    function edita_certificado_general(id_certificado) {
        $("#AJAXBOX-edita_certificado_general").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.edita_certificado_general_p1.php',
            data: {id_certificado: id_certificado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-edita_certificado_general").html(data);
            }
        });
    }
</script>

<!--cursos_gratuitos-->
<script>
    function cursos_gratuitos(id_curso) {
        //$("#AJAXCONTENT-cursos_gratuitos").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cursos_gratuitos.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_gratuitos").html(data);
            }
        });
    }
</script>

<!--material_curso-->
<script>
    function material_curso(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.material_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-material_curso").html(data);
            }
        });
    }
</script>

<!--cert_culminacion-->
<script>
    function cert_culminacion(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cert_culminacion.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cert_culminacion").html(data);
            }
        });
    }
</script>

<!--sesion_zoom-->
<script>
    function sesion_zoom(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.sesion_zoom.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-sesion_zoom").html(data);
            }
        });
    }
</script>

<!--acceso_simulador-->
<script>
    function acceso_simulador(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.acceso_simulador.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-acceso_simulador").html(data);
            }
        });
    }
</script>

<!--envio_certificado_fisico-->
<script>
    function envio_certificado_fisico(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.envio_certificado_fisico.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-envio_certificado_fisico").html(data);
            }
        });
    }
</script>

<!--modalidades_multiples-->
<script>
    function modalidades_multiples(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.modalidades_multiples.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modalidades_multiples").html(data);
            }
        });
    }
</script>

<!--notas_curso-->
<script>
    function notas_curso(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.notas_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-notas_curso").html(data);
            }
        });
    }
</script>


<!-- Modal -->
<div id="MODAL-cursos_gratuitos_agregar" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNAR CURSO GRATUITO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-cursos_gratuitos_agregar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
            data: {tag: dat, id_curso: '<?php echo $id_curso; ?>'},
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
            data: {id_tag: dat, id_curso: '<?php echo $id_curso; ?>'},
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
            data: {id_tag: dat, id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#tr-tag-" + dat).css('display', 'none');
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#box-cont-estado").html("<div class='col-md-3'>Actualizando...</div>");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cambiar_estado_curso.php',
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

<!-- eliminar_asignacion_onlinecourse -->
<script>
    function eliminar_asignacion_onlinecourse(id_asignacion_onlinecourse) {
        if(confirm('ESTA SEGURO DE ELIMINAR LA ASIGNACION ?, SE BORRARAN TODAS LAS ACTIVACIONES Y ACCESOS DE ESTE CURSO VIRTUAL')){
            $("#TITLE-modgeneral").html('ELIMINACI&Oacute;N DE CURSO VIRTUAL');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-editar.eliminar_asignacion_onlinecourse.php',
                data: {id_asignacion_onlinecourse: id_asignacion_onlinecourse},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    location.href='cursos-editar/<?php echo $id_curso; ?>.adm';
                }
            });
        }
    }
</script>

<!-- AJAX edicion_tituloFechaCostos -->
<script>
    function edicion_tituloFechaCostos() {
        var formdata = $("#FORM-edicion_tituloFechaCostos").serialize();
        $("#AJAXBOX-edicion_tituloFechaCostos").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.edicion_tituloFechaCostos.php',
            data: formdata,
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-edicion_tituloFechaCostos").html(data);
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

<!-- confirmar_edicion -->
<script>
    function confirmar_edicion() {
        $("#aux_select_docente").val($("#select_docente").val());
        $("#box-confirmacion-edicion").css('display','block');
    }
</script>



<!-- agregar_certificado -->
<script>
    function agregar_certificado() {
        $("#TITLE-modgeneral").html('ASIGNACI&Oacute;N DE CERTIFICADO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.agregar_certificado.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<!-- agregar_curso_virtual -->
<script>
    function agregar_curso_virtual() {
        $("#TITLE-modgeneral").html('AGREGAR CURSO VIRTUAL');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.agregar_curso_virtual.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- conf_bancos -->
<script>
    function conf_bancos() {
        $("#AJAXCONTENT-panel-m5").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.conf_bancos.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-panel-m5").html(data);
            }
        });
    }
</script>


<!-- asignar_enlace -->
<script>
    function asignar_enlace() {
        $("#TITLE-modgeneral").html('ASIGNAR ENLACE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.asignar_enlace.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
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

