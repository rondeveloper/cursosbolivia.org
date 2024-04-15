<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_leccion = post('id_leccion');
?>
<form action="" id="formagregaaudio" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Audio:</td>
            <td><input type="file" name="audio" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_leccion" value="<?php echo $id_leccion; ?>"/>
                <input type="submit" value="AGREGAR" name="agregar-audio-presentacion" class="btn btn-success btn-block active"/>
            </td>
        </tr>
    </table>
</form>

<script>
    
    $('#formagregaaudio').on('submit', function(e) {
        e.preventDefault();

        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");

        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-online-administrar.presentacion_audio_agregar_p2.php',
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