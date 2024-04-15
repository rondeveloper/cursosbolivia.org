<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_grupo = post('id_grupo');

/* grupo */
$rqdm1 = query("SELECT * FROM cursos_agrupaciones WHERE id='$id_grupo' LIMIT 1 ");
$rqdm2 = mysql_fetch_array($rqdm1);
$nombre_grupo = $rqdm2['nombre_grupo'];
$estado_grupo = $rqdm2['estado'];

echo "<h3 style='margin-top: 0px;font-weight: bold;'>$nombre_grupo</h3>";
echo "<hr/>";
?>
<hr/>
<form action="" method="post">
    <div class="form-group">
        <label for="nombre_grupo">Nombre del grupo:</label>
        <input type="text" class="form-control" name="nombre_grupo" id="nombre_grupo" placeholder="Nombre del grupo..." required="" value="<?php echo $nombre_grupo; ?>"/>
    </div>
    <div class="form-group">
        <label for="nombre_grupo">Estado:</label>
        <?php
        $ckeck1 = '';
        $ckeck2 = ' checked ';
        if ($estado_grupo == '1') {
            $ckeck1 = ' checked ';
            $ckeck2 = '';
        }
        ?>
        <div class="radio">
            <label><input type="radio" value="1" name="estado" <?php echo $ckeck1; ?>>Activado</label>
        </div>
        <div class="radio">
            <label><input type="radio" value="2" name="estado" <?php echo $ckeck2; ?>>Des-activado</label>
        </div>
    </div>
    <input type="hidden" class="form-control" name="id_grupo" value="<?php echo $id_grupo; ?>">
    <div class="row">
        <div class="col-md-6">
            <input type="submit" class="btn btn-default" name="actualizar-grupo" value="ACTUALIZAR GRUPO"/>
        </div>
        <div class="col-md-6 text-right">
            <input type="submit" class="btn btn-danger" name="eliminar-grupo" value="ELIMINAR GRUPO"/>
        </div>
    </div>
</form>
<hr/>
