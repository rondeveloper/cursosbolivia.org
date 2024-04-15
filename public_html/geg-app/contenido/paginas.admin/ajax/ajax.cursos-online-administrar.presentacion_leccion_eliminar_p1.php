<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_presentacion = post('id_presentacion');

/* presentacion */
$rqdtp1 = query("SELECT * FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ORDER BY id ASC ");
$presentacion = mysql_fetch_array($rqdtp1);
?>

<b>&iquest; DESEA ELIMINAR ESTA PRESENTACI&Oacute;N ?</b>

<form id="formeliminapresentacion" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Imagen:</td>
            <td><img src="contenido/imagenes/presentaciones/<?php echo $presentacion['imagen']; ?>" style="height:150px;"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_presentacion" value="<?php echo $id_presentacion; ?>"/>
                <input type="submit" value="ELIMINAR" class="btn btn-danger btn-block active"/>
                <br/>
                <button class="btn btn-default btn-block" onclick="presentacion_leccion_p1('<?php echo $presentacion['id_leccion']; ?>');">
                    CANCELAR
                </button>
            </td>
        </tr>
    </table>
</form>

<script>
    $('#formeliminapresentacion').on('submit', function(e) {
        // evito que propague el submit
        e.preventDefault();

        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");

        // agrego la data del form a formData
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_eliminar_p2.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajaxloading-presentacion_leccion_p1").html("");
                $("#ajaxbox-presentacion_leccion_p1").html(data);
            }
        });
    });

</script>