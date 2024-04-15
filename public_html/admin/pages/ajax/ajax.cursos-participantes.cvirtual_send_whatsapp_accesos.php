<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
$rqdp2 = fetch($rqdp1);
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$correo_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$id_curso = $rqdp2['id_curso'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];
$estado_participante = $rqdp2['estado'];
$id_usuario_participante = $rqdp2['id_usuario'];
$nom_para_certificado = trim($rqdp2['prefijo'] . ' ' . $rqdp2['nombres'] . ' ' . $rqdp2['apellidos']);
$id_pais = $rqdp2['id_pais'];

/* codigo pais */
$rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
$rqdcw2 = fetch($rqdcw1);
$codigo_pais = $rqdcw2['codigo'];

/* usuario */
$rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
$rqddu2 = fetch($rqddu1);
$user_usuario = $rqddu2['email'];
$password_usuario = $rqddu2['password'];

/* registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$proc_registro = fetch($rqdpr1);

/* curso */
$rqddcapcv1 = query("SELECT estado,id_modalidad,sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
$rqddcapcv2 = fetch($rqddcapcv1);
$sw_habilitacion_de_procesos = false;
$id_modalidad_curso = $rqddcapcv2['id_modalidad'];
$sw_ipelc = $rqddcapcv2['sw_ipelc'];
if ($rqddcapcv2['estado'] == '1' || $rqddcapcv2['estado'] == '2') {
    $sw_habilitacion_de_procesos = true;
}

$rqccg1 = query("SELECT oc.*,(r.fecha_final)dr_fecha_final FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON oc.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' ");
$cnt_certs_validos = 0;
$cnt_certs_ya_emitidos = 0;
$ids_participantes_ya_emitidos = '';
$contenido_whatsapp = '';
$sw_asignacion = false;

while ($curso = fetch($rqccg1)) {
    $hash_iduser = $id_usuario_participante . substr(md5('rtc' . $id_usuario_participante . '-754'), 19, 3);
    /* curso virtual */
    $id_onlinecourse = $curso['id'];
    $nombre_curso_virtual = $curso['titulo'];
    $urltag_curso_virtual = $curso['urltag'];
    $url_ingreso_cv = $dominio_plataforma.'ingreso/' . $urltag_curso_virtual . '/'.$hash_iduser.'.html';
    $fecha_final_curso_virtual = $curso['dr_fecha_final'];

    /* acceso */
    $sw_acceso = false;
    $rqaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario_participante' AND sw_acceso='1' ");
    if (num_rows($rqaccv1) > 0) {
        $sw_acceso = true;
        $sw_asignacion = true;
    }
    
    $txt_mensajecursopregrabado = ('El curso está activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.date("d/m/Y",strtotime($fecha_final_curso_virtual)).' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.');

    if ($sw_acceso) {
        $contenido_whatsapp .= '__-------------------------------__*' . ($nombre_curso_virtual) . '*__ __*Link de ingreso:*__' . $url_ingreso_cv . '__ __*Usuario:* ' . $user_usuario . '__*Contraseña:*  ' . $password_usuario . '__';
    }
}

if($id_modalidad_curso=='2'){
    $contenido_whatsapp = ''.$txt_mensajecursopregrabado.'__'.$contenido_whatsapp;
}

$txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, Podrá dar Examen en Linea, Podrá enviar los documentos para la certificación de la IPELC, Podrá hacer seguimiento a la certificación IPELC.';
if($sw_ipelc=='1'){
    $contenido_whatsapp = ''.$txt_mensajecursoipelc.'__'.$contenido_whatsapp;
}

if ($sw_asignacion) {
    if (strlen(trim($celular_participante)) == 8) {
        $txt_whatsapp = 'Hola ' . ($nombres_participante . ' ' . $apellidos_participante) . '__te hacemos el envío de los datos de acceso a sus cursos virtuales:__ __' . $contenido_whatsapp;
        $txt_whatsapp .= '__ __-------------------------------------------------------------__Ayúdanos a superar los 100 mil likes en nuestra página en facebook__https://www.facebook.com/cursoswebbolivia__ __Únete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia';
        $txt_whatsapp = (str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
        echo "phone=".$codigo_pais.trim($celular_participante)."&text=".$txt_whatsapp;
    } else {
        echo "Celular incorrecto!";
    }
}