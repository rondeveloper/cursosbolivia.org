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

/* registro */
if ($producto['estado'] == '1') {
    echo "<b style='color:green;'>ACTIVADO</b>";
    echo "<br/><br/>";
    /*
      switch ($producto['columna']) {
      case '1':
      echo "(1ra) PRIMERA<br/>Columna";
      break;
      case '2':
      echo "(2da) SEGUNDA<br/>Columna";
      break;
      case '3':
      echo "(3ra) TERCERA<br/>Columna";
      break;
      default :
      echo "NO VISIBLE<br/>EN INICIO";
      break;
      }
     */
    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='$laststch_id_administrador' LIMIT 1 ");
    $rqalc2 = mysql_fetch_array($rqalc1);
    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
    echo '<b>' . date("H:i", strtotime($laststch_fecha)) . '</b> &nbsp; ' . date("d/M", strtotime($laststch_fecha));
    echo "<br/>";
    echo $rqalc2['nombre'];
    echo "</div>";
    ?>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');">Temporal</i>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
    <?php
} elseif ($producto['estado'] == '2') {
    ?>
    <b style='color:red;'>TEMPORAL</b>
    <br/>
    <br/>
    <?php
    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='$laststch_id_administrador' LIMIT 1 ");
    $rqalc2 = mysql_fetch_array($rqalc1);
    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
    echo '<b>' . date("H:i", strtotime($laststch_fecha)) . '</b> &nbsp; ' . date("d/M", strtotime($laststch_fecha));
    echo "<br/>";
    echo $rqalc2['nombre'];
    echo "</div>";
    ?>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'desactivado');">Desactivado</i>
    <?php
} else {
    ?>
    DESACTIVADO
    <br/>
    <br/>
    <?php
    $rqalc1 = query("SELECT nombre FROM administradores WHERE id='$laststch_id_administrador' LIMIT 1 ");
    $rqalc2 = mysql_fetch_array($rqalc1);
    echo "<div style='text-align:center;color:gray;font-size:8pt;'>";
    echo '<b>' . date("H:i", strtotime($laststch_fecha)) . '</b> &nbsp; ' . date("d/M", strtotime($laststch_fecha));
    echo "<br/>";
    echo $rqalc2['nombre'];
    echo "</div>";
    ?>
    <br/><i style="font-size:7pt;color:gray;">Cambiar:</i>
    <br/>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'activado');">Activado</i>
    <i class="btn btn-xs btn-default btn-block" onclick="cambiar_estado_curso('<?php echo $producto['id']; ?>', 'temporal');">Temporal</i>
    <?php
}
                                        
