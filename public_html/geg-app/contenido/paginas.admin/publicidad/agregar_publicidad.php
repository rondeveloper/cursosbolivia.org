<?php

if(isset($_POST['nombre'])){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];
    if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
        move_uploaded_file(($_FILES['imagen']['tmp_name']), "contenido/imagenes/publicidad/".$_FILES['imagen']['name']);
        $imagen = $_FILES['imagen']['name'];
    }else{
        $imagen = 'sin_foto.png';
    }
    $resultado = mysql_query("INSERT INTO publicidad (nombre,descripcion,url,imagen) VALUES ('".$nombre."','".$descripcion."','".$url."','".$imagen."')")or die(mysql_error());
    if($resultado){
        echo "<h3>Exito al agregar la Publicidad</h3>";
        echo "<hr/>Nombre: ".$nombre;
        echo "<br/>Url: ".$url;
        echo "<br/><img src='contenido/imagenes/publicidad/".$imagen."' width='300px'/>";
        echo "<br/>Descripcion: ".$descripcion;
    }else{
        echo "Error al Agregar la Publicidad!";
    }
    
    echo "<a href='admin.php?publicidad=agregar_publicidad'>Agregar publicidad</a><br/>";
    echo "<a href='admin.php?publicidad=listar_publicidades'>Listar publicidades</a><br/>";
}else{
?>

<h1>Agregar Publicidad</h1>
<br/>
<br/>

<form action="" method="post" enctype="multipart/form-data">
    <table width="100%">
        <tr>
            <td>
                <label for="nombre">Nombre:</label>
                <br/>
                <input type="text" name="nombre" size="70"/>
            </td>
            <td>
                <label for="nombre">Url:</label>
                <br/>
                <input type="text" name="url" size="70"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="nombre">Imagen:</label>
                <br/>
                <input type="file" name="imagen" size="50"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="nombre">Descripcion:</label>
                <br/>
                <textarea name="descripcion" cols="70" rows="12"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br/>
                <br/>
                <p align="center">
                    <input type="submit" value="AGREGAR PUBLICIDAD"/>
                </p>
            </td>
        </tr>
    </table>
</form>

<?php
}
?>