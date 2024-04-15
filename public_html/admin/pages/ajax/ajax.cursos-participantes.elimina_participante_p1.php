<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');


/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = fetch($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_emision_factura = $data_registro['id_emision_factura'];

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* turnos */
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$sw_turnos = false;
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;font-weight: bold;">
            <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr />
<p>Para eliminar a un participante, se debe registrar el motivo y tambi&eacute;n adjuntar una imagen.</p>
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>

    <!-- DIV CONTENT AJAX :: ELIMINA PARTICIPANTE P2 -->
    <div id="ajaxloading-elimina_participante_p2"></div>
    <div id="ajaxbox-elimina_participante_p2">
        <form id="FORM-elimina_participante_p2" enctype="multipart/form-data">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>
                        <b>Motivo:</b>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="motivo" required placeholder="Motivo de la eliminaci&oacute;n..."/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Imagen adjunta:</b>
                        <br>
                        <i>(opcional)</i>
                    </td>
                    <td>
                        <input type="file" class="form-control" name="archivo"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <button class="btn btn-danger btn-sm" type="submit">ELIMINAR PARTICIPANTE</button>
                        <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>"/>
                        <input type="hidden" name="apartar" value="0"/>
                        <br>&nbsp;
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    $('#FORM-elimina_participante_p2').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p2.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    });
</script>