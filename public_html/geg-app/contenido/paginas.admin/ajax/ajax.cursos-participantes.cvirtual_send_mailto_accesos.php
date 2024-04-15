<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "Denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* datos participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$ci_participante = $rqdp2['ci'];
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$email_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_usuario = $rqdp2['id_usuario'];

/* password */
$rqvpc1 = query("SELECT password FROM cursos_usuarios WHERE id='$id_usuario' ");
$rqvpc2 = mysql_fetch_array($rqvpc1);
$password = $rqvpc2['password'];


/* datos curso */
$rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$id_curso = $rqdc2['id_curso'];
$qrddcdp1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$qrddcdp2 = mysql_fetch_array($qrddcdp1);
$titulo_curso = $qrddcdp2['titulo'];


/* content text */
$texto_principal = 'Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '
Le enviamos en este correo los datos de acceso a los cursos virtuales a los cuales se registro. Recuerde que el acceso al curso y sus recursos estaran disponibles para usted las 24 horas del dia desde su primer ingreso hasta 8 semanas despues.

Para ingresar al curso debe seguir estos sencillos pasos y comenzar a explorar el espacio virtual:
1. Desde el navegador web ingrese a la URL de ingreso
2. Ingrese el usuario y contrasena de ingreso a su cuenta
3. Presione el boton "INGRESAR"

DATOS DE ACCESO
';

$sw_habilitado = false;
/* datos cursos virtuales */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND cv.id IN (select id_onlinecourse from cursos_onlinecourse_acceso where id_usuario='$id_usuario') ORDER BY r.id ASC ");
while($rqdcv2 = mysql_fetch_array($rqdcv1)){
    $sw_habilitado = true;
    $nombre_curso_virtual = $rqdcv2['titulo'];
    $url_curso_virtual = 'https://cursos.bo/curso-online/' . $rqdcv2['urltag'] . '.html';
    $fecha_incio_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_inicio']));
    $fecha_final_cursovirtual = date("d/m/Y", strtotime($rqdcv2['fecha_final']));
    
    $texto_principal .= '
Curso: ' . $nombre_curso_virtual . '
Url de ingreso: ' . $url_curso_virtual . '
Usuario: ' . $email_participante . '
Contrasena: ' . $password . '

';
}

/*
$texto_principal .= '
Recuerde que la modalidad de este curso es virtual y su exito dependera de su compromiso y disciplina en el seguimiento de todo el proceso de formacion. Distribuya su tiempo de manera adecuada para cumplir con los planes y objetivos establecidos en este proceso de aprendizaje.

Saludos cordiales
';
*/

/* datos de correo */
$asunto = "DATOS DE INGRESO cursos virtuales - ".$titulo_curso;

if($sw_habilitado){
    $mailto_content = str_replace(' ','%20',str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$texto_principal));
}else{
    $mailto_content = 'SIN ACCESO A CURSOS VIRTUALES';
}

echo "".$email_participante."?subject=".($asunto)."&body=".$mailto_content;
