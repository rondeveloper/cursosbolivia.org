<?php
error_reporting(1);

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$id_leccion = get('id_leccion');

/* leccion */
$rqdt1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
/* presentacion */
$rqdtp1 = query("SELECT * FROM cursos_onlinecourse_presentaciones WHERE id_leccion='$id_leccion' ORDER BY duracion_audio ASC ");
if (mysql_num_rows($rqdtp1) == 0) {
    echo "NO SE ENCONTRO PRESENTACION PARA ESTA LECCI&Oacute;N";
    exit;
}
$leccion = mysql_fetch_array($rqdt1);
?>
<!DOCTYPE html>
<html class="js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths ready">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=1024, user-scalable=no">

        <title><?php echo 'LECCION ' . $id_leccion; ?></title>

        <base href="https://cursos.bo/" target="_self"/>

        <!-- Required stylesheet -->
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.core.css">

        <!-- Extension CSS files go here. Remove or add as needed. -->
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.menu.css">
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.navigation.css">
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.status.css">
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.hash.css">
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.scale.css">
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.automatic.css">

        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/deck.narrator.css">

        <!-- Style theme. More available in /themes/style/ or create your own. -->
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/web-2.0.css">

        <!-- Transition theme. More available in /themes/transition/ or create your own. -->
        <link rel="stylesheet" href="contenido/librerias/Slide-Narration/horizontal-slide.css">

        <!-- Required Modernizr file -->
        <script src="contenido/librerias/Slide-Narration/modernizr.custom.js.descarga"></script>
    </head>
    <body class="deck-container on-slide-0 on-slide-slide-0" style="background: #1b5b77;">

<!--        <section class="slide deck-after" data-narrator-duration="1" data-duration="1000" id="slide-0">
            <div class="deck-slide-scaler">
                <h2>Inicio de presentaci&oacute;n</h2>
            </div>
        </section>-->

        <?php
        $cnt_slide = 1;
        $duracion_audio_acumulado = 0;
        $imagen_acumulado = '';
        while ($presentacion = mysql_fetch_array($rqdtp1)) {
            if ($duracion_audio_acumulado == 0) {
                $duration = (int) $presentacion['duracion_audio'];
                if ($duration == 0) {
                    $duration = 1;
                }
                $duracion_audio_acumulado += $duration;
                $imagen_acumulado = $presentacion['imagen'];
                ?>
                <section class="slide deck-after" data-narrator-duration="<?php echo $duration; ?>" data-duration="<?php echo $duration; ?>000" id="slide-0">
                    <div class="deck-slide-scaler">
                        <h2 style="color:#FFF;">Inicio de presentaci&oacute;n</h2>
                    </div>
                </section>
                <?php
            } else {
                $duration = (int) $presentacion['duracion_audio'] - $duracion_audio_acumulado;
                $duracion_audio_acumulado += $duration;
                ?>
                <section class="slide deck-after" data-narrator-duration="<?php echo $duration; ?>" data-duration="<?php echo $duration; ?>000" id="slide-<?php echo $cnt_slide++; ?>">
                    <div class="deck-slide-scaler">
                        <img src="contenido/imagenes/presentaciones/<?php echo $imagen_acumulado; ?>" style="height:580px;"/>
                    </div>
                </section>
                <?php
                $imagen_acumulado = $presentacion['imagen'];
            }
        }
        ?>
        <section class="slide deck-after" data-narrator-duration="1" data-duration="1000" id="slide-<?php echo $cnt_slide++; ?>">
            <div class="deck-slide-scaler">
                <img src="contenido/imagenes/presentaciones/<?php echo $imagen_acumulado; ?>" style="height:580px;"/>
            </div>
        </section>
        <!-- End slides. -->

        <!-- Begin extension snippets. Add or remove as needed. -->

        <!-- deck.navigation snippet -->
        <a href="http://www.kevinlamping.com/deck.narrator.js/sample/sample.html#" class="deck-prev-link deck-nav-disabled" title="Previous"> < </a>
        <a href="http://www.kevinlamping.com/deck.narrator.js/sample/sample.html#slide-1" class="deck-next-link" title="Next"> > </a>

        <!-- deck.status snippet -->
        <p class="deck-status">
            <span class="deck-status-current" style="color:#FFF;">1</span>
            /
            <span class="deck-status-total" style="color:#FFF;">4</span>
        </p>

        <!-- deck.hash snippet -->
        <a href="http://www.kevinlamping.com/deck.narrator.js/sample/" title="Permalink to this slide" class="deck-permalink">#</a>

        <!-- deck.narrator snippet -->
        <audio controls="" class="deck-narrator-audio" id="narrator-audio">
            <?php
            if ($leccion['audio_presentacion'] <> '') {
                $url_audio = '../../audios/presentaciones/' . $leccion['audio_presentacion'];
                if (file_exists($url_audio)) {

                    $formato = 'mp3';
                    if ('---' . strpos(strtolower($leccion['audio_presentacion']), '.ogg') > 0) {
                        $formato = 'ogg';
                    }
                    $direccion_audio = 'contenido/audios/presentaciones/' . $leccion['audio_presentacion'];

                    echo '<source src="' . $direccion_audio . '" type="audio/' . $formato . '">';
                }
            }
            ?>
<!--            <source src="contenido/audios/Onerepublic-Greatest-Hits.mp3" type="audio/mp3">-->
          <!--  <source src="contenido/librerias/Slide-Narration/audio.m4a" type="audio/mp4">
            <source src="contenido/librerias/Slide-Narration/audio.ogg" type="audio/ogg">-->
          <!--  <track kind="caption" src="contenido/librerias/Slide-Narration/transcript.vtt" srclang="en" label="English">-->
        </audio>
        <!-- End extension snippets. -->


        <!-- Required JS files. -->
        <script src="contenido/librerias/Slide-Narration/jquery-1.10.1.min.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.core.js.descarga"></script>

        <!-- Extension JS files. Add or remove as needed. -->
        <script src="contenido/librerias/Slide-Narration/deck.core.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.hash.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.menu.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.status.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.navigation.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.scale.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.automatic.js.descarga"></script>
        <script src="contenido/librerias/Slide-Narration/deck.narrator.js.descarga"></script>

        <!-- Initialize the deck. You can put this in an external file if desired. -->
        <script>
            $(function() {
                $.extend(true, $.deck.defaults, {
                    automatic: {
                        startRunning: false,
                        cycle: false
                    }
                });
                $.deck('.slide');
            });
        </script>

    </body>
</html>