<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$correo_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_curso = $rqdp2['id_curso'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];
$estado_participante = $rqdp2['estado'];
$id_usuario_participante = $rqdp2['id_usuario'];
$modo_pago_participante = $rqdp2['modo_pago'];
$nom_para_certificado = trim($rqdp2['prefijo'] . ' ' . $rqdp2['nombres'] . ' ' . $rqdp2['apellidos']);

/* usuario */
$rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
$rqddu2 = mysql_fetch_array($rqddu1);
$user_usuario = $rqddu2['email'];
$password_usuario = $rqddu2['password'];

/* registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$proc_registro = mysql_fetch_array($rqdpr1);

/* curso */
$rqddcapcv1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
$rqddcapcv2 = mysql_fetch_array($rqddcapcv1);
$sw_habilitacion_de_procesos = false;
if ($rqddcapcv2['estado'] == '1' || $rqddcapcv2['estado'] == '2') {
    $sw_habilitacion_de_procesos = true;
}

$rqccg1 = query("SELECT * FROM cursos_onlinecourse WHERE id IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso')");
$cnt_certs_validos = 0;
$cnt_certs_ya_emitidos = 0;
$ids_participantes_ya_emitidos = '';
$contenido_textarea = '';
$contenido_whatsapp = '';
$contenido_div_copy = '';
$sw_asignacion = false;

while ($curso = mysql_fetch_array($rqccg1)) {
    /* curso virtual */
    $id_onlinecourse = $curso['id'];
    $nombre_curso_virtual = $curso['titulo'];
    $urltag_curso_virtual = $curso['urltag'];
    $url_ingreso_cv = 'https://cursos.bo/curso-online/' . $urltag_curso_virtual . '.html';

    /* acceso */
    $sw_acceso = false;
    $rqaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario_participante' AND sw_acceso='1' ");
    if (mysql_num_rows($rqaccv1) > 0) {
        $sw_acceso = true;
        $sw_asignacion = true;
    }

    if ($sw_acceso) {
        $contenido_textarea .= '
*' . $nombre_curso_virtual . '*
*URL:*  ' . $url_ingreso_cv . '
*USUARIO:*  ' . $user_usuario . '
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '

';
        $contenido_div_copy .= '
<br>
*' . $nombre_curso_virtual . '*
<br>
*URL:*  ' . $url_ingreso_cv . '
<br>
*USUARIO:*  ' . $user_usuario . '
<br>
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '
<br>
<br>
';

        $contenido_whatsapp .= '__*URL de ingreso:*  ' . $url_ingreso_cv . '__ __*' . $nombre_curso_virtual . '*__*Usuario:*  ' . $user_usuario . '__*Contraseña:*  ' . $password_usuario . ' __';
    }
}

if ($sw_asignacion) {
    if (strlen(trim($celular_participante)) == 8) {
        $txt_whatsapp = 'Buen día ' . utf8_decode($nombres_participante . ' ' . $apellidos_participante) . '__Le hacemos el envío de los datos de acceso a sus cursos virtuales:__ __' . $contenido_whatsapp;
        $txt_whatsapp = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
        echo "phone=591".trim($celular_participante)."&text=".$txt_whatsapp;
    } else {
        echo "Celular incorrecto!";
    }
}