<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {

    $id_certificado = post('dat');
    $id_administrador = administrador('id');
    $rqidc1 = query("SELECT (select formato from cursos_certificados where id=cc.id_certificado order by id desc limit 1)formato_certificado,certificado_id FROM cursos_emisiones_certificados cc WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqidc2 = fetch($rqidc1);
    $formato_certificado = $rqidc2['formato_certificado'];
    $formato_certificado = 3;
    $certificado_id = $rqidc2['certificado_id'];
    
    echo $dominio."contenido/paginas/procesos/pdfs/certificado-$formato_certificado.php?id_certificado=$certificado_id&id_administrador=$id_administrador&hash=".md5($id_administrador.'hash');
} else {
    echo "Denegado!";
}
