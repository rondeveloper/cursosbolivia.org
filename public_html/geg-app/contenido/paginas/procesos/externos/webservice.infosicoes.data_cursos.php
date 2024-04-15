<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* USUARIO */

$arrayResponse = array();
$array_cursos = array();
$array_relcursostags = array();
$resultado = '0';

/* cursos */
$rqvnc1 = query("SELECT 
        *,
        (select nombre from cursos_lugares where id=cursos.id_lugar)dr_lugar, 
        (select salon from cursos_lugares where id=cursos.id_lugar)dr_salon, 
        (select google_maps from cursos_lugares where id=cursos.id_lugar)dr_google_maps 
        FROM cursos WHERE estado='1' AND sw_flag_infosicoes='1' ORDER BY id DESC limit 150 ");

while ($curso = mysql_fetch_array($rqvnc1)) {

    $resultado = '1';

    $id_curso = $curso['id'];
    $titulo = $curso['titulo'];
    $titulo_identificador = $curso['titulo_identificador'];
    $id_ciudad = $curso['id_ciudad'];
    $id_modalidad = $curso['id_modalidad'];
    $lugar = $curso['dr_lugar'];
    $salon = $curso['dr_salon'];
    $fecha = $curso['fecha'];
    $horarios = $curso['horarios'];
    $imagen = $curso['imagen'];
    $costo = $curso['costo'];
    $costo2 = $curso['costo2'];
    $costo3 = $curso['costo3'];
    $fecha2 = $curso['fecha2'];
    $fecha3 = $curso['fecha3'];
    $sw_fecha2 = $curso['sw_fecha2'];
    $sw_fecha3 = $curso['sw_fecha3'];
    $flag_publicacion = $curso['flag_publicacion'];
    $estado = $curso['estado'];
    $contenido_curso = base64_encode(getContenidoCurso($curso));
    $google_maps = base64_encode($curso['dr_google_maps']);
    
    $array_curso = array(
        'id_curso' => $id_curso,
        'titulo' => $titulo,
        'titulo_identificador' => $titulo_identificador,
        'id_ciudad' => $id_ciudad,
        'id_modalidad' => $id_modalidad,
        'lugar' => $lugar,
        'salon' => $salon,
        'fecha' => $fecha,
        'horarios' => $horarios,
        'imagen' => $imagen,
        'costo' => $costo,
        'costo2' => $costo2,
        'costo3' => $costo3,
        'fecha2' => $fecha2,
        'fecha3' => $fecha3,
        'sw_fecha2' => $sw_fecha2,
        'sw_fecha3' => $sw_fecha3,
        'flag_publicacion' => $flag_publicacion,
        'google_maps' => $google_maps,
        'contenido' => $contenido_curso,
        'estado' => $estado
        );

    $rqdr1 = query("SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso' ");
    while ($rqdr2 = mysql_fetch_array($rqdr1)) {
        $id_tag = $rqdr2['id_tag'];
        $array_relcursotag = array(
            'id_curso' => $id_curso,
            'id_tag' => $id_tag
        );
        array_push($array_relcursostags, $array_relcursotag);
    }

    array_push($array_cursos, $array_curso);
}

$arrayResponse['result'] = $resultado;
$arrayResponse['cursos'] = $array_cursos;
$arrayResponse['relcursostags'] = $array_relcursostags;

echo json_encode($arrayResponse);
