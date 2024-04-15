<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $id_modelo_certificado = post('id_modelo_certificado');
    $rqmc1 = query("SELECT descripcion,codigo FROM cursos_modelos_certificados WHERE id='$id_modelo_certificado' LIMIT 1 ");
    $rqmc2 = mysql_fetch_array($rqmc1);

    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN (select id_participante from rel_participante_modelocertificado where id_modelo_certificado='$id_modelo_certificado' ) ORDER BY id DESC ");
    ?>

    <h4><?php echo $rqmc2['descripcion']; ?></h4>
    <p>Modelo de certificado: <b><?php echo $rqmc2['codigo']; ?></b></p>
    <hr/>

    <div class="row">
        <ul>
            <?php
            while ($rqcp2 = mysql_fetch_array($rqcp1)) {
                ?>
                <li style='font-size: 17pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <br/>
    <p class='text-center'><b>&iquest; Desea emitir estos certificados ?</b></p>

    <button class="btn btn-success" onclick="emision_certificados_a_eleccion_p3();">EMITIR CERTIFICADOS</button>
    &nbsp;&nbsp;&nbsp;
    <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
    
    <input type="hidden" id="id-modelo-certificado" value="<?php echo $id_modelo_certificado; ?>"/>

    <?php
} else {
    echo "Denegado!";
}
?>
