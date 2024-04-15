<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "AD";
    exit;
}

$id_leccion = post('id_leccion');


/* audio */
if (is_uploaded_file(archivo('audio'))) {
    $nombreaudio = 'AP' . $id_leccion . '-' . str_replace(' ', '-', archivoName('audio'));
    move_uploaded_file(archivo('audio'), '../../audios/presentaciones/' . $nombreaudio);
    query("UPDATE cursos_onlinecourse_lecciones SET audio_presentacion='$nombreaudio' WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    ?>
    <b>AUDIO DE PRESENTACI&Oacute;N SUBIDO EXITOSAMENTE</b>
    <button class="btn btn-info btn-xs btn-block active" onclick="presentacion_leccion_p1('<?php echo $id_leccion; ?>');">
        CONTINUAR
    </button>
    <?php
} else {
    echo "AUDIO NO SUBIDO ID:>$id_leccion<";
    exit;
}


