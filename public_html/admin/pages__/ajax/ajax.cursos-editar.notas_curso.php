<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* eliminar_material */
if (isset_post('mod_notas')) {
    $mod_notas = post('mod_notas');
    logcursos('Cambio de modalidad de notas [' . $mod_notas.']', 'curso-edicion', 'curso', $id_curso);
    query("UPDATE cursos SET mod_notas='$mod_notas' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    echo '<div class="alert alert-success">
      <strong>EXITO</strong> la modalidad fue actualizada.
    </div>';
}

/* curso */
$rqdc1 = query("SELECT mod_notas FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 "); 
$rqdc2 = fetch($rqdc1);
$mod_notas = $rqdc2['mod_notas'];
?>

<form id="FORM-notas_curso" action="" method="post">
    <table class="table table-bordered table-striped">
        <?php
        $htm_checked = '';
        if($mod_notas=='0'){
            $htm_checked = ' checked="" ';
        }
        ?>
        <tr>
            <td>MODALIDAD:</td>
            <td><b>SIN NOTAS</b></td>
            <td><input type="radio" name="mod_notas" value="0" <?php echo $htm_checked; ?>/></td>
        </tr>
        <?php
        $htm_checked = '';
        if($mod_notas=='1'){
            $htm_checked = ' checked="" ';
        }
        ?>
        <tr>
            <td>MODALIDAD:</td>
            <td><b>MANUAL</b></td>
            <td><input type="radio" name="mod_notas" value="1" <?php echo $htm_checked; ?>/></td>
        </tr>
        <?php
        $htm_checked = '';
        if($mod_notas=='2'){
            $htm_checked = ' checked="" ';
        }
        ?>
        <tr>
            <td>MODALIDAD:</td>
            <td><b>SISTEMATICA</b></td>
            <td><input type="radio" name="mod_notas" value="2" <?php echo $htm_checked; ?> disabled=""/></td>
        </tr>
        <tr>
            <td class="text-center" colspan="3">
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                <input type="submit" class="btn btn-success active" name="actualizar-modaldiad_notas" value="ACTUALIZAR MODALIDAD DE NOTAS"/>
            </td>
        </tr>
    </table>
</form>

<script>
    $("#FORM-notas_curso").on('submit', function (evt) {
        evt.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-notas_curso").html('Cargando...<br><br><br><br>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.notas_curso.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#AJAXCONTENT-notas_curso").html(data);
            }
        });
    });
</script>