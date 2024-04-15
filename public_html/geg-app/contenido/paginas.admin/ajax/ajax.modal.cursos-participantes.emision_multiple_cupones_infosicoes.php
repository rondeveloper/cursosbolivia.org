<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    /* id curso */
    $id_curso = (int) post('id_curso');

    /* ids participantes */
    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }

    /* verficacion de cupon */
    $rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
    if (mysql_num_rows($rqdcd1) == 0) {
        echo "<br/><b>No se habilito cupones para este curso!</b><br/>";
        exit;
    }
    /* cupon */
    $rqdcd2 = mysql_fetch_array($rqdcd1);
    $id_cupon = $rqdcd2['id'];

    /* limpia datos de id participante */
    $ar_exp_aux = explode(",", $ids_participantes);
    $ids_participantes = '0';
    foreach ($ar_exp_aux as $value) {
        $ids_participantes .= "," . (int) $value;
    }

    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) ORDER BY id DESC ");
    if (mysql_num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron registros disponibles para la emision de cupones.</p><br/><br/>";
    } else {
        ?>
        <div class="row">
            <ul>
                <?php
                while ($rqcp2 = mysql_fetch_array($rqcp1)) {
                    /* verificacion de emision anterior */
                    $rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_participante='" . $rqcp2['id'] . "' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqve1) > 0) {
                        continue;
                    }
                    ?>
                    <li style='font-size: 17pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <br/>
        <p class='text-center'><b>&iquest; Desea emitir los cupones ?</b></p>

        <button class="btn btn-success" onclick="emision_cupones_infosicoes_p2();">EMITIR CUPONES</button>
        &nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>

        <?php
    }
    ?>
    <hr/>
    <h5 class="text-center">
        Cupones emitidos anteriormente:
    </h5>
    <div class="row">
        <table class="table table-bordered table-striped">
            <?php
            $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' ORDER BY id DESC ");
            while ($rqcp2 = mysql_fetch_array($rqcp1)) {
                $id_participante = $rqcp2['id'];
                /* verificacion de emision anterior */
                $rqve1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                if (mysql_num_rows($rqve1) > 0) {
                    $rqve2 = mysql_fetch_array($rqve1);
                    ?>
                    <tr>
                        <td>
                            <span style='font-size: 12pt !important;'>
                                <?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo substr($rqve2['codigo'], 0, (strlen($rqve2['codigo']) - 3)) . '***'; ?>
                        </td>
                        <td>
                            <button onclick="window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_participante=<?php echo $id_participante; ?>', 'popup', 'width=700,height=500');" class="btn btn-info btn-xs">IMPRIMIR</button>
                        </td>
                        <td>
                            <?php if ($rqcp2['correo'] !== '') { ?>
                                <?php echo trim($rqcp2['correo']); ?>
                                <br/>
                                <span id="ajaxcontent-enviar_cupon-<?php echo $rqve2['id']; ?>">
                                    <b class="btn btn-default btn-xs" onclick="enviar_cupon_infosicoes('<?php echo $rqve2['id']; ?>');">ENVIAR</b>
                                </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
    <br/>
    <br/>
    <button onclick="window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_curso=<?php echo $id_curso; ?>', 'popup', 'width=700,height=500');" class="btn btn-info active btn-block">IMPRIMIR TODOS LOS CUP&Oacute;NES</button>
    <hr/>
    
    <script>
        function enviar_cupon_infosicoes(id_emision_cupon_infosicoes){
            $("#ajaxcontent-enviar_cupon-" + id_emision_cupon_infosicoes).html('Enviando...');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_cupon_infosicoes.php',
                data: {id_emision_cupon_infosicoes: id_emision_cupon_infosicoes},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxcontent-enviar_cupon-" + id_emision_cupon_infosicoes).html(data);
                }
            });
        }
    </script>
    <?php
} else {
    echo "Denegado!";
}

