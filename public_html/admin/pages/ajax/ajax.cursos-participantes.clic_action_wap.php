<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

header('Content-Type: application/json');

$id_participante = post('id_participante');
$cod_action = post('cod_action');
$id_administrador = administrador('id');

/* participante */
$rqdp1 = query("SELECT p.nombres,p.apellidos,p.celular,cn.codigo,p.id_curso,p.id_usuario,p.prefijo,p.ci,p.ci_expedido,p.correo,p.id_proceso_registro FROM cursos_participantes p INNER JOIN paises cn ON cn.id=p.id_pais WHERE p.id='$id_participante' ORDER BY p.id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$celular_participante = $rqdp2['celular'];
$codigo_pais = $rqdp2['codigo'];
$id_curso = $rqdp2['id_curso'];
$id_usuario_participante = $rqdp2['id_usuario'];
$prefijo_participante = $rqdp2['prefijo'];
$ci_participante = $rqdp2['ci'];
$ci_expedido_participante = $rqdp2['ci_expedido'];
$correo_participante = $rqdp2['correo'];
$id_proceso_de_registro = $rqdp2['id_proceso_registro'];

if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_registro = $_SERVER['REMOTE_ADDR'];
}

$respuesta_uno = '0';
$respuesta_dos = '';
$respuesta_tres = '';

switch ($cod_action) {
    case 'msj_hola':
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_uno = '1';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone='.$codigo_pais.$celular_participante.'&text=Hola%20'.urlencode($nombres_participante.' '.$apellidos_participante);
        $respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-init-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ Hola ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_accesos':
        /* usuario */
        $rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
        $rqddu2 = fetch($rqddu1);
        $user_usuario = $rqddu2['email'];
        $password_usuario = $rqddu2['password'];
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
                $respuesta_uno = '1';
                $respuesta_dos = "https://api.whatsapp.com/send?phone=".$codigo_pais.trim($celular_participante)."&text=".$txt_whatsapp;
                query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
            } else {
                $respuesta_uno = '0';
                $respuesta_dos = "https://api.whatsapp.com/send?NUMERO INCORRECTO";
            }
        }
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-llave-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ ACCESOS A CURSO VIRTUALES ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_cont':
        /* curso */
        $rqddcapcv1 = query("SELECT mailto_content FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
        $rqddcapcv2 = fetch($rqddcapcv1);
        $mailto_content = str_replace(' ','%20',str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqddcapcv2['mailto_content']));
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_uno = '1';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone=' . $codigo_pais.$celular_participante . '&text='.($mailto_content);
        $respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-hoja-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ Contenido configurado ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_verif':
        /* curso */
        $rqddcapcv1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
        $rqddcapcv2 = fetch($rqddcapcv1);
        $titulo_curso = $rqddcapcv2['titulo'];
        $fecha_curso = date("d-m-Y",strtotime($rqddcapcv2['fecha']));
        $txt_msj_confirm = 'Hola le escribimos de '.$___nombre_del_sitio.' para verificar sus datos__Curso: '.$titulo_curso.'__Fecha: '.$fecha_curso.'__ ';
        if($prefijo_participante!=''){
            $txt_msj_confirm .= '__Grado académico (Abrev) : '.($prefijo_participante);
        }
        $txt_msj_confirm .= '__Nombre: '.($nombres_participante.' '.$apellidos_participante).'__CI: '.$ci_participante.' '.$ci_expedido_participante.'__Celular: '.$celular_participante.'__Correo: '.$correo_participante.'__ __Sus datos están correctos para la certificación, me confirma?';
        $txt_msj_confirm .= '__ __-------------------------------------------------------------__Ayúdanos a superar los 100 mil likes en nuestra página en facebook__https://www.facebook.com/cursoswebbolivia__ __Únete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia';
        $wtxt_verificardatos = str_replace('__','%0A',urlencode(($txt_msj_confirm)));
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_uno = '1';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone='.$codigo_pais.$celular_participante.'&text='.$wtxt_verificardatos;
        $respuesta_tres = '[<img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-money-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>]';
        logcursos('Mensaje WHATSAPP [ VERIFICACION DE DATOS ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_pago':
        /* curso */
        $rqddcapcv1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC LIMIT 1 ");
        $rqddcapcv2 = fetch($rqddcapcv1);
        $titulo_curso = $rqddcapcv2['titulo'];


        /* cuentas bancarias */
        $txt_cuentas_bancarias_asociadas = '';
        $rqdtcb1 = query("SELECT c.*,(b.nombre)dr_nombre_banco,(c.titular)dr_nombre_titular,c.numero_cuenta FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.estado=1 ORDER BY c.id ASC ");
        $sdgs = num_rows($rqdtcb1);
        while($rqdtcb2 = fetch($rqdtcb1)){
            $txt_cuentas_bancarias_asociadas .= '
'.$rqdtcb2['dr_nombre_banco'].' cuenta: '.$rqdtcb2['numero_cuenta'].' a nombre de : '.$rqdtcb2['dr_nombre_titular'];
        }


        $txt_wap_formadepago = 'Hola '.$nombres_participante.' '.$apellidos_participante.'

Para completar tu registro al curso *'.($titulo_curso).'* es necesario realizar el pago correspondiente.

A continuación te detallamos las formas de pago: 

*CUENTA BANCARIAS*
'.$txt_cuentas_bancarias_asociadas.'


*TIGO MONEY:* 
A la linea 62390060 el costo sin recargo (Titular Rodolfo Aliaga)

Luego de hacer el pago tiene que reportar el pago subiendo la imagen del comprobante al siguiente Link:
'.$dominio.'registro-curso-p5c/'.(md5('idr-' . $id_proceso_de_registro)).'/'.$id_proceso_de_registro.'.html
';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone='.$codigo_pais.$celular_participante.'&text='.urlencode(($txt_wap_formadepago));
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_uno = '1';
        $respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-money-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ SOLICITUD DE PAGO ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_zoom':
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $respuesta_uno = '1';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone='.$codigo_pais.$celular_participante.'&text=Hola%20'.urlencode($nombres_participante.' '.$apellidos_participante);
        $respuesta_dos .= '%0AEstos son los datos de ZOOM para el curso:%0A%0A';
        $rqdccl1 = query("SELECT z.descripcion FROM sesiones_zoom z WHERE z.id_curso='$id_curso' ");
        while($rqdccl2 = fetch($rqdccl1)){
            $respuesta_dos .= str_replace(PHP_EOL, '%0A', $rqdccl2['descripcion']).'%0A %0A';
        }
        $respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/images/ic_whatssap_zoom-'.$cnt_button.'.png" style="height: 35px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ ZOOM ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    case 'msj_simulador':
        query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','$cod_action',NOW(),'$id_administrador','$ip_registro')");
        $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='$id_participante' AND cod_action='$cod_action' ");
        $rqdccl2 = fetch($rqdccl1);
        $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
        $rqdccl1 = query("SELECT u.email,u.password FROM cursos_usuarios u WHERE u.id='$id_usuario_participante' ");
        $rqdccl2 = fetch($rqdccl1);
        $respuesta_uno = '1';
        $respuesta_dos = 'https://api.whatsapp.com/send?phone='.$codigo_pais.$celular_participante.'&text=Hola%20'.urlencode($nombres_participante.' '.$apellidos_participante);
        $respuesta_dos .= '%0AEste es el link para el simulador, verifique el ingreso
%0A%0A
https://plataforma.cursosbolivia.org/simulador/
%0A%0A
*Usuario:* '.$rqdccl2['email'].'
%0A
*Contraseña:*  '.$rqdccl2['password'];
$respuesta_tres = '<img src="'.$dominio_www.'contenido/imagenes/images/ic_whatssap_sicoes-'.$cnt_button.'.png" style="height: 35px;border-radius: 20%;curor:pointer;"/>';
        logcursos('Mensaje WHATSAPP [ SIMULADOR ] [clic boton]', 'partipante-edicion', 'participante', $id_participante);
        break;
    default:
        break;
}

$array_respuesta = array('estado'=>$respuesta_uno,'url'=>$respuesta_dos,'htm'=>$respuesta_tres);
echo json_encode($array_respuesta);
