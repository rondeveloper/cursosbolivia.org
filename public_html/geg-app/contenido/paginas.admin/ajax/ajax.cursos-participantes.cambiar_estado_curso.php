<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador() && !isset_organizador()) {
    echo "DENEGADO";
    exit;
}

if (!acceso_cod('adm-cursos-estado')) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');
$estado = post('estado');

/* curso pre */
$rqdcp1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);

/* last update */
$laststch_fecha = date("Y-m-d H:i");
$laststch_id_administrador = administrador('id');

/* update data */
switch ($estado) {
    case 'activado':
        query("UPDATE cursos SET estado='1',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [ACTIVADO]', 'curso-edicion', 'curso', $id_curso);
        break;
    case 'desactivado':
        query("UPDATE cursos SET estado='0',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'curso-edicion', 'curso', $id_curso);
        break;
    case 'temporal':
        $titulo_identificador .= '-tmp';
        query("UPDATE cursos SET estado='2',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [TEMPORAL]', 'curso-edicion', 'curso', $id_curso);
        break;
    default :
        break;
}

/* curso */
$rqdc1 = query("SELECT id,estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = mysql_fetch_array($rqdc1);

if ($producto['estado'] == '1') {
    ?>
    <i class="btn btn-xs btn-success">Activado</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
    <?php
} elseif ($producto['estado'] == '2') {
    ?>
    <i class="btn btn-xs btn-danger">Temporal</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
    <?php
} else {
    ?>
    <i class="btn btn-xs btn-default active">Desactivado</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
    <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
    <?php
}
?>
&nbsp;|&nbsp;
