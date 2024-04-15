<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_participante = post('id_participante');

/* participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC ");
$participante = mysql_fetch_array($resultado1);
$id_usuario = $participante['id_usuario'];
$id_proceso_de_registro = $participante['id_proceso_registro'];

/* id_onlinecourse */
if (isset_post('id_onlinecourse')) {
    $id_onlinecourse = post('id_onlinecourse');
} else {
    $rqdoc1 = query("SELECT id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id_curso=(SELECT id_curso FROM cursos_participantes WHERE id='$id_participante') ");
    $rqdoc2 = mysql_fetch_array($rqdoc1);
    $id_onlinecourse = $rqdoc2['id_onlinecourse'];
}

/* curso virtual */
$rqdcv1 = query("SELECT urltag,titulo FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$rqdcv2 = mysql_fetch_array($rqdcv1);
$url_cursovirtual = "https://cursos.bo/curso-online/" . $rqdcv2['urltag'] . ".html";
$titulo_cursovirtual = $rqdcv2['titulo'];

if ($id_usuario !== '0') {
    /* usuario */
    $rqdu1 = query("SELECT id,email,password FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    $rqdu2 = mysql_fetch_array($rqdu1);
    $nick_usuario = $rqdu2['email'];
    $password_usuario = $rqdu2['password'];
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;text-transform: uppercase;color: #00789f;">
            <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
        <br/>
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
        <br/>
        <b style="font-size:12pt;"><?php echo $participante['correo']; ?> - <?php echo $participante['celular']; ?></b>
    </div>
</div>
<hr/>
<div class="panel panel-info">
    <div class="panel-heading">DATOS DE ACCESO</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <?php
            if ($id_usuario !== '0') {
                ?>
                <tr>
                    <td><b>Curso virtual:</b></td>
                    <td><?php echo $titulo_cursovirtual; ?></td>
                </tr>
                <tr>
                    <td><b>Usuario:</b></td>
                    <td><?php echo $nick_usuario; ?></td>
                </tr>
                <tr>
                    <td><b>Contrase&ntilde;a:</b></td>
                    <td><?php echo $password_usuario; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><b>Url de ingreso:</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><?php echo $url_cursovirtual; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>Datos por WhatsApp:</b></td>
                    <td>
                        <?php
                        if (strlen(trim($participante['celular'])) == 8) {
                            $txt_whatsapp = 'Buen día '.utf8_decode($participante['nombres'] . ' ' . $participante['apellidos']).'__Le hacemos el envío de los datos de acceso al curso *'.utf8_decode($titulo_cursovirtual).'*__ __*URL de ingreso:*__'.urlencode($url_cursovirtual).'__ __*Usuario:*__'.$nick_usuario.'__ __*Contraseña:*__'.$password_usuario;
                            $txt_whatsapp = utf8_encode(str_replace('__','%0A',str_replace(' ','%20', $txt_whatsapp)));
                            ?>
                            <a href="https://api.whatsapp.com/send?phone=591<?php echo trim($participante['celular']);  ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="https://i.ya-webdesign.com/images/whatsapp-icon-transparent-png-7.png" style="height: 40px;border-radius: 5px;"/>
                            </a>
                            <?php
                        } else {
                            echo "Celular incorrecto!";
                        }
                        ?>
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="2">
                        <b>Sin cuenta de usuario</b>
                    </td>
                    <td><button class="btn btn-xs btn-default pull-right" data-toggle="collapse" data-target="#COLLAPSE"><i class="fa fa fa-envelope"></i></button></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="alert alert-warning">
                            <strong>AVISO</strong> no se habilito la cuenta para curso virtual.
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">NOTIFICACI&Oacute;N POR CORREO</div>
    <div class="panel-body">
        <table class="table table-bordered table-hover">
            <?php if ($id_usuario !== '0') { ?>
                <tr>
                    <td>
                        <span>
                            Correo de informaci&oacute;n de acceso.
                        </span>
                        <input type="hidden" id="datos_ingreso_correo" value="<?php echo $participante['correo']; ?>" />
                        <input type="hidden" id="datos_ingreso_id_participante" value="<?php echo $participante['id']; ?>"/>
                    </td>
                    <td id="box-datos_ingreso">
                        <b class="btn btn-success btn-block" onclick="datos_ingreso();">
                            <i class="fa fa-user"></i> &nbsp; DATOS INGRESO
                        </b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            Correo de bienvenida al curso virtual.
                        </span>
                        <input type="hidden" id="correo_bienvenida_correo" value="<?php echo $participante['correo']; ?>"/>
                        <input type="hidden" id="correo_bienvenida_id_participante" value="<?php echo $participante['id']; ?>"/>
                    </td>
                    <td id="box-correo_bienvenida">
                        <b class="btn btn-success btn-block" onclick="correo_bienvenida();">
                            <i class="fa fa-check"></i> &nbsp; BIENVENIDA
                        </b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            Correo de solicitud de avance y culminaci&oacute;n de curso virtual.
                        </span>
                        <input type="hidden" id="solicitar_avance_correo" value="<?php echo $participante['correo']; ?>"/>
                        <input type="hidden" id="solicitar_avance_id_participante" value="<?php echo $participante['id']; ?>"/>
                    </td>
                    <td id="box-solicitar_avance">
                        <b class="btn btn-success btn-block" onclick="solicitar_avance();">
                            <i class="fa fa-calendar"></i> &nbsp; SOLICITAR AVANCE
                        </b>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <span>
                        Correo de solicitud del pago correspondiente al curso virtual.
                    </span>
                    <input type="hidden" id="solicitar_pago_auxdata_correo" value="<?php echo $participante['correo']; ?>"/>
                    <input type="hidden" id="solicitar_pago_auxdata_id_proceso_registro" value="<?php echo $id_proceso_de_registro; ?>"/>
                </td>
                <td id="box-solicitar_pago_auxdata">
                    <b class="btn btn-success btn-block" onclick="solicitar_pago_auxdata();">
                        <i class="fa fa-money"></i> &nbsp; SOLICITAR PAGO
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <span>
                        Correo de ficha de registro.
                    </span>
                    <input type="hidden" id="sendficha_correo" value="<?php echo $participante['correo']; ?>"/>
                    <input type="hidden" id="sendficha_id_proceso_registro" value="<?php echo $id_proceso_de_registro; ?>"/>
                </td>
                <td id="box-sendficha">
                    <b class="btn btn-success btn-block" onclick="enviar_ficharegistro();">
                        <i class="fa fa-send"></i> &nbsp; FICHA REGISTRO
                    </b>
                </td>
            </tr>
        </table>
    </div>
</div>

<hr/>
<?php
if ($id_usuario !== '0') {
    ?>
    <div class="panel panel-info">
        <div class="panel-heading">LECCIONES AVANZADAS</div>
        <div class="panel-body">
            <?php
            $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id IN (select id from cursos_onlinecourse_lecciones where id_onlinecourse='$id_onlinecourse') ");
            if (mysql_num_rows($rqdavl1) == 0) {
                echo "No se registro avance en lecciones";
            }
            while ($rqdavl2 = mysql_fetch_array($rqdavl1)) {
                $t = $rqdavl2['minutos'] * 60;
                $s = $rqdavl2['segundos'];
                $p = round($s * 100 / $t);
                if ($p > 100) {
                    $p = 100;
                    $rqdavl2['segundos'] = $t;
                }
                ?>
                <?php echo $rqdavl2['titulo']; ?>
                <span class="pull-right"><?php echo round(($rqdavl2['segundos']) / 60, 2); ?>/<?php echo $rqdavl2['minutos']; ?> minutos</span>
                <br/>
                <div class="progress" style="background: #d2d8dc;">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $p; ?>%;">
                        <?php echo $p; ?>% Completo (terminado)
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <hr/>
    <div class="panel panel-info">
        <div class="panel-heading">EVALUACIONES REALIZADAS</div>
        <div class="panel-body">
            <?php
            $rqdael1 = query("SELECT * FROM cursos_onlinecourse_evaluaciones WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ");
            if (mysql_num_rows($rqdael1) == 0) {
                echo "<p>No se registraron evaluaciones</p>";
            } else {
                ?>
                <table class="table table-striped table-bordered">
                    <?php
                    while ($rqdavl2 = mysql_fetch_array($rqdael1)) {
                        ?>
                        <tr>
                            <td>
                                <b class="btn btn-sm btn-success active"><?php echo round(($rqdavl2['total_correctas'] * 100) / $rqdavl2['total_preguntas'], 1); ?>%</b>
                            </td>
                            <td>
                                <b class="label label-primary"><?php echo $rqdavl2['total_correctas'] . '/' . $rqdavl2['total_preguntas']; ?> respuestas correctas</b>
                            </td>
                            <td>
                                Fecha: <?php echo date("d/m/Y H:i", strtotime($rqdavl2['fecha'])); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
    <hr/>
    <?php
}
?>

<!-- envio de ficha de registro -->
<script>
    function enviar_ficharegistro() {
        var sendficha_correo = $("#sendficha_correo").val();
        var sendficha_id_proceso_registro = $("#sendficha_id_proceso_registro").val();
        $("#box-sendficha").html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_ficharegistro.php',
            data: {sendficha_correo: sendficha_correo, sendficha_id_proceso_registro: sendficha_id_proceso_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-sendficha").html(data);
            }
        });
    }
</script>

<!-- solicitar_pago_auxdata -->
<script>
    function solicitar_pago_auxdata() {
        var correo = $("#solicitar_pago_auxdata_correo").val();
        var id_proceso_registro = $("#solicitar_pago_auxdata_id_proceso_registro").val();
        $("#box-solicitar_pago_auxdata").html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.solicitar_pago.php',
            data: {correo: correo, id_proceso_registro: id_proceso_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-solicitar_pago_auxdata").html(data);
            }
        });
    }
</script>

<!-- correo_bienvenida -->
<script>
    function correo_bienvenida() {
        var correo = $("#correo_bienvenida_correo").val();
        var id_participante = $("#correo_bienvenida_id_participante").val();
        $("#box-correo_bienvenida").html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.cursovirtual.correo_bienvenida.php',
            data: {correo: correo, id_participante: id_participante, id_onlinecourse: '<?php echo $id_onlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-correo_bienvenida").html(data);
            }
        });
    }
</script>

<!-- datos_ingreso -->
<script>
    function datos_ingreso() {
        var correo = $("#datos_ingreso_correo").val();
        var id_participante = $("#datos_ingreso_id_participante").val();
        $("#box-datos_ingreso").html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.cursovirtual.datos_ingreso.php',
            data: {correo: correo, id_participante: id_participante, id_onlinecourse: '<?php echo $id_onlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-datos_ingreso").html(data);
            }
        });
    }
</script>

<!-- solicitar_avance -->
<script>
    function solicitar_avance() {
        var correo = $("#solicitar_avance_correo").val();
        var id_participante = $("#solicitar_avance_id_participante").val();
        $("#box-solicitar_avance").html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.cursovirtual.solicitar_avance.php',
            data: {correo: correo, id_participante: id_participante, id_onlinecourse: '<?php echo $id_onlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-solicitar_avance").html(data);
            }
        });
    }
</script>

