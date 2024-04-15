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

$duracion1 = (int) ($presentacion['duracion_audio'] / 60);
$duracion2 = ($presentacion['duracion_audio'] % 60);

?>
<form id="formagregaleccion" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td colspan="3"><img src="contenido/imagenes/presentaciones/<?php echo $presentacion['imagen']; ?>" style="height:150px;"/></td>
        </tr>
        <tr>
            <td>Imagen:</td>
            <td colspan="2"><input type="file" name="imagen" class="form-control"/></td>
        </tr>
        <tr>
            <td>Inicio en audio:</td>
            <td><b>Minutos</b><input type="number" name="duracion1" class="form-control" required="" value="<?php echo $duracion1; ?>"/></td>
            <td><b>Segundos</b><input type="number" name="duracion2" class="form-control" required="" value="<?php echo $duracion2; ?>"/></td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="hidden" name="imagen_actual" value="<?php echo $presentacion['imagen']; ?>"/>
                <input type="hidden" name="id_presentacion" value="<?php echo $id_presentacion; ?>"/>
                <input type="hidden" name="id_leccion" value="<?php echo $presentacion['id_leccion']; ?>"/>
                <input type="submit" value="ACTUALIZAR" class="btn btn-success btn-block active"/>
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-online-administrar.presentacion_leccion_editar_p2.php',
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