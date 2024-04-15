<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_presentacion = post('id_presentacion');

/* presentacion */
$rqdtp1 = query("SELECT * FROM cursos_onlinecourse_presentaciones WHERE id='$id_presentacion' ORDER BY id ASC ");
$presentacion = fetch($rqdtp1);
?>

<b>&iquest; DESEA ELIMINAR ESTA PRESENTACI&Oacute;N ?</b>

<form id="formeliminapresentacion" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Imagen:</td>
            <td><img src="<?php echo $dominio_www; ?>contenido/imagenes/presentaciones/<?php echo $presentacion['imagen']; ?>" style="height:150px;"/></td>
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
            url: 'pages/ajax/ajax.cursos-online-administrar.presentacion_leccion_eliminar_p2.php',
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