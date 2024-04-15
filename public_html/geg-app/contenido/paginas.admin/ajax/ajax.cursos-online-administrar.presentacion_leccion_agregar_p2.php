<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_leccion = post('id_leccion');

$duracion1 = (int)post('duracion1');
$duracion2 = (int)post('duracion2');
$duracion = ($duracion1*60)+$duracion2;

/* imagen */
if (is_uploaded_file(archivo('imagen'))) {
    //*echo "IMG SUBIDO $id_leccion<";
    $nombreimagen = 'L' . $id_leccion . '-' . str_replace(' ', '-', archivoName('imagen'));
    move_uploaded_file(archivo('imagen'), '../../imagenes/presentaciones/'.$nombreimagen);
    query("INSERT INTO cursos_onlinecourse_presentaciones (id_leccion,imagen,duracion_audio) VALUES ('$id_leccion','$nombreimagen','$duracion') ");
    ?>
    <b>PRESENTACION SUBIDA EXITOSAMENTE</b>
    <hr/>
    <button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_p1('<?php echo $id_leccion; ?>');">
        CONTINUAR
    </button>
    <br/>
    <br/>
    <?php
} else {
    echo "IMG NO SUBIDO $id_leccion<";
    exit;
}


