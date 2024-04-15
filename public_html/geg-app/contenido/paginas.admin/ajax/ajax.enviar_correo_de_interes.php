<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    
    $cuces_reenvio = '';
    if(isset_get('cuces_reenvio')){
        $cuces_reenvio = '&cuces_reenvio='.get('cuces_reenvio');
    }
    
    $id_empresa = post('dat');
    
    $art = file_get_contents('https://www.infosicoes.com/contenido/paginas.admin/cron/cron.enviar_correos_de_interes.php?emp='.$id_empresa.$cuces_reenvio);
    
    echo '<img src="contenido/imagenes/images/bien.png" style="width:20px;">  Correo Enviado!';
    
}else{
    echo "Acceso denegado!";
}

?>
