<?php
$titulo_identificador_departamento = $get[2];

$rqc1 = query("SELECT * FROM departamentos WHERE titulo_identificador='$titulo_identificador_departamento' LIMIT 1 ");
$rqc2 = fetch($rqc1);

$titulo_departamento = $rqc2['nombre'];
$id_departamento = $rqc2['id'];

$rqddv1 = query("SELECT ids_departamentos_visibles FROM cursos_webdata WHERE id='1' LIMIT 1 ");
$rqddv2 = fetch($rqddv1);
$ids_departamentos_visibles = $rqddv2['ids_departamentos_visibles'];
?>
<div class="gris">
    <section class="container">

        <br/>
        <br/>
        <div class="col-md-12">
            <h1 class="titulo-second-f1-cat">CURSOS EN <?php echo strtoupper($titulo_departamento); ?></h1>
        </div>
        <br/>

        <?php
        $qr_visibilidad_departamentos = '';
        if($ids_departamentos_visibles!==''){
            //*$qr_visibilidad_departamentos = " AND id_departamento IN ($ids_departamentos_visibles) ";
        }
        $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,(ct.titulo)categoria,c.fecha FROM cursos c INNER JOIN cursos_categorias ct ON c.id_categoria=ct.id WHERE c.estado IN (1) AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento' $qr_visibilidad_departamentos) ORDER BY fecha DESC ");

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
    
    <?php
    if(false){
    ?>

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
    
    <?php
    }
    ?>
    
</div>

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
