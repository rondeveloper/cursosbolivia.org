<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$id_convocatoria = post('id_convocatoria');
$cuce_licitacion = post('cuce');
$contenido_formulario_100 = post_html('formulario-100');

$id_administrador = administrador('id');

$mensaje = '';

$sw_arch_c_upload = false;
$sw_arch_dbc_upload = false;

//NO EXISTENCIA FORMULARIO 100 [ escape de subida de archivos ]
if (isset_post('not-formulario-' . $id_convocatoria) && post('not-formulario-' . $id_convocatoria) == 1) {
    query("UPDATE convocatorias_nacionales SET sw_f100='9' WHERE id='$id_convocatoria' ORDER BY id DESC limit 1 ");
    echo '<b style="color:red;">Quitado de la lista</b>';
    exit;
}

//VERIFICACION DE CORRECTO UPLOAD DE DATOS
$SW_INCORRECT = false;
$TXTINCORRECT = "";
$SW_auxuploadbetween = false;
if (is_uploaded_file($_FILES['archivo_convocatoria']['tmp_name'])) {
    $SW_auxuploadbetween = true;
    $TXTINCORRECT .= "<br/>Conv. faltante";
}
if (is_uploaded_file($_FILES['archivo_dbc']['tmp_name'])) {
    $SW_auxuploadbetween = true;
}
if(!$SW_auxuploadbetween){
    $TXTINCORRECT .= "<br/>No se subio ningun archivo";
    $SW_INCORRECT = true;
}


if($SW_INCORRECT){
    /* mensaje de error */
    echo '<b style="color:red;">Error!'.$TXTINCORRECT.'</b> <br/> <b onclick="subir_licitarch('.$id_convocatoria.');" class="btn btn-xs btn-warning">RE-SUBIR</b>';
    exit;
}


if (isset_post('id_archivo_convocatoria')) {

    $id_archivo = post('id_archivo_convocatoria');
    $inputfile = 'archivo_convocatoria';
    if (is_uploaded_file($_FILES[$inputfile]['tmp_name'])) {

        $nombre_archivo = substr(md5(rand(100, 999)), rand(0, 27), 7) . '-';

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
            query("UPDATE archivos SET id_administrador='$id_administrador',estado='1',carpeta_asignada='$carpeta',nombre_archivo='$nombre_archivo',ip_upload='$ip_upload',fecha_upload='$fecha_upload' WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
            //movimiento('Cargado de archivo', 'carga-archivo', 'archivo', $id_archivo);
            $mensaje .= "<b style='color:green;'>C Subido!</b><br/>";
            $sw_arch_c_upload = true;
        } else {
            $mensaje .= "<b style='color:red;'>C Error!</b><br/>";
        }
    } else {
        $mensaje .= " No se subio la convocatoria | ";
    }
} elseif (isset_post('arch_id_licitacion_convocatoria')) {
    $arch_id_licitacion = post('arch_id_licitacion_convocatoria');
    $name_archivo = post('nombre_archivo_convocatoria');
    $inputfile = 'archivo_convocatoria';
    if (is_uploaded_file($_FILES[$inputfile]['tmp_name'])) {

        $nombre_archivo = substr(md5(rand(100, 999)), rand(0, 27), 7) . '-';

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
            query("UPDATE archivos SET id_administrador='$id_administrador',estado='1',carpeta_asignada='$carpeta',nombre_archivo='$nombre_archivo',ip_upload='$ip_upload',fecha_upload='$fecha_upload' WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
            query("INSERT INTO archivos (id_licitacion,id_administrador,estado,nombre,carpeta_asignada,nombre_archivo,ip_upload,fecha_upload) VALUES ('$arch_id_licitacion','$id_administrador','1','$name_archivo','$carpeta','$nombre_archivo','$ip_upload','$fecha_upload')");
            $mensaje .= "<b style='color:green;'>C Subido!</b><br/>";
            $sw_arch_c_upload = true;
        } else {
            $mensaje .= "<b style='color:red;'>C Error!</b><br/>";
        }
    } else {
        $mensaje .= " No se subio la convocatoria | ";
    }
}



if (isset_post('id_archivo_dbc')) {

    $id_archivo = post('id_archivo_dbc');
    $inputfile = 'archivo_dbc';
    if (is_uploaded_file($_FILES[$inputfile]['tmp_name'])) {

        $nombre_archivo = substr(md5(rand(100, 999)), rand(0, 27), 7) . '-';

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
            query("UPDATE archivos SET id_administrador='$id_administrador',estado='1',carpeta_asignada='$carpeta',nombre_archivo='$nombre_archivo',ip_upload='$ip_upload',fecha_upload='$fecha_upload' WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
            //movimiento('Cargado de archivo', 'carga-archivo', 'archivo', $id_archivo);
            $mensaje .= "<b style='color:green;'>DBC Subido!</b><br/>";
            $sw_arch_dbc_upload = true;
        } else {
            $mensaje .= "<b style='color:red;'>DBC Error!</b><br/>";
        }
    } else {
        $mensaje .= " No se subio la convocatoria ";
    }
} elseif (isset_post('arch_id_licitacion_dbc')) {
    $arch_id_licitacion = post('arch_id_licitacion_dbc');
    $name_archivo = post('nombre_archivo_dbc');
    $inputfile = 'archivo_dbc';
    if (is_uploaded_file($_FILES[$inputfile]['tmp_name'])) {

        $nombre_archivo = substr(md5(rand(100, 999)), rand(0, 27), 7) . '-';

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
            query("UPDATE archivos SET id_administrador='$id_administrador',estado='1',carpeta_asignada='$carpeta',nombre_archivo='$nombre_archivo',ip_upload='$ip_upload',fecha_upload='$fecha_upload' WHERE id='$id_archivo' ORDER BY id DESC limit 1 ");
            query("INSERT INTO archivos (id_licitacion,id_administrador,estado,nombre,carpeta_asignada,nombre_archivo,ip_upload,fecha_upload) VALUES ('$arch_id_licitacion','$id_administrador','1','$name_archivo','$carpeta','$nombre_archivo','$ip_upload','$fecha_upload')");
            $mensaje .= "<b style='color:green;'>DBC Subido!</b><br/>";
            $sw_arch_dbc_upload = true;
        } else {
            $mensaje .= "<b style='color:red;'>DBC Error!</b><br/>";
        }
    } else {
        $mensaje .= " No se subio la convocatoria | ";
    }
}


$rqvea1 = query("SELECT count(*) AS total FROM archivos WHERE id_licitacion='$id_convocatoria' AND estado='1' AND nombre IN ('Convocatoria','Documento Base de Contratacion') ORDER BY id DESC limit 2 ");
$rqvea2 = fetch($rqvea1);
//if($sw_arch_c_upload && $sw_arch_dbc_upload){
if($rqvea2['total'] == 2){
    query("UPDATE convocatorias_nacionales SET sw_f100='1' WHERE id='$id_convocatoria' ORDER BY id DESC limit 1 ");
}

echo $mensaje;

mysql_close();
?>

