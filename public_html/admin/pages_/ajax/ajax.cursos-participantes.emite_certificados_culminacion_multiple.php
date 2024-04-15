<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
/* END limpia datos de id participante */

$rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN(select id_participante from certificados_culminacion_emisiones where id_certificado_culminacion='$id_certificado') ORDER BY id DESC ");
       
?>
<table class="table table-striped table-bordered">
    <tr>
        <td class="visible-lg">
            CERTIFICADO CULMINACION IPELC
        </td>
    </tr>
</table>
<?php
if (num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron registros disponibles para la emision de certificados.</p><br/><br/>";
} else {
    ?>
    <div id="AJAXCONTENT-emite_certificados_culminacion_multiple_p2">
        <form id="FORM-emite_certificados_culminacion_multiple_p2" action='' method='post'>
            <table class="table table-striped table-bordered">
                <?php
                while ($rqcp2 = fetch($rqcp1)) {
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

            <div class="text-center">
            <button class="btn btn-success" onclick="emite_certificados_culminacion_multiple_p2();">EMITIR CERTIFICADOS</button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
            </div>
        </form>
    </div>
    <?php
}
?>
<!-- AJAX emite_certificados_multiple -->
<script>
    function emite_certificados_culminacion_multiple_p2() {
        var form = $("#FORM-emite_certificados_culminacion_multiple_p2").serialize();
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificados_culminacion_multiple_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
