<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $id_certificado = post('dat');
    $anv_rev = post('anv_rev');
    $rqidc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados cc WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqidc2 = fetch($rqidc1);
    $certificado_id = $rqidc2['certificado_id'];
    if($anv_rev=='anverso'){
        echo $dominio."contenido/paginas/procesos/pdfs/certificado-copia-legalizada-anverso.php?id_certificado=$certificado_id";
    }else{
        echo $dominio."contenido/paginas/procesos/pdfs/certificado-copia-legalizada.php?id_certificado=$certificado_id";
    }
} else {
    echo "Denegado!";
}
