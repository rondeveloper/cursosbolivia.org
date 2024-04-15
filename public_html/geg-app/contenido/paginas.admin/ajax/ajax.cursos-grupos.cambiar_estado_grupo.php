<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

if (!acceso_cod('adm-cursos-estado')) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_grupo = post('id_grupo');
$estado = post('estado');

/* curso pre */
$rqdcp1 = query("SELECT titulo_identificador FROM cursos_agrupaciones WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);

/* last update */
$laststch_fecha = date("Y-m-d H:i");
$laststch_id_administrador = administrador('id');

/* update data */
switch ($estado) {
    case 'activado':
        query("UPDATE cursos_agrupaciones SET estado='1',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [ACTIVADO]', 'grupo-edicion', 'grupo', $id_grupo);
        break;
    case 'desactivado':
        query("UPDATE cursos_agrupaciones SET estado='0',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'grupo-edicion', 'grupo', $id_grupo);
        break;
    default :
        break;
}

/* curso */
$rqdc1 = query("SELECT id,estado FROM cursos_agrupaciones WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
$producto = mysql_fetch_array($rqdc1);

/* registro */
if ($producto['estado'] == '1') {
    ?>
    <b style='color:green;'>ACTIVADO</b>
    <br/>
    <br/>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_grupo('<?php echo $producto['id']; ?>', 'temporal');">Temporal</i>
    <?php
} else {
    ?>
    DESACTIVADO
    <br/>
    <br/>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_grupo('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
    <?php
}
                                        
