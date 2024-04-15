<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>CURSOS RECOMENDADOS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Estimado usuario seg&uacute;n la configuraci&oacute;n de su cuenta le mostramos a continuaci&oacute;n los cursos diponibles que podrian ser de su inter&eacute;s.
                    </p>
                </div>
                
                <?php
                $rc1 = query("SELECT c.titulo,c.titulo_identificador,c.imagen,c.fecha,c.id_modalidad FROM cursos c WHERE estado='1' AND (id_ciudad IN (select id from ciudades where id_departamento='$id_departamento_usuario') OR id_modalidad='2') ORDER BY fecha DESC ");
                while ($rc2 = fetch($rc1)) {
                    $titulo_de_curso = $rc2['titulo'];
                    $fecha_de_curso = date("d/M",strtotime($rc2['fecha']));
                    $url_imagen_curso = $dominio_www."contenido/imagenes/paginas/" . $rc2['imagen'];
                    $url_pagina_curso = $dominio . $rc2['titulo_identificador'] . ".html";
                    ?>
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <a href="<?php echo $url_pagina_curso; ?>" title="" target="_blank">
                                <img src="<?php echo $url_imagen_curso; ?>" class="img-responsive">
                            </a>
                        </div>
                        <div class="hidden-xs col-sm-8 col-md-8">
                            <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso" target="_blank"><?php echo $titulo_de_curso; ?></a>
                            <div class="texto_lista_curso" align="justify"><?php echo $titulo_de_curso; ?> <?php echo $titulo_de_curso; ?>...</div>
                        </div>
                        <div class="hidden-sm hidden-md hidden-lg col-xs-7">
                            <a href="<?php echo $url_pagina_curso; ?>" title="" class="EnlaceCurso" target="_blank"><?php echo $titulo_de_curso; ?></a>
                            <div class="hidden-md texto_lista_curso" align="justify"><?php echo $titulo_de_curso; ?> ...</div>
                        </div>  
                        <?php
                        if ($rc2['id_modalidad'] == '2') {
                            ?>
                            <div class="col-xs-3 col-sm-2  col-md-2 " align="center">
                                <div class="tit_duracion hidden-xs">VIRTUAL</div>
                                <div class="tit_horas">Disponible</div>
                                <a class="btn btn-default orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>" target="_blank">Ver m&aacute;s</a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="col-xs-3 col-sm-2  col-md-2 " align="center">
                                <div class="tit_duracion hidden-xs">FECHA</div>
                                <div class="tit_horas"><?php echo $fecha_de_curso; ?></div>
                                <a class="btn btn-default orange tit_ver_mas" href="<?php echo $url_pagina_curso; ?>" target="_blank">Ver m&aacute;s</a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <hr style="border:1px solid #ccc">
                    <?php
                }
                ?>

                <br/>
                <br/>
                <br/>
                <hr/>
                <br/>
                <br/>
                <br/>
            </div>

        </div>

    </section>
</div>                     



<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
?>