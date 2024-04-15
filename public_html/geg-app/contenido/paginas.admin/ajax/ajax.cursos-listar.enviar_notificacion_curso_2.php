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
    
    
    
        $rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' AND token LIKE 'fv1SeeWhm4k:APA91bELCtsEw6a-FtWRLhOVi2J1QhQudFVbCdySFMdkz7wkerryE5DonAcguo6pnqBS42RqVMcDA0NY8sPiM1Q03-ayYMRatnY9Pk3nCGiEEj-bax7li_6OfbBnWgkab_QiSqL2YmF8' ORDER BY id DESC ");
        //***11111***$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 AND id NOT IN (select id_tokensusc from cursos_rel_notifsuscpush where id_curso='$id_curso' and fecha_envio=CURDATE() ) $qr_bloque ORDER BY id DESC ");
        //***$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 $qr_bloque ORDER BY id ASC ");
        //$rqdn1 = query("SELECT id,token FROM cursos_suscnav WHERE token LIKE 'cUCmr0ZSdS4:APA91bElZh6ZZhQ7v1NuLK7Xz053VQj4kAIScV_Gzr54leYv4sXIDTtlS14bzLYUrfJ5_xdsUoQiClsl-oLE_rnKq2QnoUuuzUCQTAUT1u_-iGDJCVaMkHRNLCox6jOdyEpalra8nGKt' ORDER BY id DESC ");
        //echo "TOTAL: ".mysql_num_rows($rqdn1);exit;
        $cnt_env = 0;
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

            if($cnt_100lotes>=20){
                
                $ch = curl_init("https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"registration_ids\" : " . json_encode($registration_ids) . "}");
                $result = curl_exec($ch);
                curl_close($ch);
                
                
                echo "<hr/>-->";
                echo print_r($result,true);
                echo "<--<hr/>";
                
                
                /* datos de recepcion */
                $result_json = json_decode($result, true);
                $multicast_id = $result_json['multicast_id'];
                $success = $result_json['success'];
                $failure = $result_json['failure'];
                $canonical_ids = $result_json['canonical_ids'];
                $results = $result_json['results'];
                $results_error = $result_json['results'][0]['error'];
                $results_message_id = $result_json['results'][0]['message_id'];
                
                //$data_mcast = print_r($result,true);
                
                /* tratamiento de recepcion */
//                if ($failure == '1') {
//                    /* no registrado */
//                    if ($results_error == 'NotRegistered') {
//                        query("UPDATE cursos_suscnav SET estado='2' WHERE id='$id_tokensusc' ");
//                        //echo "<br/>ESTADO ACTUALIZADO<br/>";
//                    }
//                }
                
                /* update data envio */
                if ((int)$failure > 0) {
                    $my_data_mcast = "$multicast_id | $success | $failure | $canonical_ids | $results | $results_error | $results_message_id";
                    $cnt_pos_fail = 0;
                    foreach ($results as $result){
                        $cnt_pos_fail++;
                        if(isset($result['error'])){
                            echo "<h2>Position fail: $cnt_pos_fail || ID: ".$registration_ids[$cnt_pos_fail]."</h2>";
                        }
                    }
                }else{
                    $my_data_mcast = "$multicast_id | $canonical_ids";
                }
                query("UPDATE cursos_rel_notifsuscpush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
                           
                //*echo "Test Finished";exit;
                
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
            
            echo "<hr/>-->";
                echo print_r($result,true);
                echo "<--<hr/>";
            
            /* datos de recepcion */
            $result_json = json_decode($result, true);
            $multicast_id = $result_json['multicast_id'];
            $success = $result_json['success'];
            $failure = $result_json['failure'];
            $canonical_ids = $result_json['canonical_ids'];
            $results = $result_json['results'];
            $results_error = $result_json['results'][0]['error'];
            $results_message_id = $result_json['results'][0]['message_id'];
            /* update data envio */
            if ((int) $failure > 0) {
                $my_data_mcast = "$multicast_id | $success | $failure | $canonical_ids | $results | $results_error | $results_message_id";
            } else {
                $my_data_mcast = "$multicast_id | $canonical_ids";
            }
            query("UPDATE cursos_rel_notifsuscpush SET data='$my_data_mcast' WHERE id IN ($ids_newnotifs) ");
        }

        logcursos_AUX('Notificacion de curso [notif push][' . $cnt_env . ' envios]', 'notificacion-curso', 'curso', $id_curso);
        echo "<b style='color:#FFF;font-size:14pt;' class='btn btn-primary active'>NOTIFICACIONES REALIZADAS: $cnt_env</b>";
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
/*
<hr/>-->{
    "multicast_id":235891323022612371,
            "success":19,
            "failure":1,
            "canonical_ids":0,
            "results":
        [
        {"message_id":"0:1574960595407392%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595406267%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595411524%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595409585%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595463871%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595407510%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595409872%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595408696%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595406048%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595407524%e609af1cf9fd7ecd"},
        {"message_id":"0:1574960595411707%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595410589%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595413156%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595412413%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595408151%e609af1cf9fd7ecd"},
        {"message_id":"0:1574960595424624%b5e1d03cf9fd7ecd"},
        {"message_id":"0:1574960595409903%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960595410072%263106ebf9fd7ecd"},
        {"error":"NotRegistered"},
        {"message_id":"0:1574960595415455%0f493ae6f9fd7ecd"}
        ]
        
        }
        <--<hr/><h2>
        Position fail: 19 || 
            ID: c6xVjHHEcO8:APA91bFjg67paPnhbzc8xjJUn8bgHytJmi0-QZEMiDddMmUdxIwP1Dz5ljT05XBTg5Cwo82XgQgI-_2GLxawadLu_tfIKRjXYl7r2RXs2MzIMGukgnHZjhdbOQ2p5t8-pbnX8fxEIpAc
            </h2>
            <hr/>-->
    {"multicast_id":5745265341260219406,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960595929110%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595948704%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595927026%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595928591%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595994007%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595938707%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595935946%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595929621%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595940666%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595944918%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595928207%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595946600%263106ebf9fd7ecd"},{"message_id":"0:1574960595929669%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595931484%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595947252%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595928391%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595951688%263106ebf9fd7ecd"},{"message_id":"0:1574960595930331%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595930824%0f493ae6f9fd7ecd"},{"message_id":"0:1574960595930637%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->
    {
        "multicast_id":1982793504251645516,
                "success":18,"failure":2,
                "canonical_ids":0,
                "results":
            [
        {"message_id":"0:1574960596469880%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596487639%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596465234%cc9b4facf9fd7ecd"},
        {"message_id":"0:1574960596469044%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596470550%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596467588%0f493ae6f9fd7ecd"},
        {"error":"NotRegistered"},
        {"message_id":"0:1574960596466912%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596469769%0f493ae6f9fd7ecd"},
        {"error":"NotRegistered"},
        {"message_id":"0:1574960596491484%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596467878%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596469361%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596466898%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596467759%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596489883%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596469364%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596470517%263106ebf9fd7ecd"},
        {"message_id":"0:1574960596470238%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574960596469230%0f493ae6f9fd7ecd"}
        ]
        
        }<--<hr/><h2>
        Position fail: 7 || 
            ID: fv1SeeWhm4k:APA91bELCtsEw6a-FtWRLhOVi2J1QhQudFVbCdySFMdkz7wkerryE5DonAcguo6pnqBS42RqVMcDA0NY8sPiM1Q03-ayYMRatnY9Pk3nCGiEEj-bax7li_6OfbBnWgkab_QiSqL2YmF8
            </h2>
            <h2>Position fail: 10 || ID: dQDRuCo9BW0:APA91bFEttPpKlGb_TsPe2IK7JrAXDxiNyMTFcVp5GSr4vZlS22nvr2eoRNRuJ8SXorPQHTo4HpsV998belcz4IXITptcblN2eKbJEifzxMp__CWocUWRyEt_w2u5szsREzouri5vNkM
            </h2><hr/>-->
    {"multicast_id":7746987664341580309,"success":19,"failure":1,"canonical_ids":0,"results":[{"message_id":"0:1574960597005158%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597004950%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597014954%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597006763%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597003454%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597115872%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597003082%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597007279%0f493ae6f9fd7ecd"},{"error":"NotRegistered"},{"message_id":"0:1574960597002008%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597004275%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597005365%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597024747%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597036596%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597006917%e609af1cf9fd7ecd"},{"message_id":"0:1574960597024386%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597039305%e609af1cf9fd7ecd"},{"message_id":"0:1574960597019114%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597007539%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597023791%e609af1cf9fd7ecd"}]}<--<hr/><h2>Position fail: 9 || ID: d7gn_Gt022o:APA91bF6WIr7NJXk2ywKsxwQYzE7fVO5WbCwj2wR177SZo-72VMkMzmcrioAokMdIRCCDbnP073Xgmcte4NL_rvcKcvHmdB1_YHFm24hDoCCbiaJr_O-hbxRKDN_wTQf1HATLQx0ZIdW</h2><hr/>-->{"multicast_id":2591826614957154004,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960597611416%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597603722%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597763836%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597602160%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597601381%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597610722%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597599277%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597605781%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597601671%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597601400%e609af1cf9fd7ecd"},{"message_id":"0:1574960597604515%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597608392%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597659825%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597601035%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597609403%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597609734%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597602495%e609af1cf9fd7ecd"},{"message_id":"0:1574960597604957%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597660937%0f493ae6f9fd7ecd"},{"message_id":"0:1574960597603326%263106ebf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1272791204880650264,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960598252710%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598256316%263106ebf9fd7ecd"},{"message_id":"0:1574960598295299%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598253358%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598279564%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598253793%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598277530%e609af1cf9fd7ecd"},{"message_id":"0:1574960598252760%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598254114%706d4ff5f9fd7ecd"},{"message_id":"0:1574960598269316%263106ebf9fd7ecd"},{"message_id":"0:1574960598263756%e609af1cf9fd7ecd"},{"message_id":"0:1574960598254425%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598258790%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598252588%e609af1cf9fd7ecd"},{"message_id":"0:1574960598256746%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598252001%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598253667%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598252459%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598250605%e609af1cf9fd7ecd"},{"message_id":"0:1574960598255775%706d4ff5f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":467789499453632155,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960598751075%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960598753967%263106ebf9fd7ecd"},{"message_id":"0:1574960598750806%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598750022%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598750307%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598750866%e609af1cf9fd7ecd"},{"message_id":"0:1574960598751605%e609af1cf9fd7ecd"},{"message_id":"0:1574960598757587%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598749899%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598755539%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598751771%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598752624%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598782435%263106ebf9fd7ecd"},{"message_id":"0:1574960598753912%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598751548%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598819298%263106ebf9fd7ecd"},{"message_id":"0:1574960598755661%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598756459%0f493ae6f9fd7ecd"},{"message_id":"0:1574960598778907%e609af1cf9fd7ecd"},{"message_id":"0:1574960598940240%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8372396025458197522,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960599358897%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599366716%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599361025%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599359771%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599470980%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599362444%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599360109%e609af1cf9fd7ecd"},{"message_id":"0:1574960599362255%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599410634%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599357287%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599373595%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599364291%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599362797%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599358769%263106ebf9fd7ecd"},{"message_id":"0:1574960599362745%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599470707%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599369465%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599379126%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599369436%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599431094%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6592635568406333584,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960600000004%e609af1cf9fd7ecd"},{"message_id":"0:1574960600013726%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599998008%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599997936%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600011645%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600001081%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600001325%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600117699%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599997907%e609af1cf9fd7ecd"},{"message_id":"0:1574960600003738%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960600002181%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600000920%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600001340%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600001044%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600014783%3b6c2a24f9fd7ecd"},{"message_id":"0:1574960600071835%e609af1cf9fd7ecd"},{"message_id":"0:1574960600012357%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600002365%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600085840%0f493ae6f9fd7ecd"},{"message_id":"0:1574960599998758%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6502397638162955827,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960600612678%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600633514%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600608135%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600606706%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600595881%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600590963%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600592057%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600591926%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600587688%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600592928%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600590985%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600592136%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600586212%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600601973%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600605846%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600587200%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600589784%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600589595%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600588228%0f493ae6f9fd7ecd"},{"message_id":"0:1574960600591276%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4955823246268661450,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960601147738%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601100768%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601100821%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601099131%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601097870%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601096388%263106ebf9fd7ecd"},{"message_id":"0:1574960601096375%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601118348%e609af1cf9fd7ecd"},{"message_id":"0:1574960601106157%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601096274%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601096951%706d4ff5f9fd7ecd"},{"message_id":"0:1574960601123774%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601097673%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601101906%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601100769%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601098584%e609af1cf9fd7ecd"},{"message_id":"0:1574960601103712%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601104856%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601102884%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601101828%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8968670571137634853,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960601619084%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601622255%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601621115%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601622893%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601618931%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601626572%e609af1cf9fd7ecd"},{"message_id":"0:1574960601626511%263106ebf9fd7ecd"},{"message_id":"0:1574960601617829%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601625591%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601620681%263106ebf9fd7ecd"},{"message_id":"0:1574960601622833%0f493ae6f9fd7ecd"},{"message_id":"https:\/\/updates.push.services.mozilla.com\/m\/gAAAAABd3_3ZRzaIgrJcsgwOZ9mOWfmY0-Ti2qmmdwLM5kK2pNFKFWpt9eF1ohwz8KeCx7JkodH2IEQUjDCSE0rFe5dYkRpLcW0w34VlJw-pSN58myXXYg6DdvUQHCHg8l6DoKceeRSMZOpM3V63f-YARHGv2y2zyyMRgc3oFZpflJsEBXi71-LC1Y8uvvBS7rgbXosBEdDJ"},{"message_id":"0:1574960601641975%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601623512%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601642978%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601620482%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601618814%0f493ae6f9fd7ecd"},{"message_id":"0:1574960601618381%e609af1cf9fd7ecd"},{"message_id":"0:1574960601626382%e609af1cf9fd7ecd"},{"message_id":"0:1574960601648861%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":5417197389574346425,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960602422658%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602423866%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602518854%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602433977%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602427473%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602424303%e609af1cf9fd7ecd"},{"message_id":"0:1574960602425295%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602424191%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602425063%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602455850%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602464713%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602426370%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602442248%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602453257%e609af1cf9fd7ecd"},{"message_id":"0:1574960602425795%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602525854%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602428573%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602427781%e609af1cf9fd7ecd"},{"message_id":"0:1574960602427114%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602424893%e609af1cf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":3866192682766310096,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960603019829%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602954290%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602978932%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602956440%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602955037%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602956202%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602990879%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602955058%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602952607%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602953517%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602960449%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602959060%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602955655%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602970654%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602962927%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602965629%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602954514%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602979018%0f493ae6f9fd7ecd"},{"message_id":"0:1574960602967712%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603016508%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6443607834784560655,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960603513839%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603551898%e609af1cf9fd7ecd"},{"message_id":"0:1574960603540892%e609af1cf9fd7ecd"},{"message_id":"0:1574960603543791%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603517031%e609af1cf9fd7ecd"},{"message_id":"0:1574960603520462%e609af1cf9fd7ecd"},{"message_id":"0:1574960603516927%e609af1cf9fd7ecd"},{"message_id":"0:1574960603514293%263106ebf9fd7ecd"},{"message_id":"0:1574960603523160%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603522642%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603517805%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603515034%263106ebf9fd7ecd"},{"message_id":"0:1574960603522002%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603524593%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603554502%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603525588%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603540152%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603516529%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603518752%0f493ae6f9fd7ecd"},{"message_id":"0:1574960603520820%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":5105165349543627790,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960604053727%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604075966%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604065425%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604098883%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604056628%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604053103%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604057587%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604077425%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604056921%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604062703%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604074769%263106ebf9fd7ecd"},{"message_id":"0:1574960604060520%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604059597%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604089345%263106ebf9fd7ecd"},{"message_id":"0:1574960604057450%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604086994%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604056931%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960604068403%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604054934%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604064054%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":7079392643188849336,"success":19,"failure":1,"canonical_ids":0,"results":[{"message_id":"0:1574960604577606%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604576821%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604581339%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604576804%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604580662%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604586793%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604581943%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604582817%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604585713%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604616040%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604574022%0f493ae6f9fd7ecd"},{"error":"NotRegistered"},{"message_id":"0:1574960604609627%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604579874%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604590751%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604583962%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604598879%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604575485%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604586885%0f493ae6f9fd7ecd"},{"message_id":"0:1574960604577226%b5e1d03cf9fd7ecd"}]}<--<hr/><h2>Position fail: 12 || ID: cZgGC_bq5g8:APA91bHREPKgdxtfIKYhXA9LLfDqD5ffil3iBmRp5MGG1eitm-NOmh-u66zivzyPPp9PcdiIx3W29a-Ivns2D6AaXtVt28mnI_Mb6Luby_sVsSIDVRMnE1q94lZEL8RpxyCSsmMo0yLt</h2><hr/>-->{"multicast_id":3308681319159103620,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960605367964%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605368904%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605375229%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605367928%e609af1cf9fd7ecd"},{"message_id":"0:1574960605369025%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605390505%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605368980%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605384503%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605384545%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605370302%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605382413%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605373608%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605370745%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605370809%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605407384%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605393395%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605371910%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960605370779%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960605376926%0f493ae6f9fd7ecd"},{"message_id":"0:1574960605383282%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8227561315691340317,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960606083970%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606087017%e609af1cf9fd7ecd"},{"message_id":"0:1574960606083236%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606082544%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606081289%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606085899%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606079191%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606091449%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606091716%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606082337%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606082338%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606081266%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606087723%e609af1cf9fd7ecd"},{"message_id":"0:1574960606081814%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606082976%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606118198%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606079695%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606090881%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606081399%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606084574%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":205938680273378724,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960606593684%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606574106%263106ebf9fd7ecd"},{"message_id":"0:1574960606574136%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606579842%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606591457%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606615091%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606575830%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576109%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606582467%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606589875%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606609798%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606604171%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576269%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606613295%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606575053%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576422%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576231%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576525%263106ebf9fd7ecd"},{"message_id":"0:1574960606577542%0f493ae6f9fd7ecd"},{"message_id":"0:1574960606576822%e609af1cf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1545214602735086295,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960607103917%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607086999%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607121893%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607085623%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607087984%e609af1cf9fd7ecd"},{"message_id":"0:1574960607085982%e609af1cf9fd7ecd"},{"message_id":"0:1574960607086226%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607107316%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607087916%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607084493%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607090330%e609af1cf9fd7ecd"},{"message_id":"0:1574960607111925%e609af1cf9fd7ecd"},{"message_id":"0:1574960607115174%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607086932%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607084809%e609af1cf9fd7ecd"},{"message_id":"0:1574960607085130%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607135872%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607129759%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607092994%e609af1cf9fd7ecd"},{"message_id":"0:1574960607093874%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":7014213009377711749,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960607656444%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607655347%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607685364%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607655459%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607657858%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607654101%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607662216%e609af1cf9fd7ecd"},{"message_id":"0:1574960607667281%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607668049%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607661771%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607654588%e609af1cf9fd7ecd"},{"message_id":"0:1574960607668436%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607693630%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607763910%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607656747%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607655821%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607721998%0f493ae6f9fd7ecd"},{"message_id":"0:1574960607657570%e609af1cf9fd7ecd"},{"message_id":"0:1574960607655264%263106ebf9fd7ecd"},{"message_id":"0:1574960607695436%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4193488435554225943,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960608236467%263106ebf9fd7ecd"},{"message_id":"0:1574960608223243%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608218707%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608313733%e609af1cf9fd7ecd"},{"message_id":"0:1574960608236465%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608218031%e609af1cf9fd7ecd"},{"message_id":"0:1574960608221491%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608228133%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608219486%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608326885%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608222304%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608242139%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608224938%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608262642%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608238422%e609af1cf9fd7ecd"},{"message_id":"0:1574960608219984%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608222978%e609af1cf9fd7ecd"},{"message_id":"0:1574960608261684%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608331609%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608247244%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":3360715080253092575,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960608846373%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960608834566%e609af1cf9fd7ecd"},{"message_id":"0:1574960608826276%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608821923%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608819017%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608827259%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608831517%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608823689%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608820734%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608825177%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608822757%e609af1cf9fd7ecd"},{"message_id":"0:1574960608818849%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608821771%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608832023%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960608821922%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608824451%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608820635%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608835340%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608819783%0f493ae6f9fd7ecd"},{"message_id":"0:1574960608821887%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4890549151587009621,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960609429210%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609433698%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609427587%263106ebf9fd7ecd"},{"message_id":"0:1574960609429417%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609433193%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609430846%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609429737%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609432688%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609433658%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609447549%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960609449937%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609492926%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609545782%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609427052%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609429536%e609af1cf9fd7ecd"},{"message_id":"0:1574960609481458%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609433077%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960609432726%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609428526%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609488203%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4302503683561092593,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960609996533%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610001261%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610022558%263106ebf9fd7ecd"},{"message_id":"0:1574960610003376%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610019603%263106ebf9fd7ecd"},{"message_id":"0:1574960609996370%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609994285%e609af1cf9fd7ecd"},{"message_id":"0:1574960609996401%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610051988%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610000407%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609998954%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609997724%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609999601%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609996042%263106ebf9fd7ecd"},{"message_id":"0:1574960609997903%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609997462%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610000692%0f493ae6f9fd7ecd"},{"message_id":"0:1574960609996443%e609af1cf9fd7ecd"},{"message_id":"0:1574960610019385%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610026466%263106ebf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6158154339696898174,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960610531269%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610566684%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610549929%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610569915%e609af1cf9fd7ecd"},{"message_id":"0:1574960610528691%e609af1cf9fd7ecd"},{"message_id":"0:1574960610527225%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610530962%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610527992%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610534690%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610531682%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610531815%ca718e0df9fd7ecd"},{"message_id":"0:1574960610529599%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610562791%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610562504%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610530059%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610532290%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610532811%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610540361%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610540126%0f493ae6f9fd7ecd"},{"message_id":"0:1574960610593810%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1556113483530231542,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960611192458%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611200583%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611231833%263106ebf9fd7ecd"},{"message_id":"0:1574960611197066%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611227690%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611189698%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611189811%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611194981%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611187876%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611188642%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611215237%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611231968%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611191074%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611196559%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611187605%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611191915%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611191117%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611227183%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611267962%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611203889%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1312693185448963213,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960611736373%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611732928%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611729076%b5e1d03cf9fd7ecd"},{"message_id":"0:1574960611731319%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611732942%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611730713%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611728894%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611726707%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611730261%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611740510%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611739591%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611743926%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611735081%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611861898%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611736304%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611730603%0f493ae6f9fd7ecd"},{"message_id":"https:\/\/updates.push.services.mozilla.com\/m\/gAAAAABd3_3jZfNX8CPC-zmz4TiHuHIi4lwoMPX6xwW1g5j0MWWgLroQrXn2BtGbYrVHxFGWUlwFkDePBufr_rukQB73reZp7qbDeGfw4zjJze5-MArWSCbWWI23lzXEzKr56XDdlYXpEJxceRbQOLMR9O7Co-n85LYG5AbmCIxIXZuqX3IGDIMB-e9YiWzQY6h0WNxbJEvm"},{"message_id":"0:1574960611749299%0f493ae6f9fd7ecd"},{"message_id":"0:1574960611730251%e609af1cf9fd7ecd"},{"message_id":"0:1574960611737114%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":3009557294868908223,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960612336027%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612340771%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612362611%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612337842%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612337996%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612351822%263106ebf9fd7ecd"},{"message_id":"0:1574960612350040%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612339381%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612335754%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612333685%263106ebf9fd7ecd"},{"message_id":"0:1574960612359030%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612400814%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612373741%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612331731%263106ebf9fd7ecd"},{"message_id":"0:1574960612342958%263106ebf9fd7ecd"},{"message_id":"0:1574960612335708%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612346644%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612340138%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612334924%e609af1cf9fd7ecd"},{"message_id":"0:1574960612334097%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":5931315229468787721,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960612866991%e609af1cf9fd7ecd"},{"message_id":"0:1574960612944706%263106ebf9fd7ecd"},{"message_id":"0:1574960612881972%e609af1cf9fd7ecd"},{"message_id":"0:1574960612894791%e609af1cf9fd7ecd"},{"message_id":"0:1574960612868314%263106ebf9fd7ecd"},{"message_id":"0:1574960612867072%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612870839%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612875975%e609af1cf9fd7ecd"},{"message_id":"0:1574960612872346%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612877916%e609af1cf9fd7ecd"},{"message_id":"0:1574960612869244%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612866346%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612869754%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612881523%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612883784%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612877014%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612867529%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612878826%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612885644%0f493ae6f9fd7ecd"},{"message_id":"0:1574960612878805%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":139624621515257652,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574960613435759%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613457076%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613457920%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613462296%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613435703%e609af1cf9fd7ecd"},{"message_id":"0:1574960613456623%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613443790%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613457934%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613464991%263106ebf9fd7ecd"},{"message_id":"0:1574960613451882%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613435610%e609af1cf9fd7ecd"},{"message_id":"0:1574960613438583%263106ebf9fd7ecd"},{"message_id":"0:1574960613444605%263106ebf9fd7ecd"},{"message_id":"0:1574960613469646%263106ebf9fd7ecd"},{"message_id":"0:1574960613455210%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613462123%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613442301%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613435746%e609af1cf9fd7ecd"},{"message_id":"0:1574960613439162%0f493ae6f9fd7ecd"},{"message_id":"0:1574960613454616%0f493ae6f9fd7ecd"}]}<--<hr/><b style='color:#FFF;font-size:14pt;' class='btn btn-primary active'>NOTIFICACIONES REALIZADAS: 643</b>
/*
 * I reckon you are using fcm push library for your push notification, if you want to send same notification to multiple users then use "registration_ids" parameter instead of "to". this tag accepts an array of strings.

ex: registration_ids:["registrationkey1","registrationkey2"].

note: limit is 100 key at a time.
 
<hr/>-->
{
    "multicast_id":8422805524148747849,"success":19,"failure":1,"canonical_ids":0,"results":
        [
        {"message_id":"0:1574959682286121%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682294775%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682321956%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682292707%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682295245%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682289780%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682288138%0f493ae6f9fd7ecd"},
        {"error":"NotRegistered"},
        {"message_id":"0:1574959682288168%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682288479%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682291469%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682289965%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682288238%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682290114%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682288620%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682292980%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682289413%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682289630%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682291347%0f493ae6f9fd7ecd"},
        {"message_id":"0:1574959682291673%0f493ae6f9fd7ecd"}
        ]
        }
        <--<hr/><hr/>-->
        {
            "multicast_id":9121396484021527278,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959682810715%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682821632%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682810684%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682817690%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682814723%263106ebf9fd7ecd"},{"message_id":"0:1574959682815687%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682811096%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959682811984%0f493ae6f9fd7ecd"},{"message_id":"https:\/\/updates.push.services.mozilla.com\/m\/gAAAAABd3_pDF1oQ4TYxWdSljhJit8HkYVJIRD06MfZ7wWBUVvD-vYVPEbElrtpEjD3Om2GXU8SshJmjLLCupu--0fBbRDDmhTQDek8GvE82i7wbXUxBSPwtEooYpYgodC0qA3oBdNyi1hwsXdGLxSt16K74evmu6A07ulmsOwasG-tZJGYPjCPBBBfnW8Y-rn-buZ_2up4_"},{"message_id":"0:1574959682817779%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682812092%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682811963%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682842763%263106ebf9fd7ecd"},{"message_id":"0:1574959682811626%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682818812%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682810208%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682812648%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682810550%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682849248%0f493ae6f9fd7ecd"},{"message_id":"0:1574959682817267%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8325423372692041507,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959683688426%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683621580%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683623375%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683637591%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683622689%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683624796%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683625261%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683623563%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683621059%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683621507%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683625159%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683622524%e609af1cf9fd7ecd"},{"message_id":"0:1574959683633001%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683625673%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683622346%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683621209%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683623735%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683731086%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683625364%0f493ae6f9fd7ecd"},{"message_id":"0:1574959683621453%e609af1cf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":7943476369522712411,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959684195666%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684204287%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684198090%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684195806%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684228918%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684232059%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684199691%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684198179%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684209654%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684196862%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684193475%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684199100%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684196382%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684202901%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684200808%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684207944%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684200136%263106ebf9fd7ecd"},{"message_id":"0:1574959684200973%e609af1cf9fd7ecd"},{"message_id":"0:1574959684200542%e609af1cf9fd7ecd"},{"message_id":"0:1574959684217308%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6273332810517315915,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959684707925%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684707054%e609af1cf9fd7ecd"},{"message_id":"0:1574959684707764%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684711386%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684740655%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684713160%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684745251%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684708864%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684711820%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684707238%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684712558%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684713947%263106ebf9fd7ecd"},{"message_id":"0:1574959684706704%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684711809%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684708103%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684709404%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684707796%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684708810%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684708126%0f493ae6f9fd7ecd"},{"message_id":"0:1574959684712905%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":3875084345853981365,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959685220817%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685230872%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685247328%263106ebf9fd7ecd"},{"message_id":"0:1574959685215070%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685220291%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685227736%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685220313%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685220747%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685217367%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685216288%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685217968%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685223445%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685215304%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685220472%e609af1cf9fd7ecd"},{"message_id":"0:1574959685219248%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685221979%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685215425%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685244941%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685222933%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685223598%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4963987506008535504,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959685703594%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685702035%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685697797%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685696411%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685698416%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685737536%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685697670%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685707858%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685714800%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685697248%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685699646%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685705979%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685697499%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685698006%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685699342%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685703444%e609af1cf9fd7ecd"},{"message_id":"0:1574959685699873%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685703709%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685706060%0f493ae6f9fd7ecd"},{"message_id":"0:1574959685704771%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":2058537766232156194,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959686194407%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686237336%e609af1cf9fd7ecd"},{"message_id":"0:1574959686189536%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686192277%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959686191825%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686258554%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686191791%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686189995%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686197015%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686195077%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686187291%e609af1cf9fd7ecd"},{"message_id":"0:1574959686196103%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686187921%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686189652%e609af1cf9fd7ecd"},{"message_id":"0:1574959686222380%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686196683%e609af1cf9fd7ecd"},{"message_id":"0:1574959686190488%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686189677%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686218940%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686192760%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":2914459677380789916,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959686705826%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686706789%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686741534%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686704476%706d4ff5f9fd7ecd"},{"message_id":"0:1574959686705769%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686718402%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686705628%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686710131%cc9b4facf9fd7ecd"},{"message_id":"0:1574959686706237%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686709877%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686709178%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686707839%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686708301%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686706123%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686706822%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686708032%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686706191%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686708082%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686725585%0f493ae6f9fd7ecd"},{"message_id":"0:1574959686715481%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":94079322109734279,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959687192840%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687197333%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687191856%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687190113%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687190156%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687194924%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687191790%e609af1cf9fd7ecd"},{"message_id":"0:1574959687190824%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687215743%263106ebf9fd7ecd"},{"message_id":"0:1574959687190134%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687214812%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687370139%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687200778%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687195064%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687191979%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687193806%263106ebf9fd7ecd"},{"message_id":"0:1574959687192988%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687192615%e609af1cf9fd7ecd"},{"message_id":"0:1574959687191061%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687197992%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8454814055686463761,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959687846532%263106ebf9fd7ecd"},{"message_id":"0:1574959687846947%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687848308%e609af1cf9fd7ecd"},{"message_id":"0:1574959687844353%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687844718%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687844147%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687842954%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959687843790%263106ebf9fd7ecd"},{"message_id":"0:1574959687843192%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687843643%e609af1cf9fd7ecd"},{"message_id":"0:1574959687858810%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687846963%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687853173%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687844549%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687842040%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687848295%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687845507%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687868704%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687844191%0f493ae6f9fd7ecd"},{"message_id":"0:1574959687843782%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":490394876018487483,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959688352379%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688348896%e609af1cf9fd7ecd"},{"message_id":"0:1574959688373803%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688345164%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688425446%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688385610%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688347012%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688347416%263106ebf9fd7ecd"},{"message_id":"0:1574959688345955%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688347236%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688345790%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688348788%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688350445%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688346389%e609af1cf9fd7ecd"},{"message_id":"0:1574959688346297%e609af1cf9fd7ecd"},{"message_id":"0:1574959688361730%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688348767%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688346463%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688352208%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688351966%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6919350283259383501,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959688870647%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688869417%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688918486%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688876204%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688866232%e609af1cf9fd7ecd"},{"message_id":"0:1574959688885902%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688865097%706d4ff5f9fd7ecd"},{"message_id":"0:1574959688868872%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688868324%e609af1cf9fd7ecd"},{"message_id":"0:1574959688869000%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688868275%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688889523%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688869893%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688872948%e609af1cf9fd7ecd"},{"message_id":"0:1574959688876790%263106ebf9fd7ecd"},{"message_id":"0:1574959688898351%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688870977%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688869010%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688895267%0f493ae6f9fd7ecd"},{"message_id":"0:1574959688870322%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":7967066242603972407,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959689377403%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689374862%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689379798%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689380603%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689419911%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689382030%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689377666%0f493ae6f9fd7ecd"},{"message_id":"https:\/\/updates.push.services.mozilla.com\/m\/gAAAAABd3_pJTbBR5mrYNN6ihJiWnrMIW630shKaMUKXg1jx-kvweHYLh81HA7OwBLpKk6yZAVL4J2NChp_SMGBrwH6ThkxXgllPBgjDvpLQS5YV1wV9RklO656Lk1X-RZDe8ZIfdpdMzn517M1ICsJXDCHT2XfUS9WVcgd0y7CWjpp3U9cuVqk5JAclA_55BEwimE-NeLRb"},{"message_id":"0:1574959689380909%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689396637%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689379095%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689391881%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689376232%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689377579%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689380808%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689378795%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689381755%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689393032%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689377680%0f493ae6f9fd7ecd"},{"message_id":"0:1574959689494135%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":2151212437942826357,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959690217531%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690221176%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690221872%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690214553%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690235789%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690215664%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690217167%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690236049%263106ebf9fd7ecd"},{"message_id":"0:1574959690215472%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690219663%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690219652%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690222721%263106ebf9fd7ecd"},{"message_id":"0:1574959690267662%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690220389%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690216036%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690282376%263106ebf9fd7ecd"},{"message_id":"0:1574959690218981%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690215885%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690216695%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690215918%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":2928521768814942712,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959690728043%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690731829%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690728922%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690747990%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690733579%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690728533%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690726549%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959690734673%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959690752758%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690731740%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690742966%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690730400%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690727276%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690740349%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690729765%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690733235%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690737476%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690733633%e609af1cf9fd7ecd"},{"message_id":"0:1574959690728997%0f493ae6f9fd7ecd"},{"message_id":"0:1574959690764223%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":860406813582527380,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959691221220%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691221402%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691219129%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691218535%e609af1cf9fd7ecd"},{"message_id":"0:1574959691221138%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691219927%e609af1cf9fd7ecd"},{"message_id":"0:1574959691229673%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691314812%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691219204%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691228005%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691232399%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691239352%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691265818%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691226692%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691221542%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691221299%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959691235234%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691230911%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691230735%e609af1c85120eb2"},{"message_id":"0:1574959691221866%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6647812458859754407,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959691823458%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692073419%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692072190%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692098154%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692093006%0f493ae6f9fd7ecd"},{"message_id":"0:1574959691824214%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692160679%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692089098%263106ebf9fd7ecd"},{"message_id":"0:1574959692104352%263106ebf9fd7ecd"},{"message_id":"0:1574959692103498%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959691823149%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692102959%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692106389%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692160392%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692089011%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692103834%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692100206%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692110337%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692090236%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692103883%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1159294806170441689,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959692609564%263106ebf9fd7ecd"},{"message_id":"0:1574959692578903%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692575486%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959692574553%e609af1cf9fd7ecd"},{"message_id":"0:1574959692573311%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959692587199%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692583047%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692593832%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692575370%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692577955%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692576036%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692584966%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692580221%263106ebf9fd7ecd"},{"message_id":"0:1574959692578832%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692634463%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692580149%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692608681%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692591751%0f493ae6f9fd7ecd"},{"message_id":"0:1574959692575631%e609af1cf9fd7ecd"},{"message_id":"0:1574959692584946%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4473146713475205584,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959693101810%e609af1cf9fd7ecd"},{"message_id":"0:1574959693099982%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693099670%706d4ff5f9fd7ecd"},{"message_id":"0:1574959693100676%e609af1cf9fd7ecd"},{"message_id":"0:1574959693101551%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693102648%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693104601%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693104828%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693098482%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693109828%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693135598%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693111563%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693103225%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693113946%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693104375%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693118995%e609af1cf9fd7ecd"},{"message_id":"0:1574959693102002%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693099577%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693109014%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693105056%263106ebf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":702550114493590578,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959693630383%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693632263%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693641606%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693629176%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693632900%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693630705%263106ebf9fd7ecd"},{"message_id":"0:1574959693631058%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693630881%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693634400%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693631446%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693630958%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693631842%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693633016%e609af1cf9fd7ecd"},{"message_id":"0:1574959693631212%e609af1cf9fd7ecd"},{"message_id":"0:1574959693638909%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959693631717%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693671743%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693634977%0f493ae6f9fd7ecd"},{"message_id":"0:1574959693631041%263106ebf9fd7ecd"},{"message_id":"0:1574959693672392%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":2695603888816737740,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959694191506%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694192213%e609af1cf9fd7ecd"},{"message_id":"0:1574959694199912%e609af1cf9fd7ecd"},{"message_id":"0:1574959694192602%263106ebf9fd7ecd"},{"message_id":"0:1574959694206875%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694218303%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694212207%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694194834%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694198241%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694190182%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694198926%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694200695%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694196068%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694196629%263106ebf9fd7ecd"},{"message_id":"0:1574959694213811%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694215852%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694191017%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694196784%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694195395%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959694277817%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":6780067799320923368,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959694735512%e609af1cf9fd7ecd"},{"message_id":"0:1574959694724963%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694752626%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959694725530%e609af1cf9fd7ecd"},{"message_id":"0:1574959694754724%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694738232%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694725627%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694726396%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694735517%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694732601%e609af1cf9fd7ecd"},{"message_id":"0:1574959694733870%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694735341%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959694780423%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694729719%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694726495%e609af1cf9fd7ecd"},{"message_id":"0:1574959694729656%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694731153%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694854266%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694727220%0f493ae6f9fd7ecd"},{"message_id":"0:1574959694733961%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8262990507572165487,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959695354011%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695365402%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695359760%e609af1cf9fd7ecd"},{"message_id":"0:1574959695356364%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695354425%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695376755%e609af1cf9fd7ecd"},{"message_id":"0:1574959695355202%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695354274%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695356519%e609af1cf9fd7ecd"},{"message_id":"0:1574959695368026%e609af1cf9fd7ecd"},{"message_id":"0:1574959695357271%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695361461%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695352962%e609af1cf9fd7ecd"},{"message_id":"0:1574959695354652%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695394818%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695353565%e609af1cf9fd7ecd"},{"message_id":"0:1574959695361044%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695361645%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695355167%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695360953%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":5342523213778759545,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959695874094%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695855896%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695857856%e609af1cf9fd7ecd"},{"message_id":"0:1574959695862462%e609af1cf9fd7ecd"},{"message_id":"0:1574959695867906%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695850905%706d4ff5f9fd7ecd"},{"message_id":"0:1574959695851389%263106ebf9fd7ecd"},{"message_id":"0:1574959695853769%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695869104%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695923462%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695860668%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695855669%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695850607%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695871482%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695876498%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695850820%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695851874%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695892589%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695891544%0f493ae6f9fd7ecd"},{"message_id":"0:1574959695869152%b5e1d03cf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":7192424688247202268,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959696374407%263106ebf9fd7ecd"},{"message_id":"0:1574959696372456%ca718e0df9fd7ecd"},{"message_id":"0:1574959696382607%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696383246%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696373261%b5e1d03cf9fd7ecd"},{"message_id":"0:1574959696375903%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696371881%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696420122%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696374800%263106ebf9fd7ecd"},{"message_id":"0:1574959696375687%e609af1cf9fd7ecd"},{"message_id":"0:1574959696373673%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696390232%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696381500%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696403671%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696373767%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696405733%706d4ff5f9fd7ecd"},{"message_id":"0:1574959696380008%e609af1cf9fd7ecd"},{"message_id":"0:1574959696384770%3b6c2a24f9fd7ecd"},{"message_id":"0:1574959696395648%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696379064%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":4709729522659153272,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959696885059%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696881371%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696892890%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696882495%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696880307%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696869413%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696870760%e609af1cf9fd7ecd"},{"message_id":"0:1574959696877921%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696868780%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696876092%e609af1cf9fd7ecd"},{"message_id":"0:1574959696870139%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696870943%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696880874%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696891727%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696876822%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696870705%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696879296%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696873903%e609af1cf9fd7ecd"},{"message_id":"0:1574959696867173%0f493ae6f9fd7ecd"},{"message_id":"0:1574959696876290%e609af1cf9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":1464777477163702282,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959697395707%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697371770%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697369228%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697360795%e609af1cf9fd7ecd"},{"message_id":"0:1574959697359884%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697369333%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697366591%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697367746%e609af1cf9fd7ecd"},{"message_id":"0:1574959697364029%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697362930%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697371538%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697397170%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697368480%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697360322%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697369402%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697379838%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697360858%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697363029%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697361820%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697417787%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":3969444976179292650,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959697852429%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697853546%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697858570%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697862098%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697852246%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697853261%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697860455%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697881555%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697855158%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697864994%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697858583%e609af1cf9fd7ecd"},{"message_id":"0:1574959697908956%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697891325%e609af1cf9fd7ecd"},{"message_id":"0:1574959697853330%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697856974%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697853359%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697860656%263106ebf9fd7ecd"},{"message_id":"0:1574959697874961%0f493ae6f9fd7ecd"},{"message_id":"0:1574959697856098%263106ebf9fd7ecd"},{"message_id":"0:1574959697984483%0f493ae6f9fd7ecd"}]}<--<hr/><hr/>-->{"multicast_id":8544252433547422467,"success":20,"failure":0,"canonical_ids":0,"results":[{"message_id":"0:1574959698425710%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698438416%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698501694%263106ebf9fd7ecd"},{"message_id":"0:1574959698445659%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698445447%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698435514%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698455387%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698432907%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698458782%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698430387%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698437146%263106ebf9fd7ecd"},{"message_id":"0:1574959698431788%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698425676%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698425618%263106ebf9fd7ecd"},{"message_id":"0:1574959698434696%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698427763%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698432945%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698448063%0f493ae6f9fd7ecd"},{"message_id":"0:1574959698459579%e609af1cf9fd7ecd"},{"message_id":"0:1574959698465688%0f493ae6f9fd7ecd"}]}<--<hr/><b style='color:#FFF;font-size:14pt;' class='btn btn-primary active'>NOTIFICACIONES REALIZADAS: 604</b>