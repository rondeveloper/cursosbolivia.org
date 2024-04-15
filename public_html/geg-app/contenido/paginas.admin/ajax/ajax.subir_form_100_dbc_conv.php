<?php

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$id_convocatoria = post('id_convocatoria');
$cuce_licitacion = post('cuce');
$contenido_formulario_100 = post_html('formulario-100');

$id_administrador = administrador('id');

$mensaje = '';

$sw_arch_c_upload = false;
$sw_arch_dbc_upload = false;

//NO EXISTENCIA FORMULARIO 100
if (isset_post('not-formulario-' . $id_convocatoria) && post('not-formulario-' . $id_convocatoria) == 1) {
    query("UPDATE convocatorias_nacionales SET sw_f100='9' WHERE id='$id_convocatoria' ORDER BY id DESC limit 1 ");
}

//AGREGADO DE FORMULARIO 100

$rqform1 = query("SELECT * FROM formularios WHERE id_licitacion='$id_convocatoria' AND nombre='FORM 100' ORDER BY id DESC limit 1");
$rqform2 = mysql_fetch_array($rqform1);

$form_nombre = $rqform2['nombre'];
$form_carpeta_asignada = $rqform2['carpeta_asignada'];
$form_nombre_archivo = $rqform2['nombre_archivo'];

$contenido_formulario = $contenido_formulario_100;


//VERIFICACION DE CORRECTO UPLOAD DE DATOS
$SW_INCORRECT = false;
$TXTINCORRECT = "";
if (!is_uploaded_file($_FILES['archivo_convocatoria']['tmp_name'])) {
    $SW_INCORRECT = true;
    $TXTINCORRECT .= "<br/>Conv. faltante";
}
if (!is_uploaded_file($_FILES['archivo_dbc']['tmp_name'])) {
    $SW_INCORRECT = true;
    $TXTINCORRECT .= "<br/>DBC faltante";
}
if (strpos("---".$contenido_formulario, $cuce_licitacion)==0) {
    $SW_INCORRECT = true;
    $TXTINCORRECT .= "<br/>Form100 incorrecto";
}
if($SW_INCORRECT){
    /* mensaje de error */
    echo '<b style="color:red;">Error!'.$TXTINCORRECT.'</b> <br/> <b onclick="subir_form_100_dbc_conv('.$id_convocatoria.');" class="btn btn-xs btn-warning">RE-SUBIR</b>';
    exit;
}


if (strpos($contenido_formulario, $cuce_licitacion)) {

    $url_content_min = $contenido_formulario;

    //monto
    $arrr_1 = explode('TOTAL:', $url_content_min);
    $arrr_2 = explode('</tr>', $arrr_1[1]);
    $monto = str_replace('/100', '/100 <br/> Bs. ', strip_tags($arrr_2[0]));

    $arr_precio = explode('Bs.', strip_tags($monto));
    $precio = (int) (trim(str_replace(',', '', $arr_precio[1])));

    //tipo de contratacion
    $id_tipo_de_contratacion = '0';
    if (strpos($url_content_min, '<p>Inicio de proceso de')) {
        $arcont1 = explode('<p>Inicio de proceso de', $url_content_min);
        $arcont2 = explode('</p>', $arcont1[1]);
        $titulo_tipo_de_contratacion = trim($arcont2[0]);
        $titulo_identificador_tipo_de_contratacion = limpiar_enlace($titulo_tipo_de_contratacion);
        $rverficontr1 = query("SELECT id FROM tipos_de_contratacion WHERE titulo_identificador='$titulo_identificador_tipo_de_contratacion' limit 1 ");
        if (mysql_num_rows($rverficontr1) == 0) {
            query("INSERT INTO tipos_de_contratacion (titulo,titulo_identificador) VALUES ('$titulo_tipo_de_contratacion','$titulo_identificador_tipo_de_contratacion') ");
            $rverficontr1 = query("SELECT id FROM tipos_de_contratacion WHERE titulo_identificador='$titulo_identificador_tipo_de_contratacion' limit 1 ");
        }
        $rverficontr2 = mysql_fetch_array($rverficontr1);
        $id_tipo_de_contratacion = $rverficontr2['id'];
    }

    if (strlen($form_carpeta_asignada) > 3) {

        $ar_carpasign = explode('/', $form_carpeta_asignada);
        $anio = $ar_carpasign[0];
        $mes = $ar_carpasign[1];
        $dia = $ar_carpasign[2];
        if (!is_dir("/home/infosico/public_html/contenido/formularios/$anio")) {
            mkdir("/home/infosico/public_html/contenido/formularios/$anio");
            chmod("/home/infosico/public_html/contenido/formularios/$anio", 0757);
        }
        if (!is_dir("/home/infosico/public_html/contenido/formularios/$anio/$mes")) {
            mkdir("/home/infosico/public_html/contenido/formularios/$anio/$mes");
            chmod("/home/infosico/public_html/contenido/formularios/$anio/$mes", 0757);
        }
        if (!is_dir("/home/infosico/public_html/contenido/formularios/$anio/$mes/$dia")) {
            mkdir("/home/infosico/public_html/contenido/formularios/$anio/$mes/$dia");
            chmod("/home/infosico/public_html/contenido/formularios/$anio/$mes/$dia", 0757);
        }

        crear_formulario_fisico($form_nombre, stripcslashes($contenido_formulario), $form_carpeta_asignada, $form_nombre_archivo);

        //echo "https://infosicoes.com/contenido/formularios/$form_carpeta_asignada/$form_nombre_archivo<hr/>";

        query("UPDATE convocatorias_nacionales SET sw_f100='1',monto='$monto',precio='$precio',id_tipo_de_contratacion='$id_tipo_de_contratacion' WHERE id='$id_convocatoria' ORDER BY id DESC limit 1 ");

        $mensaje .= '<b style="color:green;">Formulario agregado</b><br/>';
    } else {
        $mensaje .= ' No tiene carpeta asignada! ';
    }
} else {
    $mensaje .= ' Formulario no corresponde! ';
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


$rqvea1 = query("SELECT count(*) AS total FROM archivos WHERE id_licitacion='$id_convocatoria' AND nombre IN ('Convocatoria','Documento Base de Contratacion') ORDER BY id DESC limit 2 ");
$rqvea2 = mysql_fetch_array($rqvea1);
//if($sw_arch_c_upload && $sw_arch_dbc_upload){
if($rqvea2['total'] == 2){
    query("UPDATE convocatorias_nacionales SET sw_f100='1' WHERE id='$id_convocatoria' ORDER BY id DESC limit 1 ");
}

echo $mensaje;

mysql_close();
?>

