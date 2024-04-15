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
$id_curso = post('id_curso');

$ids_participantes_dat = post('dat');
if ($ids_participantes_dat == '') {
    $ids_participantes_dat = '0';
}
/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
/* END limpia datos de id participante */

/* registros */
$rqcp1 = query("SELECT p.*,r.id_emision_certificado FROM cursos_participantes p INNER JOIN cursos_rel_partcertadicional r ON p.id=r.id_participante WHERE p.estado='1' AND p.id_curso='$id_curso' AND p.id IN ($ids_participantes) GROUP BY p.id ORDER BY p.apellidos ASC ");

/* certificados */
$rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id_curso='$id_curso' ORDER BY id ASC ");
?>
<table class="table table-striped table-bordered">
    <tr>
        <th colspan="2" class="text-center">Certificados</th>
    </tr>
    <?php
    while($rqdcrt2 = fetch($rqdcrt1)){
        ?>
        <tr>
            <td>
                <?php
                echo "<b>" . $rqdcrt2['codigo'] . "</b>";
                ?>
            </td>
            <td>
                <?php
                echo $rqdcrt2['texto_qr'];
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
if (num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron registros disponibles para la emision de certificados.</p><br/><br/>";
} else {
    ?>
    <div id="AJAXCONTENT-emite_certificados_multiple_p2">
        <form id="FORM-emite_certificados_multiple_p2" action='' method='post'>
            <table class="table table-striped table-bordered">
                <tr>
                    <th colspan="2" class="text-center">Participantes</th>
                </tr>
                <?php
                $ids_participantes = '0';
                while ($rqcp2 = fetch($rqcp1)) {
                    $id_participante = $rqcp2['id'];
                    $ids_participantes .= ','.$id_participante;
                    ?>
                    <tr>
                        <td>
                            <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['apellidos'] . ' ' . $rqcp2['nombres']); ?></span>
                            <br>
                            <span style='font-size: 10pt !important;padding-bottom: 8pt;color: #2566c7;font-weight: bold;'><?php echo $rqcp2['correo']; ?></span>
                            <br>
                            <?php
                            $rqcerts1 = query("SELECT cert.codigo FROM cursos_participantes p INNER JOIN cursos_rel_partcertadicional r ON p.id=r.id_participante INNER JOIN cursos_certificados cert ON cert.id=r.id_certificado WHERE p.id='$id_participante' ");
                            while($rqcerts2 = fetch($rqcerts1)){
                                echo '<c class="label label-warning">'.$rqcerts2['codigo'].'</c> &nbsp; ';
                            }
                            ?>
                        </td>
                        <td id="td-ajaxcont-<?php echo $id_participante; ?>">
                            <span style="color:#CCC;">[ estado ]</span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <p class='text-center'>
                <b>&iquest; Desea enviar todos los certificados por correo ?</b>
            </p>

            <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>"/>
            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>

            <center id="panel-1">
                <b class="btn btn-success" onclick="enviar_todos_los_certificados();">ENVIAR TODOS LOS CERTIFICADOS</b>
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

<!-- AJAX envia_certificados_multiple_p2 -->
<script>
    function envia_certificados_multiple_p2_PRE() {
        var form = $("#FORM-emite_certificados_multiple_p2").serialize();
        $("#AJAXCONTENT-emite_certificados_multiple_p2").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.envia_certificados_multiple_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple_p2").html(data);
            }
        });
    }
</script>

<!-- AJAX enviar_todos_los_certificados -->
<script>
    var sw_envios = true;
    function enviar_todos_los_certificados() {
        if(confirm('Desea enviar todos los certificados por correo ? (COMO CERTIFICADOS DIGITALES)')){
            $("#panel-1").css('display','none');
            $("#panel-2").css('display','block');
            var ids = '<?php echo $ids_participantes; ?>';
            var arr_ids = ids.split(",");
            enviaIndividual(arr_ids);
        }
    }
    function enviaIndividual(arr_ids) {
        if(arr_ids.length>0 && sw_envios){
            var item = arr_ids.pop();
            if(item!=='0'){
                $("#td-ajaxcont-"+item).html('Enviando...');
                $.ajax({
                        url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_emitidos_por_correo.php',
                        data: {id_participante: item, keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
                        type: 'POST',
                        dataType: 'html',
                        success: function (data) {
                            $("#td-ajaxcont-"+item).html(data);
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