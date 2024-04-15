<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$inputfile = 'archivo';
$id_archivo = post('id_archivo');
$id_administrador = administrador('id');
//echo $id_archivo." -- ".$id_administrador;exit;
$nombre_archivo = substr(md5(rand(100, 999)), rand(0, 27), 7) . '-';


if (isset_post('not-archivo-' . $id_archivo)) {
    $rqa1 = query("SELECT id_licitacion FROM archivos WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
    $rqa2 = fetch($rqa1);
    $id_licitacion = $rqa2['id_licitacion'];

    $rqc1 = query("SELECT id FROM convocatorias_nacionales WHERE id='$id_licitacion' ORDER BY id DESC limit 1 ");
    if (num_rows($rqc1) == 0) {
        query("DELETE FROM archivos WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
        movimiento('Eliminacion de archivo [licitacion:' . $id_licitacion . '][modulo de agregado archivos faltantes]', 'eliminacion-archivo', 'archivo', $id_archivo);
        echo "<b style='color:red;'>Archivo eliminado!</b>";
    } else {
        query("UPDATE archivos SET estado='9' WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
        movimiento('Saltado de archivo [licitacion:' . $id_licitacion . '][modulo de agregado archivos faltantes]', 'saltado-de-archivo', 'archivo', $id_archivo);
        echo "<b style='color:orange;'>Archivo saltado!</b>";
    }
    exit;
}



if (is_uploaded_file($_FILES[$inputfile]['tmp_name'])) {

    $year = date("Y");
    $month = date("m");
    $day = date("d");
    if (!is_dir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year")) {
        mkdir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year");
    }
    if (!is_dir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year/$month")) {
        mkdir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year/$month");
    }
    if (!is_dir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year/$month/$day")) {
        mkdir("/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/$year/$month/$day");
    }
    $carpeta = "$year/$month/$day";
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_upload = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_upload = $_SERVER['REMOTE_ADDR'];
    }
    $fecha_upload = date("Y-m-d H:i:s");

    $nombre_archivo .= str_replace(' ', '-', $_FILES[$inputfile]['name']);
    if (move_uploaded_file($_FILES[$inputfile]['tmp_name'], "/home/infosico/public_html/contenido/archivos/convocatorias-sicoes/" . $carpeta . '/' . $nombre_archivo)) {
        query("UPDATE archivos SET id_administrador='$id_administrador',estado='1',carpeta_asignada='$carpeta',nombre_archivo='$nombre_archivo',ip_upload='$ip_upload',fecha_upload='$fecha_upload' WHERE id='$id_archivo' ORDER BY id DESC LIMIT 1 ");
        //movimiento('Cargado de archivo', 'carga-archivo', 'archivo', $id_archivo);
        echo "<b style='color:green;'>Subido!</b>";
    } else {
        echo "<b style='color:red;'>Error!</b>";
    }
} else {
    echo "Error ARC-7515";
}

mysql_close();
exit;
?>

