<?php

error_reporting(0);

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_download = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_download = real_escape_string($_SERVER['REMOTE_ADDR']);
}


$url_referer = "2$739i8edid9d";

/* VIDEO INTRO CURSO ONLINE */
if (isset_get('id_curso_online')) {
    $id_curso_online = get('id_curso_online');
    $rqdcl1 = query("SELECT urltag,video_introductorio FROM cursos_onlinecourse WHERE id='$id_curso_online' ");
    $rqdcl2 = fetch($rqdcl1);
    $url_referer = $dominio."curso-online/" . $rqdcl2['urltag'] . ".html";
    $name = $rqdcl2['video_introductorio'];
}


/* VIDEO DE LECCION */
if (isset_get('id_leccion')) {
    $id_leccion = get('id_leccion');
    $rqdcl1 = query("SELECT urltag,video FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ");
    $rqdcl2 = fetch($rqdcl1);
    $url_referer = $dominio."curso-online-leccion/" . $rqdcl2['urltag'] . "/video.html";
    $name = $rqdcl2['video'];
}



/* verificador de origen */
if ($_SERVER['HTTP_REFERER'] !== $url_referer) {
    echo "Acceso denegado";
    exit;
}


ob_end_clean();
$path = "../../videos/cursos/$name";
if (!is_file($path) or connection_status() != 0) {
    return(FALSE);
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-Type: application/octet-stream");
header("Content-Length: " . (string) (filesize($path)));
header("Content-Transfer-Encoding: binary\n");

header("Content-type: video/mp4");
header('Access-Control-Allow-Origin: '.$dominio);

if ($file = fopen($path, 'rb')) {
    while (!feof($file) and (connection_status() == 0)) {
        print(fread($file, 1024 * 8));
        flush();
    }
    fclose($file);
}
return((connection_status() == 0) and !connection_aborted());

