<?php
$titulo_identificador_departamento = $get[2];

$rqc1 = query("SELECT * FROM departamentos WHERE titulo_identificador='$titulo_identificador_departamento' LIMIT 1 ");
$rqc2 = fetch($rqc1);

$titulo_departamento = $rqc2['nombre'];
$id_departamento = $rqc2['id'];
?>


<style>
    .grow:hover
    {
        -webkit-transform: scale(1.2);
        -ms-transform: scale(1.2);
        transform: scale(1.2);
    }

    /*-----------------------*/
    .space_img
    {
        padding:5px;
    }
    /*-----------------------*/
    .fnd_CatalogoCurso

    {

        background-image:url(../images/FndCatalogo.jpg);

    }



</style>

<style>
    .wellPaqueteMain {
        min-height: 40px;
        margin-bottom: 0px;
        border: 5px solid #fff;
        border-radius: 2px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    }

    .wellPaquete {
        min-height: 20px;
        padding: 7px;
        margin-bottom: 0px;
        background-color: #ECF0F1;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    }




    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 8px 5px 2px 5px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 0px;
    }



    /****************************************************/

    .panel-info {
        border-color: #bce8f1;
    }
    .panel-info > .panel-heading {
        background-color: #0169B2;
        border-color: #bce8f1;
        color: #fff;
    }


    .panel-primary {
        border-color: #337ab7;
    }
    .panel {
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        margin-bottom: 0px;
    }


    /*---------------------*/



    mas.accordion {
        /*  background-color: #eee;*/
        padding-top:3px;
        color: #444;
        cursor: pointer;
        /* padding: 18px;*/
        width: 3%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    mas.accordion.active, mas.accordion:hover {
        /*  background-color: #ddd;*/
    }

    mas.accordion:after {
        margin-top:-20px;
        margin-right:-30px;
        content: '\25bc';
        font-size: 13px;
        color: #777;
        float: right;
        margin-left: 5px;
        color:#f04343;
    }

    mas.accordion.active:after {
        content: "\25b2";
    }

    div.panel {
        margin-top:-22px;
        padding: 0 2px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: 0.6s ease-in-out;
        opacity: 0;
        border:1px solid #f0f0f0;
    }



    @media screen and (max-width:65.375em) {
        div.panel {
            margin-top:-22px;
            padding: 0 2px;
            background-color: white;
            overflow: hidden;
            transition: 0.6s ease-in-out;
            opacity: 1;
            max-height: 15000px;  
            border:1px solid #f0f0f0;
        }

    }
    div.panel.show {
        opacity: 1;
        max-height: 15000px;  
    }

    .buttonblack:hover,.buttoncolor:hover {
        background: #555;
        background:none;
        color: #000;
    }

    mas_cel.accordion {
        /*  background-color: #eee;*/
        padding-top:0px;
        color: #444;
        cursor: pointer;
        /* padding: 18px;*/
        width: 3%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    mas_cel.accordion.active, mas_cel.accordion:hover {
        /*  background-color: #ddd;*/
    }

    mas_cel.accordion:after {
        content: "\25b2";
        font-size: 13px;
        color: #777;
        float: right;
        margin-left: 5px;
        color:#f04343;
    }

    mas_cel.accordion.active:after {
        content: "\2796";
    }


</style>


<style>

    .list-group-item {
        min-height:90px;
    }
    .abajo{
        position:absolute;
        bottom:0;
    }

    /***************************************/
    /*--------------------------*/	
    A.Enlace_Curso_Main{
        z-index:20000;
        font-size:18px;
        color: #000;
        line-height:20px;
        max-height:100px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main:hover {
        color: #034C9B;
        text-decoration:none;
    }
    /*--------------------------*/	
    A.Enlace_Curso_Main_Cel{
        z-index:20000;
        font-size:13px;
        color: #000;
        line-height:15px;
        max-height:40px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main_Cel:hover {
        color: #034C9B;
        text-decoration:none;
    }
    /*--------------------------*/	


    A.Enlace_Curso_Main_Titulo{
        margin-top:30px;
        z-index:20000;
        font-size:18px;
        color: #000;
        line-height:20px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main_Titulo:hover {
        color: #034C9B;
        text-decoration:none;
    }

    @media only screen and (max-width: 979px){

        .list-group-item {
            min-height:90px;
        }

        .list-group-item-cel {
            min-height:170px;
        }

        .list-group-item {
            background-color: #fff;
            border: 1px solid #ddd;
            display: block;
            margin-bottom: -1px;
            padding: 3px 5px;
            position: relative;
        }

        .list-group-item-cel {
            background-color: #fff;
            border: 1px solid #ddd;
            display: block;
            margin-bottom: -1px;
            padding: 3px 5px;
            position: relative;
        }



    }


    /* aux css */
    .courses-three-devider{
        clear:both;
        width:100%;
        height:0px;
    }
    .courses-two-devider{

    }



</style>


<style>
    .bx-img-curso-f1{
        background: #FFF;
        height: 230px;
        overflow: hidden;
    }
    .img-curso-f1{
        max-height: 230px;
        height: 100%;
        width:100%;
        transition: 1.2s;
    }
    .img-curso-f1:hover{
        transform: rotate(-2deg);
        max-width:120%;
        width:120%;
        max-height: 120%;
        height: 120%;
        margin-left: -10px;
        margin-top: -10px;
        transition: 1.2s;
    }
    .titulo-curso-f1{
        height:70px;
        overflow: hidden;
    }
    .titulo-second-f1{
        background: #258fad;
        border-radius: 7px;
        font-size: 16pt;
        color: #FFF;
        padding: 8px 0px;
        border-bottom: 2px solid #1b5b77;
        text-align: center;
        margin-bottom: 25px;
    }
    @media (max-width: 768px) {
        .bx-img-curso-f1{
            height: 120px;
        }
        .titulo-curso-f1{
            height:80px;
        }
    }
</style>

<div class="gris">
    <section class="container">

        <br/>
        <br/>
        <div class="col-md-12">
            <h1 class="titulo-second-f1">CURSOS EN <?php echo strtoupper($titulo_departamento); ?></h1>
        </div>
        <br/>

        <?php
        $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,(ct.titulo)categoria,c.fecha FROM cursos c INNER JOIN cursos_categorias ct ON c.id_categoria=ct.id WHERE c.estado IN (1) AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ORDER BY fecha DESC ");

        if (num_rows($rc1) == 0) {
            echo "<div class='col-md-12'>";
            echo "<div class=''>";
            echo "<div class='panel-body'>";
            echo "<p>No hay cursos disponibles en este departamento por el momento.</p><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        $counter_aux = 0;

        while ($rc2 = fetch($rc1)) {
            $titulo_de_curso = $rc2['titulo'];
            $categoria_curso = $rc2['categoria'];
            $fecha_curso = fecha_curso($rc2['fecha']);
            $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
            if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rc2['imagen'];
            }
            $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
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
                        <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $categoria_curso; ?></div>                            
                    </div>						
                    <div class="row hi hidden-sm hidden-md hidden-lg">	    
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $categoria_curso; ?></div>                            
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
                            <div class="col-md-4 col-sx-4 hi hidden-xs" align="right"><i class="icon-screenshot"></i> <?php echo $categoria_curso; ?></div>                            
                        </div>						
                        <div class="row hi hidden-sm hidden-md hidden-lg">	    
                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $categoria_curso; ?></div>                            
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

    <section class="container">
        <div style="background: #FFF;padding:20px;border:1px solid #DDD;">
            <p style="margin:0px;">Cursos realizados en fechas anteriores.</p>
        </div>
        <br/>
    </section>

    <section class="container">
        <div class="row">
            <?php
            $rc1 = query("SELECT c.id,c.titulo,c.titulo_identificador,c.imagen,c.fecha FROM cursos c WHERE estado='0' AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ORDER BY fecha DESC limit 10 ");
            if (num_rows($rc1) == 0) {
                echo "<p>No se encontraron cursos vigentes registrados para este organizador.</p>";
            }
            while ($rc2 = fetch($rc1)) {
                $titulo_de_curso = $rc2['titulo'];
                $fecha_de_curso = fecha_corta($rc2['fecha']);
                $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                    $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rc2['imagen'];
                }
                $url_pagina_curso = "" . $rc2['titulo_identificador'] . "/".$rc2['id'].".html";
                ?>
                <div class="col-md-6">
                    <div style="background: #FFF;padding:20px;border:1px solid #DDD;margin:5px 0px;min-height:120px;">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <a href="<?php echo $url_pagina_curso; ?>" title="">
                                    <img src="<?php echo $url_imagen_curso; ?>" class="img-responsive">
                                </a>
                            </div>
                            <div class="hidden-xs col-sm-9 col-md-9">
                                <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso"><?php echo $titulo_de_curso; ?></a>
                                <div class="texto_lista_curso" align="justify">
                                    Fecha: <?php echo $fecha_de_curso; ?> &nbsp;&nbsp;
                                    <a class="btn btn-default btn-xs orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>">Ver m&aacute;s</a>
                                </div>
                            </div>
                            <div class="hidden-sm hidden-md hidden-lg col-xs-9">
                                <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso"><?php echo $titulo_de_curso; ?></a>
                                <div class="hidden-md texto_lista_curso" align="justify">
                                    Fecha: <?php echo $fecha_de_curso; ?> &nbsp;&nbsp;
                                    <a class="btn btn-default btn-xs orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>">Ver m&aacute;s</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <hr style="border:1px solid #ccc">
    </section>


</div>

<style>

    .list-group-item {
        min-height:90px;
    }
    .abajo{
        position:absolute;
        bottom:0;
    }

    /***************************************/
    /*--------------------------*/	
    A.Enlace_Curso_Main{
        z-index:20000;
        font-size:18px;
        color: #000;
        line-height:20px;
        max-height:100px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main:hover {
        color: #034C9B;
        text-decoration:none;
    }
    /*--------------------------*/	
    A.Enlace_Curso_Main_Cel{
        z-index:20000;
        font-size:13px;
        color: #000;
        line-height:15px;
        max-height:40px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main_Cel:hover {
        color: #034C9B;
        text-decoration:none;
    }
    /*--------------------------*/	


    A.Enlace_Curso_Main_Titulo{
        margin-top:30px;
        z-index:20000;
        font-size:18px;
        color: #000;
        line-height:20px;
        text-align: left;
        text-decoration: none;
        font-weight: normal;
    }
    A.Enlace_Curso_Main_Titulo:hover {
        color: #034C9B;
        text-decoration:none;
    }

    @media only screen and (max-width: 979px){

        .list-group-item {
            min-height:90px;
        }

        .list-group-item-cel {
            min-height:170px;
        }

        .list-group-item {
            background-color: #fff;
            border: 1px solid #ddd;
            display: block;
            margin-bottom: -1px;
            padding: 3px 5px;
            position: relative;
        }

        .list-group-item-cel {
            background-color: #fff;
            border: 1px solid #ddd;
            display: block;
            margin-bottom: -1px;
            padding: 3px 5px;
            position: relative;
        }



    }
</style>


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
    return date("d M", strtotime($data));
}
