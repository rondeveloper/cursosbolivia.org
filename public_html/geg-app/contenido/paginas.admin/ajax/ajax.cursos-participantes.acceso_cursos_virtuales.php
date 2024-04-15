<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$correo_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_curso = $rqdp2['id_curso'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];
$estado_participante = $rqdp2['estado'];
$id_usuario_participante = $rqdp2['id_usuario'];
$modo_pago_participante = $rqdp2['modo_pago'];
$nom_para_certificado = trim($rqdp2['prefijo'] . ' ' . $rqdp2['nombres'] . ' ' . $rqdp2['apellidos']);

/* usuario */
$rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
$rqddu2 = mysql_fetch_array($rqddu1);
$user_usuario = $rqddu2['email'];
$password_usuario = $rqddu2['password'];

/* registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$proc_registro = mysql_fetch_array($rqdpr1);

/* curso */
$rqddcapcv1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
$rqddcapcv2 = mysql_fetch_array($rqddcapcv1);
$sw_habilitacion_de_procesos = false;
if($rqddcapcv2['estado']=='1' || $rqddcapcv2['estado']=='2'){
    $sw_habilitacion_de_procesos = true;
}
?>

<div class="text-center">
    <b>Participante</b>
    <h3><?php echo $nombres_participante . ' ' . $apellidos_participante; ?></h3>
    <b style="font-size:12pt;"><?php echo $correo_participante; ?> - <?php echo $celular_participante; ?></b>
</div>

<hr/>

<table class="table table-bordered" style="margin: 10px 0px;border: 2px solid #bccdd8;background: #FFF;">
    <tr>
        <th>C-virtual</th>
        <th>Acceso</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT * FROM cursos_onlinecourse WHERE id IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso')");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    $contenido_textarea = '';
    $contenido_whatsapp = '';
    $contenido_div_copy = '';
    $sw_asignacion = false;

    while ($curso = mysql_fetch_array($rqccg1)) {
        /* curso virtual */
        $id_onlinecourse = $curso['id'];
        $nombre_curso_virtual = $curso['titulo'];
        $urltag_curso_virtual = $curso['urltag'];
        $url_ingreso_cv = 'https://cursos.bo/curso-online/' . $urltag_curso_virtual . '.html';

        /* acceso */
        $sw_acceso = false;
        $rqaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario_participante' AND sw_acceso='1' ");
        if (mysql_num_rows($rqaccv1) > 0) {
            $sw_acceso = true;
            $sw_asignacion = true;
        }

        if ($sw_acceso) {
            $contenido_textarea .= '
*' . $nombre_curso_virtual . '*
*URL:*  ' . $url_ingreso_cv . '
*USUARIO:*  ' . $user_usuario . '
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '

';
            $contenido_div_copy .= '
<br>
*' . $nombre_curso_virtual . '*
<br>
*URL:*  ' . $url_ingreso_cv . '
<br>
*USUARIO:*  ' . $user_usuario . '
<br>
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '
<br>
<br>
';

            $contenido_whatsapp .= '__*URL de ingreso:*  ' . $url_ingreso_cv . '__ __*' . $nombre_curso_virtual . '*__*Usuario:*  ' . $user_usuario . '__*Contraseña:*  ' . $password_usuario . ' __';
        }
        ?>
        <tr>
            <td>
                <?php echo $nombre_curso_virtual; ?> 
                <br>
                <br>
                <?php
                if ($sw_acceso) {
                    ?>
                    <b data-toggle="modal" data-target="#MODAL-avance-cvirtual" class="btn btn-info btn-xs" onclick="avance_cvirtual_especifico('<?php echo $id_participante; ?>', '<?php echo $id_onlinecourse; ?>');">PANEL C-vir</b>
                    <?php
                }
                ?>
            </td>
            <td id="ajaxloading-acceso_cvirtual-p<?php echo $id_onlinecourse; ?>">
                <?php
                if ($sw_acceso) {
                    ?>
                    <div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
                    <?php if($sw_habilitacion_de_procesos){ ?>
                    <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                        Des-habilitar: <b class="btn btn-danger btn-xs" onclick="elimina_participante_cvirtual_multiple('<?php echo $id_participante; ?>', '<?php echo $id_onlinecourse; ?>');">X</b>
                    </div>
                    <?php } ?>
                    <?php
                } else {
                    ?>
                    <div style="color:#FFF;background: #ef8a80;padding: 7px;text-align: center;border: 1px solid #e74c3c;">NO HABILITADO</div>
                    <?php if($sw_habilitacion_de_procesos){ ?>
                    <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                        Habilitar: &nbsp; <b class="btn btn-success btn-xs" onclick="habilita_participante_cvirtual_multiple('<?php echo $id_participante; ?>', '<?php echo $id_onlinecourse; ?>');"><i class="fa fa-check"></i></b>
                    </div>
                    <?php } ?>
                    <?php
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr>

<?php if ($sw_asignacion) { ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">ACCESO A CURSOS POR WHATSAPP</div>
            <div class="panel-body">
                <?php
                if (strlen(trim($celular_participante)) == 8) {
                    $txt_whatsapp = 'Buen día ' . utf8_decode($nombres_participante . ' ' . $apellidos_participante) . '__Le hacemos el envío de los datos de acceso a sus cursos virtuales:__ __'.$contenido_whatsapp;
                    $txt_whatsapp = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                    ?>
                    <a href="https://api.whatsapp.com/send?phone=591<?php echo trim($celular_participante);    ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                        <img src="https://i.ya-webdesign.com/images/whatsapp-icon-transparent-png-7.png" style="height: 40px;border-radius: 5px;"/>
                    </a>
                    <?php
                } else {
                    echo "Celular incorrecto!";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">ACCESO A CURSOS POR CORREO</div>
            <div class="panel-body" id="ajaxbox-cvirtual_enviar_correo_accesos">
                <b class="btn btn-success btn-lg" onclick="cvirtual_enviar_correo_accesos('<?php echo $id_participante; ?>');"><i class="fa fa-send"></i> ENVIAR ACCESOS</b>
            </div>
        </div>
    </div>
</div>
    <hr>
    <div class="panel panel-success">
        <div class="panel-heading">ACCESO A CURSO EN FORMATO TEXTO <b class="btn btn-info btn-xs pull-right" onclick="copyToClipboard('cont-accesos')">COPY</b></div>
        <div class="panel-body">
            <textarea class="form-control" style="height: 620px;"><?php echo $contenido_textarea; ?></textarea>
        </div>
        <div class="form-control" style="display:none;" id="cont-accesos"><?php echo $contenido_div_copy; ?></div>
    </div>
    <br/>
    <br/>
<?php } ?>

    
<!-- enviar_correo_accesos -->
<script>
    function cvirtual_enviar_correo_accesos(id_participante) {
        $("#ajaxbox-cvirtual_enviar_correo_accesos").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.cvirtual_enviar_correo_accesos.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxbox-cvirtual_enviar_correo_accesos").html(data);
            }
        });
    }
</script>

<!-- avance-cvirtual -->
<script>
    function avance_cvirtual_especifico(id_participante, id_onlinecourse) {
        $("#ajaxbox-avance_cvirtual").html("");
        $("#ajaxloading-avance_cvirtual").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.avance_cvirtual.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-avance_cvirtual").html("");
                $("#ajaxbox-avance_cvirtual").html(data);
            }
        });
    }
</script>

<script>
    function habilita_participante_cvirtual_multiple(id_participante, id_onlinecourse) {
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_multiple.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html(data);
                acceso_cursos_virtuales(id_participante);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function elimina_participante_cvirtual_multiple(id_participante, id_onlinecourse) {
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_multiple.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse).html(data);
                acceso_cursos_virtuales(id_participante);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>