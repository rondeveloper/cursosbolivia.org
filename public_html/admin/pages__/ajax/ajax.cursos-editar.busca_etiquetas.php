<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$tag = post('tag');
$id_curso = post('id_curso');

if($tag==''){
    exit;
}

$rqdcct1 = query("SELECT id,tag FROM cursos_tags WHERE tag LIKE '%$tag%' AND id NOT IN (select id_tag from cursos_rel_cursostags where id_curso='$id_curso') ORDER BY tag ASC limit 7 ");
echo "<table class='table table-hover'>";
if(num_rows($rqdcct1)==0){
    echo "<tr>";
    echo "<td>";
    echo "NO SE ENCONTRARON etiquetas relacionadas a $tag<br/><br/>(puede agregar nuevas etiquetas en la aseccion 'Etiquetas')";
    echo "</td>";
    echo "</tr>";
}
while ($rqdcct2 = fetch($rqdcct1)) {
    echo "<tr id='idtag-".(int)$rqdcct2['id']."'>";
    echo "<td>";
    echo "<b class='btn btn-warning active'>" . $rqdcct2['tag']."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "</td>";
    echo "<td>";
    echo "<a class='btn btn-default btn-xs' onclick='asocia_etiqueta(".(int)$rqdcct2['id'].");'>AGREGAR</a> <br/><br/>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";