<?php
/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* datos de configuracion */
$img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* recepcion de datos POST */
$id_curso = post('id_curso');

$id_participante = post('id_participante');

/* datos participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
if ($rqdp2['id_usuario'] == '0') {
    $ci_participante = $rqdp2['ci'];
    $nombres_participante = $rqdp2['nombres'];
    $apellidos_participante = $rqdp2['apellidos'];
    $email_participante = $rqdp2['correo'];
    $celular_participante = $rqdp2['celular'];
    $fecha_registro = date("Y-m-d");
    if(strlen($ci_participante)>4){
        $password = substr($ci_participante,0,4).strtolower(substr("ABCDEFGHJKLMNPQRSTUVWXYZ",rand(0,23),1));
    }else{
        $password = substr(md5(rand(9, 999)), 2, 5);
    }

    /* creacion de usuario */
    $rqvpc1 = query("SELECT id,password FROM cursos_usuarios WHERE ci='$ci_participante' AND ci<>'' AND email='$email_participante' ");
    if (num_rows($rqvpc1) == 0) {
        query("INSERT INTO cursos_usuarios(
                       nombres, 
                       apellidos, 
                       ci, 
                       email, 
                       celular, 
                       password, 
                       sw_docente, 
                       fecha_registro, 
                       estado
                       ) VALUES (
                       '$nombres_participante',
                       '$apellidos_participante',
                       '$ci_participante',
                       '$email_participante',
                       '$celular_participante',
                       '$password',
                       '0',
                       '$fecha_registro',
                       '1'
                       )");
        $id_usuario = insert_id();
        logcursos('Creacion y asignacion de usuario [U:' . $id_usuario . ']', 'partipante-edicion', 'participante', $id_participante);
    } else {
        $rqvpc2 = fetch($rqvpc1);
        $id_usuario = $rqvpc2['id'];
    }
    query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='$id_participante' ORDER BY id DESC limit 1");
} else {
    $id_usuario = $rqdp2['id_usuario'];
}

query("INSERT INTO cursos_rel_usuariocurfreecur(id_usuario, id_curso, id_participante, estado) VALUES ('$id_usuario','$id_curso','$id_participante','1')");
logcursos('Habilitacion de curso gratuito', 'participante-accion', 'participante', $id_participante);
echo '<b class="btn btn-block btn-sm btn-success"><i class="fa fa-check"></i></b>';


/* usuario */
$rqus1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqus2 = fetch($rqus1);
$nombres_participante = $rqus2['nombres'];
$apellidos_participante = $rqus2['apellidos'];
$email_participante = $rqus2['email'];
$password = $rqus2['password'];

/* content text */
$texto_principal = '<p><span><strong>Bienvenid@ a la plataforma '.$___nombre_del_sitio.'</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Reciba un especial saludo de bienvenida a la plataforma on-line '.$___nombre_del_sitio.'</span></p>
<p align="justify">Le informamos tambien que se le asigno 1 CURSO de forma gratuita, para poder selecionar y registrarse en el curso es necesario que ingrese a la plataforma <a href="'.$dominio.'">'.$___nombre_del_sitio.'</a> a continuaci&oacute;n le mostramos los datos de acceso.</p>
<br/>
<br/>
<b>DATOS DE ACCESO</b>
<br/>
<table>
<tr>
<td style="padding:7px 10px;border: 1px solid gray;">Usuario:</td>
<td style="padding:7px 10px;border: 1px solid gray;">' . $email_participante . '</td>
</tr>
<tr>
<td style="padding:7px 10px;border: 1px solid gray;">Contrase&ntilde;a:</td>
<td style="padding:7px 10px;border: 1px solid gray;">' . $password . '</td>
</tr>
</table>
<br/>
<br/>
<p>&nbsp;</p>
                                    <div style="text-align:center;">
                                        <a href="'.$dominio.'ingreso-de-usuarios.html" style="border-radius: 15px;
    padding: 10px 30px;
    border: 1px solid #c5edff;
    font-size: 17pt;
    background: #5cabb8;
    color: #FFF;
    text-decoration: none;">
                                            <i class="fa fa-caret-square-o-right"></i> &nbsp; INGRESAR A LA PLATAFORMA
                                        </a>
                                    </div>
                                    <br/>
                                    <br/>
                                    ';
/* cont correo */
$contenido_correo = platillaEmailUno($texto_principal,'Bienvenida y curso gratuito',$email_participante,urlUnsubscribe($email_participante), trim($nombres_participante . ' ' . $apellidos_participante));

/* datos de correo */
$asunto = "Bienvenida y curso gratuito";

SISTsendEmail($email_participante, $asunto, $contenido_correo);
logcursos('Envio de correo datos de usuario y curso gratuito', 'partipante-cvirtual', 'participante', $id_participante);
    
echo "<br>OK";
