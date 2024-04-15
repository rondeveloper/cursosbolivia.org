<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $id_administrador = administrador('id');

    $id_curso = post('id_curso');
    $id_certificado = post('nro_certificado');
    $ids = post('ids');


    /* limpia datos de id participante */
    $ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $ids));
    $id_participantes = '0';
    foreach ($ar_exp_aux as $value) {
        $id_participantes .= "," . (int) $value;
    }

    $rqdec1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_certificado='$id_certificado' AND id_curso='$id_curso' ");
    $ids_emisiones = '0';
    while($rqdec2 = fetch($rqdec1)){
        $ids_emisiones .= "," . (int) $rqdec2['id'];
    }
    
    $rqd_b1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqd_b2 = fetch($rqd_b1);
    $formato_certificado = $rqd_b2['formato'];

    echo $dominio."contenido/paginas/procesos/pdfs/certificado-digital-3-masivo.php?ids_emisiones=$ids_emisiones&id_administrador=$id_administrador&hash=".md5($id_administrador.'hash');
} else {
    echo "Denegado!";
}
