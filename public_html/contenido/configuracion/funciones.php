<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function noaccent($dat){
    $acentos = array(('á'),('é'),('í'),('ó'),('ú'),('Á'),('É'),('Í'),('Ó'),('Ú'),('ñ'),('Ñ'),'ðŸ','🔴');
    $no_acent = array('a','e','i','o','u','A','E','I','O','U','n','N','','');
    $cadena = str_replace($acentos, $no_acent, $dat);
    return $cadena;
}

function limpiar_enlace($cadena) {

    $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", noaccent($cadena));
    $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
    $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
    $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
    $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
    $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
    $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
    $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
    $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
    $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
    $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
    $String = str_replace("ç", "c", $String);
    $String = str_replace("Ç", "C", $String);
    $String = str_replace("ñ", "n", $String);
    $String = str_replace("Ñ", "N", $String);
    $String = str_replace("Ý", "Y", $String);
    $String = str_replace("ý", "y", $String);

    $String = str_replace("&aacute;", "a", $String);
    $String = str_replace("&Aacute;", "A", $String);
    $String = str_replace("&eacute;", "e", $String);
    $String = str_replace("&Eacute;", "E", $String);
    $String = str_replace("&iacute;", "i", $String);
    $String = str_replace("&Iacute;", "I", $String);
    $String = str_replace("&oacute;", "o", $String);
    $String = str_replace("&Oacute;", "O", $String);
    $String = str_replace("&uacute;", "u", $String);
    $String = str_replace("&Uacute;", "U", $String);

    $String = str_replace('–', '', $String);

    $busc = array('Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', 'Ã±', '(', ')', '[', ']', ':', '"', "'", '´', '”', '“', '=', '°', '’', '³', '&', ';', 'acute', '\\', '#');
    $remm = array('a', 'e', 'i', 'o', 'u', 'n', '-', '-', '-', '-', '', '', "", '', '', '', '', '', '', '', '', '', '', '');

    $cadena = str_replace($busc, $remm, $String);

    $salida_0 = str_replace("Ãº", "u", str_replace("Ã³", "o", str_replace("Ã±", "n", str_replace("Ã¡", "a", str_replace("Ã©", "e", str_replace("Ã­", "i", strtolower($cadena)))))));
    $salida_0 = str_replace("?", "", str_replace(",", "", strtolower($salida_0)));
    $salida_0 = str_replace("/", "-", str_replace(",", "", strtolower($salida_0)));
    $salida_1 = str_replace("%", "", str_replace(".", "", str_replace(":", "", str_replace("_", "", str_replace(" ", "-", $salida_0)))));
    $salida = str_replace("Ã¡", "a", str_replace("Ã©", "e", str_replace("Ã­", "i", str_replace("Ã³", "o", str_replace("Ãº", "u", $salida_1)))));
    $salida_final = str_replace("--", "-", str_replace("---", "-", str_replace("Ã?", "a", str_replace("Ã‰", "e", str_replace("Ã?", "i", str_replace("Ã“", "o", str_replace("Ãš", "u", $salida)))))));
    return str_replace("\\", "", str_replace("--", "-", str_replace("---", "-", substr($salida_final, 0, 170))));
}

function limpiar_enlace22($cadena) {

    $cadena = str_replace(" ", "-", $cadena);

    $tofind = "Ã€Ã?Ã‚ÃƒÃ„Ã…Ã Ã¡Ã¢Ã£Ã¤Ã¥Ã’Ã“Ã”Ã•Ã–Ã˜Ã²Ã³Ã´ÃµÃ¶Ã¸ÃˆÃ‰ÃŠÃ‹Ã¨Ã©ÃªÃ«Ã‡Ã§ÃŒÃ?ÃŽÃ?Ã¬Ã­Ã®Ã¯Ã™ÃšÃ›ÃœÃ¹ÃºÃ»Ã¼Ã¿Ã‘Ã± ,.";
    $replac = "aeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiouaeiou";
    $cadena_sin_acentos = strtr(utf8_decode($cadena), utf8_decode($tofind), utf8_decode($replac));
    $cadena = ereg_replace("[^a-zA-Z0-9_.]", "-", $cadena_sin_acentos);

    $busc = array('Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', 'Ã±', 'Ã?', 'Ã‰', 'Ã?', 'Ã“', 'Ãš', 'Ã‘', ' ', '?', ',', '/', '%', '.', ':', '@', 'Nuquot', ':', '"', "'", "acute", "quot-", "N", "-ntilde-", '(', ')', '[', ']', ':');
    $remm = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', '-', '', '', '', '', '', '', '', '-', ':', '', '', "", "-", "-", "n", '-', '-', '-', '-', '');
    $salida_0 = str_replace($busc, $remm, $cadena);
    $salida_0 = str_replace("-y-", "&y&&", $salida_0);
    $salida_0 = str_replace("y-", "-", $salida_0);
    $salida_0 = str_replace("&y&&", "-y-", $salida_0);
    $salida_final = str_replace("--", "-", str_replace("---", "-", $salida_0));

    return $salida_final;
}

function limpiar_enlace2($String) {
    $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
    $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
    $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
    $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
    $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
    $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
    $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
    $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
    $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
    $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
    $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
    $String = str_replace("ç", "c", $String);
    $String = str_replace("Ç", "C", $String);
    $String = str_replace("ñ", "n", $String);
    $String = str_replace("Ñ", "N", $String);
    $String = str_replace("Ý", "Y", $String);
    $String = str_replace("ý", "y", $String);

    $String = str_replace("&aacute;", "a", $String);
    $String = str_replace("&Aacute;", "A", $String);
    $String = str_replace("&eacute;", "e", $String);
    $String = str_replace("&Eacute;", "E", $String);
    $String = str_replace("&iacute;", "i", $String);
    $String = str_replace("&Iacute;", "I", $String);
    $String = str_replace("&oacute;", "o", $String);
    $String = str_replace("&Oacute;", "O", $String);
    $String = str_replace("&uacute;", "u", $String);
    $String = str_replace("&Uacute;", "U", $String);

    $busc = array('Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', 'Ã±', 'Ã?', 'Ã‰', 'Ã?', 'Ã“', 'Ãš', 'Ã‘', ' ', '?', ',', '/', '%', '.', ':', '@', 'Nuquot', ':', '"', "'", "acute", "quot-", "N", "-ntilde-", '(', ')', '[', ']', ':', '=');
    $remm = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', '-', '', '', '', '', '', '', '', '-', ':', '', '', "", "-", "-", "n", '-', '-', '-', '-', '', '');
    $salida_0 = str_replace($busc, $remm, $String);
    $salida_0 = str_replace("-y-", "&y&&", $salida_0);
    $salida_0 = str_replace("y-", "-", $salida_0);
    $salida_0 = str_replace("&y&&", "-y-", $salida_0);
    $salida_final = str_replace("--", "-", str_replace("---", "-", $salida_0));

    return $salida_final;
}

/* post */
function isset_post($name) {
    if (isset($_POST[$name])) {
        if ($_POST[$name] !== '') {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function post($name) {
    global $mysqli;
    $return = "";
    if (isset($_POST[$name])) {
        $return = mysqli_real_escape_string($mysqli, strip_tags(str_replace("'",'',$_POST[$name])));
    }
    return $return;
}

function post_array($name) {
    $return = array();
    if (isset($_POST[$name])) {
        $return = $_POST[$name];
    }
    return $return;
}

function post_html($name) {
    global $mysqli;
    $return = "";
    if (isset($_POST[$name])) {
        $return = mysqli_real_escape_string($mysqli, $_POST[$name]);
    }
    return $return;
}

function get($name) {
    global $mysqli;
    $return = "";
    if (isset($_GET[$name])) {
        $return = mysqli_real_escape_string($mysqli,strip_tags($_GET[$name]));
    }
    return $return;
}

function isset_get($name) {
    if (isset($_GET[$name])) {
        return true;
    } else {
        return false;
    }
}

function post_in($cad) {
    $busc = array(';', '`', 'TRUNCATE', 'DELETE', 'UPDATE', 'SELECT', 'truncate', 'delete', 'update', 'select');
    $remm = array('', '', '', '', '', '', '', '', '', '');
    return str_replace($busc, $remm, $cad);
}

function real_escape_string($dat) {
    global $mysqli;
    return mysqli_real_escape_string($mysqli, $dat);
}

function unAcent($cadena) {
    $String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $cadena);
    $String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "A", $String);
    $String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "I", $String);
    $String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
    $String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
    $String = str_replace(array('É', 'È', 'Ê', 'Ë'), "E", $String);
    $String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
    $String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "O", $String);
    $String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
    $String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "U", $String);
    $String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
    $String = str_replace("ç", "c", $String);
    $String = str_replace("Ç", "C", $String);
    $String = str_replace("ñ", "n", $String);
    $String = str_replace("Ñ", "N", $String);
    $String = str_replace("Ý", "Y", $String);
    $String = str_replace("ý", "y", $String);

    return $String;
}

function encriptar($string) {
    $key = "s8djeuy";
    $result = "";
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result.=$char;
    }
    return base64_encode($result);
}

function desencriptar($string) {
    $key = "s8djeuy";
    $result = "";
    $string = base64_decode($string);
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result.=$char;
    }
    return $result;
}

function query($sql) {
    global $mysqli;

    $ret = null;
    if($_ENV['APP_MODE'] == 'production') {
        $url_actual = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_actual = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_actual = $_SERVER['REMOTE_ADDR'];
        }
    
        $cabeceras = 'From: Alertas@infosicoes.com' . "\r\n" .
            'Reply-To: ' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() .
            'Return-Path: ' . 'brayan.desteco@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $ret = mysqli_query($mysqli, $sql) or die(mail("brayan.desteco@gmail.com", 'Error SQL ' . date("Y-m-d H:i:s"), "<div>Error SQL en:<br/> $url_actual <hr/><br/>" . mysqli_error($mysqli) . "<hr/><br/>$sql<br/><hr/>IP: $ip_actual</div>", $cabeceras) . "<hr/><br/><b>Se ha producido un error</b> lamentamos la molestia, lo solucionaremos lo antes posible.<br/><hr/>");
    } else {
        //mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
        try {
            $ret = mysqli_query($mysqli, $sql) or die("<hr><br>ERROR de SQL:<br>" . mysqli_error($mysqli) . "<hr><br>SQL:<br>" . $sql);
        } catch (mysqli_sql_exception $e) {
            echo "<hr><b>ERROR de SQL:</b><br><span style='font-size:14pt;color:red;'>" . mysqli_error($mysqli) . "</span><hr><b>SQL:</b><br><span style='font-size:14pt;'>" . $sql. "</span><hr><b>Stack Trace:</b><br>" . $e;
            exit;
        }
    }
    return $ret;
}

function fetch($result) {
    $ret = mysqli_fetch_array($result);
    return $ret;
}
function num_rows($result) {
    $ret = mysqli_num_rows($result);
    return $ret;
}
function insert_id() {
    global $mysqli;
    $ret = mysqli_insert_id($mysqli);
    return $ret;
}


function url($cad) {
    echo $cad . '.adm';
}

function to_url($cad) {
    return $cad . '.adm';
}

function archivo($nombre) {
    if (is_uploaded_file($_FILES[$nombre]['tmp_name'])) {
        return $_FILES[$nombre]['tmp_name'];
    } else {
        return false;
    }
}

function isset_archivo($nombre) {
    if (is_uploaded_file($_FILES[$nombre]['tmp_name'])) {
        return true;
    } else {
        return false;
    }
}

function archivoName($nombre) {
    if (is_uploaded_file($_FILES[$nombre]['tmp_name'])) {
        return $_FILES[$nombre]['name'];
    } else {
        return false;
    }
}
// getArchivoType
function getArchivoType($nombre) {
    if (is_uploaded_file($_FILES[$nombre]['tmp_name'])) {
        $array = explode('/',$_FILES[$nombre]['type']);
        return end($array);
    } else {
        return null;
    }
}

/* redimencionar imagen */
function sube_imagen($imagen, $destino) {

    /* move_uploaded_file($imagen, $nombre_imagen); */
    /* Ruta de la imagen original */
    $rutaImagenOriginal = $imagen;

/* Ancho y alto de la imagen original */
    list($ancho, $alto, $tipo_img) = getimagesize($rutaImagenOriginal);

/* Creamos una variable imagen a partir de la imagen original */
    switch ($tipo_img) {
        case 1:
            $img_original = imagecreatefromgif($rutaImagenOriginal);
            break;
        case 2:
            $img_original = imagecreatefromjpeg($rutaImagenOriginal);
            break;
        case 3:
            $img_original = imagecreatefrompng($rutaImagenOriginal);
            break;
        case 15:
            $img_original = imagecreatefromwbmp($rutaImagenOriginal);
            break;
        default:
            $img_original = imagecreatefromjpeg($rutaImagenOriginal);
            break;
    }

/* Se define el maximo ancho y alto que tendra la imagen final */
    $max_ancho = 1300;
    $max_alto = 900;

/* Se calcula ancho y alto de la imagen final */
    $x_ratio = $max_ancho / $ancho;
    $y_ratio = $max_alto / $alto;

/* Si el ancho y el alto de la imagen no superan los maximos, */
/* ancho final y alto final son los que tiene actualmente */
    if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {//Si ancho
        $ancho_final = $ancho;
        $alto_final = $alto;
    }
    /*
     * si proporcion horizontal*alto mayor que el alto maximo,
     * alto final es alto por la proporcion horizontal
     * es decir, le quitamos al ancho, la misma proporcion que
     * le quitamos al alto
     *
     */ elseif (($x_ratio * $alto) < $max_alto) {
        $alto_final = ceil($x_ratio * $alto);
        $ancho_final = $max_ancho;
    }
    /*
     * Igual que antes pero a la inversa
     */ else {
        $ancho_final = ceil($y_ratio * $ancho);
        $alto_final = $max_alto;
    }

/* Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final . */
    $tmp = imagecreatetruecolor($ancho_final, $alto_final);

/* Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp) */
    imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

/* Se destruye variable $img_original para liberar memoria */
    imagedestroy($img_original);

/* Definimos la calidad de la imagen final */
    $calidad = 100;
/* Se crea la imagen final en el directorio indicado */
    imagejpeg($tmp, $destino, $calidad);
}

function my_date_time($cad) {
    if (strlen($cad) > 15) {
        $arr1 = explode(' ', $cad);
        if (date("Y-m-d") == $arr1[0]) {
            $fecha = "Hoy!";
        } else {
            $arr2 = explode('-', $arr1[0]);
            $fecha = $arr2[2] . '/' . $arr2[1] . '/' . $arr2[0];
        }
        $cad = $fecha . ' ' . substr($arr1[1], 0, 5);
    }
    return $cad;
}

/* administradores */
function isset_administrador() {
    if (isset($_SESSION['admin_id'])) {
        $return = true;
    } else {
        $return = false;
    }
    return $return;
}

function administrador($name) {
    if (isset($_SESSION['admin_' . $name])) {
        $return = $_SESSION['admin_' . $name];
    } else {
        $return = 'Not-Exist';
    }
    return $return;
}

function administradorSet($name, $dat) {
    $_SESSION['admin_' . $name] = $dat;
}

/* usuarios */

function isset_usuario() {
    if (isset($_SESSION['user_id'])) {
        $return = true;
    } else {
        $return = false;
    }
    return $return;
}

function usuario($name) {
    if (isset($_SESSION['user_' . $name])) {
        $return = $_SESSION['user_' . $name];
    } else {
        $return = 'Not-Exist';
    }
    return $return;
}

function usuarioSet($name, $dat) {
    $_SESSION['user_' . $name] = $dat;
}

/* docentes */

function isset_docente() {
    if (isset($_SESSION['doc_id'])) {
        $return = true;
    } else {
        $return = false;
    }
    return $return;
}

function docente($name) {
    if (isset($_SESSION['doc_' . $name])) {
        $return = $_SESSION['doc_' . $name];
    } else {
        $return = 'Not-Exist';
    }
    return $return;
}

function docenteSet($name, $dat) {
    $_SESSION['doc_' . $name] = $dat;
}

/* organizadores */

function isset_organizador() {
    if (isset($_SESSION['organizador_id'])) {
        $return = true;
    } else {
        $return = false;
    }
    return $return;
}

function organizador($name) {
    if (isset($_SESSION['organizador_' . $name])) {
        $return = $_SESSION['organizador_' . $name];
    } else {
        $return = 'Not-Exist';
    }
    return $return;
}

function organizadorSet($name, $dat) {
    $_SESSION['organizador_' . $name] = $dat;
}

/* editor */
function tinyAdmin() {
    ?>
    <script src="contenido/librerias/tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            language: 'es',
            theme: "modern",
            mode: "textareas",
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak table",
                "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media nonbreaking emoticons textcolor",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            elements: "formulario",
            theme_advanced_buttons1: "mybutton,bold,italic,underline,forecolor,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink,cleanup,code",
            theme_advanced_buttons2: "formatselect,fontselect,fontsizeselect,separator,pastetext,pasteword,selectall,image,media,preview",
            theme_advanced_buttons3: "",
            theme_advanced_toolbar_location: "top",
            plugin_preview_width: "700",
            plugin_preview_height: "450",
            paste_auto_cleanup_on_paste: true,
            relative_urls: false,
            toolbar: "insertfile undo redo preview | fontselect | fontsizeselect | forecolor backcolor emoticons | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | media fullpage",
            fontsize_formats: "9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 18pt 20pt 22pt 24pt",
            font_formats: "Andale Mono=andale mono,times;" +
                    "Arial=arial,helvetica,sans-serif;" +
                    "Arial Black=arial black,avant garde;" +
                    "Book Antiqua=book antiqua,palatino;" +
                    "Comic Sans MS=comic sans ms,sans-serif;" +
                    "Courier New=courier new,courier;" +
                    "Georgia=georgia,palatino;" +
                    "Helvetica=helvetica;" +
                    "Impact=impact,chicago;" +
                    "Symbol=symbol;" +
                    "Tahoma=tahoma,arial,helvetica,sans-serif;" +
                    "Terminal=terminal,monaco;" +
                    "Times New Roman=times new roman,times;" +
                    "Trebuchet MS=trebuchet ms,geneva;" +
                    "Verdana=verdana,geneva;" +
                    "Webdings=webdings;" +
                    "Wingdings=wingdings,zapf dingbats",
        });
    </script>
    <?php
}

/* editor | elemento */
function editorTinyMCE($id_elemento) {
    global $dominio_www;
    ?><script src="<?php echo $dominio_www; ?>contenido/librerias/tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            language: 'es',
            theme: "modern",
            mode: "exact",
            plugins: [
                "advlist autolink link image lists charmap  preview hr anchor pagebreak table",
                "searchreplace visualblocks visualchars fullscreen insertdatetime media nonbreaking emoticons textcolor",
                "save table contextmenu directionality emoticons template paste textcolor imgsurfer"
            ],
            elements: "<?php echo $id_elemento; ?>",
            theme_advanced_buttons1: "mybutton,bold,italic,underline,forecolor,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink,cleanup,code",
            theme_advanced_buttons2: "formatselect,fontselect,fontsizeselect,separator,pastetext,pasteword,selectall,image,media,preview",
            theme_advanced_buttons3: "",
            theme_advanced_toolbar_location: "top",
            plugin_preview_width: "700",
            plugin_preview_height: "450",
            paste_auto_cleanup_on_paste: true,
            relative_urls: false,
            toolbar: "insertfile undo redo preview | fontselect | fontsizeselect | forecolor backcolor emoticons | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | media fullpage | imgsurfer",
            fontsize_formats: "9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 18pt 20pt 22pt 24pt",
            font_formats: "Andale Mono=andale mono,times;" +
                    "Arial=arial,helvetica,sans-serif;" +
                    "Arial Black=arial black,avant garde;" +
                    "Book Antiqua=book antiqua,palatino;" +
                    "Comic Sans MS=comic sans ms,sans-serif;" +
                    "Courier New=courier new,courier;" +
                    "Georgia=georgia,palatino;" +
                    "Helvetica=helvetica;" +
                    "Impact=impact,chicago;" +
                    "Symbol=symbol;" +
                    "Tahoma=tahoma,arial,helvetica,sans-serif;" +
                    "Terminal=terminal,monaco;" +
                    "Times New Roman=times new roman,times;" +
                    "Trebuchet MS=trebuchet ms,geneva;" +
                    "Verdana=verdana,geneva;" +
                    "Webdings=webdings;" +
                    "Wingdings=wingdings,zapf dingbats",
        });
    </script><?php
}

/* agrega movimientos */
function movimiento($movimiento, $proceso, $objeto, $id_objeto) {
    if (isset_administrador()) {
        $id_usuario = administrador('id');
        $usuario = 'administrador';
    } elseif (isset_usuario()) {
        $id_usuario = usuario('id');
        $usuario = 'usuario';
    } else {
        $id_usuario = '0';
        $usuario = 'sin-sesion';
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_registro = $_SERVER['REMOTE_ADDR'];
    }
    $fecha = date("Y-m-d H:i:s", time());
    $r1 = query("INSERT INTO movimiento (movimiento,proceso,objeto,id_objeto,usuario,id_usuario,ip,fecha) VALUES ('$movimiento','$proceso','$objeto','$id_objeto','$usuario','$id_usuario','$ip_registro','$fecha') ");
    if ($r1) {
        return true;
    } else {
        return false;
    }
}

/* LOG DE MOVIMIENTOS */
function logcursos($movimiento, $proceso, $objeto, $id_objeto) {
    if (isset_administrador()) {
        $id_usuario = administrador('id');
        $usuario = 'administrador';
    } else {
        $id_usuario = '0';
        $usuario = 'SIS-SESION';
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_registro = $_SERVER['REMOTE_ADDR'];
    }
    $fecha = date("Y-m-d H:i:s", time());
    $r1 = query("INSERT INTO cursos_log (movimiento,proceso,objeto,id_objeto,usuario,id_usuario,ip,fecha) VALUES ('$movimiento','$proceso','$objeto','$id_objeto','$usuario','$id_usuario','$ip_registro','$fecha') ");
}
/* LOG DE MOVIMIENTOS CON ADMIN SETEADO */
function logcursosADM($movimiento, $proceso, $objeto, $id_objeto, $id_administrador) {
    $id_usuario = $id_administrador;
    $usuario = 'administrador';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_registro = $_SERVER['REMOTE_ADDR'];
    }
    $fecha = date("Y-m-d H:i:s", time());
    query("INSERT INTO cursos_log (movimiento,proceso,objeto,id_objeto,usuario,id_usuario,ip,fecha) VALUES ('$movimiento','$proceso','$objeto','$id_objeto','$usuario','$id_usuario','$ip_registro','$fecha') ");
}

/* LOADPAGES AJAX FUNCIONALIDAD */
function loadpage($url_data){
    $ar1 = explode('/',$url_data);
    $page = $ar1[0];
    unset($ar1[0]);
    $data = implode('/',$ar1);
    return ' href="'.$url_data.'.adm" onclick="load_page(\''.$page.'\',\''.$data.'\',\'\');return false;" ';
}

/* ACCESO DE PAGINAS */
function acceso($niveles) {
    if (isset_administrador()) {
        $arr = explode(',', $niveles);
        $nivel_actual = administrador('nivel');
        $sw_acces = false;
        foreach ($arr as $nivel) {
            if ($nivel !== '' && ((int) $nivel == (int) $nivel_actual)) {
                $sw_acces = true;
            }
        }
        if (!$sw_acces) {
            echo "<h1>ACCESO DENEGADO!</h1>";
            exit;
        }
    } else {
        echo "<h1>Acceso denegado!</h1>";
        exit;
    }
}

/* OBTENER ACCESO LINEAL */
function get_acceso($niveles) {
    if (isset_administrador()) {
        $arr = explode(',', $niveles);
        $nivel_actual = administrador('nivel');
        $sw_acces = false;
        foreach ($arr as $nivel) {
            if ($nivel !== '' && ((int) $nivel == (int) $nivel_actual)) {
                $sw_acces = true;
            }
        }
        return $sw_acces;
    } else {
        return false;
    }
}

/* OBTENER ACCESO DINAMICO */
function acceso_cod($cod_privilegio) {
    if (isset_administrador()) {
        $rqpa1 = query("SELECT ids_nivel_administrador FROM cursos_privilegios WHERE cod_privilegio='$cod_privilegio' ORDER BY id ASC limit 1 ");
        if (num_rows($rqpa1) > 0) {
            $rqpa2 = fetch($rqpa1);
            $ids_nivel_administrador = $rqpa2['ids_nivel_administrador'];
            $arr = explode(',', $ids_nivel_administrador);
            $rqna1 = query("SELECT nivel FROM administradores WHERE id='" . administrador('id') . "' ORDER BY id ASC limit 1 ");
            $rqna2 = fetch($rqna1);
            $nivel_actual = $rqna2['nivel'];
            $sw_acces = false;
            foreach ($arr as $nivel_administrador_permitido) {
                if ($nivel_administrador_permitido !== '0' && $nivel_administrador_permitido !== '' && ((int) $nivel_actual == (int) $nivel_administrador_permitido)) {
                    $sw_acces = true;
                }
            }
            return $sw_acces;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/* RECAPTCHA */
class ReCaptchaResponse {

    public $success;
    public $errorCodes;

}

class ReCaptcha {

    private static $_signupUrl = "https://www.google.com/recaptcha/admin";
    private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";
    private $_secret;
    private static $_version = "php_1.0";

    /**
     * Constructor.
     *
     * @param string $secret shared secret between site and ReCAPTCHA server.
     */

    //****function ReCaptcha($secret) {
    function __construct($secret) {
            if ($secret == null || $secret == "") {
            die("To use reCAPTCHA you must get an API key from <a href='"
                    . self::$_signupUrl . "'>" . self::$_signupUrl . "</a>");
        }
        $this->_secret = $secret;
    }

    /**
     * Encodes the given data into a query string format.
     *
     * @param array $data array of string elements to be encoded.
     *
     * @return string - encoded request.
     */
    private function _encodeQS($data) {
        $req = "";
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }
        /*  Cut the last '&' */
        $req = substr($req, 0, strlen($req) - 1);
        return $req;
    }

    /**
     * Submits an HTTP GET to a reCAPTCHA server.
     *
     * @param string $path url path to recaptcha server.
     * @param array  $data array of parameters to be sent.
     *
     * @return array response
     */
    private function _submitHTTPGet($path, $data) {
        $req = $this->_encodeQS($data);
        $response = file_get_contents($path . $req);
        return $response;
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $remoteIp   IP address of end user.
     * @param string $response   response string from recaptcha verification.
     *
     * @return ReCaptchaResponse
     */
    public function verifyResponse($remoteIp, $response) {
        // Discard empty solution submissions
        if ($response == null || strlen($response) == 0) {
            $recaptchaResponse = new ReCaptchaResponse();
            $recaptchaResponse->success = false;
            $recaptchaResponse->errorCodes = 'missing-input';
            return $recaptchaResponse;
        }
        $getResponse = $this->_submitHttpGet(
                self::$_siteVerifyUrl, array(
            'secret' => $this->_secret,
            'remoteip' => $remoteIp,
            'v' => self::$_version,
            'response' => $response
                )
        );
        $answers = json_decode($getResponse, true);
        $recaptchaResponse = new ReCaptchaResponse();
        if (trim($answers ['success']) == true) {
            $recaptchaResponse->success = true;
        } else {
            $recaptchaResponse->success = false;
            //$recaptchaResponse->errorCodes = $answers [error - codes];
        }
        return $recaptchaResponse;
    }
}


/* envio de email por phpmailer */
function envio_email($correo, $subject, $body) {
    SISTsendEmail($correo, $subject, $body);
}


/* envio de email por phpmailer [sicoes.com.bo] */
function envio_email_cursos_sicoes($correo, $subject, $body) {
    SISTsendEmail($correo, $subject, $body);
}


function encrypt($string) {
    $key = "ISicoes-4215";
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result.=$char;
    }
    return urlencode(str_replace('+', 'plus', base64_encode($result)));
}

function decrypt($string) {
    $key = "ISicoes-4215";
    $result = '';
    $string = base64_decode(str_replace('plus', '+', urldecode($string)));
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result.=$char;
    }
    return $result;
}

/* funcion static_ghtmls */

function static_ghtmls() {
    /* variables iniciales */
    $ruta_htmls = 'contenido/htmls/';
    $prefijo_de_archivo = 'DAT_';
    $formato = '.txt';
    /* sw htmls */
    $sw_ghtmls = false;
    if (file_exists("contenido/configuracion/sw_ghtmls.dat")) {
        $fp = fopen("contenido/configuracion/sw_ghtmls.dat", "r");
        $linea = (int) fgets($fp);
        fclose($fp);
        if ($linea == 1) {
            $sw_ghtmls = true;
        } else {
            $sw_ghtmls = false;
        }
    }
    if ($sw_ghtmls && !isset($_GET['seccion']) && !isset($_GET['dir']) && !isset_usuario()) {
        /* genera : index */
        $nombre_denominador = 'index';
        $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
        if (file_exists($ruta_final_archivo)) {
            $fichero_texto = fopen($ruta_final_archivo, "r");
            $contenido_fichero = fread($fichero_texto, filesize($ruta_final_archivo));
            echo $contenido_fichero;
            /* finalizado de ejecucion */
            exit;
        }
    } elseif ($sw_ghtmls && isset($_GET['seccion']) && !isset_usuario() && true) {
        $urlroute = trim(strip_tags($_GET['seccion']));
        $nombre_denominador = $urlroute;
        $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
        if (file_exists($ruta_final_archivo)) {
            $fichero_texto = fopen($ruta_final_archivo, "r");
            $contenido_fichero = fread($fichero_texto, filesize($ruta_final_archivo));
            echo str_replace('</body>','<script>window.onload = function() {send_static_view(\''.$urlroute.'\');}</script></body>',$contenido_fichero);
            /* finalizado de ejecucion */
            exit;
        }
    }
}

/* hash de password */
function hash_password($dat){
    return md5(md5($dat).'infos');
}
/* END hash de password */


/* numeros a letras */
function numToLiteral($xcifra) {
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto);
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2);
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT);
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6;
        $xexit = true;
        while ($xexit) {
            if ($xi == $xlimite) {
                break;
            }
            $x3digitos = ($xlimite - $xi) * -1;
            $xaux = substr($xaux, $x3digitos, abs($x3digitos));
            for ($xy = 1; $xy < 4; $xy++) {
                switch ($xy) {
                    case 1:
                        if (substr($xaux, 0, 3) < 100) {
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo_NL($xaux);
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key];
                                $xcadena = " " . $xcadena . " " . $xseek;
                            }
                        }
                        break;
                    case 2:
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo_NL($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            }
                        }
                        break;
                    case 3:
                        if (substr($xaux, 2, 1) < 1) {
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key];
                            $xsub = subfijo_NL($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        }
                        break;
                }
            }
            $xi = $xi + 3;
        }
        if (substr(trim($xcadena), -5, 5) == "ILLON")
            $xcadena.= " DE";
        if (substr(trim($xcadena), -7, 7) == "ILLONES")
            $xcadena.= " DE";
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO   $xdecimales/100 BOLIVIANOS";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 BOLIVIANOS ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= "   $xdecimales/100 BOLIVIANOS "; //
                    }
                    break;
            }
        }
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena);
        $xcadena = str_replace("  ", " ", $xcadena);
        $xcadena = str_replace("UN UN", "UN", $xcadena);
        $xcadena = str_replace("  ", " ", $xcadena);
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena);
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena);
        $xcadena = str_replace("DE UN", "UN", $xcadena);
    } // ENDFOR ($xz)
    return trim($xcadena);
}

function subfijo_NL($xx) {
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    return $xsub;
}

/* OBTIENE ID DE CERTIFICADO */
function getIDcert($cod_i_ciudad) {
    $sw_id_notfounded = true;
    do {
        $certificado_id = $cod_i_ciudad . str_replace(array('1','0','5'),array('R','T','K'), str_pad(rand(1, 99999), 5, "0", STR_PAD_LEFT));
        $rqv1 = query("SELECT count(1) AS total FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' LIMIT 1 ");
        $rqv2 = fetch($rqv1);
        if ($rqv2['total'] == 0) {
            $sw_id_notfounded = false;
        }
    } while ($sw_id_notfounded);
    return $certificado_id;
}

/* CONTENIDO DE CURSO */
function getContenidoCurso($curso, $sw_mostrar_precios = true) {
    global $dominio,$dominio_www,$___nombre_del_sitio;

    /* datos lugar */
    $rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
    $rqdl2 = fetch($rqdl1);
    $lugar_nombre = $rqdl2['nombre'];
    $lugar_salon = $rqdl2['salon'];
    $lugar_direccion = $rqdl2['direccion'];
    $lugar_google_maps = $rqdl2['google_maps'];

    /* ciudad departemento */
    $curso_id_ciudad = $curso['id_ciudad'];
    $rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
    $rqdcd2 = fetch($rqdcd1);
    $curso_nombre_departamento = $rqdcd2['departamento'];
    $curso_nombre_ciudad = $rqdcd2['ciudad'];
    $curso_text_ciudad = $curso_nombre_ciudad;
    if ($curso_nombre_departamento !== $curso_nombre_ciudad) {
        $curso_text_ciudad = $curso_nombre_ciudad . ' - ' . $curso_nombre_departamento;
    }

    /* datos docente */
    $docente_nombre = '';
    $docente_curriculum = '';

    if ($curso['sw_v_expositor'] == '1') {
        $rqdrd1 = query("SELECT prefijo,nombres,curriculum FROM cursos_docentes WHERE id='" . $curso['id_docente'] . "' ORDER BY id ASC ");
        $rqdrd2 = fetch($rqdrd1);
        $docente_nombre = $rqdrd2['prefijo'] . ' ' . $rqdrd2['nombres'];
        $docente_curriculum = $rqdrd2['curriculum'];
    }

    $htm_imagen1 = '';
    if ($curso['imagen'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/paginas/large-" . str_replace('+','%20',urlencode($curso['imagen']));
        $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen2 = '';
    if ($curso['imagen2'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/paginas/large-" . str_replace('+','%20',urlencode($curso['imagen2']));
        $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen3 = '';
    if ($curso['imagen3'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/paginas/large-" . str_replace('+','%20',urlencode($curso['imagen3']));
        $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen4 = '';
    if ($curso['imagen4'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/paginas/large-" . str_replace('+','%20',urlencode($curso['imagen4']));
        $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_archivo1 = '';
    if ($curso['archivo1'] !== '') {
        $url_img = $dominio_www."contenido/archivos/cursos/" . $curso['archivo1'];
        $htm_archivo1 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo1'] . '</a>';
    }
    $htm_archivo2 = '';
    if ($curso['archivo2'] !== '') {
        $url_img = $dominio_www."contenido/archivos/cursos/" . $curso['archivo2'];
        $htm_archivo2 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo2'] . '</a>';
    }
    $htm_archivo3 = '';
    if ($curso['archivo3'] !== '') {
        $url_img = $dominio_www."contenido/archivos/cursos/" . $curso['archivo3'];
        $htm_archivo3 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo3'] . '</a>';
    }
    $htm_archivo4 = '';
    if ($curso['archivo4'] !== '') {
        $url_img = $dominio_www."contenido/archivos/cursos/" . $curso['archivo4'];
        $htm_archivo4 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo4'] . '</a>';
    }
    $htm_archivo5 = '';
    if ($curso['archivo5'] !== '') {
        $url_img = $dominio_www."contenido/archivos/cursos/" . $curso['archivo5'];
        $htm_archivo5 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo5'] . '</a>';
    }
    $htm_reportesupago = '<a href="'.$dominio.'registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="'.$dominio.'contenido/imagenes/images/reporte-su-pago.png" style=""/></a>';
    $htm_inscripcion = '<div style="text-align:center;"><a href="'.$dominio.'registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="https://www.carreramenudoscorazones.es/wp-content/uploads/2015/04/BOTON_INSCRIPCION.jpg" style="height:120px;"/></a></div>';
    
    
        
    $mensaje_wamsm_predeternimado = $___nombre_del_sitio.' Hola, tengo interes en el Curso ' . trim(str_replace(array('curso','Curso','CURSO'), '', $curso['titulo']));
    $numero_wamsm_predeternimado = '69714008';
    $htm_whatsapp = '';
    $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $curso['id'] . "' ORDER BY r.id ASC ");
    if (num_rows($rqdwn1) == 0) {
        $numero_wamsm_predeternimado = '69714008';
        $cel_wamsm = $numero_wamsm_predeternimado;
        $url_whatsapp = 'https://api.whatsapp.com/send?phone=591' . $numero_wamsm_predeternimado . '&text=' . str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado));
        $htm_whatsapp .= '<a href="'.$url_whatsapp.'" style="background: #f7f7f7;padding: 7px 10px 5px 10px;font-size: 25pt;color: #1ca110;font-weight: bold;border-radius: 5px;border: 1px solid green;text-decoration: none;">
                    <img src="'.$dominio.'contenido/imagenes/images/wap1.jpg" style="height: 30px;"/>'.$numero_wamsm_predeternimado.'
                </a>';
    }
    while($rqdwn2 = fetch($rqdwn1)){
        $cel_wamsm = $rqdwn2['numero'];
        $url_whatsapp = 'https://api.whatsapp.com/send?phone=591' . $rqdwn2['numero'] . '&text=' . str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado));
        $htm_whatsapp .= '<a href="'.$url_whatsapp.'" style="background: #f7f7f7;padding: 7px 10px 5px 10px;font-size: 25pt;color: #1ca110;font-weight: bold;border-radius: 5px;border: 1px solid green;text-decoration: none;">
                    <img src="'.$dominio.'contenido/imagenes/images/wap1.jpg" style="height: 30px;"/>'.$rqdwn2['numero'].'
                </a><br><br>';
    }    
    
    $data_nombre_curso = ($curso['titulo']);
    $data_ciudad_curso = $curso_text_ciudad;
    $data_fecha_curso = fecha_curso_D_d_m($curso['fecha']);
    $data_horarios_curso = $curso['horarios'];
    $data_lugar_curso = $lugar_nombre;
    $data_lugar_salon_curso = $lugar_nombre . ' - ' . $lugar_salon;
    $data_direccion_lugar_curso = $lugar_direccion;
    $data_costo_bs_curso = $curso['costo'] . ' Bs';
    $data_costo_literal_curso = numToLiteral($curso['costo']);
    $data_numero_celular = $cel_wamsm;
    $txt_descuento_uno_curso = '';
    $txt_descuento_dos_curso = '';
    $txt_descuento_est_curso = '';
    $txt_descuento_est_pre_curso = '';
    if ($curso['sw_fecha2'] == '1') {
        $txt_descuento_uno_curso = '<b style="color: #ff0000;">DESCUENTO :</b> '.$curso['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha2']) . hora_descuento($curso['fecha2']);
    }
    if ($curso['sw_fecha3'] == '1') {
        $txt_descuento_dos_curso = '<b style="color: #ff0000;">DESCUENTO :</b> '.$curso['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha3']) . hora_descuento($curso['fecha3']);
    }
    if ($curso['sw_fecha_e'] == '1') {
        $txt_descuento_est_curso = '<b style="color: #ff0000;">ESTUDIANTES :</b> '.$curso['costo_e'] . ' Bs '.numToLiteral($curso['costo_e']).' (Asistir con original y fotocopia de su carnet universitario o instituto)';
    }
    if ($curso['sw_fecha_e2'] == '1') {
        $txt_descuento_est_pre_curso = '<b style="color: #ff0000;">DESCUENTO ESTUDIANTES :</b> '.$curso['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e2']) . hora_descuento($curso['fecha_e2']) . ' para estudiantes. (Asistir con original y fotocopia de su carnet universitario o instituto)';
    }


    $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE b.estado=1 AND c.estado=1 AND r.id_curso='".$curso['id']."' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
    $rqdtcb2 = fetch($rqdtcb1);
    $data_nombre_banco = $rqdtcb2['nombre_banco'];
    $data_numero_cuenta_banco = $rqdtcb2['numero_cuenta'];
    $data_titular_banco = $rqdtcb2['titular'];

    /* [INFO-PAGO-CUENTAS-BANCARIAS] */
    $data_info_pago_cuentas_bancarias = '<div style="font-size: 12.5pt;line-height: 2;font-weight: bold;color: #000;">';
    $data_info_pago_cuentas_bancarias .= '<b style="color: #ff0000;">PAGO MEDIANTE TRANSFERENCIA BANCARIA, GIRO TIGOMONEY o DEPOSITO BANCARIO</b><br><b style="color: #ff0000;">CUENTA BANCARIAS:</b><br>';
    $rqcdbe1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE b.estado=1 AND c.estado=1 AND r.id_curso='".$curso['id']."' AND r.sw_transbancunion=0 AND r.estado=1 ORDER BY c.id ASC ");
    while($rqcdbe2 = fetch($rqcdbe1)){
        $data_info_pago_cuentas_bancarias .= $rqcdbe2['nombre_banco'].' <span style="color: #ff0000;">A nombre de :</span> '.$rqcdbe2['titular'].'   <span style="color: #ff0000;">cuenta</span> '.$rqcdbe2['numero_cuenta'].'<br>';
    }
    $rqcdbdbu1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE b.estado=1 AND c.estado=1 AND r.id_curso='".$curso['id']."' AND r.sw_transbancunion=1 AND r.estado=1 ORDER BY c.id ASC ");
    if(num_rows($rqcdbdbu1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">TRANSFERENCIA DESTE CAJERO BANCO UNION:</b><br>';
        while($rqcdbdbu2 = fetch($rqcdbdbu1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">Datos cuenta <b>'.$rqcdbdbu2['nombre_banco'].'</b> <b style="color: #0000ff;">'.$rqcdbdbu2['numero_cuenta'].'</b> '.$rqcdbdbu2['tipo_cuenta'].' <b>'.strtoupper($rqcdbdbu2['titular']).'</b></span><br>';
        }
    }
    $rqcddt1 = query("SELECT c.* FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id WHERE r.id_curso='".$curso['id']."' AND r.estado=1 GROUP BY c.datos_adicionales ORDER BY c.id ASC ");
    if(num_rows($rqcddt1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">DATOS PARA TRANSFERENCIA:</b><br>';
        while($rqcddt2 = fetch($rqcddt1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">Cuenta '.$rqcddt2['tipo_cuenta'].' <b>'.strtoupper($rqcddt2['titular']).'</b> ('.$rqcddt2['datos_adicionales'].')</span><br>';
        }
    }
    $rqcntm1 = query("SELECT t.* FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros t ON r.id_numtigomoney=t.id WHERE r.id_curso='".$curso['id']."' AND r.estado=1 ORDER BY t.id ASC ");
    if(num_rows($rqcntm1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">PAGOS POR TIGO MONEY:</b><br>';
        while($rqcntm2 = fetch($rqcntm1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">A la linea <b>'.$rqcntm2['numero'].'</b> el costo sin recargo (<b>Titular '.$rqcntm2['titular'].'</b>)<br>';
        }
    }
    $data_info_pago_cuentas_bancarias .= '</div>';

    /* condicional para ocultar el precio */
    if (!$sw_mostrar_precios) {
        $data_costo_bs_curso = "";
        $data_costo_literal_curso = "";
        $txt_descuento_uno_curso = "";
        $txt_descuento_dos_curso = "";
        $txt_descuento_est_curso = "";
        $txt_descuento_est_pre_curso = "";
    }

    
    /* palabras reservadas */
    $array_palabras_reservadas_busc = array(
        '[imagen-1]',
        '[imagen-2]',
        '[imagen-3]',
        '[imagen-4]',
        'src="/paginas',
        'registro-curso-infosicoes',
        '[ARCHIVO-1]',
        '[ARCHIVO-2]',
        '[ARCHIVO-3]',
        '[ARCHIVO-4]',
        '[ARCHIVO-5]',
        '[REPORTE-SU-PAGO]',
        '[INSCRIPCION]',
        '[WHATSAPP]',
        '[NUMERO-CELULAR]',
        '[NOMBRE-CURSO]',
        '[CIUDAD-CURSO]',
        '[FECHA-A1-CURSO]',
        '[HORARIOS]',
        '[LUGAR-CURSO]',
        '[LUGAR-SALON-CURSO]',
        '[DIRECCION-LUGAR]',
        '[COSTO-BS]',
        '[COSTO-LITERAL]',
        '[DESCUENTO-UNO]',
        '[DESCUENTO-DOS]',
        '[COSTO-ESTUDIANTES]',
        '[DESCUENTO-ESTUDIANTES]',
        '[DOCENTE-NOMBRE]',
        '[DOCENTE-CURRICULUM]',
        '[NOMBRE-BANCO]',
        '[CUENTA-BANCO]',
        '[TITULAR-BANCO]',
        '[INFO-PAGO-CUENTAS-BANCARIAS]'
    );
    $array_palabras_reservadas_remm = array(
        $htm_imagen1,
        $htm_imagen2,
        $htm_imagen3,
        $htm_imagen4,
        'src="https://www.infosiscon.com/paginas',
        'registro-curso',
        $htm_archivo1,
        $htm_archivo2,
        $htm_archivo3,
        $htm_archivo4,
        $htm_archivo5,
        $htm_reportesupago,
        $htm_inscripcion,
        $htm_whatsapp,
        $data_numero_celular,
        $data_nombre_curso,
        $data_ciudad_curso,
        $data_fecha_curso,
        $data_horarios_curso,
        $data_lugar_curso,
        $data_lugar_salon_curso,
        $data_direccion_lugar_curso,
        $data_costo_bs_curso,
        $data_costo_literal_curso,
        $txt_descuento_uno_curso,
        $txt_descuento_dos_curso,
        $txt_descuento_est_curso,
        $txt_descuento_est_pre_curso,
        $docente_nombre,
        $docente_curriculum,
        $data_nombre_banco,
        $data_numero_cuenta_banco,
        $data_titular_banco,
        $data_info_pago_cuentas_bancarias
    );
    $rqtgsper1 = query("SELECT titulo,contenido FROM cursos_tags_contenido ");
    while($rqtgsper2 = fetch($rqtgsper1)){
        array_push($array_palabras_reservadas_busc, $rqtgsper2['titulo']);
        array_push($array_palabras_reservadas_remm, $rqtgsper2['contenido']);
    }
    $contenido_curso = trim(str_replace($array_palabras_reservadas_busc, $array_palabras_reservadas_remm, $curso['contenido']));

    return $contenido_curso;
}


/* CONTENIDO DE CURSO */
function getContenidoBlog($curso) {
    global $dominio_www;

    $contenido_blog = trim($curso['contenido']);

    $htm_imagen1 = '';
    if ($curso['imagen'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/blog/" . str_replace('+','%20',urlencode($curso['imagen']));
        $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen2 = '';
    if ($curso['imagen2'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/blog/" . str_replace('+','%20',urlencode($curso['imagen2']));
        $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen3 = '';
    if ($curso['imagen3'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/blog/" . str_replace('+','%20',urlencode($curso['imagen3']));
        $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen4 = '';
    if ($curso['imagen4'] !== '') {
        $url_img = $dominio_www."contenido/imagenes/blog/" . str_replace('+','%20',urlencode($curso['imagen4']));
        $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }

    /* palabras reservadas */
    $array_palabras_reservadas_busc = array(
        '[imagen-1]',
        '[imagen-2]',
        '[imagen-3]',
        '[imagen-4]'
    );
    $array_palabras_reservadas_remm = array(
        $htm_imagen1,
        $htm_imagen2,
        $htm_imagen3,
        $htm_imagen4
    );
    
    return str_replace($array_palabras_reservadas_busc,$array_palabras_reservadas_remm,$contenido_blog);
}
/* fecha_curso_D_d_m */
function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}
/* hora_descuento */
function hora_descuento($fecha) {
    $text = '';
    $h = date("H:i", strtotime($fecha));
    if ($h !== '00:00') {
        $text = ' a horas: ' . $h;
    }
    return $text;
}

/* PLANTILLA DE ENVIO DE EMAIL 1 */
function platillaEmailUno($bodyEmail,$tituloEmail,$enviarAEmail,$urlUnsubscribeEmail,$nomUsuarioEmail) {
    global $dominio,$___nombre_del_sitio,$___color_base, $__CONFIG_MANAGER;

    /* datos de configuracion */
    $img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');

    $busc = array('class="img-pag-static"', 'font-size: 12pt', 'font-size: 13pt', 'font-size: 14pt', 'font-size: 15pt', 'font-size: 16pt');
    $remm = array(' style="width: 100%;" ', 'font-size: 10pt', 'font-size: 10pt', 'font-size: 10pt', 'font-size: 12pt', 'font-size: 12pt');
    $bodycont = str_replace($busc, $remm, $bodyEmail);
    $titulo_mensaje = $tituloEmail;
    $correo_a_enviar = $enviarAEmail;
    $url_unsubscribe = $urlUnsubscribeEmail;

    $cont = '<div bgcolor="#e6e6e6" style="width:100%;min-width:100%;background-color:#e6e6e6;margin:0px;padding:0px" align="center">
<table style="text-align:center;min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>

<tr>
<td align="center">
<div style="background-color:#e6e6e6">
<table style="background-color:#e6e6e6" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#e6e6e6">
<tbody>
<tr>
<td align="center">
<table style="width:612px" width="612" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td style="padding:15px 5px" valign="top" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td style="background-color:#869198;padding:1px;border-bottom: 1px solid #989898;" valign="top" bgcolor="#869198" align="center">
<table style="background-color:#869198" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#869198" align="center">
<tbody>
<tr>
<td style="background-color:#ffffff;padding:0px" valign="top" bgcolor="#ffffff" align="center">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding-top:0px;padding-bottom:0px" valign="top" align="center">

<div style="background: '.$___color_base.';padding: 20px 180px;">
<img alt="" style="display:block;height:auto!important;max-width:100%!important;" width="599" vspace="0" hspace="0" border="0" src="'.$img_logotipo_principal.'">
</div>

</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>

<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="left">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="font-family:Arial,Verdana,Helvetica,sans-serif;font-size:14px;color:#403f42;text-align:left;display:block;word-wrap:break-word;line-height:1.2;padding:10px 20px" valign="top" align="left">
<div></div>
<div>
<div>
<br>
<div><span style="font-size:14px;color:rgb(0,0,0);font-family:Arial,Verdana,Helvetica,sans-serif">Estimad@ '.$nomUsuarioEmail.',</span></div>
<div><br>
</div>
<div>
<span style="font-size:14px;color:rgb(0,0,0);font-family:Arial,Verdana,Helvetica,sans-serif">
Esperamos que usted y sus seres queridos est&eacute;n a salvo y les vaya bien en este fin de a&ntilde;o.
</span>
</div>

<div>
<br>
</div>

</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>



<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="background-color:rgb(191,191,191)" width="100%" valign="top" bgcolor="BFBFBF" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="font-family:Arial,Verdana,Helvetica,sans-serif;font-size:14px;color:#403f42;text-align:left;display:block;word-wrap:break-word;line-height:1.2;padding:10px 20px" valign="top" align="left">
<div></div>
<div>
<div>
<div style="text-align:center" align="center"><span style="font-size:20px;color:rgb(0,0,0)">' . $titulo_mensaje . '</span></div>
</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td valign="top" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding-bottom:10px;height:1px;line-height:1px" width="100%" valign="top" align="center">
<div><img alt="" style="display:block;height:1px;width:5px" width="5" vspace="0" hspace="0" height="1" border="0" src="https://ci5.googleusercontent.com/proxy/prjVWi9agcvHo6wWwSY0NoWHiaFTUW1GFE88HIUk5LrHN5aeEIX3D6pJtDlEPNI6Dvf_Ou5XHLexQ1ajT_5sVXHMGfcLsqoinYvkNDmXc8HzvBff2Y637Q=s0-d-e1-ft#https://imgssl.constantcontact.com/letters/images/1101116784221/S.gif"></div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="line-height: 1.4;font-family:Arial,Verdana,Helvetica,sans-serif;font-size:12px;color:#403f42;text-align:left;display:block;word-wrap:break-word;padding:10px 20px" valign="top" align="left">

<div>
' . $bodycont . '
</div>
<div style="text-align: center;border-top: 2px dashed gray;padding: 10px 0px;margin-top: 15px;line-height: 2;">
Ay&uacute;danos a superar los 100 mil likes en nuestra p&aacute;gina en facebook https://www.facebook.com/cursoswebbolivia
<br>
&Uacute;nete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia
</div>

</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
<tr>
<td></td>
</tr>
</tbody>
</table>
<table style="background:#ffffff;margin-left:auto;margin-right:auto;table-layout:auto!important" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="width:100%" width="100%" valign="top" align="center">
<div style="margin-left:auto;margin-right:auto;max-width:100%" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding:16px 0px" valign="top" align="center">
<table style="width:580px" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="color:#5d5d5d;font-family:Verdana,Geneva,sans-serif;font-size:12px;padding:4px 0px" valign="top" align="center">
<span>'.$___nombre_del_sitio.'<span> |
</span></span>
</span></span><span></span><span></span><span>Cursos y capacitaciones en Bolivia</span><span></span>
</td>
</tr>
<tr>
<td style="padding:10px 0px" valign="top" align="center">
<table cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="color:#5d5d5d;font-family:Verdana,Geneva,sans-serif;font-size:12px;padding:4px 0px" valign="top" align="center">
<a href="'.$dominio.'" style="color:#5d5d5d" target="_blank">Acerca de nosotros</a> | 
enviado a (' . $correo_a_enviar . ') | 
<a href="' . $url_unsubscribe . '" style="color:#5d5d5d" target="_blank">Dejar de recibir correos</a>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>';
    return $cont;
}

function urlUnsubscribe($correo){
    global $dominio;
    return $dominio.'unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html';
}

function emailValido($dat) {
    $array_correos_excepciones = array();
    if (filter_var(trim($dat), FILTER_VALIDATE_EMAIL) || (in_array(trim($dat), $array_correos_excepciones))) {
        return true;
    } else {
        return false;
    }
}

function SISTsendEmail__toerase($correo_a_enviar, $asunto, $contenido_correo) {
    if (emailValido($correo_a_enviar)) {
        $id_correo_notificador = 1;
        /*
        $arc1 = explode('@',$correo_a_enviar);
        if(in_array(trim($arc1[1]), array('hotmail.com','outlook.com','msn.com','live.com','windowslive.com'))){
            $id_correo_notificador = 3;
        }
        */
        /* datos de correo notificador */
        $rqdcn1 = query("SELECT correo,user,password,cifrado,puerto,host,nombre_remitente FROM notificadores_de_correo WHERE id='$id_correo_notificador' LIMIT 1 ");
        $rqdcn2 = fetch($rqdcn1);
        $___datamail_From = $rqdcn2['correo'];
        $___datamail_Username = $rqdcn2['user'];
        $___datamail_Password = $rqdcn2['password'];
        $___datamail_SMTPSecure = $rqdcn2['cifrado'];
        $___datamail_Port = $rqdcn2['puerto'];
        $___datamail_Host = $rqdcn2['host'];
        $___datamail_NameFrom = $rqdcn2['nombre_remitente'];

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $___datamail_Host;
            $mail->SMTPAuth = true;
            $mail->Username = $___datamail_Username;
            $mail->Password = $___datamail_Password;
            $mail->SMTPSecure = $___datamail_SMTPSecure;
            $mail->Port = $___datamail_Port;
            /* Recipients */
            $mail->setFrom($___datamail_From, $___datamail_NameFrom);
            $mail->addAddress($correo_a_enviar);
            $mail->AddReplyTo('info@nemabol.com', $___datamail_NameFrom);
            /* Content */
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = str_replace('?','',utf8_decode($asunto));
            $mail->Body = $contenido_correo;
            $mail->Send();
        } catch (phpmailerException $e) {
            echo "Message:: " . $e->errorMessage();
            mail("brayan.desteco@gmail.com", 'error exception PhmExp', 'error exception  ' . $e->errorMessage() . $correo_a_enviar.'['.$asunto.']');
        } catch (Exception $e) {
            echo "Message:: " . $e->getMessage();
            mail("brayan.desteco@gmail.com", 'error exception Exp', 'error exception  ' . $e->errorMessage() . $correo_a_enviar.'['.$asunto.']');
        }
    }else{
        echo "<hr>EMAIL INVALIDO [$correo_a_enviar] DEPURADO<hr>";
        /* depuracion de correo */
        query("UPDATE cursos_participantes SET sw_notif='0' WHERE correo LIKE '$correo_a_enviar' ");
        query("UPDATE cursos_usuarios SET sw_notif='0' WHERE email LIKE '$correo_a_enviar' ");
    }
}

function SISTsendEmail($correo_a_enviar, $asunto, $contenido_correo, $isPrimary = true) {
    SISTsendEmailFULL($correo_a_enviar, $asunto, $contenido_correo,'','',$isPrimary);
}


function SISTsendEmailFULL($correo_a_enviar, $asunto, $contenido_correo,$copias,$archivos, $isPrimary = true) {
    global $_ENV;
    if($_ENV['APP_MODE'] == 'development') {
        return;
    }
    if (emailValido($correo_a_enviar)) {
        if($isPrimary){
            /* correo amazon */
            $id_correo_notificador = 8;    
        }else{
            /* correo desde local */
            $id_correo_notificador = 1;
        }
        /*
        $arc1 = explode('@',$correo_a_enviar);
        if(in_array(trim($arc1[1]), array('hotmail.com','outlook.com','msn.com','live.com','windowslive.com'))){
            $id_correo_notificador = 3;
        }
        */
        /* datos de correo notificador */
        $rqdcn1 = query("SELECT correo,user,password,cifrado,puerto,host,nombre_remitente FROM notificadores_de_correo WHERE id='$id_correo_notificador' LIMIT 1 ");
        $rqdcn2 = fetch($rqdcn1);
        $___datamail_From = $rqdcn2['correo'];
        $___datamail_Username = $rqdcn2['user'];
        $___datamail_Password = $rqdcn2['password'];
        $___datamail_SMTPSecure = $rqdcn2['cifrado'];
        $___datamail_Port = $rqdcn2['puerto'];
        $___datamail_Host = $rqdcn2['host'];
        $___datamail_NameFrom = $rqdcn2['nombre_remitente'];
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $___datamail_Host;
            $mail->SMTPAuth = true;
            $mail->Username = $___datamail_Username;
            $mail->Password = $___datamail_Password;
            $mail->SMTPSecure = $___datamail_SMTPSecure;
            $mail->Port = $___datamail_Port;
            /* Recipients */
            $mail->setFrom($___datamail_From, $___datamail_NameFrom);
            $mail->addAddress($correo_a_enviar);
            $mail->AddReplyTo('info@nemabol.com', $___datamail_NameFrom);
            if($copias!=''){
                foreach ($copias as $correo_copia) {
                    $mail->addCC($correo_copia);
                }
            }
            if($archivos!=''){
                foreach ($archivos as $archivo) {
                    $mail->AddAttachment($archivo[0], $archivo[1]);
                }
            }
            /* Content */
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = str_replace('?','',utf8_decode($asunto));
            $mail->Body = $contenido_correo;
            $mail->Send();
        } catch (phpmailerException $e) {
            echo "Message:: " . $e->errorMessage();
            mail("brayan.desteco@gmail.com", 'error exception PhmExp', 'error exception  ' . $e->errorMessage() . $correo_a_enviar.'['.$asunto.']');
        } catch (Exception $e) {
            echo "Message:: " . $e->getMessage();
            mail("brayan.desteco@gmail.com", 'error exception Exp', 'error exception  ' . $e->errorMessage() . $correo_a_enviar.'['.$asunto.']');
        }
    }else{
        echo "<hr>EMAIL INVALIDO [$correo_a_enviar] DEPURADO<hr>";
        /* depuracion de correo */
        query("UPDATE cursos_participantes SET sw_notif='0' WHERE correo LIKE '$correo_a_enviar' ");
        query("UPDATE cursos_usuarios SET sw_notif='0' WHERE email LIKE '$correo_a_enviar' ");
    }
}


function numIDcurso($id_curso){
    global $___num_reduccion_id_curso;
    return abs((int)$id_curso-$___num_reduccion_id_curso);
}


function sendConvertionAPI($event_data){
    /* accesos */
    // anterior $_PIXEL_ID_conv_api = '538550900001569';
    $_PIXEL_ID_conv_api = '554311238689048';
    // anterior $_TOKEN_conv_api = 'EAAGXu0rL0IcBACxM3tCQKty97wcGZAOqjc6eiDbHW5jBkHrnoTo0sutkNaqClK13nTyy4gV0Vw6V5Ayyp12NGKVhFeCIiaEVoW7NOqXZCCEHc6e1eJ5BAxZByhTweHuIg79VWZB4mNcFM8yXMZAIEFfZCsQL0W1NRQ487bPjA0LO9lRBbSjPVQg2jVWZBQkKFMZD';
    //$_TOKEN_conv_api = "EAAZAKpOKPjAEBAOvWf5xULtBQModNNkYqBEZCL3z3DD0GYZB2QZB1HJb4ArS3SxZAQ9A6mpi6R5l9VIODQDqSYAGSRQfp5tH5iF25k5wDYlqQ7S2fUYBLf2WDurwCzyWba1Fx5hZCLJny87rPsM4lA5I470l2Xnx1nV350f1CwEKLOjcleANnrbbF2yvmwuwgZD";
    $_TOKEN_conv_api = "EAAZAKpOKPjAEBAPg20K907HjbRrWsJm1HOykS3cAOKiC6BPZBw2ZBLJIKhr1ssEt4cbbOv2OcQYHFZAOB9ykZAYXmQe5kXTAREo76R4ehYMQlZAOaj7vOee2sMajw5SDhlGSZAVZA00Yce7xLimBqjrVMU0ouFCGBgxYJMT6DVpXu0bDFxjKJndJjNvVQbHKr8wZD";
    /* event data */
    $data__email = hash('sha256', $event_data['email']);
    $data__monto = $event_data['value_monto'];
    $data__urlpage = $event_data['urlpage'];
    $data__idproducto = $event_data['idproducto'];
    $data__event_name = "Purchase";
    $data__event_time = time();

    $data = [[
        "event_name" => json_encode($data__event_name),
        "event_time" => $data__event_time,
        "action_source" => "email",
        "user_data" => [
            "em" => $data__email,
            "client_ip_address" => $_SERVER['REMOTE_ADDR'],
            "client_user_agent" => $_SERVER['HTTP_USER_AGENT'],
        ],
        "custom_data" => [
            "currency" => "BOB",
            "value" => $data__monto,
        ],
        "contents" => [
            [
                "id" => $data__idproducto,
                "quantity" => 1,
            ]
        ],
        "event_source_url" => $data__urlpage,
        "action_source" => "website",
    ]];

    /* campos */
    $fields = [
        'access_token' => $_TOKEN_conv_api,
        'data' => $data
    ];

    /* url api */
    $url_api = 'https://graph.facebook.com/v10.0/' . $_PIXEL_ID_conv_api . '/events';

    /* sending curl */
    $curl = curl_init($url_api);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    /* response */
    return $response;
}

/* FUNCIONES: UTIL CLASS */
require_once __DIR__.'/../utilidades/Util.php';

/* FUNCIONES: CARRITO CLASS */
require_once __DIR__.'/../utilidades/Carrito.php';

/* FUNCIONES: TIENDA CLASS */
require_once __DIR__.'/../utilidades/Tienda.php';

/* FUNCIONES: HASHUTIL CLASS */
require_once __DIR__.'/../utilidades/HashUtil.php';

/* FUNCIONES: EMAILUTIL CLASS */
require_once __DIR__.'/../utilidades/EmailUtil.php';

/* FUNCIONES: UTIL IMAGE UPLOADER */
require_once __DIR__.'/../utilidades/UploadImageUtil.php';


