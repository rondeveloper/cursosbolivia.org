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
$rqdc1 = query("SELECT id,estado,columna FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = mysql_fetch_array($rqdc1);
?>

<div class="col-md-3">
    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
</div>

<?php
if ($producto['estado'] == '1') {
    ?>
    <div class="col-md-5">
        <span class="input-group-addon"><b style='color:green;'>ACTIVADO</b></span>
    </div>
    <?php
    if (acceso_cod('adm-cursos-estado')) {
        ?>
        <div class="col-md-2">
            <div>
                <input type="button" value="TERMPORALIZAR" class="btn btn-danger btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');"/>
            </div>
        </div>
        <div class="col-md-2">
            <div>
                <input type="button" value="DESACTIVAR" class="btn btn-default btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');"/>
            </div>
        </div>
        <?php
    }
} elseif ($producto['estado'] == '2') {
    ?>
    <div class="col-md-5">
        <span class="input-group-addon"><b style='color:red;'>TEMPORAL</b></span>
    </div>
    <?php
    if (acceso_cod('adm-cursos-estado')) {
        ?>
        <div class="col-md-2">
            <div>
                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');"/>
            </div>
        </div>
        <div class="col-md-2">
            <div>
                <input type="button" value="DESACTIVAR" class="btn btn-default btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');"/>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="col-md-5">
        <span class="input-group-addon">DESACTIVADO</span>
    </div>
    <?php
    if (acceso_cod('adm-cursos-estado')) {
        ?>
        <div class="col-md-2">
            <div>
                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');"/>
            </div>
        </div>
        <div class="col-md-2">
            <div>
                <input type="button" value="TERMPORALIZAR" class="btn btn-danger btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');"/>
            </div>
        </div>
        <?php
    }
}
                                