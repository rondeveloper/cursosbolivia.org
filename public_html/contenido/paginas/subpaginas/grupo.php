<?php
/* dato */
$titulo_identificador_grupo = $get[2];

/* agrupacion */
$rqdc1 = query("SELECT * FROM cursos_agrupaciones WHERE titulo_identificador='$titulo_identificador_grupo' AND estado IN (0,1,2) ORDER BY FIELD(estado,1,2,0),id DESC limit 1 ");
$grupo = fetch($rqdc1);
$id_grupo = $grupo['id'];

$titulo_identificador_curso = $grupo['titulo_identificador'];
$titulo_curso = $grupo['titulo'];
$duracion_curso = $grupo['horarios'];
if ($duracion_curso == '') {
    $duracion_curso = '4 Hrs.';
}
$modalidad_curso = "Presencial";
if ($grupo['id_modalidad'] == '2') {
    $modalidad_curso = "Virtual";
} elseif ($grupo['id_modalidad'] == '3') {
    //$modalidad_curso = "Semi-presencial";
    $modalidad_curso = "VIRTUAL";
}

/* datos lugar */
$rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $grupo['id_lugar'] . "' ");
$rqdl2 = fetch($rqdl1);
$lugar_nombre = $rqdl2['nombre'];
$lugar_salon = $rqdl2['salon'];
$lugar_direccion = $rqdl2['direccion'];
$lugar_google_maps = $rqdl2['google_maps'];

/* ciudad departemento */
$grupo_id_ciudad = $grupo['id_ciudad'];
$rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$grupo_id_ciudad' ");
$rqdcd2 = fetch($rqdcd1);
$grupo_nombre_departamento = $rqdcd2['departamento'];
$grupo_nombre_ciudad = $rqdcd2['ciudad'];
$grupo_text_ciudad = $grupo_nombre_ciudad;
if ($grupo_nombre_departamento !== $grupo_nombre_ciudad) {
    $grupo_text_ciudad = $grupo_nombre_ciudad . ' - ' . $grupo_nombre_departamento;
}

$htm_imagen1 = '';
if ($grupo['imagen'] !== '') {
    $url_img = "contenido/imagenes/paginas/" . $grupo['imagen'];
    $url_img = $dominio . "contenido/imagenes/paginas/" . $grupo['imagen'];
    $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen2 = '';
if ($grupo['imagen2'] !== '') {
    $url_img = "contenido/imagenes/paginas/" . $grupo['imagen2'];
    $url_img = $dominio . "contenido/imagenes/paginas/" . $grupo['imagen2'];
    $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen3 = '';
if ($grupo['imagen3'] !== '') {
    $url_img = "contenido/imagenes/paginas/" . $grupo['imagen3'];
    $url_img = $dominio . "contenido/imagenes/paginas/" . $grupo['imagen3'];
    $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen4 = '';
if ($grupo['imagen4'] !== '') {
    $url_img = "contenido/imagenes/paginas/" . $grupo['imagen4'];
    $url_img = $dominio . "contenido/imagenes/paginas/" . $grupo['imagen4'];
    $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_archivo1 = '';
if ($grupo['archivo1'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $grupo['archivo1'];
    $htm_archivo1 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $grupo['archivo1'] . '</a>';
}
$htm_archivo2 = '';
if ($grupo['archivo2'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $grupo['archivo2'];
    $htm_archivo2 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $grupo['archivo2'] . '</a>';
}
$htm_archivo3 = '';
if ($grupo['archivo3'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $grupo['archivo3'];
    $htm_archivo3 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $grupo['archivo3'] . '</a>';
}
$htm_archivo4 = '';
if ($grupo['archivo4'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $grupo['archivo4'];
    $htm_archivo4 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $grupo['archivo4'] . '</a>';
}
$htm_archivo5 = '';
if ($grupo['archivo5'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $grupo['archivo5'];
    $htm_archivo5 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $grupo['archivo5'] . '</a>';
}
$htm_reportesupago = '<a href="'.$dominio.'registro-curso/' . $grupo['titulo_identificador'] . '.html" target="_blank"><img src="'.$dominio.'contenido/imagenes/images/reporte-su-pago.png" style=""/></a>';
$htm_inscripcion = '<div style="text-align:center;"><a href="'.$dominio.'registro-curso/' . $grupo['titulo_identificador'] . '.html" target="_blank"><img src="https://www.carreramenudoscorazones.es/wp-content/uploads/2015/04/BOTON_INSCRIPCION.jpg" style="height:120px;"/></a></div>';
$htm_whatsapp = "<div style='text-align:center;'><a href='https://api.whatsapp.com/send?phone=" . $grupo['whats_numero'] . "&amp;text=" . str_replace("'", "", str_replace(' ', '%20', str_replace('&', 'y', $grupo['whats_mensaje']))) . "'><img src='https://www.infosiscon.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style='height:120px;'/></a></div>";
$data_nombre_curso = $grupo['titulo'];
$data_ciudad_curso = $grupo_text_ciudad;
$data_fecha_curso = fecha_curso_D_d_m($grupo['fecha']);
$data_horarios_curso = $grupo['horarios'];
$data_lugar_curso = $lugar_nombre;
$data_lugar_salon_curso = $lugar_nombre . ' - ' . $lugar_salon;
$data_direccion_lugar_curso = $lugar_direccion;
$data_costo_bs_curso = $grupo['costo'] . ' Bs';
$txt_descuento_uno_curso = '';
$txt_descuento_dos_curso = '';
$txt_descuento_est_curso = '';
$txt_descuento_est_pre_curso = '';
if ($grupo['sw_fecha2'] == '1') {
    $txt_descuento_uno_curso = $grupo['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($grupo['fecha2']);
}
if ($grupo['sw_fecha3'] == '1') {
    $txt_descuento_dos_curso = $grupo['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($grupo['fecha3']);
}
if ($grupo['sw_fecha_e'] == '1') {
    $txt_descuento_est_curso = $grupo['costo_e'] . ' Bs. hasta el ' . fecha_curso_D_d_m($grupo['fecha_e']) . ' (Estudiantes)';
}
if ($grupo['sw_fecha_e2'] == '1') {
    $txt_descuento_est_pre_curso = $grupo['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($grupo['fecha_e2']) . ' (Estudiantes)';
}

$contenido_curso = str_replace('href="'.$dominio.'registro-curso',' onclick="proceso_inscripcion();" title="'.$dominio.'registro-curso',getContenidoCurso($grupo));

/* costo real */
$rqdccgct1 = query("SELECT SUM(costo) AS total FROM cursos WHERE id IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ");
$rqdccgct2 = fetch($rqdccgct1);
$costo_real = $rqdccgct2['total'];
/* cnt cursos */
$rqdccgctcc1 = query("SELECT COUNT(*) AS total FROM cursos WHERE id IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ");
$rqdccgctcc2 = fetch($rqdccgctcc1);
$cnt_cursos = $rqdccgctcc2['total'];
/* descuento */
switch ($cnt_cursos) {
    case 0:
        $descuento = 0;
        break;
    case 1:
        $descuento = $grupo['desc_1'];
        break;
    case 2:
        $descuento = $grupo['desc_2'];
        break;
    case 3:
        $descuento = $grupo['desc_3'];
        break;
    case 4:
        $descuento = $grupo['desc_4'];
        break;
    default:
        $descuento = $grupo['desc_5'];
        break;
}
/* costo */
$costo = ceil(($costo_real / 100) * (100 - $descuento));
//if ($grupo['sw_fecha2'] == '1' && (date("Y-m-d") <= $grupo['fecha2'])) {
//    $costo = $grupo['costo2'];
//}
//if ($grupo['sw_fecha3'] == '1' && (date("Y-m-d") <= $grupo['fecha3'])) {
//    $costo = $grupo['costo3'];
//}
$sw_descuento_costo2 = false;
$f_h = date("H:i", strtotime($grupo['fecha2']));
if ($f_h !== '00:00') {
    $f_actual = strtotime(date("Y-m-d H:i"));
    $f_limite = strtotime($grupo['fecha2']);
} else {
    $f_actual = strtotime(date("Y-m-d"));
    $f_limite = strtotime(substr($grupo['fecha2'], 0, 10));
}
if ($grupo['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
    $sw_descuento_costo2 = true;
    $costo2 = $grupo['costo2'];
}
$sw_descuento_costo3 = false;
if ($grupo['sw_fecha3'] == '1' && ( date("Y-m-d") <= $grupo['fecha3'])) {
    $sw_descuento_costo3 = true;
    $costo3 = $grupo['costo3'];
}
$sw_descuento_costo_e2 = false;
if ($grupo['sw_fecha_e2'] == '1' && (date("Y-m-d") <= $grupo['fecha_e2'])) {
    $sw_descuento_costo_e2 = true;
    $costo_e2 = $grupo['costo_e2'];
}


/* fecha de inicio */
$arf1 = explode('-', $grupo['fecha']);
$arf2 = date("N", strtotime($grupo['fecha']));
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

        $contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>$data_nombre_curso</h2>";
        $contenido_correo .= "<center><a href='".$dominio."$titulo_identificador_curso.html'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='".$dominio."contenido/alt/logotipo-v3.png'/></a></center>";
        $contenido_correo .= $contenido_curso;
        $contenido_correo .= "<hr/><b>Curso recomendado por:</b> $recomendado<br/><b>Enviado a:</b> $email<br/>";

        $contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
                . "</div>";

        $asunto = "$data_nombre_curso, recomendado por $recomendado";

        SISTsendEmail($email, $asunto, $contenido_correo);
        movimiento('Curso enviado por email [' . $email . ']', 'share-email', 'curso', $id_grupo);
    } else {
        echo "<script>alert('Verifica que no eres un robot');history.back();</script>";
    }
}
?>



<style>
    .img-pag-static{
        max-width: 90%;
        border-radius: 5px;
        border: 1px solid #dadada;
        padding: 1px;
    }
</style>

<div class="wrapsemibox course-self" style="margin-top: 60px;">

    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-9" style="padding-right:20px"><br>
                <div id="contentInfo">
                    <div class="TituloContenidoCursosinLinea">
                        <h1><?php echo $titulo_curso; ?></h1>
                    </div>
                    <div style="height:5px"></div>
                    <div style=" background-color:#F6F6F6;padding:13px 10px 7px 5px; border-bottom:1px solid #999; border-top:1px solid #999;">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($grupo['id_modalidad'] !== '2') { ?>
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
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 Titulo_texto">Sitio web</div>
                                    <?php
                                    $url_corta = $dominio.'g/' . $id_grupo . '/';
                                    ?>
                                    <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<a href='<?php echo $url_corta; ?>'><?php echo $url_corta; ?></a></div>
                                    <div style="height:25px"></div>
                                </div>
                                <?php if ($grupo['id_modalidad'] !== '2' && $grupo['id_modalidad'] !== '3') { ?>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-3 Titulo_texto">Ciudad</div>
                                        <div class="col-md-9 col-xs-9 Titulo_texto1"> : &nbsp;<?php echo $grupo_text_ciudad; ?></div>
                                        <div style="height:25px"></div>
                                    </div>
                                    <?php
                                    if ($grupo['estado'] !== '0') {
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
                            if ($grupo['estado'] !== '0') {
                                ?>
                                <div class="col-md-6">
                                    <?php
                                    if ((int) $costo > 0) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Inversi&oacute;n</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1"> 
                                                <strong>:</strong>&nbsp; <span class="costo-cursos"><?php echo $costo; ?></span> Bs.
                                            </div> 
                                            <div style="height:25px"></div>
                                        </div>
                                        <?php
                                    } else {
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
                                    if ($grupo['sw_fecha_e'] == '1' && (date("Y-m-d") <= $grupo['fecha_e'])) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Estudiantes</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1"> <strong>:</strong>&nbsp; <?php echo $grupo['costo_e']; ?> Bs.</div> 
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
                                                    Inversi&oacute;n: <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha2']); ?></span>
                                                    <?php
                                                    if ($sw_descuento_costo3) {
                                                        ?>
                                                        <br/>
                                                        Inversi&oacute;n: <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha3']); ?></span>
                                                        <?php
                                                    }
                                                    if ($sw_descuento_costo_e2) {
                                                        ?>
                                                        <br/>
                                                        Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha_e2']); ?></span>
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
                                    <?php
                                    if ($id_grupo == '1851') {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Pagos</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1"> <strong>:</strong>&nbsp; Banco UNION cuenta 1-00000-28058168<br/> :&nbsp; Titular NEMABOL</div> 
                                            <div style="height:25px"></div>
                                        </div>
                                        <?php
                                    } elseif ((int) $costo > 0) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 Titulo_texto">Pagos</div>
                                            <div class="col-md-9 col-xs-9 Titulo_texto1">
                                                <strong>:</strong>&nbsp; Banco UNION cuenta 1-00000-24033833<br/> :&nbsp; Titular NEMABOL
                                                <br/>
                                                <strong>:</strong>&nbsp; Pago por TigoMoney 69714008 (sin recargo)
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

                <!-- cursos del grupo -->
                <div style="margin-bottom: 15px;
                     border-top: 1px solid #999999;
                     border-bottom: 1px solid #999999;
                     padding: 10px;">
                    <div style="padding-bottom: 10px;
                         border-bottom: 1px solid gray;
                         margin-bottom: 10px;">
                        <b>Cursos:</b> <span class="cnt-cursos"><?php echo $cnt_cursos; ?></span> | <b>Costo:</b> <strike><?php echo $costo_real; ?> BS</strike> | <b>Descuento:</b> <span class="descuento-cursos"><?php echo $descuento; ?></span>% | <b>Total:</b> <span class="costo-cursos" style="font-size: 14pt;"><?php echo $costo; ?></span> BS 
                    </div>
                    <div class="alert alert-warning">
                        Marca los cursos que deseas tomar en la comuna 'Selecci&oacute;n'
                    </div>
                    <form action="" method="post">
                        <table class="table table-bordered table-striped table-responsive">
                            <tr>
                                <th class="hidden-xs"></th>
                                <th>Cursos</th>
                                <th>Costo</th>
                                <th style="width: 70px;">Selecci&oacute;n</th>
                            </tr>
                            <?php
                            $rqdccg1 = query("SELECT id,titulo,titulo_identificador,costo,imagen FROM cursos WHERE id IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ");
                            while ($rqdccg2 = fetch($rqdccg1)) {
                                ?>
                                <tr>
                                    <td style="width:120px;" class="hidden-xs"><img src="contenido/imagenes/paginas/<?php echo $rqdccg2['imagen']; ?>" style="width:120px;"/></td>
                                    <td><b style="font-size: 14pt;color: #3b7fb9;"><?php echo $rqdccg2['titulo']; ?></b></td>
                                    <td>
                                    <strike id="c-str_c_<?php echo $rqdccg2['id']; ?>"><?php echo $rqdccg2['costo']; ?> BS.</strike>
                                    <div class="c-desc" style="font-size:12pt;color:#1b5b77;font-weight: bold;"><span id="c__b_c_<?php echo $rqdccg2['id']; ?>"><?php echo $rqdccg2['costo']; ?></span> BS.</div>
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" id="c__a_c_<?php echo $rqdccg2['id']; ?>" value="<?php echo (int) $rqdccg2['costo']; ?>"/>
                                        <input type="checkbox" style="width:30px;height:30px;" value="c_<?php echo $rqdccg2['id']; ?>" checked="" onclick="actualiza_costos_grupo();">
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr class="hidden-xs">
                                <td colspan="2" class="text-center">
                                    <?php
                                    if($grupo['estado']=='1'){
                                    ?>
                                    <a onclick="proceso_inscripcion();" class="btn btn-xs btn-success">
                                        <i class="fa fa-edit"></i> Inscribirme a los cursos seleccionados
                                    </a>
                                    <?php
                                    }
                                    ?>
                                    <span class="pull-right">Total:</span>
                                </td>
                                <td><b><span class="costo-cursos"><?php echo $costo; ?></span> BS.</b></td>
                                <td></td>
                            </tr>
                            <tr class="hidden-lg hidden-md hidden-sm">
                                <td class="text-center">
                                    <?php
                                    if($grupo['estado']=='1'){
                                    ?>
                                    <a onclick="proceso_inscripcion();" class="btn btn-xs btn-success">
                                        <i class="fa fa-edit"></i> Inscribirme
                                    </a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><b><span class="costo-cursos">Total:<br/><?php echo $costo; ?></span> BS.</b></td>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <!----------------------------------------------------------------->
                <!----------------------------------------------------------------->

                <!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

                <div class="cont-course">

                    <button class="accordion active"><i class="icon-group text-contrast"></i> Presentaci&oacute;n</button>
                    <div class="panel show text-cont-curso">
                        <p class="Titulo_texto1"></p>
                        <?php echo $contenido_curso; ?>
                        <br/>
                        <?php
                        /* cursos en la misma ciudad */
                        $rqcmd1 = query("SELECT titulo,titulo_identificador,fecha FROM cursos WHERE estado='1' AND sw_flag_cursosbo='1' AND id_ciudad='" . $grupo['id_ciudad'] . "' AND id<>'" . $grupo['id'] . "' ");
                        if (num_rows($rqcmd1) > 0) {
                            ?>
                            <h4 style="color: red;">Otros cursos en <?php echo $grupo_text_ciudad; ?></h4>
                            <table style="width:100%;">
                                <?php
                                while ($rqcmd2 = fetch($rqcmd1)) {
                                    $url_curso_md = $dominio . $rqcmd2['titulo_identificador'] . '.html';
                                    ?>
                                    <tr>
                                        <td style="padding-top: 10px;">
                                            <a href="<?php echo $url_curso_md; ?>" style="font-size: 12pt;">
                                                <?php echo $rqcmd2['titulo']; ?>
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <?php echo fecha_corta($rqcmd2['fecha']); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $url_curso_md; ?>" class="btn btn-xs btn-default" style="padding: 2px 10px;">
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
                                echo str_replace('<iframe ', '<iframe style="width:100% !important;" ', $lugar_google_maps);
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
                if ($grupo['estado'] == '0') {
                    ?>
                    <br/>
                    <div class="alert alert-info">
                        <strong>Mensaje:</strong> El curso solicitado ya fu&eacute; finalizado en fechas anteriores, proximamente tendremos m&aacute;s cursos similares en esta ciudad.
                    </div>
                    <p>'<?php echo $grupo['titulo']; ?>' fu&eacute; concluido exitosamente, proximamente tendremos m&aacute;s cursos similares en esta ciudad, para consultas comuniquese a info@nemabol.com, a continuaci&oacute;n se listan los cursos actualmente vigentes.</p>

                    <style>
                        .cont-course{
                            display: none;
                        }
                    </style>
                    <div class="clear"></div>
                    <div class="row">
                        <?php
                        $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,(d.nombre)departamento,c.fecha FROM cursos c INNER JOIN ciudades d ON c.id_ciudad=d.id WHERE c.estado IN (1) AND c.sw_flag_cursosbo='1' AND c.sw_siguiente_semana='0' AND c.flag_publicacion IN ('1','3') AND c.id_ciudad='" . $grupo['id_ciudad'] . "' ORDER BY c.fecha DESC ");

                        $counter_aux = 0;

                        while ($rc2 = fetch($rc1)) {
                            $titulo_de_curso = $rc2['titulo'];
                            $departamento_curso = $rc2['departamento'];
                            $fecha_curso = fecha_curso($rc2['fecha']);
                            $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                            if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                                $url_imagen_curso = "https://www.infosiscon.com/contenido/imagenes/paginas/" . $rc2['imagen'];
                            }
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
                    $rqdor1 = query("SELECT imagen,titulo,codigo,titulo_identificador FROM cursos_organizadores WHERE id='" . $grupo['id_organizador'] . "' LIMIT 1 ");
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
                <?php
                if ($grupo['estado'] !== '0') {
                    ?>
                    <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                        <h4 style="color:#289a0d" align="center">Costo y duraci&oacute;n</h4>
                    </div>
                    <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000">
                        <p class="text-center">
                            <br/>
                            <b style="font-size:30pt;color:#333; margin-top: 5px;font-weight: bold;" class="text-right"><span class="costo-cursos"><?php echo $costo; ?></span> Bs</b>
                        </p>
                        <?php
                        /* precio estudiantes */
                        if ($grupo['sw_fecha_e'] == '1' && (date("Y-m-d") <= $grupo['fecha_e'])) {
                            echo '<p style="text-align:right;font-size:12pt;">Estudiantes: &nbsp;  <span style="font-size:15pt;font-weight:bold;">' . $grupo['costo_e'] . ' Bs.</span></p>';
                        }
                        ?>
                        <?php
                        if ($sw_descuento_costo2) {
                            ?>
                            <div style="background:#FFF;color:#005982;border-radius: 3px;padding: 3px;margin:2px 7px 2px 0px;text-align:center;border:1px solid #2d72c6;margin:auto;">
                                <b style='color:#439a43;font-size:8pt;'>DESCUENTO POR PAGO ANTICIPADO</b>
                                <br/>
                                Inversi&oacute;n: <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha2']); ?></span>
                                <?php
                                if ($sw_descuento_costo3) {
                                    ?>
                                    <br/>
                                    Inversi&oacute;n: <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha3']); ?></span>
                                    <?php
                                }
                                if ($sw_descuento_costo_e2) {
                                    ?>
                                    <br/>
                                    Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($grupo['fecha_e2']); ?></span>
                                    <?php
                                }
                                ?>
                            </div>
                            <br/>
                            <?php
                        }
                        ?>
                        <p style="text-align:center;font-size:12pt;">Duraci&oacute;n: <br/> <span style="font-size:15pt;font-weight:bold;"><?php echo $duracion_curso; ?></span></p>
                        <hr/>
                    </div>
                    <br>
                    <?php
                }
                ?>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Formas de incripci&oacute;n</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000">
                    <p>
                        - Inscripci&oacute;n en Oficinas<br>
                        - Inscripci&oacute;n online<br>
                    </p>
                    <hr/>
                    <?php
                    if($grupo['estado']=='1'){
                    ?>
                    <p class=" text-right">
                        <a onclick="proceso_inscripcion();" class="btn btn-lg btn-block btn-success">
                            <i class="fa fa-thumbs-o-up"></i> Inscribirme
                        </a>
                    </p>
                    <?php
                    }
                    ?>
                </div>
                <br>
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
                $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,c.fecha,c.id_modalidad FROM cursos c WHERE estado='1' AND sw_flag_cursosbo='1' AND (id_ciudad='$grupo_id_ciudad' OR id_modalidad='2') ORDER BY fecha DESC ");
                while ($rc2 = fetch($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $fecha_de_curso = fecha_corta($rc2['fecha']);
                    $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                    if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                        $url_imagen_curso = "https://www.infosiscon.com/contenido/imagenes/paginas/" . $rc2['imagen'];
                    }
                    $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
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
                    Envia la informaci&oacute;n de este curso a un amigo por correo electr&oacute;nico mediante <b><?php echo $___nombre_del_sitio; ?></b>
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

<script>
    /* costos */
    var costo = parseInt('<?php echo $costo_real; ?>');
    var desc_1 = parseInt('<?php echo $grupo['desc_1']; ?>');
    var desc_2 = parseInt('<?php echo $grupo['desc_2']; ?>');
    var desc_3 = parseInt('<?php echo $grupo['desc_3']; ?>');
    var desc_4 = parseInt('<?php echo $grupo['desc_4']; ?>');
    var desc_5 = parseInt('<?php echo $grupo['desc_5']; ?>');
    function actualiza_costos_grupo() {
        var contador = 0;
        var descuento = parseInt('<?php echo $descuento; ?>');
        $("input[type=checkbox]").each(function () {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        switch (contador) {
            case 0:
                descuento = 0;
                break;
            case 1:
                descuento = desc_1;
                break;
            case 2:
                descuento = desc_2;
                break;
            case 3:
                descuento = desc_3;
                break;
            case 4:
                descuento = desc_4;
                break;
            default:
                descuento = desc_5;
                break;
        }
        /* costo calculado */
        var c_costo = 0;
        $("input[type=checkbox]").each(function () {
            var identf = $(this).val();
            var c__a_ = $("#c__a_" + identf).val();
            if ($(this).is(":checked")) {
                var cc_cost = Math.ceil((c__a_ / 100) * (100 - descuento));
                c_costo += cc_cost;
                $("#c__b_" + identf).html(cc_cost);
                if (descuento > 0) {
                    $("#c-str_" + identf).css('display', 'block');
                } else {
                    $("#c-str_" + identf).css('display', 'none');
                }
            } else {
                $("#c-str_" + identf).css('display', 'none');
                $("#c__b_" + identf).html(c__a_);
            }
        });
        $(".cnt-cursos").html(contador);
        $(".descuento-cursos").html(descuento);
        $(".costo-cursos").html(c_costo);
    }
</script>

<script>
    function proceso_inscripcion() {
        var tags_cur = 'c_0';
        var cnt_reg = 0;
        $("input[type=checkbox]").each(function () {
            var identf = $(this).val();
            if ($(this).is(":checked")) {
                tags_cur += ','+identf;
                cnt_reg++;
            }
        });
        if(cnt_reg>0){
            location.href = 'registro-grupo/<?php echo $titulo_identificador_curso; ?>/' + tags_cur + '.html';
        }else{
            alert('No se seleccionaron cursos.');
        }
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
            success: function (data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>

<script>
    function copyToClipboard() {
        var container = document.createElement('div');
        container.innerHTML = document.getElementById("contentInfo").innerHTML;
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function (sheet) {
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
    function shareFacebook() {
        FB.ui({
            method: 'share',
            href: '<?php echo $dominio.$titulo_identificador_curso; ?>.html'
        }, function (response) {
        });
    }
</script>



<div id="fb-root"></div>
<script>(function (d, s, id) {
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

/* incremento reproducciones */
$cnt_reproducciones = $grupo['cnt_reproducciones'] + 1;
query("UPDATE cursos_agrupaciones SET cnt_reproducciones='$cnt_reproducciones' WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");

/* metrica - detalle */
if (isset($get[3]) && $get[3] == 'v-detalle') {
    $rqdm1 = query("SELECT id,reproducciones FROM metricas_e_cursos WHERE id_curso='$id_grupo' AND fecha=CURDATE() AND modo='1' ");
    if (num_rows($rqdm1) == 0) {
        query("INSERT INTO metricas_e_cursos (id_curso,fecha,reproducciones,modo) VALUES ('$id_grupo',CURDATE(),'1','1')");
    } else {
        $rqdm2 = fetch($rqdm1);
        $id_metrica = $rqdm2['id'];
        $reproducciones = (int) $rqdm2['reproducciones'] + 1;
        query("UPDATE metricas_e_cursos SET reproducciones='$reproducciones' WHERE id='$id_metrica' ORDER BY id DESC limit 1 ");
    }
}


