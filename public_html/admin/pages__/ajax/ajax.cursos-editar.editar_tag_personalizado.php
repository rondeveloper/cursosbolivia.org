<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_tag = post('id_tag');
$id_curso = post('id_curso');

$rqdt1 = query("SELECT * FROM cursos_tags_contenido WHERE id='$id_tag' ");
$rqdt2 = fetch($rqdt1);

if (isset_post('sw_delete_tag')) {
    $id_tag = post('id_tag');
    query("DELETE FROM cursos_tags_contenido WHERE id='$id_tag' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de TAG personalizado', 'tag-edicion', 'tag', $id_tag);
    echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro eliminado correctamente.
  </div>';
}

editorTinyMCE('editor-tag');
?>

<form method="post" action="">
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td><b>TAG:</b></td>
            <td><input type="text" name="tag" class="form-control" placeholder="[NOMBRE-DEL-TAG]" required="" value="<?php echo $rqdt2['titulo']; ?>"></td>
        </tr>
        <tr>
            <td colspan="2" >
                <b>CONTENIDO:</b>
                <br>
                <br>
                <textarea id="editor-tag" name="contenido-tag"  style="height:400px;" rows="35"><?php echo $rqdt2['contenido']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <br>
                <input type="submit" name="" class="btn btn-success" value="ACTUALIZAR TAG"/>
                <input type="hidden" name="editar-tag" value="1"/>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                <input type="hidden" name="id_tag" value="<?php echo $id_tag; ?>"/>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>

