<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* adminsitrador */
$rqdadm1 = query("SELECT nivel FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqdadm2 = fetch($rqdadm1);
$nivel_administrador = $rqdadm2['nivel'];

/*
if ($nivel_administrador != '1' && $nivel_administrador != '1') {
    echo "DENEGADO";
    exit;
}
*/

if (isset_post('fecha-buscar')) {
    $fecha_buscqueda = post('fecha-buscar');
    $rqcp1 = query("SELECT titulo,fecha,id FROM cursos WHERE fecha='$fecha_buscqueda' ORDER BY id DESC ");
    if (num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron cursos con la fecha indicada.</p><br/><br/>";
    } else {
        ?>  
        <div id="AJAXCONTENT-emite_certificados_multiple_p2">
            <form id="FORM-cambiar-fecha" action='' method='post'>
                <div style="background: #efefef;padding: 25px;border-radius: 15px;line-height: 2.4;border: 1px solid #dedede;">
                    <b>Ingrese la nueva fecha del curso:</b>
                    <br>
                    <input type="date" name="fecha-nueva" value="<?php echo $fecha_buscqueda; ?>" required="" class="form-control" placeholder="Fecha..." />
                    <br>
                    <b>Ingrese la fecha previa:</b>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" name="fecha2" class="form-control" value="<?php echo date("Y-m-d", strtotime($fecha_buscqueda)); ?>"/>
                        </div>
                        <div class="col-md-6">
                            <input type="time" name="fecha2_hour" class="form-control" value="<?php echo date("H:i", strtotime($fecha_buscqueda)); ?>"/>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-striped table-bordered">
                    <?php
                    $ids_mostrados = '';
                    while ($rqcp2 = fetch($rqcp1)) {
                        $ids_mostrados .= ','.$rqcp2['id'];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="idcur-<?php echo $rqcp2['id']; ?>" value="1" checked="" style="width:25px;height:25px;" />
                            </td>
                            <td>
                                <span style='font-size: 12pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['titulo']); ?></span>
                                <br>
                                <b style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['id']; ?></b> <i>Fecha: <?php echo $rqcp2['fecha']; ?></i>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br />
                <p class='text-center'>
                    <b>&iquest; Desea cambiar la fecha a estos cursos ?</b>
                </p>

                <input type="hidden" name="sw_cambiar_fecha" value="1" />
                <input type="hidden" name="ids_mostrados" value="<?php echo trim($ids_mostrados,','); ?>" />
                <input type="hidden" name="fecha-anterior" value="<?php echo $fecha_buscqueda; ?>" />
                
                <div class="text-center">
                    <b class="btn btn-success" onclick="cambiar_fecha();">CAMBIAR FECHA DE CURSOS</b>
                </div>
            </form>
        </div>
        <?php
    }
} elseif (isset_post('sw_cambiar_fecha')) {

    $fecha_nueva = post('fecha-nueva');
    $fecha_anterior = post('fecha-anterior');
    $ids_mostrados = post('ids_mostrados');
    $fecha2 = (post('fecha2')) . ' ' . (post('fecha2_hour'));

    $ar1 = explode(",", $ids_mostrados);
    foreach ($ar1 as $id_curso) {
        if (isset_post('idcur-' . $id_curso)) {
            query("UPDATE cursos SET fecha='$fecha_nueva', fecha2='$fecha2' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
            logcursos('Cambio de fecha a curso: ['.$fecha_anterior.' -> '.$fecha_nueva.']', 'curso-edicion', 'curso', $id_curso);
            echo '<div class="alert alert-success">
            <strong>EXITO</strong> se cambio la fecha correctamente.
          </div>
          ';
        }
    }

} else {
    ?>
        <div id="AJAXCONTENT-emite_certificados_multiple_p2">
            <form id="FORM-fecha-curso" action='' method='post'>
                <div style="background: #efefef;padding: 25px;border-radius: 15px;line-height: 2.4;border: 1px solid #dedede;">
                    <b>Cursos en fecha:</b>
                    <br>
                    <input type="date" name="fecha-buscar" value="<?php echo date("Y-m-d"); ?>" required="" class="form-control" placeholder="ID del curso..." />
                </div>
                <br>
                <div class="text-center">
                    <b class="btn btn-success" onclick="buscar_cursos();">BUSCAR CURSOS</b>
                </div>
            </form>
        </div>
    <?php
}
?>

<script>
    function buscar_cursos() {
        var form = $("#FORM-fecha-curso").serialize();
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.inicio.cambio_fecha_cursos.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<script>
    function cambiar_fecha() {
        var form = $("#FORM-cambiar-fecha").serialize();
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.inicio.cambio_fecha_cursos.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
