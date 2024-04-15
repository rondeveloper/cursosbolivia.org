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

/* datos recibidos */
$id_participante = post('id_participante');
$nro_lista = post('nro_lista');


/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = mysql_fetch_array($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = mysql_fetch_array($rqrp1);
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_modo_de_registro = $data_registro['id_modo_de_registro'];
$id_emision_factura = $data_registro['id_emision_factura'];

$metodo_de_pago = $data_registro['metodo_de_pago'];
$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$observacion_participante = $participante['observacion'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* turnos */
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$sw_turnos = false;
if (mysql_num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;">
           <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
        <b class="pull-right btn btn-info active">Nro. <?php echo $nro_lista; ?></b>
        <br/>
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
    <form id="form-participante-<?php echo $participante['id']; ?>">
        <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-8">

                    <h5 class="text-center">
                        Prefijo / Nombre / Apellidos
                    </h5>
                    <div class="row">
                        <div class="col-md-3 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="prefijo" value="<?php echo $participante['prefijo']; ?>" placeholder="Prefijo..."/>
                        </div>
                        <div class="col-md-4 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="nombres" value="<?php echo $participante['nombres']; ?>" placeholder="Nombres..." id="f-nom-p" onkeyup="this.value = this.value.toUpperCase()"/>
                        </div>
                        <div class="col-md-5 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="apellidos" value="<?php echo $participante['apellidos']; ?>" placeholder="Apellidos..." id="f-ape-p" onkeyup="this.value = this.value.toUpperCase()"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center">
                        CI / Expedido
                    </h5>
                    <div class="row">
                        <div style="width:60%;float: left;">
                            <input type="text" name="ci" class="form-control" placeholder="C.I." value="<?php echo $participante['ci']; ?>" id="f-ci-p"/>
                        </div>
                        <div style="width: 40%;float: left;">
                            <select class="form-control" name="ci_expedido">
                                <option value="<?php echo $participante['ci_expedido']; ?>"><?php echo ($participante['ci_expedido'] == '') ? '...' : $participante['ci_expedido']; ?></option>
                                <option value="">...</option>
                                <option value="LP">LP</option>
                                <option value="CB">CB</option>
                                <option value="SC">SC</option>
                                <option value="OR">OR</option>
                                <option value="PT">PS</option>
                                <option value="CH">CH</option>
                                <option value="PD">PD</option>
                                <option value="BN">BN</option>
                                <option value="TJ">TJ</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-center">
                        Razon Social / NIT
                        &nbsp;&nbsp;
                        <b onclick="nombreFact2(1);" class="btn btn-xs btn-primary">NF1</b>
                        &nbsp;
                        <b onclick="nombreFact2(2);" class="btn btn-xs btn-primary">NF2</b>
                        &nbsp;
                        <b onclick="nombreFact2(3);" class="btn btn-xs btn-primary">NF3</b>
                        &nbsp;
                        <b onclick="nitFact2();" class="btn btn-xs btn-primary">CiNit</b>
                    </h5>
                    <div class="row">
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="razon_social" value="<?php echo $razon_social_de_registro; ?>" placeholder="Razon Social..." id="f-raz-p"/>
                        </div>
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="nit" value="<?php echo $nit_de_registro; ?>" placeholder="NIT..." id="f-nit-p"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-center">
                        Celular / Correo
                    </h5>
                    <div class="row">
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="celular" value="<?php echo $participante['celular']; ?>" placeholder="Celular..."/>
                        </div>
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="correo" value="<?php echo $participante['correo']; ?>" placeholder="Correo..."/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">
                        Numeraci&oacute;n / Observaciones
                    </h5>
                    <div class="row">
                        <div class="col-md-5 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="numeracion" value="<?php echo $participante['numeracion']; ?>" placeholder="Numeraci&oacute;n..."/>
                            <input type='checkbox' name='reenumerar' value='1'/> <i class="fa Example of arrow-up fa-arrow-up"></i> re-enumerar 
                        </div>
                        <div class="col-md-7 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="observacion" value="<?php echo $observacion_participante; ?>" placeholder="Observaciones..."/>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            if ($sw_turnos) {
                ?>
                <h5 class="text-center">
                    Turno seleccionado
                </h5>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <select class="form-control" name="id_turno" id="numero_participantes">
                            <?php
                            $rqttc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                            while ($rqtc2 = mysql_fetch_array($rqttc1)) {
                                $txt_selected = '';
                                if ($participante['id_turno'] == $rqtc2['id']) {
                                    $txt_selected = ' selected="selected" ';
                                }
                                ?>
                                <option value="<?php echo $rqtc2['id']; ?>" <?php echo $txt_selected; ?> ><?php echo $rqtc2['titulo'] . ' | ' . $rqtc2['descripcion']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
            } else {
                echo '<input type="hidden" name="id_turno" value="0"/>';
            }
            ?>

        </div>

        <br/>

        <!-- DIV CONTENT AJAX :: EDICION DE PARTICIPANTE P2 -->
        <div id="ajaxloading-edita_participante_p2"></div>
        <div id="ajaxbox-edita_participante_p2">
            <div class="panel-footer">
                <br/>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>" />
<!--                <b class="btn btn-success" onclick="edita_participante_p2(<?php echo $participante['id']; ?>);" >ACTUALIZAR DATOS</b>-->
                <input type="submit" class="btn btn-success" value="ACTUALIZAR DATOS"/>
                &nbsp;&nbsp;&nbsp;
                <b class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</b>
                <br/>
                <br/>
            </div>
        </div>

    </form>
</div>


<script>
    $('#form-participante-<?php echo $participante['id']; ?>').on('submit', function(e) {
        // evito que propague el submit
        e.preventDefault();

        $("#ajaxloading-edita_participante_p2").html(text__loading_dos);

        // agrego la data del form a formData
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_participante_p2.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajaxloading-edita_participante_p2").html("");
                $("#ajaxbox-edita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    });
</script>
