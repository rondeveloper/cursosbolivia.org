<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

$id_cuenta = post('id_cuenta');
$rqidc1 = query("SELECT * FROM reseller_usuarios WHERE id='$id_cuenta' ");
$reseller = mysql_fetch_array($rqidc1);
?>


<form action="" method="post">
    <table class="table table-bordered table-striped">
        <tr>
            <td>Nombre</td>
            <td><input type="text" name="nombre" value="<?php echo $reseller['nombre']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Celular</td>
            <td><input type="number" name="celular" value="<?php echo $reseller['celular']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Correo</td>
            <td><input type="text" name="email" value="<?php echo $reseller['email']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Nombre facturaci&oacute;n</td>
            <td><input type="text" name="nombre_facturacion" value="<?php echo $reseller['nombre_facturacion']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>NIT facturaci&oacute;n</td>
            <td><input type="number" name="nit_facturacion" value="<?php echo $reseller['nit_facturacion']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Nick</td>
            <td><input type="text" name="nick" value="<?php echo $reseller['nick']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Contrase&ntilde;a</td>
            <td><input type="text" name="password" value="<?php echo $reseller['password']; ?>" placeholder="" class="form-control" required="" autocomplete="off"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_cuenta" value="<?php echo $reseller['id']; ?>"/>
                <input type="submit" name="editar-cuenta-reseller" value="EDITAR CUENTA" class="btn btn-block btn-success active"/>
            </td>
        </tr>                    
    </table>
</form>

