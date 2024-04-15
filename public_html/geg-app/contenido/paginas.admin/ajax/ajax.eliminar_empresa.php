<?php
session_start();

include_once '../../configuracion/config.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset($_SESSION['login_esf_captcha']) && isset($_POST['id'])) {
    if ($_SESSION['login_esf_nivel'] == 'superadmin') {

        $r1 = mysql_query("DELETE FROM empresas WHERE id='" . $_POST['id'] . "'") or die(mysql_error());
        if ($r1) {
            echo "Eliminado";
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
