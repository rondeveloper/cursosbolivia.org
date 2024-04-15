<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_usuario = post('id_usuario');
$id_curso = post('id_curso');

/* usuario */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC ");
$rqdu2 = fetch($rqdu1);

/* curso */
$rqcr1 = query("SELECT sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC ");
$rqcr2 = fetch($rqcr1);
$sw_ipelc = $rqcr2['sw_ipelc'];

if(isset_post('titulo_msj')){
    $titulo_msj = post('titulo_msj');
    $contenido_msj = str_replace('\r\n','<br>',post('contenido_msj'));
    $id_administrador = administrador('id');
    
    query("INSERT INTO cursos_mensaje_usuarios (id_usuario,titulo,contenido,fecha,id_administrador,estado) VALUES ('$id_usuario','$titulo_msj','$contenido_msj',NOW(),'$id_administrador','1') ");
    
    /* content text */
    $texto_principal = '<p><span><strong>'.($titulo_msj).'</strong></span></p>
    <p><span><br>Estimad@ ' . trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']) . '<br>
    <br>Usted tiene un nuevo mensaje de nuestra plataforma</span></p>
    <br/>
    <br/>
    <b>CONTENIDO DEL MENSAJE</b>
    <br/>
    <table>
    <tr>
    <td style="padding:15px 20px;border: 1px solid gray;">T&iacute;tulo:</td>
    <td style="padding:15px 20px;border: 1px solid gray;">' . ($titulo_msj) . '</td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid gray;">Mensaje:</td>
    <td style="padding:15px 20px;border: 1px solid gray;">' . ($contenido_msj) . '</td>
    </tr>
    </table>
    <br>
    <p>Agradecemos tu atenci&oacute;n.<br>Saludos cordiales</p>
    <br/>';


    /* datos de correo */
    $contenido_correo = platillaEmailUno($texto_principal, "Mensaje de la plataforma - " . $___nombre_del_sitio, $rqdu2['email'], urlUnsubscribe($rqdu2['email']), trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']));
    $asunto = "Mensaje nuevo - ".$titulo_msj;

    SISTsendEmail($rqdu2['email'], $asunto, $contenido_correo);
    logcursos('Envio de mensaje', 'usuario-mensaje', 'usuario', $id_usuario);
    
    echo '<div class="alert alert-success">
        <strong>EXITO</strong> el mensaje se envio correctamente.
    </div>';
}

?>
<h3 class="text-center">
    <?php echo $rqdu2['nombres'] . ' ' . $rqdu2['apellidos']; ?>
</h3>
<hr>
<table class="table table-striped table-bordered">
    <tr>
        <td><b>ID:</b></td>
        <td><?php echo $rqdu2['id']; ?></td>
    </tr>
    <tr>
        <td><b>Nombres:</b></td>
        <td><?php echo $rqdu2['nombres']; ?></td>
    </tr>
    <tr>
        <td><b>Apellidos:</b></td>
        <td><?php echo $rqdu2['apellidos']; ?></td>
    </tr>
    <tr>
        <td><b>C.I.:</b></td>
        <td><?php echo $rqdu2['ci']; ?></td>
    </tr>
    <tr>
        <td><b>Celular:</b></td>
        <td><?php echo $rqdu2['celular']; ?></td>
    </tr>
    <tr>
        <td><b>Email:</b>(Nick Usuario)</td>
        <td><?php echo $rqdu2['email']; ?></td>
    </tr>
    <tr>
        <td><b>Contrase&ntilde;a:</b></td>
        <td><?php echo $rqdu2['password']; ?></td>
    </tr>
</table>
<hr>

<div id="CONTENT-paneles">
    <h4 style="text-align: center;background: #f7f7f7;padding: 10px 0px;border: 1px solid #85d3ec;">
        PROCESOS PARTICULARES DE USUARIO
    </h4>
    <br>
    <table class="table table-striped table-bordered">
        <tr>
            <td>Mensajes de correo</td>
            <td><b class="btn btn-block btn-warning" onclick="mensajes();">MENSAJES</b></td>
        </tr>
        <tr>
            <td>Examen de segundo turno</td>
            <td><b class="btn btn-block btn-warning" onclick="segundo_turno();">EXAMEN 2DO TURNO</b></td>
        </tr>
        <?php if($sw_ipelc=='1'){ ?>
        <tr>
            <td>Documentos IPELC</td>
            <td><b class="btn btn-block btn-warning" onclick="docs_ipelc();">DOCS IPELC</b></td>
        </tr>
        <?php } ?>
    </table>
</div>

<!-- show_panel_mensajes -->
<script>
    function mensajes() {
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.mensajes.php',
            data: { id_usuario: '<?php echo $id_usuario; ?>' },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    }
</script>

<!-- segundo_turno -->
<script>
    function segundo_turno() {
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.segundo_turno.php',
            data: { id_usuario: '<?php echo $id_usuario; ?>', id_curso: '<?php echo $id_curso; ?>' },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    }
</script>


<!-- docs_ipelc -->
<script>
    function docs_ipelc() {
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.docs_ipelc.php',
            data: { id_usuario: '<?php echo $id_usuario; ?>', id_curso: '<?php echo $id_curso; ?>' },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    }
</script>
