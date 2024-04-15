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
if (num_rows($rqdc1) == 0) {
    echo "ERROR";
    exit;
}
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo'];
$id_material = $rqdc2['id_material'];

/* material */
$rqdm1 = query("SELECT * FROM cursos_material WHERE id='$id_material' LIMIT 1 ");
$rqdm2 = fetch($rqdm1);
$nombre_material = $rqdm2['nombre_material'];
$estado_material = $rqdm2['estado'];

if ($estado_material !== '1') {
    echo "MATERIAL DES-HABILITADO";
    exit;
}

/* participantes */
$array_correos_a_enviar = array();
$rqdp1 = query("SELECT correo FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' AND correo LIKE '%@%' ");
while ($rqdp2 = fetch($rqdp2)) {
    $correo_participante = $rqdp2['correo'];
    array_push($array_correos_a_enviar, $correo_participante);
}

/* correo */
$contenido_correo = "<p>Saludos, se le hace el env&iacute;o en forma digital adjuntado en este correo del material adicional del curso al cual usted tuvo participaci&oacute;n.</p>";
$contenido_correo .= "<b>Curso:</b> " . $nombre_curso . "<br />";
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


$contenido_correo = platillaEmailUno($contenido_correo,$nombre_material,'[REMM-CORREO]','[REMM-UNSUB]', 'Usuario');

if(strlen($nombre_material)<5){
    echo "Error nombre material.";
    exit;
}

/* envio de correo */
$subject = ($nombre_material);
$body = $contenido_correo;


/* archivos */
$arr_archivos = array();
$rqdaa1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material' ");
while ($rqda2 = fetch($rqdaa1)) {
    $dir_file = '../../archivos/material/' . $rqda2['nombre_fisico'];
    $extension = pathinfo($rqda2['nombre_fisico'], PATHINFO_EXTENSION);
    $name_file = $rqda2['nombre_digital'].'.'.$extension;
    array_push($arr_archivos,array($dir_file,$name_file));
}
$cnt_aux = 0;
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    if (emailValido($correo_a_enviar)) {
        SISTsendEmailFULL($correo_a_enviar, $subject, str_replace(array('[REMM-CORREO]','[REMM-UNSUB]'),array($correo_a_enviar,urlUnsubscribe($correo_a_enviar)),$body),'',$arr_archivos);
        $cnt_aux++;
    } else {
        echo "<br/>[$correo_a_enviar] no valido.";
    }
}

if ($cnt_aux > 0) {
    logcursos('Envio de material digital a participantes [M:'.$id_material.']', 'curso-material', 'curso', $id_curso);
    echo '<div class="alert alert-success">
<strong>EXITO</strong> material enviado correctamente ('.$cnt_aux.' envios).
</div>
';
} else {
    echo "Sin correo a enviar";
}
