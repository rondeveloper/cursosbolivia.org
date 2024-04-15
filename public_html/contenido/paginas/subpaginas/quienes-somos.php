<?php
$rqdp1 = query("SELECT * FROM cursos_paginas WHERE nombre='QUIENES-SOMOS' limit 1 ");
$rqdp2 = fetch($rqdp1);
$titulo = $rqdp2['titulo'];
$contenido = str_replace(array('[PAGE-NAME]'),array($___nombre_del_sitio),$rqdp2['contenido']);


if(isset($get[2]) && $get[2]=='15121651354153135135'){
    echo "<br><br><br><br><br><h1>TESTTTTT</h1>";


    $rqdc1 = query("SELECT id,titulo FROM cursos ORDER BY id DESC");
    while($rqdc2 = fetch($rqdc1)){
        $id_curso = $rqdc2['id'];
        $titulo_curso = $rqdc2['titulo'];
        $new_title = str_replace(
            array('INFOSICOES', 'Infosicoes', 'InfoSICOES', 'infosiscon',"'"),
            array('INFOSISCON', 'Infosiscon', 'Infosiscon', 'Infosiscon',""),
            $titulo_curso
        );
        query("UPDATE cursos SET titulo='$new_title' WHERE id='$id_curso' LIMIT 1 ");

        echo "<br>($id_curso) $titulo_curso<br>";
        echo "<br>($id_curso) $new_title<br><br>";
    }
}


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

