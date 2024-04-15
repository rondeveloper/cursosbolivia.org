<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
    exit;
}

$id_curso = post('id_curso');
$id_certificado = post('id_certificado');
$ids = post('ids');

/* limpia datos de id participante */
$ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $ids));
$id_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $id_participantes .= "," . (int) $value;
}

$rqdec1 = query("SELECT id FROM certificados_culminacion_emisiones WHERE id_certificado_culminacion='$id_certificado' ");
$ids_emisiones = '0';
while($rqdec2 = fetch($rqdec1)){
    $ids_emisiones .= "," . (int) $rqdec2['id'];
}

$hash = md5(md5($ids_emisiones . 'cce5616'));
echo $dominio."contenido/paginas/procesos/pdfs/certificado-culminacion-ipelc-digital.php?id_emision=$ids_emisiones&hash=$hash";
