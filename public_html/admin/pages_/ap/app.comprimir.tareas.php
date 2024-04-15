<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/*
if (!isset_docente()) {
    exit;
}
*/

$id_tarea = post('id_tarea');
$id_rel_cursoonlinecourse = post('id_rel_cursoonlinecourse');

/* CARPETA */
$micarpeta = 'ZIP-TAREA-ID'.$id_tarea;
if (!file_exists($micarpeta)) {
    mkdir($micarpeta, 0777, true);
}

$rqt1 = query("SELECT u.apellidos,u.nombres,e.id,e.archivo,e.calificacion FROM cursos_onlinecourse_tareasenvios e INNER JOIN cursos_usuarios u ON e.id_usuario=u.id WHERE e.id_tarea='$id_tarea' ORDER BY u.apellidos ASC ");
if (num_rows($rqt1) == 0) {
    echo '<div class="alert alert-warning">
  <strong>AVISO</strong> no se encontraron registros.
</div>';
    exit;
}

while ($rqdl2 = fetch($rqt1)) {
    $micarpeta_part = $micarpeta . '/' . strtoupper($rqdl2['apellidos'] . ' ' . $rqdl2['nombres']);
    if (!file_exists($micarpeta_part)) {
        mkdir($micarpeta_part, 0777, true);
    }
    $source = $___path_raiz."contenido/archivos/tareas/" . $rqdl2['archivo'];
    $dest = $micarpeta_part.'/'.$rqdl2['archivo'];
    if (!file_exists($dest)) {
        copy($source, $dest);
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

$archivoZip = "TAREA-".$id_tarea."-".date("d-M-Y")."--".date("H-i")."---".rand(9999,99999999).".zip";

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
  <strong>EXITO</strong> registros completos, proceso finalizado!!
  <br/><br/>Descargar: <a href="'.$dominio_admin.'pages/ap/'.$rutaFinal.'/'.$archivoZip.'" style="text-decoration: underline;color: blue;">'.$archivoZip.'</a>
</div>';
    } else {
        echo "Error, archivo zip no ha sido creado!!";
    }
}else {
    echo "No se pudo abrir!!";
}
