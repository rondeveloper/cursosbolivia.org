<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset($_SESSION['login_esf_captcha']) && post('id') ) {
    if ($_SESSION['login_esf_nivel'] == 'superadmin') {

        $r1 = query("DELETE FROM convocatorias_nacionales WHERE id='" . post('id') . "'") or die(mysqli_error($mysqli));
        if ($r1) {
            echo "Convocatoria Eliminada!";
        } else {
            echo "Error";
        }
    }else{
        echo "Accion NO PERMITIDA!";
    }
}else{
    echo "Denegado!";
}

?>
