<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if(isset_administrador()){
    
    $id_envio = post('id_envio');
    $rqcc1 = query("SELECT contenido FROM envios_cont_correo WHERE id_envio='$id_envio' ORDER BY id DESC LIMIT 1 ");
    if(mysql_num_rows($rqcc1)==0){
        echo "<b>CORREO NO REGISTRADO</b>";
    }else{
        $rqcc2 = mysql_fetch_array($rqcc1);
        echo stripslashes($rqcc2['contenido']);
    }
    
}else{
    echo "Acceso denegado!";
}


?>
