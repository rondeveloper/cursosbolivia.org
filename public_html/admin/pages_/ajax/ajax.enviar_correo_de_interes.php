<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    
    $cuces_reenvio = '';
    if(isset_get('cuces_reenvio')){
        $cuces_reenvio = '&cuces_reenvio='.get('cuces_reenvio');
    }
    
    $id_empresa = post('dat');
    
    $art = file_get_contents('https://www.infosicoes.com/contenido/paginas.admin/cron/cron.enviar_correos_de_interes.php?emp='.$id_empresa.$cuces_reenvio);
    
    echo '<img src="'.$dominio_www.'contenido/imagenes/images/bien.png" style="width:20px;">  Correo Enviado!';
    
}else{
    echo "Acceso denegado!";
}

?>
