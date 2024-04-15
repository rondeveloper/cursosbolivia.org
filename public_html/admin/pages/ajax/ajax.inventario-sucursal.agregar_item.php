<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/*
$mensaje = '';

if(isset_post('agregar-item')){
    $cantidad = post('cantidad');
    $item = post('item');
    $descripcion = post('descripcion');
    for($i=1;$i<=$cantidad;$i++){
        query("INSERT INTO items_sucursal (item,descripcion) VALUES ('$item','$descripcion') ");
    }
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> Item registrado.
  </div>';
}

echo $mensaje;
*/
?>

<form id="FORM-agregar-item" action="" method="post">
    <table class="table table-bordered table-striped">
        <tr>
            <td><b>ITEM</b></td>
            <td><input type="text" name="item" class="form-control" value="" autocomplete="off" required=""/></td>
        </tr>
        <tr>
            <td><b>CANTIDAD</b></td>
            <td><input type="number" name="cantidad" class="form-control" value="1" min="1" max="50" autocomplete="off" required=""/></td>
        </tr>
        <tr>
            <td><b>DESCRIPCI&Oacute;N</b></td>
            <td><textarea name="descripcion" class="form-control" style="height: 120px;"></textarea></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="agregar-item" value="1"/>
                <input type="submit" class="btn btn-success" value="AGREGAR" />
            </td>
        </tr>
    </table>
</form>



<!-- <script>
    $('#FORM-agregar-item').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.inventario-sucursal.agregar_item.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    });
</script> -->