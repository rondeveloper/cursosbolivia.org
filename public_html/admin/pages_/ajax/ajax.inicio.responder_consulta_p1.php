<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

$id_consulta = post('id_consulta');

$rqdc1 = query("SELECT * FROM consultas WHERE id='$id_consulta' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$sw_leido = $rqdc2['sw_leido'];
$contenido = $rqdc2['contenido'];
$nombre = $rqdc2['nombre'];
$tel_cel = $rqdc2['tel_cel'];
?>

<table class="table table-bordered table-striped">
    <tr>
        <td colspan="2">
            <h4><b><?php echo $contenido; ?></b></h4>
        </td>
    </tr>
    <!-- tbody CONTENT AJAX :: RESPONDE CONSULTA P2 -->
    <tbody id="ajaxbox-responder_consulta_p2">
        <?php
        $rqcr1 = query("SELECT *,(select nombre from administradores where id=cr.id_administrador)administrador FROM consultas_respuestas cr WHERE id_consulta='$id_consulta' ");
        while ($rqcr2 = fetch($rqcr1)) {
            ?>
            <tr>
                <td>
                    <b><?php echo $rqcr2['administrador']; ?>:</b> 
                    <?php echo $rqcr2['respuesta']; ?>
                </td>
                <td class="text-right">
                    <?php echo date("d/M H:i",strtotime($rqcr2['fecha_registro'])); ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tr>
        <td colspan="2">
            <textarea class="form-control" rows="3" id="respuesta-consulta" placeholder="Responder..."></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b class="btn btn-success btn-block" onclick="responder_consulta_p2('<?php echo $id_consulta; ?>');">RESPONDER</b>
        </td>
    </tr>
</table>
<div id="ajaxloading-responder_consulta_p2"></div>



