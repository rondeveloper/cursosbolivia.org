<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $id_administrador = administrador('id');

    $id_curso = post('id_curso');
    $nro_certificado = post('nro_certificado');
    $ids = post('ids');


    /* limpia datos de id participante */
    $ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $ids));
    $id_participantes = '0';
    foreach ($ar_exp_aux as $value) {
        $id_participantes .= "," . (int) $value;
    }

    /* formato */
    $rqd_a1 = query("SELECT id_certificado,id_certificado_2,id_certificado_3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqd_a2 = fetch($rqd_a1);
    if ($nro_certificado == '1234') {
        /* en conjunto por orden numerico */
        $text_cert_2 = "enconjunto=true&";
        $id_certificado = $rqd_a2['id_certificado'];
    } elseif ($nro_certificado == 'abcd') {
        /* en conjunto por orden alfabetico */
        $text_cert_2 = "enconjuntoalfabetico=true&";
        $id_certificado = $rqd_a2['id_certificado'];
    } elseif ((int)$nro_certificado == 3) {
        $text_cert_2 = "cert3=true&";
        $id_certificado = $rqd_a2['id_certificado_3'];
    } elseif ((int)$nro_certificado == 2) {
        $text_cert_2 = "cert2=true&";
        $id_certificado = $rqd_a2['id_certificado_2'];
    } elseif ((int)$nro_certificado > 100) {
        $text_cert_2 = "cert_adicional=true&id_cert_adicional=$nro_certificado&";
        $id_certificado = $nro_certificado;
    } else {
        $text_cert_2 = "";
        $id_certificado = $rqd_a2['id_certificado'];
    }
    $rqd_b1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqd_b2 = fetch($rqd_b1);
    //$formato_certificado = $rqd_b2['formato'];
    $formato_certificado = 3;

    echo $dominio."contenido/paginas/procesos/pdfs/certificado-$formato_certificado-masivo.php?" . $text_cert_2 . "id_participantes=$id_participantes&id_administrador=$id_administrador&hash=".md5($id_administrador.'hash');
} else {
    echo "Denegado!";
}
