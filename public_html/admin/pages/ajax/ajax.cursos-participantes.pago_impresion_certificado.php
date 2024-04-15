<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* sw de autorizacion de impresion de ceritifcado */
$sw_modulo_autorizacion_impresion_certificado = true;

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$ids_emisiones = post('ids_emisiones');
$id_administrador = administrador('id');
$modimp = post('modimp');

if($modimp=='todos-imp-fisico'){
    $modo = 'IMPRESION-FISICO';
    $file_pdfimpresion_php = 'certificado-3-masivo.php';
    $parameter_tosend = 'ids_emisiones';
} else if($modimp=='todos-imp-digital'){
    $modo = 'IMPRESION-DIGITAL';
    $file_pdfimpresion_php = 'certificado-digital-3-masivo.php';
    $parameter_tosend = 'ids_emisiones';
} else if($modimp=='imp-fisico'){
    $modo = 'IMPRESION-FISICO';
    $file_pdfimpresion_php = 'certificado-3.php';
    $parameter_tosend = 'id_certificado';
}else if($modimp=='imp-digital'){
    $modo = 'IMPRESION-DIGITAL';
    $file_pdfimpresion_php = 'certificado-digital-3.php';
    $parameter_tosend = 'id_certificado';
}

/* id sucursal */
$rqdds1 = query("SELECT id_sucursal FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
$rqdds2 = fetch($rqdds1);
$id_sucursal = $rqdds2['id_sucursal'];

if (isset_post('sw_registrar')) {
    $costo_por_certificado = post('costo_por_certificado');
    $ids_impresiones = '';

    $rqcerg1 = query("SELECT e.* FROM cursos_emisiones_certificados e WHERE e.id IN ($ids_emisiones) ");
    while ($rqcerg2 = fetch($rqcerg1)) {
        $id_emision_certificado = $rqcerg2['id'];
        $certificado_id = $rqcerg2['certificado_id'];
        $id_participante = $rqcerg2['id_participante'];
        $id_modo_pago = 1;
        $id_referencia = 20;
        $num_cert_fisico = post('numcert-'.$id_emision_certificado);
        $detalle = 'Impresion de certificado [' . $certificado_id . ']['.$modo.']';
        /* regsitro en contabilidad */
        if ($costo_por_certificado > 0) {
            query("INSERT INTO contabilidad (
                id_tipo_movimiento, 
                id_modo_pago, 
                id_referencia, 
                id_sucursal, 
                monto, 
                fecha, 
                detalle, 
                id_administrador, 
                fecha_registro, 
                estado
                ) VALUES (
                    '1',
                    '$id_modo_pago',
                    '$id_referencia',
                    '$id_sucursal',
                    '$costo_por_certificado',
                    CURDATE(),
                    '$detalle',
                    '$id_administrador',
                    NOW(),
                    '1'
                    ) ");
            $id_contabilidad = insert_id();
            query("INSERT INTO contabilidad_rel_data (
                id_contabilidad,
                id_participante
                ) VALUES (
                    '$id_contabilidad',
                    '$id_participante'
                    )");
        }

        /* log de impresiones */
        query("INSERT INTO certsgenimp_log 
        (id_emision_certificado, id_administrador, modo, costo, num_cert_fisico, fecha) 
        VALUES 
        ('$id_emision_certificado','$id_administrador','$modo','$costo_por_certificado','$num_cert_fisico',NOW())");
        $id_impcert = insert_id();

        /* solicitud de autorizacion */
        if($sw_modulo_autorizacion_impresion_certificado){
            query("INSERT INTO certificados_autorizaciones
            (id_emision_certificado, id_administrador_solicitante, id_administrador_autorizador, id_certsgenimp_log, fecha_solicitud, observacion, estado) 
            VALUES 
            ('$id_emision_certificado','$id_administrador','99','$id_impcert',NOW(),'','0')");
        }

        $ids_impresiones .= ','.$id_impcert.'|'.$certificado_id;
    }
    ?>
    
    <div class="alert alert-success">
        <strong>EXITO</strong> el registro y la generacion de certificados se completo correctamente.
    </div>

    <p>Por favor ingrese los n&uacute;meros de certificado utilizados en la impresion.</p>

    <form action="" id="FORM-registro-numeros-certificado">
        <table class="table table-bordered table-striped">
            <?php
            $ids_a_procesar = explode(',',trim($ids_impresiones,','));
            foreach ($ids_a_procesar as $stringcod) {
                $ar1 = explode('|',$stringcod);
                $id_impresion = $ar1[0];
                $certificado_id = $ar1[1];
            ?>
                <tr>
                    <td style="width: 30px;">Imp:<?php echo $id_impresion; ?></td>
                    <td>ID de certificado: <b class="pull-right"><?php echo $certificado_id; ?></b></td>
                    <td class="text-right">N&uacute;mero:</td>
                    <td><input type="text" class="form-control" name="idimp-<?php echo $id_impresion; ?>"/></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <button type="submit" class="btn btn-success">REGISTRAR N&Uacute;MEROS</button>
                    <input type="hidden" name="ids_impresiones" value="<?php echo $ids_impresiones; ?>" />
                    <input type="hidden" name="sw_regnums" value="1" />
                </td>
            </tr>
        </table>
    </form>
  <?php
} elseif (isset_post('sw_regnums')) {
    $ids_impresiones = post('ids_impresiones');
    $ids_a_procesar = explode(',',trim($ids_impresiones,','));
    foreach ($ids_a_procesar as $stringcod) {
        $ar1 = explode('|',$stringcod);
        $id_impresion = $ar1[0];
        $num_cert_fisico = post('idimp-'.$id_impresion);
        
        /* log de impresiones */
        query("UPDATE certsgenimp_log SET num_cert_fisico='$num_cert_fisico' WHERE id='$id_impresion' ORDER BY id DESC limit 1 ");
    }
    ?>
    
    <div class="alert alert-success">
        <strong>EXITO</strong> el registro de numeraci&oacute;n se completo correctamente.
    </div>

  <?php
} else {

    $rqce1 = query("SELECT e.* FROM cursos_emisiones_certificados e WHERE e.id IN ($ids_emisiones) ");
    $cnt_certificados = num_rows($rqce1);
?>
    <h3 style="background: #e9e9ff;padding: 10px;margin-top: 0px;text-align: center;border: 1px solid #d8d8d8;">Registro de Impresiones</h3>

    <p>SE IMPRIMIRAN <b><?php echo $cnt_certificados; ?></b> CERTIFICADOS.

    <form action="" id="FORM-pago_impresion_certificados">
        <table class="table table-bordered table-striped">
            <?php
            $cntaux = 1;
            while ($rqce2 = fetch($rqce1)) {
            ?>
                <tr>
                    <td style="width: 30px;"><?php echo $cntaux++; ?></td>
                    <td colspan="2">ID de certificado: <b class="pull-right"><?php echo $rqce2['certificado_id']; ?></b></td>
                    <td style="display:none;" class="text-right">N&uacute;mero:</td>
                    <td style="display:none;"><input type="text" class="form-control" name="numcert-<?php echo $rqce2['id']; ?>"/></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Costo por certificado:</b></td>
                <td><input type="number" name="costo_por_certificado" class="form-control" value="" required /></td>
            </tr>
            <tr>
                <td colspan="2"><b>Firmas:</b></td>
                <td><label><input type="checkbox" name="ignorar_firmas" id="ignorar_firmas" value="1" style="width: 12px;height:12px;" checked/> &nbsp; <i>NO INCLUIR FIRMAS O SELLOS</i> <span style="font-weight: 100;font-size:11pt;">( Imagen de Fondo )</span></label></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <button type="submit" class="btn btn-success">IMPRIMIR</button>
                    <input type="hidden" name="ids_emisiones" value="<?php echo $ids_emisiones; ?>" />
                    <input type="hidden" name="modimp" value="<?php echo $modimp; ?>" />
                    <input type="hidden" name="sw_registrar" value="1" />
                </td>
            </tr>
        </table>
    </form>

<?php
}

/* valor a mandar */
if($modimp=='todos-imp-fisico' || $modimp=='todos-imp-digital'){
    $value_tosend = $ids_emisiones;
} else if($modimp=='imp-fisico' || $modimp=='imp-digital'){
    $rqcergaux1 = query("SELECT e.certificado_id FROM cursos_emisiones_certificados e WHERE e.id IN ($ids_emisiones) ");
    $rqcergaux2 = fetch($rqcergaux1);
    $value_tosend = $rqcergaux2['certificado_id'];
}
?>

<script>
    $('#FORM-pago_impresion_certificados').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var sw_ignorar_firmas = 0;
        if (document.getElementById('ignorar_firmas').checked) {
            sw_ignorar_firmas = 1;
        }
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
                window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/<?php echo $file_pdfimpresion_php; ?>?<?php echo $parameter_tosend; ?>=<?php echo $value_tosend; ?>&id_administrador=<?php echo $id_administrador; ?>&hash=<?php echo md5($id_administrador . 'hash'); ?>&sw_ignorar_firmas='+sw_ignorar_firmas, 'popup', 'width=700,height=500');
                setTimeout(() => {
                    lista_participantes(VAR_id_curso, 0);
                }, 2000);
                setTimeout(() => {
                    lista_participantes(VAR_id_curso, 0);
                }, 5000);
            }
        });
    });
</script>

<script>
    $('#FORM-registro-numeros-certificado').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    });
</script>
