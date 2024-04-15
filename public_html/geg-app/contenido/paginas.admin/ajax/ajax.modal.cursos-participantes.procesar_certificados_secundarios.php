<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $id_participante = post('id_participante');
    $id_curso = post('id_curso');

    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdp2 = mysql_fetch_array($rqdp1);
    ?>

    <h4><?php echo $rqdp2['nombres'] . " " . $rqdp2['apellidos']; ?></h4>

    <p>
        Selecciona los certificados a emitir para el participante: 
        <b onclick="$('.checkboxg02').attr('checked', 'checked');" class="btn btn-xs btn-info active pull-right">marcar todos</b>
    </p>


    <form action="" method="POST" id="formajax1">

        <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>"/>
        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>

        <table class="table table-striped">
            <?php
            $sw_join = false;
            $sw_join_2 = true;
            $rqmc1 = query("SELECT * FROM cursos_modelos_certificados WHERE id_curso='$id_curso' AND id NOT IN (select id_modelo_certificado from rel_participante_modelocertificado where id_participante='$id_participante' and id_curso='$id_curso' ) ORDER BY id DESC limit 25");
            if (mysql_num_rows($rqmc1) == 0) {
                echo "<tr><td colspan='3'><b>Este curso no tiene certificados secundarios asignados!</b><p>Puede agregar certificados secundarios desde el panel de edici&oacute;n del curso.</p></td></tr>";
                $sw_join_2 = false;
            }
            while ($rqmc2 = mysql_fetch_array($rqmc1)) {
                $sw_join = true;
                ?>
                <tr>
                    <td><input type="checkbox" name="curso-modelo-<?php echo $rqmc2['id']; ?>" value="1" id="cm-<?php echo $rqmc2['id']; ?>" class="checkboxg02"/></td>
                    <td><label for="cm-<?php echo $rqmc2['id']; ?>"><?php echo $rqmc2['descripcion']; ?></label></td>
                    <td><?php echo $rqmc2['codigo']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

    </form>


    <?php
    if ($sw_join) {
        ?>
        <div class='text-center'>
            <br/>
            <br/>
            <button class="btn btn-success" onclick="procesar_certificados_secundarios_p2();">EMITIR CERTIFICADOS</button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
        </div>
        <?php
    } elseif ($sw_join_2) {
        echo "<h5>Ya se emitieron todos los certificados correspondientes.</h5>";
    }
    ?>

    <?php
    $rqcea1 = query("SELECT certificado_id,id_modelo_certificado FROM cursos_emisiones_certificados WHERE id_participante='$id_participante' AND id_curso='$id_curso' AND id_modelo_certificado<>'0' ORDER BY id DESC limit 50 ");
    if (mysql_num_rows($rqcea1) > 0) {
        ?>
        <hr/>

        <h4>Certificados emitidos anteriormente</h4>
        <table class="table table-striped">
            <?php
            $ids_modelos_certificados_env = "0";
            while ($rqcea2 = mysql_fetch_array($rqcea1)) {
                $id_modelo_certificado = $rqcea2['id_modelo_certificado'];
                $certificado_id = $rqcea2['certificado_id'];

                $rqmc1 = query("SELECT codigo,cont_titulo_curso FROM cursos_modelos_certificados WHERE id='$id_modelo_certificado' ORDER BY id DESC limit 1 ");
                $rqmc2 = mysql_fetch_array($rqmc1);

                $ids_modelos_certificados_env .= ",$id_modelo_certificado";
                ?>
                <tr>
                    <td><?php echo $rqmc2['codigo']; ?></td>
                    <td><?php echo $rqmc2['cont_titulo_curso']; ?></td>
                    <td><button onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-4.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR CERTIFICADO</button></td>
                </tr>
                <?php
            }
            ?>
        </table>


        <br/>

        <button class="btn btn-success" onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-4-masivo.php?id_participantes=<?php echo $id_participante; ?>&id_modelo_certificado=<?php echo $ids_modelos_certificados_env; ?>', 'popup', 'width=700,height=500');">IMPRIMIR EMITIDOS</button>

        <?php
    }
    ?>

    <?php
} else {
    echo "Denegado!";
}
?>
