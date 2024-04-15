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

/* DOCENTE aux */
if (isset_docente() && !isset($_SESSION['participante-inscrito'])) {
    $_SESSION['participante-inscrito'] = 'true';
}

/* chat */
$roomcod = '0';

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    $rqvpcv1 = query("SELECT p.id,p.id_curso,(r.id)dr_id_rel_cursoonlinecourse FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON p.id_curso=r.id_curso WHERE r.id_onlinecourse='$id_onlinecourse' AND p.id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and sw_acceso='1')>0 ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
        $id_participante = $rqvpcv2['id'];
        $id_rel_cursoonlinecourse = $rqvpcv2['dr_id_rel_cursoonlinecourse'];
    }
} elseif (isset_docente()) {
    $id_docente = docente('id');
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_docente='$id_docente' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
        $id_participante = 0;
        $id_rel_cursoonlinecourse = $rqvpcv2['id'];
    }
}
?>

<link type="text/css" rel="stylesheet" href="<?php echo $dominio_www; ?>contenido/css/style-chat-course-vr.css"/>

<div style="height:50px;"></div>
<div class="boxcontent-curso-online">
    <div class="bar-left-curso-online">
        <?php
        include_once 'pages/items/item.d.curso_online.bar_left.php';
        ?>
    </div>
    <div class="wrapsemibox">
        <section class="containerXX" style="padding: 2px 5px;">
            <div style="height:10px"></div>
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <?php
                        include_once 'pages/items/item.m.datos_onlinecourse.php';
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
                                    <h2 style="margin-top: 0px;color: #FFF;">CALIFICACIONES: <?php echo $titulo_onlinecourse; ?></h2>
                                </div>
                                <div style="background: #FFF;
                                     border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 0px 0px 10px 10px;
                                     padding: 10px 15px;">

                                    <?php
                                    $sw_finalizacion = true;
                                    ?>
                                    <center>
                                        <img src='https://imagenes.universia.net/gc/net/images/educacion/b/bu/bue/buenas-notas-.jpg' style="width: 250px;border-radius:20px;margin: 30px 0px;"/>
                                    </center>

                                    <h3 class="">Las notas ya fueron subidas al sistema</h3>
                                    <div class="">
                                        A continuaci&oacute;n te mostramos las calificaciones asociadas a tu cuenta para este curso. Esperamos que este curso te haya sido de utilidad y te invitamos a tomar otros cursos con nosotros.
                                    </div>


                                    <br>


                                    <?php
                                    /* notas sistematicas : ASISTENCIA */
                                    $rqvns1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='ASISTENCIA' ");
                                    $rqvns2 = fetch($rqvns1);
                                    $porcentaje_nota_asistencia = $rqvns2['porcentaje'];
                                    $estado_nota_asistencia = $rqvns2['estado'];
                                    /* notas sistematicas : TAREAS */
                                    $rqvnsb1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='TAREAS' ");
                                    $rqvns2 = fetch($rqvnsb1);
                                    $porcentaje_nota_tareas = $rqvns2['porcentaje'];
                                    $estado_nota_tareas = $rqvns2['estado'];
                                    /* notas sistematicas : EXAMEN VIRTUAL */
                                    $rqvnsc1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='EXAMEN VIRTUAL' ");
                                    $rqvns2 = fetch($rqvnsc1);
                                    $porcentaje_nota_examenvirtual = $rqvns2['porcentaje'];
                                    $estado_nota_examenvirtual = $rqvns2['estado'];


                                    /* total asistencias a verificar */
                                    $rqdcn1 = query("SELECT DISTINCT fecha FROM cursos_onlinecourse_asistencia WHERE id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                                    $cnt_asistencias = num_rows($rqdcn1);

                                    /* total tareas asignadas */
                                    $rqdcnt1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_tareas t INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=t.id_onlinecourse WHERE r.id='$id_rel_cursoonlinecourse' ");
                                    $rqdcnt2 = fetch($rqdcnt1);
                                    $cnt_tareas = $rqdcnt2['total'];

                                    /* registro */
                                    $rqp1 = query("SELECT p.* FROM cursos_participantes p WHERE p.id='$id_participante' ORDER BY id DESC limit 1 ");
                                    $rqp2 = fetch($rqp1);
                                    /* nota asistencia */
                                    $rqdac1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_asistencia WHERE id_participante='$id_participante' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                                    $rqdac2 = fetch($rqdac1);
                                    $nota_asistencia = ceil($rqdac2['total'] * 100 / $cnt_asistencias);

                                    /* nota tareas */
                                    $rqdacet1 = query("SELECT sum(e.calificacion) AS total FROM cursos_onlinecourse_tareasenvios e INNER JOIN cursos_onlinecourse_tareas t ON t.id=e.id_tarea INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=t.id_onlinecourse WHERE e.id_usuario='" . $rqp2['id_usuario'] . "' AND r.id='$id_rel_cursoonlinecourse' ");
                                    $rqdacet2 = fetch($rqdacet1);
                                    $nota_tareas = ceil($rqdacet2['total'] / $cnt_tareas);

                                    /* nota examen */
                                    $rqdacev1 = query("SELECT e.total_correctas,e.total_preguntas FROM cursos_onlinecourse_evaluaciones e INNER JOIN cursos_rel_cursoonlinecourse r ON e.id_onlinecourse=r.id_onlinecourse WHERE e.id_usuario='" . $rqp2['id_usuario'] . "' AND r.id='$id_rel_cursoonlinecourse' ORDER BY e.id DESC limit 1  ");
                                    $nota_examen = 0;
                                    if (num_rows($rqdacev1) > 0) {
                                        $rqdacev2 = fetch($rqdacev1);
                                        $nota_examen = ceil($rqdacev2['total_correctas'] * 100 / $rqdacev2['total_preguntas']);
                                    }

                                    /* nota final */
                                    $nota_final = 0;
                                    if ($porcentaje_nota_asistencia > 0 && $estado_nota_asistencia == '1') {
                                        $nota_final += ($nota_asistencia * $porcentaje_nota_asistencia / 100);
                                    }
                                    if ($porcentaje_nota_tareas > 0 && $estado_nota_tareas == '1') {
                                        $nota_final += ($nota_tareas * $porcentaje_nota_tareas / 100);
                                    }
                                    if ($porcentaje_nota_examenvirtual > 0 && $estado_nota_examenvirtual == '1') {
                                        $nota_final += ($nota_examen * $porcentaje_nota_examenvirtual / 100);
                                    }

                                    $array_notas_adicionales = array();
                                    $rqvnna1 = query("SELECT * FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='0' AND estado='1' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ORDER BY id ASC ");
                                    while ($rqvnna2 = fetch($rqvnna1)) {
                                        $rqdnad1 = query("SELECT calificacion FROM rel_notacalificacion WHERE id_rel_cursoonlinecoursenotas='" . $rqvnna2['id'] . "' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                                        if (num_rows($rqdnad1) > 0) {
                                            $rqdnad2 = fetch($rqdnad1);
                                            $calificacion = $rqdnad2['calificacion'];
                                        } else {
                                            $calificacion = 0;
                                        }
                                        $nota_final += ($calificacion * $rqvnna2['porcentaje'] / 100);
                                        array_push($array_notas_adicionales, array('nombre' => $rqvnna2['nombre'], 'calificacion' => $calificacion, 'porcentaje' => $rqvnna2['porcentaje']));
                                    }
                                    $nota_final = round($nota_final, 2);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tr>
                                                    <th>CRITERIO</th>
                                                    <th>PORCENTAJE</th>
                                                    <th>NOTA</th>
                                                </tr>
                                                <?php
                                                if ($porcentaje_nota_asistencia > 0 && $estado_nota_asistencia == '1') {
                                                    ?>
                                                    <tr>
                                                        <td>ASISTENCIA</td>
                                                        <td><?php echo $porcentaje_nota_asistencia; ?> %</td>
                                                        <td><?php echo $nota_asistencia; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($porcentaje_nota_tareas > 0 && $estado_nota_tareas == '1') {
                                                    ?>
                                                    <tr>
                                                        <td>TAREAS</td>
                                                        <td><?php echo $porcentaje_nota_tareas; ?> %</td>
                                                        <td><?php echo $nota_tareas; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                if ($porcentaje_nota_examenvirtual > 0 && $estado_nota_examenvirtual == '1') {
                                                    ?>
                                                    <tr>
                                                        <td>EXAMEN VIRTUAL</td>
                                                        <td><?php echo $porcentaje_nota_examenvirtual; ?> %</td>
                                                        <td><?php echo $nota_examen; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                foreach ($array_notas_adicionales as $array_nota) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $array_nota['nombre']; ?></td>
                                                        <td><?php echo $array_nota['porcentaje']; ?> %</td>
                                                        <td><?php echo $array_nota['calificacion']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td>NOTA FINAL</td>
                                                    <td>100 %</td>
                                                    <td><?php echo $nota_final; ?></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <hr>
                                            <p class="text-center">
                                                Su nota final es:
                                            </p>
                                            <div class="text-center" style="padding: 35px 0px 25px 0px;background: #aeefaa;">
                                                <b style="font-size: 40pt;color: #ffffff;"><?php echo round($nota_final); ?></b>
                                            </div>
                                            <br>
                                            <hr>
                                            <br>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <hr/>
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
