<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset($_SESSION['login_esf_captcha']) && post('id') ) {
    if ($_SESSION['login_esf_nivel'] == 'superadmin') {

        $r1 = mysql_query("DELETE FROM convocatorias_nacionales WHERE id='" . post('id') . "'") or die(mysql_error());
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
