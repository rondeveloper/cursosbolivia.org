<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_curso = post('id_curso');
$id_administrador = administrador('id');

/* accion alternativa */
$sw_copiar_y_no_remover = false;
if(isset_get('accion-alternativa') && get('accion-alternativa')=='copiar'){
    $sw_copiar_y_no_remover = true;
}

/* datos recibidos */
$ids_participantes_dat = post('dat');
if ($ids_participantes_dat == '') {
    $ids_participantes_dat = '0';
}

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
/* END limpia datos de id participante */


if (!acceso_cod('adm-traspart')) {
    echo "ACCESO DENEGADO";
    exit;
}



if (isset_post('sw_trasladar_part')) {
    /* STEP 2 */
    $id_curso_destino = post('id_curso_destino');
    $rqvc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso_destino' LIMIT 1 ");
    if(num_rows($rqvc1)==0){
        echo '<div class="alert alert-danger">
        <strong>ERROR</strong> el curso <b>' . $id_curso_destino . '</b> no existe.
      </div>';
        exit;
    }
    $ids_participantes = post('ids_participantes');
    $ar1 = explode(",", $ids_participantes_dat);
    $aux_nomparts = '';
    foreach ($ar1 as $id_participante) {
        if (isset_post('idpart-' . $id_participante)) {
            $rqdp1 = query("SELECT id,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
            $rqdp2 = fetch($rqdp1);
            $id_proceso_registro = $rqdp2['id'];
            $nombre_part = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
            $aux_nomparts .= $nombre_part.'<br>';

            if($sw_copiar_y_no_remover){
                copiar_participante($id_participante, $id_proceso_registro, $id_curso_destino);
                echo '<div class="alert alert-success">
                <strong>EXITO</strong> participante <b>' . $nombre_part . '</b> copiado correctamente.
              </div>
              ';
            } else {
                query("UPDATE cursos_proceso_registro SET id_curso='$id_curso_destino' WHERE id_curso='$id_curso' AND id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                query("UPDATE cursos_participantes SET id_curso='$id_curso_destino' WHERE id_curso='$id_curso' AND id='$id_participante' ORDER BY id DESC limit 1 ");
                logcursos('Participante trasladado ['.$id_curso.' -> '.$id_curso_destino.']', 'participante-edicion', 'participante', $id_participante);
                echo '<div class="alert alert-success">
                <strong>EXITO</strong> participante <b>' . $nombre_part . '</b> trasladado correctamente.
              </div>
              ';
            }
        }
    }
    /* administrador */
    $rqdad1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
    $rqdad2 = fetch($rqdad1);
    $nom_administrador = $rqdad2['nombre'];
    /* notificacion por correo */
    $subject = 'Traslado de participantes';
    $body = 'Se notifica que se hizo el traslado de los siguientes participantes.<br><br>Administrador: '.$nom_administrador.'<br>curso anterior: '.$id_curso.'<br>curso destino: '.$id_curso_destino.'<br><br>Particiapntes:<br>'.$aux_nomparts.'';
    $email = 'info@nemabol.com';
    $contenido_correo = platillaEmailUno($body,$subject,$email,urlUnsubscribe('noemail@correo.com'),'Usuario');
    SISTsendEmail($email, $subject, $contenido_correo);
} else {
    /* STEP 1 */
    $rqcp1 = query("SELECT p.* FROM cursos_participantes p WHERE p.estado='1' AND p.id IN ($ids_participantes) AND p.id_curso='$id_curso' ORDER BY p.id DESC ");
    if (num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron participantes.</p><br/><br/>";
    } else {
?>
        <div id="AJAXCONTENT-emite_certificados_multiple_p2">
            <form id="FORM-traspart" action='' method='post'>
                <div style="background: #efefef;padding: 25px;border-radius: 15px;line-height: 2.4;border: 1px solid #dedede;">
                    <?php if($sw_copiar_y_no_remover) : ?>
                        <b>ID del curso donde se copiaran los participantes:</b>
                    <?php else : ?>
                        <b>ID del curso destino del traslado:</b>
                    <?php endif; ?>
                    <br>
                    <input type="number" name="id_curso_destino" value="" required="" class="form-control" placeholder="ID del curso..." />
                </div>
                <hr>
                <table class="table table-striped table-bordered">
                    <?php
                    $ids_emisioncert = '0';
                    while ($rqcp2 = fetch($rqcp1)) {
                        $ids_emisioncert .= ',' . $rqcp2['id_emision_certificado'];
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;" />
                            </td>
                            <td>
                                <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                                <br>
                                <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <br />
                <p class='text-center'>
                    <?php if($sw_copiar_y_no_remover) : ?>
                        <b>&iquest; Desea copiar a estos participantes ?</b>
                    <?php else : ?>
                        <b>&iquest; Desea trasladar a estos participantes ?</b>
                    <?php endif; ?>
                </p>

                <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                <input type="hidden" name="dat" value="<?php echo $ids_participantes_dat; ?>" />
                <input type="hidden" name="sw_trasladar_part" value="1" />

                <div class="text-center">
                    <?php if($sw_copiar_y_no_remover) : ?>
                        <b class="btn btn-success" onclick="copiar_participantes_p2();">COPIAR PARTICIPANTES</b>
                    <?php else : ?>
                        <b class="btn btn-success" onclick="trasladar_participantes_p2();">TRASLADAR PARTICIPANTES</b>
                    <?php endif; ?>
                </div>
            </form>
        </div>
<?php
    }
}
?>

<script>
    function trasladar_participantes_p2() {
        if(confirm(' CONFIRMACION\n Desea proceder con el traslado ?\n \n IMPORTANTE: los participantes se ELIMINARAN de este curso.')){
            var form = $("#FORM-traspart").serialize();
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.trasladar_participantes.php',
                data: form,
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    }
</script>

<script>
    function copiar_participantes_p2() {
        if(confirm(' CONFIRMACION\n Desea proceder con el copiado ?')){
            var form = $("#FORM-traspart").serialize();
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.trasladar_participantes.php?accion-alternativa=copiar',
                data: form,
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    }
</script>


<?php

function copiar_participante($id_participante, $id_proceso_registro, $id_curso_destino) {
    /* obtencion de datos del participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdp2 = fetch($rqdp1);

    /* nuevo registro */
    $id_curso = $rqdp2['id_curso'];
    $nombres = addslashes($rqdp2['nombres']);
    $apellidos = addslashes($rqdp2['apellidos']);
    $prefijo = $rqdp2['prefijo'];
    $ci = $rqdp2['ci'];
    $ci_expedido = $rqdp2['ci_expedido'];
    $correo = $rqdp2['correo'];
    $celular = $rqdp2['celular'];
    $institucion = $rqdp2['institucion'];
    $tel_institucion = $rqdp2['tel_institucion'];
    $observacion = $rqdp2['observacion'].' Copiado del curso ID '.$id_curso;
    $id_turno = $rqdp2['id_turno'];
    $orden = $rqdp2['orden'];
    $sw_pago = 1;
    $id_modo_pago = '10';
    $id_usuario = $rqdp2['id_usuario'];
    $id_administrador = administrador('id');
    $cod_reg = substr("RC-$id_curso_destino-" . str_replace(" ", "-", $nombres), 0, 14);

    /* nuevo proceso registro */
    query("INSERT INTO cursos_proceso_registro(
        id_curso, 
        id_turno,
        id_administrador,
        cod_reg, 
        id_modo_pago, 
        sw_pago_enviado, 
        paydata_id_administrador, 
        paydata_fecha, 
        cnt_participantes, 
        razon_social, 
        nit, 
        monto_deposito, 
        fecha_registro, 
        estado
        ) VALUES (
        '$id_curso_destino',
        '$id_turno',
        '$id_administrador',
        '$cod_reg',
        '$id_modo_pago',
        '$sw_pago',
        '$id_administrador',
        NOW(),
        '1',
        '',
        '',
        '0',
        NOW(),
        '1'
        )");
    $id_proceso_registro = insert_id();
    

    /* creacion del nuevo participante */
    query("INSERT INTO cursos_participantes(
        id_curso, 
        id_proceso_registro, 
        nombres, 
        apellidos, 
        prefijo, 
        ci, 
        ci_expedido, 
        correo, 
        celular, 
        institucion, 
        tel_institucion, 
        observacion, 
        id_turno, 
        orden, 
        sw_pago, 
        id_modo_pago, 
        id_usuario, 
        estado
        ) VALUES (
        '$id_curso_destino',
        '$id_proceso_registro',
        '$nombres',
        '$apellidos',
        '$prefijo',
        '$ci',
        '$ci_expedido',
        '$correo',
        '$celular',
        '$institucion',
        '$tel_institucion',
        '$observacion',
        '$id_turno',
        '$orden',
        '$sw_pago',
        '$id_modo_pago',
        '$id_usuario',
        '1'
        )");
    $id_nuevo = insert_id();
    logcursos('Habilitacion de participante', 'partipante-deshabilitacion', 'participante', $id_nuevo);
    logcursos('Participante copiado a -> ID curso '.$id_curso_destino.' ('.$id_nuevo.')]', 'participante-edicion', 'participante', $id_participante);
}
