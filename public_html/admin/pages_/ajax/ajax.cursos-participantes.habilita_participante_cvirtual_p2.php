<?php
/* REQUERIDO PHP MAILER */

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

/* datos recibidos */
$id_participante = post('id_participante');

/* datos participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$ci_participante = $rqdp2['ci'];
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$email_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$fecha_registro = date("Y-m-d");
$id_administrador = administrador('id');

if(strlen($ci_participante)>4){
    $password = substr($ci_participante,0,4).strtolower(substr("ABCDEFGHJKLMNPQRSTUVWXYZ",rand(0,23),1));
}else{
    $password = substr(md5(rand(9, 999)), 2, 5);
}

if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_registro = $_SERVER['REMOTE_ADDR'];
}

/* verificacion de correo */
if(!emailValido($email_participante)){
    echo "EMAIL NO VALIDO:<br>$email_participante";
    exit;
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
    $password = $rqvpc2['password'];
}
query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='$id_participante' ORDER BY id DESC limit 1");

/* datos curso */
$rqdc1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$id_curso = $rqdc2['id_curso'];

/* curso */
$rqdcur1 = query("SELECT id_modalidad,sw_ipelc FROM cursos WHERE id='$id_curso' LIMIT 1 ");
$rqdcur2 = fetch($rqdcur1);
$id_modalidad_curso = $rqdcur2['id_modalidad'];
$sw_ipelc = $rqdcur2['sw_ipelc'];

$hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);

/* datos curso virtual */
$rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final,r.id_onlinecourse FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' ORDER BY r.id DESC limit 1 ");
$rqdcv2 = fetch($rqdcv1);
$nombre_curso_virtual = $rqdcv2['titulo'];
$id_onlinecourse = $rqdcv2['id_onlinecourse'];
$url_curso_virtual = $dominio_plataforma.'ingreso/' . $rqdcv2['urltag'] . '/' . $hash_iduser . '.html';
//$fecha_incio_cursovirtual = $rqdcv2['fecha_inicio'];
//$fecha_final_cursovirtual = $rqdcv2['fecha_final'];

$fecha_incio_cursovirtual = date('Y-m-d');
$fecha_final_cursovirtual = date('Y-m-d',strtotime('+2 month',strtotime(date('Y-m-d'))));

/* creacion de registro de acceso */
$rqvacc1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario' ");
if (num_rows($rqvacc1) == 0) {
    query("INSERT INTO cursos_onlinecourse_acceso(
                id_onlinecourse, 
                id_usuario, 
                sw_acceso, 
                fecha_inicio, 
                fecha_final, 
                fecha_activacion, 
                id_administrador_activacion, 
                estado
                ) VALUES (
                '$id_onlinecourse',
                '$id_usuario',
                '1',
                '$fecha_incio_cursovirtual',
                '$fecha_final_cursovirtual',
                NOW(),
                '$id_administrador',
                '1'
                )");
} else {
    $rqvacc2 = fetch($rqvacc1);
    query("UPDATE cursos_onlinecourse_acceso SET sw_acceso='1',fecha_activacion=NOW(),id_administrador_activacion='$id_administrador' WHERE id='" . $rqvacc2['id'] . "' ");
}

$txt_mensajecursopregrabado = '';
if($id_modalidad_curso=='2'){
    $txt_mensajecursopregrabado = ('<p>El curso est&aacute; activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.$fecha_final_cursovirtual.' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.</p>');
}

if($sw_ipelc=='1'){
    $txt_mensajecursopregrabado = $txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, podrá dar Examen en Linea, podrá enviar los documentos para la certificación de la IPELC, podrá hacer seguimiento a la certificación IPELC.';
}

/* content text */
$texto_principal = '<p><span><strong>Bienvenida al curso virtual | ' . ($nombre_curso_virtual) . '</strong></span></p>
<p><span><br>Estimad@ ' . trim($nombres_participante . ' ' . $apellidos_participante) . '<br>
<br>Reciba un especial saludo de bienvenida al ' . ($nombre_curso_virtual) . '<em>&nbsp;</em>ofrecido por&nbsp;NEMABOL en convenio con la plataforma on-line '.$___nombre_del_sitio.'</span></p>
<p align="justify">Le invitamos a que esta semana ingrese a los recursos del curso y explore las pesta&ntilde;as de capacitaci&oacute;n donde encontrar&aacute; informaci&oacute;n sobre esta propuesta de formaci&oacute;n: los objetivos, la metodolog&iacute;a, el cronograma, en fin el programa completo del curso.</p>

'.$txt_mensajecursopregrabado.'

<br/>
<p align="justify">Para ingresar al curso debe seguir estos sencillos pasos y comenzar a explorar el espacio virtual:</p>
<p>1. Desde el navegador web ingrese a <a href="' . $url_curso_virtual . '">' . $url_curso_virtual . '</a><br>
<p>2. Ingrese el usuario y contrase&ntilde;a de ingreso a su cuenta</p>
<p>3. Presione el boton "INGRESAR"</p>
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
<p align="justify">Cada estudiante debe seguir estos pasos para completar el curso:</p>
<p>1. Ingresar a la plataforma.</p>
<p>2. Descargar el material disponible el parte inferior de la pagina de bienvenida al curso.</p>
<p>3. Visualizar cada uno de los capitulos en formato de video disponibles en la plataforma.</p>
<p>5. Preguntar sus dudas directamente al docente via el chat de mensajeria del curso.</p>
<p>3. Realizar un repaso de los conocimientos respondiendo el cuestionario de preguntas de examen.</p>
<p>&nbsp;</p>
<p>Espero que este proceso de FORMACIÓN virtual sea de mucho provecho para usted, y que los contenidos y actividades propuestos en el curso, permitan aprender nuevos conocimientos &uacute;tiles para su desempe&ntilde;o laboral.</p>
<p>Recuerde que la modalidad de este curso es virtual y su &eacute;xito depender&aacute; de su compromiso y disciplina en el seguimiento de todo el proceso de formaci&oacute;n. Distribuya su tiempo de manera adecuada para cumplir con los planes y objetivos establecidos en este proceso de aprendizaje.<br>
<br>Cuenten siempre con mi acompa&ntilde;amiento y apoyo.</p>
<p><br>Atte:<br>Su tutor virtual</em></p>
<p>&nbsp;</p>
                                    <div style="text-align:center;">
                                        <a href="' . $url_curso_virtual . '" style="border-radius: 15px;
    padding: 10px 30px;
    border: 1px solid #c5edff;
    font-size: 17pt;
    background: #5cabb8;
    color: #FFF;
    text-decoration: none;">
                                            <i class="fa fa-caret-square-o-right"></i> &nbsp; INGRESAR AL CURSO VIRTUAL
                                        </a>
                                    </div>
                                    <br/>
                                    <br/>
                                    ';

/* datos de correo */
$asunto = "Curso virtual - $nombre_curso_virtual";
$contenido_correo = platillaEmailUno($texto_principal,$nombre_curso_virtual,$email_participante,urlUnsubscribe($email_participante),trim($nombres_participante . ' ' . $apellidos_participante));
SISTsendEmail($email_participante, $asunto, $contenido_correo);
logcursos('Envio de correo bienvenida curso-virtual', 'partipante-cvirtual', 'participante', $id_participante);

/* actualizacion de registro */
query("UPDATE cursos_participantes SET sw_cvirtual='1' WHERE id='$id_participante' ");
logcursos('HABILITACION A CURSO VIRTUAL', 'partipante-cvirtual', 'participante', $id_participante);

/* incremento de imagen llave */
query("INSERT INTO clicaction_log(id_participante,cod_action,fecha,id_administrador,ip) VALUES ('$id_participante','msj_accesos',NOW(),'$id_administrador','$ip_registro')");
?>
<div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
<div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
    Des-habilitar: <b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_cvirtual_p1(<?php echo $id_participante; ?>);">X</b>
</div>
<b class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $id_participante; ?>);">PANEL C-vir</b>

<?php
$rqdpadm1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
$rqdpadm2 = fetch($rqdpadm1);
echo "<div class='text-center' style='margin: 15px 0px;background: #f1f1f1;'>";
echo "<b>".$rqdpadm2['nombre']."</b>";
echo "<br>";
echo "<i>".date("d/M H:i")."</i>";
echo "</div>";
?>

<br>

<div class="alert alert-success">
    <strong>EXITO</strong> participante habilitado.
</div>
<center>
    <?php
    /* msj accesos */
    $cod_action = 'msj_accesos';
    $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$id_participante."' AND cod_action='$cod_action' ");
    $rqdccl2 = fetch($rqdccl1);
    $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
    echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$id_participante.',\''.$cod_action.'\');"><img src="'.$dominio_www.'contenido/imagenes/wapicons/wap-llave-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';
    ?>
</center>

<br>
<b onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 0);" class="btn btn-xs btn-warning btn-block">CERTIFICADOS</b>