<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_leccion = post('id_leccion');

/* tema */
$rqdt1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
$leccion = fetch($rqdt1);


/* presentacion */
$rqdtp1 = query("SELECT * FROM cursos_onlinecourse_presentaciones WHERE id_leccion='$id_leccion' ORDER BY id ASC ");
if (num_rows($rqdtp1) == 0) {
    echo "<b>Esta lecci&oacute;n no tiene presentaciones asociadas.</b>";
}
?>

<b>AUDIO:</b> 
<?php
if ($leccion['audio_presentacion'] == '') {
    echo "<b style='color:red;'>NO SE TIENE AUDIO PARA ESTA PRESENTACION</b>";
} else {
    $url_audio = '../../audios/presentaciones/' . $leccion['audio_presentacion'];
    if (file_exists($url_audio)) {
        echo $leccion['audio_presentacion'];
        echo "<br/>";
        echo '<audio
        id="t-rex-roar"
        controls
        src="'.$dominio_www.'contenido/audios/presentaciones/' . $leccion['audio_presentacion'] . '">
    </audio>';
    } else {
        echo "NO SE TIENE AUDIO PARA ESTA PRESENTACION";
    }
}
?>

<button class="btn btn-warning btn-xs active pull-right" onclick="presentacion_audio_agregar_p1('<?php echo $id_leccion; ?>');">
    SUBIR AUDIO
</button>

<hr/>
<div style="height:450px;overflow-y:scroll">
    <table class="table table-bordered table-striped">
        <?php
        while ($presentacion = fetch($rqdtp1)) {
            ?>
            <tr>
                <td><div style='width:270px;background:gray;text-align:center;overflow: hidden;'><img src="<?php echo $dominio_www; ?>contenido/imagenes/presentaciones/<?php echo $presentacion['imagen']; ?>" style="height:150px;"/></div></td>
                <td>
                    <?php echo (int) ($presentacion['duracion_audio'] / 60); ?> min. | <?php echo ($presentacion['duracion_audio'] % 60); ?> seg. 
                    <br/>
                    <br/>
                    <button class="btn btn-primary btn-xs btn-block" onclick="presentacion_leccion_editar_p1('<?php echo $presentacion['id']; ?>');">
                        EDITAR
                    </button>
                    <br/>
                    <button class="btn btn-danger btn-xs btn-block" onclick="presentacion_leccion_eliminar_p1('<?php echo $presentacion['id']; ?>');">
                        ELIMINAR
                    </button>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>

<hr/>

<button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_agregar_p1('<?php echo $id_leccion; ?>');">
    AGREGAR PRESENTACI&oacute;N
</button>

