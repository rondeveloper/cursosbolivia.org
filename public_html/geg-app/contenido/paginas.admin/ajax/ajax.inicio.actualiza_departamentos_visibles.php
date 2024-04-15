<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador() || !acceso_cod('adm-visibilidad-cursos')) {
    echo "DENEGADO";
    exit;
}

$ids_departamentos_visibles = '0';
$sw_aux = true;
$rqddac1 = query("SELECT id FROM departamentos WHERE tipo='1' ");
while ($rqddac2 = mysql_fetch_array($rqddac1)) {
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
