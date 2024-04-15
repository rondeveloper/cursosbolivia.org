<?php
$rqdp1 = query("SELECT * FROM cursos_paginas WHERE nombre='QUIENES-SOMOS' limit 1 ");
$rqdp2 = fetch($rqdp1);
$titulo = $rqdp2['titulo'];
$contenido = str_replace(array('[PAGE-NAME]'),array($___nombre_del_sitio),$rqdp2['contenido']);
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3><?php echo $titulo; ?></h3>
                </div>
                <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                    <?php echo $contenido; ?>
                    <div>
                        <b>Enlaces de informaci&oacute;n:</b>
                        &nbsp; 
                        <a href="politica-de-privacidad.html">Pol&iacute;tica de privacidad</a> | <a href="contacto.html">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

