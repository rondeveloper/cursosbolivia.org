<?php

/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
//include_once '../../librerias/correo/class.phpmailer.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


//Load Composer's autoloader
require '../../librerias/phpmailer/vendor/autoload.php';


if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

$id_grupo = post('id_grupo');

/* participante */
$rqddp1 = query("SELECT id,correo_contacto,id_grupo,id_curso FROM cursos_proceso_registro WHERE (id_grupo='$id_grupo' OR id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo')) AND sw_pago_enviado='0' ORDER BY id DESC LIMIT 150 ");
while($rqddp2 = mysql_fetch_array($rqddp1)){
    
    $id_proceso_de_registro = $rqddp2['id'];
    $email_a_enviar = trim($rqddp2['correo_contacto']);

    if (strlen($email_a_enviar) <= 5) {
        echo "Correo invalido!";
        continue;
    }

    $rqc1 = query("SELECT titulo,desc_1,desc_2,desc_3,desc_4,desc_5 FROM cursos_agrupaciones WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);
    $titulo_curso = $rqc2['titulo'];
    $desc_1_curso = $rqc2['desc_1'];
    $desc_2_curso = $rqc2['desc_2'];
    $desc_3_curso = $rqc2['desc_3'];
    $desc_4_curso = $rqc2['desc_4'];
    $desc_5_curso = $rqc2['desc_5'];
    $costo_curso = 0;

    /* participante */
    $rqdp1 = query("SELECT id,nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_de_registro' ORDER BY id ASC limit 1 ");
    $rqdp2 = mysql_fetch_array($rqdp1);
    $nombre_participante = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
    $id_participante = $rqdp2['id'];

    if($rqddp2['id_grupo']!='0'){
        $url_pago = 'https://cursos.bo/registro-grupo-p3c/'.md5('idr-' . $id_proceso_de_registro).'/'.$id_proceso_de_registro.'.html';
    }else{
        $url_pago = 'https://cursos.bo/registro-curso-p5c/'.md5('idr-' . $id_proceso_de_registro).'/'.$id_proceso_de_registro.'.html';
    }

    $htm = '
    <p>
    Estimad@ ' . $nombre_participante . '
    <br/>
    Para completar el proceso de inscripci&oacute;n al curso "' . $titulo_curso . '", es necesario que se haga el reporte de pago respectivo al curso, una vez verificado el pago se le anviar&aacute; los accesos a los cursos virtuales donde tendra un tiempo de 2 a 8 semanas para completarlos y obtener los certificados correspondientes.
    <br/>
    <table border="1" cellpadding="5" style="width:80%;margin:20px auto">
    <tr>
    <td colspan="2">' . $titulo_curso . '</td>
    </tr>
    <tr>
    <td>PARTICIPANTE:</td>
    <td>' . $nombre_participante . '</td>
    </tr>
    <tr>
    <td>CURSOS:</td>
    <td>';

    $auxcnt_cursos = 0;
    $auxcnt_total = 0;
    $rqcaap1 = query("SELECT titulo,costo FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_de_registro')  ");
    while($rqcaap2 = mysql_fetch_array($rqcaap1)){
        $auxcnt_total += (int)$rqcaap2['costo'];
        $auxcnt_cursos++;
        $nombre_curso = $rqcaap2['titulo'];
        $htm .= $nombre_curso.' <br>';
    }

    switch ($auxcnt_cursos) {
        case 1:
            $costo_curso = $auxcnt_total-(($auxcnt_total/100)*$desc_1_curso);
            break;
        case 2:
            $costo_curso = $auxcnt_total-(($auxcnt_total/100)*$desc_2_curso);
            break;
        case 3:
            $costo_curso = $auxcnt_total-(($auxcnt_total/100)*$desc_3_curso);
            break;
        case 4:
            $costo_curso = $auxcnt_total-(($auxcnt_total/100)*$desc_4_curso);
            break;
        default:
            $costo_curso = $auxcnt_total-(($auxcnt_total/100)*$desc_5_curso);
            break;
    }

    $htm .= '</td>
    </tr>
    <tr>
    <td>COSTO:</td>
    <td>' . $costo_curso . ' Bs.</td>
    </tr>
    <tr>
    </table>
    <div style="text-align:center;padding-bottom:20px">Puede realizar el pago a cualquiera de las siguientes cuentas:<br><span style="font-size:11pt">
                                                  <b>BANCO UNION A NOMBRE DE NEMABOL 1-00000-21553173</b>
                                                  <br>
                                                  BANCO DE CREDITO BCP 201-50853966-3-23 Evangelina Sardon Tintaya
                                                  <br>
                                                  BANCO SOL 1166531-000-001 Evangelina Sardon Tintaya
                                                  <br>
                                                  BANCO UNION 1-14318998 Evangelina Sardon Tintaya
                                                  </span>
                                                  <br>Tambi&eacute;n se aceptan transferencias mediante <span style="color:#1b36bb;font-weight:bold">Tigo Money</span> al n&uacute;mero: &nbsp; <span style="font-size:12pt;color:#1b36bb;font-weight:bold">69714008</span><br></div>
    <br/>
    <div style="text-align:center;padding-bottom:30px"><a href="'.$url_pago.'" style="background:#4eb94e;padding:10px 25px;border-radius:7px;color:#fff;font-size:15pt;text-decoration:none;border:1px solid #e2d7d7" target="_blank">REPORTAR PAGO REALIZADO</a><br><br>Ingresa al siguiente enlace para reportar el pago: <a href="'.$url_pago.'">'.$url_pago.'</a></div>
    <br/>
    <br/>
    Puede comunicarse con nosotros llamando al 69713008 l&iacute;nea Cursos.bo, &oacute; escribirnos al correo: ventas@nemabol.com
    <br/>
    Esperamos que nuestro servicio le sea de utilidad, ante cualquier duda o consulta no dude en comunicarse con nosotros.
    <br/>
    <br/>
    Saludos cordiales
    <br/>
    <br/>
    </p>
    ';

    $asunto = utf8_decode($titulo_curso . ' - solicitud de reporte de pago');
    $subasunto = ($titulo_curso);

    $contenido_correo = "<div style='font-family:arial;line-height: 2;color:#333;'>"
            . "<h2 style='text-align:center;background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>$subasunto</h2>";
    $contenido_correo .= "<center><a href='https://cursos.bo/'><img style='background:#2fbb20;width:230px;padding:1px;border:1px solid gray;border-radius:5px;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>"
            . $htm;
    $contenido_correo .= "<h3 style='background:#2fbb20;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>"
            . "</div><hr/>";


    $subject = $asunto;
    $body = $contenido_correo;

    //*echo $contenido_correo;exit;

    if (strlen($email_a_enviar) > 3) {
        if (isCorreoValido($email_a_enviar)) {
            try {
                    $mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas
                    //Server settings
                    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'cursos.bo';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'sistema@cursos.bo';                 // SMTP username
                    $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                                    // TCP port to connect to
                    //Recipients
                    $mail->setFrom('sistema@cursos.bo', 'Cursos BOLIVIA');
                    $mail->addAddress($email_a_enviar);     // Add a recipient

                    /* Content */
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body = $body;

                    $mail->Send(); //Enviar
                    //return true;
            } catch (phpmailerException $e) {
                    echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
                    //return false;
            }            
            echo "<i>Enviado a $email_a_enviar.</i><br>";
            logcursos('Envio de solicitud de pago ['.$correo.']', 'partipante-proceso', 'participante', $id_participante);
        }else{
            echo "<i>INCORRECTO [$email_a_enviar]</i><br>";
        }
    }else{
        echo "<i>INCORRECTO [$email_a_enviar]</i><br>";
    }
}

echo "<b>SOLICITUDES ENVIADAS CORRECTAMENTE!</b>";

function isCorreoValido($dat) {
    $array_correos_excepciones = array(
        'willans79@gmail.com',
        'victorvg@totalradios.com',
        'fmamani@dicsabol.com'
    );
    if (filter_var(trim($dat), FILTER_VALIDATE_EMAIL) || (in_array(trim($dat), $array_correos_excepciones))) {
        if (comprobar_email($dat)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function comprobar_email($email) {
    $mail_correcto = 0;
    //compruebo unas cosas primeras 
    if ((strlen($email) >= 6) && (substr_count($email, "@") == 1) && (substr($email, 0, 1) != "@") && (substr($email, strlen($email) - 1, 1) != "@")) {
        if ((!strstr($email, "'")) && (!strstr($email, "\"")) && (!strstr($email, "\\")) && (!strstr($email, "\$")) && (!strstr($email, " "))) {
            //miro si tiene caracter . 
            if (substr_count($email, ".") >= 1) {
                //obtengo la terminacion del dominio 
                $term_dom = substr(strrchr($email, '.'), 1);
                //compruebo que la terminación del dominio sea correcta 
                if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, "@"))) {
                    //compruebo que lo de antes del dominio sea correcto 
                    $antes_dom = substr($email, 0, strlen($email) - strlen($term_dom) - 1);
                    $caracter_ult = substr($antes_dom, strlen($antes_dom) - 1, 1);
                    if ($caracter_ult != "@" && $caracter_ult != ".") {
                        $mail_correcto = 1;
                    }
                }
            }
        }
    }
    if ($mail_correcto) {
        return true;
    } else {
        return false;
    }
}