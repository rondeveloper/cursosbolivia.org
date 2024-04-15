<?php
/* mensaje */
$mensaje = '';

/* URLTAG de curso */
$urltag_onlinecourse = $get[2];

$rqd1 = query("SELECT * FROM cursos_onlinecourse WHERE urltag='$urltag_onlinecourse' AND estado IN (1,5) ORDER BY id DESC limit 1 ");
if (num_rows($rqd1) == 0) {
    echo "<script>alert('No se encontro resultados.');location.href='$dominio';</script>";
    exit;
}
$onlinecourse = fetch($rqd1);
$id_curso = $onlinecourse['id_curso'];
//$urltag_onlinecourse = $onlinecourse['urltag'];
$titulo_onlinecourse = $onlinecourse['titulo'];
$sw_cert_onlinecourse = $onlinecourse['sw_cert'];
$id_onlinecourse = $onlinecourse['id'];
$contenido_onlinecourse = $onlinecourse['contenido'];
$video_introductorio_onlinecourse = $onlinecourse['video_introductorio'];
$imagen_onlinecourse = $dominio . "cursos/" . $onlinecourse['imagen'] . ".size=6.img";
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);
$nombre_curso = $curso['titulo'];

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    $rqvpcv1 = query("SELECT id FROM cursos_participantes WHERE estado='1' AND id_curso IN (select id_curso from cursos_rel_cursoonlinecourse where id_onlinecourse='$id_onlinecourse' and estado='1') AND id_usuario='$id_usuario' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
    }
} elseif (isset_docente()) {
    $id_docente = docente('id');
    $rqvpcv1 = query("SELECT id FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_docente='$id_docente' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
    }
}
?>
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
                    if (!$sw_acceso_a_curso) {
                        ?>
                        <hr/>
                        <p>
                            Para poder tomar el curso y tener acceso a todos los recursos ofrecidos debes ingresar a tu cuenta de usuario, para ello ingresa a continuaci&oacute;n 
                            tu codigo de registro m&aacute;s el nombre y apellidos que se encuentran en tu ficha de registro.
                        </p>
                        
                        <hr/>
                        
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="boxForm ajusta_form_contacto">
                                    <h5>INGRESA A TU CUENTA</h5>
                                    <hr/>
                                    <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="text" name="cod_registro" placeholder="Codigo de registro..." required="">
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="text" name="nombres" placeholder="Nombres..." required="">
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="text" name="apellidos" placeholder="Apellidos..." required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 text-center">
                                                <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR POR DATOS DE REGISTRO"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <span><b style="font-weight:bold;">&iquest; Ya tienes tu cuenta ?</b> ingresa con el siguiente enlace:</span>
                                            <br/>
                                            <br/>
                                            <div class="col-md-12 text-center">
                                                <a href="ingreso-de-usuarios.html" type="submit" class="btn btn-primary">INGRESAR POR DATOS DE USUARIO</a>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>

                        </div>

                        <hr/>

                        <br/>

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

                        <br/>

                        <?php
                        $rql1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ");
                        $rql2 = fetch($rql1);
                        $total_lecciones = $rql2['total'];
                        $leccion_actual = $leccion['nro_leccion'];
                        ?>
                        <div style='background:#258fad;color:#FFF;padding:10px 15px;border-bottom:5px solid #1b5b77;'>
                            <div style=''>
                                <h4 style="color:#FFF"><?php echo $titulo_onlinecourse; ?> <span class="pull-right">EVALUACI&Oacute;N</span></h4>
                            </div>
                        </div>


                            <?php
                            $rqdael1 = query("SELECT * FROM cursos_onlinecourse_evaluaciones WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ");
                            if (num_rows($rqdael1) > 0) {
                                ?>
                                <div style="border:12px solid #1b5b77;border-top:5px solid #1b5b77;background: #FFF;padding: 15px;">
                                    <b>EVALUACI&Oacute;N VIRTUAL</b>
                                    <p>Usted ya realiz&oacute; la evaluaci&oacute;n correspondiente para este curso.</p>
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th colspan="2">Nota</th>
                                            <th>Evaluado</th>
                                        </tr>
                                        <?php
                                        while ($rqdavl2 = fetch($rqdael1)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <b class="btn btn-sm btn-success btn-block active"><?php echo round(($rqdavl2['total_correctas'] * 100) / $rqdavl2['total_preguntas'], 1); ?> puntos</b>
                                                </td>
                                                <td>
                                                    <b class="label label-primary"><?php echo $rqdavl2['total_correctas'] . '/' . $rqdavl2['total_preguntas']; ?> respuestas correctas</b>
                                                </td>
                                                <td>
                                                    <?php echo date("d/m/Y H:i", strtotime($rqdavl2['fecha'])); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <hr>
                                </div>
                                <?php
                            }else{
                                /* datos iniciales */
                                $minutos_habilitados = 15;
                                $qrvdti1 = query("SELECT * FROM cursos_onlinecourse_examenes WHERE id_onlinecourse='$id_onlinecourse' ORDER BY id DESC limit 1 ");
                                if (num_rows($qrvdti1) > 0) {
                                    $qrvdti2 = fetch($qrvdti1);
                                    $minutos_habilitados = $qrvdti2['minutos'];
                                }
                            ?>
                            <div style="border:12px solid #1b5b77;border-top:5px solid #1b5b77;background: #FFF;padding: 15px;">

                                <p style="text-transform: uppercase;margin-bottom: 40px;">
                                    Para completar la evaluaci&Oacute;n debe responder cada una de las preguntas en el siguiente cuadro, recuerda que hay un tiempo limite de <b><?php echo $minutos_habilitados; ?> minutos</b> para responder todas las preguntas, pasado este tiempo el examinador terminara y se le asignara un puntaje.
                                </p>

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
                                                            <a class="button startQuiz" href="#" onclick="setTimeout('finalizacion()',<?php echo $minutos_habilitados; ?>*60*1000);">Empezar!</a>
                                                        </div>
                                                        <!-- where the quiz gets built -->
                                                    </div>

                                                    <hr/>


                                                    <div class="quizResults">
                                                        <h3 class="quizScore">Tu puntaje: <span><!-- where the quiz score goes --></span></h3>
                                                        <br/>
                                                        <h3 class="quizLevel"><strong>Resultado:</strong> <span><!-- where the quiz ranking level goes --></span></h3>
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

                                <hr/>

                            </div>
                        
                        <script>
                            function finalizacion(){
                                alert('TIEMPO LIMITE');
                                location.reload(true);
                            }
                        </script>

                            <?php
                        }
                        ?>

                        <hr/>
                        
                        <style>
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
                        <div style="background: #efefef;padding: 20px 0px;">
                            <div class="row">
                                <div class="col-xs-4 col-md-5 text-right">
                                    <?php
                                    if($onlinecourse['sw_enproceso']=='0'){
                                    ?>
                                    <b onclick="location.href = 'curso-online-finalizacion/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="padding: 10px 20px;">
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
                                    if($onlinecourse['sw_cert']=='1'){
                                    ?>
                                    <b onclick="location.href = 'curso-online-certificado/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;padding: 10px 20px;">
                                        CERTIFICADO <i class="icon-forward" style="padding: 0px;"></i>&nbsp;
                                    </b>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>


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
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                        <script>
                            var vid = document.getElementById("player");
                            vid.addEventListener("contextmenu", function(event) {
                                event.preventDefault();
                            }, false);
                        </script>

                        <?php
                    }
                    ?>

                </div>
            </div>

            <div style="height:10px"></div>
        </section>
    </div>

</div>

<script>
    function sendScoreToServer(displayScore, questionCount) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.evaluacion.sendScoreToServer.php',
            data: {displayScore: displayScore, questionCount: questionCount, id_onlinecourse: '<?php echo $id_onlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                
            }
        });
    }
</script>
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
        border-radius: 5px;
        box-shadow: 2px 2px 8px 1px #bdcfd4, 2px 2px 12px 0px #e4e4e4;
        border: 2px solid #82b7ce;
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
