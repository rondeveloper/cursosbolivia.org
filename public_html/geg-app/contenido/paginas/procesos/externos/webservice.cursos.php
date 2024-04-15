<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* USUARIO */

$arrayResponse = array();
$array_cursos = array();
$resultado = '0';

/* verifica nuevos comentarios */
$rqvnc1 = query("SELECT c.id,c.titulo,c.fecha,d.nombre AS departamento,c.imagen FROM cursos c INNER JOIN departamentos d ON c.id_ciudad=d.id WHERE c.estado='1' ORDER BY id DESC limit 50 ");
while ($rqvnc2 = mysql_fetch_array($rqvnc1)) {

    $resultado = '1';
    $id_curso = $rqvnc2['id'];
    $tituloCurso = $rqvnc2['titulo'];
    $fechaCurso = $rqvnc2['fecha'];
    $departamentoCurso = $rqvnc2['departamento'];

    $urlImagenCurso = "https://cursos.bo/contenido/imagenes/paginas/" . $rqvnc2['imagen'];
    if (!file_exists("../../../imagenes/paginas/" . $rqvnc2['imagen'])) {
        $urlImagenCurso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rqvnc2['imagen'];
    }

    $array_curso = array(
        'idCurso' => $id_curso,
        'tituloCurso' => $tituloCurso,
        'fechaCurso' => $fechaCurso,
        'departamentoCurso' => $departamentoCurso,
        'urlImagenCurso' => $urlImagenCurso);

    array_push($array_cursos, $array_curso);
}

$arrayResponse['result'] = $resultado;
$arrayResponse['cursos'] = $array_cursos;

echo json_encode($arrayResponse);
