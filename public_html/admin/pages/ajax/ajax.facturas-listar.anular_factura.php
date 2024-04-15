<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
} 

$id_factura = post('id_factura');
?>

<form action="" method="post">
    <div class="panel-body">
        <div class="form-group">
            <label class="col-lg-3 col-md-4 control-label text-primary">MOTIVO DE ANULACI&Oacute;N</label>
            <div class="col-lg-9 col-md-8">
                <input type="text" class="form-control form-cascade-control" name="motivo_anulacion" value="" required placeholder="Observaci&oacute;n..." autocomplete="off" />
                <br />
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <input type="hidden" name="id_factura"  value="<?php echo $id_factura; ?>"/>
        <input type="submit" name="anular-factura" class="btn btn-danger btn-sm btn-animate-demo" value="ANULAR FACTURA" />
    </div>
</form>

