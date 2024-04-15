<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $id_curso = post('id_curso');

    $rqc1 = query("SELECT * FROM blog WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqc2 = fetch($rqc1);

    $titulo_curso = $rqc2['titulo'];

    $fecha = date("Y") . '-12-31';
    $fecha_registro = date("Y-m-d H:i");

    query("INSERT INTO blog(
           id_ciudad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_material, 
           id_abreviacion, 
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
    $id_curso_nuevo = insert_id();


    logcursos('Creacion de blog por duplicacion[' . $id_curso . ':' . $titulo_curso. ']', 'blog-creacion', 'blog', $id_curso_nuevo);

    echo $id_curso_nuevo;
} else {
    echo "Denegado!";
}
