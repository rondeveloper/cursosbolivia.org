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

if(isset_post('sw_habilitar')){
    /* habilitacion de segundo turno */
    query("INSERT INTO segundos_turnos(
        id_usuario, 
        id_curso, 
        estado
        ) VALUES (
        '$id_usuario',
        '$id_curso',
        '1'
        )");

    logcursos('Habilitacion de examen de segundo turno', 'usuario-examen-2t', 'usuario', $id_usuario);

    /* usuario */
    $rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC ");
    $rqdu2 = fetch($rqdu1);

    /* curso */
    $rqdcr1= query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC ");
    $rqdcr2 = fetch($rqdcr1);
    $titulo_curso = $rqdcr2['titulo'];

    /* content text */
    $texto_principal = '
    <p>
    Estimad@ ' . trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']) . '
    <br>
    <br>
    Se ha realizado la habilitaci&oacute;n del examen de segundo turno para el curso de: '.$titulo_curso.', en el cual usted est&aacute; inscrito.
    </p>
    <p>
    Para completar el proceso de evaluaci&oacute;n mediante esta modalidad, es necesario seguir los siguientes pasos:
    </p>
    <br>
    <b>PROCEDIMIENTO PARA REALIZAR EL EXAMEN DE SEGUNDO TURNO</b>
    <br>
    <br>
    <table style="width: 100%;">
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Debe realizar un pago de <b>50</b> bs. a cualquiera de las siguientes cuentas: <a href="'.$dominio.'formas-de-pago.html">FORMAS DE PAGO</a></td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Debe realizar un <b>video</b> realizando una presentaci&oacute;n personal y exponiendo alg&uacute;n tema que a usted le parezca conveniente</td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">El video debe ser de minimamente 5 minutos, donde aparezca usted de frente, hablando en el idioma del curso realizado</td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Debe ingresar a su cuenta de usuario en: <a href="'.$dominio_plataforma.'">'.$dominio_plataforma.'</a></td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Sus datos de usuario son los siguientes:<br>Usuario: '.$rqdu2['email'].'<br>Contrase&ntilde;a: '.$rqdu2['password'].'</td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">En las opciones del men&uacute; aparecera una opcion <b>EXAMEN DE 2DO TURNO</b></td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Debe subir el video y el comprobante de pago en los formularios mostrados</td>
    </tr>
    <tr>
    <td style="padding:15px 20px;border: 1px solid #c5c5c5;">Posterior a eso nuestros docentes evaluar&aacute;n su presentaci&oacute;n y asignar&aacute;n una ponderaci&oacute;n en base al contenido del video.</td>
    </tr>
    </table>
    <br>
    <br>

    <p>Agradecemos su atenci&oacute;n<br>Saludos cordiales</p>
    <br/>
                                        ';
    /* cont correo */
    $enviarAEmail = $rqdu2['email'];
    $tituloEmail ='EXAMEN DE SEGUNDO TURNO';
    $contenido_correo =platillaEmailUno($texto_principal,$tituloEmail,$enviarAEmail,urlUnsubscribe($enviarAEmail),trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']));

    /* datos de correo */
    $asunto = "EXAMEN DE SEGUNDO TURNO";

    SISTsendEmail($enviarAEmail, $asunto, $contenido_correo);
    logcursos('Envio de mensaje', 'usuario-mensaje', 'usuario', $id_usuario);
    
    echo '<div class="alert alert-success">
        <strong>EXITO</strong>
        <br>
        La habilitaci&oacute;n de completo correctamente y se envi&oacute; un correo con intrucciones al participante.
    </div>';
}
?>

<h4 style="text-align: center;background: #f7f7f7;padding: 10px 0px;border: 1px solid #85d3ec;">
    EXAMEN DE SEGUNDO TURNO
</h4>

<?php
$rqdvest1 = query("SELECT * FROM segundos_turnos WHERE id_usuario='$id_usuario' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if(num_rows($rqdvest1)==0){
    ?>
    <div class="alert alert-warning">
        <strong>Sin habilitaci&oacute;n</strong>
        <br>
        El examen desegundo turno no se habilit&oacute; para este usuario.
    </div>
    <table class="table table-striped table-bordered">
        <tr>
            <td class="text-center">&iquest; Desea habilitar el examen ?</td>
        </tr>
        <tr>
            <td class="text-center" style="padding:25px;">
                <b onclick="habilitar_segundo_turno();" class="btn btn-block btn-success">HABILITAR</b>
            </td>
        </tr>
    </table>
    <?php
}else{
    $rqdvest2 = fetch($rqdvest1);
    $video = $rqdvest2['video'];
    $imagen_pago = $rqdvest2['imagen_pago'];
    ?>
    <table class="table table-striped table-bordered">
        <tr>
            <td>ESTADO</td>
            <td><i class="label label-success">HABILITADO</label></td>
        </tr>
        <tr>
            <td>DEPOSITO</td>
            <td>
                <?php if ($imagen_pago=='') { ?>
                    No se subio el comprobante
                <?php } else { ?>
                    <i class="label label-success">ENVIADO</i> 
                    &nbsp; 
                    <a href="<?php echo $dominio_www; ?>contenido/archivos/examen-2t/<?php echo $imagen_pago; ?>" target="_blank">[ Ver archivo ]</a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td>VIDEO</td>
            <td>
                <?php if ($video=='') { ?>
                    No se subio el video
                <?php } else { ?>
                    <i class="label label-success">ENVIADO</i> 
                    &nbsp; 
                    <a href="<?php echo $dominio_www; ?>contenido/archivos/examen-2t/<?php echo $video; ?>" target="_blank">[ Ver archivo ]</a>
                <?php } ?>
            </td>
        </tr>
    </table>
    <?php
}
?>

<!-- habilitar_segundo_turno -->
<script>
    function habilitar_segundo_turno() {
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.segundo_turno.php',
            data: { id_usuario: '<?php echo $id_usuario; ?>', id_curso: '<?php echo $id_curso; ?>', sw_habilitar: 1 },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    }
</script>
