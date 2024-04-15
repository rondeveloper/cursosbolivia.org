<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_referencia = post('id_referencia');

$rqdr1 = query("SELECT * FROM contabilidad_referencias WHERE id='$id_referencia' ORDER BY id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);
?>
<form action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>REFERENCIA:</b></td>
            <td><input type="text" name="referencia" required="" value="<?php echo $rqdr2['titulo']; ?>" class="form-control"/></td>
        </tr>
        <tr>
            <td><b>ESTADO:</b></td>
            <td>
                <select class="form-control" name="estado">
                    <?php
                    $selected_1 = '';
                    $selected_2 = ' selected="selected" ';
                    if ($rqdr2['estado'] == '1') {
                        $selected_1 = ' selected="selected" ';
                        $selected_2 = '';
                    }
                    ?>
                    <option value="1" <?php echo $selected_1; ?>>HABILITADO</option>
                    <option value="2" <?php echo $selected_2; ?>>DES-HABILITADO</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <input type="hidden" name="id_referencia" value="<?php echo $id_referencia; ?>"/>
                <input type="submit" name="editar-referencia" value="EDITAR REFERENCIA" class="btn btn-success btn-block"/>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>
