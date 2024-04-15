<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* datos recibidos */
$ids_participantes_dat = post('dat');
if ($ids_participantes_dat == '') {
    $ids_participantes_dat = '0';
}
$id_certificado = post('id_certificado');
$id_curso = post('id_curso');
$modo = post('modo');

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
/* END limpia datos de id participante */

switch ($modo) {
    case '0':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN(select id_participante from cursos_rel_partcertadicional where id_certificado='$id_certificado') ORDER BY id DESC ");
        break;
    case '1':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado='0' ORDER BY id DESC ");
        break;
    case '2':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_2='0' ORDER BY id DESC ");
        break;
    case '3':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_3='0' ORDER BY id DESC ");
        break;
    default:
        echo "ERROR";
        exit;
        break;
}

/* certificado */
$rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' LIMIT 1 ");
$rqdcrt2 = mysql_fetch_array($rqdcrt1);
?>
<table class="table table-striped table-bordered">
    <tr>
        <td class="visible-lg">
            <?php
            echo "<b>" . $rqdcrt2['codigo'] . "</b>";
            ?> 
        </td>
        <td class="visible-lg">
            <?php
            echo $rqdcrt2['texto_qr'];
            echo "<br/>";
            echo $rqdcrt2['fecha_qr'];
            ?> 
        </td>
        <td class="visible-lg">
            <?php
            echo $rqdcrt2['cont_dos'];
            echo "<br/>";
            echo $rqdcrt2['cont_tres'];
            ?> 
        </td>
    </tr>
</table>
<?php
if (mysql_num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron registros disponibles para la emision de certificados.</p><br/><br/>";
} else {
    ?>
    <div id="AJAXCONTENT-emite_certificados_multiple_p2">
        <form id="FORM-emite_certificados_multiple_p2" action='' method='post'>
            <table class="table table-striped table-bordered">
                <?php
                while ($rqcp2 = mysql_fetch_array($rqcp1)) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;"/>
                        </td>
                        <td>
                            <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <p class='text-center'>
                <b>&iquest; Desea emitir estos certificados ?</b>
            </p>

            <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>"/>
            <input type="hidden" name="id_certificado" value="<?php echo $id_certificado; ?>"/>
            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
            <input type="hidden" name="modo" value="<?php echo $modo; ?>"/>

            <button class="btn btn-success" onclick="emite_certificados_multiple_p2();">EMITIR CERTIFICADOS</button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
        </form>
    </div>
    <?php
}
?>
<!-- AJAX emite_certificados_multiple -->
<script>
    function emite_certificados_multiple_p2() {
        var form = $("#FORM-emite_certificados_multiple_p2").serialize();
        $("#AJAXCONTENT-emite_certificados_multiple_p2").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificados_multiple_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>
