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
$id_onlinecourse = $onlinecourse['id'];
$titulo_onlinecourse = $onlinecourse['titulo'];
$sw_cert_onlinecourse = $onlinecourse['sw_cert'];
$contenido_onlinecourse = $onlinecourse['contenido'];
$imagen_onlinecourse = $dominio . "cursos/" . $onlinecourse['imagen'] . ".size=6.img";
$id_onlinecourse_leccion = 0;

/* DOCENTE */
if (isset_docente() && !isset($_SESSION['participante-inscrito'])) {
    $_SESSION['participante-inscrito'] = 'true';
}

/* chat */
$roomcod = '0';

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    //$rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE estado='1' AND id_curso IN (select id_curso from cursos_rel_cursoonlinecourse where id_onlinecourse='$id_onlinecourse' and estado='1') AND id_usuario='$id_usuario' ");
    //**$rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND fecha_inicio<=CURDATE() AND fecha_final>=CURDATE()) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and ((fecha_inicio<=CURDATE() and fecha_final>=CURDATE()) OR estado='0') and sw_acceso='1')>0 ");
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and sw_acceso='1')>0 ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
    }
} elseif (isset_docente()) {
    $id_docente = docente('id');
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_docente='$id_docente' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
    }
}

if (isset_post('ingresar')) {
    $ci = post('ci');

    $usuario = post('usuario');
    $password = post('password');
    //*$cod_registro = post('cod_registro');

    /*
      $sw_participante_encontrado = false;
      $rqvdu1 = query("SELECT id FROM cursos_proceso_registro WHERE id_curso='$id_curso' AND codigo='$cod_registro' ");
      if (num_rows($rqvdu1) > 0) {
      $rqvdu2 = fetch($rqvdu1);
      $id_proceso_registro = $rqvdu2['id'];
      $rqvpc1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id_proceso_registro='$id_proceso_registro' AND nombres LIKE '$nombres' AND apellidos LIKE '$apellidos' ");
      if (num_rows($rqvpc1) > 0) {
      $sw_participante_encontrado = true;
      }
      }
     */

    /* ingreso por datos */
    $sw_participante_encontrado = false;



    $rqvpc1 = query("SELECT * FROM cursos_usuarios WHERE estado='1' AND email='$usuario' AND password='$password' ");
    if (num_rows($rqvpc1) > 0) {
        $rqvpc2 = fetch($rqvpc1);
        $id_usuario = $rqvpc2['id'];

        //***$rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND fecha_inicio<=CURDATE() AND fecha_final>=CURDATE()) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and ((fecha_inicio<=CURDATE() and fecha_final>=CURDATE()) OR estado='0') and sw_acceso='1')>0 ");
        $rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and sw_acceso='1')>0 ");
        //$rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' ");
        if (num_rows($rqdcp1) > 0) {
            //echo "<br/><br/><hr/> --> $id_usuario ";exit;
            usuarioSet('id', $id_usuario);
            $sw_participante_encontrado = true;
            /* primer ingreso participante */
            $rqdvpi1 = query("SELECT id,estado FROM cursos_onlinecourse_acceso WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ");
            $rqdvpi2 = fetch($rqdvpi1);
            if ($rqdvpi2['estado'] == '0') {
                $fecha_inicio_cv = date("Y-m-d");
                $fecha_final_cv = date("Y-m-d", strtotime("+1 week"));
                query("UPDATE cursos_onlinecourse_acceso SET estado='1', fecha_inicio='$fecha_inicio_cv', fecha_final='$fecha_final_cv' WHERE id='" . $rqdvpi2['id'] . "' ");
            }
        } else {
            $mensaje .= '<br/><div class="alert alert-info">
  <strong>USUARIO SIN ACCESO A CURSO VIRTUAL</strong> el usuario ingresado no tiene acceso a este curso virtual, ya sea por limite de acceso al curso o por deshabilitacion de participante.
</div>';
        }
    }

    /* ingreso solo ci */
    if (strlen($ci) > 5 && false) {
        $rqvpc1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso IN (select id_curso from cursos_rel_cursoonlinecourse where id_onlinecourse='$id_onlinecourse' and estado='1' and fecha_inicio<=CURDATE() and fecha_final>=CURDATE() ) AND ci='$ci' ");
        if (num_rows($rqvpc1) > 0) {

            $rqvpc2 = fetch($rqvpc1);
            if ($rqvpc2['id_usuario'] == '0') {
                $nombres = $rqvpc2['nombres'];
                $apellidos = $rqvpc2['apellidos'];
                $email = $rqvpc2['correo'];
                $celular = $rqvpc2['celular'];
                $password = substr(md5(rand(9, 999)), 2, 5);
                $sw_docente = '0';
                $fecha_registro = date("Y-m-d");
                query("INSERT INTO cursos_usuarios(
                       nombres, 
                       apellidos, 
                       ci, 
                       email, 
                       celular, 
                       password, 
                       sw_docente, 
                       fecha_registro, 
                       estado
                       ) VALUES (
                       '$nombres',
                       '$apellidos',
                       '$ci',
                       '$email',
                       '$celular',
                       '$password',
                       '$sw_docente',
                       '$fecha_registro',
                       '1'
                       )");
                $id_usuario = insert_id();
                query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='" . $rqvpc2['id'] . "' ORDER BY id DESC limit 1");
            } else {
                $id_usuario = $rqvpc2['id_usuario'];
            }
            usuarioSet('id', $id_usuario);
            $sw_participante_encontrado = true;
        }
    }

    if (!$sw_participante_encontrado) {
        $mensaje .= '<div class="alert alert-info">
  <strong>ACCESO DENEGADO</strong> los datos que ingresaste no corresponden a alg&uacute;n participante inscrito y habilitado para este curso.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-success">
  <strong>EXECELENTE</strong> participante encontrado como inscrito en este curso.
</div>
';
        $_SESSION['participante-inscrito'] = 'true';
        echo "<script>location.href='curso-online/$urltag_onlinecourse.html';</script>";
        exit;
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
                    if ($sw_acceso_a_curso) {
                        ?>

                        <hr/>

                        <div class="row">
                            <div class="col-md-12">

                                <div style="border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 10px 10px 0px 0px;
                                     padding: 10px 15px;
                                     background: #1b5b77;">
                                    <h2 style="margin-top: 0px;color: #FFF;">FINALIZACI&Oacute;N: <?php echo $titulo_onlinecourse; ?></h2>
                                </div>
                                <div style="background: #FFF;
                                     border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 0px 0px 10px 10px;
                                     padding: 10px 15px;">

                                    <?php
                                    $sw_finalizacion = true;
                                    if($onlinecourse['sw_cert']=='1'){
                                        $rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                                        while ($rqlcv2 = fetch($rqlcv1)) {
                                            $id_leccion = $rqlcv2['id'];
                                            $titulo_leccion = $rqlcv2['titulo'];
                                            $minutos_leccion = $rqlcv2['minutos'];
                                            $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' ");
                                            $tt_leccion = '0/' . $minutos_leccion;

                                            $p = 0;
                                            if (num_rows($rqdavl1) > 0) {
                                                $rqdavl2 = fetch($rqdavl1);
                                                $t = $rqdavl2['minutos'] * 60;
                                                $s = $rqdavl2['segundos'];
                                                $p = round($s * 100 / $t);
                                                if ($p > 100) {
                                                    $p = 100;
                                                    $rqdavl2['segundos'] = $t;
                                                    //*$sw_finalizacion = true;
                                                }
                                                /* excepcion para leccion de 1 minuto */
                                                if($minutos_leccion==1){
                                                    $p = 100;
                                                }
                                                $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
                                            }
                                            if ($p <= 90) {
                                                $sw_finalizacion = false;
                                            }
                                        }
                                    }

                                    if ($sw_finalizacion) {
                                        ?>
                                        <center>
                                            <img src='https://divinopastorandujar.es/sites/divinopastorandujar.es/files/findecurso-513x342.jpg' style="width: 250px;border-radius:20px;margin: 30px 0px;"/>
                                        </center>

                                        <h3 class="">&iexcl;Felicidades acabas de teminar el CURSO VIRTUAL!</h3>
                                        <div class="">
                                            Te felicitamos por culminar todos los modulos de este curso virtual, recuerda que puedes volver a revisar los contenidos en caso de que quieras reforzar tus conocimientos.
                                            <br>
                                            Esperamos que este curso te haya sido de utilidad y te invitamos a tomar otros cursos con nosotros.
                                            <?php if($onlinecourse['sw_cert']=='1'){ ?>
                                            Ahora puedes generar tu certificado digital e imprimirlo o descargarlo en forma de archivo PDF, 
                                            puedes hacerlo desde el siguiente enlace:
                                            <?php } ?>
                                        </div>

                                        <?php if($onlinecourse['sw_cert']=='1'){ ?>
                                        <div class="text-center">
                                            <hr/>
                                            <a href="curso-online-certificado/<?php echo $urltag_onlinecourse; ?>.html" class="btn btn-sm btn-primary" style="border-radius: 15px;
                                               padding: 7px 30px;
                                               border: 1px solid #c5edff;
                                               box-shadow: 2px 3px 3px 0px #c7c7c7;
                                               font-size: 14pt;">
                                                CERTIFICADO DEL CURSO VIRTUAL
                                            </a>
                                            <hr/>
                                        </div>
                                        <?php } ?>

                                        <?php
                                    } else {
                                        ?>
                                        <h3 class="">Curso a&uacute;n no finalizado</h3>
                                        <div class="">
                                            <br>
                                            Es necesario completar todos los modulos (lecciones) al menos a un 90% para finalizar el curso, puedes ver tu avance del curso en la parte inferior de esta p&aacute;gina y de esa manera identificar cuales te faltan completar.
                                            <br>
                                            <hr>
                                            <br>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div>
                                        <?php
                                        if (!$sw_finalizacion) {
                                            ?>
                                            <h4 style="background: #318cb8;
                                                color: #FFF;
                                                padding: 7px 15px;
                                                border-radius: 5px;
                                                border-bottom: 4px solid #1b5b77;">AVANCE EN EL CURSO</h4>
                                            <p>A continuaci&oacute;n te mostramos el avance que tienes en cada uno de los modulos de este curso.</p>
                                            <div>
                                                <?php
                                                $rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                                                while ($rqlcv2 = fetch($rqlcv1)) {
                                                    $id_leccion = $rqlcv2['id'];
                                                    $titulo_leccion = $rqlcv2['titulo'];
                                                    $minutos_leccion = $rqlcv2['minutos'];
                                                    $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' ");
                                                    $tt_leccion = '0/' . $minutos_leccion;
                                                    $p = 0;
                                                    if (num_rows($rqdavl1) > 0) {
                                                        $rqdavl2 = fetch($rqdavl1);
                                                        $t = $rqdavl2['minutos'] * 60;
                                                        $s = $rqdavl2['segundos'];
                                                        $p = round($s * 100 / $t);
                                                        if ($p > 100) {
                                                            $p = 100;
                                                            $rqdavl2['segundos'] = $t;
                                                        }
                                                        $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
                                                        $segundos_avanzados = $rqdavl2['segundos'];
                                                        /* excepcion para leccion de 1 minuto */
                                                        if($minutos_leccion==1){
                                                            $segundos_avanzados += 60;
                                                            $p = 100;
                                                        }
                                                    }else{
                                                        $segundos_avanzados = 0;
                                                    }
                                                    ?>
                                                    <b><?php echo $titulo_leccion; ?></b>
                                                    <span class="pull-right"><?php echo round(($segundos_avanzados) / 60, 2); ?>/<?php echo $minutos_leccion; ?> minutos</span>
                                                    <br/>
                                                    <div class="progress" style="background: #d2d8dc;">
                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $p; ?>%;">
                                                            <?php echo $p; ?>% Completo (terminado)
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                    /* enlace prev */
                                    $rqdanc1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' ORDER BY id DESC limit 1 ");
                                    if (num_rows($rqdanc1) > 0) {
                                        $rqdanc2 = fetch($rqdanc1);
                                        ?>
                                        <b onclick="location.href = 'curso-online-leccion/<?php echo $rqdanc2['urltag']; ?>/video.html';" class="btn-nav-course" style="padding: 10px 20px;">
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
                                    $rqdcve1 = query("SELECT sw_examen FROM cursos_onlinecourse WHERE id='$id_onlinecourse' ORDER BY id DESC limit 1 ");
                                    $rqdcve2 = fetch($rqdcve1);
                                    if ($rqdcve2['sw_examen']=='1') {
                                        $rqdanc2 = fetch($rqdanc1);
                                        ?>
                                        <b onclick="location.href = 'curso-online-evaluacion/<?php echo $urltag_onlinecourse; ?>.html';" class="btn-nav-course" style="border-radius: 0px 10px 10px 0px;padding-right: 3px;padding: 10px 20px;">
                                            EVALUACI&Oacute;N <i class="icon-forward" style="padding: 0px;"></i>&nbsp;
                                        </b>
                                        <?php
                                    }else{
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
                        /* chat de curso */
                        //include_once 'contenido/paginas/items/item.b.chat_content_data.php';
                        ?>

                        <br/>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div style="height:10px"></div>
        </section>
    </div>   

</div>

<!-- AJAX genera_htmlcurso_individual -->
<script>
    function solicitar_certificado(id_curso) {
        $("#AJAXCONTENT-solicitar_certificado").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.curso-online-certificado.solicitar_certificado.php',
            type: 'POST',
            data: {id_curso: id_curso},
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-solicitar_certificado").html(data);
            }
        });
    }
</script>

<!-- Modal -->
<div id="MODAL-solicitar_certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISI&Oacute;N DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-solicitar_certificado"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
