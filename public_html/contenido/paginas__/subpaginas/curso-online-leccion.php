<?php
/* mensaje */
$mensaje = '';

/* URLTAG de curso */
$urltag = $get[2];

$rqdl1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE urltag='$urltag' AND estado='1' ORDER BY id DESC limit 1 ");
if (num_rows($rqdl1) == 0) {
    echo "<script>alert('No se encontro resultados.');location.href='$dominio';</script>";
    exit;
}
$leccion = fetch($rqdl1);
$id_onlinecourse = $leccion['id_onlinecourse'];
$id_onlinecourse_leccion = $leccion['id'];

$rqd1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_onlinecourse' AND estado IN (1,5) ORDER BY id DESC limit 1 ");
if (num_rows($rqd1) == 0) {
    echo "<script>alert('No se encontro resultados.');location.href='$dominio';</script>";
    exit;
}
$onlinecourse = fetch($rqd1);
$id_curso = $onlinecourse['id_curso'];
$urltag_onlinecourse = $onlinecourse['urltag'];
$titulo_onlinecourse = $onlinecourse['titulo'];
$sw_cert_onlinecourse = $onlinecourse['sw_cert'];
$contenido_onlinecourse = $onlinecourse['contenido'];
$video_introductorio_onlinecourse = $onlinecourse['video_introductorio'];
$imagen_onlinecourse = $dominio . "cursos/" . $onlinecourse['imagen'] . ".size=6.img";
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);
$nombre_curso = $curso['titulo'];

/* chat */
$roomcod = '0';

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE estado='1' AND id_curso IN (select id_curso from cursos_rel_cursoonlinecourse where id_onlinecourse='$id_onlinecourse' and estado='1') AND id_usuario='$id_usuario' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C'.$rqvpcv2['id_curso'];
    }
}elseif (isset_docente()) {
    $id_docente = docente('id');
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_docente='$id_docente' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C'.$rqvpcv2['id_curso'];
    }
}
?>

<script src="contenido/librerias/SlickQuiz-master/js/jquery.js"></script>

<link type="text/css" rel="stylesheet" href="contenido/css/style-chat-course-vr.css"/>

<div style="height:50px;"></div>
<div class="boxcontent-curso-online">
    <div class="bar-left-curso-online">
        <?php
        include_once 'contenido/paginas/items/item.d.curso_online.bar_left.php';
        ?>
    </div>
    <div class="wrapsemibox">
        <section class="containerXX" style="padding: 2px 5px;">
            <div style="height:10px"></div>
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <?php
                        include_once 'contenido/paginas/items/item.m.datos_onlinecourse.php';
                        ?>
                    </div>

                    <?php echo $mensaje; ?>
                    
                    
                    
                    <?php
                    if($onlinecourse['id']=='5' && false){
                        echo '<br><hr><br><div class="alert alert-danger">
  <strong>AVISO IMPORTANTE!</strong><br>Lamentamos mucho la molestia, este curso se encuentra en mantenimiento durante 24 horas, por favor vuelve el d&iacute;a de ma&ntilde;ana.
</div><br><hr><br>';
                    }else{
                    ?>

                    <?php
                    if (!$sw_acceso_a_curso) {
                        ?>
                        <div style="margin: 70px 0px 300px 0px;">
                            <div class="alert alert-danger">
                                <strong>AVISO</strong> su cuenta de usuario no tiene acceso a este curso.
                            </div>
                        </div>
                        <?php
                    } else {

                        $modo_leccion = 'video';
                        if (isset($get[3])) {
                            $modo_leccion = $get[3];
                        }
                        ?>
                        <style>
                            .nav-tabs li a {
                                background: #f7f7f7;
                                border-bottom: 1px solid #0169b2;
                                color: gray !important;
                                margin-right: 7px;
                            }
                            .vid{
                                width: 100%;
                                border: 7px solid #1b5b77;
                                background: #1b5b77;
                            }
                            .btn-nav-course{
                                background: #258fad;
                                color: #FFF;
                                padding: 3px 7px;
                                border-radius: 10px 0px 0px 10px;
                                cursor: pointer;
                                border: 1px solid #1b5b77;
                                margin-top: 10px;
                                font-size: 8pt;
                            }
                        </style>

                        <ul class="nav nav-tabs" style="margin-bottom: 10px;border-bottom: 1px solid #0169b2;margin-top: 20px;">
                            <?php
                            $txt_active = '';
                            if ($modo_leccion == 'video') {
                                $txt_active = 'active';
                            }
                            ?>
                            <li class="<?php echo $txt_active; ?>"><a href="curso-online-leccion/<?php echo $urltag; ?>/video.html"><span class="hidden-xs">LECCION EN </span>MODO VIDEO</a></li>
                            <?php
                            $txt_active2 = '';
                            if ($modo_leccion == 'presentacion') {
                                $txt_active2 = 'active';
                            }
                            ?>
                            <li class="<?php echo $txt_active2; ?> disabled"><a <?php //href="curso-online-leccion/<?php echo $urltag; ? >/presentacion.html" ?>><span class="hidden-xs">LECCION EN </span>MODO PRESENTACION</a></li>
                            <?php
                            $txt_active3 = '';
                            if ($modo_leccion == 'texto') {
                                $txt_active3 = 'active';
                            }
                            ?>
                            <li class="<?php echo $txt_active3; ?> disabled"><a <?php //href="curso-online-leccion/<?php echo $urltag; ? >/texto.html" ?>><span class="hidden-xs">LECCION EN </span>MODO TEXTO</a></li>

                            <?php
                            /* enlace next */
                            $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND id>'$id_onlinecourse_leccion' AND estado='1' ORDER BY id ASC limit 1 ");
                            $rqdanc2 = fetch($rqdanc1);
                            ?>
                            <li class="pull-right">
                                <?php
                                /* enlace prev */
                                $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND id<'$id_onlinecourse_leccion' AND estado='1' ORDER BY id DESC limit 1 ");
                                if (num_rows($rqdanc1) > 0) {
                                    $rqdanc2 = fetch($rqdanc1);
                                    ?>
                                    <b onclick="location.href = 'curso-online-leccion/<?php echo $rqdanc2['urltag']; ?>/<?php echo $modo_leccion; ?>.html';" class="btn-nav-course"><i class="icon-backward"></i> ANTERIOR</b>
                                    <?php
                                }
                                ?>
                                &nbsp;
                                <b onclick="location.href = 'curso-online/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 2px;"><i class="icon-home" style="padding: 0px;"></i></b>
                                &nbsp;
                                <?php
                                /* enlace next */
                                $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND id>'$id_onlinecourse_leccion' AND estado='1' ORDER BY id ASC limit 1 ");
                                if (num_rows($rqdanc1) > 0) {
                                    $rqdanc2 = fetch($rqdanc1);
                                    ?>
                                    <b onclick="location.href = 'curso-online-leccion/<?php echo $rqdanc2['urltag']; ?>/<?php echo $modo_leccion; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;">SIGUIENTE <i class="icon-forward" style="padding: 0px;"></i>&nbsp;</b>
                                    <?php
                                }elseif($onlinecourse['sw_enproceso']=='0'){
                                    ?>
                                    <b onclick="location.href = 'curso-online-finalizacion/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;">SIGUIENTE <i class="icon-forward" style="padding: 0px;"></i>&nbsp;</b>
                                    <?php
                                }
                                ?>
                            </li>
                        </ul>





                        <?php
                        $rql1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ");
                        $rql2 = fetch($rql1);
                        $total_lecciones = $rql2['total'];
                        $leccion_actual = $leccion['nro_leccion'];
                        ?>
                        <div style='background:#258fad;color:#FFF;padding:10px 15px;border-bottom:5px solid #1b5b77;'>
                            <div style=''>
                                <h4 style="color:#FFF"><?php echo $leccion['titulo']; ?> <span class="pull-right">LECCI&Oacute;N <?php echo $leccion_actual; ?> DE <?php echo $total_lecciones; ?></span></h4>
                            </div>
                        </div>

                        <?php
                        /* LECCION EN MODO PRESENTACION */
                        if ($modo_leccion == 'presentacion') {
                            $rqvs1 = query("SELECT id FROM cursos_onlinecourse_presentaciones WHERE id_leccion='" . $leccion['id'] . "' LIMIT 1 ");
                            if (num_rows($rqvs1) > 0) {
                                ?>
                                <iframe style="border:0px;width:100%;height:670px;" src="contenido/paginas/procesos/presentacion.php?id_leccion=<?php echo $leccion['id']; ?>"></iframe>
                                <?php
                            } else {
                                echo "<br/><h5>La lecci&oacute;n no cuenta con la modalidad presentaci&oacute;n.</h5><br/><br/><br/>";
                            }
                            //http://codesamplez.com/programming/php-html5-video-streaming-tutorial
                        }
                        ?>

                                <style>
                                    .iframe-video{
                                        width: 100%;
                                        height: 700px; 
                                        background: #1d6381;
                                    }
                                    @media (max-width: 1400px){
                                        .iframe-video {
                                            height: 600px;
                                        }
                                    }
                                    @media (max-width: 1200px){
                                        .iframe-video {
                                            height: 500px;
                                        }
                                    }
                                    @media (max-width: 1000px){
                                        .iframe-video {
                                            height: 400px;
                                        }
                                    }
                                </style>
                        <?php
                        /* LECCION EN MODO VIDEO */
                        if ($modo_leccion == 'video') {
                            if ($leccion['video'] !== '' && $leccion['sw_vimeo']=='1') {
                                ?>
                                <iframe src="https://player.vimeo.com/video/<?php echo $leccion['video']; ?>?autoplay=1" class="iframe-video" frameborder="0" allow="autoplay; fullscreen" allowfullscreen autoplay></iframe>
                                <?php
                            } elseif ($leccion['video'] !== '' && $leccion['sw_vimeo']=='2') {
                                ?>
                                <iframe src="https://www.youtube-nocookie.com/embed/<?php echo $leccion['video']; ?>?autoplay=1&iv_load_policy=3&modestbranding=1&rel=0&showinfo=0"  class="iframe-video" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <?php
                            } elseif ($leccion['localvideofile'] !== '' && $leccion['sw_vimeo']=='0') {
                                $url_imagen_v = $dominio."contenido/imagenes/images/banner-general-cursosbo.jpg";
                                if ($curso['imagen'] !== '' && file_exists('contenido/imagenes/paginas/' . $curso['imagen'])) {
                                    $url_imagen_v = $dominio.'paginas/' . $curso['imagen'] . '.size=4.img';
                                }
                                $url_video = $dominio_www.'contenido/videos/cursos/' . $leccion['localvideofile'];
                                ?>
                                <video controls="controls" autoplay="autoplay" class="iframe-video" controlslist="nodownload">
                                    <source src="<?php echo $url_video; ?>" type="video/webm" />
                                </video>
                                <?php
                            } else {
                                echo "<br/><h5>La lecci&oacute;n no cuenta con la modalidad video.</h5><br/><br/><br/>";
                            }
                        }
                        ?>


                        <?php
                        /* LECCION EN MODO TEXTO */
                        if ($modo_leccion == 'texto') {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if ($leccion['contenido'] !== '') {
                                        echo $leccion['contenido'];
                                    } else {
                                        echo "<br/><h5>La lecci&oacute;n no cuenta con la modalidad texto.</h5><br/><br/><br/>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <hr/>
                        
                        <div style="background: #efefef;padding: 20px 0px;">
                            <div class="row">
                                <div class="col-xs-4 col-md-5 text-right">
                                    <?php
                                    /* enlace prev */
                                    $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND id<'$id_onlinecourse_leccion' AND estado='1' ORDER BY id DESC limit 1 ");
                                    if (num_rows($rqdanc1) > 0) {
                                        $rqdanc2 = fetch($rqdanc1);
                                        ?>
                                        <b onclick="location.href = 'curso-online-leccion/<?php echo $rqdanc2['urltag']; ?>/<?php echo $modo_leccion; ?>.html';" class="btn-nav-course" style="padding: 10px 20px;">
                                            <i class="icon-backward"></i> ANTERIOR
                                        </b>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-xs-4 col-md-2 text-center">
                                    <b onclick="location.href = 'curso-online/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 2px;padding: 10px 20px;">
                                        <i class="icon-home" style="padding: 0px;"></i>
                                    </b>
                                </div>
                                <div class="col-xs-4 col-md-5 text-left">
                                    <?php
                                    /* enlace next */
                                    $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND id>'$id_onlinecourse_leccion' AND estado='1' ORDER BY id ASC limit 1 ");
                                    if (num_rows($rqdanc1) > 0) {
                                        $rqdanc2 = fetch($rqdanc1);
                                        ?>
                                        <b onclick="location.href = 'curso-online-leccion/<?php echo $rqdanc2['urltag']; ?>/<?php echo $modo_leccion; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;padding: 10px 20px;">
                                            SIGUIENTE <i class="icon-forward" style="padding: 0px;"></i>&nbsp;
                                        </b>
                                        <?php
                                    }elseif($onlinecourse['sw_enproceso']=='0'){
                                        ?>
                                        <b onclick="location.href = 'curso-online-finalizacion/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;padding: 10px 20px;">
                                            SIGUIENTE <i class="icon-forward" style="padding: 0px;"></i>&nbsp;
                                        </b>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        $rqmc1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id_onlinecourse='$id_onlinecourse' AND id_leccion='" . $leccion['id'] . "' AND estado='1' ");
                        if (num_rows($rqmc1)>0) {
                        ?>
                        <div>
                            <h4 style="background: #318cb8;color: #FFF;padding: 7px 15px;border-radius: 5px;border-bottom: 4px solid #1b5b77;">MATERIAL DESCARGABLE</h4>
                            <p>A continuaci&oacute;n se listan los materiales de apoyo para esta lecci&oacute;n, los cuales deben ser estuadiados para una mejor asimilaci&oacute;n del curso.</p>
                            <table class="table table-bordered table-striped table-hover">
                                <?php
                                $cnt = 1;
                                while ($producto = fetch($rqmc1)) {
                                    ?>
                                    <tr>
                                        <td class=""><?php echo $cnt++; ?></td>
                                        <td class="">
                                            <?php
                                            echo $producto['nombre'];
                                            ?>         
                                        </td>
                                        <td style="width:30px;">
                                            <img src="contenido/imagenes/images/icon-pdf.ico" style="height:15px;"/>
                                        </td>
                                        <td class="">
                                            <?php
                                            echo $producto['formato_archivo'];
                                            ?> 
                                        </td>
                                        <td class="">
                                            <?php
                                            echo $producto['nombre_fisico'];
                                            ?> 
                                        </td>
                                        <td class="" style="width:120px;">
                                            <a href="contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-warning active"><i class='fa fa-eye'></i> ver/descargar</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                        <hr/>
                        <?php
                        }
                        ?>

                        <?php
                        /* chat de curso */
                        include_once 'contenido/paginas/items/item.b.chat_content_data.php';
                        ?>
                        
                        <br/>

                        <?php
                        if (false) {
                            ?>

                            <hr/>

                            <div>
                                <h3>EVALUACI&Oacute;N DE CONOCIMIENTOS</h3>
                                <p>Debes completar exitosamente el siguiente examinador para poder tener esta lecci&oacute;n como aprobada.</p>
                            </div>


                            <div class="row">

                                <!--                    <link href="contenido/librerias/SlickQuiz-master/css/reset.css" media="screen" rel="stylesheet" type="text/css">-->
                                <link href="contenido/librerias/SlickQuiz-master/css/slickQuiz.css" media="screen" rel="stylesheet" type="text/css">
                                <!--                    <link href="contenido/librerias/SlickQuiz-master/css/master.css" media="screen" rel="stylesheet" type="text/css">-->


                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                    <div class="panel panel-info panel-quiz">
                                        <div class="panel-body">
                                            <div id="slickQuiz">
                                                <h1 class="quizName"><!-- where the quiz name goes --></h1>

                                                <div id="pro-bar-box" class="progress progress-striped active" style="display:none;">
                                                    <div id="pro-bar" class="progress-bar progress-bar-info" style="width: 1%;"></div>
                                                </div>

                                                <div class="quizArea">
                                                    <div class="quizHeader">
                                                        <!-- where the quiz main copy goes -->
                                                        <a class="button startQuiz" href="#">Empezar!</a>
                                                    </div>
                                                    <!-- where the quiz gets built -->
                                                </div>

                                                <hr/>


                                                <div class="quizResults">
                                                    <h3 class="quizScore">Tu puntaje: <span><!-- where the quiz score goes --></span></h3>
                                                    <h3 class="quizLevel"><strong>Ranking:</strong> <span><!-- where the quiz ranking level goes --></span></h3>
                                                    <div class="quizResultsCopy">
                                                        <!-- where the quiz result copy goes -->
                                                    </div>
                                                </div>


                                                <script src="contenido/librerias/SlickQuiz-master/js/jquery.js"></script>
            <!--                                        <script src="contenido/librerias/SlickQuiz-master/js/slickQuiz-config.js"></script>-->
                                                <script src="contenido/librerias/SlickQuiz-master/js/slickQuiz-config.php?dat=<?php echo $id_onlinecourse; ?>"></script>
                                                <script src="contenido/librerias/SlickQuiz-master/js/slickQuiz.js"></script>
                        <!--                        <script src="contenido/librerias/SlickQuiz-master/js/master.js"></script>-->
                                                <script>
                                        $('#slickQuiz').slickQuiz();
                                                </script>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <?php
                        }
                        ?>

                        <hr/>


                        <?php
                        if (false) {
                            ?>

                            <div class="row" style="background:#258fad;height:30px;border-radius:5px;overflow: hidden;">
                                <div class="col-md-1" style="background:#1b5b77;color:#FFF;line-height: 1.1;height:30px;">
                                    <b style="font-size:7pt;">AVANCE DEL CURSO</b>
                                </div>
                                <div class="col-md-10">
                                    <div class="progress progress-striped active" style="display: block;height:30px;">
                                        <div class="progress-bar progress-bar-info" style="width: <?php echo (int) ($leccion_actual * (100 / $total_lecciones)); ?>%;height:30px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-1" style="background:#1b5b77;color:#FFF;line-height: 1.1;height:30px;">
                                    <b style="font-size:8pt;">Leccion <?php echo $leccion_actual; ?> / <?php echo $total_lecciones; ?></b>
                                </div>
                            </div>

                            <hr/>

                            <?php
                        }
                        ?>

                        <br/>


                        <script>
                            var vid = document.getElementById("player");
                            vid.addEventListener("contextmenu", function(event) {
                                event.preventDefault();
                            }, false);
                        </script>

                        <?php
                    }
                    
                    
                    }
                    ?>

                </div>

            </div>


            <div style="height:10px"></div>
        </section>
    </div>                     

</div>







<style>
    /* QUIZ STYLES */
    /* Styles to prettify the quiz page */
    .button {
        float: left;
        width: auto;
        padding: 5px 15px;
        color:#ffffff;
        background-color:darkcyan;
        border: 1px solid #fff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        text-decoration: none;
    }
    .button:hover {
        background-color:darkslategray;
        color:#FFF;
    }

    .startQuiz {
        margin-top: 40px;
    }

    .tryAgain {
        float: none;
        margin: 20px 0;
    }

    /* clearfix */
    .quizArea, .quizResults {
        zoom: 1;
    }
    .quizArea:before, .quizArea:after, .quizResults:before, .quizResults:after {
        content: "\0020";
        display: block;
        height: 0;
        visibility: hidden;
        font-size: 0;
    }
    .quizArea:after, .quizResults:after {
        clear: both;
    }

    .questionCount {
        font-size: 14px;
        font-style: italic;
    }
    .questionCount span {
        font-weight: bold;
    }

    ol.questions {
        margin-top: 20px;
        margin-left: 0;
    }
    ol.questions li {
        margin-left: 0;
    }

    ul.answers {
        margin-left: 20px;
        margin-bottom: 20px;
    }

    ul.responses li {
        margin: 10px 20px 20px;
    }
    ul.responses li p span {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    .complete ul.answers li.correct, ul.responses li.correct p span {
        color: #6C9F2E;
    }
    ul.responses li.incorrect p span {
        color: #B5121B;
    }

    .quizResults h3 {
        margin: 0;
    }
    .quizResults h3 span {
        font-weight: normal;
        font-style: italic;
    }
    .quizResultsCopy {
        clear: both;
        margin-top: 20px;
    }

    /*my styles*/
    .answers input{
        width:auto;
    }
    .label-option-q{
        width:100%;
        cursor: pointer;
        transition: .5s;
        padding: 2px;
    }
    .label-option-q:hover{
        background:#FFF;
        transition: .5s;
    }
    .quizName{
        font-family: arial !important;
    }
    .panel-quiz{
        border: 1px solid #c5c5c5;
        border-radius: 5px;
    }
    #pro-bar-box,#pro-bar{
        height: 20px;
    }
    #pro-bar-box{
        background: #FFF;
        border: 1px solid #92bbd2;
    }
</style>


<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
