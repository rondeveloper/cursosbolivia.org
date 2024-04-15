<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

if (isset_post('asignar-enlace')) {
    $id_enlace = post('id_enlace');

    if($id_enlace == '0'){
        query("DELETE FROM rel_cursoenlace WHERE id_curso='$id_curso' ");
        logcursos('Asignacion SIN enlace a curso', 'curso-ediccion', 'curso', $id_curso);
        ?>
        <div class="alert alert-success">
            <strong>EXITO</strong> Se quito el enlace.
        </div>
        <b>SIN ENLACE</b> &nbsp;&nbsp;  (Enlace por defecto)
        &nbsp;&nbsp; 
        <b class="btn btn-sm btn-default" onclick="asignar_enlace();">ASIGNAR ENLACE</b>
        <?php
    }else{
        query("DELETE FROM rel_cursoenlace WHERE id_enlace='$id_enlace' ");
        query("INSERT INTO rel_cursoenlace(id_curso, id_enlace, estado) VALUES ('$id_curso','$id_enlace',1)");
        logcursos('Asignacion de enlace', 'curso-ediccion', 'curso', $id_curso);

        $rqde1 = query("SELECT enlace FROM enlaces_cursos WHERE id='$id_enlace' ORDER BY id DESC limit 1 ");
        $rqde2 = fetch($rqde1);
        $url_enlace = $dominio.$rqde2['enlace'].'/';
        ?>
        <div class="alert alert-success">
            <strong>EXITO</strong> enlace asignado correctamente.
        </div>
        <b style="color: green;">CON ENLACE</b> &nbsp;&nbsp; [<?php echo $rqde2['enlace']; ?>] 
        &nbsp;&nbsp; 
        <b class="btn btn-sm btn-default" onclick="asignar_enlace();">CAMBIAR ENLACE</b> 
        &nbsp;&nbsp;|&nbsp;&nbsp; 
        <a href="<?php echo $url_enlace; ?>" target="_blank"><?php echo $url_enlace; ?></a>
        <?php
    }
} else {

    $id_currect_enlace = 0;
    $rqde1 = query("SELECT e.id FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON r.id_enlace=e.id WHERE r.id_curso='$id_curso' ORDER BY r.id DESC limit 1 ");
    if (num_rows($rqde1) > 0) {
        $rqde2 = fetch($rqde1);
        $id_currect_enlace = $rqde2['id'];
    }

    $resultado1 = query("SELECT e.* FROM enlaces_cursos e WHERE e.estado IN (1) ORDER BY e.id ASC ");
?>
    <style>
        .select2-container.my-select2{
            display: block;
        }
    </style>

    <form id="FORM-enlace" action="" method="post">
        <table class="table table-striped table-bordered table-responsive">
            <tr>
                <td>
                    <b>Enlace a asignar:</b>
                </td>
            </tr>
            <tr>
                <td>
                    <select name="id_enlace" class="my-select2">
                        <option value="0">SIN ENLACE</option>
                        <?php
                        while ($producto = fetch($resultado1)) {
                        ?>
                            <option value="<?php echo $producto['id']; ?>" <?php echo ($id_currect_enlace==$producto['id']?' selected="selected" ':'')?>><?php echo $producto['enlace'] . ' | ' . $producto['descripcion']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <input type="hidden" name="asignar-enlace" value="1" />
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                    <input type="submit" value="ASIGNAR" class="btn btn-sm btn-success" />
                </td>
            </tr>
        </table>
    </form>
<?php
}
?>

<script>
    $('#FORM-enlace').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-editar.asignar_enlace.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#MODAL-modgeneral').modal('toggle');
                $("#ajaxcontent-enlace").html(data);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
      $('.my-select2').select2();
    });
</script>
