<?php
/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

//Load Composer's autoloader
require '../../librerias/phpmailer/vendor/autoload.php';

/*
  if (!isset_administrador() && !isset_organizador()) {
  echo "DENEGADO";
  exit;
  }
 */

/* data */
$id_departamento = post('id_departamento');
$modo_envio = get('modenv');
$title_push = strip_tags(urldecode(get('title')));
$bloque_envio = abs((int) get('bloque'));
$id_admd = (int) base64_decode(get('ahdmd'));

/* registro */
if($id_departamento=='10'){
    $titulo_identificador_cursosdep = 'cursos-virtuales';
    $data_nombre_mensaje = 'Cursos VIRTUALES programados para esta semana';
}else{
    $rqdc1 = query("SELECT * FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
    $depart = mysql_fetch_array($rqdc1);
    $titulo_identificador_cursosdep = 'cursos-en-'.$depart['titulo_identificador'];
    $data_nombre_mensaje = 'Cursos para esta semana en '.$depart['nombre'];
}
$contenido_mensaje = getContenidoCursosDepartamento($id_departamento);

/* bloque */
$qr_bloque = '';
if ($bloque_envio !== 0) {
    $bloque_envio = abs($bloque_envio - 1);
    $qr_bloque = " AND MOD(id,6)=$bloque_envio ";
}
?>

<?php
/* HABILITADOS */

/* imagen */
$rqdi1 = query("SELECT imagen FROM cursos WHERE estado='1' AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ORDER BY id DESC limit 1 ");
$rqdi2 = mysql_fetch_array($rqdi1);
$url_img_curso = 'https://cursos.bo/' . "contenido/imagenes/paginas/" . $rqdi2['imagen'] . "";
if (!file_exists("../../imagenes/paginas/" . $rqdi2['imagen'])) {
    $url_img_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $rqdi2['imagen'] . "";
}
if($id_departamento !== '10') {
    $url_img_curso = "https://cursos.bo/contenido/imagenes/images/curso-office.jpg";
}

/* cont correo a enviar */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>$data_nombre_mensaje</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo/$titulo_identificador_cursosdep.html'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $contenido_mensaje;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
$asunto = "$data_nombre_mensaje";

$cabeceras = 'From:' . 'CURSOS.BO <contacto@cursos.bo>' . "\r\n" .
        'Reply-To:' . 'contacto@cursos.bo' . "\r\n" .
        'X-Mailer: PHP/' . phpversion() .
        'Return-Path:' . 'contacto@cursos.bo' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$qr_ciudad_urd = "";
$qr_ciudad_prt = "";
$qr_ciudad_usrp = "";
if ($id_departamento !== '0' && $id_departamento !== '10') {
    $qr_ciudad_urd = " AND (id_ciudad='0' OR id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ) ";
    $qr_ciudad_prt = " AND id_curso IN ("
        . "select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') "
        . ") ";
    $qr_ciudad_usrp = " AND id_departamento IN ('0','$id_departamento') ";
}

/* modo de envio */
switch ($modo_envio) {
    case 'usuariosreg':
        /* total_usuarios_registrados */
        $rqtur1 = query("SELECT DISTINCT email FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE()) $qr_ciudad_urd $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqtur1)) {
            $correo = $rqturap2['email'];
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE email='$correo' AND id_departamento='$id_departamento' AND fecha=CURDATE() ");
            $rqder2 = mysql_fetch_array($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifdepemail (id_departamento,email,fecha) VALUES ('$id_departamento','$correo',CURDATE()) ");
                enviar_email_2($correo, $asunto, $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email_2("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);echo "YEP";exit;
                }
            }
        }
        logcursos_AUX('Notificacion de cursos departamento [correo][usuarios registrados][' . $cnt_env . ' envios]', 'notificacion-curso', 'departamento', $id_departamento);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'anterpart-pres':
        /* anteriores participantes */
        $rqturap1 = query("SELECT DISTINCT correo FROM cursos_participantes WHERE sw_notif=1 AND id_curso IN (select id from cursos where id_modalidad IN (1) ) AND correo LIKE '%@%' $qr_ciudad_prt AND correo NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE() ) $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqturap1)) {
            $correo = $rqturap2['correo'];
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE email='$correo' AND id_departamento='$id_departamento' AND fecha=CURDATE() ");
            $rqder2 = mysql_fetch_array($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifdepemail (id_departamento,email,fecha) VALUES ('$id_departamento','$correo',CURDATE()) ");
                //*echo $contenido_correo . $cont_unsubscribe;exit;
                enviar_email_2($correo, $asunto, $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email_2("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);echo "YEP";exit;
                }
            }
        }
        logcursos_AUX('Notificacion de cursos departamento [correo][participantes anteriores][' . $cnt_env . ' envios]', 'notificacion-curso', 'departamento', $id_departamento);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'anterpart-vir':
        /* anteriores participantes */
        $rqturap1 = query("SELECT DISTINCT correo FROM cursos_participantes WHERE sw_notif=1 AND id_curso IN (select id from cursos where id_modalidad IN (2,3) ) AND correo LIKE '%@%' $qr_ciudad_prt AND correo NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE() ) $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqturap1)) {
            $correo = $rqturap2['correo'];
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE email='$correo' AND id_departamento='$id_departamento' AND fecha=CURDATE() ");
            $rqder2 = mysql_fetch_array($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifdepemail (id_departamento,email,fecha) VALUES ('$id_departamento','$correo',CURDATE()) ");
                //*echo $contenido_correo . $cont_unsubscribe;exit;
                enviar_email_2($correo, $asunto, $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email_2("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);echo "YEP";exit;
                }
            }
        }
        logcursos_AUX('Notificacion de cursos departamento [correo][participantes anteriores][' . $cnt_env . ' envios]', 'notificacion-curso', 'departamento', $id_departamento);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'pushnav':
        /* $total_suscripcion_navegador */
        $arrNotificationMessage = array(
            'title' => $title_push,
            'text' => $title_push,
            'sound' => "mySound",
            'image' => $url_img_curso,
            'icon' => $url_img_curso,
            'click_action' => "https://cursos.bo/$titulo_identificador_cursosdep.html",
            'url' => "https://cursos.bo/$titulo_identificador_cursosdep.html",
            "vibrate" => [200, 100, 200, 100, 200, 100, 400],
            'priority' => "high"
        );
        $rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' $qr_ciudad_usrp AND id NOT IN (select id_tokensusc from cursos_rel_notifdeppush where id_departamento='$id_departamento' and fecha_envio=CURDATE() ) $qr_bloque ORDER BY id ASC ");
        //*$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' AND id_departamento IN ('0','$id_departamento') $qr_bloque ORDER BY id ASC ");
        //$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE token LIKE 'cUCmr0ZSdS4:APA91bElZh6ZZhQ7v1NuLK7Xz053VQj4kAIScV_Gzr54leYv4sXIDTtlS14bzLYUrfJ5_xdsUoQiClsl-oLE_rnKq2QnoUuuzUCQTAUT1u_-iGDJCVaMkHRNLCox6jOdyEpalra8nGKt' ORDER BY id DESC ");
        //echo "TOTAL: ".mysql_num_rows($rqdn1);exit;
        $cnt_env = 0;
        $cnt_ret = 0;
        $cnt_100lotes = 0;
        $registration_ids = array ();
        $ids_newnotifs = '0';
        $fecha_envio = date("Y-m-d");
        $extraData = array(
                'any_extra_data' => "any data"
            );
        $header = array('Content-Type: application/json',
                "Authorization: key=AAAA9h2b9-w:APA91bEmp3dln24XQP9Ck6G920eL0_CDh66E9rlcFz3gsP0TX0myFQt-6gkeXas15dAa7YOzDlBz0XmferenRRajAjJkvPy8oTvKV3uvcWsNZ7z9sOaK1e0N55wWwYB7pUNkWy_0V0-x");
        while ($rqdn2 = mysql_fetch_array($rqdn1)) {
            $deviceToken = $rqdn2['token'];
            $id_tokensusc = $rqdn2['id'];
            $cnt_100lotes++;
            array_push($registration_ids, $deviceToken);
            
            /* registro de envio */
            query("INSERT INTO cursos_rel_notifdeppush (id_departamento,id_tokensusc,fecha_envio) VALUES ('$id_departamento','$id_tokensusc','$fecha_envio') ");
            $id_newnotif = mysql_insert_id();
            $ids_newnotifs .= ','.$id_newnotif;
            
            if($cnt_100lotes>=90){
                $ch = curl_init("https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"registration_ids\" : " . json_encode($registration_ids) . "}");
                $result = curl_exec($ch);
                curl_close($ch);
                
                /* datos de recepcion */
                $result_json = json_decode($result, true);
                $multicast_id = $result_json['multicast_id'];
                $success = $result_json['success'];
                $failure = $result_json['failure'];
                $canonical_ids = $result_json['canonical_ids'];
                $results = $result_json['results'];
                $results_error = $result_json['results'][0]['error'];
                $results_message_id = $result_json['results'][0]['message_id'];
                $my_data_mcast = "$multicast_id | $canonical_ids";
                /* update data envio */
                if ((int)$failure > 0) {
                    $cnt_pos_fail = 0;
                    foreach ($results as $result){
                        if(isset($result['error'])){
                            if ($result['error'] == 'NotRegistered') {
                                query("UPDATE cursos_suscnav SET estado='2' WHERE token LIKE '".$registration_ids[$cnt_pos_fail]."' ");
                                $cnt_ret++;
                                $cnt_env--;
                            }
                        }
                        $cnt_pos_fail++;
                    }
                }
                query("UPDATE cursos_rel_notifdeppush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
                $cnt_100lotes = 0;
                $registration_ids = array ();
                $ids_newnotifs = '0';
            }
            $cnt_env++;
        }
        if ($cnt_100lotes > 0) {
            $ch = curl_init("https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"registration_ids\" : " . json_encode($registration_ids) . "}");
            $result = curl_exec($ch);
            curl_close($ch);
            /* datos de recepcion */
            $result_json = json_decode($result, true);
            $multicast_id = $result_json['multicast_id'];
            $success = $result_json['success'];
            $failure = $result_json['failure'];
            $canonical_ids = $result_json['canonical_ids'];
            $results = $result_json['results'];
            $results_error = $result_json['results'][0]['error'];
            $results_message_id = $result_json['results'][0]['message_id'];
            $my_data_mcast = "$multicast_id | $canonical_ids";
            /* update data envio */
            if ((int) $failure > 0) {
                $cnt_pos_fail = 0;
                foreach ($results as $result) {
                    if (isset($result['error'])) {
                        if ($result['error'] == 'NotRegistered') {
                            query("UPDATE cursos_suscnav SET estado='2' WHERE token LIKE '" . $registration_ids[$cnt_pos_fail] . "' ");
                            $cnt_ret++;
                            $cnt_env--;
                        }
                    }
                    $cnt_pos_fail++;
                }
            }
            query("UPDATE cursos_rel_notifdeppush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
        }
        logcursos_AUX('Notificacion de cursos departamento [notif push][' . $cnt_env . ' envios]', 'notificacion-curso', 'departamento', $id_departamento);
        echo '<div style="padding: 5px;">';
        echo '<b style="color:#FFF;font-size:14pt;background: #67a90f;padding: 5px;border-radius: 9px 0px 0px 9px;">Completado:</b>';
        echo '&nbsp;&nbsp;<span style="color:#FFF;font-size:12pt;margin: 5px;">'.$cnt_env.' enviados&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;'.$cnt_ret.' retirados</span>';
        echo '</div>';
        break;
    default:
        break;
}




/* FUNCIONES */

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
    if (isCorreoValido($correo_a_enviar)) {
        try {
            $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'cursos.bo';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'contacto@cursos.bo';                 // SMTP username
            $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('contacto@cursos.bo', 'CURSOS.BO');
            $mail->addAddress($correo_a_enviar);     // Add a recipient

            /* Content */
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = utf8_decode($asunto);
            $mail->Body = $contenido_correo;

            $mail->Send(); //Enviar
            //return true;
        } catch (phpmailerException $e) {
            echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
            //return false;
        }
    } else {
        echo "<span style='font-size:10pt;'>$correo_a_enviar no valido <i style='font-size:8pt;color:red;'>[DEPURADO]</i></span><br/>";
        query("UPDATE cursos_usuarios SET sw_notif=0 WHERE email='$correo_a_enviar' LIMIT 2 ");
        query("UPDATE cursos_participantes SET sw_notif=0 WHERE correo='$correo_a_enviar' LIMIT 2 ");
    }
}

/* enviar_email_2 */
function enviar_email_2($correo_a_enviar, $asunto, $contenido_correo) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "Reply-To: CURSOS.BO <info@nemabol.com>" . "\r\n";
    $headers .= "From: CURSOS.BO <sistema@cursos.bo>" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    mail($correo_a_enviar, $asunto, $contenido_correo, $headers);
}


function isCorreoValido($dat) {
    $array_correos_excepciones = array(
        'willans79@gmail.com',
        'victorvg@totalradios.com',
        'fmamani@dicsabol.com'
    );
    if (filter_var(trim($dat), FILTER_VALIDATE_EMAIL) || (in_array(trim($dat), $array_correos_excepciones))) {
        return true;
    } else {
        return false;
    }
}

function getContenidoCursosDepartamento($id_departamento) {
    $return_massaje = '';
    $return_massaje .= '
    <div style="font-family:arial;">
        <table style="width: 100%;">
    ';
    
    $qr_modalidad = " AND c.id_modalidad='1' ";
    $qr_departamento = " AND cd.id_departamento='$id_departamento' ";
    if($id_departamento=='10'){
        $qr_modalidad = " AND c.id_modalidad IN (2,3) ";
        $qr_departamento = "";
    }

    $data_required = "c.titulo,c.titulo_identificador,c.imagen,c.imagen_gif,(cd.nombre)ciudad,c.fecha,c.horarios,c.duracion,c.sw_fecha,c.id_modalidad,c.id_lugar,c.costo,c.fecha2,c.sw_fecha2,c.costo2,c.fecha3,c.sw_fecha3,c.costo3,c.sw_fecha_e,c.sw_fecha_e2,c.costo_e,c.costo_e2";
    $rc1 = query("SELECT $data_required FROM cursos c INNER JOIN ciudades cd ON c.id_ciudad=cd.id WHERE c.estado IN (1) $qr_modalidad $qr_departamento ORDER BY c.fecha DESC ");
    while ($curso = mysql_fetch_array($rc1)) {
        $titulo_de_curso = $curso['titulo'];
        $ciudad_curso = $curso['ciudad'];
        $fecha_curso = fecha_curso($curso['fecha']);
        if ($curso['id_modalidad'] == '2') {
            $fecha_curso = 'DISPONIBLE AHORA';
        }
        $horarios = $curso['horarios'];
        $duracion_curso = $curso['horarios'];
        if ($duracion_curso == '') {
            $duracion_curso = '4 Hrs.';
        }
        if ($curso['imagen_gif'] == '') {
            $url_imagen_curso = 'https://cursos.bo/' .  "contenido/imagenes/paginas/" . $curso['imagen'];
            if (!file_exists("../../imagenes/paginas/" . $curso['imagen'])) {
                $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $curso['imagen'];
            }
        } else {
            $url_imagen_curso = 'https://cursos.bo/' .  "contenido/imagenes/paginas/" . $curso['imagen_gif'];
        }
        $url_pagina_curso = 'https://cursos.bo/' . $curso['titulo_identificador'] . "/v-detalle.html";
        $url_registro_curso = 'https://cursos.bo/' . "registro-curso/" . $curso['titulo_identificador'] . "/v-registro.html";
        
        $mensaje_wamsm_predeternimado = 'Hola, tengo interes en el Curso ' . trim(str_replace(array('curso','Curso','CURSO'), '', $curso['titulo']));
        $numero_wamsm_predeternimado = '69714008';
        $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $curso['id'] . "' ORDER BY r.id ASC LIMIT 1 ");
        if (mysql_num_rows($rqdwn1) == 0) {
            $numero_wamsm_predeternimado = '69714008';
            $cel_wamsm = '591' . $numero_wamsm_predeternimado;
        } else {
            $rqdwn2 = mysql_fetch_array($rqdwn1);
            $cel_wamsm = '591' . $rqdwn2['numero'];
        }
        $url_whatsapp = 'https://api.whatsapp.com/send?phone=591' . $cel_wamsm . '&text=' . str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado));
        $modalidad_curso = "Presencial";

        /* datos lugar */
        $rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
        $rqdl2 = mysql_fetch_array($rqdl1);
        $lugar_nombre = $rqdl2['nombre'];
        $lugar_salon = $rqdl2['salon'];
        $lugar_direccion = $rqdl2['direccion'];
        $lugar_google_maps = $rqdl2['google_maps'];

        /* costo */
        $costo = $curso['costo'];
        $sw_descuento_costo2 = false;
        $f_h = date("H:i", strtotime($curso['fecha2']));
        if ($f_h !== '00:00') {
            $f_actual = strtotime(date("Y-m-d H:i"));
            $f_limite = strtotime($curso['fecha2']);
        } else {
            $f_actual = strtotime(date("Y-m-d"));
            $f_limite = strtotime(substr($curso['fecha2'], 0, 10));
        }
        if ($curso['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
            $sw_descuento_costo2 = true;
            $costo2 = $curso['costo2'];
        }
        $sw_descuento_costo3 = false;
        if ($curso['sw_fecha3'] == '1' && ( date("Y-m-d") <= $curso['fecha3'])) {
            $sw_descuento_costo3 = true;
            $costo3 = $curso['costo3'];
        }
        
        $sw_descuento_costo_e = false;
        if ($curso['sw_fecha_e'] == '1') {
            $sw_descuento_costo_e = true;
            $costo_e = $curso['costo_e'];
        }
        $sw_descuento_costo_e2 = false;
        if ($curso['sw_fecha_e2'] == '1') {
            $sw_descuento_costo_e2 = true;
            $costo_e2 = $curso['costo_e2'];
        }

        $return_massaje .= '
        <tr>
            <td style="border: 1px solid #9fbce8;padding: 10px;">
                <div style="">
                    <a href="'.$url_pagina_curso.'" style="font-size: 27pt;
                       text-decoration: none;
                       color: #326bb1;
                       font-weight: bold;">
                       '.$titulo_de_curso.'
                    </a>
                    <table style="width: 100%;margin: 10px 0px;">
                        <tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Fecha:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                '.$fecha_curso.'
                            </td>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Inversi&oacute;n:</b> <span style="font-size: 14pt;color: #565656;">'.$costo.' BS.</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Horarios:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                '.$duracion_curso.'
                            </td>';
                            if ($sw_descuento_costo2) {
                                $return_massaje .= '
                                <td style="padding: 7px 5px;vertical-align: top;" rowspan="7">';
                                if ($sw_descuento_costo_e) {
                                      $return_massaje .= '<b style="color: #42569e;font-size: 12pt;">Estudiantes:</b> <span style="font-size: 14pt;color: #565656;">'.$costo_e.' BS.</span>
                                                            <br/>
                                                            <br/>';
                                }
                                    $return_massaje .= '<b style="color: #42569e;font-size: 12pt;">Descuentos:</b> 
                                    <br/>
                                    <div style="background:#FFF;color:#005982;border-radius: 3px;padding: 3px;margin:2px 7px 2px 0px;border-left: 1px solid #adadad;padding-left: 10px;">
                                        <b style="color:#439a43;font-size:8pt;">POR PAGO ANTICIPADO:</b> <span style="font-size:9pt;color:gray;">(mediante dep&oacute;sito Bancarios y/o Transferencias)</span>
                                        <br/>
                                        Inversi&oacute;n: '.$costo2.' Bs. <span style="font-size:8pt;color:#535353;">hasta el '.mydatefechacurso2($curso['fecha2']).'</span>
                                        ';
                                        if ($sw_descuento_costo3) {
                                            $return_massaje .= '
                                            <br/>
                                            Inversi&oacute;n: '.$costo3.' Bs. <span style="font-size:8pt;color:#535353;">hasta el '.mydatefechacurso2($curso['fecha3']).'</span>
                                            ';
                                        }
                                        if ($sw_descuento_costo_e2) {
                                            $return_massaje .= '
                                            <br/>
                                            Estudiantes: '.$costo_e2.' Bs. <span style="font-size:8pt;color:#535353;">hasta el '.mydatefechacurso2($curso['fecha_e2']).'</span>
                                            ';
                                        }
                                        $return_massaje .= '
                                    </div>
                                </td>
                                ';
                            }
                            
                            $return_massaje .= '
                        </tr>';
                            if($id_departamento!=='10'){
                        $return_massaje .= '<tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Ciudad:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                '.$ciudad_curso.'
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Lugar:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                '.$lugar_nombre.'
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Sal&oacute;n:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                ';
                                if ($lugar_salon == '') {
                                    $return_massaje .= "verificar en detalles";
                                } else {
                                    $return_massaje .= $lugar_salon;
                                }
                                $return_massaje .= '
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 7px 5px;">
                                <b style="color: #42569e;font-size: 12pt;">Direcci&oacute;n:</b>
                            </td>
                            <td style="padding: 7px 5px;">
                                '.$lugar_direccion.'
                            </td>
                        </tr>';
                            }
                        $return_massaje .= '
                    </table>
                </div>
            </td>
            <td style="border: 1px solid #9fbce8;padding: 10px;text-align:center;vertical-align: top;">
                <a href="'.$url_pagina_curso.'">
                    <img src="'.$url_imagen_curso.'" alt="'.$titulo_de_curso.'" title="'.$titulo_de_curso.'" style="width: 190px;"/>
                </a>
                <br/>
                <br/>
                <br/>
                <a href="'.$url_pagina_curso.'" style="color: #FFF;
                   background: orange;
                   padding: 10px 20px;
                   border-radius: 5px;
                   font-weight: bold;
                   text-decoration: none;">VER DETALLES</a>
                <br/>
                <br/>
                <br/>
                <a href="'.$url_registro_curso.'" style="color: #FFF;
                   background: #1ab91a;
                   padding: 10px 20px;
                   border-radius: 5px;
                   font-weight: bold;
                   text-decoration: none;">REGISTRARME</a>
                <br/>
                <br/>
                <br/>
                <a href="'.$url_whatsapp.'">
                    <img src="https://cursos.bo/contenido/imagenes/paginas/1510747809whatsapp__.png" style="height: 50px;"/>
                </a>
            </td>
        </tr>
        ';
    }
    $texto_informacion_para_pago = '<b>SOLICITE A SU ENTIDAD FINANCIERA LA HABILITACION DE BANCA POR INTERNET LLAMANDO POR TELEFONO, EVITE SALIR DE SUS CASA #QUEDATEENCASA</b>
        <br>
CUENTA BANCARIAS
<br>
Banco UNION A nombre de : <b>NEMABOL</b>   cuenta 124033833
<br>
<b>BANCO DE CREDITO BCP</b> A nombre de : Evangelina Sardon Tintaya    cuenta 201-50853966-3-23
<br>
<b>BANCO SOL</b> A nombre de : Evangelina Sardon Tintaya cuenta 1166531-000-001
<br>
<b>BANCO NACIONAL BNB</b>   A nombre de : Evangelina Sardon Tintaya cuenta 1501512288
<br>
<b>BANCO MERCANTIL SANTA CRUZ</b> A nombre de : Evangelina Sardon Tintaya cuenta 4066860455
<br>
<b>BANCO FIE</b>  A nombre de : Evangelina Sardon Tintaya cuenta 40004725631
<br>
<br>
<b>TIGO MONEY:</b> A la linea <b>69714008</b> el costo sin recargo (Titular Edgar Aliaga)
<br>
DATOS PARA REALIZAR UNA TRANSFERENCIA BANCARIA:
<br>
Datos cuenta JURIDICA <b>NEMABOL</b> (Caja de Ahorro, CI 2044323 LP, NIT 2044323014 CIUDAD LA PAZ)
<br>
Datos cuenta PERSONA NATURAL <b>EVANGELINA SARDON TINTAYA</b> (Caja de Ahorro, CI 6845644 LP CIUDAD LA PAZ
<br>
Consultas Whatsapp : https://wa.me/59169794724
<br>
Videos demo ingreso a nuestra plataforma en facebook https://www.facebook.com/boliviasicoes/videos/850008035473174/
<br><br>';
    $return_massaje .= '
    <tr>
        <td style="border: 1px solid #9fbce8;padding: 10px 30px;line-height: 2;color: #2f64a5;" colspan="2">
            <b style="color:green;">PUEDE REALIZAR EL PAGO DE LOS CURSOS MEDIANTE DEPOSITO O TRANSFERENCIA</b>
            <br>
            '.$texto_informacion_para_pago.'
        </td>

    </tr>
    </table>
    </div>
    ';
    return $return_massaje;
}

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

function fecha_corta($data) {
    $d = date("d", strtotime($data));
    $m = date("m", strtotime($data));
    $me = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $d . " de " . $me[(int) $m];
}

function mydatefechacurso2($dat) {
    $ds = date("w", strtotime($dat));
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $h = date("H:i", strtotime($dat));
    $txt_hour = '';
    if ($h !== '00:00') {
        $txt_hour = ' hasta ' . $h;
    }
    $array_dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $array_meses = array('NONE', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $array_dias[$ds] . " " . $d . " de " . ucfirst($array_meses[(int) ($m)]) . '' . $txt_hour;
}
