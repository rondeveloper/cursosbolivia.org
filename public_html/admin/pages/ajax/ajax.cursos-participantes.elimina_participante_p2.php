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

/* datos recibidos */
$id_participante = post('id_participante');
$apartar = (int)post('apartar');
$motivo = post('motivo');
$id_administrador = administrador('id');
if ($apartar == 1 && false) {
    query("INSERT INTO cursos_part_apartados (id_participante,fecha) VALUES ('$id_participante','".date("Y-m-d")."') ");
    logcursos('Participante apartado de lista de procesos', 'partipante-apartado', 'participante', $id_participante);
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> participante apartado de la lista de procesos.
    </div>
    <?php
} else {
    query("UPDATE cursos_participantes SET estado='0' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de participante [deshabilitacion]', 'partipante-deshabilitacion', 'participante', $id_participante);

    /* id curso */
    $rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdc2 = fetch($rqdc1);
    $id_curso = (int) $rqdc2['id_curso'];

    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* registro de motivo de eliminacion */
    $archivo = "";
    if (is_uploaded_file(archivo('archivo'))) {
        $archivo = 'me-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('archivo')), (strlen(archivoName('archivo')) - 7));
        move_uploaded_file(archivo('archivo'), $___path_raiz.'contenido/imagenes/doc-usuarios/' . $archivo);
    }
    query("INSERT INTO eliminacion_participantes (id_participante,id_administrador,motivo,archivo,fecha) VALUES ('$id_participante','$id_administrador','$motivo','$archivo',NOW()) ");

    /* datos curso */
    $rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = fetch($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        $rqdcp1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
        $rqdcp2 = fetch($rqdcp1);
        $nombre_participante = trim($rqdcp2['nombres'] . ' ' . $rqdcp2['apellidos']);
        logcursos('Eliminacion de participante [fuera de fecha][' . $nombre_participante . ']', 'curso-edicion', 'curso', $id_curso);
    }
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> participante eliminado correctamente.
    </div>
    <?php
}