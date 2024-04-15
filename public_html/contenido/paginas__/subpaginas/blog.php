<?php
/* dato */
$titulo_identificador_curso = $get[2];

$rqdc1 = query("SELECT * FROM blog WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (0,1,2) ORDER BY FIELD(estado,1,2,0),id DESC limit 1 ");
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
} elseif ($curso['id_modalidad'] == '3') {
    //$modalidad_curso = "Semi-presencial";
    $modalidad_curso = "VIRTUAL";
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

$contenido_curso = getContenidoBlog($curso);


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

        $contenido_correo = platillaEmailUno($contenido_curso."<hr/><b>Curso recomendado por:</b> $recomendado<br/><b>Enviado a:</b> $email<br/>",$data_nombre_curso,$email,urlUnsubscribe($email), 'Usuario');

        $asunto = "$data_nombre_curso, recomendado por $recomendado"; // El asunto del mensaje
        
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
                    <div style="background: #f9f9f9;padding: 2px 10px;text-align: right;">
                        Fecha: <?php echo $dia_de_inicio . ', ' . $fecha_de_inicio; ?>
                    </div>                    
                    <div style="height:20px"></div>
                </div>

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
                        <div style="">
                            <iframe src="https://player.vimeo.com/video/399212575?autoplay=1" class="video-pr" frameborder="0" allow="autoplay; fullscreen" allowfullscreen autoplay></iframe>
                        </div>
                    <?php } ?>

                    <br>
                    <div class="panel show text-cont-curso">
                        <p class="Titulo_texto1"></p>
                        <?php echo $contenido_curso; ?>
                        <br/>
                        
                        <div class="fb-like" data-href="https://www.facebook.com/eeventos.bo/" data-width="" data-layout="button_count" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                        <div class="fb-share-button" data-href="<?php echo $dominio.$titulo_identificador_curso; ?>/" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
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
                            $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                            if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                                $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rc2['imagen'];
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
<!--                <br>
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
                </div>-->
                <br>
                <div style=" background-color:#EEEEEE; padding:7px 12px 10px 12px; color:#000">
                    <h4 style="color:#289a0d" align="center">Comparte el blog</h4>
                </div>
                <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000; text-align: center;">
                    <div class="fb-share-button" data-href="<?php echo $dominio.$titulo_identificador_curso; ?>/" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                    <br>
                    <br>
                    <span class="">
                                                <b class="btn btn-xs btn-info" onclick="copyToClipboard();" title="Copiar informaci&oacute;n al clipboard."><i class="icon-copy text-contrast"></i></b>
                                                &nbsp;
                                                <b class="btn btn-xs btn-info" onclick="shareFacebook();" title="Compartir en Facebook."><i class="icon-facebook text-contrast"></i></b>
                                                &nbsp;
                                                <b class="btn btn-xs btn-info" title="Enviar por Email." data-toggle="modal" data-target="#MODAL-shareEmail"><i class="icon-envelope text-contrast"></i></b>
                    </span>
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
                $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,c.fecha,c.id_modalidad FROM cursos c WHERE estado='1' AND sw_flag_cursosbo='1' AND (id_ciudad='$curso_id_ciudad' OR id_modalidad='2') ORDER BY fecha DESC ");
                while ($rc2 = fetch($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $fecha_de_curso = fecha_corta($rc2['fecha']);
                    $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                    if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                        $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rc2['imagen'];
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
$cnt_reproducciones = $curso['cnt_reproducciones'] + 1;
query("UPDATE blog SET cnt_reproducciones='$cnt_reproducciones' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

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


