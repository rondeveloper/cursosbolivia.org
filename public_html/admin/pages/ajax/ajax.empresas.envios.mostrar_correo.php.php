<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if(isset_administrador()){
    
    $id_envio = post('id_envio');
    $rqcc1 = query("SELECT contenido FROM envios_cont_correo WHERE id_envio='$id_envio' ORDER BY id DESC LIMIT 1 ");
    if(num_rows($rqcc1)==0){
        echo "<b>CORREO NO REGISTRADO</b>";
    }else{
        $rqcc2 = fetch($rqcc1);
        echo stripslashes($rqcc2['contenido']);
    }
    
}else{
    echo "Acceso denegado!";
}


?>
