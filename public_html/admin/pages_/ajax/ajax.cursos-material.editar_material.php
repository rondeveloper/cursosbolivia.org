<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_material = post('id_material');

/* material */
$rqdm1 = query("SELECT * FROM cursos_material WHERE id='$id_material' LIMIT 1 ");
$rqdm2 = fetch($rqdm1);
$nombre_material = $rqdm2['nombre_material'];
$estado_material = $rqdm2['estado'];

echo "<h3 style='margin-top: 0px;font-weight: bold;'>$nombre_material</h3>";
echo "<hr/>";
?>
<hr/>
<form action="" method="post">
    <div class="form-group">
        <label for="nombre_material">Nombre del paquete de materiales:</label>
        <input type="text" class="form-control" name="nombre_material" id="nombre_material" placeholder="Nombre del paquete de materiales..." required="" value="<?php echo $nombre_material; ?>"/>
    </div>
    <div class="form-group">
        <label for="nombre_material">Estado:</label>
        <?php
        $ckeck1 = '';
        $ckeck2 = ' checked ';
        if ($estado_material == '1') {
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
    <input type="hidden" class="form-control" name="id_material" value="<?php echo $id_material; ?>">
    <div class="row">
        <div class="col-md-6">
            <input type="submit" class="btn btn-default" name="actualizar-material" value="ACTUALIZAR MATERIAL"/>
        </div>
        <div class="col-md-6 text-right">
            <input type="submit" class="btn btn-danger" name="eliminar-material" value="ELIMINAR MATERIAL"/>
        </div>
    </div>
</form>
<hr/>
