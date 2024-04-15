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
$id_material = post('id_material');

/* material */
$rqdm1 = query("SELECT * FROM cursos_material WHERE id='$id_material' LIMIT 1 ");
$rqdm2 = mysql_fetch_array($rqdm1);
$nombre_material = $rqdm2['nombre_material'];

echo "<h3 style='margin-top: 0px;font-weight: bold;'>$nombre_material</h3>";
echo "<hr/>";

/* archivos */
$rqda1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
if (mysql_num_rows($rqda1) == 0) {
    ?>
    <div class="alert alert-info">
        <strong>AVISO</strong> no se encontraron registros.
    </div>
    <?php
}
?>
<table class="table table-striped table-bordered">
    <?php
    $cnt = 1;
    while ($rqda2 = mysql_fetch_array($rqda1)) {
        ?>
        <tr>
            <td>
                <?php echo $cnt++; ?>
            </td>
            <td>
                <?php echo $rqda2['nombre_digital']; ?>
            </td>
            <td>
                <a href="contenido/archivos/material/<?php echo $rqda2['nombre_fisico']; ?>" target="_blank">DESCARGAR</a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr/>

<div class="panel panel-info">
    <div class="panel-heading">AGREGAR ARCHIVO</div>
    <div class="panel-body">
        <div id="AJAXCONTENT-agrega_archivo">
            <form action="" method="post" enctype="multipart/form-data" id="FORM-agrega_archivo">

                <div class="form-group">
                    <label for="nombre_digital">Nombre del archivo:</label>
                    <input type="text" class="form-control" name="nombre_digital" id="nombre_digital" placeholder="Nombre del archivo..." required="">
                </div>
                <div class="form-group">
                    <label for="archivo">Archivo:</label>
                    <input type="file" class="form-control" name="archivo" id="archivo" required="">
                </div>
                <br>
                <input type="hidden" class="form-control" name="id_material" value="<?php echo $id_material; ?>">
                <button type="submit" class="btn btn-success" name="agregar-archivo">AGREGAR ARCHIVO</button> 
            </form>
        </div>
    </div>
</div>

<hr>

<script>
    $("#FORM-agrega_archivo").on('submit', function (evt) {
        evt.preventDefault();
        
        var inputFileCedula = document.getElementById('archivo');
        var file = inputFileCedula.files[0];
        var nombre_digital = $("#nombre_digital").val();
        var id_material = '<?php echo $id_material; ?>';

        // Crea un formData y lo envías
        var formData = new FormData();
        formData.append('id_material', id_material);
        formData.append('nombre_digital', nombre_digital);
        formData.append('archivo',file);
        
        $("#AJAXCONTENT-agrega_archivo").html("<h3>CARGANDO...<h3>");

        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-material.agrega_archivo.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#AJAXCONTENT-agrega_archivo").html(data);
            }
        });
    });
</script>