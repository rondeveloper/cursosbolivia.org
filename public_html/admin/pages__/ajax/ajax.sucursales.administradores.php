<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_sucursal = post('id_sucursal');

if(isset_post('agregar')){
    $id_administrador = post('id_administrador');
    query("INSERT INTO rel_admsuc (id_sucursal,id_administrador) VALUES ('$id_sucursal','$id_administrador') ");
    query("UPDATE administradores SET id_sucursal='$id_sucursal' WHERE id='$id_administrador' LIMIT 1 ");
    echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
}

if(isset_post('quitar')){
    $id_administrador = post('id_administrador');
    query("DELETE FROM rel_admsuc WHERE id_sucursal='$id_sucursal' AND id_administrador='$id_administrador' ");
    query("UPDATE administradores SET id_sucursal='1' WHERE id='$id_administrador' LIMIT 1 ");
    echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro se actualizo correctamente.
</div>';
}

$rqdas1 = query("SELECT a.nombre,a.id FROM rel_admsuc r INNER JOIN administradores a ON a.id=r.id_administrador WHERE r.id_sucursal='$id_sucursal' ");
if (num_rows($rqdas1) == 0) {
    echo 'Sin administradores';
}
?>
<table class="table table-bordered table-striped">
    <?php
    while ($rqdas2 = fetch($rqdas1)) {
    ?>
        <tr>
        <td>
                <?php echo $rqdas2['nombre']; ?>
            </td>
            <td>
                <b class="btn btn-xs btn-danger" onclick="quita_administrador('<?php echo $rqdas2['id']; ?>');"><i class="fa fa-trash-o"></i> Quitar</b>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<hr>

<b>ADICIONAR A OTROS ADMINISTRADORES:</b>

<table class="table table-bordered table-striped">
    <?php
    $rqdar1 = query("SELECT * FROM administradores WHERE id NOT IN (SELECT a.id FROM rel_admsuc r INNER JOIN administradores a ON a.id=r.id_administrador WHERE r.id_sucursal='$id_sucursal' ) AND estado='1' ");
    while ($rqdar2 = fetch($rqdar1)) {
    ?>
        <tr>
            <td>
                <?php echo $rqdar2['nombre']; ?>
            </td>
            <td>
                <b class="btn btn-xs btn-primary" onclick="agrega_administrador('<?php echo $rqdar2['id']; ?>');"><i class="fa fa-check"></i> Agregar</b>
            </td>
        </tr>
    <?php
    }
    ?>
</table>


<script>
    function agrega_administrador(id_administrador) {
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.sucursales.administradores.php',
            data: {
                agregar: 1, id_sucursal: '<?php echo $id_sucursal; ?>', id_administrador: id_administrador
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<script>
    function quita_administrador(id_administrador) {
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.sucursales.administradores.php',
            data: {
                quitar: 1, id_sucursal: '<?php echo $id_sucursal; ?>', id_administrador: id_administrador
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

