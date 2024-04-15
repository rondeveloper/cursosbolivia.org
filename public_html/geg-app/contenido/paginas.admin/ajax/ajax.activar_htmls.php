<?php

session_start();

include_once '../../configuracion/config.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset($_SESSION['login_esf_captcha'])) {
    
    $r1 = mysql_query("UPDATE control_web SET conv_nac_htmls='1' ") or die(mysql_error());
    if ($r1) {
        echo "<img onclick='desactivar_htmls();' style='width:70px;height:30px;cursor:pointer;' src='contenido/imagenes/images/on.jpg'/>";
    } else {
        echo "Error";
    }
} else {
    echo "Denegado!";
}
?>
