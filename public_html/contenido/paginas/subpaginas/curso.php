<?php
/* datos de configuracion */
$sw_mostrar_precios = $__CONFIG_MANAGER->getSw('sw_mostrar_precios');
/* curso */
$titulo_identificador_curso = $get[2];
$rqdc1 = query("SELECT * FROM cursos WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (0,1,2) ORDER BY FIELD(estado,1,2,0),id DESC limit 1 ");
if (num_rows($rqdc1) == 0) {
    echo "<script>alert('Curso no encontrado');location.href='$dominio';</script>";
    exit;
}
$curso = fetch($rqdc1);

$id_curso = $curso['id'];
$titulo_curso = $curso['titulo'];
$duracion_curso = $curso['horarios'];
if ($duracion_curso == '') {
    $duracion_curso = '4 Hrs.';
}
$modalidad_curso = "Presencial";
if ($curso['id_modalidad'] == '2') {
    $modalidad_curso = "Virtual";
} elseif ($curso['id_modalidad'] == '3' ) {
    //$modalidad_curso = "Semi-presencial";
    $modalidad_curso = "VIRTUAL";
} elseif ($curso['id_modalidad'] == '4') {
    //$modalidad_curso = "Semi-presencial";
    $modalidad_curso = "VIRTUAL EN VIVO";
}

/* datos lugar */
$rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
$rqdl2 = fetch($rqdl1);
$lugar_nombre = $rqdl2['nombre'];
$lugar_salon = $rqdl2['salon'];
$lugar_direccion = $rqdl2['direccion'];
$lugar_google_maps = $rqdl2['google_maps'];

/* ciudad departemento */
$curso_id_ciudad = $curso['id_ciudad'];
$rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
$rqdcd2 = fetch($rqdcd1);
$curso_nombre_departamento = $rqdcd2['departamento'];
$curso_nombre_ciudad = $rqdcd2['ciudad'];
$curso_text_ciudad = $curso_nombre_ciudad;
if ($curso_nombre_departamento !== $curso_nombre_ciudad) {
    $curso_text_ciudad = $curso_nombre_ciudad . ' - ' . $curso_nombre_departamento;
}

/* imagen principal del curso */
$url_imagen_principal = $dominio_www."contenido/imagenes/paginas/large-" . $curso['imagen'];



$htm_imagen1 = '';
if ($curso['imagen'] !== '') {
    $url_img = "contenido/imagenes/paginas/medium-" . $curso['imagen'];
    if (file_exists($url_img)) {
        $url_img = $dominio . "contenido/imagenes/paginas/medium-" . $curso['imagen'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/imagenes/paginas/" . $curso['imagen'];
    }
    $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}

$htm_imagen2 = '';
if ($curso['imagen2'] !== '') {
    $url_img = "contenido/imagenes/paginas/medium-" . $curso['imagen2'];
    if (file_exists($url_img)) {
        $url_img = $dominio . "contenido/imagenes/paginas/medium-" . $curso['imagen2'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/imagenes/paginas/" . $curso['imagen2'];
    }
    $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen3 = '';
if ($curso['imagen3'] !== '') {
    $url_img = "contenido/imagenes/paginas/medium-" . $curso['imagen3'];
    if (file_exists($url_img)) {
        $url_img = $dominio . "contenido/imagenes/paginas/medium-" . $curso['imagen3'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/imagenes/paginas/" . $curso['imagen3'];
    }
    $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen4 = '';
if ($curso['imagen4'] !== '') {
    $url_img = "contenido/imagenes/paginas/medium-" . $curso['imagen4'];
    if (file_exists($url_img)) {
        $url_img = $dominio . "contenido/imagenes/paginas/medium-" . $curso['imagen4'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/imagenes/paginas/" . $curso['imagen4'];
    }
    $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_archivo1 = '';
if ($curso['archivo1'] !== '') {
    if (file_exists("contenido/archivos/cursos/" . $curso['archivo1'])) {
        $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo1'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/archivos/cursos/" . $curso['archivo1'];
    }
    $htm_archivo1 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo1'] . '</a>';
}
$htm_archivo2 = '';
if ($curso['archivo2'] !== '') {
    if (file_exists("contenido/archivos/cursos/" . $curso['archivo2'])) {
        $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo2'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/archivos/cursos/" . $curso['archivo2'];
    }
    $htm_archivo2 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo2'] . '</a>';
}
$htm_archivo3 = '';
if ($curso['archivo3'] !== '') {
    if (file_exists("contenido/archivos/cursos/" . $curso['archivo3'])) {
        $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo3'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/archivos/cursos/" . $curso['archivo3'];
    }
    $htm_archivo3 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo3'] . '</a>';
}
$htm_archivo4 = '';
if ($curso['archivo4'] !== '') {
    if (file_exists("contenido/archivos/cursos/" . $curso['archivo4'])) {
        $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo4'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/archivos/cursos/" . $curso['archivo4'];
    }
    $htm_archivo4 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo4'] . '</a>';
}
$htm_archivo5 = '';
if ($curso['archivo5'] !== '') {
    if (file_exists("contenido/archivos/cursos/" . $curso['archivo5'])) {
        $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo5'];
    } else {
        $url_img = "https://www.infosiscon.com/" . "contenido/archivos/cursos/" . $curso['archivo5'];
    }
    $htm_archivo5 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo5'] . '</a>';
}
$htm_reportesupago = '<a href="'.$dominio.'registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="'.$dominio.'contenido/imagenes/images/reporte-su-pago.png" style=""/></a>';
$htm_inscripcion = '<div style="text-align:center;"><a href="'.$dominio.'registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="https://www.carreramenudoscorazones.es/wp-content/uploads/2015/04/BOTON_INSCRIPCION.jpg" style="height:120px;"/></a></div>';
$htm_whatsapp = "<div style='text-align:center;'><a href='https://api.whatsapp.com/send?phone=" . $curso['whats_numero'] . "&amp;text=" . str_replace("'", "", str_replace(' ', '%20', str_replace('&', 'y', $curso['whats_mensaje']))) . "'><img src='https://www.infosiscon.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style='height:120px;'/></a></div>";
$data_nombre_curso = $curso['titulo'];
$data_ciudad_curso = $curso_text_ciudad;
$data_fecha_curso = fecha_curso_D_d_m($curso['fecha']);
$data_horarios_curso = $curso['horarios'];
$data_lugar_curso = $lugar_nombre;
$data_lugar_salon_curso = $lugar_nombre . ' - ' . $lugar_salon;
$data_direccion_lugar_curso = $lugar_direccion;
$data_costo_bs_curso = $curso['costo'] . ' Bs';
$data_costo_literal_curso = numtoletras($curso['costo']);
$txt_descuento_uno_curso = '';
$txt_descuento_dos_curso = '';
$txt_descuento_est_curso = '';
$txt_descuento_est_pre_curso = '';
if ($curso['sw_fecha2'] == '1') {
    $txt_descuento_uno_curso = $curso['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha2']);
}
if ($curso['sw_fecha3'] == '1') {
    $txt_descuento_dos_curso = $curso['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha3']);
}
if ($curso['sw_fecha_e'] == '1') {
    $txt_descuento_est_curso = $curso['costo_e'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e']) . ' (Estudiantes)';
}
if ($curso['sw_fecha_e2'] == '1') {
    $txt_descuento_est_pre_curso = $curso['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e2']) . ' (Estudiantes)';
}
/* palabras reservadas */
$array_palabras_reservadas_busc = array(
    '[imagen-1]',
    '[imagen-2]',
    '[imagen-3]',
    '[imagen-4]',
    'src="/paginas',
    'registro-curso-infosicoes',
    '[ARCHIVO-1]',
    '[ARCHIVO-2]',
    '[ARCHIVO-3]',
    '[ARCHIVO-4]',
    '[ARCHIVO-5]',
    '[REPORTE-SU-PAGO]',
    '[INSCRIPCION]',
    '[WHATSAPP]',
    '[NOMBRE-CURSO]',
    '[CIUDAD-CURSO]',
    '[FECHA-A1-CURSO]',
    '[HORARIOS]',
    '[LUGAR-CURSO]',
    '[LUGAR-SALON-CURSO]',
    '[DIRECCION-LUGAR]',
    '[COSTO-BS]',
    '[COSTO-LITERAL]',
    '[DESCUENTO-UNO]',
    '[DESCUENTO-DOS]',
    '[DESCUENTO-ESTUDIANTES]',
    '[DESCUENTO-ESTUDIANTES-PRE]'
);
$array_palabras_reservadas_remm = array(
    $htm_imagen1,
    $htm_imagen2,
    $htm_imagen3,
    $htm_imagen4,
    'src="https://www.infosiscon.com/paginas',
    'registro-curso',
    $htm_archivo1,
    $htm_archivo2,
    $htm_archivo3,
    $htm_archivo4,
    $htm_archivo5,
    $htm_reportesupago,
    $htm_inscripcion,
    $htm_whatsapp,
    $data_nombre_curso,
    $data_ciudad_curso,
    $data_fecha_curso,
    $data_horarios_curso,
    $data_lugar_curso,
    $data_lugar_salon_curso,
    $data_direccion_lugar_curso,
    $data_costo_bs_curso,
    $data_costo_literal_curso,
    $txt_descuento_uno_curso,
    $txt_descuento_dos_curso,
    $txt_descuento_est_curso,
    $txt_descuento_est_pre_curso
);

//$contenido_curso = trim(str_replace($array_palabras_reservadas_busc, $array_palabras_reservadas_remm, $curso['contenido']));
$contenido_curso = getContenidoCurso($curso, $sw_mostrar_precios);

/* costo */
$costo = $curso['costo'];
//if ($curso['sw_fecha2'] == '1' && (date("Y-m-d") <= $curso['fecha2'])) {
//    $costo = $curso['costo2'];
//}
//if ($curso['sw_fecha3'] == '1' && (date("Y-m-d") <= $curso['fecha3'])) {
//    $costo = $curso['costo3'];
//}
$sw_descuento_costo2 = false;
$f_h = date("H:i", strtotime($curso['fecha2']));
if ($f_h !== '00:00') {
    $f_actual = strtotime(date("Y-m-d H:i"));
    $f_limite = strtotime($curso['fecha2']);
} else {
    $f_actual = strtotime(date("Y-m-d"));
    $f_limite = strtotime(substr($curso['fecha2'], 0, 10));
    
    $f_limite_aux = strtotime($curso['fecha2']);
}
if ($curso['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
    $sw_descuento_costo2 = true;
    $costo2 = $curso['costo2'];
}
$sw_descuento_costo3 = false;
if ($curso['sw_fecha3'] == '1' && ( date("Y-m-d") <= $curso['fecha3'])) {
    $sw_descuento_costo3 = true;
    $costo3 = $curso['costo3'];
}
$sw_descuento_costo_e2 = false;
if ($curso['sw_fecha_e2'] == '1' && (date("Y-m-d") <= $curso['fecha_e2'])) {
    $sw_descuento_costo_e2 = true;
    $costo_e2 = $curso['costo_e2'];
}


/* fecha de inicio */
$arf1 = explode('-', $curso['fecha']);
$arf2 = date("N", strtotime($curso['fecha']));
$array_dias = array('none', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$fecha_de_inicio = $arf1[2] . " de " . $array_meses[(int) $arf1[1]] . " de " . $arf1[0];
$dia_de_inicio = $array_dias[$arf2];

/* compartir por email */
if (isset_post('email') && isset_post('nombre')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }
    if (($response != null && $response->success) || true) {
        $bad = array("content-type", "bcc:", "to:", "cc:");
        $email = str_replace($bad, "", post('email'));
        $recomendado = str_replace($bad, "", post('nombre'));
        $contenido_correo = platillaEmailUno($contenido_curso."<hr/><b>Curso recomendado por:</b> $recomendado<br/><b>Enviado a:</b> $email<br/>",$data_nombre_curso,$email,urlUnsubscribe($email),'usuario');
        $asunto = "$data_nombre_curso, recomendado por $recomendado"; // El asunto del mensaje

        /*
        mail($email, $asunto, $contenido_correo, $cabeceras);
        mail("brayan.desteco@gmail.com", $asunto, $contenido_correo, $cabeceras);
        movimiento('Curso enviado por email [' . $email . ']', 'share-email', 'curso', $id_curso);
        */
    } else {
        echo "<script>alert('Verifica que no eres un robot');history.back();</script>";
    }
}


/* url corta */
$url_corta = $dominio.numIDcurso($id_curso) . '/';
//$url_registro = $dominio.'registro-curso/'.$titulo_identificador_curso.'.html';
$url_registro = $dominio.'R/'.$id_curso.'/';
$rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$id_curso."' AND r.estado=1 ");
if(num_rows($rqenc1)>0){
    $rqenc2 = fetch($rqenc1);
    $url_corta = $dominio.$rqenc2['enlace'] . "/";
    //$url_registro = $dominio.'R/'.$rqenc2['enlace'] . "/";
    $url_registro = $dominio.'R/'.$id_curso . "/";
}

/* numero tigomoney */
$qrtm1 = query("SELECT n.numero FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros n ON r.id_numtigomoney=n.id WHERE r.id_curso='$id_curso' AND r.sw_numprin=1 AND n.estado=1 ");
$numero_tigomoney = "69714008";
if(num_rows($qrtm1)>0){
    $qrtm2 = fetch($qrtm1);
    $numero_tigomoney = $qrtm2['numero'];
}
?>



<style>
    .img-pag-static{
        max-width: 90%;
        border-radius: 5px;
        border: 1px solid #dadada;
        padding: 1px;
    }
    .btn-copy-clipboard {
        background: #eff2fc;
        padding: 2px 7px;
        border-radius: 4px;
        border: 1px solid #a1b7c2;
        color: #7d7d8e;
        box-shadow: 1px 1px 1px 0px #bebebe;
        cursor: pointer;
    }

    @media (max-width: 700px) {
        .TituloContenidoCursosinLinea h1 {
            font-size: 14pt !important;
            text-align: center;
            line-height: 1.2;
        }
    }
</style>

<div class="wrapsemibox course-self" style="margin-top: 60px;">

    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-9" style="padding-right:20px"><br>
                <div id="">
                    <div class="TituloContenidoCursosinLinea">
                        <h1><?php echo $titulo_curso; ?></h1>
                    </div>
                    <div>
                        <img src="<?php echo $url_imagen_principal; ?>" style="width: 100%;border: 1px solid #d4d4d4;margin: 10px 0px;" title="<?php echo $titulo_curso; ?>"/>
                    </div>
                    <div style="height:5px"></div>
                    <div style=" background-color:#F6F6F6;padding:13px 10px 7px 5px; border-bottom:1px solid #999; border-top:1px solid #999;">  
                        <div class="row">

                            <div class="col-md-6">
                                <?php if ($curso['id_modalidad'] !== '2') { ?>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-3 Titulo_texto"> Fecha</div>
                                        <div class="col-md-9 col-xs-9 Titulo_texto1"> <strong>:</strong>&nbsp; <?php echo $dia_de_inicio . ', ' . $fecha_de_inicio; ?></div>
                                        <div style="height:25px"></div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Duraci&oacute;n</div>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"><strong>:</strong>&nbsp; <?php echo $duracion_curso; ?></div>
                                    <div style="height:25px"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Modalidad</div>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<?php echo $modalidad_curso; ?></div>
                                    <div style="height:25px"></div>
                                </div>

                                <?php
                                if ($curso['estado'] !== '0') {
                                ?>
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Sitio web</div>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp; <b class="btn-copy-clipboard" onclick="copyToClipboard('<?php echo $url_corta; ?>')"><i class="fa fa-copy"></i></b> &nbsp; <a href='<?php echo $url_corta; ?>'><?php echo $url_corta; ?></a></div>
                                    <div style="height:25px"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Registro</div>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp; <b class="btn-copy-clipboard" onclick="copyToClipboard('<?php echo $url_registro; ?>')"><i class="fa fa-copy"></i></b> &nbsp; <a href='<?php echo $url_registro; ?>'><?php echo $url_registro; ?></a></div>
                                    <div style="height:25px"></div>
                                </div>
                                <?php
                                }
                                ?>

                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Whatsapp</div>
                                    <?php 
                                    $mensaje_wamsm_predeternimado = 'Hola, tengo interes en el curso: ' . trim($curso['titulo_formal']);
                                    $mensaje_wamsm = str_replace('+','%20',urlencode($mensaje_wamsm_predeternimado));
                                    $rqdwn1 = query("SELECT w.numero,w.wap_shortlink FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='".$curso['id']."' ORDER BY r.id ASC LIMIT 1 ");
                                    $numero_wamsm_predeternimado = '69714008';
                                    $cel_wamsm = '591'.$numero_wamsm_predeternimado;
                                    $wap_shortlink = 'https://wa.me/59169714008';
                                    if(num_rows($rqdwn1)>0){
                                        $rqdwn2 = fetch($rqdwn1);
                                        $cel_wamsm = '591'.$rqdwn2['numero'];
                                        $wap_shortlink = 'https://wa.me/591'.$rqdwn2['numero'];
                                    }
                                    ?>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp; <b class="btn-copy-clipboard" onclick="copyToClipboard('<?php echo $wap_shortlink; ?>')"><i class="fa fa-copy"></i></b> &nbsp; <a href='<?php echo $wap_shortlink; ?>?text=<?php echo $mensaje_wamsm; ?>' target="_blank"><?php echo $wap_shortlink; ?></a></div>
                                    <div style="height:25px"></div>
                                </div>
                                <?php if ($curso['id_modalidad'] !== '2' && $curso['id_modalidad'] !== '3' && $curso['id_modalidad'] !== '4') { ?>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-3 Titulo_texto">Ciudad</div>
                                        <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<?php echo $curso_text_ciudad; ?></div>
                                        <div style="height:25px"></div>
                                    </div>
                                    <?php
                                    if ($curso['estado'] !== '0') {
                                        ?>
                                        <?php
                                        if ($lugar_nombre !== '') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-3 col-xs-3 Titulo_texto">Lugar</div>
                                                <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<?php echo $lugar_nombre; ?></div>
                                                <div style="height:25px"></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($lugar_salon !== '') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-3 col-xs-3 Titulo_texto">Sal&oacute;n</div>
                                                <div class="col-md-9 col-xs-9 Titulo_texto1"><?php
                                                    if ($lugar_salon == '') {
                                                        echo ": &nbsp;verificar en detalles";
                                                    } else {
                                                        echo ": &nbsp;" . $lugar_salon;
                                                    }
                                                    ?>
                                                </div>
                                                <div style="height:25px"></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($lugar_direccion !== '') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-3 col-xs-3 Titulo_texto">Direcci&oacute;n</div>
                                                <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<?php echo $lugar_direccion; ?></div>
                                                <div style="height:25px"></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    <?php } ?>

                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                            if ($curso['estado'] !== '0') {
                                ?>
                                <div class="col-md-6">
                                    <?php
                                     if($sw_mostrar_precios){
                                            if ((int) $costo > 0) {
                                            ?>
                                            <div class="row">
                                        <div class="col-md-3 col-xs-3 Titulo_texto">Inversi&oacute;n</div>
                                        <div class="col-md-9 col-xs-9 Titulo_texto1"> 
                                            <strong>:</strong>&nbsp; <?php echo $costo; ?> Bs.
                                        </div> 
                                        <div style="height:25px"></div>
                                    </div>
                                            <?php
                                            }else{
                                                ?>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-3 Titulo_texto" style="padding-top: 5px;">Ingreso</div>
                                        <div class="col-md-9 col-xs-9 Titulo_texto1" style="padding-top: 5px;">: GRATUITO<br/>con c&eacute;dula de identidad</div> 
                                        <div style="height:25px"></div>
                                    </div>
                                    <?php
                                            }
                                            ?>
                                    <?php
                                    /* precio estudiantes */
                                    if ($curso['sw_fecha_e'] == '1' && (date("Y-m-d") <= $curso['fecha_e'])) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Estudiantes</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1"> <strong>:</strong>&nbsp; <?php echo $curso['costo_e']; ?> Bs.</div> 
                                            <div style="height:25px"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="row">
                                        <?php
                                        if ($sw_descuento_costo2) {
                                            ?>
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Descuento</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1">
                                                <div style="background:#FFF;color:#005982;border-radius: 3px;padding: 3px;margin:2px 7px 2px 0px;border-left: 1px solid #adadad;padding-left: 10px;">
                                                    <b style='color:#439a43;font-size:8pt;'>POR PAGO ANTICIPADO</b>
                                                    <br/>
                                                    Inversi&oacute;n: <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha2']); ?></span>
                                                    <?php
                                                    if ($sw_descuento_costo3) {
                                                        ?>
                                                        <br/>
                                                        Inversi&oacute;n: <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha3']); ?></span>
                                                        <?php
                                                    }
                                                    if ($sw_descuento_costo_e2) {
                                                        ?>
                                                        <br/>
                                                        Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha_e2']); ?></span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div style="height:25px"></div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php }
                                        if ((int) $costo > 0) {
                                            $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                                            $rqdtcb2 = fetch($rqdtcb1);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-3 col-xs-3 Titulo_texto">Pagos</div>
                                                <div class="col-md-9 col-xs-9 Titulo_texto1">
                                                    <strong>:</strong>&nbsp; <?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?><br/> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?>
                                                    <br/>
                                                    <strong>:</strong>&nbsp; Pago por TigoMoney <?php echo $numero_tigomoney; ?> (sin recargo)
                                                </div> 
                                                <div style="height:25px"></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 ">
                                            <br/>
                                            <span class="pull-right">
                                                <b class="btn btn-xs btn-info" onclick="copyToClipboard();" title="Copiar informaci&oacute;n al clipboard."><i class="icon-copy text-contrast"></i></b>
                                                &nbsp;
                                                <b class="btn btn-xs btn-info" onclick="shareFacebook();" title="Compartir en Facebook."><i class="icon-facebook text-contrast"></i></b>
                                                &nbsp;
                                                <b class="btn btn-xs btn-info" title="Enviar por Email." data-toggle="modal" data-target="#MODAL-shareEmail"><i class="icon-envelope text-contrast"></i></b>
                                            </span>
                                        </div>
                                        <div style="height:25px"></div>
                                    </div>

                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div style="height:20px"></div>

                </div>
                
                
                
                <div id="contentInfo" style="display:none;">
                    <div>*<?php echo $titulo_curso; ?>*</div>
                    <div><br></div>
                    <?php if ($curso['id_modalidad'] !== '2') { ?>
                    <div>*Fecha:* &nbsp; <?php echo $dia_de_inicio . ', ' . $fecha_de_inicio; ?></div>
                    <div><br></div>
                    <?php } ?>
                    <div>*Duraci&oacute;n:* &nbsp; <?php echo $duracion_curso; ?></div>
                    <div><br></div>
                    <?php if ($curso['id_modalidad'] == '3' || $curso['id_modalidad'] == '4') { ?>
                    <div>*Modalidad:* &nbsp;Online mediante ZOOM</div>
                    <?php }else{ ?>
                    <div>*Modalidad:* &nbsp;<?php echo $modalidad_curso; ?></div>
                    <?php } ?>
                    <div><br></div>
                    <div>*Detalle completo del curso:* &nbsp; <?php echo $dominio .numIDcurso($id_curso) . '/'; ?></div>
                    <div><br></div>
                    <?php if ($curso['estado'] !== '0') { ?>
                    <div>
                    <?php if ((int) $costo > 0) { ?>
                    *Inversi&oacute;n:* &nbsp; <?php echo $costo; ?> Bs.
                    <div><br></div>
                    <?php }else{ ?>
                    *Ingreso:* GRATUITO con c&eacute;dula de identidad
                    <div><br></div>
                    <?php } ?>
                    </div>
                    <?php if ($curso['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) { ?>
                        <div>*DESCUENTO POR PAGO ANTICIPADO:*</div>
                        <div><br></div>
                        <div>*Inversi&oacute;n:* <?php echo $curso['costo2']; ?> Bs. hasta <?php echo date("d/m",strtotime($curso['fecha2'])); ?>  <?php echo date("H:i",strtotime($curso['fecha2']))=='00:00'?'':date("H:i",strtotime($curso['fecha2'])); ?></div>
                        <div><br></div>
                        <?php if ($curso['sw_fecha3'] == '1' && ( date("Y-m-d") <= $curso['fecha3'])) { ?>
                            <div>*Inversi&oacute;n:* <?php echo $curso['costo3']; ?> Bs. hasta <?php echo date("d/m",strtotime($curso['fecha3'])); ?> <?php echo date("H:i",strtotime($curso['fecha3']))=='00:00'?'':date("H:i",strtotime($curso['fecha3'])); ?></div>
                            <div><br></div>
                        <?php } ?>
                        <?php if ($curso['sw_fecha_e'] == '1' && ( date("Y-m-d") <= $curso['fecha_e'])) { ?>
                            <div>*Estudiantes:* <?php echo $curso['costo_e']; ?> Bs. presentando carnet universitario</div>
                            <div><br></div>
                        <?php } ?>
                    <?php } ?>
                    <div>*Whatsapp:* &nbsp; <?php echo 'https://wa.me/'.$cel_wamsm; ?></div>
                    <div><br></div>
                    <?php if ((int) $costo > 0) { ?>
                    <?php 
                    $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                    $rqdtcb2 = fetch($rqdtcb1);
                    ?>
                    <div>*PAGOS:*</div>
                    <div><br></div>
                    <div><?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?></div>
                    <div><br></div>
                    <div>Pago por TigoMoney <?php echo $numero_tigomoney; ?> (sin recargo)</div>
                    <div><br></div>
                    <div>*Otras formas de pago:* <?php echo $dominio; ?>formas-de-pago.html</div>
                    <div><br></div>
                    <div>Una vez realizado el pago, tiene que registrarse en: <?php echo $url_registro; ?></div>
                    <div><br></div>
                    <?php } ?>
                    <?php } ?>
                    <div><br></div>
                </div>

                <!----------------------------------------------------------------->
                <!----------------------------------------------------------------->

                <?php if ($curso['estado'] == '1' && $curso['id'] != '2348') { ?>
                <a href="<?php echo $url_registro; ?>" class="btn btn-lg btn-block btn-danger" style="background: red;font-weight: bold;border-radius: 7px;text-decoration: underline;">
                    INSCRIBIRME AL CURSO
                </a>
                <br>
                <?php } ?>
                
                <!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
                
                <?php
                /* RECOMENDACION */
                if ($curso['sw_recomendaciones'] == '1') {
                    ?>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div style="border: 3px solid #dadada;padding: 10px 20px;border-radius: 10px;background: #ff0000;color: #FFF;text-align: center;font-size: 11pt;padding-bottom: 17px;">
                                <?php
                                if ($curso['rec_limitdesc'] == '100') {
                                    ?>
                                    <b style="font-size: 25pt;">CURSO GRATUITO</b>
                                    <?php
                                } else {
                                    ?>
                                    <b style="font-size: 25pt;">DESCUENTO</b>
                                    <?php
                                }
                                ?>
                                <br>
                                Obten 1% de descuento por cada recomendaci&oacute;n que realices
                                <br>
                                <span style="color:#d8efa0;font-size: 9pt;">( habilitado hasta <?php echo $curso['rec_limitdesc']; ?>% )</span>
                                <br>
                                <br>
                                <a href="recomendar/<?php echo $titulo_identificador_curso; ?>.html" style="    text-decoration: underline;background: #ececec;padding: 3px 15px;color: #1000ff;border-radius: 4px;border: 1px solid #bfbaba;font-size: 9pt;">OBTENER DESCUENTO</a>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php
                }
                ?>


                <?php 
                /* COUNTDOWN PARA cursos con sesion ZOOM */
                $rqsz1 = query("SELECT * FROM sesiones_zoom WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                if(num_rows($rqsz1)>0){
                    $rqsz2 = fetch($rqsz1); 
                    $fecha_strtotime = strtotime($rqsz2['fecha']);
                    if($fecha_strtotime>time()){
                        $fecha_sesion = date("d/m/y H:i",$fecha_strtotime);
                        ?>
                        <style>
                            .timer-container{
                                text-align: center;
                                background: #eef2f5;
                                margin-bottom: 25px;
                                border: 1px solid #d5d5d5;
                                padding-top: 15px;
                            }
                            #timer {
                                font-size: 3em;
                                font-weight: 100;
                                color: white;
                                padding: 20px;
                                width: 100%;
                                color: white;
                                padding-top: 0px;
                            }

                            #timer div {
                                display: inline-block;
                                min-width: 90px;
                                padding: 15px;
                                background: #020b43;
                                border-radius: 10px;
                                border: 2px solid #030d52;
                                margin: 15px;
                            }
                            #timer div span {
                                color: #ffffff;
                                display: block;
                                margin-top: 15px;
                                font-size: .35em;
                                font-weight: 400;
                            }
                        </style>
                        <div class="timer-container">
                            <b style="font-size: 15pt;">La clase iniciara en:</b>
                            <div id="timer"></div>
                        </div>
                        <script>
                        // Set the date we're counting down to
                        var countDownDate = parseInt('<?php echo $fecha_strtotime; ?>000');

                        // Update the count down every 1 second
                        var x = setInterval(function() {

                        // Get today's date and time
                        var now = new Date().getTime();

                        // Find the distance between now and the count down date
                        var distance = countDownDate - now;

                        // Time calculations for days, hours, minutes and seconds
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        // Display the result in the element with id="demo"
                        document.getElementById("timer").innerHTML = '<div>' + days + '<span>Dias</span></div>' + '<div>' + hours + '<span>Horas</span></div>' +'<div>' + minutes + '<span>Minutos</span></div>' + '<div>' + seconds + '<span>Segundos</span></div>';

                        // If the count down is finished, write some text
                        if (distance < 0) {
                            clearInterval(x);
                            document.getElementById("timer").innerHTML = "EXPIRED";
                        }
                        }, 1000);
                        </script>
                        <?php 
                    }
                } 
                ?>



                <div class="cont-course">
                    <?php if($id_curso==2276){ ?>
                    <style>
                        .video-pr{
                            width: 100%;height: 420px;background: #f7f7f7;
                        }
                        @media (max-width: 600px){
                            .video-pr{
                                height: 320px;
                            }
                        }
                        @media (max-width: 500px){
                            .video-pr{
                                height: 270px;
                            }
                        }
                    </style>
                        <div>
                            <iframe src="https://player.vimeo.com/video/399212575?autoplay=1" class="video-pr" frameborder="0" allow="autoplay; fullscreen" allowfullscreen autoplay></iframe>
                        </div>
                    <?php } ?>
     
                    <button class="accordion active"><i class="icon-group text-contrast"></i> Presentaci&oacute;n</button>
                    <div class="panel show text-cont-curso" style="text-align: justify;">
                        <p class="Titulo_texto1"></p>
                        <?php echo $contenido_curso; ?>
                        
                        <?php 
                        if ((int) $costo > 0) {
                        ?>
                            
                            <!-- direcciones -->
                            <div style="font-size: 12pt;margin-top: 10px;margin-bottom: 50px;padding-top: 10px;line-height: 1.8;color: #333;">
                                <?php
                                $rqdd1 = query("SELECT s.*,(d.nombre)departamento FROM sucursales s INNER JOIN departamentos d ON s.id_departamento=d.id WHERE s.estado=1 ");
                                while($rqdd2 = fetch($rqdd1)){
                                ?>
                                    <p><strong><span style="color: #ff0000;"><?php echo strtoupper($rqdd2['departamento']); ?>:</span></strong>&nbsp; <span style="font-weight: 500;"><?php echo $rqdd2['direccion']; ?></span> (<?php echo $rqdd2['horarios_atencion']; ?>) <span style="color: #ff0000;">M&oacute;vil <?php echo $rqdd2['num_celular']; ?></span></p>
                                <?php 
                                }
                                ?>
                            </div>
                        
                        <?php 
                            /* bancos desplegables */
                            include_once 'contenido/paginas/items/item.m.curso.bancos_desplegables.php'; 
                        }
                        ?>
                        
                        <?php
                        /* cursos en la misma ciudad */
                        $rqcmd1 = query("SELECT id,titulo,titulo_identificador,fecha,costo,sw_fecha2,fecha2,costo2,sw_fecha3,fecha3,costo3 FROM cursos WHERE estado='1' AND sw_flag_cursosbo='1' AND id_ciudad='" . $curso['id_ciudad'] . "' AND id<>'" . $curso['id'] . "' AND id_organizador='".$curso['id_organizador']."' ");
                        if (num_rows($rqcmd1) > 0) {
                            if($curso['id_modalidad']!='1'){
                                $curso_text_ciudad = 'modalidad VIRTUAL';
                            }
                            ?>
                        <h4 style="color: red;">Otros cursos en <?php echo $curso_text_ciudad; ?> <i style="color:gray;font-weight: normal;">(Costos con descuento)</i></h4>
                            <table style="width:100%;">
                                <?php
                                while ($rqcmd2 = fetch($rqcmd1)) {
                                    $url_curso_md = $dominio . $rqcmd2['titulo_identificador'] . '.html';
                                    $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$rqcmd2['id']."' ");
                                    if(num_rows($rqenc1)>0){
                                        $rqenc2 = fetch($rqenc1);
                                        $url_curso_md = $dominio.$rqenc2['enlace'] . "/";
                                    }
                                    ?>
                                    <tr>
                                        <td style="padding-top: 10px;">
                                            <a href="<?php echo $url_curso_md; ?>" style="font-size: 12pt;">
                                                <?php echo $rqcmd2['titulo']; ?>
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <?php 
                                            if($curso['id_modalidad']=='1'){
                                                echo fecha_corta($rqcmd2['fecha']); 
                                            }
                                            ?>
                                        </td>
                                        <?php if($sw_mostrar_precios){ ?>
                                        <td class="" style="padding: 0px 5px;min-width: 70px;font-size: 12pt;">
                                            <?php 
                                            /* costo */
                                            $costo_cur = $rqcmd2['costo'];
                                            if ($rqcmd2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqcmd2['fecha2'])) {
                                                $costo_cur = $rqcmd2['costo2'];
                                            }
                                            if ($rqcmd2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqcmd2['fecha3'])) {
                                                $costo_cur = $rqcmd2['costo3'];
                                            }
                                            echo $costo_cur.' Bs'; 
                                            ?>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <a href="<?php echo $url_curso_md; ?>" class="btn btn-xs btn-default" style="padding: 2px 10px; margin-left:8px">
                                                Ver detalles
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <br/>
                            <?php
                        }
                        ?>
                        <div class="fb-like" data-href="https://www.facebook.com/cursoswebbolivia/" data-width="" data-layout="button_count" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                        <div class="fb-share-button" data-href="<?php echo $dominio.$titulo_identificador_curso; ?>.html" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                        <br/>
                        <br/>
                    </div>                    

                    <?php if (trim($lugar_google_maps) !== '') { ?>
                        <button class="accordion active"><i class="icon-pencil text-contrast"></i> Ubicaci&oacute;n por Google Maps</button>
                        <div class="panel show">
                            <p class="Titulo_texto1"> </p>
                            <?php
                            if (strlen($lugar_google_maps) > 30) {
                                echo str_replace('<iframe ', '<iframe style="width: 100% !important;border: 1px solid #e1e1e1;margin-bottom: 10px;margin-top: 5px;" ', $lugar_google_maps);
                            } else {
                                echo "No se registro ubicaci&ocute;n en Google Maps";
                            }
                            ?>
                        </div>
                    <?php } ?>


                    <br/>
                    <br/>
                    <br/>

                </div>
                <?php
                /* mensaje curso ya finalizado */
                if ($curso['estado'] == '0') {
                    ?>
                    <br/>
                    <div class="alert alert-info">
                        <strong>Mensaje:</strong> El curso solicitado ya fu&eacute; finalizado en fechas anteriores, proximamente tendremos m&aacute;s cursos similares en esta ciudad.
                    </div>
                    <p>'<?php echo $curso['titulo']; ?>' fu&eacute; concluido exitosamente, proximamente tendremos m&aacute;s cursos similares en esta ciudad, para consultas comuniquese a info@nemabol.com, a continuaci&oacute;n se listan los cursos actualmente vigentes.</p>

                    <style>
                        .cont-course{
                            display: none;
                        }
                    </style>
                    <div class="clear"></div>
                    <div class="row">
                        <?php
                        $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,(d.nombre)departamento,c.fecha FROM cursos c INNER JOIN ciudades d ON c.id_ciudad=d.id WHERE c.estado IN (1) AND c.sw_flag_cursosbo='1' AND c.sw_siguiente_semana='0' AND c.flag_publicacion IN ('1','3') AND c.id_ciudad='" . $curso['id_ciudad'] . "' ORDER BY c.fecha DESC ");

                        $counter_aux = 0;

                        while ($rc2 = fetch($rc1)) {
                            $titulo_de_curso = $rc2['titulo'];
                            $departamento_curso = $rc2['departamento'];
                            $fecha_curso = fecha_curso($rc2['fecha']);
                            $url_imagen_curso = "contenido/imagenes/paginas/small-" . $rc2['imagen'];
                            $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
                            ?>
                            <div class="col-xs-6 col-sm-6 col-md-6" align="left">
                                <div class="blog-post-short">
                                    <div class="img-holder">
                                        <div class="bg-img-holder bx-img-curso-f1">
                                            <a href="<?php echo $url_pagina_curso; ?>">
                                                <img src="<?php echo $url_imagen_curso; ?>" alt="<?php echo $titulo_de_curso; ?>" title="<?php echo $titulo_de_curso; ?>" class="img-responsive grafico img-curso-f1"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!----->		
                                <div class="blog-post-short list-group-item hidden-xs">	 
                                    <div class="row">
                                        <div class="col-md-12 hidden-xs titulo-curso-f1">
                                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Titulo"><?php echo $titulo_de_curso; ?></a>
                                        </div>
                                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg titulo-curso-f1">
                                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Cel"><?php echo $titulo_de_curso; ?></a>
                                        </div>
                                    </div>
                                    <div class="row hi hidden-xs">	    
                                        <div class="col-md-8 col-sx-8 hi hidden-xs"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                        <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $departamento_curso; ?></div>                            
                                    </div>						
                                    <div class="row hi hidden-sm hidden-md hidden-lg">	    
                                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $departamento_curso; ?></div>                            
                                    </div>						
                                    <div class="row hi hidden-xs ">	    	
                                        <div class="blog-meta">
                                            <div class="col-md-12 hi hidden-xs" align="right">
                                                <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo btn-block"> <i class="icon-edit text-contrast"></i> Ver detalles</a>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row hi hidden-sm hidden-md hidden-lg">	    	
                                        <div class="blog-meta">
                                            <div class="col-md-12 hi hidden-sm hidden-md hidden-lg" align="center">
                                                <a href="<?php echo $url_pagina_curso; ?>" class="buttonlinkcel rojo"> <i class="icon-edit text-contrast"></i> Ver m&aacute;s</a>
                                            </div>
                                        </div>  
                                    </div>
                                </div>		
                                <!------------------------------------->
                                <div class="blog-post-short list-group-item-cel hidden-sm hidden-md hidden-lg">		 
                                    <div class="row">
                                        <div class="col-md-12 hidden-xs">
                                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Titulo"><?php echo $titulo_de_curso; ?></a>
                                        </div>
                                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg titulo-curso-f1">
                                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Cel"><?php echo $titulo_de_curso; ?></a>
                                        </div>
                                    </div>
                                    <div>	
                                        <div class="row hi hidden-xs">	    
                                            <div class="col-md-8 col-sx-8 hi hidden-xs"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                            <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $departamento_curso; ?></div>                            
                                        </div>						
                                        <div class="row hi hidden-sm hidden-md hidden-lg">	    
                                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $departamento_curso; ?></div>                            
                                        </div>						
                                        <div class="row hi hidden-xs ">	    	
                                            <div class="blog-meta">
                                                <div class="col-md-7 tope hi hidden-xs"></div>
                                                <div class="col-md-5 hi hidden-xs" align="right">
                                                    <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo">  <i class="icon-edit text-contrast"></i> Ver m&aacute;s</a>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row hi hidden-sm hidden-md hidden-lg toBottom">	    	
                                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg">
                                            <a href="<?php echo $url_pagina_curso; ?>" class="buttonlinkcel orangecel">  <i class="icon-edit text-contrast"></i>  Ver m&aacute;s</a>
                                        </div>
                                    </div>  
                                </div>
                                <!------------------------------------->
                                <br>
                            </div>
                            <?php
                            $counter_aux++;

                            if ($counter_aux % 2 == 0) {
                                echo "<div class='courses-three-devider hidden-xs'></div>";
                            }
                            if ($counter_aux % 2 == 0) {
                                echo "<div class='courses-two-devider'></div>";
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
                /* END mensaje curso ya finalizado */
                ?>

            </div>



            <div class="col-md-3">
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">ORGANIZADOR</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000">
                    <?php
                    $rqdor1 = query("SELECT imagen,titulo,codigo,titulo_identificador FROM cursos_organizadores WHERE id='" . $curso['id_organizador'] . "' LIMIT 1 ");
                    $rqdor2 = fetch($rqdor1);
                    ?>
                    <img src="contenido/imagenes/organizadores/<?php echo $rqdor2['imagen']; ?>" style="width:100%;"/>
                    <h4 class="text-left"><?php echo $rqdor2['titulo']; ?> - <?php echo $rqdor2['codigo']; ?></h4>
                    <p class="text-left">
                        <a href="organizador/<?php echo $rqdor2['titulo_identificador']; ?>.html" class="btn btn-lg btn-block btn-primary">
                            <i class="icon-list text-contrast"></i> Ver perfil de organizador
                        </a>
                    </p>
                </div>
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Formas de incripci&oacute;n</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000">
                    <p>
                        - Inscripci&oacute;n en Oficinas<br>
                        - Inscripci&oacute;n online<br>
                    </p>
                    <hr/>
                    <p class=" text-right">
                        <?php
                        if ($curso['estado'] == '1' && $curso['id'] != '2348') {
                            if (!isset_usuario() && false) {
                                ?>
                                <a class="btn btn-lg btn-block btn-success" data-toggle="modal" data-target="#myModal">
                                    <i class="fa fa-thumbs-o-up"></i> Inscribirme
                                </a>
                                <?php
                            } else {
                                ?>
                                <a href="<?php echo $url_registro; ?>" class="btn btn-lg btn-block btn-success">
                                    <i class="fa fa-thumbs-o-up"></i> Inscribirme
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </p>
                </div>
                <br>

                <?php
                if ($curso['estado'] !== '0') {
                    ?>
                    <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                        <h4 style="color:#289a0d" align="center">
                        <?= $sw_mostrar_precios ? 'Costo y duraci&oacute;n' : 'Duraci&oacute;n' ?>
                        </h4>
                    </div>
                    <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000">
                        <p class="text-center">
                            <?php if($sw_mostrar_precios){ ?>
                             <br/><b style="font-size:30pt;color:#333; margin-top: 5px;font-weight: bold;" class="text-right"><?php echo $costo; ?> Bs</b> <?php } ?>
                        </p>
                        <?php
                        /* precio estudiantes */
                        if ($curso['sw_fecha_e'] == '1' && (date("Y-m-d") <= $curso['fecha_e'])) {
                            echo '<p style="text-align:right;font-size:12pt;">Estudiantes: &nbsp;  <span style="font-size:15pt;font-weight:bold;">' . $curso['costo_e'] . ' Bs.</span></p>';
                        }
                        ?>
                        <?php
                        if ($sw_descuento_costo2) {
                            ?>
                            <div style="background:#FFF;color:#005982;border-radius: 3px;padding: 3px;margin:2px 7px 2px 0px;text-align:center;border:1px solid #2d72c6;margin:auto;">
                                <b style='color:#439a43;font-size:8pt;'>DESCUENTO POR PAGO ANTICIPADO</b>
                                <br/>
                                Inversi&oacute;n: <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha2']); ?></span>
                                <?php
                                if ($sw_descuento_costo3) {
                                    ?>
                                    <br/>
                                    Inversi&oacute;n: <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha3']); ?></span>
                                    <?php
                                }
                                if ($sw_descuento_costo_e2) {
                                    ?>
                                    <br/>
                                    Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha_e2']); ?></span>
                                    <?php
                                }
                                ?>
                            </div>
                            <br/>
                            <?php
                        }
                        ?>

                        <p style="text-align:right;font-size:12pt;">Duraci&oacute;n: &nbsp; <span style="font-size:15pt;font-weight:bold;"><?php echo $duracion_curso; ?></span></p>
                        <p class=" text-right">
                        <hr/>
                        Duraci&oacute;n 1 d&iacute;a
                        </p>
                    </div>
                    <br>
                    <?php
                }
                ?>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Comparte el curso</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000; text-align: center;">
                    <div class="fb-share-button" data-href="<?php echo $dominio.$titulo_identificador_curso; ?>.html" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                    <br>
                    <div style="height:7px"></div>
                </div>
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Unete al grupo</h4>
                </div>
                <?php
                     $carpeta = $dominio."contenido/imagenes/images/Grupo-Fac.gif"; ?>
                     <a target="_blank" href="https://www.facebook.com/groups/756303315625817"><img src="<?= $carpeta ?> " alt="grupo faceboock infosiscon" class="img-thumbnail"></a>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000; text-align: center;">
                    <a target="_blank" href="https://www.facebook.com/groups/756303315625817" class="fb-xfbml-parse-ignore btn btn-primary" style="border-radius: 8px;background-color:#3d75ef;"><i style="background:#fff;padding:4px;border-radius: 50%;width:21px;height: 21px;" class="fa fa-facebook text-primary"></i> UNIRME</a>
                    <div style="height:7px"></div>
                </div>
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Buscador</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000; text-align: center;">
                    <p>Busca el curso que necesitas</p>
                    <form action="buscador.html" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="buscar" class="form-control" value="" placeholder="..."/>
                            </div>
                            <div class="col-md-12">
                                <select name="departamento" id="select_departamento" class="form-control" onchange="actualiza_ciudades();">
                                    <option value="0">Todos los departamentos</option>
                                    <?php
                                    $rqdc1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                    while ($rqdc2 = fetch($rqdc1)) {
                                        ?>
                                        <option value="<?php echo $rqdc2['id']; ?>"><?php echo $rqdc2['nombre']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <select name="ciudad" id="select_ciudad" class="form-control">
                                    <option value="0">Todas las ciudades</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-danger" value="BUSCAR"/>
                            </div>
                        </div>
                    </form>

                    <br>
                    <div style="height:7px"></div>
                </div>

                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">&Uacute;nete</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000; text-align: center;">
                    <p>Ay&uacute;danos a superar los 100 mil likes en nuestra p&aacute;gina en facebook</p>
                    <a href="https://www.facebook.com/cursoswebbolivia" target="_blank" style="    background: #1877f2;
    color: #FFF;
    padding: 5px 15px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 12pt;"><i class="icon-facebook text-contrast"></i> Pagina Oficial</a>
                    <br>
                    <br>
                    <p>&Uacute;nete a nuestro grupo</p>
                    <a href="https://www.facebook.com/groups/grupocursosbolivia" target="_blank" style="    background: #1877f2;
    color: #FFF;
    padding: 5px 15px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 12pt;"><i class="icon-facebook text-contrast"></i> Grupo Oficial</a>
                    <br>
                    <br>
                    <div style="height:7px"></div>
                </div>






            </div>
        </div>
    </section>
</div>




<div id="proximo_inioio_curso" class="proximo_inioio_curso course-self">
    <section class="recent-projects-home topspace30">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <div class="TituloIndexB">
                        <h2>Otros cursos que te pueden interesar</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr style="border:1px solid #ccc">
                </div>
                <?php
                $rc1 = query("SELECT c.id,c.titulo,c.titulo_identificador,c.imagen,c.fecha,c.id_modalidad FROM cursos c WHERE estado='1' AND sw_flag_cursosbo='1' AND (id_ciudad='$curso_id_ciudad' OR id_modalidad IN (2,3,4) ) ORDER BY fecha DESC ");
                while ($rc2 = fetch($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $fecha_de_curso = fecha_corta($rc2['fecha']);
                    $url_imagen_curso = "contenido/imagenes/paginas/small-" . $rc2['imagen'];
                    $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
                    $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$rc2['id']."' AND r.estado=1 ");
                    if(num_rows($rqenc1)>0){
                        $rqenc2 = fetch($rqenc1);
                        $url_pagina_curso = $dominio.$rqenc2['enlace'] . "/";
                    }
                    ?>
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <a href="<?php echo $url_pagina_curso; ?>" title="">
                                <img src="<?php echo $url_imagen_curso; ?>" class="img-responsive">
                            </a>
                        </div>
                        <div class="hidden-xs col-sm-8 col-md-8">
                            <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso"><?php echo $titulo_de_curso; ?></a>
                            <div class="texto_lista_curso" align="justify"><?php echo $titulo_de_curso; ?> <?php echo $titulo_de_curso; ?>...</div>
                        </div>
                        <div class="hidden-sm hidden-md hidden-lg col-xs-7">
                            <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso"><?php echo $titulo_de_curso; ?></a>
                            <div class="hidden-md texto_lista_curso" align="justify"><?php echo $titulo_de_curso; ?> ...</div>
                        </div>  
                        <?php
                        if ($rc2['id_modalidad'] == '2') {
                            ?>
                            <div class="col-xs-3 col-sm-2  col-md-2 " align="center">
                                <div class="tit_duracion hidden-xs">VIRTUAL</div>
                                <div class="tit_horas">Disponible</div>
                                <a class="btn btn-default orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>">Ver m&aacute;s</a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="col-xs-3 col-sm-2  col-md-2 " align="center">
                                <div class="tit_duracion hidden-xs">FECHA</div>
                                <div class="tit_horas"><?php echo $fecha_de_curso; ?></div>
                                <a class="btn btn-default orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>">Ver m&aacute;s</a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <hr style="border:1px solid #ccc">
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>



<!-- Modal -->
<div id="MODAL-shareEmail" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ENVIAR INFORMACI&Oacute;N POR CORREO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Envia la informaci&oacute;n de este curso a un amigo por correo electr&oacute;nico mediante nuestra plataforma
                </p>
                <div>
                    <div class="boxForm">
                        <h5>INGRESA EL CORREO A NOTIFICAR</h5>
                        <hr/>
                        <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="email" name="email" placeholder="Correo electr&oacute;nico..." required="">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="text" name="nombre" placeholder="Recomendado por..." required="">
                                </div>
                            </div>   
                            <!--                            <div class="form-group">
                                                            <div class="col-md-12 text-center">
                                                                <div style="width:300px;margin:auto;">
                                                                    <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                                                    <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                                                </div> 
                                                            </div>
                                                        </div>-->
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar" class="btn btn-success" value="ENVIAR INFORMACI&Oacute;N"/>
                                </div>
                            </div>
                            <hr/>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- send_static_view -->
<script>
    function send_static_view(dat) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.send_static_view.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                console.log(data);
            }
        });
    }
</script>

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.actualiza_ciudades.php',
            data: {id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>

<script>
    function copyToClipboard_PRE() {
        var container = document.createElement('div');
        container.innerHTML = document.getElementById("contentInfo").innerHTML;
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function(sheet) {
            return !sheet.disabled;
        });
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        for (var i = 0; i < activeSheets.length; i++)
            activeSheets[i].disabled = true;
        document.execCommand('copy');
        for (var i = 0; i < activeSheets.length; i++)
            activeSheets[i].disabled = false;
        document.body.removeChild(container);
    }
    function copyToClipboard(contentToCopy = null) {
        alert('Se ha copiado la informacion del curso al portapapeles (Ctrl + C)');
        var container = document.createElement('div');
        if(contentToCopy!=null){
            contentToCopy;
            container.innerHTML = contentToCopy;
        }else{
            container.innerHTML = document.getElementById("contentInfo").innerHTML;
        }
        //container.style.position = 'fixed';
        //container.style.pointerEvents = 'none';
        //container.style.opacity = 0;
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function(sheet) {
            return !sheet.disabled;
        });
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        for (var i = 0; i < activeSheets.length; i++)
            //activeSheets[i].disabled = true;
        document.execCommand('copy');
        for (var i = 0; i < activeSheets.length; i++)
            //activeSheets[i].disabled = false;
        document.body.removeChild(container);
    }
    function shareFacebook() {
        FB.ui({
            method: 'share',
            href: '<?php echo $dominio.$titulo_identificador_curso; ?>.html'
        }, function(response) {
        });
    }
</script>



<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v3.2&appId=2070955676476906';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


<?php

function fecha_corta($data) {
    //$meses = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d = date("d", strtotime($data));
    $m = date("m", strtotime($data));
    return "$d " . $meses[(int) $m];
}

function mydatefechacurso($dat) {
    $day = date("w", strtotime($dat));
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    return $array_dias[(int) $day] . " " . $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}

function mydatefechacurso2($dat) {
    $ds = date("w", strtotime($dat));
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $h = date("H:i", strtotime($dat));
    $txt_hour = '';
    if ($h !== '00:00') {
        $txt_hour = ' hasta ' . $h;
    }
    $array_dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $array_meses = array('NONE', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $array_dias[$ds] . " " . $d . " de " . ucfirst($array_meses[(int) ($m)]) . '' . $txt_hour;
}

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

/* numeros a letras */

function numtoletras($xcifra) {
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO   $xdecimales/100 BOLIVIANOS";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 BOLIVIANOS ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= "   $xdecimales/100 BOLIVIANOS "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}

function subfijo($xx) {
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    return $xsub;
}

/* incremento reproducciones */
$cnt_reproducciones = $curso['cnt_reproducciones'] + 1;
query("UPDATE cursos SET cnt_reproducciones='$cnt_reproducciones' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

/* metrica - detalle */
if (isset($get[3]) && $get[3] == 'v-detalle') {
    $rqdm1 = query("SELECT id,reproducciones FROM metricas_e_cursos WHERE id_curso='$id_curso' AND fecha=CURDATE() AND modo='1' ");
    if (num_rows($rqdm1) == 0) {
        query("INSERT INTO metricas_e_cursos (id_curso,fecha,reproducciones,modo) VALUES ('$id_curso',CURDATE(),'1','1')");
    } else {
        $rqdm2 = fetch($rqdm1);
        $id_metrica = $rqdm2['id'];
        $reproducciones = (int) $rqdm2['reproducciones'] + 1;
        query("UPDATE metricas_e_cursos SET reproducciones='$reproducciones' WHERE id='$id_metrica' ORDER BY id DESC limit 1 ");
    }
}


