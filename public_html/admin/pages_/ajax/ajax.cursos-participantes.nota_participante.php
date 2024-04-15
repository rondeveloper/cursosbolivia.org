<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

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
$id_curso = $participante['id_curso'];
$nombre_participante = $participante['nombres'].' '.$participante['apellidos'];
$correo_participante = $participante['correo'];

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

/* curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];

$mensaje = '';
/* modificar nota */
if (isset_post('nota')) {
    $nota = post('nota');
    $rqvne1 = query("SELECT id FROM participantes_notas_manuales WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if (num_rows($rqvne1) > 0) {
        $rqvne2 = fetch($rqvne1);
        $id_reg = $rqvne2['id'];
        query("UPDATE participantes_notas_manuales SET nota='$nota' WHERE id='$id_reg' ORDER BY id DESC limit 1 ");
    } else {
        query("INSERT INTO participantes_notas_manuales (id_participante,nota) VALUES ('$id_participante','$nota') ");
    }
    $mensaje = '<div class="alert alert-success">
  <strong>EXITO</strong> registro actualizado.
</div>';
}

$rqvne1 = query("SELECT nota FROM participantes_notas_manuales WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
if (num_rows($rqvne1) > 0) {
    $rqvne2 = fetch($rqvne1);
    $current_nota = $rqvne2['nota'];
} else {
    $current_nota = 0;
}

/* enviar por email */
if (isset_post('sendbyemail')) {
    $current_nota;
    $cont_email = '<p>Estimado@ '.$nombre_participante.'</p>';
    $cont_email .= '<p>Le notificamos por este correo que se ha subido la nota final del curso <b>'.$titulo_curso.'</b>.</p>';
    $cont_email .= '<table border="1">';
    $cont_email .= '<tr>';
    $cont_email .= '<td style="padding: 20px;"><b>PARTICIPANTE:</b></td>';
    $cont_email .= '<td style="padding: 20px;font-size:12pt;">'.$nombre_participante.'</td>';
    $cont_email .= '</tr>';
    $cont_email .= '<tr>';
    $cont_email .= '<td style="padding: 20px;"><b>NOTA FINAL:</b></td>';
    $cont_email .= '<td style="padding: 20px;font-size:15pt;color:green;">'.$current_nota.' / 100</td>';
    $cont_email .= '</tr>';
    $cont_email .= '</table>';
    $cont_email .= '<br><br><p>Le felicitamos por su desempe&ntilde;o en el curso y esperamos que este curso le haya sido de utilidad.</p>';
    $cont_email .= '<p>Muchas gracias por ser participe de este curso.</p>';
    $cont_email .= '<br><p>Saludos cordiales<br>'.$___nombre_del_sitio.'</p>';
    $contenido_correo = platillaEmailUno($cont_email,$titulo_curso,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);
    SISTsendEmail($correo_participante, 'NOTA FINAL - '.$titulo_curso, $contenido_correo);
    logcursos('Envio de NOTA FINAL por correo', 'participante-nota', 'participante', $id_participante);
    $mensaje = '<div class="alert alert-success">
  <strong>EXITO</strong> se ha enviado al participante la nota por correo.
</div>';
}

?>

<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>

<?php echo $mensaje; ?>

<div class="text-center">
    <form action="" method="post" id="FORM-update_nota">
        <div class="form-group">
            <label for="nombre">NOTA:</label>
            <input type="number" min="0" max="100" class="form-control text-center" name="nota" id="nota" placeholder="Nota..." value='<?php echo $current_nota; ?>' required="" style="font-size:25pt;height: auto;">
        </div>
        <br>
        <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>">
        <input type="submit" class="btn btn-success" name="actualizar-nota" value="ACTUALIZAR NOTA"/> 
    </form>
</div>
<hr>
<b class="btn btn-default" onclick="enviar_nota_por_correo();"><i class="fa fa-envelope"></i> Enviar nota por correo</b>

<script>
    $("#FORM-update_nota").on('submit', function(evt) {
        evt.preventDefault();
        var formData = new FormData(this);
        var aux_data_nota = $("#nota").val();
        $("#AJAXCONTENT-notas_curso").html('Cargando...<br><br><br><br>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.nota_participante.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                $("#aux-udp-<?php echo $id_participante; ?>").html(aux_data_nota);
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    });
</script>


<script>
    function enviar_nota_por_correo() {
        if (confirm('DESEA ENVIAR LA NOTA POR CORREO ?')) {
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.nota_participante.php',
                data: {id_participante: '<?php echo $id_participante; ?>',sendbyemail: 1},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>