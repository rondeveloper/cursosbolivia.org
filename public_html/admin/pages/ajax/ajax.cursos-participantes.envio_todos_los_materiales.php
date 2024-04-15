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


$rqcp1 = query("SELECT p.* FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id WHERE p.estado='1' AND r.sw_pago_enviado='1' AND p.id_curso='$id_curso' AND p.id IN ($ids_participantes) GROUP BY p.id ORDER BY p.apellidos ASC ");

/* certificados */
$rqdcrt1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ORDER BY id ASC ");
?>
<table class="table table-striped table-bordered">
    <tr>
        <th colspan="2" class="text-center">Materiales</th>
    </tr>
    <?php
    while($rqdcrt2 = fetch($rqdcrt1)){
        ?>
        <tr>
            <td>
                <?php
                echo "<b>" . $rqdcrt2['nombre'] . "</b>";
                ?> 
            </td>
            <td>
                <?php
                echo $rqdcrt2['nombre_fisico'];
                ?> 
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<?php
if (num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron materiales.</p><br/><br/>";
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
                    $rqvdenv1 = query("SELECT id FROM rel_partmaterialcur WHERE id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                    $cnt_verif = fetch($rqvdenv1);
                    ?>
                    <tr>
                        <td>
                            <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['apellidos'] . ' ' . $rqcp2['nombres']); ?></span>
                            <br>
                            <span style='font-size: 10pt !important;padding-bottom: 8pt;color: #2566c7;font-weight: bold;'><?php echo $rqcp2['correo']; ?></span>
                        </td>
                        <td id="td-ajaxcont-<?php echo $id_participante; ?>">
                            <?php
                            if($cnt_verif==0){
                                $ids_participantes .= ','.$id_participante;
                                ?>
                                <span style="color:#CCC;">[ estado ]</span>
                                <br>
                                <b class="btn btn-primary btn-xs" onclick="envia_materiales('<?php echo $id_participante; ?>');">Enviar</b>
                                <?php
                            }else{
                                echo '<br><b class="label label-success">ENVIADO ANTERIORMENTE</b>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <p class='text-center'>
                <b>&iquest; Desea enviar todos los materiales por correo ?</b>
            </p>

            <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>"/>
            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>

            <center id="panel-1">
                <b class="btn btn-success" onclick="enviar_todos_los_materiales();">ENVIAR TODOS LOS MATERIALES</b>
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

<!-- AJAX enviar_todos_los_certificados -->
<script>
    var sw_envios = true;
    function enviar_todos_los_materiales() {
        if(confirm('Desea enviar todos los materiales por correo ?')){
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
                        url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_material_curso.php',
                        data: {id_participante: item, id_curso: '<?php echo $id_curso; ?>',keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
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
    function envia_materiales(id_participante){
        $("#td-ajaxcont-"+id_participante).html('Enviando...');
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_material_curso.php',
            data: {id_participante: id_participante, id_curso: '<?php echo $id_curso; ?>',keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#td-ajaxcont-"+id_participante).html(data);
            }
        });
    }
</script>