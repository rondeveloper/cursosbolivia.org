<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$rqh1 = query("SELECT * FROM hash_captcha_archivos ");
echo "<style>td{border:1px solid #DDD;padding:5px;}</style>";
echo "<table>";
while($rqh2 = fetch($rqh1)){   
    
    $urlarchivofisico = '/contenido/imagenes/captcha/'.$rqh2['nom_arch_fisico'];
    
    $cont1 = file_get_contents('https://www.infosicoes.com'.$urlarchivofisico);
    
    $base64 = base64_encode($cont1);
    
    $md5_base64 = md5($base64);
    
    echo "<tr>";
    
    echo "<td>".$rqh2['codcaptcha']."</td>";
    echo "<td>".$rqh2['estado']."</td>";
    echo "<td>".$rqh2['nom_arch_fisico']."</td>";
    echo "<td>".'<img src="'.$urlarchivofisico.'" style="height:20px;"/>'."</td>";
    echo "<td>".$md5_base64."</td>";
    echo "<td>".$base64."</td>";

    echo "</tr>";
    
    query("UPDATE hash_captcha_archivos SET base64='$base64',hashbase64='$md5_base64' WHERE id='".$rqh2['id']."' ");
}
echo "</table>";

