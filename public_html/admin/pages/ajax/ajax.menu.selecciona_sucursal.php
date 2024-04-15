<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_administrador = administrador('id');

//echo $id_administrador;

/* cambio de sucursal */
if (isset_post('id_sucursal')) {
    $id_sucursal = post('id_sucursal');
    query("UPDATE administradores SET id_sucursal='$id_sucursal' WHERE id='$id_administrador' LIMIT 1 ");
?>
    <div class="alert alert-success">
        <strong>SUCURSAL SELECCIONADA</strong>
        <br>
        Registro actualizado correctamente.
    </div>
<?php
    exit;
}


$rqdas1 = query("SELECT s.* FROM rel_admsuc r INNER JOIN sucursales s ON s.id=r.id_sucursal WHERE id_administrador='$id_administrador' AND s.estado='1' ");
if (num_rows($rqdas1) == 0) {
?>
    <div class="alert alert-info">
        <strong>AVISO</strong>
        <br>
        Solo tiene asignado la sucursal por defecto.
    </div>
<?php
} else {
?>
    <p>Seleccione la sucursal con la cual desea trabajar.</p>
    <hr>
    <div class="text-center" id="ajaxresult-selec_suc">
        <?php
        while ($rqdas2 = fetch($rqdas1)) {
        ?>
            <b class="btn btn-lg btn-success" onclick="selec_suc('<?php echo $rqdas2['id']; ?>');"><?php echo $rqdas2['nombre']; ?></b>
            <br>
            <br>
        <?php
        }
        ?>
    </div>
    <hr>
<?php
}
?>
<script>
    function selec_suc(id_sucursal) {
        $("#ajaxresult-selec_suc").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.menu.selecciona_sucursal.php',
            data: {
                id_sucursal: id_sucursal
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxresult-selec_suc").html(data);
                location.reload();
            }
        });
    }
</script>