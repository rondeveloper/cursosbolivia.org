<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* datos GET para generar el Email Content */
$id_tienda_registro = get('id_tienda_registro');

$hash = HashUtil::hashIdRegistroTienda($id_tienda_registro);
$url_de_pago = $dominio.'registro-cursos-tienda-completado/'.$id_tienda_registro.'/'.$hash.'.html';
?>

<div style='font-family:arial;line-height: 2;color:#333;'>

    <p>
        Estimad@
        <br>
        Se le hace el env&iacute;o de URL donde podr&aacute; subir el comprobante de pago, 
        para as&iacute; poder recibir los accesos a sus cursos adquiridos.
        <br>
        <br>
        <b>URL DE PAGO:</b>
        <br>
        <a href="<?= $url_de_pago ?>" target="_blank"><?= $url_de_pago ?></a>
        <br>
        <br>
        Saludos cordiales
        <br>
        <br>
    </p>

</div>
