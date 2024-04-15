<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset($_SESSION['login_esf_captcha'])) {
    
    $r1 = query("UPDATE control_web SET conv_nac_htmls='0' ") or die(mysqli_error($mysqli));
    if ($r1) {
        echo "<img onclick='activar_htmls();' style='width:70px;height:30px;cursor:pointer;' src='".$dominio_www."contenido/imagenes/images/off.jpg'/>";
    } else {
        echo "Error";
    }
} else {
    echo "Denegado!";
}
?>
