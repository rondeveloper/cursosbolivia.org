<?php

session_start();

error_reporting(0);

include_once 'config.php';
include_once 'funciones.php';

mysql_connect($hostname, $username, $password);
mysql_select_db($database);

$data_seccion = get('seccion');

encrypt($data . '/' . date("H"));

$proc_data_1 = substr($data_seccion, 7);
$proc_data_2 = decrypt($proc_data_1);
$ar1 = explode('/', $proc_data_2);

$pagina_css = $ar1[0];
$hour = $ar1[1];

if ($hour !== date("H")) {
    echo "Acceso denegado!";
    exit;
}


$cont = file_get_contents("http://www.infosicoes.com/contenido/css/$pagina_css");

$busc = array('../imagenes/images/');
$remm = array('contenido/imagenes/images/');

/* pagina licitacion */

array_push($busc, '.licitacion-tabla1{');
array_push($remm, '.licitacion-tabla1,.'.enc_class('licitacion-tabla1').'{');

array_push($busc, '.licitacion-tabla1 td{');
array_push($remm, '.licitacion-tabla1 td,.'.enc_class('licitacion-tabla1').' td{');


/* pagina convocatorias_nacionales */

array_push($busc, ".tabla_1 {");
array_push($remm, ".tabla_1,.".enc_class('tabla_1')." {");

array_push($busc, ".tabla_1 th {");
array_push($remm, ".tabla_1 th,.".enc_class('tabla_1')." th {");

array_push($busc, ".tabla_1 td {");
array_push($remm, ".tabla_1 td,.".enc_class('tabla_1')." td {");

array_push($busc, ".tabla_1 a{");
array_push($remm, ".tabla_1 a,.".enc_class('tabla_1')." a {");

array_push($busc, ".tabla_1 a:hover{");
array_push($remm, ".tabla_1 a:hover,.".enc_class('tabla_1')." a:hover {");




$show_cont = str_replace($busc, $remm, $cont);
header("Content-type: text/css", true);
echo $show_cont;


?>

