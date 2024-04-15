<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_participante = post('id_participante');

/* participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC ");
$participante = fetch($resultado1);
$id_usuario = $participante['id_usuario'];
$id_proceso_de_registro = $participante['id_proceso_registro'];
$hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);
$id_pais = $participante['id_pais'];
$id_curso = $participante['id_curso'];

/* codigo pais */
$rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
$rqdcw2 = fetch($rqdcw1);
$codigo_pais = $rqdcw2['codigo'];

/* curso */
$rqdcur1 = query("SELECT id_modalidad,sw_ipelc FROM cursos WHERE id='$id_curso' LIMIT 1 ");
$rqdcur2 = fetch($rqdcur1);
$id_modalidad_curso = $rqdcur2['id_modalidad'];
$sw_ipelc = $rqdcur2['sw_ipelc'];

/* id_onlinecourse */
if (isset_post('id_onlinecourse')) {
    $id_onlinecourse = post('id_onlinecourse');
} else {
    $rqdoc1 = query("SELECT id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id_curso=(SELECT id_curso FROM cursos_participantes WHERE id='$id_participante') AND estado='1' ");
    $rqdoc2 = fetch($rqdoc1);
    $id_onlinecourse = $rqdoc2['id_onlinecourse'];
}

$rqdoccc1 = query("SELECT fecha_final FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$rqdoccc2 = fetch($rqdoccc1);
$fecha_final_curso_virtual = $rqdoccc2['fecha_final'];

/* curso virtual */
$rqdcv1 = query("SELECT urltag,titulo FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$rqdcv2 = fetch($rqdcv1);
$url_cursovirtual = $dominio_plataforma."ingreso/" . $rqdcv2['urltag'] . "/".$hash_iduser.".html";
$titulo_cursovirtual = $rqdcv2['titulo'];

if ($id_usuario !== '0') {
    /* usuario */
    $rqdu1 = query("SELECT id,email,password FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    $rqdu2 = fetch($rqdu1);
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
            $rqvac1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') AND estado=1 ");
            if ($id_usuario !== '0' && num_rows($rqvac1)>0) {
                ?>
                <tr>
                    <td><b>Curso virtual:</b></td>
                    <td><?php echo $titulo_cursovirtual; ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><b>Link de ingreso:</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><a href="<?php echo $url_cursovirtual; ?>" target="_blank"><?php echo $url_cursovirtual; ?></a></td>
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
                    <td colspan="2" class="text-center">&nbsp;</td>
                </tr>
                <tr>
                    <td><b>Datos por WhatsApp:</b></td>
                    <td>
                        <?php
                        if (strlen(trim($participante['celular'])) == 8) {
                            if($id_modalidad_curso=='2'){
                                $txt_mensajecursopregrabado = ('__El curso está activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.date("d/m/Y",strtotime($fecha_final_curso_virtual)).' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.__');
                            }elseif($sw_ipelc=='1'){
                                $txt_mensajecursopregrabado = $txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, podrá dar Examen en Linea, podrá enviar los documentos para la certificación de la IPELC, podrá hacer seguimiento a la certificación IPELC.';
                            }else{
                                $txt_mensajecursopregrabado = ' ';
                            }
                            
                            $txt_whatsapp = 'Hola '.($participante['nombres'] . ' ' . $participante['apellidos']).'__te hacemos el envío de los datos de acceso al curso *'.($titulo_cursovirtual).'*__'.$txt_mensajecursopregrabado.'__*Link de ingreso:*__'.urlencode($url_cursovirtual).'__ __*Usuario:* '.$nick_usuario.'__ __*Contraseña:* '.$password_usuario;
                            $txt_whatsapp = (str_replace('__','%0A',str_replace(' ','%20', $txt_whatsapp)));
                            ?>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo $codigo_pais.trim($participante['celular']);  ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="https://www.cursos.bo/contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 40px;border-radius: 5px;"/>
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
            if (num_rows($rqdavl1) == 0) {
                echo "No se registro avance en lecciones";
            }
            while ($rqdavl2 = fetch($rqdavl1)) {
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
            if (num_rows($rqdael1) == 0) {
                echo "<p>No se registraron evaluaciones</p>";
            } else {
                ?>
                <table class="table table-striped table-bordered">
                    <?php
                    while ($rqdavl2 = fetch($rqdael1)) {
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
            url: 'pages/ajax/ajax.instant.enviar_ficharegistro.php',
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
            url: 'pages/ajax/ajax.instant.solicitar_pago.php',
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
            url: 'pages/ajax/ajax.instant.cursovirtual.correo_bienvenida.php',
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
            url: 'pages/ajax/ajax.instant.cursovirtual.datos_ingreso.php',
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
            url: 'pages/ajax/ajax.instant.cursovirtual.solicitar_avance.php',
            data: {correo: correo, id_participante: id_participante, id_onlinecourse: '<?php echo $id_onlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-solicitar_avance").html(data);
            }
        });
    }
</script>

