<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
$rqdc2 = fetch($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);


/* update data */
switch ($estado) {
    case 'activado':
        query("UPDATE cursos_onlinecourse SET estado='1' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [ACTIVADO]', 'curso-virtual-edicion', 'curso-virtual', $id_curso);
        break;
    case 'desactivado':
        query("UPDATE cursos_onlinecourse SET estado='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'curso-virtual-edicion', 'curso-virtual', $id_curso);
        break;
    default :
        break;
}


/* curso */
$rqdc1 = query("SELECT id,estado FROM cursos_onlinecourse WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = fetch($rqdc1);
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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div>
                <input type="button" value="ACTIVAR" class="btn btn-success btn-sm btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');"/>
            </div>
        </div>
        <?php
    }
}


