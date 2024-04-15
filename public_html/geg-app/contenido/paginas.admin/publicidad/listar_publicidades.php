<?php
//
?>
<h1>PUBLICIDAD listado de publicidades existentes</h1>

<br/>
<br/>

<?php

$resultado_publicidad = mysql_query("SELECT * FROM publicidad ORDER BY id DESC") or die(mysql_error());
while($publicidad = mysql_fetch_array($resultado_publicidad)){
    echo "<br/>Nombre: " . $publicidad['nombre'];
    echo "<br/>Url: " . $publicidad['url'];
    echo "<br/><img src='contenido/imagenes/publicidad/" . $publicidad['imagen'] . "' width='200px'/>";
    echo "<br/>Descripcion: " . $publicidad['descripcion']."<br/><br/><br/>";
}
?>