<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* DATA */
$id_curso = (int) get('id');

/* USUARIO */

/* RESPONSE */
$arrayResponse = array();
$array_curso = array();
$resultado = '0';

/* registro */
$rqvnc1 = query("SELECT "
        . "c.id,c.titulo,c.fecha,d.nombre AS departamento,c.imagen,c.horarios,cd.nombre as ciudad,l.nombre as lugar,l.salon,c.costo,c.sw_fecha2,c.costo2 ,c.fecha2 ,c.sw_fecha3 ,c.costo3 ,c.fecha3 ,c.sw_fecha_e ,c.costo_e ,c.fecha_e"
        . " FROM cursos c "
        . "INNER JOIN departamentos d ON c.id_departamento=d.id "
        . "INNER JOIN ciudades cd ON c.id_ciudad=cd.id "
        . "INNER JOIN cursos_lugares l ON c.id_lugar=l.id "
        . "WHERE c.id='$id_curso' "
        . "ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqvnc1) > 0) {
    $resultado = '1';

    $rqvnc2 = mysql_fetch_array($rqvnc1);
    $tituloCurso = $rqvnc2['titulo'];
    $fechaCurso = fecha_curso_D_d_m($rqvnc2['fecha']);
    $departamentoCurso = $rqvnc2['departamento'];
    $duracionCurso = $rqvnc2['horarios'];
    $ciudadCurso = $rqvnc2['ciudad'];
    $lugarCurso = $rqvnc2['lugar'];
    $salonCurso = $rqvnc2['salon'];
    $costoCurso = $rqvnc2['costo'];

    $txt_descuento_uno_curso = '';
    $txt_descuento_dos_curso = '';
    $txt_descuento_est_curso = '';
    if ($rqvnc2['sw_fecha2'] == '1') {
        $txt_descuento_uno_curso = $rqvnc2['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($rqvnc2['fecha2']);
    }
    if ($rqvnc2['sw_fecha3'] == '1') {
        $txt_descuento_dos_curso = $rqvnc2['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($rqvnc2['fecha3']);
    }
    if ($rqvnc2['sw_fecha_e'] == '1') {
        $txt_descuento_est_curso = $rqvnc2['costo_e'] . ' Bs. hasta el ' . fecha_curso_D_d_m($rqvnc2['fecha_e']) . ' (Estudiantes)';
    }
    $descuentoCurso = trim($txt_descuento_uno_curso . ' ' . $txt_descuento_dos_curso . ' ' . $txt_descuento_est_curso);

    $urlImagenCurso = "https://cursos.bo/contenido/imagenes/paginas/" . $rqvnc2['imagen'];
    if (!file_exists("../../../imagenes/paginas/" . $rqvnc2['imagen'])) {
        $urlImagenCurso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rqvnc2['imagen'];
    }

    $array_curso = array(
        'idCurso' => $id_curso,
        'tituloCurso' => $tituloCurso,
        'fechaCurso' => $fechaCurso,
        'duracionCurso' => $duracionCurso,
        'departamentoCurso' => $departamentoCurso,
        'ciudadCurso' => $ciudadCurso,
        'urlImagenCurso' => $urlImagenCurso,
        'lugarCurso' => $lugarCurso,
        'salonCurso' => $salonCurso,
        'costoCurso' => $costoCurso,
        'descuentoCurso' => $descuentoCurso,
    );
}

$arrayResponse['result'] = $resultado;
$arrayResponse['curso'] = $array_curso;

echo json_encode($arrayResponse);

/*
function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}
*/
