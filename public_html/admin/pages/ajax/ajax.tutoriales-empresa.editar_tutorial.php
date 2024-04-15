<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_tutorial = post('id_tutorial');

$rql1 = query("SELECT * FROM tutoriales_empresa WHERE id='$id_tutorial' ORDER BY id LIMIT 1 ");
$rql2 = fetch($rql1);
$sw_vimeo = $rql2['sw_vimeo'];
$video_id = $rql2['video'];
$localvideofile = $rql2['localvideofile'];
$titulo = $rql2['titulo'];
$minutos = $rql2['minutos'];
?>
<form action="" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-striped">
        <tr>
            <td><b>T&iacute;tulo del tutorial:</b></td>
            <td><textarea name="titulo" class="form-control" rows="2" required="" placeholder="Descripci&oacute;n dada a los administradores."><?php echo $titulo; ?></textarea></td>
        </tr>
        <tr>
            <td><b>Duraci&oacute;n en minutos:</b></td>
            <td><input type="number" name="minutos" value="<?php echo $minutos; ?>" class="form-control"></td>
        </tr>
        <tr>
            <td><b>Servidor:</b></td>
            <td>
                <select name="sw_vimeo" class="form-control" onchange="selec_servidor_edit(this.value);">
                    <?php
                    $selected = '';
                    if ($sw_vimeo == '1') {
                        $selected = ' selected="selected" ';
                    }
                    ?>
                    <option value="1" <?php echo $selected; ?>>VIMEO</option>
                    <?php
                    $selected = '';
                    if ($sw_vimeo == '2') {
                        $selected = ' selected="selected" ';
                    }
                    ?>
                    <option value="2" <?php echo $selected; ?>>YOUTUBE</option>
                    <?php
                    $selected = '';
                    if ($sw_vimeo == '0') {
                        $selected = ' selected="selected" ';
                    }
                    ?>
                    <option value="0" <?php echo $selected; ?>>LOCAL</option>
                </select>
            </td>
        </tr>
        <tr id="tr-lf-edit" style="display: none;">
            <td><b>Archivo:</b></td>
            <td><input type="file" name="localvideofile" class="form-control" /></td>
        </tr>
        <tr id="tr-vm-edit" style="display: table-row;">
            <td><b>ID de Video:</b></td>
            <td><input type="text" name="video" value="<?php echo $video_id; ?>" class="form-control"></td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center;padding:20px;">
                    <input type="hidden" name="id_tutorial" value="<?php echo $id_tutorial; ?>" />
                    <input type="submit" name="editar-tutorial" value="ACTUALIZAR TUTORIAL" class="btn btn-success btn-lg btn-animate-demo active" />
                </div>
            </td>
        </tr>
    </table>
</form>


<script>
    function selec_servidor_edit(dat) {
        if(dat=='0'){
            $("#tr-lf-edit").css('display','table-row');
            $("#tr-vm-edit").css('display','none');
        }else{
            $("#tr-lf-edit").css('display','none');
            $("#tr-vm-edit").css('display','table-row');
        }
    }
</script>