<?php
session_start();

include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_docente()) {
    echo "ACCESO DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_pregunta = post('id_pregunta');

$rqmc1 = query("SELECT * FROM cursos_onlinecourse_preguntas WHERE id='$id_pregunta' LIMIT 1 ");
$producto = fetch($rqmc1);
?>
<form action="" method="post">
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td>
                <i class="fa fa-tags"></i> &nbsp; Pregunta de evaluaci&oacute;n:
                <br/>
                <br/>
                <textarea name="pregunta" class="form-control" placeholder="Ingresa la pregunta..." required="" style="height: 70px;"><?php echo $producto['pregunta']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $selected_1 = '';
                $selected_2 = ' checked="checked" ';
                if ($producto['estado'] == '1') {
                    $selected_1 = ' checked="checked" ';
                    $selected_2 = '';
                }
                ?>
                <i class="fa fa-tags"></i> &nbsp; Estado de la pregunta:
                <br/>
                <div class="text-center">
                    <input type="radio" value="1" name="estado" <?php echo $selected_1; ?> style="width: 15px;height: 15px;"/> Habilitado 
                    &nbsp;&nbsp; | &nbsp;&nbsp; 
                    <input type="radio" value="0" name="estado" <?php echo $selected_2; ?> style="width: 15px;height: 15px;"/> No habilitado
                </div>
                <br>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="padding:20px;">
                <b>RESPUESTAS</b>
                <div>
                    <table style="width:100%;" class="table table-striped">
                        <?php
                        $cnt_aux = 1;
                        $rqdr1 = query("SELECT respuesta,sw_correcto FROM cursos_onlinecourse_respuestas WHERE id_pregunta='$id_pregunta' ORDER BY id ASC ");
                        while ($rqdr2 = fetch($rqdr1)) {
                            $htm_check = '';
                            if ($rqdr2['sw_correcto'] == '1') {
                                $htm_check = ' checked="checked" ';
                            }
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="respuesta-<?php echo $cnt_aux; ?>" class="form-control" placeholder="..." value="<?php echo $rqdr2['respuesta']; ?>"/>
                                </td>
                                <td>
                                    <input type="checkbox" name="check-respuesta-<?php echo $cnt_aux; ?>" value="1" class="form-control" <?php echo $htm_check; ?>/>
                                </td>
                            </tr>
                            <?php
                            $cnt_aux++;
                        }
                        for ($i = $cnt_aux; $i <= 7; $i++) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="respuesta-<?php echo $i; ?>" class="form-control" placeholder="..."/>
                                </td>
                                <td>
                                    <input type="checkbox" name="check-respuesta-<?php echo $i; ?>" value="1" class="form-control"/>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center;padding:20px;">
                    <input type="hidden" name="id_pregunta" value="<?php echo $id_pregunta; ?>"/>
                    <input type="submit" name="editar-pregunta-evaluacion" value="ACTUALIZAR PREGUNTA DE EVALUACI&Oacute;N" class="btn btn-success btn-lg btn-animate-demo"/>
                </div>
            </td>
        </tr>
    </table>
</form>

<script>
    function editar_pregunta(id_pregunta) {
        $("#AJAXCONTENT-editar_pregunta").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.acount-docente.preguntas-evaluacion.editar_pregunta.php',
            data: {id_pregunta: id_pregunta},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-editar_pregunta").html(data);
            }
        });
    }
</script>