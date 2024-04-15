<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador() || !acceso_cod('adm-visibilidad-cursos')) {
    echo "DENEGADO";
    exit;
}

$ids_departamentos_visibles = '0';
$sw_aux = true;
$rqddac1 = query("SELECT id FROM departamentos WHERE tipo='1' ");
while ($rqddac2 = fetch($rqddac1)) {
    if (isset_post('d-' . $rqddac2['id'])) {
        $ids_departamentos_visibles .= ',' . $rqddac2['id'];
    }else{
        $sw_aux = false;
    }
}
if($sw_aux){
    $ids_departamentos_visibles = '';
}
query("UPDATE cursos_webdata SET ids_departamentos_visibles='$ids_departamentos_visibles' WHERE id='1' ");
