<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_docente()) {
    echo "ACCESO DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_participante = post('id_participante');
$id_rel_cursoonlinecourse = post('id_rel_cursoonlinecourse');




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
if ($porcentaje_nota_asistencia > 0 && $estado_nota_asistencia=='1') {
    $nota_final += ($nota_asistencia * $porcentaje_nota_asistencia / 100);
}
if ($porcentaje_nota_tareas > 0 && $estado_nota_tareas=='1') {
    $nota_final += ($nota_tareas * $porcentaje_nota_tareas / 100);
}
if ($porcentaje_nota_examenvirtual > 0 && $estado_nota_examenvirtual=='1') {
    $nota_final += ($nota_examen * $porcentaje_nota_examenvirtual / 100);
}

$rqvnna1 = query("SELECT * FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='0' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ORDER BY id ASC ");
while ($rqvnna2 = fetch($rqvnna1)) {
    $rqdnad1 = query("SELECT calificacion FROM rel_notacalificacion WHERE id_rel_cursoonlinecoursenotas='" . $rqvnna2['id'] . "' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if (num_rows($rqdnad1) > 0) {
        $rqdnad2 = fetch($rqdnad1);
        $calificacion = $rqdnad2['calificacion'];
    } else {
        $calificacion = 0;
    }
    $nota_final += ($calificacion * $rqvnna2['porcentaje'] / 100);
}
echo round($nota_final,2);
