<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
$rqdp2 = fetch($rqdp1);
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$correo_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_curso = $rqdp2['id_curso'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];
$estado_participante = $rqdp2['estado'];
$id_usuario_participante = $rqdp2['id_usuario'];
$id_modo_pago = $rqdp2['id_modo_pago'];
$nom_para_certificado = trim($rqdp2['prefijo'] . ' ' . $rqdp2['nombres'] . ' ' . $rqdp2['apellidos']);
$id_pais = $rqdp2['id_pais'];

/* codigo pais */
$rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
$rqdcw2 = fetch($rqdcw1);
$codigo_pais = $rqdcw2['codigo'];

/* usuario */
$rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
$rqddu2 = fetch($rqddu1);
$user_usuario = $rqddu2['email'];
$password_usuario = $rqddu2['password'];

/* registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$proc_registro = fetch($rqdpr1);
$cod_registro = $proc_registro['codigo'];
$monto_deposito_registro = $proc_registro['monto_deposito'];
$id_banco_pago_registro = $proc_registro['id_banco'];
$observaciones_registro = $proc_registro['observaciones'];
$imagen_deposito = $proc_registro['imagen_deposito'];

/* curso */
$rqddcapcv1 = query("SELECT estado,id_modalidad,sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
$rqddcapcv2 = fetch($rqddcapcv1);
$sw_habilitacion_de_procesos = false;
$id_modalidad_curso = $rqddcapcv2['id_modalidad'];
$sw_ipelc = $rqddcapcv2['sw_ipelc'];
if ($rqddcapcv2['estado'] == '1' || $rqddcapcv2['estado'] == '2') {
    $sw_habilitacion_de_procesos = true;
}
?>

<div class="text-center">
    <b>Participante</b>
    <h3><?php echo $nombres_participante . ' ' . $apellidos_participante; ?></h3>
    <b style="font-size:12pt;"><?php echo $correo_participante; ?> - <?php echo $celular_participante; ?></b>
    <hr>
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Cod registro:</b> <?php echo $cod_registro; ?></td>
            <td><b>Monto:</b> <?php echo $monto_deposito_registro; ?> BS</td>
        </tr>
        <tr>
            <td>
                <b>Modo de pago:</b> 
                <?php
                if ($id_modo_pago == '0') {
                    echo "SIN PAGO";
                } else {
                    $rqdmdp1 = query("SELECT titulo FROM modos_de_pago WHERE id='$id_modo_pago' ");
                    $rqdmdp2 = fetch($rqdmdp1);
                    echo $rqdmdp2['titulo'];
                }
                ?>
            </td>
            <td>
                <b>Banco:</b> 
                <?php
                if ($id_banco_pago_registro == '0') {
                    echo "NO APLICA";
                } else {
                    $rqdmdp1 = query("SELECT c.*,(b.nombre)nombre_banco FROM cuentas_de_banco c LEFT JOIN bancos b ON c.id_banco=b.id WHERE c.id='$id_banco_pago_registro' ");
                    $rqdmdp2 = fetch($rqdmdp1);
                    echo $rqdmdp2['descripcion'] . ' &nbsp; | &nbsp; ' . $rqdmdp2['numero_cuenta'] . ' &nbsp; | &nbsp; ' . $rqdmdp2['nombre_banco'];
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Obs:</b> <?php echo $observaciones_registro==''?'Sin observaciones':$observaciones_registro; ?></td>
            <td>
                <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $id_participante; ?>');" class="btn btn-xs btn-default">
                    <i class="fa fa-money"></i> MODIFICAR DATOS DEL PAGO
                </a>
            </td>
        </tr>
        <tr>
            <td><b>Comprobante de pago:</b></td>
            <td style="width: 65%;">
                <?php 
                if($imagen_deposito==''){
                    echo "Sin comprobante";
                }else{
                    ?>
                    <img src="<?php echo $dominio_www; ?>contenido/imagenes/depositos/<?php echo $imagen_deposito; ?>" style="width: 100%;"/>
                    <?php
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<hr/>

<form action="" method="post" id="FORM-acv">
    <div class="text-right">
        <input type="submit" name="activar-seleccionados" value="ACTIVAR SELECCIONADOS" class="btn btn-xs btn-success"/>
    </div>
<table class="table table-bordered table-hover" style="margin: 10px 0px;border: 2px solid #bccdd8;background: #FFF;">
    <tr>
        <th></th>
        <th>Curso virtual</th>
        <th>Acceso</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT oc.*,(r.fecha_final)dr_fecha_final FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON oc.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND r.estado='1' ");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    $contenido_textarea = '';
    $contenido_whatsapp = '';
    $contenido_div_copy = '';
    $sw_asignacion = false;
    $ids_acv = '0';

    while ($curso = fetch($rqccg1)) {
        
        $hash_iduser = $id_usuario_participante . substr(md5('rtc' . $id_usuario_participante . '-754'), 19, 3);
        
        /* curso virtual */
        $id_onlinecourse = $curso['id'];
        $imagen_curso_virtual = $curso['imagen'];
        $nombre_curso_virtual = $curso['titulo'];
        $urltag_curso_virtual = $curso['urltag'];
        $url_ingreso_cv = $dominio_plataforma.'ingreso/' . $urltag_curso_virtual . '/'.$hash_iduser.'.html';
        $fecha_final_curso_virtual = $curso['dr_fecha_final'];

        /* acceso */
        $sw_acceso = false;
        $rqaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario_participante' AND sw_acceso='1' ");
        if (num_rows($rqaccv1) > 0) {
            $sw_acceso = true;
            $sw_asignacion = true;
        }
        
        $txt_mensajecursopregrabado = ('El curso está activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.date("d/m/Y",strtotime($fecha_final_curso_virtual)).' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.');

        if ($sw_acceso) {
            $contenido_textarea .= '
*' . $nombre_curso_virtual . '*
*LINK DE INGRESO:*
' . $url_ingreso_cv . '
*USUARIO:* ' . $user_usuario . '
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '

';
            $contenido_div_copy .= '
<br>
*' . $nombre_curso_virtual . '*
<br>
*LINK DE INGRESO:*
<br>
' . $url_ingreso_cv . '
<br>
*USUARIO:*  ' . $user_usuario . '
<br>
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '
<br>
<br>
';

            $contenido_whatsapp .= ' __-------------------------------__*' . ($nombre_curso_virtual) . '*__ __*Link de ingreso:*__' . $url_ingreso_cv . '__ __*Usuario:* ' . $user_usuario . '__*Contraseña:*  ' . $password_usuario . '__';
        }
        $ids_acv .= ','.$id_onlinecourse;
        ?>
        <tr>
            <td>
                <input type="checkbox" name="check-cv-<?php echo $id_onlinecourse; ?>" <?php echo ($sw_acceso)?"disabled":'checked=""'?> style="width: 20px;height: 20px;"/>
            </td>
            <td>
                <span style="font-size: 14pt;"><?php echo $nombre_curso_virtual; ?></span>
                <br>
                <img src="<?php echo $dominio_www; ?>contenido/imagenes/cursos/<?php echo $imagen_curso_virtual; ?>" style="height:70px;"/>
                <?php
                if ($sw_acceso) {
                    ?>
                    <br>
                    <br>
                    <b data-toggle="modal" data-target="#MODAL-avance-cvirtual" class="btn btn-info btn-xs" onclick="avance_cvirtual_especifico('<?php echo $id_participante; ?>', '<?php echo $id_onlinecourse; ?>');">PANEL C-vir</b>
                    <?php
                }
                ?>
            </td>
            <td id="ajaxloading-acceso_cvirtual-p<?php echo $id_onlinecourse.'-'.$id_participante; ?>">
                <?php
                if ($sw_acceso) {
                    ?>
                    <div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
                    <?php if ($sw_habilitacion_de_procesos) { ?>
                        <div style="padding: 5px;text-align: center;border: 1px solid #EEE;background: #FFF;">
                            Des-habilitar: <b class="btn btn-danger btn-xs" onclick="elimina_participante_cvirtual_multiple('<?php echo $id_participante; ?>', '<?php echo $id_onlinecourse; ?>');">X</b>
                        </div>
                    <?php } ?>
                    <?php
                } else {
                    ?>
                    <div style="color:#FFF;background: #ef8a80;padding: 7px;text-align: center;border: 1px solid #e74c3c;">NO HABILITADO</div>
                    <?php if ($sw_habilitacion_de_procesos) { ?>
                        <div style="padding: 5px;text-align: center;border: 1px solid #EEE;background: #FFF;">
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
    if($id_modalidad_curso=='2'){
        $contenido_whatsapp = '__'.$txt_mensajecursopregrabado.'__'.$contenido_whatsapp;
        $contenido_div_copy = $txt_mensajecursopregrabado.'<br><br>'.$contenido_div_copy;
        $contenido_textarea = ($txt_mensajecursopregrabado).'

'.$contenido_textarea;
    }
    if($sw_ipelc=='1'){
        $txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, Podrá dar Examen en Linea, Podrá enviar los documentos para la certificación de la IPELC, Podrá hacer seguimiento a la certificación IPELC.';
        $contenido_whatsapp = '__'.$txt_mensajecursoipelc.'__'.$contenido_whatsapp;
        $contenido_div_copy = $txt_mensajecursoipelc.'<br><br>'.$contenido_div_copy;
        $contenido_textarea = ($txt_mensajecursoipelc).'

'.$contenido_textarea;
    }
    ?>
</table>
    <input type="hidden" name="ids_acv" value="<?php echo $ids_acv; ?>"/>
    <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>"/>
</form>

<hr>

<?php if ($sw_asignacion) { ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">ACCESO A CURSOS POR WHATSAPP</div>
                <div class="panel-body">
                    <?php
                    if (strlen(trim($celular_participante)) == 8) {
                        $txt_whatsapp = 'Hola ' . ($nombres_participante . ' ' . $apellidos_participante) . '__te hacemos el envío de los datos de acceso a sus cursos virtuales:__ ' . $contenido_whatsapp;
                        $txt_whatsapp .= '__ __-------------------------------------------------------------__Ayúdanos a superar los 100 mil likes en nuestra página en facebook__https://www.facebook.com/cursoswebbolivia__ __Únete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia';
                        $txt_whatsapp = (str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                        ?>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo $codigo_pais.trim($celular_participante); ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
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
            url: 'pages/ajax/ajax.cursos-participantes.cvirtual_enviar_correo_accesos.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
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
            url: 'pages/ajax/ajax.cursos-participantes.avance_cvirtual.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-avance_cvirtual").html("");
                $("#ajaxbox-avance_cvirtual").html(data);
            }
        });
    }
</script>

<script>
    function habilita_participante_cvirtual_multiple(id_participante, id_onlinecourse) {
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_multiple.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html("<b>PROCESADO</b>");
                $("#TD-cvir-"+id_participante).html(data);
                acceso_cursos_virtuales(id_participante);
            }
        });
    }
    function elimina_participante_cvirtual_multiple(id_participante, id_onlinecourse) {
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_multiple.php',
            data: {id_participante: id_participante, id_onlinecourse: id_onlinecourse},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_onlinecourse+"-"+id_participante).html(data);
                acceso_cursos_virtuales(id_participante);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<script>
    $('#FORM-acv').on('submit', function(e) {
        e.preventDefault();
        $("#AJAXCONTENT-acceso_cursos_virtuales").html('<h2>Procesando...</h2>');
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_multiple.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#TD-cvir-<?php echo $id_participante; ?>").html(data);
                acceso_cursos_virtuales(<?php echo $id_participante; ?>);
            }
        });
    });
</script>

