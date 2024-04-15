<?php
/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* data */
$ids = post('ids') == '' ? '0' : post('ids');


if (isset_post('titulo_msj')) {
    $ids_participantes = post('ids_participantes');
    $titulo_msj = utf8_decode(str_replace('–','-',post('titulo_msj')));
    $contenido_msj = utf8_decode(str_replace('\r\n','<br>',str_replace('–','-',post('contenido_msj'))));
    $id_administrador = administrador('id');
    
    

    //$rqddu1 = query("SELECT * FROM cursos_usuarios WHERE id IN (SELECT id_usuario FROM cursos_participantes WHERE id IN ($ids_participantes))");
    $rqddu1 = query("SELECT * FROM cursos_participantes WHERE id IN ($ids_participantes) ");
    $sw_env_adm = true;
    while ($rqddu2 = fetch($rqddu1)) {
        
        $id_usuario = $rqddu2['id_usuario'];
        $nombre_usuario = $rqddu2['nombres'].' '.$rqddu2['apellidos'];

        if($id_usuario != '0'){
            query("INSERT INTO cursos_mensaje_usuarios (id_usuario,titulo,contenido,fecha,id_administrador,estado) VALUES ('$id_usuario','$titulo_msj','$contenido_msj',NOW(),'$id_administrador','1') ");
        }

        /* content text */
        $texto_principal = '<p><span><strong>' . ($titulo_msj) . '</strong></span></p>
    <p><span><br>Estimad@ ' . trim($rqddu2['nombres'] . ' ' . $rqddu2['apellidos']) . '<br>
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
        $contenido_correo = platillaEmailUno(utf8_encode($texto_principal), "Mensaje de la plataforma - " . $___nombre_del_sitio, $rqddu2['correo'], urlUnsubscribe($rqddu2['correo']), $nombre_usuario);
        $asunto = "Mensaje nuevo - " . utf8_encode($titulo_msj);
        
        if($sw_env_adm){
            $sw_env_adm = false;
            SISTsendEmail("desteco@gmail.com", $asunto.' (copia para administrador)', $contenido_correo);
        }

        /* envio de correo */
        SISTsendEmail($rqddu2['correo'], $asunto, $contenido_correo);        
        
        if($id_usuario != '0'){
            logcursos('Envio de mensaje', 'usuario-mensaje', 'usuario', $id_usuario);
        }

        echo '<div class="alert alert-success">
        <strong>EXITO</strong> el mensaje se envio correctamente a '.$rqddu2['correo'].'.
    </div>';
    }
}
?>
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
                <input type="hidden" value="<?php echo $ids; ?>" name="ids_participantes"/>
                <input type="submit" class="btn btn-block btn-warning" value="ENVIAR" name="enviar-mensaje"/>
            </td>
        </tr>
    </table>
</form>

<hr>
<table class="table table-striped table-bordered">
    <tr>
        <th>PARTICIPANTE</th>
        <th>CORREO</th>
    </tr>
    <?php
    //$rqdl1 = query("SELECT p.nombres,p.apellidos,u.email FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.id_usuario<>'0' AND p.id IN ($ids) ORDER BY p.id DESC ");
    $rqdl1 = query("SELECT p.nombres,p.apellidos,p.correo FROM cursos_participantes p WHERE p.id IN ($ids) ORDER BY p.id DESC ");
    while ($rqdl2 = fetch($rqdl1)) {
        ?>
        <tr>
            <td><?php echo $rqdl2['nombres'] . ' ' . $rqdl2['apellidos']; ?></td>
            <td><?php echo $rqdl2['correo']; ?></td>
        </tr>
        <?php
    }
    ?>
</table>

<script>
    $('#FORM-enviar_mensaje_usuario').on('submit', function (e) {
        e.preventDefault();
        $("#AJAXCONTENT-mensaje_usuarios_multiple").html('Procesando...');
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.mensaje_usuarios_multiple.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#AJAXCONTENT-mensaje_usuarios_multiple").html(data);
            }
        });
    });
</script>
