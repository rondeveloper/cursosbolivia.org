<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$ref = post('ref');
$id_ref = post('id_ref');

$mensaje = '';

/* editar */
if(isset_post('editar-correo')){
    $correo = post('correo');
    if ($ref == '1') {
        query("UPDATE cursos_participantes SET correo='$correo',sw_notif='1' WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    } else {
        query("UPDATE cursos_usuarios SET email='$correo',sw_notif='1' WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    }
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> el correo fue modificado correctamente.
  </div>';
}

if ($ref == '1') {
    $rqdpr1 = query("SELECT correo FROM cursos_participantes WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    $rqdpr2 = fetch($rqdpr1);
    $correo = $rqdpr2['correo'];
} else {
    $rqdus1 = query("SELECT email FROM cursos_usuarios WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    $rqdus2 = fetch($rqdus1);
    $correo = $rqdus2['email'];
}

echo $mensaje;
?>
<form id="FORM-editar_correo" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Correo:</b></td>
            <td><input type="email" name="correo" class="form-control" value="<?php echo $correo; ?>" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="editar-correo" value="1" />
                <input type="hidden" name="ref" value="<?php echo $ref; ?>" />
                <input type="hidden" name="id_ref" value="<?php echo $id_ref; ?>" />
                <input type="submit" name="enviar" class="btn btn-success btn-sm btn-block" value="EDITAR CORREO" />
            </td>
        </tr>
    </table>
</form>



<script>
    $('#FORM-editar_correo').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.depurar-correos.editar_correo.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    });
</script>
