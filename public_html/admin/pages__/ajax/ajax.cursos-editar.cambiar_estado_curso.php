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

/* bloque presencial */
$rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad='1' ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='4' ");


/* bloque pregrabado */
$rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad='2' ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='5' ");


/* bloque en-vivo */
$rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad='3' ");
$cnt = num_rows($rqbids1);
$ids = '0';
while($rqbids2 = fetch($rqbids1)){
    $ids .= ','.$rqbids2['id'];
}
query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='6' ");

/* curso */
$rqdc1 = query("SELECT id,estado,columna FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
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
                                