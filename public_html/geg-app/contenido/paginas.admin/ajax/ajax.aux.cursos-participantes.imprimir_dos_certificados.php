<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $id_curso = post('id_curso');
    $ids = post('ids');
    $nro_certificado = '1';

    /* limpia datos de id participante */
    $ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $ids));
    $id_participantes = '0';
    foreach ($ar_exp_aux as $value) {
        /* id_emision_certificados */
        $id_participantes .= "," . (int) $value;
    }

    /* formato */
    $rqd_a1 = query("SELECT id_certificado,id_certificado_2 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqd_a2 = mysql_fetch_array($rqd_a1);
    if ($nro_certificado == '2') {
        $text_cert_2 = "cert2=true&";
        $id_certificado = $rqd_a2['id_certificado_2'];
    } else {
        $text_cert_2 = "";
        $id_certificado = $rqd_a2['id_certificado'];
    }
    $rqd_b1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqd_b2 = mysql_fetch_array($rqd_b1);
    $formato_certificado = $rqd_b2['formato'];

    echo "http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-$formato_certificado-masivo.php?imp2cert=true&id_participantes=$id_participantes";
} else {
    echo "Denegado!";
}

