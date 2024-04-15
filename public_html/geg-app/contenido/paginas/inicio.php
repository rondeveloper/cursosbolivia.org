
<style>
    h1{
        background: #b0b1bd;
        border-radius: 7px;
        font-size: 16pt;
        color: #FFF;
        padding: 8px 0px;
        border-bottom: 2px solid #365bb1;
        text-align: center;
        margin-bottom: 25px;
        clear: both;
    }
</style>

<section class="carousel carousel-fade slide home-slider" id="c-slide" data-ride="carousel" data-interval="4500" data-pause="false">
    <ol class="carousel-indicators">
        <!-- <li data-target="#c-slide" data-slide-to="0" class="active"></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="1" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="2" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="3" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="4" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="5" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="6" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="7" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="8" class=""></li>-->
    </ol>
    <div class="carousel-inner">
        <div class="item active">
            <img src="contenido/imagenes/banners/geg-banner.PNG" style="width:100%;" class="img-responsive" alt="GEG BOLIVIA">
        </div>
    </div>
    <a class="left carousel-control animated fadeInLeft" href="https://cursos.bo/#c-slide" data-slide="prev"><i class="icon-angle-left"></i></a>
    <a class="right carousel-control animated fadeInRight" href="https://cursos.bo/#c-slide" data-slide="next"><i class="icon-angle-right"></i></a>
</section>

<div class="gris">
    <section class="container container-home">

        <style>
            .box-tab-depart{
                float:left;
                width:10%;
                margin-left: 1%;
            }
            .tab-depart{
                border: 1px solid #e8e8e8;
                padding: 3px 10px;
                border-radius: 8px;
                overflow: hidden;
                background: #FFF;
                box-shadow: 3px 3px 2px 0px #e8e8e8;
                margin-bottom: 5px;
                transition: .5s;
            }
            .img-tab-depart{
                background: #f7f7f7;
                width: 100%;
                border-radius: 5px;
            }
            .title-tab-depart{
                font-size: 12pt;
                color: #a5a5a5;
                transition: .5s;
            }
            .tab-depart:hover{
                background: gray;
                transition: .5s;
            }
            .tab-depart:hover .title-tab-depart{
                color: #FFF;
                transition: .5s;
            }

            .box-label-ciudad{
                background: rgba(255, 0, 0, 0.92);
                color: #FFf;
                width: 110px;
                position: relative;
                top: -230px;
                float: right;
                text-align: center;
                font-weight: bold;
                border-radius: 0px 0px 0px 5px;
                padding: 5px 0px;
                border-bottom: 1px solid #f7f7f7;
                border-left: 1px solid #f7f7f7;
                box-shadow: -1px 1px 3px 0px rgba(128, 128, 128, 0.43);
                transition: .3s;
            }
            .box-label-costo{
                background: rgba(255, 0, 0, 0.92);
                color: #FFf;
                width: 70px;
                position: relative;
                top: -30px;
                float: left;
                text-align: center;
                font-weight: bold;
                border-radius: 0px 5px 0px 0px;
                padding: 4px 0px;
                border-top: 1px solid #f7f7f7;
                border-right: 1px solid #f7f7f7;
                box-shadow: -1px 1px 3px 0px rgba(128, 128, 128, 0.43);
                transition: .3s;
            }
            .h2-to-normal{
                margin: 0px;
                font-size: 12pt;
                color: #000;
                font-weight: normal;
            }
            /*            .blog-post-short:hover .box-label-ciudad{
                            background: rgba(255, 0, 0, 0.86);
                            transition: .0s;
                        }*/
            @media (max-width: 770px){
                .box-label-ciudad {
                    top: -120px;
                    padding: 2px 0px;
                    width: 65px;
                    font-size: 6pt;
                }
                .box-label-costo{
                    top: -20px;
                    padding: 2px 0px;
                    width: 40px;
                }
            }
        </style>

        <?php if (false) { ?>
            <div class="row hidden-xs" style="margin-top: 20px;">
                <?php
                $rqdd1 = query("SELECT nombre,cod,titulo_identificador,imagen FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                while ($rqdd2 = mysql_fetch_array($rqdd1)) {
                    ?>
                    <a href="cursos-en-<?php echo $rqdd2['titulo_identificador']; ?>.html" title="CURSOS EN <?php echo strtoupper($rqdd2['nombre']); ?>">
                        <div class="box-tab-depart">
                            <div class="tab-depart">
                                <div class="row">
                                    <div class="col-md-5 col-xs-5 text-center">
                                        <img src="contenido/imagenes/departamentos/<?php echo $rqdd2['imagen']; ?>" alt="Cursos en <?php echo $rqdd2['nombre']; ?>" class="img-responsive space_img grow img-tab-depart"/>
                                    </div>
                                    <div class="col-md-7 col-xs-7 text-center" style="padding-top: 4px;">
                                        <b class="title-tab-depart"><?php echo $rqdd2['cod']; ?></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div>

            <div class="row hidden-lg" style="margin-top: 15px;text-align:center;">
                <?php
                $rqdd1 = query("SELECT nombre,cod,titulo_identificador,imagen FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                while ($rqdd2 = mysql_fetch_array($rqdd1)) {
                    ?>
                    <a href="cursos-en-<?php echo $rqdd2['titulo_identificador']; ?>.html" title="CURSOS EN <?php echo strtoupper($rqdd2['nombre']); ?>" class="btn btn-xs btn-info" style="padding: 3px 6px;">
                        <?php echo $rqdd2['cod']; ?>
                    </a> &nbsp;
                    <?php
                }
                ?>
            </div>
        <?php } ?>

        <?php if (false) { ?>
            <div class="search-box">
                <form action="buscador.html" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="buscar" class="form-control" value="" placeholder="Introduce el criterio de busqueda..."/>
                        </div>
                        <div class="col-md-2">
                            <select name="departamento" id="select_departamento" class="form-control" onchange="actualiza_ciudades();">
                                <option value="0">Todos los departamentos</option>
                                <?php
                                $rqdc1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                while ($rqdc2 = mysql_fetch_array($rqdc1)) {
                                    ?>
                                    <option value="<?php echo $rqdc2['id']; ?>"><?php echo $rqdc2['nombre']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="ciudad" id="select_ciudad" class="form-control">
                                <option value="0">Todas las ciudades</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-danger" value="BUSCAR"/>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>


        <style>
            .cont-short-couses{
                clear: both;
                max-height: 650px;
                overflow: hidden;
            }
            .cover-short-courses{
                background: white;
                box-shadow: 0px -16px 20px 20px white;
                height: 100px;
                text-align: center;
                padding: 30px;
                position: absolute;
                width: 1160px;
                max-width: 100%;
                margin-top: -100px;
                opacity: .95;
                cursor: pointer;
                border-bottom: 1px solid #258fad;
                transition: .3s;
            }
            .cover-short-courses:hover{
                opacity: .99;
                border-bottom: 3px solid #258fad;
                transition: .3s;
            }
            .cover-short-courses:hover .cover-short-courses-label{
                text-decoration: underline;
                text-decoration-color: #DDD;
            }
            .cover-short-courses-label{
                font-size: 25pt;
                color: #258fad;
            }
        </style>

        
        <div class="col-md-12">
            <h1>SIGUIENTE SESI&Oacute;N EN VIVO</h1>
        </div>
        <div>
            <iframe style="width: 100%;height: 540px;" src="https://www.youtube-nocookie.com/embed/A8vYRm6if6o" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>


        <div class="col-md-12">
            <h1>CERTIFICADOS HABILITADOS</h1>
        </div>

        <br/>

        <?php
        $counter_aux = 0;

        /* cursos comunes */
        $rcv1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,c.imagen_gif,(cd.nombre)ciudad,c.fecha,c.horarios,c.sw_fecha,c.id_modalidad,c.costo,c.costo2,c.costo3,c.fecha2,c.sw_fecha2,c.fecha3,c.sw_fecha3 FROM cursos c INNER JOIN ciudades cd ON c.id_ciudad=cd.id WHERE c.estado IN (1) AND c.sw_siguiente_semana='0' AND c.sw_flag_cursosbo='1' AND c.columna='1' AND c.id_modalidad IN (2,3) ORDER BY c.fecha ASC ");
        while ($rc2 = mysql_fetch_array($rcv1)) {
            $titulo_de_curso = $rc2['titulo'];
            $ciudad_curso = $rc2['ciudad'];
            $fecha_curso = fecha_curso($rc2['fecha']);
            if ($rc2['id_modalidad'] == '2') {
                $fecha_curso = 'DISPONIBLE AHORA';
            }
            $horarios = $rc2['horarios'];
            if ($rc2['imagen_gif'] == '') {
                $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
            } else {
                $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen_gif'];
            }
            $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
            //$url_pagina_curso = $dominio;
            $modalidad_curso = "PRESENCIAL";
            $icon_modalidad_curso = "icon-bookmark";
            if ($rc2['id_modalidad'] == '2') {
                $modalidad_curso = "VIRTUAL";
                $icon_modalidad_curso = "fa fa-street-view";
                $ciudad_curso = 'TODA BOLIVIA';
            } elseif ($rc2['id_modalidad'] == '3') {
                $modalidad_curso = "VIRTUAL";
                $icon_modalidad_curso = "fa fa-street-view";
            }

            /* costo */
            $costo_curso = $rc2['costo'];
            $f_h = date("H:i", strtotime($rc2['fecha2']));
            if ($f_h !== '00:00') {
                $f_actual = strtotime(date("Y-m-d H:i"));
                $f_limite = strtotime($rc2['fecha2']);
            } else {
                $f_actual = strtotime(date("Y-m-d"));
                $f_limite = strtotime(substr($rc2['fecha2'], 0, 10));
            }
            $texto_descuento = 'TODA BOLIVIA';
//            if ($rc2['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
//                $texto_descuento = $rc2['costo2'] . ' Bs hasta el ' . date("d/m", strtotime($rc2['fecha2']));
//            }
//            if ($rc2['sw_fecha3'] == '1' && ( date("Y-m-d") <= $rc2['fecha3'])) {
//                $texto_descuento = $rc2['costo3'] . ' Bs hasta el ' . date("d/m", strtotime($rc2['fecha3']));
//            }
            ?>
            <div class="col-xs-6 col-sm-6 col-md-4" align="left">
                <div class="blog-post-short">
                    <div class="img-holder">
                        <div class="bg-img-holder bx-img-curso-f1">
                            <a href="<?php echo $url_pagina_curso; ?>">
                                <img src="<?php echo $url_imagen_curso; ?>" alt="<?php echo $titulo_de_curso; ?>" title="<?php echo $titulo_de_curso; ?>" class="img-responsive grafico img-curso-f1" />
                            </a>
                        </div>
                    </div>
<!--                    <div class="box-label-ciudad"><?php echo strtoupper($ciudad_curso); ?></div>-->
                    <div class="box-label-costo">Gratuito</div>
                </div>
                <!----->		
                <div class="blog-post-short list-group-item hidden-xs">	 
                    <div class="row">
                        <div class="col-md-12 hidden-xs titulo-curso-f1">
                            <h2 class="h2-to-normal"><a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Titulo"><?php echo $titulo_de_curso; ?></a></h2>
                        </div>
                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg titulo-curso-f1">
                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Cel"><?php echo $titulo_de_curso; ?></a>
                        </div>
                    </div>
                    <div class="row hi hidden-xs">	    
                        <div class="col-md-6 col-sx-6 hi hidden-xs"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                        <div class="col-md-6 col-sx-6 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $texto_descuento; ?></div>  

                        <div class="col-md-8 col-sx-8 hi hidden-xs"><i class="icon-time"></i> <?php echo $horarios; ?></div>
                        <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><b style="color:red;"><i class="<?php echo $icon_modalidad_curso; ?>"></i> <?php echo $modalidad_curso; ?></b></div>  
                    </div>						
                    <div class="row hi hidden-sm hidden-md hidden-lg">	    
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>                            
                    </div>						
                    <div class="row hi hidden-xs ">	    	
                        <div class="blog-meta">
                            <div class="col-md-12 hi hidden-xs" align="right">
                                <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo btn-block"> <i class="icon-edit text-contrast"></i> INGRESAR</a>
                            </div>
                        </div>  
                    </div>
                    <div class="row hi hidden-sm hidden-md hidden-lg">	    	
                        <div class="blog-meta">
                            <div class="col-md-12 hi hidden-sm hidden-md hidden-lg" align="center">
                                <a href="<?php echo $url_pagina_curso; ?>" class="buttonlinkcel rojo"> <i class="icon-edit text-contrast"></i> Certificado</a>
                            </div>
                        </div>  
                    </div>
                </div>		
                <!------------------------------------->
                <div class="blog-post-short list-group-item-cel hidden-sm hidden-md hidden-lg">		 
                    <div class="row">
                        <div class="col-md-12 hidden-xs">
                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Titulo"><?php echo trim(str_replace($ciudad_curso, '', str_replace('en ' . $ciudad_curso, '', $titulo_de_curso))); ?></a>
                        </div>
                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg titulo-curso-f1">
                            <a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Cel"><?php echo trim(str_replace($ciudad_curso, '', str_replace('en ' . $ciudad_curso, '', $titulo_de_curso))); ?></a>
                        </div>
                    </div>
                    <div>	
                        <div class="row hi hidden-xs">	    
                            <div class="col-md-8 col-sx-8 hi hidden-xs"><i class="icon-calendar"></i> <?php echo trim(str_replace('de ' . date("Y"), '', $fecha_curso)); ?></div>
                            <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>                            
                        </div>						
                        <div class="row hi hidden-sm hidden-md hidden-lg">	    
                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $texto_descuento; ?></div>                            
                        </div>						
                        <div class="row hi hidden-xs ">	    	
                            <div class="blog-meta">
                                <div class="col-md-7 tope hi hidden-xs"></div>
                                <div class="col-md-5 hi hidden-xs" align="right">
                                    <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo">  <i class="icon-edit text-contrast"></i> Certificado</a>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row hi hidden-sm hidden-md hidden-lg toBottom">	    	
                        <div class="col-md-12 hi hidden-sm hidden-md hidden-lg">
                            <a href="<?php echo $url_pagina_curso; ?>" class="buttonlinkcel orangecel">  <i class="icon-edit text-contrast"></i>  Certificado</a>
                        </div>
                    </div>  
                </div>
                <!------------------------------------->
                <br>
            </div>
            <?php
            $counter_aux++;

            if ($counter_aux % 3 == 0) {
                echo "<div class='courses-three-devider hidden-xs'></div>";
            }
            if ($counter_aux % 2 == 0) {
                echo "<div class='courses-two-devider'></div>";
            }
        }
        ?>
        <!--                        <div class="hidden-xs"></div>
                                <div class="hidden-md hidden-lg"><div class="clearfix"></div></div>
        
        
                                <div class="hidden-xs"><div class="clearfix"></div></div>
                                <div class="hidden-md hidden-lg"></div>
                                
                                <div class="hidden-xs"></div>
                                <div class="hidden-md hidden-lg"></div>-->
        <br>
    </section>
</div>

<?php
$rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,(cd.nombre)ciudad,c.fecha,c.horarios,c.id_modalidad FROM cursos c INNER JOIN ciudades cd ON c.id_ciudad=cd.id WHERE c.estado='1' AND c.sw_siguiente_semana='1' AND c.sw_flag_cursosbo='1' AND c.columna='1' ORDER BY fecha ASC ");
if (mysql_num_rows($rc1) > 0) {
    ?>
    <div style="background-color:#FFF">
        <section class="container">
            <br>
            <div class="col-md-12">
                <h2 class="titulo-second-f1">CURSOS SIGUIENTE SEMANA</h2>
            </div>
            <br>
            <div class="row">
                <?php
                $counter_aux = 0;
                while ($rc2 = mysql_fetch_array($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $ciudad_curso = $rc2['ciudad'];
                    $fecha_curso = fecha_curso($rc2['fecha']);
                    $horarios = $rc2['horarios'];
                    $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                    $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
                    $modalidad_curso = "PRESENCIAL";
                    $icon_modalidad_curso = "icon-bookmark";
                    if ($rc2['id_modalidad'] == '2') {
                        $modalidad_curso = "VIRTUAL";
                        $icon_modalidad_curso = "fa fa-street-view";
                    } elseif ($rc2['id_modalidad'] == '3') {
                        $modalidad_curso = "SEMIPRESENCIAL";
                        $icon_modalidad_curso = "fa fa-street-view";
                    }
                    ?>
                    <div class="col-xs-6 col-sm-6 col-md-4" align="left">
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
                                <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>     

                                <div class="col-md-8 col-sx-8 hi hidden-xs"><i class="icon-time"></i> <?php echo $horarios; ?></div>
                                <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><b style="color:red;"><i class="<?php echo $icon_modalidad_curso; ?>"></i> <?php echo $modalidad_curso; ?></b></div>  
                            </div>
                            <div class="row hi hidden-sm hidden-md hidden-lg">	    
                                <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>                            
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
                                        <a href="<?php echo $url_pagina_curso; ?>" class="buttonlinkcel rojo"> <i class="icon-edit text-contrast"></i> Ver m&aacute;s</a></div>
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
                                    <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>
                                </div>
                                <div class="row hi hidden-sm hidden-md hidden-lg">	    
                                    <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                                    <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $ciudad_curso; ?></div>                            
                                </div>						
                                <div class="row hi hidden-xs ">	    	
                                    <div class="blog-meta">
                                        <div class="col-md-7 tope hi hidden-xs"></div>
                                        <div class="col-md-5 hi hidden-xs" align="right"><a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo">  <i class="icon-edit text-contrast"></i> Ver m&aacute;s</a></div>
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

                    if ($counter_aux % 3 == 0) {
                        echo "<div class='courses-three-devider hidden-xs'></div>";
                    }
                    if ($counter_aux % 2 == 0) {
                        echo "<div class='courses-two-devider'></div>";
                    }
                }
                ?>
            </div>
        </section>
    </div>
    <?php
}
?>







<div style="background-color:#FFF">
    <section class="container">
        <hr/>

        <style>
            .box-valida-cert{
                background: #f7f7f7;
                padding: 5px 20px;
                border: 1px solid #258fad;
                margin-bottom: 20px;
            }
        </style>
        <div class="box-valida-cert">
            <div class="row">
                <form action="validacion-de-certificado.html" method="post" id="form-valida-cert">
                    <h2 class="titulo-second-f1-a" style="clear: both;">VALIDACI&Oacute;N DE CERTIFICADOS</h2>

                    <p>
                        Mediante el sistema de validaci&oacute;n de certificados, se podr&aacute; verificar la autenticidad del certificado correspondiente emitido para cada uno de los cursos realizados, estos pueden ser solicitados por la instituci&oacute;n o persona que lo requiera.
                        <br/>
                        Para ello ingrese el <b>'ID de certificado'</b> ubicado en la parte inferior del c&oacute;digo QR del certificado emitido. IMPORTANTE: La informaci&oacute;n v&aacute;lida es la generada en la pantalla.
                    </p>
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <hr/>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                            <input type="text" name="id_certificado" id="id_certificado" value="" class="form-control" placeholder="Ingrese el ID de certificado..." aria-describedby="basic-addon1" required=""/>
                            <span class="input-group-addon" onclick="send_form_valid();" style="cursor:pointer;"><i class="fa fa-eye"></i> VALIDAR</span>
                        </div>
                        <script>
                            function send_form_valid() {
                                if (document.getElementById('id_certificado').value !== '') {
                                    document.getElementById('form-valida-cert').submit();
                                }
                            }
                        </script>
                        <hr/>
                    </div>
                </form>
            </div>
        </div>
    </section>

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
            success: function (data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>


<?php

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

function fecha_corta($data) {
    $d = date("d", strtotime($data));
    $m = date("m", strtotime($data));
    $me = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $d . " de " . $me[(int) $m];
}
