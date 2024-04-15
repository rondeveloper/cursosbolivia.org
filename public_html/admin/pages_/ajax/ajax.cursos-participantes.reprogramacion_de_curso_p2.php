<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);

/* data */
$nombre = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
$ci = $rqdp2['ci'];
$id_curso = $rqdp2['id_curso'];
$motivo_reprogramacion = post('motivo_reprogramacion');
$fecha_tentativa = post('fecha_tentativa');
$correo = post('correo');
$celular = post('celular');
$observacion = post('observacion');
$fecha_registro = date("Y-m-d H:i");
$id_administrador = administrador('id');

/* registro */
query("INSERT INTO cursos_reprogramacion_participantes(
        id_participante, 
        id_curso, 
        id_administrador, 
        nombre, 
        ci, 
        motivo_reprogramacion, 
        fecha_tentativa, 
        correo, 
        celular, 
        observacion, 
        fecha_registro, 
        estado
        ) VALUES (
        '$id_participante',
        '$id_curso',
        '$id_administrador',
        '$nombre',
        '$ci',
        '$motivo_reprogramacion',
        '$fecha_tentativa',
        '$correo',
        '$celular',
        '$observacion',
        '$fecha_registro',
        '1'
        )");
$id_reprogramacion = insert_id();
$codigo_reprogramacion = 'REP00'.$id_reprogramacion;
query("UPDATE cursos_reprogramacion_participantes SET codigo='$codigo_reprogramacion' WHERE id='$id_reprogramacion' ORDER BY id DESC limit 1 ");
logcursos('Reprogramacion de participacion ['.$codigo_reprogramacion.']', 'reprogramacion-asistencia', 'participante', $id_participante);
$url_ficha = $dominio."contenido/paginas/procesos/pdfs/ficha-reprogramacion.php?codigo=$codigo_reprogramacion";
?>
<td colspan="2">
    <div class="alert alert-success">
        <strong>Exito!</strong> registro agregado correctamente.
    </div>
    <hr/>
    Codigo de reprogramaci&oacute;n: <b><?php echo $codigo_reprogramacion; ?></b>
    <hr/>
    Ficha de reprogramaci&oacute;n: &nbsp; <b class="btn btn-lg btn-primary active" onclick="window.open('<?php echo $url_ficha; ?>', 'popup', 'width=700,height=500');">VISUALIZAR FICHA</b>
</td>



