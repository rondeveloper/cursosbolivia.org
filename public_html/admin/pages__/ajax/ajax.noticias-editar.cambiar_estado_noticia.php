<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

if (!acceso_cod('adm-paginas')) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_noticia = post('id_noticia');
$estado = post('estado');

/* noticia pre */
$rqdcp1 = query("SELECT titulo_identificador FROM publicaciones WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);


/* update data */
switch ($estado) {
    case 'activado':
        query("UPDATE publicaciones SET estado='1',titulo_identificador='$titulo_identificador' WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [ACTIVADO]', 'noticia-edicion', 'noticia', $id_noticia);
        break;
    case 'desactivado':
        query("UPDATE publicaciones SET estado='0',titulo_identificador='$titulo_identificador' WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'noticia-edicion', 'noticia', $id_noticia);
        break;
    default :
        break;
}

/* noticia */
$rqdc1 = query("SELECT id,estado FROM publicaciones WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
$noticia = fetch($rqdc1);
?>


<div class="col-md-3">
    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
</div>

<?php
if ($noticia['estado'] == '1') {
    ?>
    <div class="col-md-5">
        <span class="input-group-addon"><b style='color:green;'>ACTIVADO</b></span>
    </div>
    <?php
    if (acceso_cod('adm-paginas')) {
        ?>
        <div class="col-md-4 text-right">
            <input type="button" value="DESACTIVAR" class="btn btn-default btn-xs" onclick="cambiar_estado_noticia('<?php echo $noticia['id']; ?>', 'desactivado');"/>
        </div>
        <?php
    }
} else {
    ?>
    <div class="col-md-5">
        <span class="input-group-addon">DESACTIVADO</span>
    </div>
    <?php
    if (acceso_cod('adm-paginas')) {
        ?>
        <div class="col-md-4 text-right">
            <input type="button" value="ACTIVAR" class="btn btn-success btn-xs" onclick="cambiar_estado_noticia('<?php echo $noticia['id']; ?>', 'activado');"/>
        </div>
        <?php
    }
}


     