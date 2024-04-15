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

/* proceso de edicion */
if (isset_post('update-proceso-2')) {
    /* datos recibidos */
    $monto_pago = post('monto_pago');
    $id_curso = post('id_curso');
    $id_modo_pago = post('id_modo_pago');
    $id_banco = post('id_banco');
    $transaccion_id = post('transaccion_id');
    $observaciones = post('observaciones');
    $pago_id_departamento = post('pago_id_departamento');
    $id_administrador = administrador('id');

    /* monto en gratuito */
    if ($id_modo_pago == '10') {
        $monto_pago = '0';
    }

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = fetch($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    $sw_pago_enviado = '1';
    if ($id_modo_pago == '0') {
        $sw_pago_enviado = '0';
    }

    /* imagen deposito */
    $imagen_deposito = post('actual_imagen_deposito');
    if (is_uploaded_file(archivo('imagen_deposito'))) {
        if ($imagen_deposito !== '') {
            logcursos('Sube imagen respaldo de pago[prev:' . $imagen_deposito . ']', 'partipante-edicion', 'participante', $id_participante);
        }
        $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
        move_uploaded_file(archivo('imagen_deposito'), $___path_raiz . 'contenido/imagenes/depositos/' . $imagen_deposito);
    }

    /* segunda imagen imagen_matricula */
    $imagen_matricula = post('actual_segunda_imagen_deposito');
    if (is_uploaded_file(archivo('imagen_matricula'))) {
        if ($imagen_matricula !== '') {
            logcursos('Sube sec imagen respaldo de pago[prev:' . $imagen_matricula . ']', 'partipante-edicion', 'participante', $id_participante);
        }
        $imagen_matricula = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_matricula')), (strlen(archivoName('imagen_matricula')) - 7));
        move_uploaded_file(archivo('imagen_matricula'), $___path_raiz . 'contenido/imagenes/depositos/' . $imagen_matricula);
    }

    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            id_modo_pago='$id_modo_pago', sw_pago='$sw_pago_enviado'  
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    query("UPDATE cursos_proceso_registro SET 
            id_modo_pago='$id_modo_pago',
            monto_deposito='$monto_pago',
            imagen_deposito='$imagen_deposito',
            imagen_matricula='$imagen_matricula',
            transaccion_id='$transaccion_id',
            pago_id_departamento='$pago_id_departamento',
            sw_pago_enviado='$sw_pago_enviado',
            id_banco='$id_banco',
            observaciones='$observaciones',
            paydata_id_administrador='$id_administrador',
            paydata_fecha=NOW() 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    logcursos('Edicion datos de PAGO', 'partipante-edicion', 'participante', $id_participante);
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> pago editado correctamente.
    </div>
<?php
exit;
}

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = fetch($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT * FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
$imagen_matricula = $data_registro['imagen_matricula'];
$id_banco_pago = $data_registro['id_banco'];
if ($imagen_de_deposito == '') {
    $url_imagen = "https://www.eternacadencia.com.ar/components/com_virtuemart/assets/images/vmgeneral/no-image.jpg";
} else {
    $url_imagen = $dominio_www . "contenido/imagenes/depositos/" . $imagen_de_deposito;
}

$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$paydata_id_administrador = $data_registro['paydata_id_administrador'];
$paydata_fecha = $data_registro['paydata_fecha'];
$observaciones_registro = $data_registro['observaciones'];

$id_cobro_khipu = $data_registro['id_cobro_khipu'];
$transaccion_id = $data_registro['transaccion_id'];
$pago_id_departamento = $data_registro['pago_id_departamento'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* curso */
$rqddcr1 = query("SELECT estado,costo,costo2,costo3,costo_e,costo_e2,sw_fecha2,fecha2,sw_fecha3,fecha3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqddcr2 = fetch($rqddcr1);

$costo_curso = $rqddcr2['costo'];
$costo2_curso = $rqddcr2['costo2'];
$costo3_curso = $rqddcr2['costo3'];
$costoe_curso = $rqddcr2['costo_e'];
$costoe2_curso = $rqddcr2['costo_e2'];

/* costo base */
$costo_base = $rqddcr2['costo'];
if ($rqddcr2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqddcr2['fecha2'])) {
    $costo_base = $rqddcr2['costo2'];
}
if ($rqddcr2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqddcr2['fecha3'])) {
    $costo_base = $rqddcr2['costo3'];
}

/* habilitacion de edicion de pago */
$sw_procesos = false;
$htm_disabled = ' disabled="" ';
if (($rqddcr2['estado'] == '1' || $rqddcr2['estado'] == '2') && $sw_pago_enviado == '0') {
    $sw_procesos = true;
    $htm_disabled = '';
}

/* sw solo info */
$sw_solo_info = false;
if(isset_post('sw_solo_info')){
    $sw_solo_info = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;">
            <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
        <?php
        if ($sw_pago_enviado == '1') {
        ?>
            <b class="pull-right btn btn-success active">PAGO DEFINIDO</b>
        <?php
        } else {
        ?>
            <b class="pull-right btn btn-danger active">PAGO NO DEFINIDO</b>
        <?php
        }
        ?>
        <br />
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr />
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>

<form id="form-pagoparticipante" method="post" enctype="multipart/form-data">
    <div class="panel panel-default" style="border: 1px solid #9ecc93;">
        <div class="panel-body">
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <?php
                        if ($imagen_de_deposito != '') {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>Imagen actual:</b>
                                    <br/>
                                    <img src="<?php echo $url_imagen; ?>" style="width:50%;" />
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2">
                                <b>Metodo de pago:</b>
                                <br/>
                                <select class="form-control myinput" name='id_modo_pago' onchange="modPago(this.value);">
                                    <?php
                                    $rqccmtp1 = query("SELECT id,titulo,titulo_identificador FROM modos_de_pago WHERE estado='1' ORDER BY FIELD(id,4,3,5,11) ");
                                    while ($rqccmtp2 = fetch($rqccmtp1)) {
                                        $selected_aux1 = '';
                                        if ($data_registro['id_modo_pago'] == $rqccmtp2['id']) {
                                            $selected_aux1 = ' selected="selected" ';
                                        }
                                        ?>
                                        <option value="<?php echo $rqccmtp2['id']; ?>" <?php echo $selected_aux1; ?>><?php echo $rqccmtp2['titulo'] . ($rqccmtp2['id'] == 5 ? ' &nbsp; 69714008' : '') . ($rqccmtp2['id'] == 11 ? ' &nbsp; info@nemabol.com' : ''); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr id="TR-banco" style="display: none;">
                            <td colspan="2">
                                <b>Banco:</b>
                                <br/>
                                <select class="form-control myinput" name="id_banco" id="id_banco">
                                    <?php
                                    $rqbn1 = query("SELECT id,nombre FROM bancos WHERE estado=1 ORDER BY id ASC ");
                                    while ($rqbn2 = fetch($rqbn1)) {
                                        $selected_aux1 = '';
                                        if ($data_registro['id_banco'] == $rqbn2['id']) {
                                            $selected_aux1 = ' selected="selected" ';
                                        }
                                        ?>
                                        <option value="<?php echo $rqbn2['id']; ?>" <?php echo $selected_aux1; ?>><?php echo $rqbn2['nombre']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr id="TR-idtransaccion">
                            <td colspan="2">
                                <b>ID de transacci&oacute;n:</b> <span style="color:gray;font-size:9pt;">(TIGO MONEY)</span>
                                <br/>
                                <input class="form-control myinput" type='text' name='transaccion_id' placeholder="ID de transacci&oacute;n..." value="<?= $transaccion_id ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Monto en Bolivianos del pago:</b>
                                <br/>
                                <input class="form-control myinput" type='number' name='monto_pago' required="required" value="<?php echo $monto_de_pago; ?>"/>
                            </td>
                            <td>
                                <b>Imagen/foto/screenshot del comprobante:</b>
                                <br/>
                                <input class="form-control myinput" type='file' accept="image/*" name='imagen_deposito' />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                            </td>
                            <td>
                                <b>Segunda imagen: (OPCIONAL)</b>
                                <br/>
                                <input class="form-control myinput" type='file' accept="image/*" name='imagen_matricula' placeholder="Segunda imagen... (OPCIONAL)"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>Ciudad donde se hizo el pago:</b>
                                <br/>
                                <select class="form-control myinput" name="pago_id_departamento">
                                    <option value="3" <?= ($pago_id_departamento == '3')?' selected="selected" ':'' ?>>La Paz</option>
                                    <option value="1" <?= ($pago_id_departamento == '1')?' selected="selected" ':'' ?>>Cochabamba</option>
                                    <option value="4" <?= ($pago_id_departamento == '4')?' selected="selected" ':'' ?>>Santa Cruz</option>
                                    <option value="6" <?= ($pago_id_departamento == '6')?' selected="selected" ':'' ?>>Chuquisaca</option>
                                    <option value="2" <?= ($pago_id_departamento == '2')?' selected="selected" ':'' ?>>Potosi</option>
                                    <option value="8" <?= ($pago_id_departamento == '8')?' selected="selected" ':'' ?>>Oruro</option>
                                    <option value="7" <?= ($pago_id_departamento == '7')?' selected="selected" ':'' ?>>Pando</option>
                                    <option value="9" <?= ($pago_id_departamento == '9')?' selected="selected" ':'' ?>>Beni</option>
                                    <option value="5" <?= ($pago_id_departamento == '5')?' selected="selected" ':'' ?>>Tarija</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br/>
        </div>
        <div class="panel-footer">
            <div class="row">
                <input type="hidden" name="update-proceso-2" value="true"/>
                <input type="hidden" name="id_participante" value="<?= $id_participante ?>"/>
                <input type="hidden" name="actual_imagen_deposito" value="<?= $imagen_de_deposito ?>"/>
                <input type="hidden" name="actual_segunda_imagen_deposito" value="<?= $imagen_matricula ?>"/>
                <div class="col-sm-12 text-center" style="padding: 20px;">
                    <input type="submit" class="btn btn-lg btn-success" value='ACTUALIZAR PAGO' style="border-radius: 7px;" name='subir-comprobante'/>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_proceso_registro" value="<?php echo $id_proceso_registro; ?>"/>
</form>
</div>



<script>
    $('#form-pagoparticipante').on('submit', function(e) {
        e.preventDefault();
        $("#ajaxloading-pago_participante").html("CARGANDO...");
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.pago_participante.editar.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajaxloading-pago_participante").html("");
                $("#ajaxbox-pago_participante").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    });
</script>


<!-- modPago -->
<script>
    function modPago(cod) {
        switch (cod) {
            case '5':
                $('#TR-idtransaccion').css('display', 'table-row');
                $('#TR-banco').css('display', 'none');
                break;
            case '11':
                $('#TR-banco').css('display', 'none');
                $('#TR-idtransaccion').css('display', 'none');
                break;
            case '1':
                $('#TR-idtransaccion').css('display', 'none');
                $('#TR-banco').css('display', 'none');
                break;
            case '10':
                $('#TR-idtransaccion').css('display', 'none');
                $('#TR-banco').css('display', 'none');
                break;
            default:
                $('#TR-banco').css('display', 'table-row');
                $('#TR-idtransaccion').css('display', 'none');
        }
    }
</script>
