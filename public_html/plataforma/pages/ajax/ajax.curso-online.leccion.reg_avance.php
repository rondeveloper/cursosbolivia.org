<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_usuario()) {
    echo "DENEGADO";
    exit;
}

$id_usuario = usuario('id');
$id_onlinecourse = post('id_onlinecourse');
$id_onlinecourse_leccion = post('id_onlinecourse_leccion');

/* actualiza avance */
$flag = date("Y-m-d H:i:s");
$rqvlecav1 = query("SELECT id,flag,segundos FROM cursos_onlinecourse_lec_avance WHERE id_onlinecourse_leccion='$id_onlinecourse_leccion' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
if (num_rows($rqvlecav1) == 0) {
        query("INSERT INTO cursos_onlinecourse_lec_avance (
           id_onlinecourse_leccion,
           id_usuario,
           segundos,
           flag
           ) VALUES (
           '$id_onlinecourse_leccion',
           '$id_usuario',
           '0',
           '$flag'
           ) ");
} else {
        $rqvlecav2 = fetch($rqvlecav1);
        $id_onlinecourse_lec_avance = $rqvlecav2['id'];
        $p_flag = $rqvlecav2['flag'];
        $p_segundos = $rqvlecav2['segundos'];
        $time_spend = strtotime($flag)-strtotime($p_flag);
        if(($time_spend)<(180)){
            query("UPDATE cursos_onlinecourse_lec_avance SET flag='$flag',segundos='".(int)($p_segundos+$time_spend)."' WHERE id='$id_onlinecourse_lec_avance' ORDER BY id DESC limit 1 ");
        }else{
            query("UPDATE cursos_onlinecourse_lec_avance SET flag='$flag' WHERE id='$id_onlinecourse_lec_avance' ORDER BY id DESC limit 1 ");
        }
}

usuarioSet($id_usuario);

echo "1";