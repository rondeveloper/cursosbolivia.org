<?php

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* USUARIO */
$id_usuario = 0;
$sw_docente = 0;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    $rqdu1 = query("SELECT sw_docente FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    $usuario = fetch($rqdu1);
    $sw_docente = (int) $usuario['sw_docente'];
}


$arrayResponse = array();

$arrayResponse['result'] = '0';
$arrayResponse['html'] = '';
$arrayResponse['lastIdComent'] = '0';


$contenidoHtml = '';

$roomcod = post('roomcod');
$id_onlinecourse = post('id_onlinecourse');
$id_onlinecourse_leccion = post('id_onlinecourse_leccion');
$lastIdComent = (int) post('last_id_coment');

/* verifica nuevos comentarios */
$rqvnc1 = query("SELECT id FROM cursos_onlinecourse_chat WHERE id_onlinecourse='$id_onlinecourse' AND roomcod='$roomcod' AND estado='1' AND id>$lastIdComent ORDER BY id DESC limit 1 ");
if (num_rows($rqvnc1) > 0) {

    $rqvnc2 = fetch($rqvnc1);
    $lastIdComent = $rqvnc2['id'];

    $rqmc1 = query("SELECT * FROM cursos_onlinecourse_chat WHERE id_onlinecourse='$id_onlinecourse' AND roomcod='$roomcod' AND estado='1' ORDER BY id DESC limit 50 ");
    $data = array();
    while ($rqmc2 = fetch($rqmc1)) {
        $data[] = $rqmc2;
    }

    $contenidoHtml .= '<div class="outgoing_msg">
          <div class="sent_msg">
               <p>Hola! te damos la bienvenida a este curso, puedes consultarme tus dudas mediante este chat, atte: el docente</p>
               <span class="time_date"> Tutor virtual </span> 
          </div>
      </div>';
    $chats = array_reverse($data);
    foreach ($chats as $chat) {
        if ($chat['sw_docente'] == '1') {
            /* datos docente */
            $id_docente = $chat['id_usuario'];
            $rqdd1 = query("SELECT nombres,apellidos,prefijo FROM cursos_docentes WHERE id='$id_docente' LIMIT 1 ");
            $rqdd2 = fetch($rqdd1);
            $nombre_docente = trim($rqdd2['prefijo'] . ' ' . $rqdd2['nombres'] . ' ' . $rqdd2['apellidos']);
            $fecha_comentario = mydate_comment($chat['fecha']);
            $contenidoHtml .= '
        <div class="outgoing_msg">
            <div class="sent_msg">
                <p>' . $chat['mensaje'] . '</p>
                <span class="time_date"> ' . $nombre_docente . '  <span class="pull-right">' . $fecha_comentario . '</span></span> 
            </div>
        </div>';
        } else {
            /* datos usuario */
            $id_usuario = $chat['id_usuario'];
            $rqdd1 = query("SELECT nombres,apellidos FROM cursos_usuarios WHERE id='$id_usuario' LIMIT 1 ");
            $rqdd2 = fetch($rqdd1);
            $nombre_usuario = ucfirst(strtolower(trim($rqdd2['nombres'] . ' ' . $rqdd2['apellidos'])));
            $fecha_comentario = mydate_comment($chat['fecha']);
            $contenidoHtml .= '
        <div class="incoming_msg">
            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
            <div class="received_msg">
                <div class="received_withd_msg">
                    <p>' . $chat['mensaje'] . '</p>
                    <span class="time_date"> ' . $nombre_usuario . '  <span class="pull-right">' . $fecha_comentario . '</span></span>
                </div>
            </div>
        </div>';
        }
    }

    $contenidoHtml .= '&nbsp;.&nbsp;';

    $arrayResponse['html'] = $contenidoHtml;
    $arrayResponse['result'] = '1';
    $arrayResponse['lastIdComent'] = $lastIdComent;
} elseif (isset_usuario()) {
    /* actualiza avance */
    $flag = date("Y-m-d H:i:s");
    $rqvlecav1 = query("SELECT id,flag,segundos FROM cursos_onlinecourse_lec_avance WHERE id_onlinecourse_leccion='$id_onlinecourse_leccion' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
    if (num_rows($rqvlecav1) == 0) {
        query("INSERT INTO cursos_onlinecourse_lec_avance (
           id_onlinecourse_leccion,
           id_usuario,
           segundos,
           flag
           ) VALUES (
           '$id_onlinecourse_leccion',
           '$id_usuario',
           '$minutos',
           '$flag'
           ) ");
    } else {
        $rqvlecav2 = fetch($rqvlecav1);
        $id_onlinecourse_lec_avance = $rqvlecav2['id'];
        $p_flag = $rqvlecav2['flag'];
        $p_segundos = $rqvlecav2['segundos'];
        $time_spend = strtotime($flag)-strtotime($p_flag);
        if(($time_spend)<(180)){
            query("UPDATE cursos_onlinecourse_lec_avance SET flag='$flag',segundos='".(int)($p_segundos+$time_spend)."' WHERE id='$id_onlinecourse_lec_avance' ORDER BY id DESC limit 1 ");
        }else{
            query("UPDATE cursos_onlinecourse_lec_avance SET flag='$flag' WHERE id='$id_onlinecourse_lec_avance' ORDER BY id DESC limit 1 ");
        }
    }
}

echo json_encode($arrayResponse);

function mydate_comment($fecha) {
    $d = date("d", strtotime($fecha));
    $m = date("m", strtotime($fecha));
    $hora = date("H:i", strtotime($fecha));
    $mes = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Nov', 'Dic');
    $r = $d . '/' . $mes[(int) $m] . ' ' . $hora;
    return $r;
}


if (isset_usuario()){
    usuarioSet(usuario('id'));
}
