<?php

error_reporting(0);

include_once 'config.php';

$ruta = utf8_decode($_GET['seccion']);

if (strpos($ruta, '.size=')) {
    $arr1 = explode('.size=', $ruta);
    $ruta = $arr1[0];
    $size = $arr1[1];
} else {
    $size = 0;
}

//Ruta de la imagen original
$rutaImagenOriginal = '../../contenido/imagenes/' . $ruta;

if (file_exists($rutaImagenOriginal)) {

//Creamos una variable imagen a partir de la imagen original

    if (strpos(strtolower($ruta), '.jpg')) {
        $img_original = imagecreatefromjpeg($rutaImagenOriginal);
    } elseif (strpos(strtolower($ruta), '.jpeg')) {
        $img_original = imagecreatefromjpeg($rutaImagenOriginal);
    } elseif (strpos(strtolower($ruta), '.png')) {
        $img_original = imagecreatefrompng($rutaImagenOriginal);
    } elseif (strpos(strtolower($ruta), '.gif')) {
        $img_original = imagecreatefromgif($rutaImagenOriginal);
    } elseif (strpos(strtolower($ruta), '.wbmp')) {
        $img_original = imagecreatefromwbmp($rutaImagenOriginal);
    } else {
        $rutaImagenOriginal = '../../contenido/imagenes/images/imagen-no-disponible.png';
        $img_original = imagecreatefrompng($rutaImagenOriginal);
    }
} else {
    $rutaImagenOriginal = '../../contenido/imagenes/images/imagen-no-disponible.png';
    $img_original = imagecreatefrompng($rutaImagenOriginal);
}

//Ancho y alto de la imagen original
list($ancho, $alto) = getimagesize($rutaImagenOriginal);

//Se define el maximo ancho y alto que tendra la imagen final
switch ($size) {
    case '1':
        //muy pequeño
        $max_ancho = 55;
        $max_alto = 55;
        break;
    case '2':
        //pequeño
        $max_ancho = 200;
        $max_alto = 200;
        break;
    case '3':
        //normal
        $max_ancho = 400;
        $max_alto = 400;
        break;
    case '4':
        //grande
        $max_ancho = 600;
        $max_alto = 600;
        break;
    case '5':
        //muy grande
        $max_ancho = 800;
        $max_alto = 800;
        break;
    case '5':
        //extra grande
        $max_ancho = 1100;
        $max_alto = 1000;
        break;
    default:
        //original
        $max_ancho = $ancho;
        $max_alto = $alto;
        break;
}

//Se calcula ancho y alto de la imagen final
$x_ratio = $max_ancho / $ancho;
$y_ratio = $max_alto / $alto;


//Si el ancho y el alto de la imagen no superan los maximos,
//ancho final y alto final son los que tiene actualmente
if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {//Si ancho
    $ancho_final = $ancho;
    $alto_final = $alto;
}

//si no alcanza
//if ($x_ratio < $max_ancho) {//Si ancho
//    $y_ratio = ($max_alto / $alto) ;
//    $x_ratio = $max_ancho;
//}

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

$blanco = imagecolorallocate($tmp, 255, 255, 255);

imagefill($tmp,0,0,$blanco);

//Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

//Se destruye variable $img_original para liberar memoria
imagedestroy($img_original);

Header("Content-type: image/jpeg");
imagejpeg($tmp);


?>