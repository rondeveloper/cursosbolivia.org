<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$id_editor = 'editor-tag'.rand(0,99);

editorTinyMCE($id_editor);
?>

<form method="post" action="">
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td><b>TAG:</b></td>
            <td><input type="text" name="tag" class="form-control" placeholder="[NOMBRE-DEL-TAG]" required=""></td>
        </tr>
        <tr>
            <td colspan="2" >
                <b>CONTENIDO:</b>
                <br>
                <br>
                <textarea id="<?php echo $id_editor; ?>" name="contenido-tag"  style="height:400px;" rows="35"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <br>
                <input type="submit" name="" class="btn btn-success" value="AGREGAR TAG"/>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                <input type="hidden" name="agregar-tag" value="1"/>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>
