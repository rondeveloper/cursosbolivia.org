<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

/* registrar accesos */
if (isset_post('sw_asignar_acceso_p2')) {
    $sw_acceso_envio_formularios = 0;
    $sw_acceso_compras_menores = 0;
    $sw_acceso_anpe_lp = 0;
    $sw_acceso_subastas = 0;
    if (isset_post('sw_acceso_envio_formularios')) {
        $sw_acceso_envio_formularios = 1;
    }
    if (isset_post('sw_acceso_compras_menores')) {
        $sw_acceso_compras_menores = 1;
    }
    if (isset_post('sw_acceso_anpe_lp')) {
        $sw_acceso_anpe_lp = 1;
    }
    if (isset_post('sw_acceso_subastas')) {
        $sw_acceso_subastas = 1;
    }
    $rqvac1 = query("SELECT id FROM simulador_accesos WHERE id_curso='$id_curso' LIMIT 1 ");
    if (num_rows($rqvac1) == 0) {
        query("INSERT INTO simulador_accesos 
        (id_curso, sw_acceso_envio_formularios, sw_acceso_compras_menores, sw_acceso_anpe_lp, sw_acceso_subastas) 
        VALUES 
        ('$id_curso','$sw_acceso_envio_formularios','$sw_acceso_compras_menores','$sw_acceso_anpe_lp','$sw_acceso_subastas')");
        logcursos('Asignacion de accesos a simulador', 'curso-edicion', 'curso', $id_curso);
        echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro agregado correctamente.
  </div>';
    }else{
        $rqvac2 = fetch($rqvac1);
        $id_acc = $rqvac2['id'];
        query("UPDATE simulador_accesos SET 
        sw_acceso_envio_formularios='$sw_acceso_envio_formularios', sw_acceso_compras_menores='$sw_acceso_compras_menores', sw_acceso_anpe_lp='$sw_acceso_anpe_lp', sw_acceso_subastas='$sw_acceso_subastas' 
        WHERE id='$id_acc' LIMIT 1 ");
        logcursos('Actualizacion de accesos a simulador', 'curso-edicion', 'curso', $id_curso);
        echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro modificado correctamente.
  </div>';
    }
    
}

if (isset_post('sw_asignar_acceso')) {
?>
    <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">MODULOS PERMITIDOS</h4>
    <table class="table table-striped table-bordered">
        <tr>
            <td style="padding: 20px;">
                <form id="FORM-accesos-simulador">
                    <label><input type="checkbox" name="sw_acceso_envio_formularios" value="1" style="width: 17px;height:17px;" /> &nbsp; ENV&Iacute;O DE FORMULARIOS</label>
                    <br>
                    <label><input type="checkbox" name="sw_acceso_compras_menores" value="1" style="width: 17px;height:17px;" /> &nbsp; COMPRAS MENORES</label>
                    <br>
                    <label><input type="checkbox" name="sw_acceso_anpe_lp" value="1" style="width: 17px;height:17px;" /> &nbsp; ANPE LP</label>
                    <br>
                    <label><input type="checkbox" name="sw_acceso_subastas" value="1" style="width: 17px;height:17px;" /> &nbsp; SUBASTAS</label>
                    <br>
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                    <input type="hidden" name="sw_asignar_acceso_p2" value="1" />
                </form>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center;">
                <b class="btn btn-success" onclick="asignar_acceso_p2();">ASIGNAR ACCESOS</b>
            </td>
        </tr>
    </table>
    <?php
} else {
    $rqva1 = query("SELECT * FROM simulador_accesos WHERE id_curso='$id_curso' LIMIT 1 ");
    if (num_rows($rqva1) == 0) {
    ?>
        <div class="alert alert-danger">
            <strong>SIN ACCESO</strong>
            <br>
            Este curso no tiene acceso al simulador.
        </div>
        <br>
        <b class="btn btn-primary" onclick="asignar_acceso(<?php echo $id_curso; ?>);">ASIGNAR ACCESO</b>
    <?php
    } else {
        $rqva2 = fetch($rqva1);
        $sw_acceso_envio_formularios = $rqva2['sw_acceso_envio_formularios'];
        $sw_acceso_compras_menores = $rqva2['sw_acceso_compras_menores'];
        $sw_acceso_anpe_lp = $rqva2['sw_acceso_anpe_lp'];
        $sw_acceso_subastas = $rqva2['sw_acceso_subastas'];
    ?>
        <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">MODULOS PERMITIDOS</h4>
        <table class="table table-striped table-bordered">
            <tr>
                <td style="padding: 20px;">
                    <form id="FORM-accesos-simulador">
                        <label><input type="checkbox" name="sw_acceso_envio_formularios" value="1" style="width: 17px;height:17px;" <?php echo ($sw_acceso_envio_formularios == 1 ? ' checked="checked" ' : ''); ?> /> &nbsp; ENV&Iacute;O DE FORMULARIOS</label>
                        <br>
                        <label><input type="checkbox" name="sw_acceso_compras_menores" value="1" style="width: 17px;height:17px;" <?php echo ($sw_acceso_compras_menores == 1 ? ' checked="checked" ' : ''); ?> /> &nbsp; COMPRAS MENORES</label>
                        <br>
                        <label><input type="checkbox" name="sw_acceso_anpe_lp" value="1" style="width: 17px;height:17px;" <?php echo ($sw_acceso_anpe_lp == 1 ? ' checked="checked" ' : ''); ?> /> &nbsp; ANPE LP</label>
                        <br>
                        <label><input type="checkbox" name="sw_acceso_subastas" value="1" style="width: 17px;height:17px;" <?php echo ($sw_acceso_subastas == 1 ? ' checked="checked" ' : ''); ?> /> &nbsp; SUBASTAS</label>
                        <br>
                        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                        <input type="hidden" name="sw_asignar_acceso_p2" value="1" />
                    </form>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; text-align: center;">
                    <b class="btn btn-info" onclick="asignar_acceso_p2();">ACTUALIZAR ACCESOS</b>
                </td>
            </tr>
        </table>
<?php
    }
}
?>

<!--asignar_acceso-->
<script>
    function asignar_acceso(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.acceso_simulador.php',
            data: {
                id_curso: id_curso,
                sw_asignar_acceso: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-acceso_simulador").html(data);
            }
        });
    }
</script>

<!-- asignar_acceso_p2 -->
<script>
    function asignar_acceso_p2() {
        var form = $("#FORM-accesos-simulador").serialize();

        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.acceso_simulador.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-acceso_simulador").html(data);
            }
        });
    }
</script>