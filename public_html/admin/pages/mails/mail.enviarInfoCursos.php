<?php

use phpseclib3\Math\BigInteger\Engines\PHP;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* datos GET para generar el Email Content */

/* procesamiento de datos */
$ids_cursos_a_mostrar = '3406,3412,3411,3410';

$rqc1  = query("SELECT * FROM cursos WHERE id IN ($ids_cursos_a_mostrar) ")
?>

<div style='font-family:arial;line-height: 2;color:#333;'>

    <?php
    while($rqc2 = fetch($rqc1)){
        echo $rqc2['titulo'].'<br>';
    }
    ?>

</div>
