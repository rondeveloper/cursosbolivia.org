<?php
$rqdp1 = query("SELECT * FROM cursos_paginas WHERE nombre='CONTACTO' limit 1 ");
$rqdp2 = fetch($rqdp1);
$titulo = $rqdp2['titulo'];
$contenido = $rqdp2['contenido'];
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container" style="min-height: 570px;">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <img src="https://www.infosicoes.com/contenido/imagenes/images/banner_contactenos.jpg" style="width:100%;margin:auto;"/>
                
                <br>
                <br>
                
                <div class="alert alert-success">
                    <b>MUCHAS GRACIAS POR SU MENSAJE</b>
                    <p>Su mensaje ha sido enviado exitosamente.</p>
                </div>
            </div>
        </div>
    </section>
</div>                     

