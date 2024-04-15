<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_leccion = post('id_leccion');

$rql1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ORDER BY id LIMIT 1 ");
$rql2 = fetch($rql1);
$sw_vimeo = $rql2['sw_vimeo'];
$video_id = $rql2['video'];
$localvideofile = $rql2['localvideofile'];
$titulo = $rql2['titulo'];
$nro_leccion = $rql2['nro_leccion'];

if ($sw_vimeo == '1') {
?>
    <table class="table table-bordered table-striped">
        <tr>
            <td><b>Lecci&oacute;n:</b></td>
            <td><?php echo $titulo; ?></td>
        </tr>
        <tr>
            <td><b>N&uacute;mero:</b></td>
            <td># <?php echo $nro_leccion; ?></td>
        </tr>
        <tr>
            <td><b>Servidor:</b></td>
            <td>VIMEO</td>
        </tr>
        <tr>
            <td><b>ID de video:</b></td>
            <td><?php echo $video_id; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Video:</b></td>
        </tr>
        <tr>
            <td colspan="2">
                <iframe src="https://player.vimeo.com/video/<?php echo $video_id; ?>?autoplay=1" style="width: 100%;height: 310px;background: #222;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen autoplay></iframe>
            </td>
        </tr>
    </table>
<?php
} elseif ($sw_vimeo == '2') {
?>
    <table class="table table-bordered table-striped">
        <tr>
            <td><b>Lecci&oacute;n:</b></td>
            <td><?php echo $titulo; ?></td>
        </tr>
        <tr>
            <td><b>N&uacute;mero:</b></td>
            <td># <?php echo $nro_leccion; ?></td>
        </tr>
        <tr>
            <td><b>Servidor:</b></td>
            <td>YOUTUBE</td>
        </tr>
        <tr>
            <td><b>ID de video:</b></td>
            <td><?php echo $video_id; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Video:</b></td>
        </tr>
        <tr>
            <td colspan="2">
                <iframe src="https://www.youtube-nocookie.com/embed/<?php echo $video_id; ?>?autoplay=1&iv_load_policy=3&modestbranding=1&rel=0&showinfo=0"  style="width: 100%;height: 310px;background: #222;" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </td>
        </tr>
    </table>
<?php
} else {
    $url_local_video = $dominio_www . 'contenido/videos/cursos/' . $localvideofile;
?>
    <table class="table table-bordered table-striped">
        <tr>
            <td><b>Lecci&oacute;n:</b></td>
            <td><?php echo $titulo; ?></td>
        </tr>
        <tr>
            <td><b>N&uacute;mero:</b></td>
            <td># <?php echo $nro_leccion; ?></td>
        </tr>
        <tr>
            <td><b>Servidor:</b></td>
            <td>LOCAL</td>
        </tr>
        <tr>
            <td><b>Archivo:</b></td>
            <td><?php echo $localvideofile; ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Video:</b></td>
        </tr>
        <tr>
            <td colspan="2">
                <video width="100%" controls style="background: #222;" autoplay>
                    <source src="<?php echo $url_local_video; ?>" type="video/mp4">
                    <source src="<?php echo $url_local_video; ?>" type="video/ogg">
                    Your browser does not support HTML video.
                </video>
            </td>
        </tr>
    </table>
<?php
}
