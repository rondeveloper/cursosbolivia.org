<?php
/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$dat = post('dat');

$ids_participantes = $dat;

$rqdp1 = query("SELECT id,nombres,apellidos,correo FROM cursos_participantes WHERE id IN ($ids_participantes) ");
?>
<table class="table table-bordered table-striped">
    <?php
    while ($rqdp2 = fetch($rqdp1)) {
    ?>
        <tr>
            <td><?php echo $rqdp2['nombres'] . ' ' . $rqdp2['apellidos']; ?></td>
            <td><?php echo $rqdp2['correo']; ?></td>
            <?php
            if (isset_post('enviar_correo_confirm')) {

                $enlace_confirmacion = $dominio.'verificacion-ciudad/'.$rqdp2['id'].'/'.md5($rqdp2['id'].'54215').'.html';
                $asunto = "Confirmación de ciudad para envio de certificado IPELC";
                $texto = "Hola ".$rqdp2['nombres'] . ' ' . $rqdp2['apellidos']." necesitamos confirmar la ciudad donde se encuentra para poder mandar los certificados f&iacute;sicos IPELC.
                <br><br>
                Ingrese a esta direcci&oacute;n URL para confirmar la ciudad donde le madaremos.
                <br>
                Clic Aqui >>>>>>>> <a href='$enlace_confirmacion'>CONFIRMAR CIUDAD</a>
                <br><br>
                Muchas Gracias.
                <br><br>
                <br>
                Oficina CENTRAL LA PAZ: Av camacho Edif. Saenz N° 1377 Piso 3 Of. 301 esq. Loayza (Horario de 09:00 a 13:00 / 14:30 a 18:30 de Lunes a Viernes  S&aacute;bados de 09:00 a 13:00) Movil 62360096
                <br><br>
                Oficina ORURO: Calle Potosi 1564 entre Bolívar y Adolfo Mier Edificio SIRIUS 1er Piso of 4 (Horario de 08:00 a 12:00 / 14:30 a 18:30 de Lunes a Viernes  S&aacute;bados de 09:00 a 13:00) M&oacute;vil 77000691
                <br><br>
                Oficina Cochabamba: Av. Ayacucho Nro 250 Edificio El Clan Mezzanine Of 204 Entre Gral Acha y Santivañez Frente a la CNS (Horario de 09:00 a 13:00 / 13:30 a 17:30 de Lunes a Viernes  S&aacute;bados de 09:00 a 13:00) M&oacute;vil 62360040
                <br><br>
                NEMABOL - CURSOS.BO - INTFOSIN -KM";
                $cont_correo = platillaEmailUno($texto,'CONFIRMACI&Oacute;N DE CIUDAD',$rqdp2['correo'],urlUnsubscribe($rqdp2['correo']),$rqdp2['nombres'] . ' ' . $rqdp2['apellidos']);

                SISTsendEmail($rqdp2['correo'], $asunto, $cont_correo);
            ?>
                <td><i class="label label-success">ENVIADO</i></td>
            <?php
            }
            ?>
        </tr>
    <?php
    }
    ?>
    <?php
    if (isset_post('enviar_correo_confirm')) {
    ?>
        <tr>
            <td colspan="2">
                <i class="label label-success">CORREOS ENVIADOS</i>
            </td>
        </tr>
    <?php
    } else {
    ?>
        <tr>
            <td colspan="2">
                <b> DESEA ENVIAR CORREO DE VERIFICACI&Oacute;N A ESTOS CORREOS ?</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b class="btn btn-success" onclick="enviar_correo_confirm();">ENVIAR</b>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<script>
    function enviar_correo_confirm() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.confirmacion_correo_departamento.php',
            data: {
                dat: ids.join(','),
                enviar_correo_confirm: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>