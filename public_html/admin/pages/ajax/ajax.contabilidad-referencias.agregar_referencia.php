<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_tipo_movimiento = post('id_tipo_movimiento');
?>
<form action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>REFERENCIA <?php $tipo=='ingreso'?'INGRESO':'SALIDA'; ?>:</b></td>
            <td><input type="text" name="referencia" required="" class="form-control"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <input type="hidden" name="id_tipo_movimiento" value="<?php echo $id_tipo_movimiento; ?>"/>
                <input type="submit" name="agregar-referencia" value="AGREGAR REFERENCIA" class="btn btn-success btn-block"/>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>
