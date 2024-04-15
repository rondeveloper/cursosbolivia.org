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


$rqcp1 = query("SELECT p.*,e.id_certificado_culminacion,(e.id)id_emision_certificado FROM cursos_participantes p INNER JOIN certificados_culminacion_emisiones e ON p.id=e.id_participante WHERE p.estado='1' AND p.id IN ($ids_participantes) AND e.id_certificado_culminacion='$id_certificado' ORDER BY p.id DESC ");
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
    echo "<br/><p>No se encontraron registros disponibles para el envio de certificados.</p><br/><br/>";
} else {
    ?>
    <div id="AJAXCONTENT-emite_certificados_multiple_p2">
        <form id="FORM-emite_certificados_multiple_p2" action='' method='post'>
            <table class="table table-striped table-bordered">
                <?php
                $ids_emisioncert = '0';
                while ($rqcp2 = fetch($rqcp1)) {
                    $ids_emisioncert .= ','.$rqcp2['id_emision_certificado'];
                    ?>
                    <tr>
                        <td>
                            <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                            <br>
                            <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                        </td>
                        <td id="box-enviar_cert_digital-<?php echo $rqcp2['id_emision_certificado']; ?>">
                            
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <p class='text-center'>
                <b>&iquest; Desea enviar estos certificados por correo ?</b>
            </p>

            <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>"/>
            <input type="hidden" name="id_certificado" value="<?php echo $id_certificado; ?>"/>

            <center id="panel-1">
                <b class="btn btn-success" onclick="envia_certificados_culminacion_multiple_individualmente();">ENVIAR CERTIFICADOS</b>
                &nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
            </center>
            <center id="panel-2" style="display: none;">
                <b class="btn btn-danger active" onclick="cancelar_envios();">CANCELAR ENVIOS</b>
            </center>
        </form>
    </div>
    <?php
}
?>

<!-- AJAX envia_certificados_culminacion_multiple_individualmente -->
<script>
    var sw_envios = true;
    function envia_certificados_culminacion_multiple_individualmente() {
        if(confirm('Desea enviar los certificados por correo ?')){
            $("#panel-1").css('display','none');
            $("#panel-2").css('display','block');
            var ids = '<?php echo $ids_emisioncert; ?>';
            var arr_ids = ids.split(",");
            enviaIndividual(arr_ids);
        }
    }
    function enviaIndividual(arr_ids) {
        if(arr_ids.length>0 && sw_envios){
            var item = arr_ids.pop();
            if(item!=='0'){
                $("#box-enviar_cert_digital-"+item).html('Procesando...');
                $.ajax({
                        url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_cert_digital.php',
                        data: {id_emision_certificado: item, keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
                        type: 'POST',
                        dataType: 'html',
                        success: function (data) {
                            $("#box-enviar_cert_digital-"+item).html(data);
                            enviaIndividual(arr_ids);
                        }
                });
            }else{
                enviaIndividual(arr_ids);
            }
        }else{
            alert('ENVIOS FINALIZADOS');
        }
    }
    function cancelar_envios(){
        sw_envios = false;
        alert('ENVIOS POSTERIORES CANCELADOS');
        $("#panel-2").css('display','none');
    }
</script>