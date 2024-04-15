<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_material = post('id_material');

/* material */
$rqdrm1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id='$id_material' ORDER BY id DESC limit 1 ");
$rqdrm2 = fetch($rqdrm1);
$id_onlinecourse = $rqdrm2['id_onlinecourse'];
$id_leccion = $rqdrm2['id_leccion'];
$nombre_material = $rqdrm2['nombre'];
$nombre_fisico_material = $rqdrm2['nombre_fisico'];
$formato_archivo_material = $rqdrm2['formato_archivo'];

/* online course */
$rqdloc1 = query("SELECT titulo FROM cursos_onlinecourse WHERE id='$id_onlinecourse' ORDER BY id DESC limit 1 ");
$rqdloc2 = fetch($rqdloc1);
$nombre_curso = $rqdloc2['titulo'];

/* leccion */
$rqdl1 = query("SELECT titulo FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
$rqdl2 = fetch($rqdl1);
$nombre_leccion = $rqdl2['titulo'];

/* participantes */
$array_correos_a_enviar = array();
$rqdp1 = query("SELECT p.correo FROM cursos_participantes p INNER JOIN cursos_onlinecourse_acceso a ON a.id_usuario=p.id_usuario INNER JOIN cursos c ON p.id_curso=c.id WHERE a.id_onlinecourse='$id_onlinecourse' AND a.sw_acceso='1' AND c.estado IN (1,2) AND p.estado='1' AND p.correo LIKE '%@%' ");
while ($rqdp2 = fetch($rqdp2)) {
    $correo_participante = $rqdp2['correo'];
    array_push($array_correos_a_enviar, $correo_participante);
}

/* correo */
$contenido_correo = "<h2 style='text-align:center;background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>$nombre_material</h2>";
$contenido_correo .= "<center><a href='".$dominio."'><img style='width:230px;padding:1px;border:1px solid gray;border-radius:5px;background:#31b312;' src='".$dominio_www."contenido/alt/logotipo-v3.png'/></a></center>";
$contenido_correo .= "<p>Saludos, se le hace el env&iacute;o en forma digital adjuntado en este correo del material adicional del curso al cual usted tuvo participaci&oacute;n.</p>";
$contenido_correo .= "<b>Curso:</b> " . $nombre_curso . "<br />";
$contenido_correo .= "<b>Lecci&oacute;n:</b> " . $nombre_leccion . "<br />";
$contenido_correo .= "<b>Material:</b> " . $nombre_material . "<br />";

$rqda1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
$contenido_correo .= "<table style='width:80%;'>";
$cnt = 1;
while ($rqda2 = fetch($rqda1)) {
    $dir_file = '../../archivos/material/' . $rqda2['nombre_fisico'];
    $contenido_correo .= "<tr>";
    $contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $cnt++ . "</td>";
    $contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $rqda2['nombre_digital'] . "</td>";
    $contenido_correo .= "</tr>";
}
$contenido_correo .= "</table>";
$contenido_correo .= "<br/><br/><p>Servicio de mensajeria ".$___nombre_del_sitio."</p>";
$contenido_correo .= "<h3 style='background:#31b312;color:#FFF;border-radius:5px;padding:5px;'>Gracias por confiar en nosotros</h3>";

if(strlen($nombre_material)<5){
    echo "Error nombre material.";
    exit;
}

/* envio de correo */
$subject = ($nombre_material);
$body = $contenido_correo;

/* datos de correo notificador */
$id_correo_notificador = 1;
$rqdcn1 = query("SELECT correo,user,password,cifrado,puerto,host,nombre_remitente FROM notificadores_de_correo WHERE id='$id_correo_notificador' LIMIT 1 ");
$rqdcn2 = fetch($rqdcn1);
$___datamail_From = $rqdcn2['correo'];
$___datamail_Username = $rqdcn2['user'];
$___datamail_Password = $rqdcn2['password'];
$___datamail_SMTPSecure = $rqdcn2['cifrado'];
$___datamail_Port = $rqdcn2['puerto'];
$___datamail_Host = $rqdcn2['host'];
$___datamail_NameFrom = $rqdcn2['nombre_remitente'];

try {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = $___datamail_Host;
    $mail->SMTPAuth = true;
    $mail->Username = $___datamail_Username;
    $mail->Password = $___datamail_Password;
    $mail->SMTPSecure = $___datamail_SMTPSecure;
    $mail->Port = $___datamail_Port;
    $mail->setFrom($___datamail_From, $___datamail_NameFrom);
    $mail->addAddress($___datamail_From);
    /* correos adjuntos ocultos */
    $cnt_aux = 0;
    foreach ($array_correos_a_enviar as $correo_a_enviar) {
        if (emailValido($correo_a_enviar)) {
            $mail->addBCC($correo_a_enviar);
            $cnt_aux++;
        } else {
            echo "<br/>[$correo_a_enviar] no valido.";
        }
    }
    /* Content */
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    /* archivos */
    $rqdaa1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
    while ($rqda2 = fetch($rqdaa1)) {
        $dir_file = '../../archivos/material/' . $rqda2['nombre_fisico'];
        $extension = pathinfo($rqda2['nombre_fisico'], PATHINFO_EXTENSION);
        $name_file = $rqda2['nombre_digital'].'.'.$extension;
        $mail->AddAttachment($dir_file, $name_file);
    }
    if ($cnt_aux > 0) {
        $mail->Send();
        logcursos('Envio de material digital a participantes [M:'.$id_material.']', 'curso-material', 'curso', $id_curso);
        echo '<div class="alert alert-success">
  <strong>EXITO</strong> material enviado correctamente ('.$cnt_aux.' envios).
</div>
';
    } else {
        echo "Sin correo a enviar";
    }
} catch (phpmailerException $e) {
    echo "Message:: " . $e->errorMessage();
}
