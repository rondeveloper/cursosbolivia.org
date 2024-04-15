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



if(isset_post('titulo_msj')){
    /* usuario */
    $rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC ");
    $rqdu2 = fetch($rqdu1);

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
    <table style="width: 100%;">
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
    <p>Agradecemos su atenci&oacute;n<br>Saludos cordiales</p>
    <br/>
                                        ';
    /* cont correo */
    $enviarAEmail = $rqdu2['email'];
    $tituloEmail ='Mensaje de la plataforma - '.$___nombre_del_sitio;
    $contenido_correo =platillaEmailUno($texto_principal,$tituloEmail,$enviarAEmail,urlUnsubscribe($enviarAEmail),trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']));

    /* datos de correo */
    $asunto = "Mensaje nuevo - ".$titulo_msj;

    SISTsendEmail($enviarAEmail, $asunto, $contenido_correo);
    logcursos('Envio de mensaje', 'usuario-mensaje', 'usuario', $id_usuario);
    
    echo '<div class="alert alert-success">
        <strong>EXITO</strong> el mensaje se envio correctamente.
    </div>';
}

?>

<table class="table table-striped table-bordered">
    <tr>
        <th>FECHA</th>
        <th>TITULO</th>
        <th>MENSAJE</th>
        <th>ADMINISTRADOR</th>
    </tr>
    <?php
    $rqdl1 = query("SELECT * FROM cursos_mensaje_usuarios WHERE id_usuario='$id_usuario' ORDER BY id DESC ");
    if(num_rows($rqdl1)==0){
        echo '<tr><td colspan="4">Sin mensajes...</td></tr>';
    }
    while ($rqdl2 = fetch($rqdl1)) {
        ?>
        <tr>
            <td>
                <?php
                echo date("d/M/y", strtotime($rqdl2['fecha']));
                echo "<br/>";
                echo date("H:i", strtotime($rqdl2['fecha']));
                ?>
            </td>
            <td><?php echo $rqdl2['titulo']; ?></td>
            <td><?php echo $rqdl2['contenido']; ?></td>
            <td>
                <?php
                if ($rqdl2['id_administrador'] == '0') {
                    echo "Sistema";
                } else {
                    $rqdad1 = query("SELECT nombre FROM administradores WHERE id='".$rqdl2['id_administrador']."' LIMIT 1 ");
                    $rqdad2 = fetch($rqdad1);
                    echo $rqdad2['nombre'];
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<br>
<hr>

<form id="FORM-enviar_mensaje_usuario" action="" method="post">
    <h4 class="text-center">ENVIO DE MENSAJE</h4>
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>T&iacute;tulo:</b></td>
            <td><input type="text" name="titulo_msj" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td><b>Contenido:</b></td>
            <td><textarea name="contenido_msj" required="" class="form-control" style="height: 70px;"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <input type="hidden" value="<?php echo $id_usuario; ?>" name="id_usuario"/>
                <input type="submit" class="btn btn-block btn-warning" value="ENVIAR" name="enviar-mensaje"/>
            </td>
        </tr>
    </table>
</form>


<script>
    $('#FORM-enviar_mensaje_usuario').on('submit', function(e) {
        e.preventDefault();
        $("#CONTENT-paneles").html('Procesando...');
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.mensajes.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    });
</script>



