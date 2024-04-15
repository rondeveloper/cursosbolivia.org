<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

/* registrar accesos */
if (isset_post('sw_habilitar_p2')) {
    $costo_envio = post('costo_envio');
    $rqvac1 = query("SELECT id FROM direnvio_certs WHERE id_curso='$id_curso' LIMIT 1 ");
    if (num_rows($rqvac1) == 0) {
        query("INSERT INTO direnvio_certs 
        (id_curso, costo_envio) 
        VALUES 
        ('$id_curso','$costo_envio')");
        logcursos('Habilitacion de envio de certificado fisico', 'curso-edicion', 'curso', $id_curso);
        echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro agregado correctamente.
  </div>';
    } else {
        $rqvac2 = fetch($rqvac1);
        $id_acc = $rqvac2['id'];
        query("UPDATE direnvio_certs SET 
        costo_envio='$costo_envio' 
        WHERE id='$id_acc' LIMIT 1 ");
        logcursos('Actualizacion de envio de certificado fisico', 'curso-edicion', 'curso', $id_curso);
        echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro modificado correctamente.
  </div>';
    }
}

if (isset_post('sw_habilitar')) {
?>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">ENV&Iacute;O CERTIFICADO FISICO</h4>
            <form id="FORM-envio_certificado_fisico">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>Costo de env&iacute;o:</td>
                        <td><input type="number" name="costo_envio" class="form-control" value="0" required /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 20px; text-align: center;">
                            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                            <input type="hidden" name="sw_habilitar_p2" value="1" />
                            <b class="btn btn-success" onclick="habilitar_p2();">ASIGNAR ACCESOS</b>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php
} else {
    $rqva1 = query("SELECT * FROM direnvio_certs WHERE id_curso='$id_curso' LIMIT 1 ");
    if (num_rows($rqva1) == 0) {
    ?>
        <div class="alert alert-danger">
            <strong>SIN ENVIO DE CERTIFICADO HABILITADO</strong>
            <br>
            Este curso no tiene habilitado esta funcionalidad.
        </div>
        <br>
        <b class="btn btn-primary" onclick="habilitar(<?php echo $id_curso; ?>);">HABILITAR ENV&Iacute;O DE CERTIFICADO</b>
    <?php
    } else {
        $rqva2 = fetch($rqva1);
        $costo_envio = $rqva2['costo_envio'];
    ?>
        <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">ENV&Iacute;O CERTIFICADO FISICO</h4>
        <form id="FORM-envio_certificado_fisico">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>Costo de env&iacute;o:</td>
                    <td><input type="number" name="costo_envio" class="form-control" value="<?php echo $costo_envio; ?>" required /></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 20px; text-align: center;">
                        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                        <input type="hidden" name="sw_habilitar_p2" value="1" />
                        <b class="btn btn-info" onclick="habilitar_p2();">ACTUALIZAR ACCESOS</b>
                    </td>
                </tr>
            </table>
        </form>
<?php
    }
}
?>

<!--habilitar-->
<script>
    function habilitar(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.envio_certificado_fisico.php',
            data: {
                id_curso: id_curso,
                sw_habilitar: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-envio_certificado_fisico").html(data);
            }
        });
    }
</script>

<!-- habilitar_p2 -->
<script>
    function habilitar_p2() {
        var form = $("#FORM-envio_certificado_fisico").serialize();

        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.envio_certificado_fisico.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-envio_certificado_fisico").html(data);
            }
        });
    }
</script>