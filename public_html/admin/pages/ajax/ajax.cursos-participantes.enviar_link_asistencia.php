<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$rqcp1 = query("SELECT p.* FROM cursos_participantes p
WHERE p.estado='1' 
AND p.sw_pago='1'
AND p.id_curso='$id_curso' 
ORDER BY p.id DESC ");

?>
<div>
    <table class="table table-striped table-bordered">
        <?php
        $ids_parts = '0';
        while ($rqcp2 = fetch($rqcp1)) {
            $id_participante = $rqcp2['id'];
            $rqv1 = query("SELECT id FROM cursos_asistencia WHERE id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
            if (num_rows($rqv1) == 0) {
                $ids_parts .= ',' . $rqcp2['id'];
        ?>
                <tr>
                    <td>
                        <input type="checkbox" id="chekbox-idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;" />
                    </td>
                    <td>
                        <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                        <br>
                        <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                    </td>
                    <td id="box-enviar_correo-<?php echo $rqcp2['id']; ?>">

                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </table>

    <?php
    if ($ids_parts == '0') {
        echo "<br/><p>No se encontraron participantes sin asistencia.</p><br/><br/>";
    } else {

        $ids_participantes = $ids_participantes ?? '';
    ?>
    <br />
        <p class='text-center'>
            <b>&iquest; Desea enviar el link de asistencia por correo ?</b>
        </p>
        <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>" />
        <center id="panel-1">
            <b class="btn btn-success" onclick="envia_correos_individualmente();">ENVIAR LINKS</b>
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
        </center>
        <center id="panel-2" style="display: none;">
            <b class="btn btn-danger active" onclick="cancelar_envios();">CANCELAR ENVIOS</b>
        </center>
    <?php
    }
    ?>
</div>
<!-- AJAX envia_correos_individualmente -->
<script>
    var sw_envios = true;
   
    function envia_correos_individualmente() {
        if (confirm('Desea enviar los links de asistencia por correo ?')) {
            $("#panel-1").css('display', 'none');
            $("#panel-2").css('display', 'block');
            var ids = '<?php echo $ids_parts; ?>';
            var arr_ids = ids.split(",");
            enviaIndividual(arr_ids);
        }
    }

    function enviaIndividual(arr_ids) {
        if(arr_ids.length > 0 && sw_envios) {
            var item = arr_ids.pop();
            if (item !== '0') {
                if (document.getElementById('chekbox-idpart-' + item).checked) {
                    $("#box-enviar_correo-" + item).html('Procesando...');
                    $.ajax({
                        url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_link_asistencia.php',
                        data: {
                            id_participante: item,
                            keyaccess: '5rw4t6gd1',
                            id_administrador: '<?php echo administrador('id'); ?>',
                            sw_use_simulador: '<?php echo $sw_use_simulador; ?>'
                        },
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            $("#box-enviar_correo-" + item).html(data);
                            enviaIndividual(arr_ids);
                        }
                    });
                } else {
                    enviaIndividual(arr_ids);
                }
            } else {
                enviaIndividual(arr_ids);
            }
        } else {
            alert('ENVIOS FINALIZADOS');
        }
    }
    function cancelar_envios(){
        sw_envios = false;
        alert('ENVIOS POSTERIORES CANCELADOS');
        $("#panel-2").css('display', 'none');
    }
</script>

