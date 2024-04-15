<?php
?>
<div class="gris">
    <section class="container">

        <br/>
        <br/>
        <div class="col-md-12">
            <h1 class="titulo-second-f1-cat">COMUNICADOS</h1>
        </div>
        <br/>

        <?php
        $qr_visibilidad_departamentos = '';
        $rc1 = query("SELECT * FROM blog WHERE estado IN (1) ");

        if (num_rows($rc1) == 0) {
            ?>
            <hr>
            <br>
            <div class="row">
                <div class="alert alert-info">
                    <strong>SIN COMUNICADOS</strong>
                    <br>
                    No hay comunicados vigentes por el momento.
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <?php
        }

        $counter_aux = 0;

        while ($rc2 = fetch($rc1)) {
            $titulo_de_curso = $rc2['titulo'];
            $categoria_curso = $rc2['categoria'];
            $fecha_curso = fecha_curso($rc2['fecha']);
            $url_imagen_curso = "contenido/imagenes/blog/" . $rc2['imagen'];
            $url_pagina_curso = "" . $rc2['titulo_identificador'] . "/";
            ?>
            <div class="col-xs-12 col-sm-6 col-md-4" align="left">
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
                        <div class="col-md-12 col-sx-12 hi hidden-xs"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                    </div>
                    <div class="row hi hidden-sm hidden-md hidden-lg">	    
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                        <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg" align="left"><i class="icon-screenshot"></i> <?php echo $categoria_curso; ?></div>                            
                    </div>
                    <div class="row hi hidden-xs ">
                        <div class="blog-meta">
                            <div class="col-md-12 hi hidden-xs" align="right">
                                <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink rojo btn-block"> <i class="icon-edit text-contrast"></i> Ver comunicado</a>
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
                            <div class="col-md-12 col-sx-12 hi hidden-xs"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                        </div>
                        <div class="row hi hidden-sm hidden-md hidden-lg">	    
                            <div class="col-md-12 col-sx-12 hi hidden-sm hidden-md hidden-lg"><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
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
        <br>
    </section>

</div>
<br><br><br><br><br><br><br><br><br><br>

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
