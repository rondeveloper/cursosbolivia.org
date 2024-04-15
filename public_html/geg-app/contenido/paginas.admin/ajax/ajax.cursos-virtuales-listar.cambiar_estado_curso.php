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
$producto = mysql_fetch_array($rqdc1);

/* registro */
if ($producto['estado'] == '1') {
    echo "<b style='color:green;'>ACTIVADO</b>";
    echo "<br/>";
    ?>
    <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
    <?php
} else {
    ?>
    DESACTIVADO<br/>
    <br/><br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
    <?php
}
                                        
