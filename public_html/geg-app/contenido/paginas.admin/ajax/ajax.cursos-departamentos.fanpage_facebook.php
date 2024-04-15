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

$id_departamento = post('id_departamento');

$resultado1 = query("SELECT * FROM departamentos WHERE id='$id_departamento' LIMIT 1 ");
$departamento = mysql_fetch_array($resultado1);
$url_facebook = $departamento['url_facebook'];

?>
<div class="row">
    <div class="col-md-10">
        <b style="font-size:15pt;font-weight:bold;color:#00789f;"><?php echo $departamento['nombre']; ?></b>
        <br/>
        <?php
        if ($url_facebook!=='') {
            ?>
            Facebook: SI
            <?php
        } else {
            ?>
            Facebook: NO
            <?php
        }
        ?>
    </div>
    <div class="col-md-2 text-center">
        <img src="<?php echo "contenido/imagenes/departamentos/" . $departamento['imagen']; ?>" style="height:65px;width:65px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
    </div>
</div>
<?php
if ($url_facebook!=='') {
    ?>
    <hr/>
    <b class="btn btn-block btn-success btn-xs active">URL FACEBOOK ACTUAL</b>
    <table class="table table-striped table-bordered">
        <tr>
            <td>URL FANPAGE:</td>
            <td><a href="<?php echo $url_facebook; ?>" target="_blank"><?php echo $url_facebook; ?></a></td>
        </tr>
    </table>
    <?php
}
?>
<hr/>
<b class="btn btn-block btn-primary btn-xs active">ACTUALIZAR FANPAGE</b>
<form action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td>URL FANPAGE:</td>
            <td><input type="text" name="url_facebook" class="form-control" required="" value="<?php echo $url_facebook; ?>"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_departamento" value="<?php echo $id_departamento; ?>"/>
                <input type="submit" name="agregar-fanpage" class="btn btn-success btn-block" value="AGREGAR FANPAGE"/>
            </td>
        </tr>
    </table>
</form>