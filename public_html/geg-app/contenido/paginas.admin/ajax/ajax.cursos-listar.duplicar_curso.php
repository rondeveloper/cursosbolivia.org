<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $id_curso = post('id_curso');

    $rqc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);

    $titulo_curso = $rqc2['titulo'];

    $fecha = date("Y") . '-12-31';
    $fecha_registro = date("Y-m-d H:i");

    query("INSERT INTO cursos(
           id_ciudad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_material, 
           id_abreviacion, 
           id_cuenta_bancaria, 
           fecha, 
           titulo_identificador, 
           titulo, 
           contenido, 
           lugar, 
           horarios, 
           duracion, 
           imagen, 
           imagen2, 
           imagen3, 
           imagen4, 
           imagen_gif, 
           google_maps, 
           expositor, 
           colaborador, 
           fb_txt_requisitos, 
           fb_txt_dirigido, 
           fb_hashtags, 
           observaciones, 
           incrustacion, 
           seccion, 
           pixelcode, 
           texto_qr, 
           sw_siguiente_semana, 
           short_link, 
           fecha_registro, 
           estado
           ) VALUES (
           '" . $rqc2['id_ciudad'] . "',
           '" . $rqc2['id_organizador'] . "',
           '" . $rqc2['id_lugar'] . "',
           '" . $rqc2['id_docente'] . "',
           '" . $rqc2['id_material'] . "',
           '" . $rqc2['id_abreviacion'] . "',
           '" . $rqc2['id_cuenta_bancaria'] . "',
           '" . $fecha . "',
           '" . str_replace('-tmp','',$rqc2['titulo_identificador']).'-tmp' . "',
           '" . $rqc2['titulo'] . "',
           '" . $rqc2['contenido'] . "',
           '" . $rqc2['lugar'] . "',
           '" . $rqc2['horarios'] . "',
           '" . $rqc2['duracion'] . "',
           '" . $rqc2['imagen'] . "',
           '" . $rqc2['imagen2'] . "',
           '" . $rqc2['imagen3'] . "',
           '" . $rqc2['imagen4'] . "',
           '" . $rqc2['imagen_gif'] . "',
           '" . $rqc2['google_maps'] . "',
           '" . $rqc2['expositor'] . "',
           '" . $rqc2['colaborador'] . "',
           '" . $rqc2['fb_txt_requisitos'] . "',
           '" . $rqc2['fb_txt_dirigido'] . "',
           '" . $rqc2['fb_hashtags'] . "',
           '" . $rqc2['observaciones'] . "',
           '" . $rqc2['incrustacion'] . "',
           '" . $rqc2['seccion'] . "',
           '" . addslashes($rqc2['pixelcode']) . "',
           '" . $rqc2['texto_qr'] . "',
           '" . $rqc2['sw_siguiente_semana'] . "',
           '" . $rqc2['short_link'] . "',
           '" . $fecha_registro . "',
           '2'
           )");
    $id_curso_nuevo = mysql_insert_id();

    /* tags */
    $rqdcct1 = query("SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso' ");
    while ($rqdcct2 = mysql_fetch_array($rqdcct1)) {
        $id_tag = $rqdcct2['id_tag'];
        $rqverif1 = query("SELECT COUNT(1) AS total FROM cursos_rel_cursostags WHERE id_curso='$id_curso_nuevo' AND id_tag='$id_tag' ");
        $rqverif2 = mysql_fetch_array($rqverif1);
        if ($rqverif2['total'] == 0) {
            query("INSERT INTO cursos_rel_cursostags (id_curso,id_tag) VALUES ('$id_curso_nuevo','$id_tag') ");
        }
    }

    //movimiento('Duplicacion de curso [' . $titulo_curso . '][new:' . $id_curso_nuevo . ']', 'duplicacion-curso', 'curso', $id_curso);
    logcursos('Creacion de curso por duplicacion[' . $id_curso . ':' . $titulo_curso. ']', 'curso-creacion', 'curso', $id_curso_nuevo);

    //echo "Curso duplicado.";
    echo $id_curso_nuevo;
} else {
    echo "Denegado!";
}
