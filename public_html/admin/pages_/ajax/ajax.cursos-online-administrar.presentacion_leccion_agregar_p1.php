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
<form id="formagregaleccion" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Imagen:</td>
            <td colspan="2"><input type="file" name="imagen" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td>Duraci&oacute;n audio:</td>
            <td><b>Minutos</b><input type="number" name="duracion1" value="0" class="form-control" required=""/></td>
            <td><b>Segundos</b><input type="number" name="duracion2" value="0" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="hidden" name="id_leccion" value="<?php echo $id_leccion; ?>"/>
                <input type="submit" value="AGREGAR" class="btn btn-success btn-block active"/>
            </td>
        </tr>
    </table>
</form>

<script>
    $('#formagregaleccion').on('submit', function(e) {
        // evito que propague el submit
        e.preventDefault();

        $("#ajaxbox-presentacion_leccion_p1").html("");
        $("#ajaxloading-presentacion_leccion_p1").html("Cargando...");

        // agrego la data del form a formData
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-online-administrar.presentacion_leccion_agregar_p2.php',
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