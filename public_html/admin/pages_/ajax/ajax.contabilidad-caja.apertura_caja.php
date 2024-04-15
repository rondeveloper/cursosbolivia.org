<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_administrador = administrador('id');
$rqdas1 = query("SELECT s.id FROM administradores a INNER JOIN sucursales s ON s.id=a.id_sucursal WHERE a.id='$id_administrador' ");
$rqdas2 = fetch($rqdas1);
$id_sucursal = $rqdas2['id'];

if (isset_post('aperturar-caja')) {
    $rqv1 = query("SELECT id FROM caja WHERE id_administrador='$id_administrador' AND fecha=CURDATE() ORDER BY id DESC limit 1 ");
    if (num_rows($rqv1)>0) {
        echo '<div class="alert alert-danger">
        <strong>ERROR</strong> usted ya realizo apertura de caja.
      </div>
      ';
    } else {
        $monto_apertura = post('monto_apertura');
        query("INSERT INTO caja 
        (id_administrador, id_sucursal, fecha, monto_apertura, monto_cierre, estado) 
        VALUES 
        ('$id_administrador','$id_sucursal',CURDATE(),'$monto_apertura','0','1')");
        echo '<div class="alert alert-success">
        <strong>EXITO</strong><br>La apertura de caja se realizo correctamente.
      </div>
      ';
    }
} else {

    $rqv1 = query("SELECT id FROM caja WHERE id_administrador='$id_administrador' AND fecha=CURDATE() ORDER BY id DESC limit 1 ");
    if (num_rows($rqv1) == 0) {
?>
        <form id="FORM-apertura-caja" action="">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>
                        <b>Ingrese el monto de apertura de caja:</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="number" step=".1" name="monto_apertura" value="" class="form-control" placeholder="Monto..." required />
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="hidden" name="aperturar-caja" value="1"/>
                    <input type="submit" value="APERTURAR CAJA" class="btn btn-lg btn-success" />
                    </td>
                </tr>
            </table>
        </form>
    <?php
    } else {
    ?>
        <div class="alert alert-info">
            <strong>AVISO</strong><br>Usted ya realizo la apertura de caja para el d&iacute;a de hoy.
        </div>

<?php
    }
}
?>


<script>
    $('#FORM-apertura-caja').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.contabilidad-caja.apertura_caja.php',
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