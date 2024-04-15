<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_docente()) {
    echo "ACCESO DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_tarea = post('id_tarea');

$rqp1 = query("SELECT * FROM cursos_onlinecourse_tareas WHERE id='$id_tarea' ");
$rqp2 = fetch($rqp1);
?>
<form action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Tarea:</b></td>
            <td><input type="text" name="tarea" required="" class="form-control" value="<?php echo $rqp2['tarea']; ?>"></td>
        </tr>
        <tr>
            <td><b>Descripci&oacute;n:</b></td>
            <td><textarea name="descripcion" required="" class="form-control" style="height: 120px;"><?php echo $rqp2['descripcion']; ?></textarea></td>
        </tr>
        <tr>
            <td><b>Estado:</b></td>
            <td class="text-center">
                <?php
                $htm_check_a = '';
                $htm_check_b = ' checked="" ';
                if ($rqp2['estado'] == '1') {
                    $htm_check_a = ' checked="" ';
                    $htm_check_b = '';
                }
                ?>
                <input type="radio" name="estado" value="1" style="width: auto;" <?php echo $htm_check_a; ?>> Habilitado
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <input type="radio" name="estado" value="0" style="width: auto;" <?php echo $htm_check_b; ?>> No Habilitado
                <br>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <input type="hidden" name="id_tarea" value="<?php echo $rqp2['id']; ?>"/>
                <input type="submit" name="editar-tarea" class="btn btn-info btn-block" value="ACTUALIZAR TAREA"/>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>
