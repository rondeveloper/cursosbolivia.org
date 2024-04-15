<?php
/* dato */
$titulo_identificador = $get[2];

$rqdor1 = query("SELECT *,(select nombre from departamentos where id=cursos_organizadores.id_departamento)departamento FROM cursos_organizadores WHERE estado IN ('1','2') AND titulo_identificador='$titulo_identificador' LIMIT 1 ");
$organizador = fetch($rqdor1);
?>

<style>

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



    button.accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    button.accordion.active, button.accordion:hover {
        background-color: #ddd;
    }

    button.accordion:after {
        content: '\02795';
        font-size: 13px;
        color: #777;
        float: right;
        margin-left: 5px;
    }

    button.accordion.active:after {
        content: "\2796";
    }

    div.panel {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: 0.6s ease-in-out;
        opacity: 0;
    }

    div.panel.show {
        opacity: 1;
        max-height: 15000px;  
    }

    .buttonblack:hover,.buttoncolor:hover {
        /*	background: #555;*/
        background:none;
        color: #000;
    }


</style>

<div class="wrapsemibox" style="margin-top: 60px;">


    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12" style="padding-right:20px">
                <br>
                <div class="row">
                    <div class="TituloContenidoCursosinLinea">
                        <h2><?php echo $organizador['nombre_extendido']; ?></h2>
                    </div>
                    <div class="row" style=" background-color:#F6F6F6;padding:13px 10px 7px 5px; border-bottom:1px solid #999; border-top:1px solid #999">
                        <div class="col-md-6">
                            <div style="height:5px"></div>
                            <div>  
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto"> Codigo</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['codigo']; ?></div>
                                <div style="height:25px"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto">N.I.T.</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['nit']; ?></div>
                                <div style="height:25px"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto">Direcci&oacute;n</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['direccion']; ?></div>
                                <div style="height:25px"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto">Tel&eacute;fono</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['telefono']; ?></div>
                                <div style="height:25px"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto">Correo</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['correo']; ?></div>
                                <div style="height:25px"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 Titulo_texto">Ubicaci&oacute;n</div>
                                <div class="col-xs-6 col-sm-6  col-md-6 Titulo_texto1"><?php echo $organizador['departamento']; ?> - Bolivia</div>
                                <div style="height:25px"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style=" background-color:#F6F6F6; border-radius:7px; padding:7px 12px 10px 12px; color:#000;text-align:center;">
                                <img src="contenido/imagenes/organizadores/<?php echo $organizador['imagen']; ?>" style="width:80%;"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="height:20px"></div>


                <!----------------------------------------------------------------->
                <!----------------------------------------------------------------->

                <!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

                <button class="accordion active"><i class="icon-group text-contrast"></i> Presentaci&oacute;n</button>
                <div class="panel show">
                    <p class="Titulo_texto1"></p>
                    <p><?php echo $organizador['descripcion']; ?></p>
                    <br/>
                </div>

                <button class="accordion active"><i class="icon-list-ol text-contrast"></i> Cursos&nbsp;&nbsp;&nbsp;</button>
                <div class="panel show">
                    <p class="Titulo_texto1"> </p>
                    <?php
                    $rc1 = query("SELECT c.id,c.titulo,c.titulo_identificador,c.imagen,c.fecha FROM cursos c WHERE estado='1' AND sw_flag_cursosbo='1' AND id_organizador='" . $organizador['id'] . "' ORDER BY fecha DESC ");
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
                        /* url curso */
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
                            <div class="col-xs-3 col-sm-2  col-md-2 " align="center">
                                <div class="tit_duracion hidden-xs">FECHA</div>
                                <div class="tit_horas"><?php echo $fecha_de_curso; ?></div>
                                <a class="btn btn-default orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>">Ver m&aacute;s</a>
                            </div>
                        </div>
                        <hr style="border:1px solid #ccc">
                        <?php
                    }
                    ?>
                    <br/>
                </div>

                <button class="accordion active"><i class="icon-pencil text-contrast"></i> Ubicaci&oacute;n por Google Maps</button>
                <div class="panel show">
                    <p class="Titulo_texto1"> </p>
                    <?php
                    if (strlen($organizador['google_maps']) > 30) {
                        echo str_replace('<iframe ', '<iframe style="width:100% !important;" ', $organizador['google_maps']);
                    } else {
                        echo "No se registro ubicaci&ocute;n en Google Maps";
                    }
                    ?>
                </div>


                <br/>
                <br/>
                <br/>

            </div>


        </div>
    </section>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">INSCRIPCI&Oacute;N DE PARTICIPANTES</h4>
            </div>
            <div class="modal-body">
                <p>
                    Para poder realizar la inscripci&oacute;n al curso, es necesario que ingreses a tu cuenta en la plataforma <b><?php echo $___nombre_del_sitio; ?></b>
                </p>
                <div>
                    <div class="boxForm">
                        <h5>INGRESA A TU CUENTA</h5>
                        <hr/>
                        <form action="ingreso-de-usuarios/curso/<?php echo $id_curso; ?>.html" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="email" name="email" placeholder="Correo electr&oacute;nico..." required="">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                </div>
                            </div>                    					
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR A MI CUENTA"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <span><b style="font-weight:bold;">&iquest; No tienes una cuenta ?</b> registrate con el siguiente enlace:</span>
                                <br/>
                                <br/>
                                <div class="col-md-12 text-center">
                                    <a href="registro-de-usuarios/curso/<?php echo $id_curso; ?>.html" type="submit" rel="nofollow" class="btn btn-primary">CREAR UNA CUENTA</a>
                                </div>
                            </div>
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


<?php

function fecha_corta($data) {
    return date("d / m", strtotime($data));
}
?>