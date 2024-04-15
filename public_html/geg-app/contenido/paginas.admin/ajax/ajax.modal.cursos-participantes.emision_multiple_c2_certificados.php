<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_2='0' ORDER BY id DESC ");
    ?>
    <div class="row">
        <ul>
            <?php
            while ($rqcp2 = mysql_fetch_array($rqcp1)) {
                ?>
                <li style='font-size: 17pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'].' '.$rqcp2['nombres'].' '.$rqcp2['apellidos']); ?></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <br/>
    <p class='text-center'><b>&iquest; Desea emitir estos certificados ?</b></p>
    
    <button class="btn btn-success" onclick="emision_multiple_c2_certificados_p2();">EMITIR CERTIFICADOS</button>
    &nbsp;&nbsp;&nbsp;
    <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>

    <?php
} else {
    echo "Denegado!";
}
?>
