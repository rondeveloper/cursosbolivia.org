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
$rqdcp1 = query("SELECT id_modalidad,titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdcp1);
$titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);
$id_modalidad = $rqdc2['id_modalidad'];

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

/* actualizacion html curso */
file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=curso-individual&id_curso=".$id_curso);
file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=inicio");

/* actualiza bloque ids */
/* bloque activo */
$rqbids1 = query("SELECT id FROM cursos WHERE estado=1 ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='1' ");
/* bloque temporal */
$rqbids1 = query("SELECT id FROM cursos WHERE estado=2 ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='2' ");
/* bloque virtual */
if($id_modalidad!='1'){
    $rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad!='1' ");
    $cnt = num_rows($rqbids1);
    $ids = '0';
    while($rqbids2 = fetch($rqbids1)){
        $ids .= ','.$rqbids2['id'];
    }
    query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='3' ");
}

/* curso */
$rqdc1 = query("SELECT id,estado,columna FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = fetch($rqdc1);

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
    $rqalc2 = fetch($rqalc1);
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
    $rqalc2 = fetch($rqalc1);
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
    $rqalc2 = fetch($rqalc1);
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
                                        
