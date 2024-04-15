<?php

function noaccent($dat){
    $acentos = array(utf8_encode('á'),utf8_encode('é'),utf8_encode('í'),utf8_encode('ó'),utf8_encode('ú'),utf8_encode('Á'),utf8_encode('É'),utf8_encode('Í'),utf8_encode('Ó'),utf8_encode('Ú'),utf8_encode('ñ'),utf8_encode('Ñ'));
    $no_acent = array('a','e','i','o','u','A','E','I','O','U','n','N');
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

//post
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
    $return = "";
    $busc = array("'", ';', '=', '*', 'delete', 'drop', 'truncate');
    $remm = array("", ',', '', '', '', '', '');
    if (isset($_POST[$name])) {
        $return = mysql_real_escape_string(strip_tags(str_replace("'",'',$_POST[$name])));
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
    $return = "";
    $busc = array("'", ';', '=', '*', 'delete', 'drop', 'truncate');
    $remm = array("", ',', '', '', '', '', '');
    if (isset($_POST[$name])) {
        $return = mysql_real_escape_string($_POST[$name]);
    }

    return $return;
}

function get($name) {
    $return = "";
    $busc = array("'", ';', '=', '*', ' ', 'delete', 'drop');
    $remm = array("", ',', '', '', '-', '', '');
    if (isset($_GET[$name])) {
        //$return = mysql_real_escape_string(str_replace($busc,$remm,strip_tags($_GET[$name])));
        $return = mysql_real_escape_string(strip_tags($_GET[$name]));
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
    //$ret = mysql_query($sql) or die(mysql_error() . " :->: " . $sql);
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
    //$ret = mysql_query($sql) or die(mail("brayan.desteco@gmail.com", 'Error SQL ' . date("Y-m-d H:i:s"), "<div>Error SQL en:<br/> $url_actual <hr/><br/>" . mysql_error() . "<hr/><br/>$sql<br/><hr/>IP: $ip_actual</div>", $cabeceras) . "<hr/><br/><b>Se ha producido un error</b> lamentamos la molestia, lo solucionaremos lo antes posible.<br/><hr/>");
    $ret = mysql_query($sql) or die("<div>Error SQL en:<br/> $url_actual <hr/><br/>" . mysql_error() . "<hr/><br/>$sql<br/><hr/>IP: $ip_actual</div>" . "<hr/><br/><b>Se ha producido un error</b> lamentamos la molestia, lo solucionaremos lo antes posible.<br/><hr/>");
    return $ret;
}

//pre2 function encriptar($string) {
//    $key = 'abc';
//    $result = '';
//    for ($i = 0; $i < strlen($string); $i++) {
//        $char = substr($string, $i, 1);
//        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
//        $char = chr(ord($char) + ord($keychar));
//        $result.=$char;
//    }
//    return base64_encode($result);
//}
//
//function desencriptar($string) {
//    $key = 'abc';
//    $result = '';
//    $string = base64_decode($string);
//    for ($i = 0; $i < strlen($string); $i++) {
//        $char = substr($string, $i, 1);
//        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
//        $char = chr(ord($char) - ord($keychar));
//        $result.=$char;
//    }
//    return $result;
//}
// pre 1 function encriptar($string) {
//    $key = '4152';
//    $lsEncVar = mcrypt_ecb(MCRYPT_DES,$key, $string, MCRYPT_ENCRYPT);
//    return base64_encode($lsEncVar);
//}
//
//function desencriptar($string) {
//    $key = '4152';
//    $string = base64_decode($string);
//    $lsEncVar = mcrypt_ecb(MCRYPT_DES, $key, $string, MCRYPT_ENCRYPT);
//    return $lsEncVar;
//}

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

//redimencionar imagen
function sube_imagen($imagen, $destino) {

    //move_uploaded_file($imagen, $nombre_imagen);
    //Ruta de la imagen original
    $rutaImagenOriginal = $imagen;

//Ancho y alto de la imagen original
    list($ancho, $alto, $tipo_img) = getimagesize($rutaImagenOriginal);

//Creamos una variable imagen a partir de la imagen original
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

//Se define el maximo ancho y alto que tendra la imagen final
    $max_ancho = 1300;
    $max_alto = 900;

//Se calcula ancho y alto de la imagen final
    $x_ratio = $max_ancho / $ancho;
    $y_ratio = $max_alto / $alto;

//Si el ancho y el alto de la imagen no superan los maximos,
//ancho final y alto final son los que tiene actualmente
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

//Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final .
    $tmp = imagecreatetruecolor($ancho_final, $alto_final);

//Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
    imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

//Se destruye variable $img_original para liberar memoria
    imagedestroy($img_original);

//Definimos la calidad de la imagen final
    $calidad = 100;
//Se crea la imagen final en el directorio indicado
    imagejpeg($tmp, $destino, $calidad);
}

function form100($cad) {
    $pagina = $cad;
    $pagina = str_replace('<td width="13%" class="Formulario">', '<td width="13%" class="Formulario" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="15%" align="center">', '<td width="15%" align="center" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="73%" class="FormularioTitulo">', '<td width="73%" class="FormularioTitulo" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="12%" align="center">', '<td><h3>INFOSICOES</h3></td><td width="12%" align="center">', $pagina);
    $pagina = str_replace('link', 'meta', $pagina);
    $pagina = str_replace('script', 'section', $pagina);
    $pagina = str_replace('src="..', 'src="https://www.sicoes.gob.bo', $pagina);
    $pagina = str_replace('href="..', 'href="https://www.sicoes.gob.bo', $pagina);
    $pagina = str_replace('../lib/descargar.php', 'https://www.sicoes.gob.bo/lib/descargar.php', $pagina);

    $array_form_1 = explode('<body>', $pagina);
    $formulario_limpio = '';
    for ($i = 1; $i < count($array_form_1); $i++) {
        $array_form_2 = explode("</body>", $array_form_1[$i]);
        $formulario_limpio = $formulario_limpio . $array_form_2[0];
    }

    return $formulario_limpio;
}

function form100E($cad) {
    $arr = explode('</head>', $cad);
    $busc = array('body', '</html>');
    $remm = array('div', '');
    $cont1 = str_replace($busc, $remm, $arr[1]);
    return $cont1;
}

function form180($cad) {
    $busc = array('<head', '</head>', 'body', 'html');
    $remm = array('<div style="display:none;" ', '</div>', 'div', 'div');
    $cont1 = str_replace($busc, $remm, $cad);
    return $cont1;
}

function form400($cad) {
    $arr = explode('<body', str_replace('onload="borrar()"', '', $cad));
    $arr2 = explode('</body>', $arr[1]);
    $cad = '<div' . $arr2[0] . '</div>';
    $cad = str_replace('<input', '<input style="display:none;"', str_replace('../imagenes/', 'https://www.sicoes.gob.bo/imagenes/', str_replace('../lib/', 'https://www.sicoes.gob.bo/lib/', $cad)));
    return $cad;
}

function form0($cad) {
    $pagina = $cad;
    $pagina = str_replace('<td width="13%" class="Formulario">', '<td width="13%" class="Formulario" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="15%" align="center">', '<td width="15%" align="center" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="73%" class="FormularioTitulo">', '<td width="73%" class="FormularioTitulo" style="display:none;">', $pagina);
    $pagina = str_replace('<td width="12%" align="center">', '<td><h3>INFOSICOES</h3></td><td width="12%" align="center">', $pagina);
    $pagina = str_replace('link', 'meta', $pagina);
    $pagina = str_replace('script', 'section', $pagina);
    $pagina = str_replace('src="..', 'src="https://www.sicoes.gob.bo', $pagina);
    $pagina = str_replace('href="..', 'href="https://www.sicoes.gob.bo', $pagina);
    $pagina = str_replace('../lib/descargar.php', 'https://www.sicoes.gob.bo/lib/descargar.php', $pagina);

    $busc_body = array('<body onload="inicio()">', '<body >');
    $remm_body = '<body>';
    $pagina = str_replace($busc_body, $remm_body, $pagina);

    $array_form_1 = explode('<body>', $pagina);
    $array_form_11 = explode('</body>', $array_form_1[1]);
    $url_content_min = str_replace('type="button"', 'type="hidden"', $array_form_11[0]);
    $url_content_min = str_replace('src="https://www.sicoes.gob.bo/imagenes/logo_mefp_forms.gif"', 'src="" style="width:0px;height:0px;"', str_replace('type="button"', 'type="hidden"', $url_content_min));

    return $url_content_min;
}

function form00($cad) {
    $busc = array(
        '<head',
        '</head>',
        '<script',
        '</script>',
        'body',
        'html',
        'src="../imagenes/',
        'href="../lib/',
        'onClick="window.close();"',
        '{',
        '}',
        '<link',
        '<meta',
        'name="btnImprimir"'
    );
    $remm = array(
        '<div style="display:none;" ',
        '</div>',
        '<div style="display:none;" ',
        '</div>',
        'div',
        'div',
        'src="https://www.sicoes.gob.bo/imagenes/',
        'href="https://www.sicoes.gob.bo/lib/'
        , ' style="display:none;" ',
        '[',
        ']',
        '<br',
        '<br',
        ' style="display:none;" '
    );
    $cont1 = str_replace($busc, $remm, $cad);
    return $cont1;
}

function verificarGet($cad) {
    $busc = array('truncate', 'TRUNCATE', 'delete', 'DELETE', 'update', 'UPDATE', ' select', 'SELECT', ' FROM ', ' from ', '`');
    $remm = 'none';
    return str_replace($busc, $remm, $cad);
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

function enlaceDescartarLicitacion($id_empresa, $id_licitacion) {
    $enlace = "ast";
    $aux1 = $id_empresa + 3241;
    $aux2 = substr($aux1, 0, 4);
    $enlace .= $aux2 . $id_empresa . '0905';
    $aux3 = $id_licitacion + 4153;
    $aux4 = substr($aux3, 0, 4);
    $enlace .= $aux4 . $id_licitacion;

    return $enlace;
}

function enlaceMonitorearLicitacion($id_empresa, $id_licitacion) {
    $enlace = "ths";
    $aux1 = $id_empresa + 3241;
    $aux2 = substr($aux1, 0, 4);
    $enlace .= $aux2 . $id_empresa . '0905';
    $aux3 = $id_licitacion + 4153;
    $aux4 = substr($aux3, 0, 4);
    $enlace .= $aux4 . $id_licitacion;

    return $enlace;
}

function enlaceDarDeBaja($id_empresa) {
    $enlace = "tws";
    $aux1 = ($id_empresa * 3144) + 125145812246153;
    $aux2 = substr($aux1, 0, 14);
    $aux3 = ($id_empresa * 9514 + 2515421512151);
    $aux4 = substr($aux3, 0, 12);

    $enlace .= $aux2 . $id_empresa . $aux4;

    return $enlace;
}

function enlaceDarDeBaja2($id_empresa) {
    $enlace = "1Th01";
    $aux1 = ($id_empresa * 3144) + 125145812246153;
    $aux2 = substr($aux1, 0, 14);
    $aux3 = ($id_empresa * 9514 + 2515421512151);
    $aux4 = substr($aux3, 0, 12);

    $enlace .= $aux2 . $id_empresa . $aux4;

    return $enlace;
}

//administradores
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

function tinyAdmin() {
    ?>
    <script src="contenido/tinymce/js/tinymce/tinymce.min.js"></script>
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

//editor 
function editorTinyMCE($id_elemento) {
    ?><script src="contenido/tinymce/js/tinymce/tinymce.min.js"></script>
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

//agrega movimientos
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

/* LOG DE MOVIMIENTOS CURSOS.BO */
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

/* LOADPAGES AJAX FUNCIONALIDAD */
function loadpage($url_data){
    $ar1 = explode('/',$url_data);
    $page = $ar1[0];
    unset($ar1[0]);
    $data = implode('/',$ar1);
    return ' href="'.$url_data.'.adm" onclick="load_page(\''.$page.'\',\''.$data.'\',\'\');return false;" ';
}

//ACCESO DE PAGINAS
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

//OBTENER ACCESO LINEAL
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

//OBTENER ACCESO DINAMICO
function acceso_cod($cod_privilegio) {
    if (isset_administrador()) {
        $rqpa1 = query("SELECT ids_nivel_administrador FROM cursos_privilegios WHERE cod_privilegio='$cod_privilegio' ORDER BY id ASC limit 1 ");
        if (mysql_num_rows($rqpa1) > 0) {
            $rqpa2 = mysql_fetch_array($rqpa1);
            $ids_nivel_administrador = $rqpa2['ids_nivel_administrador'];
            $arr = explode(',', $ids_nivel_administrador);
            $rqna1 = query("SELECT nivel FROM administradores WHERE id='" . administrador('id') . "' ORDER BY id ASC limit 1 ");
            $rqna2 = mysql_fetch_array($rqna1);
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

//FUNCIONES DE EXTRACCION
function limpp($cadena) {
    $cadena = trim($cadena);
    $cadena_r = strip_tags($cadena);
    //$cadena_r = utf8_decode($cadena);
    $cadena_r = str_replace("'", "\'", $cadena_r);
    $cadena_r = str_replace('"', '\"', $cadena_r);
    return $cadena_r;
}

function limp_to_link($cadena) {
    $busc = array("'", '"', '\\', '\\\\', '/', '//', '`', '”', '“');
    $remm = array('', '', '', '', '', '', '', '', '');
    $cadena_r = str_replace($busc, $remm, $cadena);
    return trim($cadena_r);
}

//extrae tabal conv nacional
function extrae_tabla($html) {

    $busc_a = array("<TD>", "</TD>", "&nbsp;", "<TR valign=top class=impar>", "impar", "<TABLE COLS=11 width=100% ><tr>", "&amp;", "</TR>", "');", "<TABLE COLS=20  width=100%>", '<table width="100%" border="0" cellspacing="0" cellpadding="0">', "TABLE");
    $remm_a = array("<td>", "</td>", "", "<TR valign=top class=par>", "par", "<TABLE COLS=11  width=100%><tr>", "&", "</tr>", "')", "<table COLS=20 width=100% >", "<table COLS=20 width=100% >", "table");

    $content = str_replace($busc_a, $remm_a, $html);

    if (strpos($content, "table")) {
        $array_3 = explode('<table COLS=20 width=100% >', stripslashes($content));
        $array_4 = explode('</table>', $array_3[(count($array_3) - 1 )]);
        $content = $array_4[0]; //contenido completo de la tabla
    }
    return $content;
}

//extrae tabal conv nacional FORM simple 
function extrae_tabla_simple($html) {

    $busc_a = array("<TD>", "</TD>", "&nbsp;", "<TR valign=top class=impar>", "impar", "<TABLE COLS=11 width=100% ><tr>", "&amp;", "</TR>", "');", "<TABLE COLS=20  width=100%>", '<table width="100%" border="0" cellspacing="0" cellpadding="0">', "TABLE");
    $remm_a = array("<td>", "</td>", "", "<TR valign=top class=par>", "par", "<TABLE COLS=11  width=100%><tr>", "&", "</tr>", "')", "<table COLS=20 width=100% >", "<table COLS=20 width=100% >", "table");

    $content = str_replace($busc_a, $remm_a, $html);

    if (strpos($content, "table")) {
        $array_3 = explode('<table COLS=11  width=100%>', stripslashes($content));
        $array_4 = explode('</table>', $array_3[1]);
        $content = $array_4[0]; //contenido completo de la tabla
    }
    return $content;
}

//extrae tabla conv internacional
function extrae_tabla_ci($html) {

    $busc_a = array("<TD>", "</TD>", "&nbsp;", "<TR valign=top class=impar>", "impar", "&amp;", "</TR>", "');", "<TABLE COLS=11  width=100%>", "TABLE");
    $remm_a = array("<td>", "</td>", "", "<TR valign=top class=par>", "par", "&", "</tr>", "')", "<TABLE COLS=11 width=100% >", "table");

    $content = str_replace($busc_a, $remm_a, $html);

    if (strpos($content, "table")) {
        $array_3 = explode('<table COLS=11 width=100% >', stripslashes($content));
        $array_4 = explode('</table>', $array_3[(count($array_3) - 1 )]);
        $content = $array_4[0]; //contenido completo de la tabla
    }
    return $content;
}

//function crear_formulario_fisico($form_nombre, $form_direccion_externa, $form_carpeta_asignada, $form_nombre_archivo) {
//
//    $url_content_min = get_url($form_direccion_externa);
//
//    if ($form_nombre == 'FORM 100') {
//        $url_content_min = form100($url_content_min);
//    } elseif ($form_nombre == 'FORM 100-E') {
//        //$url_content_min = form100E($url_content_min);
//        $url_content_min = form00($url_content_min);
//    } elseif ($form_nombre == 'FORM 180') {
//        $url_content_min = form180($url_content_min);
//    } elseif ($form_nombre == 'FORM 400') {
//        $url_content_min = form400($url_content_min);
//    } else {
//        $url_content_min = form00($url_content_min);
//    }
//    $ar = fopen("/home/infosico/public_html/contenido/formularios/$form_carpeta_asignada/$form_nombre_archivo", "w") or die("Problemas en la creacion de archivo");
//    fputs($ar, $url_content_min);
//    fclose($ar);
//}
function crear_formulario_fisico($form_nombre, $contenido_formulario, $form_carpeta_asignada, $form_nombre_archivo) {

    $busc = array('/img/', '/imagenes/', 'descargar</a>', 'Descargar</td>', 'value="Obtener Confirmaci&oacute;n"', 'value="Imprimir"');
    $remm = array('https://www.sicoes.gob.bo/img/', 'https://www.sicoes.gob.bo/imagenes/', '..</a>', '..</td>', 'style="display:none;" value=""', 'style="display:none;" value=""');

    $url_content_min = str_replace($busc, $remm, $contenido_formulario);
    /*
      if ($form_nombre == 'FORM 100') {
      $url_content_min = form100($url_content_min);
      } elseif ($form_nombre == 'FORM 100-E') {
      //$url_content_min = form100E($url_content_min);
      $url_content_min = form00($url_content_min);
      } elseif ($form_nombre == 'FORM 180') {
      $url_content_min = form180($url_content_min);
      } elseif ($form_nombre == 'FORM 400') {
      $url_content_min = form400($url_content_min);
      } else {
      $url_content_min = form00($url_content_min);
      } */

    //echo $url_content_min;
    $ar = fopen("/home/infosico/public_html/contenido/formularios/$form_carpeta_asignada/$form_nombre_archivo", "w") or die("Problemas en la creacion de archivo");
    fputs($ar, $url_content_min);
    fclose($ar);

    //echo "/home/infosico/public_html/contenido/formularios/$form_carpeta_asignada/$form_nombre_archivo";exit;
}

function datos_adicionales($licitt) {

    $formulario = $licitt['formularios'][0];
    $nombre_archivo = $formulario['nombre_archivo'];
    $carpeta_asignada = $formulario['carpeta_asignada'];

    $url_content_min = file_get_contents("http://www.infosicoes.com/contenido/formularios/$carpeta_asignada/$nombre_archivo");

    if (strpos($formulario['nombre_archivo'], '100')) {
        $arrr_1 = explode('TOTAL:', $url_content_min);
        $arrr_2 = explode('</tr>', $arrr_1[1]);
        $monto = str_replace("/100", " <br/> Bs. ", strip_tags($arrr_2[0]));
        $arrr_1 = explode('Fax', $url_content_min);
        $arrr_2 = explode('</tr>', $arrr_1[1]);
        $arrr_3 = explode('</td>', $arrr_2[1]);
        $fax = strip_tags($arrr_3[count($arrr_3) - 3]);
        $telefono = strip_tags($arrr_3[count($arrr_3) - 2]);
    } elseif ($formulario['nombre'] == 'FORM 400') {
        $monto = '';
        $fax = '';
        $telefono = '';
        $arrr_1 = explode('Total estimado cuando la cantidad es variable', $url_content_min);
        $arrr_2 = explode('<td', $arrr_1[1]);
        $monto = strip_tags('<td' . $arrr_2[9]) . " Bs.";
        $arrr_1 = explode('Fax', $url_content_min);
        $arrr_2 = explode('<td', $arrr_1[1]);
        $fax = strip_tags('<td' . $arrr_2[5]);
    }

    $precio = '';
    if (strpos($formulario['nombre'], '100')) {
        $arr_precio = explode('Bs.', strip_tags($monto));
        $precio = (int) (trim(str_replace(',', '', $arr_precio[1])));
    } elseif ($formulario['nombre'] == 'FORM 400') {
        $arr_precio = explode('Bs.', strip_tags($monto));
        $precio = (int) (trim(str_replace(',', '', $arr_precio[0])));
    }

    $licitt['monto'] = trim(limpp($monto));
    $licitt['precio'] = trim(limpp($precio));
    $licitt['telefono'] = trim(limpp($telefono));
    $licitt['fax'] = trim(limpp($fax));


    return $licitt;
}

function get_longitud_licitacion($html_fila_licitacion) {

    $busc = array('<td>');
    $remm = array('');
    $html_fila_licitacion = str_replace($busc, $remm, $html_fila_licitacion);

    $array_8 = explode("</td>", $html_fila_licitacion);

    $tag_estado = trim(strtolower(str_replace(" ", "-", strip_tags($array_8[4]))));
    $tag_arch_form = strlen(trim(str_replace(' ', '-', strip_tags($array_8[8] . $array_8[9]))));
    $tag_presentacion = substr(trim(str_replace(' ', '-', str_replace(':', '-', $array_8[7]))), 0, 10);

    return "$tag_estado-$tag_arch_form-$tag_presentacion";
}

//datos de la fila de la licitacion
function datos_licitacion($html_fila_licitacion) {

    $busc = array('<td>');
    $remm = array('');

    $html_fila_licitacion = str_replace($busc, $remm, $html_fila_licitacion);

    $datos_licitacion['longitud_actual'] = get_longitud_licitacion($html_fila_licitacion);
    $datos_licitacion['cuce'] = "";
    $datos_licitacion['entidad'] = "";
    $datos_licitacion['modalidad'] = "";
    $datos_licitacion['nro_contr'] = "";
    $datos_licitacion['nro_conv'] = "";
    $datos_licitacion['objeto'] = "";
    $datos_licitacion['id_estado'] = "";
    $datos_licitacion['fecha_publicacion'] = "";
    $datos_licitacion['fecha_representacion'] = "";
    $datos_licitacion['monto'] = "";
    $datos_licitacion['precio'] = "";
    $datos_licitacion['persona_contacto'] = "";
    $datos_licitacion['garantia'] = "";
    $datos_licitacion['costo_pliego'] = "";
    $datos_licitacion['reunion_aclaracion'] = "";
    $datos_licitacion['fecha_adjudicacion_desierta'] = "";
    $datos_licitacion['telefono'] = "";
    $datos_licitacion['fax'] = "";
    $datos_licitacion['departamento'] = "";
    $datos_licitacion['fecha_registro'] = date("Y-m-d h:i:s");


    $array_8 = explode("</td>", $html_fila_licitacion);

    //$datos_licitacion['longitud_actual'] = strlen(trim(str_replace(" ","",strip_tags($array_8[7].$array_8[9].$array_8[10].$array_8[11]))));

    $datos_licitacion['cuce'] = trim(limp_to_link(limpp($array_8[0])));
    $datos_licitacion['entidad'] = trim(limp_to_link(limpp($array_8[1])));
    $datos_licitacion['modalidad'] = trim((limpp($array_8[2])));
    $datos_licitacion['objeto'] = trim(limp_to_link(limpp(decodificacion_objeto_newsicoes($array_8[3]))));

    //estado
    $estado_str = trim(limp_to_link(limpp($array_8[4])));
    $estado_str_titulo_identificador = limpiar_enlace($estado_str);
    $rqest1 = query("SELECT id FROM estados WHERE titulo_identificador='$estado_str_titulo_identificador' LIMIT 1 ");
    if ((mysql_num_rows($rqest1) == 0) && ($estado_str_titulo_identificador !== '')) {
        query("INSERT INTO estados (titulo,titulo_identificador) VALUES ('$estado_str','$estado_str_titulo_identificador') ");
        $rqest1 = query("SELECT id FROM estados WHRE titulo_identificador='$estado_str_titulo_identificador' LIMIT 1 ");
    }
    $rqest2 = mysql_fetch_array($rqest1);
    $datos_licitacion['id_estado'] = $rqest2['id'];

    $datos_licitacion['monto'] = trim(limp_to_link(limpp($array_8[5])));

    //fecha publicacion
    if (trim($array_8[6]) == '') {
        $array_8[6] = '00/00/0000';
    }
    $array_8[6] = str_replace("/", "-", $array_8[6]);
    $array_8[6] = trim($array_8[6]);
    $array_fecha1 = explode("-", $array_8[6]);
    $datos_licitacion['fecha_publicacion'] = $array_fecha1[2] . "-" . $array_fecha1[1] . "-" . $array_fecha1[0];

    //fecha presentacion
    if (trim($array_8[7]) == '') {
        $array_8[7] = '00/00/0000 00:00';
    }
    $fech = trim($array_8[7]);
    $array_fecha3 = explode("/", $fech);
    $datos_licitacion['fecha_representacion'] = eregi_replace("[\n|\r|\n\r]", "", $array_fecha3[2] . "-" . $array_fecha3[1] . "-" . $array_fecha3[0] . "");


    $datos_licitacion['persona_contacto'] = trim(limp_to_link(limpp($array_8[10])));
    $datos_licitacion['garantia'] = trim(limp_to_link(limpp($array_8[11])));
    $datos_licitacion['costo_pliego'] = trim(limp_to_link(limpp($array_8[12])));

    $datos_licitacion['departamento'] = trim(limp_to_link(limpp($array_8[16])));

    //fecha reunion_aclaracion
    if (trim($array_8[14]) == '') {
        $array_8[14] = '00/00/0000 00:00';
    }
    $array_fecha3b = explode(" ", trim($array_8[14]));
    $fech2 = $array_fecha3b[0];
    $hor2 = $array_fecha3b[1];
    $array_fecha4 = explode("/", $fech2);
    $datos_licitacion['reunion_aclaracion'] = eregi_replace("[\n|\r|\n\r]", "", $array_fecha4[2] . "-" . $array_fecha4[1] . "-" . $array_fecha4[0] . " " . $hor2 . ":00");

    //fecha adjudicacion desierta
    if (trim($array_8[15]) == '') {
        $array_8[15] = '00/00/0000';
    }
    $array_8[15] = str_replace("/", "-", $array_8[15]);
    $array_8[15] = trim($array_8[15]);
    $array_fecha2 = explode("-", $array_8[15]);
    $datos_licitacion['fecha_adjudicacion_desierta'] = $array_fecha2[2] . "-" . $array_fecha2[1] . "-" . $array_fecha2[0];

    $array_8[8]; //archivos
    $array_9 = explode("openWindownx1('", $array_8[8]);
    $datos_licitacion['archivos'] = array();

    for ($k = 1; $k < count($array_9); $k++) {

        $array_9[$k]; //datos de archivo
        $array_10 = explode("')", $array_9[$k]);
        $array_10[0] = limpp($array_10[0]); //codigo-encriptado de archivo
        $array_10[1] = str_replace(';">', "", limpp($array_10[1]));
        $array_nombre_archivo = explode("</a>", $array_10[1]);
        $array_10[1] = trim(str_replace(';">', '', str_replace('\\', '', $array_nombre_archivo[0]))); //nombre de archivo
        //$mini_array = explode("/", $array_10[0]);

        $array_archiv = array(
            "direccion_externa" => "#",
            "encript" => $array_10[0],
            "nombre" => $array_10[1]
        );

        array_push($datos_licitacion['archivos'], $array_archiv);
    }

    $array_8[9]; //formularios

    $array_11 = explode("openWindownx0('", $array_8[9]);
    $datos_licitacion['formularios'] = array();

    for ($k = 1; $k < count($array_11); $k++) {

        $array_11[$k]; //datos de archivo
        $array_12 = explode("')", $array_11[$k]);
        $array_12[0] = limpp($array_12[0]);
        $array_12[0]; //codigo-encriptado de formulario 
        $array_12[1] = substr(limpp($array_12[1]), 4);
        $rrraux = explode('<', $array_12[1]);
        $array_12[1] = $rrraux[0];
        $array_12[1]; //nombre de formulario

        $nombre_formulario = $array_12[1];
        $direccion_externa = "#";
        $codigo_encriptado = $array_12[0];
        $nombre_archivo = 'conv-n-' . str_replace(' ', '-', strtolower($nombre_formulario)) . '-' . time() . '-' . $datos_licitacion['cuce'] . '.txt';

        $arr_carp = explode('-', trim($datos_licitacion['fecha_publicacion']));
        $anio = $arr_carp[0];
        $mes = $arr_carp[1];
        $dia = $arr_carp[2];
        if ($anio == '') {
            $anio = date("Y");
        }
        if ($mes == '') {
            $mes = date("00");
        }
        if ($dia == '') {
            $dia = date("00");
        }
        /*
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
         */
        $carpeta_asignada = "$anio/$mes/$dia";

        $array_form = array(
            "nombre_archivo" => $nombre_archivo,
            "carpeta_asignada" => $carpeta_asignada,
            "direccion_externa" => $direccion_externa,
            "encript" => $codigo_encriptado,
            "nombre" => $nombre_formulario
        );

        array_push($datos_licitacion['formularios'], $array_form);
    }

    return $datos_licitacion;
}

//datos de la fila de la licitacion SIMPLE
function datos_licitacion_simple($html_fila_licitacion) {

    $busc = array('<td>', '<TD >', '<br>', '<TD align=right >');
    $remm = array('', '', '', '');

    $html_fila_licitacion = str_replace($busc, $remm, $html_fila_licitacion);

    $array_8 = explode("</td>", $html_fila_licitacion);

    //fecha presentacion
    if (trim($array_8[9]) == '') {
        $array_8[9] = '00/00/0000 00:00';
    }
    $fech = trim($array_8[9]);
    $array_fecha1 = explode(" ", $fech);
    $array_fecha3 = explode("/", $array_fecha1[0]);
    $datos_licitacion['fecha_representacion'] = eregi_replace("[\n|\r|\n\r]", "", $array_fecha3[2] . "-" . $array_fecha3[1] . "-" . $array_fecha3[0] . "") . ' ' . $array_fecha1[1];

    //estado
    $datos_licitacion['estado'] = trim(strip_tags($array_8[7]));

    return $datos_licitacion;
}

//datos de la fila de la licitacion internacional
function datos_licitacion_internacional($html_fila_licitacion) {

    $busc = array('<td>', '<TD >', '<br>', '<TD align=right >');
    $remm = array('', '', '', '');

    $html_fila_licitacion = str_replace($busc, $remm, $html_fila_licitacion);

    $datos_licitacion['longitud_actual'] = get_longitud_licitacion_internacional($html_fila_licitacion);
    $datos_licitacion['cuce'] = "";
    $datos_licitacion['entidad'] = "";
    $datos_licitacion['modalidad'] = "";
    $datos_licitacion['nro_contr'] = "";
    $datos_licitacion['nro_conv'] = "";
    $datos_licitacion['objeto'] = "";
    $datos_licitacion['estado'] = "";
    $datos_licitacion['fecha_publicacion'] = "";
    $datos_licitacion['fecha_representacion'] = "";
    $datos_licitacion['archivos'] = "";
    $datos_licitacion['formularios'] = "";
    $datos_licitacion['monto'] = "";
    $datos_licitacion['precio'] = "";
    $datos_licitacion['persona_contacto'] = "";
    $datos_licitacion['reunion_aclaracion'] = "";
    $datos_licitacion['fecha_adjudicacion_desierta'] = "";
    $datos_licitacion['telefono'] = "";
    $datos_licitacion['fax'] = "";
    $datos_licitacion['departamento'] = "";
    $datos_licitacion['fecha_registro'] = date("Y-m-d h:i:s");


    $array_8 = explode("</td>", $html_fila_licitacion);

    $datos_licitacion['cuce'] = trim(limp_to_link(limpp($array_8[1])));
    $datos_licitacion['entidad'] = trim(limp_to_link(limpp($array_8[2])));
    $datos_licitacion['modalidad'] = trim((limpp($array_8[3])));
    $datos_licitacion['nro_contr'] = trim((limpp($array_8[4])));
    $datos_licitacion['nro_conv'] = trim((limpp($array_8[5])));
    $datos_licitacion['objeto'] = trim(ucfirst(limp_to_link(limpp($array_8[6]))));
    $datos_licitacion['estado'] = trim(limp_to_link(limpp($array_8[7])));

    //fecha publicacion
    if (trim($array_8[8]) == '') {
        $array_8[8] = '00/00/0000';
    }
    $array_8[8] = str_replace("/", "-", $array_8[8]);
    $array_8[8] = trim($array_8[8]);
    $array_fecha1 = explode("-", $array_8[8]);
    $datos_licitacion['fecha_publicacion'] = $array_fecha1[2] . "-" . $array_fecha1[1] . "-" . $array_fecha1[0];

    //fecha presentacion
    if (trim($array_8[9]) == '') {
        $array_8[9] = '00/00/0000 00:00';
    }
    $fech = trim($array_8[9]);
    $array_fecha3 = explode("/", $fech);
    $datos_licitacion['fecha_representacion'] = eregi_replace("[\n|\r|\n\r]", "", $array_fecha3[2] . "-" . $array_fecha3[1] . "-" . $array_fecha3[0] . "");


    //codigos archivos formularios
    $datos_licitacion['cod_archivos'] = "conv_int" . time() . "arch-" . $datos_licitacion['cuce'];
    $datos_licitacion['cod_formularios'] = "conv_int" . time() . "form-" . $datos_licitacion['cuce'];


    $array_8[10]; //archivos
    $array_9 = explode("openWindow1('../", $array_8[10]);
    $datos_licitacion['archivos'] = array();

    for ($k = 1; $k < count($array_9); $k++) {

        $array_9[$k]; //datos de archivo
        $array_10 = explode("')", $array_9[$k]);
        $array_10[0] = limpp($array_10[0]); //direccion de archivo
        $array_10[1] = str_replace('">', "", limpp($array_10[1]));
        $array_nombre_archivo = explode("</a>", $array_10[1]);
        $array_10[1] = trim(str_replace('\\', '', $array_nombre_archivo[0])); //nombre de archivo

        $mini_array = explode("/", $array_10[0]);

        $array_archiv = array(
            "codigo" => $datos_licitacion['cod_archivos'],
            "direccion_externa" => "https://www.sicoes.gob.bo/" . $array_10[0],
            "direccion_interna" => $mini_array[(count($mini_array) - 1)],
            "nombre" => $array_10[1]
        );

        array_push($datos_licitacion['archivos'], $array_archiv);
    }

    $array_8[11]; //formularios

    $array_11 = explode("openWindow('../", $array_8[11]);
    $datos_licitacion['formularios'] = array();

    for ($k = 1; $k < count($array_11); $k++) {

        $array_11[$k]; //datos de archivo
        $array_12 = explode("')", $array_11[$k]);
        $array_12[0] = limpp($array_12[0]);
        $array_12[0]; //direccion de archivo () URL
        $array_12[1] = substr(limpp($array_12[1]), 3);
        $rrraux = explode('<', $array_12[1]);
        $array_12[1] = $rrraux[0];
        $array_12[1]; //nombre de archivo

        $nombre_formulario = $array_12[1];
        $direccion_externa = "https://www.sicoes.gob.bo/" . $array_12[0];
        $nombre_archivo = 'conv-n-' . str_replace(' ', '-', strtolower($nombre_formulario)) . '-' . time() . '-' . $datos_licitacion['cuce'] . '.txt';

        $arr_carp = explode('-', trim($datos_licitacion['fecha_publicacion']));
        $anio = $arr_carp[0];
        $mes = $arr_carp[1];
        $dia = $arr_carp[2];
        if ($anio == '') {
            $anio = date("Y");
        }
        if ($mes == '') {
            $mes = date("00");
        }
        if ($dia == '') {
            $dia = date("00");
        }
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
        $carpeta_asignada = "$anio/$mes/$dia";

        $array_form = array(
            "cod_formulario" => $datos_licitacion['cod_formularios'],
            "nombre_archivo" => $nombre_archivo,
            "carpeta_asignada" => $carpeta_asignada,
            "direccion_externa" => $direccion_externa,
            "nombre" => $nombre_formulario
        );

        array_push($datos_licitacion['formularios'], $array_form);
    }

    return $datos_licitacion;
}

//RECAPTCHA
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
    function ReCaptcha($secret) {
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
        // Cut the last '&'
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
            $recaptchaResponse->errorCodes = $answers [error - codes];
        }
        return $recaptchaResponse;
    }

}

//IS INFOSICOES
function is_infosicoes($dom) {
    if ($dom == 'https://www.infosicoes.com/') {
        return true;
    } else {
        return false;
    }
}

//EXTRAE URL
function get_url($url) {

    echo "Funcion-no disponible";
    exit;

    $busc = array('&amp', '?', '=', '&');
    $remm = array('ampersan', 'interrogacion', 'igual', 'ampersan');
    $url_extract = str_replace($busc, $remm, $url);
    //$url_new = "http://50.87.5.200/~ilenkaco/ap-infosicoes/track1.php?url=$url_extract";
    $url_new = "http://www.boliviagrande.com/ap-infosicoes/track1.php?url=$url_extract";
    //$url_new = "http://consejomunicipaloruro.com/ap-ifs/track1.php?url=$url_extract";
    $url_content_min = file_get_contents($url_new);
    if (strlen($url_content_min) == 0) {
        $url_content_min = file_get_contents($url_new);
    }
    return $url_content_min;
}

//EXTRAE CURL
function get_curl($url, $params) {

    echo "Funcion-no disponible";
    exit;

    $busc = array('&amp', '?', '=', '&');
    $remm = array('ampersan', 'interrogacion', 'igual', 'ampersan');
    $url_extract = str_replace($busc, $remm, $url . '-PARAMS-' . $params);
    //$url_new = "http://50.87.5.200/~ilenkaco/ap-infosicoes/track1.php?c_url=$url_extract";
    $url_new = "http://www.boliviagrande.com/ap-infosicoes/track1.php?c_url=$url_extract";
    //$url_new = "http://consejomunicipaloruro.com/ap-ifs/track1.php?c_url=$url_extract";
    $url_content_min = file_get_contents($url_new);
    if (strlen($url_content_min) == 0) {
        $url_content_min = file_get_contents($url_new);
    }
    return $url_content_min;
}

//envio de email por phpmailer
function envio_email($correo, $subject, $body) {
    $cabeceras = 'From: Cursos BO <sistema@cursos.bo>' . "\r\n" .
            'Reply-To: ' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() .
            'Return-Path: ' . 'sistema@cursos.bo' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($correo, $subject, $body, $cabeceras);
}

//envio de email por phpmailer
function envio_emailPRE($correo, $subject, $body) {
    try {
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.cursos.bo';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'sistema@cursos.bo';                 // SMTP username
        $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
        //Recipients
        $mail->setFrom('sistema@cursos.bo', 'Cursos BO');
        $mail->addAddress($correo);     // Add a recipient
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($correo);

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

//envio de email por phpmailer
function envio_email_ventas($correo, $subject, $body) {

    try {
        $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas

        $mail->IsSMTP();                           // Usamos el metodo SMTP de la clase PHPMailer
        $mail->SMTPAuth = true;                  // habilitado SMTP autentificaciÃ³n
        $mail->SMTPSecure = "";
        $mail->Timeout = 30;                  // habilitado SMTP autentificaciÃ³n

        $mail->Port = 25;                // puerto del server SMTP
        $mail->Host = "mail.infosicoes.com";  // SMTP server
        $mail->Username = "ventas@infosicoes.com";     // SMTP server Usuario
        $mail->Password = "PbA(vre?i*AN";            // SMTP server password
        $mail->From = "ventas@infosicoes.com"; //Remitente de Correo
        $mail->FromName = "InfoSICOES - Departamento de ventas"; //Nombre del remitente
        $to = $correo; //Para quien se le va enviar
        $mail->AddAddress($to);
        $mail->Subject = $subject; //Asunto del correo
        $mail->MsgHTML($body);
        $mail->IsHTML(true); // Enviar como HTML
        $mail->Send(); //Enviar
        // echo 'El Mensaje a sido enviado.';
        return true;
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Mensaje de error si se produciera.
        return false;
    }
}

//envio de email por phpmailer [sicoes.com.bo]
function envio_email_cursos_sicoes($correo, $subject, $body) {

    try {
        $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas

        $mail->IsSMTP();                           // Usamos el metodo SMTP de la clase PHPMailer
        $mail->SMTPAuth = true;                  // habilitado SMTP autentificaciÃ³n
        $mail->SMTPSecure = "";
        $mail->Timeout = 30;                  // habilitado SMTP autentificaciÃ³n

        $mail->Port = 25;                // puerto del server SMTP
        $mail->Host = "mail.sicoes.com.bo";  // SMTP server
        $mail->Username = "cursos@sicoes.com.bo";     // SMTP server Usuario
        $mail->Password = "r+Jgw=2u4E?5";            // SMTP server password
        $mail->From = "cursos@sicoes.com.bo"; //Remitente de Correo
        $mail->FromName = "Cursos SICOES"; //Nombre del remitente
        $to = $correo; //Para quien se le va enviar
        $mail->AddAddress($to);
        $mail->Subject = $subject; //Asunto del correo
        $mail->MsgHTML($body);
        $mail->IsHTML(true); // Enviar como HTML
        $mail->Send(); //Enviar
        // echo 'El Mensaje a sido enviado.';
        return true;
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Mensaje de error si se produciera.
        return false;
    }
}

//generado de enlace de descarga de archivo
function enlace_de_descarga_de_archivo($id_empresa, $id_archivo, $correo_de_descarga) {
    $var_send = "user_id:$id_empresa;archivo_id:$id_archivo;email:$correo_de_descarga";
    $tdt5hhtt = urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5("hashhtik"), ($var_send), MCRYPT_MODE_CBC, md5(md5("hashhtik")))));
    return "tdt5hhtt=$tdt5hhtt";
}

//descodificacion objeto nuevo sicoes
function decodificacion_objeto_newsicoes($dat) {

    $busc = array();
    $remm = array();

    $busc[0] = 'ã?O';
    $remm[0] = 'ño';

    $busc[1] = 'ã?N';
    $remm[1] = 'ón';

    $busc[2] = 'Â??';
    $remm[2] = '';

    $busc[3] = 'aâ';
    $remm[3] = 'a';

    $busc[4] = 'sã?';
    $remm[4] = 'sé';

    $busc[5] = 'nâ';
    $remm[5] = 'n';

    $busc[6] = '\"';
    $remm[6] = '';

    $busc[7] = '\\';
    $remm[7] = '';

    $busc[8] = '\\\\';
    $remm[8] = '';

    $busc[9] = "'";
    $remm[9] = ' ';

    $busc[10] = 'â°';
    $remm[10] = 'º';

    $busc[11] = 'ï¿½N';
    $remm[11] = 'ón';

    $busc[12] = 'ï¿½R';
    $remm[12] = 'ér';

    $busc[13] = 'Nï¿½';
    $remm[13] = 'Nº';

    $busc[14] = 'ï¿½L';
    $remm[14] = 'ál';

    $busc[15] = 'ã³N';
    $remm[15] = 'ón';

    $busc[16] = 'ã?A';
    $remm[16] = 'ña';

    $busc[17] = 'ã?R';
    $remm[17] = 'ér';

    $busc[18] = 'ã©C';
    $remm[18] = 'éc';

    $busc[19] = 'ã¡T';
    $remm[19] = 'á';

    $busc[20] = 'ã?C';
    $remm[20] = 'ác';

    $busc[21] = 'ã­O';
    $remm[21] = 'ío';

    $busc[22] = 'ã?S';
    $remm[22] = 'ís';

    $busc[23] = 'ï¿½O';
    $remm[23] = 'ño';

    $rest = str_replace('?', '', ucfirst(strtolower(trim(utf8_encode(str_replace($busc, $remm, $dat))))));

    $busc = array();
    $remm = array();

    $busc[0] = utf8_encode('ã³');
    $remm[0] = utf8_encode('ó');

    $busc[1] = utf8_encode('â');
    $remm[1] = utf8_encode('');

    $busc[2] = utf8_encode('Â');
    $remm[2] = utf8_encode('');

    $busc[3] = utf8_encode('ã¡');
    $remm[3] = utf8_encode('á');

    $busc[4] = utf8_encode('ã±');
    $remm[4] = utf8_encode('ñ');

    $busc[5] = utf8_encode('ciï¿½n');
    $remm[5] = utf8_encode('ción');

    $busc[6] = utf8_encode('?');
    $remm[6] = utf8_encode('');


    $new_objeto = str_replace($busc, $remm, utf8_decode($rest));


    return $new_objeto;
}

//FUNCION FORMATEA MONTO EN DOLAR EURO BOLIVIANO
function monto_dolar_euro($dat) {
    $data = '';
    $rqcm1 = query("SELECT valor FROM conversion_moneda WHERE titulo_identificador='dolar' LIMIT 1");
    $rqcm2 = mysql_fetch_array($rqcm1);
    $valor_dolar = $rqcm2['valor'];
    $rqcm3 = query("SELECT valor FROM conversion_moneda WHERE titulo_identificador='euro' LIMIT 1");
    $rqcm4 = mysql_fetch_array($rqcm3);
    $valor_euro = $rqcm4['valor'];
    $data .= number_format($dat, 2, ',', '.') . " Bs.<br/>";
    $data .= number_format(($dat / $valor_dolar), 2, ',', '.') . " \$us.<br/>";
    $data .= number_format(($dat / $valor_euro), 2, ',', '.') . " &#8364;";
    return $data;
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

/* funcion de muestra css */

function enc_css($data) {
    return date("mdH") . '-' . encrypt($data . '/' . date("H")) . '.estilo';
}

function enc_class($data) {
    return "css" . md5(date("mdH") . '-' . encrypt($data . '/' . date("H")));
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
        //echo "<!-- $urlroute -->";
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
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el lÃ­mite a 6 dÃ­gitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegÃ³ al lÃ­mite mÃ¡ximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dÃ­gitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dÃ­gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es nÃºmero redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo_NL($xaux); // devuelve el subfijo correspondiente (MillÃ³n, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquÃ­ si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lÃ³gica que las centenas)
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
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo_NL($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta lÃ­nea la puedes cambiar de acuerdo a tus necesidades o a tu paÃ­s -------
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
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para MÃ©xico se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
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
        $certificado_id = $cod_i_ciudad . str_pad(rand(1, 99999), 5, "0", STR_PAD_LEFT);
        $rqv1 = query("SELECT count(1) AS total FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' LIMIT 1 ");
        $rqv2 = mysql_fetch_array($rqv1);
        if ($rqv2['total'] == 0) {
            $sw_id_notfounded = false;
        }
    } while ($sw_id_notfounded);
    return $certificado_id;
}



/* CONTENIDO DE CURSO */
function getContenidoCurso($curso) {
    global $dominio;


    /* datos lugar */
    $rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
    $rqdl2 = mysql_fetch_array($rqdl1);
    $lugar_nombre = $rqdl2['nombre'];
    $lugar_salon = $rqdl2['salon'];
    $lugar_direccion = $rqdl2['direccion'];
    $lugar_google_maps = $rqdl2['google_maps'];

    /* ciudad departemento */
    $curso_id_ciudad = $curso['id_ciudad'];
    $rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
    $rqdcd2 = mysql_fetch_array($rqdcd1);
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
        $rqdrd2 = mysql_fetch_array($rqdrd1);
        $docente_nombre = $rqdrd2['prefijo'] . ' ' . $rqdrd2['nombres'];
        $docente_curriculum = $rqdrd2['curriculum'];
    }

    $htm_imagen1 = '';
    if ($curso['imagen'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/imagenes/paginas/" . $curso['imagen'])) {
            $url_img = $dominio . "contenido/imagenes/paginas/" . $curso['imagen'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/imagenes/paginas/" . $curso['imagen'];
        }
        $htm_imagen1 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen2 = '';
    if ($curso['imagen2'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/imagenes/paginas/" . $curso['imagen2'])) {
            $url_img = $dominio . "contenido/imagenes/paginas/" . $curso['imagen2'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/imagenes/paginas/" . $curso['imagen2'];
        }
        $htm_imagen2 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen3 = '';
    if ($curso['imagen3'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/imagenes/paginas/" . $curso['imagen3'])) {
            $url_img = $dominio . "contenido/imagenes/paginas/" . $curso['imagen3'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/imagenes/paginas/" . $curso['imagen3'];
        }
        $htm_imagen3 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_imagen4 = '';
    if ($curso['imagen4'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/imagenes/paginas/" . $curso['imagen4'])) {
            $url_img = $dominio . "contenido/imagenes/paginas/" . $curso['imagen4'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/imagenes/paginas/" . $curso['imagen4'];
        }
        $htm_imagen4 = '<img src="' . $url_img . '" class="img-pag-static"/>';
    }
    $htm_archivo1 = '';
    if ($curso['archivo1'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/archivos/cursos/" . $curso['archivo1'])) {
            $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo1'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo1'];
        }
        $htm_archivo1 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo1'] . '</a>';
    }
    $htm_archivo2 = '';
    if ($curso['archivo2'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/archivos/cursos/" . $curso['archivo2'])) {
            $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo2'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo2'];
        }
        $htm_archivo2 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo2'] . '</a>';
    }
    $htm_archivo3 = '';
    if ($curso['archivo3'] !== '') {
        if (file_exists("/home/hcurso/public_html/archivos/cursos/" . $curso['archivo3'])) {
            $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo3'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo3'];
        }
        $htm_archivo3 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo3'] . '</a>';
    }
    $htm_archivo4 = '';
    if ($curso['archivo4'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/archivos/cursos/" . $curso['archivo4'])) {
            $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo4'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo4'];
        }
        $htm_archivo4 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo4'] . '</a>';
    }
    $htm_archivo5 = '';
    if ($curso['archivo5'] !== '') {
        if (file_exists("/home/hcurso/public_html/contenido/archivos/cursos/" . $curso['archivo5'])) {
            $url_img = $dominio . "contenido/archivos/cursos/" . $curso['archivo5'];
        } else {
            $url_img = "https://www.infosicoes.com/" . "contenido/archivos/cursos/" . $curso['archivo5'];
        }
        $htm_archivo5 = '<a href="' . $url_img . '" class="urlarch-pag-static" target="_blank">' . $curso['archivo5'] . '</a>';
    }
    $rqdwn1 = query("SELECT numero FROM whatsapp_numeros WHERE id='".$curso['id_whats_numero']."' LIMIT 1 ");
    $rqdwn2 = mysql_fetch_array($rqdwn1);
    $cel_wamsm = $rqdwn2['numero'];
    $htm_reportesupago = '<a href="https://cursos.bo/registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="https://cursos.bo/contenido/imagenes/images/reporte-su-pago.png" style=""/></a>';
    $htm_inscripcion = '<div style="text-align:center;"><a href="https://cursos.bo/registro-curso/' . $curso['titulo_identificador'] . '.html" target="_blank"><img src="https://www.carreramenudoscorazones.es/wp-content/uploads/2015/04/BOTON_INSCRIPCION.jpg" style="height:120px;"/></a></div>';
    $htm_whatsapp = "<div style='text-align:center;'><a href='https://api.whatsapp.com/send?phone=591" . $cel_wamsm . "&amp;text=" . str_replace("'", "", str_replace(' ', '%20', str_replace('&', 'y', $curso['whats_mensaje']))) . "'><img src='https://www.infosicoes.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style='height:120px;'/></a></div>";
    $data_nombre_curso = $curso['titulo'];
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
        $txt_descuento_uno_curso = $curso['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha2']) . hora_descuento($curso['fecha2']);
    }
    if ($curso['sw_fecha3'] == '1') {
        $txt_descuento_dos_curso = $curso['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha3']) . hora_descuento($curso['fecha3']);
    }
    if ($curso['sw_fecha_e'] == '1') {
        $txt_descuento_est_curso = $curso['costo_e'] . ' Bs '.numToLiteral($curso['costo_e']).' (Asistir con original y fotocopia de su carnet universitario o instituto)';
    }
    if ($curso['sw_fecha_e2'] == '1') {
        $txt_descuento_est_pre_curso = $curso['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e2']) . hora_descuento($curso['fecha_e2']) . ' para estudiantes. (Asistir con original y fotocopia de su carnet universitario o instituto)';
    }


    $rqdtcb1 = query("SELECT * FROM cuentas_de_banco WHERE id='" . $curso['id_cuenta_bancaria'] . "' ORDER BY id ASC ");
    $rqdtcb2 = mysql_fetch_array($rqdtcb1);
    $data_nombre_banco = $rqdtcb2['banco'];
    $data_numero_cuenta_banco = $rqdtcb2['numero_cuenta'];
    $data_titular_banco = $rqdtcb2['titular'];
    
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
        '[TITULAR-BANCO]'
    );
    $array_palabras_reservadas_remm = array(
        $htm_imagen1,
        $htm_imagen2,
        $htm_imagen3,
        $htm_imagen4,
        'src="https://www.infosicoes.com/paginas',
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
        $data_titular_banco
    );
    $contenido_curso = trim(str_replace($array_palabras_reservadas_busc, $array_palabras_reservadas_remm, $curso['contenido']));

    return $contenido_curso;
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