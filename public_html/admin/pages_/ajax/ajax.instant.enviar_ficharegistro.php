<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (isset_administrador()) {
    
    $id_proceso_de_registro = post('sendficha_id_proceso_registro');
    $email_a_enviar = trim(str_replace(' ','',post('sendficha_correo')));

    if (strlen($email_a_enviar) <= 5) {
        echo "Correo invalido!";
        exit;
    }

    $htm = '
<p>
Estimad@
<br/>
Se le hace el env&iacute;o de la ficha de registro, para el curso al cual usted realizo una inscripci&oacute;n. 
<br/>
<br/>
Saludos cordiales
<br/>
<br/>
</p>
';

    //$enviar_a = "brayan.desteco@gmail.com";
    $enviar_a = $email_a_enviar;

    $asunto = ('FICHA DE REGISTRO PARA CURSO');
    $subasunto = ('FICHA DE REGISTRO PARA CURSO');
    $contenido_correo = platillaEmailUno($htm,$subasunto,'[REMM-CORREO]','[REMM-UNSUB]', 'Usuario');

    /* variables para los datos del archivo */
    $nombrearchivo = "ficha-de-registro-$id_proceso_de_registro.pdf";
    $url_archivo = $dominio.encrypt('registro-participantes-curso/' . $id_proceso_de_registro . '/pdf').".impresion";

    $archivo_cont = file_get_contents($url_archivo);

    $correo = $enviar_a;
    $subject = $asunto;
    $body = $contenido_correo;

    $nuevoarchivo = fopen($nombrearchivo, "w+");
    fwrite($nuevoarchivo, $archivo_cont);
    fclose($nuevoarchivo);
    
    $array_correos_a_enviar = explode(",", $correo);
    foreach ($array_correos_a_enviar as $correo_a_enviar) {
        SISTsendEmailFULL($correo_a_enviar, $subject, str_replace(array('[REMM-CORREO]','[REMM-UNSUB]'),array($correo_a_enviar,urlUnsubscribe($correo_a_enviar)),$body),'',array(array($nombrearchivo,$nombrearchivo)));
    }

    unlink($nombrearchivo);

    echo "<b>FICHA ENVIADA CORRECTAMENTE</b>";
} else {
    echo "Denegado!";
}
