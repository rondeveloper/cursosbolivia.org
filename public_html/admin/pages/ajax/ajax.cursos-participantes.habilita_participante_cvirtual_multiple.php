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
$id_onlinecourse_vaux = post('id_onlinecourse');

/* ids_onlinecourse_activar */
$ids_onlinecourse_activar = '0';
if(isset_post('ids_acv')){
    $ids_acv = post('ids_acv');
    $aracv1 = explode(',',$ids_acv);
    foreach ($aracv1 as $id_acv) {
        if(isset_post('check-cv-'.$id_acv)){
            $ids_onlinecourse_activar .= ','.$id_acv;
        }
    }
}else{
    $ids_onlinecourse_activar .= ','.$id_onlinecourse_vaux;
}

/* datos participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$id_curso = $rqdp2['id_curso'];
$ci_participante = $rqdp2['ci'];
$nombres_participante = $rqdp2['nombres'];
$apellidos_participante = $rqdp2['apellidos'];
$email_participante = $rqdp2['correo'];
$celular_participante = $rqdp2['celular'];
$fecha_registro = date("Y-m-d");
$id_administrador = administrador('id');

if(strlen($ci_participante)>4){
    $password = substr($ci_participante,0,4).strtolower(substr("ABCDEFGHJKMNPQRTUVWXYZ",rand(0,23),1));
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
    $password = $rqvpc2['password'];
}
query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='$id_participante' ORDER BY id DESC limit 1");
$hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);

/* activacion de cursos vistuales */
$arids_onlinecourse_activar = explode(',',$ids_onlinecourse_activar);
foreach ($arids_onlinecourse_activar as $id_onlinecourse) {
    if((int)$id_onlinecourse>0){
        /* datos curso virtual */
        $rqdcv1 = query("SELECT cv.titulo,cv.urltag,r.fecha_inicio,r.fecha_final,r.id_onlinecourse FROM cursos_onlinecourse cv INNER JOIN cursos_rel_cursoonlinecourse r ON cv.id=r.id_onlinecourse WHERE cv.id='$id_onlinecourse' AND r.estado='1' ORDER BY r.id DESC limit 1 ");
        $rqdcv2 = fetch($rqdcv1);
        $nombre_curso_virtual = $rqdcv2['titulo'];
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
            query("UPDATE cursos_onlinecourse_acceso SET sw_acceso='1' WHERE id='" . $rqvacc2['id'] . "' ");
        }

        /* actualizacion de registro */
        query("UPDATE cursos_participantes SET sw_cvirtual='1' WHERE id='$id_participante' ");
        logcursos('HABILITACION A CURSO VIRTUAL [CV:' . $id_onlinecourse . ']', 'partipante-cvirtual', 'participante', $id_participante);
    }
}

/* visualizacion en TD */
$qrcoe1 = query("SELECT count(*) AS total FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' AND estado='1' ORDER BY id DESC limit 1 ");
$qrcoe2 = fetch($qrcoe1);
$cnt_cursos_cirtuales_asociados = (int) $qrcoe2['total'];
$rqdcntcva1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_acceso WHERE id_usuario='$id_usuario' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso' and estado='1' ) ");
$rqdcntcva2 = fetch($rqdcntcva1);
$aux_cnt_asignados = $rqdcntcva2['total'];
?>
<b class="btn btn-xs btn-block btn-default" onclick="$('#AJAXCONTENT-acceso_cursos_virtuales').html('Cargando...');
        acceso_cursos_virtuales('<?php echo $id_participante; ?>');" data-toggle="modal" data-target="#MODAL-acceso_cursos_virtuales">
    C-VIRTUALES
</b>
<?php
for ($i = 1; $i <= $cnt_cursos_cirtuales_asociados; $i++) {
    if ($i <= $aux_cnt_asignados) {
        echo "<div style='background: #73ab2c;margin-right: 2px;margin-top: 2px;float: left;width: 20px;height: 5px;border-radius: 5px;'></div>";
    } else {
        echo "<div style='background: #cecece;margin-right: 2px;margin-top: 2px;float: left;width: 20px;height: 5px;border-radius: 5px;'></div>";
    }
}
