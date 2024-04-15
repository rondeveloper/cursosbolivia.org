<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* datos de configuracion */
$img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');

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
while ($rqdp2 = fetch($rqdp1)) {
    $correo_participante = $rqdp2['correo'];
    array_push($array_correos_a_enviar, $correo_participante);
}


/* correo */
$contenido_correo = "<p>Saludos<br>Se le hace el env&iacute;o en forma digital adjuntado en este correo del material adicional del curso al cual usted tuvo participaci&oacute;n.</p>";
$contenido_correo .= "<b>Curso:</b> " . $nombre_curso . "<br />";
$contenido_correo .= "<b>Lecci&oacute;n:</b> " . $nombre_leccion . "<br />";
$contenido_correo .= "<b>Material:</b> " . $nombre_material . "<br />";

$contenido_correo .= "<br><br><table style='width:100%;'>";
$dir_file = '../../archivos/cursos/' . $nombre_fisico_material;
$contenido_correo .= "<tr>";
$contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . 1 . "</td>";
$contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $formato_archivo_material. "</td>";
$contenido_correo .= "<td style='border:1px solid gray;padding:7px;'>" . $nombre_material . "</td>";
$contenido_correo .= "</tr>";

$contenido_correo .= "</table>";

$contenido_correo = platillaEmailUno($contenido_correo,$nombre_material,'[REMM-CORREO]','[REMM-UNSUB]', 'Usuario');



/* envio de correo */
$subject = ($nombre_material." - ".$nombre_curso);
$body = $contenido_correo;

$cnt_aux = 0;
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    /* archivo */
    $dir_file = '../../archivos/cursos/' . $nombre_fisico_material;
    $extension = pathinfo($nombre_fisico_material, PATHINFO_EXTENSION);
    $name_file = limpiar_enlace($nombre_material).'.'.$extension;
    SISTsendEmailFULL($correo_a_enviar, $subject, str_replace(array('[REMM-CORREO]','[REMM-UNSUB]'),array($correo_a_enviar,urlUnsubscribe($correo_a_enviar)),$body),'',array(array($dir_file,$name_file)));
    $cnt_aux++;
}

logcursos('Envio de material digital de curso virtual a participantes [ID:'.$id_material.']['.$cnt_aux.' envios]', 'curso-virtual-material', 'curso-virtual', $id_onlinecourse);
echo '<div class="alert alert-success">
    <strong>EXITO</strong> material enviado correctamente ('.$cnt_aux . ' envios).
</div>
';
