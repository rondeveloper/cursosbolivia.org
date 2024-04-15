<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
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
$rqdcp1 = query("SELECT titulo_identificador FROM blog WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);

/* last update */
$laststch_fecha = date("Y-m-d H:i");
$laststch_id_administrador = administrador('id');

/* update data */
switch ($estado) {
    case 'activado':
        query("UPDATE blog SET estado='1',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [ACTIVADO]', 'blog-edicion', 'blog', $id_curso);
        break;
    case 'desactivado':
        query("UPDATE blog SET estado='0',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'blog-edicion', 'blog', $id_curso);
        break;
    default :
        break;
}

/* curso */
$rqdc1 = query("SELECT id,estado,columna FROM blog WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = fetch($rqdc1);

/* registro */
if ($producto['estado'] == '1') {
    echo "<b style='color:green;'>ACTIVADO</b>";
    echo "<br/><br/>";
    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='$laststch_id_administrador' LIMIT 1 ");
    $rqalc2 = fetch($rqalc1);
    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
    echo '<b>' . date("H:i", strtotime($laststch_fecha)) . '</b> &nbsp; ' . date("d/M", strtotime($laststch_fecha));
    echo "<br/>";
    echo $rqalc2['nombre'];
    echo "</div>";
    ?>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
    <?php
} else {
    ?>
    DESACTIVADO
    <br/>
    <br/>
    <?php
    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='$laststch_id_administrador' LIMIT 1 ");
    $rqalc2 = fetch($rqalc1);
    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
    echo '<b>' . date("H:i", strtotime($laststch_fecha)) . '</b> &nbsp; ' . date("d/M", strtotime($laststch_fecha));
    echo "<br/>";
    echo $rqalc2['nombre'];
    echo "</div>";
    ?>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_blog('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
    <?php
}
                                        
