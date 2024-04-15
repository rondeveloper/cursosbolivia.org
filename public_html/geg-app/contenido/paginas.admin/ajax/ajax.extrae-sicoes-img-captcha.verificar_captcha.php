<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "Acceso denegado";
    exit;
}

$codcaptcha = strtolower(post('codcaptcha'));

$rqtaf1 = query("SELECT * FROM hash_captcha_archivos WHERE codcaptcha='$codcaptcha' LIMIT 1 ");
$cnt = mysql_num_rows($rqtaf1);
$rqtaf2 = mysql_fetch_array($rqtaf1);
$estado = $rqtaf2['estado'];
$id_reg = $rqtaf2['id'];


if ($codcaptcha == '') {
    ?>
    <div class="col-md-6">
        <b style="color:red;">CODIGO VACIO</b>
        <br/>
        Debes ingresar el cosigo que se visualiza en la imagen,
        <br/>
        Luego presiona el boton verificar captcha: 
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-warning active" onclick="verificar_captcha();">VERIFICAR CAPTCHA</button>
    </div>

    <?php
} elseif ($cnt == 0) {
    ?>
    <div class="col-md-6">
        <b style="color:red;">NO ENCONTRADO</b>
        <br/>
        Codigo <?php echo strtoupper($codcaptcha); ?> no encontrado, verifica si el codigo es correcto,
        <br/>
        Luego presiona el boton verificar captcha: 
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-warning active" onclick="verificar_captcha();">VERIFICAR CAPTCHA</button>
    </div>

    <?php
} elseif ($estado == '1') {
    ?>
    <div class="col-md-6">
        <b style="color:blue;">CODIGO CARGADO PREVIAMENTE</b>
        <br/>
        El codigo <?php echo strtoupper($codcaptcha); ?> ya fue cargado previamente,
        <br/>
        Presiona el siguiente boton continuar: 
    </div>
    <div class="col-md-6 text-right">
        <a href="extrae-sicoes-img-captcha.adm" class="btn btn-info active">CONTINUAR</a>
    </div>

    <?php
} elseif ($estado == '0') {
    ?>
    <div class="col-md-5">
        <b style="color:green;">CODIGO ENCONTRADO</b>
        <br/>
        Descargar la imagen <?php echo strtoupper($codcaptcha); ?> y cargala en el siguiente cuadro:
    </div>
    <div class="col-md-7 text-center">
        <b>Ingresa la imagen descargada:</b>
        <br/>
        <input type="file" name="imgcaptcha" class="form-control"/>
        <br/>
        <input type="hidden" name="codcaptcha" value="<?php echo $codcaptcha; ?>"/>
        <input type="hidden" name="id_reg" value="<?php echo $id_reg; ?>"/>
        <input type="submit" name="cargar-img-captcha" class="btn btn-success active" value="SUBIR IMAGEN"/>
    </div>

    <?php
}
?>
