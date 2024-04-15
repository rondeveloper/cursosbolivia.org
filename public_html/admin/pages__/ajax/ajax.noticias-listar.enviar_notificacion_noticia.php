<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

header('Access-Control-Allow-Origin: '.$dominio);

/*
  if (!isset_administrador() && !isset_organizador()) {
  echo "DENEGADO";
  exit;
  }
 */

/* data */
$id_noticia = post('id_noticia');
$modo_envio = get('modenv');
$title_push = strip_tags(urldecode(get('title')));
$bloque_envio = abs((int) get('bloque'));
$id_admd = (int) base64_decode(get('ahdmd'));

/* registro */
$rqdc1 = query("SELECT * FROM publicaciones WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
$noticia = fetch($rqdc1);

$noticia_id_ciudad = (int) $noticia['id_ciudad'];
$id_categoria = (int) $noticia['id_categoria'];

$titulo_identificador_noticia = $noticia['titulo_identificador'];
$data_nombre_noticia = $noticia['titulo'];
$fecha_noticia = fecha_noticia($noticia['fecha']);
$contenido_noticia = $noticia['contenido'];

/* id departamento */
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$noticia_id_ciudad' LIMIT 1 ");
$rqdd2 = fetch($rqdd1);
$id_departamento = $rqdd2['id_departamento'];

/* bloque */
$qr_bloque = '';
if ($bloque_envio !== 0) {
    $bloque_envio = abs($bloque_envio - 1);
    $qr_bloque = " AND MOD(id,6)=$bloque_envio ";
}
?>

<?php

/* HABILITADOS */

/* etiquetas seleccionadas */
$ids_etiquetas = '0';
$rqdctob1 = query("SELECT id FROM cursos_tags WHERE id_categoria='$id_categoria' ");
while ($rqdctob2 = fetch($rqdctob1)) {
    $id_tag = $rqdctob2['id'];
    if (isset_post('tag' . $id_tag)) {
        $ids_etiquetas .= ',' . $id_tag;
    }
}

/* imagen */
$url_img_noticia = $dominio_www . "contenido/imagenes/noticias/" . $noticia['imagen'] . "";

/* cont correo a enviar */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>".($data_nombre_noticia)."</h2>";
$contenido_correo .= "<center><a href='".$dominio."$titulo_identificador_noticia.html'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='".$dominio_www."contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $contenido_noticia;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

$asunto = "$data_nombre_noticia";

/* modo de envio */
switch ($modo_envio) {
    case 'usuariosreg':
        /* total_usuarios_registrados */
        $rqtur1 = query("SELECT DISTINCT email FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia') AND id_ciudad IN (0,$noticia_id_ciudad) $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = fetch($rqtur1)) {
            $correo = $rqturap2['email'];
            $enlace_dar_baja_notificaciones = '<a href="'.$dominio.'unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifnotiemail WHERE email='$correo' AND id_noticia='$id_noticia' ");
            $rqder2 = fetch($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifnotiemail (id_noticia,email) VALUES ('$id_noticia','$correo') ");
                enviar_email($correo, $asunto, $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);
                }
            }
        }
        logcursos_AUX('Notificacion de curso [correo][usuarios registrados][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_noticia);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'anterpart':
        /* anteriores participantes */
        $rqturap1 = query("SELECT DISTINCT correo FROM cursos_participantes WHERE sw_notif=1 AND correo LIKE '%@%' AND id_curso IN ("
                . "select id from cursos where id_ciudad='$noticia_id_ciudad' and id in (select id_curso from cursos_rel_cursostags where id_tag in ($ids_etiquetas) ) "
                . ") AND correo NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia' )  $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = fetch($rqturap1)) {
            $correo = $rqturap2['correo'];
            $enlace_dar_baja_notificaciones = '<a href="'.$dominio.'unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifnotiemail WHERE email='$correo' AND id_noticia='$id_noticia' ");
            $rqder2 = fetch($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifnotiemail (id_noticia,email) VALUES ('$id_noticia','$correo') ");
                enviar_email($correo, $asunto, $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);
                }
            }
        }
        logcursos_AUX('Notificacion de curso [correo][participantes anteriores][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_noticia);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'appmovil':
        echo "<b class='btn btn-default'>Sin usuarios</b>";
        break;
    case 'pushnav':
        /* $total_suscripcion_navegador */
        $arrNotificationMessage = array(
            'title' => $title_push,
            'text' => $title_push . ', ' . $fecha_noticia,
            'sound' => "mySound",
            'image' => $url_img_noticia,
            'icon' => $url_img_noticia,
            'click_action' => $dominio."$titulo_identificador_noticia.html",
            'url' => $dominio."$titulo_identificador_noticia.html",
            "vibrate" => [200, 100, 200, 100, 200, 100, 400],
            'priority' => "high"
        );
        //*$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' AND id_departamento IN ('0','$id_departamento') AND id NOT IN (select id_tokensusc from cursos_rel_notifsuscpush where id_noticia='$id_noticia' and fecha_envio=CURDATE() ) $qr_bloque ORDER BY id DESC ");
        $rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' AND id_departamento IN ('0','$id_departamento') $qr_bloque ORDER BY id ASC ");
        //$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE id='11160' ORDER BY id DESC ");
        //echo "TOTAL: ".num_rows($rqdn1);exit;
        $cnt_env = 0;
        $fecha_envio = date("Y-m-d");
        while ($rqdn2 = fetch($rqdn1)) {

            $extraData = array(
                'any_extra_data' => "any data"
            );
            $deviceToken = $rqdn2['token'];
            $id_tokensusc = $rqdn2['id'];
            $ch = curl_init("https://fcm.googleapis.com/fcm/send");
            $header = array('Content-Type: application/json',
                "Authorization: key=AAAA9h2b9-w:APA91bEmp3dln24XQP9Ck6G920eL0_CDh66E9rlcFz3gsP0TX0myFQt-6gkeXas15dAa7YOzDlBz0XmferenRRajAjJkvPy8oTvKV3uvcWsNZ7z9sOaK1e0N55wWwYB7pUNkWy_0V0-x");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"to\" : " . json_encode($deviceToken) . "}");

            $result = curl_exec($ch);
            curl_close($ch);
            
            /*
            echo "<hr/>-->";
            echo print_r($result,true);
            echo "<--<hr/>";
            exit;
            */

            /* datos de recepcion */
            $result_json = json_decode($result, true);
            $multicast_id = $result_json['multicast_id'];
            $success = $result_json['success'];
            $failure = $result_json['failure'];
            $canonical_ids = $result_json['canonical_ids'];
            $results = $result_json['results'];
            $results_error = $result_json['results'][0]['error'];
            $results_message_id = $result_json['results'][0]['message_id'];
            
            $data_mcast = print_r($result,true);
            
            echo $data_mcast;

            /* registro de envio */
            query("INSERT INTO cursos_rel_notifnotisuscpush (id_noticia,id_tokensusc,fecha_envio,data) VALUES ('$id_noticia','$id_tokensusc','$fecha_envio','$data_mcast') ");

            /* tratamiento de recepcion */
            if ($failure == '1') {
                /* no registrado */
                if ($results_error == 'NotRegistered') {
                    query("UPDATE cursos_suscnav SET estado='2' WHERE id='$id_tokensusc' ");
                    //echo "<br/>ESTADO ACTUALIZADO<br/>";
                }
            }

            //echo "<hr/>$result<hr/>";


            $cnt_env++;
        }
        logcursos_AUX('Notificacion de curso [notif push][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_noticia);
        echo "<b style='color:#FFF;font-size:14pt;' class='btn btn-primary active'>NOTIFICACIONES REALIZADAS: $cnt_env</b>";
        break;
    default:
        break;
}




/* FUNCIONES */

function fecha_noticia($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}

function logcursos_AUX($movimiento, $proceso, $objeto, $id_objeto) {
    global $id_admd;
    $id_usuario = $id_admd;
    $usuario = 'administrador';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_registro = $_SERVER['REMOTE_ADDR'];
    }
    $fecha = date("Y-m-d H:i:s", time());
    $r1 = query("INSERT INTO cursos_log (movimiento,proceso,objeto,id_objeto,usuario,id_usuario,ip,fecha) VALUES ('$movimiento','$proceso','$objeto','$id_objeto','$usuario','$id_usuario','$ip_registro','$fecha') ");
}

function enviar_email($correo_a_enviar, $asunto, $contenido_correo) {
    if (emailValido($correo_a_enviar)) {
        SISTsendEmailFULL($correo_a_enviar, ($asunto), $contenido_correo,'','');
    } else {
        echo "<span style='font-size:10pt;'>$correo_a_enviar NO VALIDO <i style='font-size:8pt;color:red;'>[DEPURADO]</i></span><br/>";
        query("UPDATE cursos_usuarios SET sw_notif=0 WHERE email='$correo_a_enviar' LIMIT 2 ");
        query("UPDATE cursos_participantes SET sw_notif=0 WHERE correo='$correo_a_enviar' LIMIT 2 ");
    }
}
