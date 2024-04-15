<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo 'DENEGADO';
    exit;
}

/* data */
$id_grupo = post('id_grupo');

/* grupo */
$rqdg1 = query("SELECT titulo,fecha,horarios FROM cursos_agrupaciones WHERE id='$id_grupo' LIMIT 1 ");
$rqdg2 = mysql_fetch_array($rqdg1);
$nombre_grupo = $rqdg2['titulo'];
$fecha_grupo = $rqdg2['fecha'];
$horarios_grupo = $rqdg2['horarios'];
?>

<h3><?php echo $nombre_grupo; ?></h3>
<hr>

<form action="" method="post">
    <b>Fecha:</b>
    <input type="date" name="fecha" class="form-control" value="<?php echo $fecha_grupo; ?>"/>
    <br>
    <b>Horarios:</b>
    <input type="text" name="horarios" class="form-control" value="<?php echo $horarios_grupo; ?>"/>
    <br>
    <b>Cursos:</b>
    <?php
    $rqcaag1 = query("SELECT id,titulo FROM cursos WHERE id IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ");
    $cnt_cursos = mysql_num_rows($rqcaag1);
    if($cnt_cursos==0){
        echo '<br>Sin cursos asociados.';
    }
    $cnt_aux = 1;
    while ($rqcaag2 = mysql_fetch_array($rqcaag1)) {
        ?>
        <br/>
        <input type="checkbox" name="c-<?php echo $cnt_aux; ?>" value="<?php echo $rqcaag2['id']; ?>" checked=""/>
        &nbsp;
        <?php echo $rqcaag2['titulo']; ?>
        <?php
    }
    ?>
    
    <br>
    <br>
    <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>"/>
    <input type="hidden" name="cnt_cursos" value="<?php echo $cnt_cursos; ?>"/>
    <input type="submit" name="duplicar-grupo" class="btn btn-block btn-success" value="DUPLICAR GRUPO"/>
</form>

<hr>
