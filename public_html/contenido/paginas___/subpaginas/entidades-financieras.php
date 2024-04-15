<?php

$qr_departamento = "";
if(isset($get[2])){
    $titulo_identiicador_departamento = $get[2];
    $qr_departamento = " AND titulo_identificador='$titulo_identiicador_departamento' ";
}

$rqdp1 = query("SELECT * FROM cursos_paginas WHERE nombre='QUIENES-SOMOS' limit 1 ");
$rqdp2 = fetch($rqdp1);
$titulo = $rqdp2['titulo'];
$contenido = $rqdp2['contenido'];
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>ENTIDADES FINANCIERAS</h3>
                </div>
                <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                    <blockquote>
                        <p>A continuaci&oacute;n te mostramos informaci&oacute;n de las entidades financieras abiertas en el periodo de cuarentena en los distintos departamentos de Bolivia.</p>
                    </blockquote>
                    <?php
                    $rqdef1 = query("SELECT * FROM departamentos WHERE estado='1' $qr_departamento ORDER BY orden ASC ");
                    while ($rqdef2 = fetch($rqdef1)) {
                        ?>
                        <img src="contenido/imagenes/entidades-financieras/<?php echo str_replace('png', 'jpeg', $rqdef2['imagen']); ?>" style="width: 100%;margin-bottom: 30px;"/>
                        <?php
                    }
                    ?>
                    <div>
                        <b>Fuente de informaci&oacute;n:</b>
                        &nbsp; 
                        <a href="https://www.asoban.bo/" target="_blank">ASOBAN | Asociaci&oacute;n de Bancos Privados de Bolivia</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

