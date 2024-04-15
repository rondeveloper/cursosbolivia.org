<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$id_referido = post('id_referido');
query("UPDATE recomendaciones_referidos SET estado='1' WHERE id='$id_referido' ORDER BY id DESC limit 1 ");

$rqdrf1 = query("SELECT * FROM recomendaciones_referidos WHERE id='$id_referido' ORDER BY id DESC limit 1 ");
$rqdrf2 = fetch($rqdrf1);
$id_recomendacion = $rqdrf2['id_recomendacion'];
query("INSERT INTO recomendaciones_referidos (id_recomendacion) VALUES ('$id_recomendacion')");
$new_id_referido = insert_id();

/* curso */
$rqdrcm1 = query("SELECT c.titulo FROM cursos c INNER JOIN recomendaciones r ON r.id_curso=c.id WHERE r.id='$id_recomendacion' ORDER BY r.id DESC LIMIT 1 ");
$rqdrcm2 = fetch($rqdrcm1);

$title_pr = str_replace('?', '', utf8_decode($rqdrcm2['titulo']));
$link_pr = $dominio.'r/' . $new_id_referido . '/';
$msj_whatsapp = 'Hola__Te recomiendo este curso:__' . $title_pr . '__' . $link_pr . '';
$txt_whatsapp = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $msj_whatsapp)));

echo 'https://api.whatsapp.com/send?text='.$txt_whatsapp;