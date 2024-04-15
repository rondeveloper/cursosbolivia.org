<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_emision_certificado = post('id_emision_certificado');

$rqdc1 = query("SELECT *,(select nombre from administradores where id=c.id_administrador_emisor)administrador FROM cursos_emisiones_certificados c WHERE id='$id_emision_certificado' ");
$rqdc2 = mysql_fetch_array($rqdc1);
?>
<table class="table table-hover table-striped table-bordered">
    <tr>
        <td>ID de certificado</td>
        <td><?php echo $rqdc2['certificado_id']; ?></td>
    </tr>
    <tr>
        <td>Receptor de certificado</td>
        <td><?php echo $rqdc2['receptor_de_certificado']; ?></td>
    </tr>
    <tr>
        <td>Fecha de emision</td>
        <td><?php echo $rqdc2['fecha_emision']; ?></td>
    </tr>
    <tr>
        <td>Emitido por</td>
        <td><?php echo $rqdc2['administrador']; ?></td>
    </tr>
</table>
<hr/>
<?php
$id_certificado = $rqdc2['id_certificado'];

/* verif */
$rqv1 = query("SELECT COUNT(*) AS total FROM cursos_emisiones_certificados WHERE id_certificado='$id_certificado' ");
$rqv2 = mysql_fetch_array($rqv1);
if ($rqv2['total'] > 1) {
    echo "<b>ESTE CERTIFICADO NO PUEDE SER EDITADO INDIVIDUALMENTE.</b>";
    exit;
}

$rqdcr1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqdcr2 = mysql_fetch_array($rqdcr1);

$cont_titulo = $rqdcr2['cont_titulo'];
$cont_uno = $rqdcr2['cont_uno'];
$cont_dos = $rqdcr2['cont_dos'];
$cont_tres = $rqdcr2['cont_tres'];
$texto_qr = $rqdcr2['texto_qr'];
$fecha_qr = $rqdcr2['fecha_qr'];
?>

<div class="text-center">
    <form id="FORM-edita_certificado_individual_p2" action="" method="POST">
        <table class="table table-hover table-striped table-bordered">
            <tr>
                <td>T&iacute;tulo</td>
                <td><input type="text" class="form-control" name="cont_titulo" value='<?php echo $cont_titulo; ?>'/></td>
            </tr>
            <tr>
                <td>Texto uno</td>
                <td><input type="text" class="form-control" name="cont_uno" value='<?php echo $cont_uno; ?>'/></td>
            </tr>
            <tr>
                <td>Texto dos</td>
                <td><input type="text" class="form-control" name="cont_dos" value='<?php echo $cont_dos; ?>'/></td>
            </tr>
            <tr>
                <td>Texto tres</td>
                <td><input type="text" class="form-control" name="cont_tres" value='<?php echo $cont_tres; ?>'/></td>
            </tr>
            <tr>
                <td>Texto qr</td>
                <td><input type="text" class="form-control" name="texto_qr" value='<?php echo $texto_qr; ?>'/></td>
            </tr>
            <tr>
                <td>Fecha qr</td>
                <td><input type="date" class="form-control" name="fecha_qr" value='<?php echo $fecha_qr; ?>'/></td>
            </tr>
        </table>
        <div id="AJAXCONTENT-edita_certificado_individual_p2">
            <br/>
            <input type="hidden" name="id_certificado" value="<?php echo $id_certificado; ?>"/>
            <b class="btn btn-success" onclick="edita_certificado_individual_p2();">ACTUALIZAR CERTIFICADO</b>
            <br/>
        </div>
    </form>
</div>

<hr/>

<script>
    function edita_certificado_individual_p2() {
        var form = $("#FORM-edita_certificado_individual_p2").serialize();
        $("#AJAXCONTENT-edita_certificado_individual_p2").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_certificado_individual_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_certificado_individual_p2").html(data);
            }
        });
    }
</script>
