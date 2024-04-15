<?php

/* REQUERIDO PHP MAILER */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* Load Composer's autoloader */
require '../../librerias/phpmailer/vendor/autoload.php';

/* data */
$id_curso = post('id_curso');
$modo_envio = get('modenv');
$title_push = strip_tags(urldecode(get('title')));
$bloque_envio = abs((int) get('bloque'));
$id_admd = (int) base64_decode(get('ahdmd'));

/* curso */
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($rqdc1);

$curso_id_ciudad = (int) $curso['id_ciudad'];
$id_categoria = (int) $curso['id_categoria'];
$curso_id_modalidad = $curso['id_modalidad'];
$titulo_identificador_curso = $curso['titulo_identificador'];
$data_nombre_curso = $curso['titulo'];
$fecha_curso = fecha_curso($curso['fecha']);
$contenido_curso = getContenidoCurso($curso);

/* id departamento */
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$curso_id_ciudad' LIMIT 1 ");
$rqdd2 = mysql_fetch_array($rqdd1);
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
while ($rqdctob2 = mysql_fetch_array($rqdctob1)) {
    $id_tag = $rqdctob2['id'];
    if (isset_post('tag' . $id_tag)) {
        $ids_etiquetas .= ',' . $id_tag;
    }
}

/* imagen */
$url_img_curso = $dominio . "contenido/imagenes/paginas/" . $curso['imagen'] . "";
if (!file_exists("../../imagenes/paginas/" . $curso['imagen'])) {
    $url_img_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $curso['imagen'] . "";
}

/* cont correo a enviar */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>$data_nombre_curso</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo/$titulo_identificador_curso.html'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= $contenido_curso;
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros.</h3>"
        . "</div>";

/* datos de correo */
if($curso_id_modalidad=='2'){
    $asunto = "$data_nombre_curso";
}else{
    $asunto = "$fecha_curso, $data_nombre_curso";
}
$cabeceras = 'From:' . 'CURSOS.BO <contacto@cursos.bo>' . "\r\n" .
        'Reply-To:' . 'contacto@cursos.bo' . "\r\n" .
        'X-Mailer: PHP/' . phpversion() .
        'Return-Path:' . 'contacto@cursos.bo' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";

/* modo de envio */
switch ($modo_envio) {
    case 'usuariosreg':
        /* qr ciudades */
        $qr_ciudades_1 = " AND id_ciudad IN (0,$curso_id_ciudad) ";
        if($curso['id_modalidad'] == '3'){
            $qr_ciudades_1 = "";
        }
        /* total_usuarios_registrados */
        $rqtur1 = query("SELECT DISTINCT email FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso') AND email NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) $qr_ciudades_1 $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqtur1)) {
            $correo = $rqturap2['email'];
            $enlace_dar_baja_curso = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html">Ya tome este curso anteriormente</a>';
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_preunsubscribe = '<div style="background: #f5f5f5;padding: 7px 20px;text-align: right;color: #ccc;font-size: 9.5pt;"><a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html" style="color: #FFF;background: #bfa31b;padding: 4px 15px;border-radius: 5px;text-decoration: none;">Ya tome este curso anteriormente</a></div>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_curso | $enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifcuremail WHERE email='$correo' AND id_curso='$id_curso' ");
            $rqder2 = mysql_fetch_array($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifcuremail (id_curso,email) VALUES ('$id_curso','$correo') ");
                enviar_email($correo, $asunto, $cont_preunsubscribe . $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);
                }
            }
        }
        logcursos_AUX('Notificacion de curso [correo][usuarios registrados][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_curso);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'anterpart':
        /* qr ciudades */
        $qr_ciudades_2 = " id_ciudad='$curso_id_ciudad' and ";
        if($curso['id_modalidad'] == '3'){
            $qr_ciudades_2 = "";
        }
        /* anteriores participantes */
        $rqturap1 = query("SELECT DISTINCT correo FROM cursos_participantes WHERE sw_notif=1 AND correo LIKE '%@%' AND id_curso IN ("
                . "select id from cursos where $qr_ciudades_2 id in (select id_curso from cursos_rel_cursostags where id_tag in ($ids_etiquetas) ) "
                . ") AND correo NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso' ) AND correo NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqturap1)) {
            $correo = $rqturap2['correo'];
            $enlace_dar_baja_curso = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html">Ya tome este curso anteriormente</a>';
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_preunsubscribe = '<div style="background: #f5f5f5;padding: 7px 20px;text-align: right;color: #ccc;font-size: 9.5pt;"><a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html" style="color: #FFF;background: #bfa31b;padding: 4px 15px;border-radius: 5px;text-decoration: none;">Ya tome este curso anteriormente</a></div>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_curso | $enlace_dar_baja_notificaciones</div>";
            /* verficacion de envio ya realizado */
            $rqder1 = query("SELECT count(*) AS total FROM cursos_rel_notifcuremail WHERE email='$correo' AND id_curso='$id_curso' ");
            $rqder2 = mysql_fetch_array($rqder1);
            if((int)$rqder2['total']==0){
                query("INSERT INTO cursos_rel_notifcuremail (id_curso,email) VALUES ('$id_curso','$correo') ");
                enviar_email($correo, $asunto, $cont_preunsubscribe . $contenido_correo . $cont_unsubscribe);
                $cnt_env++;
                if ($cnt_env == 1) {
                    //*enviar_email("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);
                }
            }
        }
        logcursos_AUX('Notificacion de curso [correo][participantes anteriores][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_curso);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'partnoasist':
        /* qr ciudades */
        $qr_ciudades_3 = " AND id_ciudad='$curso_id_ciudad' ";
        if($curso['id_modalidad'] == '3'){
            $qr_ciudades_3 = "";
        }
        /* $total_participantes_sin_asistir */
        $rqturapn1 = query("SELECT DISTINCT correo FROM cursos_participantes WHERE sw_notif=1 AND estado='0' AND correo LIKE '%@%' AND id_curso IN ("
                . "select id from cursos where id in (select id_curso from cursos_rel_cursostags where id_tag in (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso') ) $qr_ciudades_3 "
                . ") AND correo NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso' ) AND correo NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) $qr_bloque ");
        $cnt_env = 0;
        while ($rqturap2 = mysql_fetch_array($rqturapn1)) {
            $correo = $rqturap2['correo'];
            $enlace_dar_baja_curso = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html">Ya tome este curso anteriormente</a>';
            $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
            $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_curso | $enlace_dar_baja_notificaciones</div>";
            query("INSERT INTO cursos_rel_notifcuremail (id_curso,email) VALUES ('$id_curso','$correo') ");
            enviar_email($correo, $asunto, $contenido_correo . $cont_unsubscribe);
            $cnt_env++;
            if ($cnt_env == 1) {
                //*enviar_email("brayan.desteco@gmail.com", $asunto, $contenido_correo . $cont_unsubscribe);
            }
        }
        logcursos_AUX('Notificacion de curso [correo][participantes anteriores][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_curso);
        echo "<b class='btn btn-info active'>ENVIOS REALIZADOS [$cnt_env]</b>";
        break;
    case 'appmovil':
        /*
          $correo = "brayan.desteco@gmail.com";
          $enlace_dar_baja_curso = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/' . $id_curso . '/' . md5($correo . 'dardebaja') . '.html">Ya tome este curso anteriormente</a>';
          $enlace_dar_baja_notificaciones = '<a href="https://cursos.bo/unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html" title="unsubscribe_url">Dejar de recibir notificaciones</a>';
          $cont_unsubscribe = "<hr/><div style='text-align:center;'>$enlace_dar_baja_curso | $enlace_dar_baja_notificaciones</div>";
          query("INSERT INTO cursos_rel_notifcuremail (id_curso,email) VALUES ('$id_curso','$correo') ");
          mail($correo, $asunto, $contenido_correo . $cont_unsubscribe, $cabeceras);
         */
        echo "<b class='btn btn-default'>Sin usuarios</b>";

        break;
    case 'pushnav':
        /* $total_suscripcion_navegador */
        $arrNotificationMessage = array(
            'title' => $title_push,
            'text' => $title_push . ', ' . $fecha_curso,
            'sound' => "mySound",
            'image' => $url_img_curso,
            'icon' => $url_img_curso,
            'click_action' => "https://cursos.bo/$titulo_identificador_curso.html",
            'url' => "https://cursos.bo/$titulo_identificador_curso.html",
            "vibrate" => [200, 100, 200, 100, 200, 100, 400],
            'priority' => "high"
        );
        /* qr ciudades */
        $qr_ciudades_4 = " AND id_departamento IN ('0','$id_departamento') ";
        if($curso['id_modalidad'] == '3'){
            $qr_ciudades_4 = "";
        }
    
    
    
        $rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 AND id NOT IN (select id_tokensusc from cursos_rel_notifsuscpush where id_curso='$id_curso' and fecha_envio=CURDATE() ) $qr_bloque ORDER BY id DESC ");
        //***$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 $qr_bloque ORDER BY id ASC ");
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
            query("INSERT INTO cursos_rel_notifsuscpush (id_curso,id_tokensusc,fecha_envio) VALUES ('$id_curso','$id_tokensusc','$fecha_envio') ");
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
                
                /*
                echo "<hr/>-->";
                echo print_r($result,true);
                echo "<--<hr/>";
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
                $my_data_mcast = "$multicast_id | $canonical_ids";
                /* update data envio */
                if ((int)$failure > 0) {
                    $cnt_pos_fail = 0;
                    foreach ($results as $result){
                        if(isset($result['error'])){
                            /* echo "-->".  print_r($registration_ids,true)."<--"; */
                            /* echo "<h2>Position fail: $cnt_pos_fail || ID: ".$registration_ids[$cnt_pos_fail]."</h2>"; */
                            if ($result['error'] == 'NotRegistered') {
                                query("UPDATE cursos_suscnav SET estado='2' WHERE token LIKE '".$registration_ids[$cnt_pos_fail]."' ");
                                /* echo "<br/>TOKEN RETIRADO: ".$registration_ids[$cnt_pos_fail]."<br/>"; */
                                $cnt_ret++;
                                $cnt_env--;
                            }
                        }
                        $cnt_pos_fail++;
                    }
                }
                query("UPDATE cursos_rel_notifsuscpush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
                $cnt_100lotes = 0;
                $registration_ids = array ();
                $ids_newnotifs = '0';
            }
            
            //echo "<hr/>$result<hr/>";
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
            query("UPDATE cursos_rel_notifsuscpush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
        }

        logcursos_AUX('Notificacion de curso [notif push][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_curso);
        echo '<div style="padding: 5px;">';
        echo '<b style="color:#FFF;font-size:14pt;background: #67a90f;padding: 5px;border-radius: 9px 0px 0px 9px;">Completado:</b>';
        echo '&nbsp;&nbsp;<span style="color:#FFF;font-size:12pt;margin: 5px;">'.$cnt_env.' enviados&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;'.$cnt_ret.' retirados</span>';
        echo '</div>';
        break;
    default:
        break;
}




/* FUNCIONES */

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}

/*
function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}
*/

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
        echo "<span style='font-size:10pt;'>$correo_a_enviar NO VALIDO <i style='font-size:8pt;color:red;'>[DEPURADO]</i></span><br/>";
        query("UPDATE cursos_usuarios SET sw_notif=0 WHERE email='$correo_a_enviar' LIMIT 2 ");
        query("UPDATE cursos_participantes SET sw_notif=0 WHERE correo='$correo_a_enviar' LIMIT 2 ");
    }
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


