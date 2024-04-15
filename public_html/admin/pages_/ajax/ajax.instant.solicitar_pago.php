<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

/* administrador */
$rqddad1 = query("SELECT email FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqddad2 = fetch($rqddad1);
$correo_administrador = $rqddad2['email'];

$id_proceso_de_registro = post('id_proceso_registro');
$email_a_enviar = trim(str_replace(' ', '', post('correo')));

if (strlen($email_a_enviar) <= 5) {
    echo "Correo invalido!";
    exit;
}

/* curso */
$rqdcr1 = query("SELECT id_curso FROM cursos_proceso_registro WHERE id='$id_proceso_de_registro' ");
$rqdcr2 = fetch($rqdcr1);
$id_curso = $rqdcr2['id_curso'];
$rqc1 = query("SELECT titulo,costo,sw_fecha2,sw_fecha3,fecha2,fecha3,costo2,costo3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$titulo_curso = $rqc2['titulo'];
$costo_curso = $rqc2['costo'];
if ($rqc2['sw_fecha2'] == '1' && (time() <= strtotime($rqc2['fecha2']))) {
    $costo_curso = $rqc2['costo2'];
}
if ($rqc2['sw_fecha3'] == '1' && (time() <= strtotime($rqc2['fecha3']))) {
    $costo_curso = $rqc2['costo3'];
}

/* cuenta bancaria */
$rqdcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
$rqdcb2 = fetch($rqdcb1);
$banco = strtoupper($rqdcb2['nombre_banco']);
$numero_cuenta = $rqdcb2['numero_cuenta'];
$titular = strtoupper($rqdcb2['titular']);


/* participante */
$rqdp1 = query("SELECT id,nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_de_registro' ORDER BY id ASC limit 1 ");
$rqdp2 = fetch($rqdp1);
$nombre_participante = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
$id_participante = $rqdp2['id'];

$url_pago = $dominio.'registro-curso-p5c/'.md5('idr-' . $id_proceso_de_registro).'/'.$id_proceso_de_registro.'.html';

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
Videos demo ingreso a nuestra plataforma en facebook https://www.facebook.com/cursosnemabol/videos/254755995895069/
<br><br>';

$htm = '
<p>
Estimad@ ' . $nombre_participante . '
<br/>
Para completar el proceso de inscripci&oacute;n al curso "' . $titulo_curso . '", es necesario que se haga el reporte de pago respectivo al curso.
<br/>
<table border="1" cellpadding="3" style="width:80%;margin:20px auto">
<tr>
<td>CURSO:</td>
<td>' . $titulo_curso . '</td>
</tr>
<tr>
<td>PARTICIPANTE:</td>
<td>' . $nombre_participante . '</td>
</tr>
<tr>
<td>COSTO:</td>
<td>' . $costo_curso . ' Bs.</td>
</tr>
<tr>
</table>
<div style="text-align:center;padding-bottom:20px">Puede realizar el pago a la siguiente cuenta:<br>
                                              <span style="font-size:14pt">
                                              <b>'.$banco.' '.$numero_cuenta.' A NOMBRE DE '.$titular.'</b>
                                              </span>
                                              <br>Tambi&eacute;n se aceptan transferencias mediante <span style="color:#1b36bb;font-weight:bold">Tigo Money</span> al n&uacute;mero: &nbsp; <span style="font-size:12pt;color:#1b36bb;font-weight:bold">69714008</span><br></div>
<br/>
'.$texto_informacion_para_pago.'
<div style="text-align:center;padding-bottom:30px"><a href="'.$url_pago.'" style="background:#4eb94e;padding:10px 25px;border-radius:7px;color:#fff;font-size:15pt;text-decoration:none;border:1px solid #e2d7d7" target="_blank">REPORTAR PAGO</a><br><br><a href="'.$url_pago.'" target="_blank">'.$url_pago.'</a></div>
<br/>
Puede comunicarse con nosotros llamando al 69713008 l&iacute;nea '.$___nombre_del_sitio.', &oacute; escribirnos al correo: ventas@nemabol.com
<br/>
Esperamos que nuestro servicio le sea de utilidad, ante cualquier duda o consulta no dude en comunicarse con nosotros.
<br/>
<br/>
Saludos cordiales
<br/>
<br/>
</p>
';

//$enviar_a = "brayan.desteco@gmail.com";
$enviar_a = $email_a_enviar;

$asunto = ($titulo_curso . ' - reporte de pago');
$subasunto = ($titulo_curso);
$contenido_correo = platillaEmailUno($htm,$subasunto,'[REMM-CORREO]','[REMM-UNSUB]', 'Usuario');

$correo = $enviar_a;
$subject = $asunto;
$body = $contenido_correo;

$array_correos_a_enviar = explode(",", $correo);
foreach ($array_correos_a_enviar as $correo_a_enviar) {
    SISTsendEmailFULL($correo_a_enviar, $subject, str_replace(array('[REMM-CORREO]','[REMM-UNSUB]'),array($correo_a_enviar,urlUnsubscribe($correo_a_enviar)),$body),array($correo_administrador),'');
}

logcursos('Envio de solicitud de pago ['.$correo.']', 'partipante-proceso', 'participante', $id_participante);

echo "<b>SOLICITUD ENVIADA CORRECTAMENTE!</b>";

