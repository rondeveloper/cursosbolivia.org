<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');


/* recepcion de datos POST */
$name_archivo = 'archivo';
if (is_uploaded_file($_FILES[$name_archivo]['tmp_name'])) {
    $nombre = post('nombre');

    $name_file = $_FILES[$name_archivo]['name'];
    $extension = pathinfo($name_file, PATHINFO_EXTENSION);
    if ($extension == 'php') {
        exit;
    }
    $nombre_fisico = md5($id_curso . $name_file) . '.' . $extension;
    $dest_archivo = '../../../contenido/archivos/material/' . $nombre_fisico;
    if (move_uploaded_file($_FILES[$name_archivo]['tmp_name'], $dest_archivo)) {
        query("INSERT INTO materiales_curso (id_curso,nombre,nombre_fisico) VALUES ('$id_curso','$nombre','$nombre_fisico') ");
        $id_archivo = insert_id();
        logcursos('Agregado de archivo material:' . $id_archivo, 'curso-edicion', 'curso', $id_curso);
        echo '<br/><div class="alert alert-success">
      <strong>EXITO</strong> registro agregado correctamente.
    </div>';
    } else {
        echo '<div class="alert alert-danger">
      <strong>ERROR</strong> no se pudo procesar.
    </div>';
    }
}

/* eliminar_material */
if (isset_post('eliminar_material')) {
    /* recepcion de datos POST */
    $id_material = post('id_material');
    logcursos('Elimnacion de archivo material:' . $id_material, 'curso-edicion', 'curso', $id_curso);
    query("UPDATE materiales_curso SET estado='0' WHERE id='$id_material' LIMIT 1 ");
    echo '<div class="alert alert-success">
      <strong>EXITO</strong> el registro fue eliminado.
    </div>';
}

/* archivos */
$rqda1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ");
if (num_rows($rqda1) == 0) {
    ?>
    <div class="alert alert-info">
        <strong>AVISO</strong> el curso no tiene material asociado.
    </div>
    <?php
}
?>
<table class="table table-striped table-bordered">
    <?php
    $cnt = 1;
    while ($rqda2 = fetch($rqda1)) {
        ?>
        <tr>
            <td>
                <?php echo $cnt++; ?>
            </td>
            <td>
                <?php echo $rqda2['nombre']; ?>
            </td>
            <td>
                <a href="<?php echo $dominio_www; ?>contenido/archivos/material/<?php echo $rqda2['nombre_fisico']; ?>" target="_blank">DESCARGAR</a>
            </td>
            <td>
                <b class="btn btn-xs btn-danger" onclick="eliminar_material('<?php echo $rqda2['id']; ?>');">Eliminar</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr/>

<a class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar_material"> <i class="fa fa-plus"></i> AGREGAR MATERIAL</a>

<!-- Modal -->
<div id="MODAL-agregar_material" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGAR MATERIAL</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-agrega_archivo">
                    <form action="" method="post" enctype="multipart/form-data" id="FORM-agrega_archivo">
                        <div class="form-group">
                            <label for="nombre">Nombre del archivo:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del archivo..." required="">
                        </div>
                        <div class="form-group">
                            <label for="archivo">Archivo:</label>
                            <input type="file" class="form-control" name="archivo" id="archivo" required="">
                        </div>
                        <br>
                        <input type="hidden" class="form-control" name="id_curso" value="<?php echo $id_curso; ?>">
                        <input type="submit" class="btn btn-success" name="agregar-archivo" value="AGREGAR MATERIAL"/> 
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<hr>

<script>
    function eliminar_material(id_material) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.material_curso.php',
            data: {id_curso: '<?php echo $id_curso; ?>', id_material: id_material, eliminar_material: '1'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-material_curso").html(data);
            }
        });
    }
</script>


<script>
    $("#FORM-agrega_archivo").on('submit', function (evt) {
        evt.preventDefault();

        var inputFileCedula = document.getElementById('archivo');
        var file = inputFileCedula.files[0];
        var nombre = $("#nombre").val();
        var id_curso = '<?php echo $id_curso; ?>';

        var formData = new FormData();
        formData.append('id_curso', id_curso);
        formData.append('nombre', nombre);
        formData.append('archivo', file);

        $("#AJAXCONTENT-agrega_archivo").html("<h3>CARGANDO...<h3>");

        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.material_curso.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $('.modal-backdrop').remove();
                $("#AJAXCONTENT-material_curso").html(data);
            }
        });
    });
</script>