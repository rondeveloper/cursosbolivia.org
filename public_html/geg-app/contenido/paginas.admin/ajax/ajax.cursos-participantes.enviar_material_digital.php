<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

//Load Composer's autoloader
require '../../librerias/phpmailer/vendor/autoload.php';

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_curso = post('id_curso');

/* curso */
$rqdc1 = query("SELECT titulo,id_material FROM cursos WHERE id='$id_curso' AND id_material<>'0' ");
if (mysql_num_rows($rqdc1) == 0) {
    echo "ERROR";
    exit;
}
$rqdc2 = mysql_fetch_array($rqdc1);
$nombre_curso = $rqdc2['titulo'];
$id_material = $rqdc2['id_material'];

/* material */
$rqdm1 = query("SELECT * FROM cursos_material WHERE id='$id_material' LIMIT 1 ");
$rqdm2 = mysql_fetch_array($rqdm1);
$nombre_material = $rqdm2['nombre_material'];
$estado_material = $rqdm2['estado'];

if ($estado_material !== '1') {
    echo "MATERIAL DES-HABILITADO";
    exit;
}

/* participantes */
$array_correos_a_enviar = array();
$rqdp1 = query("SELECT correo FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' AND correo LIKE '%@%' ");
while ($rqdp2 = mysql_fetch_array($rqdp2)) {
    $correo_participante = $rqdp2['correo'];
    array_push($array_correos_a_enviar, $correo_participante);
}

/* correo */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>$nombre_material</h2>";
$contenido_correo .= "<center><a href='https://cursos.bo'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='https://cursos.bo/contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= "<p>Saludos, se le hace el env&iacute;o en forma digital adjuntado en este correo del material adicional del curso al cual usted tuvo participaci&oacute;n.</p>";
$contenido_correo .= "<b>Curso:</b> " . $nombre_curso . "<br />";
$contenido_correo .= "<b>Material:</b> " . $nombre_material . "<br />";

$rqda1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
$contenido_correo .= "<table style='width:80%;'>";
$cnt = 1;
while ($rqda2 = mysql_fetch_array($rqda1)) {
    $dir_file = '../../archivos/material/' . $rqda2['nombre_fisico'];
    $contenido_correo .= "<tr>";
    $contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $cnt++ . "</td>";
    $contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $rqda2['nombre_digital'] . "</td>";
    $contenido_correo .= "</tr>";
}
$contenido_correo .= "</table>";
$contenido_correo .= "<br/><br/><p>Servicio de mensajeria Cursos.BO</p>";
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>";

if(strlen($nombre_material)<5){
    echo "Error nombre material.";
    exit;
}

/* envio de correo */
$subject = utf8_decode($nombre_material);
$body = $contenido_correo;
try {
    $mail->Host = 'cursos.bo';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'sistema@cursos.bo';                 // SMTP username
    $mail->Password = 'Pw4w3BXpZ$5';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;

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
    $mail->setFrom('sistema@cursos.bo', 'CURSOS.BO');
    $mail->addAddress("sistema@cursos.bo");     // Add a recipient

    /* correos adjuntos ocultos */
    $cnt_aux = 0;
    foreach ($array_correos_a_enviar as $correo_a_enviar) {
        if (isCorreoValido($correo_a_enviar)) {
            $mail->addBCC($correo_a_enviar);
            $cnt_aux++;
        } else {
            echo "<br/>[$correo_a_enviar] no valido.";
        }
    }

    /* Content */
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $body;

    /* archivos */
    $rqdaa1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
    while ($rqda2 = mysql_fetch_array($rqdaa1)) {
        $dir_file = '../../archivos/material/' . $rqda2['nombre_fisico'];
        $extension = pathinfo($rqda2['nombre_fisico'], PATHINFO_EXTENSION);
        $name_file = $rqda2['nombre_digital'].'.'.$extension;
        $mail->AddAttachment($dir_file, $name_file); //adjuntamos archivo
    }

    if ($cnt_aux > 0) {
        $mail->Send(); //Enviar
        logcursos('Envio de material digital a participantes [M:'.$id_material.']', 'curso-material', 'curso', $id_curso);
        echo '<div class="alert alert-success">
  <strong>EXITO</strong> material enviado correctamente ('.$cnt_aux.' envios).
</div>
';
    } else {
        echo "Sin correo a enviar";
    }
} catch (phpmailerException $e) {
    echo "Message:: " . $e->errorMessage(); //Mensaje de error si se produciera.
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
