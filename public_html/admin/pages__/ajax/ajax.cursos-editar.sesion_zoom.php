<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$mensaje = '';

if (isset_post('sw_send_form')) {
    $id_curso = post('id_curso');
    $url = post('url');
    $descripcion = post('descripcion');
    $reunion_id = str_replace(' ', '', post('reunion_id'));
    $codigo_acceso = post('codigo_acceso');
    $fecha = post('fecha').' '.post('hora');
    $duracion = post('duracion');
    query("INSERT INTO sesiones_zoom 
    (id_curso, url, descripcion, reunion_id, codigo_acceso, fecha, duracion, estado) 
    VALUES 
    ('$id_curso','$url','$descripcion','$reunion_id','$codigo_acceso','$fecha','$duracion','1')");
    logcursos('Creacion de sesion zoom', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> registro agregado correctamente.
  </div>';
}

if (isset_post('sw_send_edit_form')) {
    $id_sesion = post('id_sesion');
    $id_curso = post('id_curso');
    $url = post('url');
    $descripcion = post('descripcion');
    $reunion_id = str_replace(' ', '', post('reunion_id'));
    $codigo_acceso = post('codigo_acceso');
    $fecha = post('fecha').' '.post('hora');
    $duracion = post('duracion');
    query("UPDATE sesiones_zoom SET 
    id_curso='$id_curso', url='$url', descripcion='$descripcion', reunion_id='$reunion_id', codigo_acceso='$codigo_acceso', fecha='$fecha', duracion='$duracion' 
    WHERE id='$id_sesion' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de sesion zoom', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> registro actualizado correctamente.
  </div>';
}


if (isset_post('sw_eliminar_sesion')) {
    $id_sesion = post('id_sesion');
    query("DELETE FROM sesiones_zoom WHERE id='$id_sesion' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de sesion zoom', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> registro eliminado correctamente.
  </div>';
}


echo $mensaje;

if (isset_post('sw_editar_sesion')) {
    $id_sesion = post('id_sesion');
    $rqcsz1 = query("SELECT * FROM sesiones_zoom WHERE id='$id_sesion' LIMIT 1 ");
    $rqcsz2 = fetch($rqcsz1);
?>
    <form id="FORM-sesion_zoom" action="" method="post">
        <table class="table table-bordered table-hover">
            <tr>
                <td>
                    <b>Descripci&oacute;n:</b>
                </td>
                <td>
                    <textarea class="form-control myinput" name="descripcion" placeholder="Descripci&oacute;n de la reuni&oacute;n..." required="" autocomplete="off" style="height: 270px;"><?php echo $rqcsz2['descripcion']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <b>URL de reuni&oacute;n:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="url" value="<?php echo $rqcsz2['url']; ?>" placeholder="URL de reuni&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>ID de reuni&oacute;n:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="reunion_id" value="<?php echo $rqcsz2['reunion_id']; ?>" placeholder="ID de reuni&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>C&oacute;digo de acceso:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="codigo_acceso" value="<?php echo $rqcsz2['codigo_acceso']; ?>" placeholder="C&oacute;digo de acceso..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Fecha:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="date" name="fecha" value="<?php echo date("Y-m-d",strtotime($rqcsz2['fecha'])); ?>" placeholder="Fecha..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Hora:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="time" name="hora" value="<?php echo date("H:i",strtotime($rqcsz2['fecha'])); ?>" placeholder="Hora..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Duraci&oacute;n: (min.)</b>
                </td>
                <td>
                    <input class="form-control myinput" type="number" name="duracion" value="<?php echo $rqcsz2['duracion']; ?>" placeholder="Duraci&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
        </table>
        <div class="panel-footer">
            <div class="row">
                <input type="hidden" value="<?php echo $id_sesion; ?>" name="id_sesion" />
                <input type="hidden" value="<?php echo $id_curso; ?>" name="id_curso" />
                <input type="hidden" value="1" name="sw_send_edit_form" />
                <input type="submit" class="btn btn-lsm btn-info" value="Actualizar sesion ZOOM" style="border-radius: 7px;" name="agregar">
            </div>
        </div>
    </form>
<?php
} elseif (isset_post('sw_agregar_sesion')) {
?>
    <form id="FORM-sesion_zoom" action="" method="post">
        <table class="table table-bordered table-hover">
            <tr>
                <td>
                    <b>Descripci&oacute;n:</b>
                </td>
                <td>
                    <textarea class="form-control myinput" name="descripcion" placeholder="Descripci&oacute;n de la reuni&oacute;n..." required="" autocomplete="off" style="height: 270px;"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <b>URL de reuni&oacute;n:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="url" placeholder="URL de reuni&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>ID de reuni&oacute;n:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="reunion_id" placeholder="ID de reuni&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>C&oacute;digo de acceso:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="text" name="codigo_acceso" placeholder="C&oacute;digo de acceso..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Fecha:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="date" name="fecha" value="" placeholder="Fecha..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Hora:</b>
                </td>
                <td>
                    <input class="form-control myinput" type="time" name="hora" value="" placeholder="hora..." required="" value="" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>
                    <b>Duraci&oacute;n: (min.)</b>
                </td>
                <td>
                    <input class="form-control myinput" type="number" name="duracion" value="" placeholder="Duraci&oacute;n..." required="" value="" autocomplete="off">
                </td>
            </tr>
        </table>
        <div class="panel-footer">
            <div class="row">
                <input type="hidden" value="<?php echo $id_curso; ?>" name="id_curso" />
                <input type="hidden" value="1" name="sw_send_form" />
                <input type="submit" class="btn btn-lsm btn-success" value="Agregar sesion ZOOM" style="border-radius: 7px;" name="agregar">
            </div>
        </div>
    </form>
    <?php
} else {
    $rqv1 = query("SELECT * FROM sesiones_zoom WHERE id_curso='$id_curso' ORDER BY id ASC ");
    if (num_rows($rqv1) == 0) {
    ?>
        <div class="alert alert-warning">
            <strong>SIN SESION</strong> no hay sesion de zoom configurada.
        </div>
        <hr>
        <b class="btn btn-sm btn-success" onclick="agregar_sesion_zoom();">AGREGAR SESION</b>
    <?php
    } else {
        ?>
        <table class="table table-bordered table-striped">
        <?php
        while($rqv2 = fetch($rqv1)){
            ?>
            <tr>
                <td>
                    <b>Descripci&oacute;n:</b>
                </td>
                <td>
                    <?php echo str_replace(PHP_EOL, '<br>', $rqv2['descripcion']); ?>
                </td>
                <td rowspan="6">
                    <b class="btn btn-xs btn-info" onclick="editar_sesion_zoom(<?php echo $rqv2['id']; ?>);">EDITAR</b>
                    <br>
                    <br>
                    <b class="btn btn-xs btn-danger" onclick="eliminar_sesion_zoom(<?php echo $rqv2['id']; ?>);">ELIMINAR</b>
                </td>
            </tr>
            <tr>
                <td>
                    <b>URL de reuni&oacute;n:</b>
                </td>
                <td>
                    <?php echo $rqv2['url']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>ID de reuni&oacute;n:</b>
                </td>
                <td>
                    <?php echo $rqv2['reunion_id']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>C&oacute;digo de acceso:</b>
                </td>
                <td>
                    <?php echo $rqv2['codigo_acceso']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Fecha y Hora:</b>
                </td>
                <td>
                    <?php echo date("d/m/Y H:i",strtotime($rqv2['fecha'])); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Duraci&oacute;n:</b>
                </td>
                <td>
                    <?php echo $rqv2['duracion']; ?> min.
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    .
                </td>
            </tr>
            <?php
        }
        ?>
        </table>
        <hr>
        <b class="btn btn-sm btn-success" onclick="agregar_sesion_zoom();">AGREGAR SESION</b>
<?php
    }
}

?>

<script>
    function agregar_sesion_zoom() {
        $("#AJAXCONTENT-sesion_zoom").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.sesion_zoom.php',
            data: {
                id_curso: '<?php echo $id_curso; ?>',
                sw_agregar_sesion: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-sesion_zoom").html(data);
            }
        });
    }
</script>


<script>
    function editar_sesion_zoom(id_sesion) {
        $("#AJAXCONTENT-sesion_zoom").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.sesion_zoom.php',
            data: {
                id_curso: '<?php echo $id_curso; ?>',
                id_sesion: id_sesion,
                sw_editar_sesion: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-sesion_zoom").html(data);
            }
        });
    }
</script>

<script>
    function eliminar_sesion_zoom(id_sesion) {
        if (confirm('ESTA SEGURO DE ELIMINAR LA SESION ?')) {
            $("#AJAXCONTENT-sesion_zoom").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-editar.sesion_zoom.php',
                data: {
                    id_curso: '<?php echo $id_curso; ?>',
                    id_sesion: id_sesion,
                    sw_eliminar_sesion: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-sesion_zoom").html(data);
                }
            });
        }
    }
</script>


<script>
    $('#FORM-sesion_zoom').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-sesion_zoom").html('Cargando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-editar.sesion_zoom.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-sesion_zoom").html(data);
            }
        });
    });
</script>