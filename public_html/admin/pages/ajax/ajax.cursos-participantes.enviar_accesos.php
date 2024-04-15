<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$rqcp1 = query("SELECT p.* FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.estado='1' AND p.sw_pago='1' AND id_curso='$id_curso' ORDER BY p.id DESC ");

?>
<?php
if (num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron participantes con cuentas de usuario.</p><br/><br/>";
} else {
    ?>
    <div id="AJAXCONTENT-emite_certificados_multiple_p2">
        <form id="FORM-emite_certificados_multiple_p2" action='' method='post'>
            <table class="table table-striped table-bordered">
                <?php
                $ids_parts = '0';
                while ($rqcp2 = fetch($rqcp1)) {
                    $ids_parts .= ','.$rqcp2['id'];
                    ?>
                    <tr>
                       <td>
                            <input type="checkbox" id="chekbox-idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;"/>
                        </td>
                        <td>
                            <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                            <br>
                            <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                        </td>
                        <td id="box-enviar_acceso-<?php echo $rqcp2['id']; ?>">
                            
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <p class='text-center'>
                <b>&iquest; Desea enviar los accesos por correo ?</b>
            </p>

            <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>"/>
            <input type="hidden" name="id_certificado" value="<?php echo $id_certificado; ?>"/>

            <center id="panel-1">
                <b class="btn btn-success" onclick="envia_accesos_individualmente();">ENVIAR ACCESOS</b>
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

<!-- AJAX envia_accesos_individualmente -->
<script>
    var sw_envios = true;
    function envia_accesos_individualmente() {
        if(confirm('Desea enviar los certificados por correo ?')){
            $("#panel-1").css('display','none');
            $("#panel-2").css('display','block');
            var ids = '<?php echo $ids_parts; ?>';
            var arr_ids = ids.split(",");
            enviaIndividual(arr_ids);
        }
    }
    function enviaIndividual(arr_ids) {
        if(arr_ids.length>0 && sw_envios){
            var item = arr_ids.pop();
            if(item!=='0'){
                if (document.getElementById('chekbox-idpart-'+item).checked) {
                    $("#box-enviar_acceso-"+item).html('Procesando...');
                    $.ajax({
                            url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_acceso.php',
                            data: {id_participante: item, keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
                            type: 'POST',
                            dataType: 'html',
                            success: function (data) {
                                $("#box-enviar_acceso-"+item).html(data);
                                enviaIndividual(arr_ids);
                            }
                    });
                }else{
                    enviaIndividual(arr_ids);
                }
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