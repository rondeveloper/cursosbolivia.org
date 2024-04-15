<?php
/* dato */
$titulo_identificador_curso = $get[2];

$rqdc1 = query("SELECT * FROM publicaciones WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (0,1,2) ORDER BY FIELD(estado,1,2,0),id DESC limit 1 ");
if (num_rows($rqdc1) == 0) {
    echo "<script>alert('Curso no encontrado');location.href='$dominio';</script>";
    exit;
}
$noticia = fetch($rqdc1);

$id_curso = $noticia['id'];
$titulo_curso = $noticia['titulo'];
$duracion_curso = $noticia['duracion'] . ' ' . $noticia['horarios'];
if ($duracion_curso == '') {
    $duracion_curso = '4 Hrs.';
}
$modalidad_curso = "Presencial";
if ($noticia['id_modalidad'] == '2') {
    $modalidad_curso = "Virtual";
} elseif ($noticia['id_modalidad'] == '3') {
    //$modalidad_curso = "Semi-presencial";
    $modalidad_curso = "VIRTUAL";
}

/* datos lugar */
$rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $noticia['id_lugar'] . "' ");
$rqdl2 = fetch($rqdl1);
$lugar_nombre = $rqdl2['nombre'];
$lugar_salon = $rqdl2['salon'];
$lugar_direccion = $rqdl2['direccion'];
$lugar_google_maps = $rqdl2['google_maps'];

/* ciudad departemento */
$noticia_id_ciudad = $noticia['id_ciudad'];
$rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$noticia_id_ciudad' ");
$rqdcd2 = fetch($rqdcd1);
$noticia_nombre_departamento = $rqdcd2['departamento'];
$noticia_nombre_ciudad = $rqdcd2['ciudad'];
$noticia_text_ciudad = $noticia_nombre_ciudad;
if ($noticia_nombre_departamento !== $noticia_nombre_ciudad) {
    $noticia_text_ciudad = $noticia_nombre_ciudad . ' - ' . $noticia_nombre_departamento;
}

$htm_imagen1 = '';
if ($noticia['imagen'] !== '') {
    $url_img = "contenido/imagenes/noticias/" . $noticia['imagen'];
    $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen2 = '';
if ($noticia['imagen2'] !== '') {
    $url_img = "contenido/imagenes/noticias/" . $noticia['imagen2'];
    $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen3 = '';
if ($noticia['imagen3'] !== '') {
    $url_img = "contenido/imagenes/noticias/" . $noticia['imagen3'];
    $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_imagen4 = '';
if ($noticia['imagen4'] !== '') {
    $url_img = "contenido/imagenes/noticias/" . $noticia['imagen4'];
    $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
}
$htm_archivo1 = '';
if ($noticia['archivo1'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $noticia['archivo1'];
    $htm_archivo1 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $noticia['archivo1'] . '</a>';
}
$htm_archivo2 = '';
if ($noticia['archivo2'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $noticia['archivo2'];
    $htm_archivo2 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $noticia['archivo2'] . '</a>';
}
$htm_archivo3 = '';
if ($noticia['archivo3'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $noticia['archivo3'];
    $htm_archivo3 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $noticia['archivo3'] . '</a>';
}
$htm_archivo4 = '';
if ($noticia['archivo4'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $noticia['archivo4'];
    $htm_archivo4 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $noticia['archivo4'] . '</a>';
}
$htm_archivo5 = '';
if ($noticia['archivo5'] !== '') {
    $url_img = $dominio . "contenido/archivos/cursos/" . $noticia['archivo5'];
    $htm_archivo5 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $noticia['archivo5'] . '</a>';
}
$htm_reportesupago = '<a href="'.$dominio.'registro-curso/' . $noticia['titulo_identificador'] . '.html" target="_blank"><img src="'.$dominio.'contenido/imagenes/images/reporte-su-pago.png" style=""/></a>';
$htm_inscripcion = '<div style="text-align:center;"><a href="'.$dominio.'registro-curso/' . $noticia['titulo_identificador'] . '.html" target="_blank"><img src="https://www.carreramenudoscorazones.es/wp-content/uploads/2015/04/BOTON_INSCRIPCION.jpg" style="height:120px;"/></a></div>';
$htm_whatsapp = "<div style='text-align:center;'><a href='https://api.whatsapp.com/send?phone=" . $noticia['whats_numero'] . "&amp;text=" . str_replace("'", "", str_replace(' ', '%20', str_replace('&', 'y', $noticia['whats_mensaje']))) . "'><img src='https://www.infosicoes.com/contenido/imagenes/noticias/1510747809whatsapp__.png' style='height:120px;'/></a></div>";
$data_nombre_curso = $noticia['titulo'];
$data_ciudad_curso = $noticia_text_ciudad;
$data_fecha_curso = fecha_curso_D_d_m($noticia['fecha']);
$data_horarios_curso = $noticia['horarios'];
$data_lugar_curso = $lugar_nombre;
$data_lugar_salon_curso = $lugar_nombre . ' - ' . $lugar_salon;
$data_direccion_lugar_curso = $lugar_direccion;
$data_costo_bs_curso = $noticia['costo'] . ' Bs';
$txt_descuento_uno_curso = '';
$txt_descuento_dos_curso = '';
$txt_descuento_est_curso = '';
$txt_descuento_est_pre_curso = '';
if ($noticia['sw_fecha2'] == '1') {
    $txt_descuento_uno_curso = $noticia['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($noticia['fecha2']);
}
if ($noticia['sw_fecha3'] == '1') {
    $txt_descuento_dos_curso = $noticia['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($noticia['fecha3']);
}
if ($noticia['sw_fecha_e'] == '1') {
    $txt_descuento_est_curso = $noticia['costo_e'] . ' Bs. hasta el ' . fecha_curso_D_d_m($noticia['fecha_e']) . ' (Estudiantes)';
}
if ($noticia['sw_fecha_e2'] == '1') {
    $txt_descuento_est_pre_curso = $noticia['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($noticia['fecha_e2']) . ' (Estudiantes)';
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
    'src="https://www.infosicoes.com/paginas',
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

$contenido_curso = trim(str_replace($array_palabras_reservadas_busc, $array_palabras_reservadas_remm, $noticia['contenido']));
//$contenido_curso = getContenidoCurso($noticia);

/* costo */
$costo = $noticia['costo'];
//if ($noticia['sw_fecha2'] == '1' && (date("Y-m-d") <= $noticia['fecha2'])) {
//    $costo = $noticia['costo2'];
//}
//if ($noticia['sw_fecha3'] == '1' && (date("Y-m-d") <= $noticia['fecha3'])) {
//    $costo = $noticia['costo3'];
//}
$sw_descuento_costo2 = false;
$f_h = date("H:i", strtotime($noticia['fecha2']));
if ($f_h !== '00:00') {
    $f_actual = strtotime(date("Y-m-d H:i"));
    $f_limite = strtotime($noticia['fecha2']);
} else {
    $f_actual = strtotime(date("Y-m-d"));
    $f_limite = strtotime(substr($noticia['fecha2'], 0, 10));
}
if ($noticia['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
    $sw_descuento_costo2 = true;
    $costo2 = $noticia['costo2'];
}
$sw_descuento_costo3 = false;
if ($noticia['sw_fecha3'] == '1' && ( date("Y-m-d") <= $noticia['fecha3'])) {
    $sw_descuento_costo3 = true;
    $costo3 = $noticia['costo3'];
}
$sw_descuento_costo_e2 = false;
if ($noticia['sw_fecha_e2'] == '1' && (date("Y-m-d") <= $noticia['fecha_e2'])) {
    $sw_descuento_costo_e2 = true;
    $costo_e2 = $noticia['costo_e2'];
}


/* fecha de inicio */
$arf1 = explode('-', $noticia['fecha']);
$arf2 = date("N", strtotime($noticia['fecha']));
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

        SISTsendEmail($email, $asunto, $contenido_correo);
        SISTsendEmail("brayan.desteco@gmail.com", $asunto, $contenido_correo);
        movimiento('Curso enviado por email [' . $email . ']', 'share-email', 'curso', $id_curso);
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
<!--                <div id="contentInfo">
                    <?php
                    if ($noticia['imagen'] !== '') {
                        ?>
                        <div style="margin-bottom: 30px;">
                            <img src="contenido/imagenes/noticias/<?php echo $noticia['imagen']; ?>" style="width:100%;"/>
                        </div>
                        <?php
                    }
                    ?>
                </div>-->
                <div class="cont-course">
                    <div class="panel show text-cont-curso">
                        <h1 style="font-family: arial;margin: 30px 0px;font-size: 24pt;color: #1b5b77;"><?php echo $titulo_curso; ?></h1>
                        <p class="Titulo_texto1"></p>
                        <?php echo $contenido_curso; ?>
                        <br/>
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
            </div>



            <div class="col-md-3">
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Comparte la noticia</h4>
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
                        <h2>Cursos que te pueden interesar</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr style="border:1px solid #ccc">
                </div>
                <?php
                $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,c.fecha,c.id_modalidad FROM cursos c WHERE estado='1' ORDER BY fecha DESC ");
                while ($rc2 = fetch($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $fecha_de_curso = fecha_corta($rc2['fecha']);
                    $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                    if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                        $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/noticias/" . $rc2['imagen'];
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
    function copyToClipboard() {
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

/* incremento reproducciones */
$cnt_reproducciones = $noticia['cnt_reproducciones'] + 1;
query("UPDATE publicaciones SET cnt_reproducciones='$cnt_reproducciones' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

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


