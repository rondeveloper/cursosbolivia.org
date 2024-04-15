<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    exit;
}

//$id_curso = 2330;
//$id_curso = 2328;
$id_curso = post('id_curso');
$mod = post('mod');
$ids = post('ids') == '' ? '0' : post('ids');

/* curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo'];

$qr_depo = "";
$aux_txt_depo = "-con-deposito-";
$aux_dir_depo = " C-DEPOSITO";
if($mod=='2'){
    $qr_depo = " AND d.codigo<>'dep-iplc' ";
    $aux_txt_depo = "";
    $aux_dir_depo = "";
}

$qr_foto = "";
if($mod=='3'){
    $qr_foto = " AND d.codigo='fotocarnet' ";
    $aux_txt_depo = "-solo-fotos-";
    $aux_dir_depo = " FOTOS";
}

$micarpeta = 'DOCS'.$id_curso.' - '.$nombre_curso . $aux_dir_depo;
if (!file_exists($micarpeta)) {
    mkdir($micarpeta, 0777, true);
}


$arr_busc = array('ci-anverso', 'ci-reverso', 'titulo', 'dep-iplc');
$arr_remm = array('C.I. anverso', 'C.I. reverso', 'TITULO', 'DEPOSITO');

$rqdl1 = query("SELECT u.id,u.nombres,u.apellidos FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id INNER JOIN documentos_usuario d ON d.id_usuario=u.id WHERE p.id_curso='$id_curso' AND p.id IN ($ids) $qr_depo $qr_foto GROUP BY u.id ORDER BY p.id DESC ");
if(num_rows($rqdl1)==0){
    echo '<div class="alert alert-warning">
  <strong>AVISO</strong> no se encontraron registros.
</div>';
    exit;
}
while ($rqdl2 = fetch($rqdl1)) {
    $micarpeta_part = $micarpeta . '/' . strtoupper($rqdl2['apellidos'] . ' ' . $rqdl2['nombres']);
    if (!file_exists($micarpeta_part)) {
        mkdir($micarpeta_part, 0777, true);
    }
    $rqddu1 = query("SELECT * FROM documentos_usuario d WHERE d.id_usuario='" . $rqdl2['id'] . "' $qr_depo $qr_foto ");
    while ($rqddu2 = fetch($rqddu1)) {
        $source = "../../imagenes/doc-usuarios/" . $rqddu2['nombre'];
        $dest = $micarpeta_part.'/'.str_replace($arr_busc, $arr_remm, $rqddu2['codigo']) .'-'.$rqddu2['nombre'];
        if (!file_exists($dest)) {
            copy($source, $dest);
            //echo "<b>COPIADO</b> ";
        }
    }
}

/* primero creamos la función que hace la magia
 * esta funcion recorre carpetas y subcarpetas
 * añadiendo todo archivo que encuentre a su paso
 * recibe el directorio y el zip a utilizar 
 */

function agregar_zip($dir, $zip) {
    //verificamos si $dir es un directorio
    if (is_dir($dir)) {
        //abrimos el directorio y lo asignamos a $da
        if ($da = opendir($dir)) {
            //leemos del directorio hasta que termine
            while (($archivo = readdir($da)) !== false) {
                /* Si es un directorio imprimimos la ruta
                 * y llamamos recursivamente esta función
                 * para que verifique dentro del nuevo directorio
                 * por mas directorios o archivos
                 */
                if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    //echo "<strong>Creando directorio: $dir$archivo</strong><br/>";
                    agregar_zip($dir . $archivo . "/", $zip);

                    /* si encuentra un archivo imprimimos la ruta donde se encuentra
                     * y agregamos el archivo al zip junto con su ruta 
                     */
                } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
                    //echo "Agregando archivo: $dir$archivo <br/>";
                    $zip->addFile($dir . $archivo, $dir . $archivo);
                }
            }
            //cerramos el directorio abierto en el momento
            closedir($da);
        }
    }
}

//fin de la función
//creamos una instancia de ZipArchive
$zip = new ZipArchive();

/* directorio a comprimir
 * la barra inclinada al final es importante
 * la ruta debe ser relativa no absoluta
 */

//$dir = 'fuente/';
$dir = $micarpeta . '/';

//ruta donde guardar los archivos zip, ya debe existir
$rutaFinal = "comprimidos";

if (!file_exists($rutaFinal)) {
    mkdir($rutaFinal);
}

$archivoZip = "DOCUMENTOS-".$id_curso."-$aux_txt_depo-".date("d-M-Y")."--".date("H-i")."-h---".rand(9999,99999999).".zip";

if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
    agregar_zip($dir, $zip);
    $zip->close();

    //Muevo el archivo a una ruta
    //donde no se mezcle los zip con los demas archivos
    rename($archivoZip, "$rutaFinal/$archivoZip");

    //Hasta aqui el archivo zip ya esta creado
    //Verifico si el archivo ha sido creado
    if (file_exists($rutaFinal . "/" . $archivoZip)) {
        echo '<div class="alert alert-success">
  <strong>EXITO</strong> regsitros completos, proceso finalizado!!
  <br/><br/>Descargar: <a href="'.$dominio_admin.'pages/ap/'.$rutaFinal.'/'.$archivoZip.'">'.$archivoZip.'</a>
</div>';
    } else {
        echo "Error, archivo zip no ha sido creado!!";
    }
}
