<?php
/* $urltag_onlinecourse, $onlinecourse, $sw_acceso_a_curso required */
?>
<?php
$sw_current_ipelc = false;
$id_usuario = usuario('id');
$rqdcip1 = query("SELECT * FROM cursos c INNER JOIN cursos_participantes p ON p.id_curso=c.id INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=c.id INNER JOIN cursos_onlinecourse oc ON oc.id=r.id_onlinecourse WHERE c.sw_ipelc='1' AND p.id_usuario='$id_usuario' AND oc.urltag='$urltag_onlinecourse' ");
if(num_rows($rqdcip1)>0){
    $sw_current_ipelc = true;
}
?>
<style>
    /* ESTILOS PARA CURSO ONLINE */
    body{
        background: #1e5871 !important;
    }
    .wrapsemibox{
        width:77% !important;
        margin:0px !important;
        float:left !important;
        max-width: 2200px !important;
    }
    .bar-left-curso-online{
        width:20%;
        margin-right: 1.5%;
        float:left;
    }
    .boxcontent-curso-online{
        background: #1e5871;
    }
    #TextoConsultasPie{
        clear: both;
    }
    .tophead-bar-left{
        border: 1px solid #8fa1b9;
        padding: 10px 15px;
        background: #2e6c88;
        text-align: center;
        color: #FFF;
        font-size: 17pt;
        margin-bottom: 0px;
        margin-top:0px;
    }
    .body-bar-left{
        background: #FFF;
        border: 1px solid #8fa1b9;
        padding: 10px 15px;
        min-height: 650px;
    }
    .active-item-c{
        background: #eff9fd;
    }
    .div-modos-v{
        background: #fbfbfb;
        box-shadow: inset 0px 0px 6px 1px #c5c5c5;
        padding: 0px 20px;
    }
    .item-mod-no-active,.item-mod-no-active:hover{
        background: #f1f1f1;
        cursor: no-drop;
    }
    .item-mod-active{
        background: white !important;
    }
</style>
<style>
    @media (max-width: 700px){
        .bar-left-curso-online{
            width: 100%;
        }
        .wrapsemibox {
            width: 100% !important;
        }
        .tophead-bar-left{
            cursor: pointer;
        }
        .body-bar-left{
            display: none;
        }
        .class_show_menu_cursovirtual_movil{
            display: block !important;
        }
        .body-bar-left .list-group-item {
            min-height: 40px;
        }
        .chat-curso-open {
            height: auto;
        }
        .containerXX .nav-tabs > li.active > a, .containerXX .nav-tabs > li > a {
            font-size: 6pt;
            padding: 4px;
        }
        .btn-nav-course {
            padding: 2px 4px !important;
            font-size: 7pt !important;
        }
        .chat-curso-open,.chat-curso-close {
            right: 10%;
            width: 80%;
            transition: .3s;
        }
    }
</style>
<script>
    var sw_show_menu_cursovirtual_movil = false;
    function show_menu_cursovirtual_movil() {
        if (sw_show_menu_cursovirtual_movil) {
            $("#id-body-bar-left").removeClass("class_show_menu_cursovirtual_movil");
            sw_show_menu_cursovirtual_movil = false;
        } else {
            $("#id-body-bar-left").addClass("class_show_menu_cursovirtual_movil");
            sw_show_menu_cursovirtual_movil = true;
        }
    }
</script>
<div>
    <?php
    if ($sw_acceso_a_curso) {
        ?>
        <h5 class="tophead-bar-left" onclick="show_menu_cursovirtual_movil();"><i class="fa fa-navicon"></i> CURSO ONLINE</h5>
        <?php if (($onlinecourse['id'] == '39' || $onlinecourse['id'] == '40') && false) { ?>
            <div class="text-center" style="padding: 30px 0px;background: #bbffdc;line-height: 2.2;">
                <b>COMUNICADO IMPORTANTE</b>
                <br>
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#MODAL-comunicado">Leer comunicado</button>
                <br>
                <br>
                <b>Documentaci&oacute;n IPELC:</b>
                <br>
                <a href="mi-cuenta-documentacion.html" target="_blank" style="text-decoration: underline;">Subir documentos IPELC >></a>
            </div>
        <?php } ?>
        
        <?php if (($onlinecourse['id'] == '39' || $onlinecourse['id'] == '40' || $onlinecourse['id'] == '45' || $onlinecourse['id'] == '46' || $onlinecourse['id'] == '49' || $onlinecourse['id'] == '50') && false) { ?>
            <div class="text-center" style="padding: 30px 0px;background: #bbffdc;line-height: 2.2;">
                <b>Documentaci&oacute;n IPELC:</b>
                <br>
                <a href="mi-cuenta-documentacion.html" target="_blank" style="text-decoration: underline;">Subir documentos IPELC >></a>
            </div>
        <?php } ?>

        <?php
        /* NOTAS / CALIFICACIONES : notificacion */
        $rqddnt1 = query("SELECT id FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND sw_notaspublicadas='1' ORDER BY id DESC limit 1 ");
        if (num_rows($rqddnt1) > 0) {
            ?>
            <div class="text-center" style="padding: 30px 0px;background: #d8f79c;border-top: 1px solid #d2d2d2;line-height: 2.2;">
                <b>Las notas ya fueron subidas al sistema:</b>
                <br>
                <a href="curso-online-notas/<?php echo $urltag_onlinecourse; ?>.html" style="text-decoration: underline;">Ver calificaciones/notas >></a>
            </div>
            <?php
        }
        ?>

        
        <div class="body-bar-left"  id="id-body-bar-left">
            <div class="list-group">
                <?php
                $active_item_c = '';
                if ($get[1] == 'curso-online' && $get[2] == $urltag_onlinecourse) {
                    $active_item_c = 'active-item-c';
                }
                ?>
                <a href="curso-online/<?php echo $urltag_onlinecourse; ?>.html" class="list-group-item <?php echo $active_item_c; ?>">PRESENTACI&Oacute;N <span class="badge">INICIO</span></a>
                <?php
                $rql1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' ORDER BY nro_leccion ASC ");
                while ($lec_barleft = fetch($rql1)) {
                    $active_item_c = '';
                    if ($get[2] == $lec_barleft['urltag']) {
                        $active_item_c = 'active-item-c';
                    }
                    ?>
                    <a data-toggle="collapse" href="#collapse-LEC<?php echo $lec_barleft['id']; ?>" class="list-group-item <?php echo $active_item_c; ?>"><?php echo $lec_barleft['titulo']; ?> <span class="badge">L-<?php echo $lec_barleft['nro_leccion']; ?></span></a>
                    <div id="collapse-LEC<?php echo $lec_barleft['id']; ?>" class="panel-collapse collapse div-modos-v">
                        <?php
                        /* modo video */
                        if (($lec_barleft['video'] !== '' && $lec_barleft['sw_vimeo'] == '1') || ($lec_barleft['video'] !== '' && $lec_barleft['sw_vimeo'] == '2') || ($lec_barleft['localvideofile'] !== '' && $lec_barleft['sw_vimeo'] == '0')) {
                            ?>
                            <a href="curso-online-leccion/<?php echo $lec_barleft['urltag']; ?>/video.html" class="list-group-item item-mod-active">
                                <b>VISUALIZAR VIDEO</b> &nbsp; <i>(audio y video)</i>
                                <br/>
                                <span class="label label-success">INGRESAR A LA LECCI&Oacute;N</span>
                            </a>
                            <?php
                        } else {
                            ?>
                            <a class="list-group-item item-mod-no-active">
                                <b>MODO VIDEO</b> &nbsp; <i>(audio y video)</i>
                                <br/>
                                <span class="label label-default">No disponible</span>
                            </a>
                            <?php
                        }
                        ?>
                        <?php
                        /* modo presentacion */
                        if (false) {
                            ?>
                            <a href="curso-online-leccion/<?php echo $lec_barleft['urltag']; ?>/presentacion.html" class="list-group-item item-mod-active">
                                <b>MODO PRESENTACI&Oacute;N</b> &nbsp; <i>(audio e imagenes)</i>
                                <br/>
                                <span class="label label-success">DISPONIBLE</span>
                            </a>
                            <?php
                        }
                        ?>
                        <?php
                        /* modo texto */
                        if (false) {
                            ?>
                            <a href="curso-online-leccion/<?php echo $lec_barleft['urltag']; ?>/texto.html" class="list-group-item item-mod-active">
                                <b>MODO TEXTO</b> &nbsp; <i>(texto e imagenes)</i>
                                <br/>
                                <span class="label label-success">DISPONIBLE</span>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
                    
                    
                <?php
                /* NOTAS / CALIFICACIONES */
                $active_item_c = '';
                if ($get[1] == 'curso-online-notas') {
                    $active_item_c = 'active-item-c';
                }
                $rqddnt1 = query("SELECT id FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND sw_notaspublicadas='1' ORDER BY id DESC limit 1 ");
                if (num_rows($rqddnt1)>0) {
                    ?>
                    <a href="curso-online-notas/<?php echo $urltag_onlinecourse; ?>.html" class="list-group-item <?php echo $active_item_c; ?>">
                        NOTAS paciales/finales <span class="badge">Calificaciones</span>
                    </a>
                    <?php
                }
                ?>
                    
                    
                    
                <?php if($sw_current_ipelc){ ?>
                <a href="mi-cuenta-documentacion.html" target="_blank" class="list-group-item">
                        <span class="badge">Subir</span>
                        <i class="fa fa-file-o"></i> DOCUMENTOS IPELC
                        <br>
                        En este enlace podras subir los documentos solicitados por IPELC.
                </a>
                <?php } ?>

                <?php
                $active_item_c = '';
                if ($get[1] == 'curso-online-finalizacion') {
                    $active_item_c = 'active-item-c';
                }
                if ($onlinecourse['sw_enproceso'] == '0') {
                    ?>
                    <a href="curso-online-finalizacion/<?php echo $urltag_onlinecourse; ?>.html" class="list-group-item <?php echo $active_item_c; ?>">
                        Finalizaci&oacute;n del curso virtual <span class="badge">Final</span>
                    </a>
                    <?php
                } else {
                    ?>
                    <a  class="list-group-item <?php echo $active_item_c; ?>">
                        <span class="badge">En proceso</span>
                        CURSO EN DESARROLLO 
                        <br>
                        Este curso a&uacute;n se lleva a cabo y no se ha completado.
                    </a>
                    <?php
                }
                ?>

                <?php
                if ($onlinecourse['sw_examen']=='1') {
                    $active_item_c = '';
                    if ($get[1] == 'curso-online-evaluacion') {
                        $active_item_c = 'active-item-c';
                    }
                    ?>
                    <a href="curso-online-evaluacion/<?php echo $urltag_onlinecourse; ?>.html" class="list-group-item <?php echo $active_item_c; ?>">EVALUACI&Oacute;N DE APRENDIZAJE <span class="badge">EXAMEN</span></a>
                    <?php
                }
                ?>

                <?php
                $active_item_c = '';
                if ($get[1] == 'curso-online-certificado') {
                    $active_item_c = 'active-item-c';
                }
                if ($sw_cert_onlinecourse == '1') {
                    ?>
                    <a href="curso-online-certificado/<?php echo $urltag_onlinecourse; ?>.html" class="list-group-item <?php echo $active_item_c; ?>">
                        CERTIFICACI&Oacute;N <span class="badge">CERT</span>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
    } else {
        echo "&nbsp;.&nbsp;";
        echo "<style>.bar-left-curso-online {width: 11% !important;}</style>";
    }
    ?>
</div>
    



