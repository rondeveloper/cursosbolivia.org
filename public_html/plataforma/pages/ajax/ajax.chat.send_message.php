<?php
session_start();

include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* USUARIO */
$id_usuario = 0;
$sw_docente = 0;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    $sw_docente = 0;
}

/* DOCENTE */
if (isset_docente()) {
    $id_usuario = docente('id');
    $sw_docente = 1;
}

$roomcod = post('roomcod');
$mensaje = post('message');
$id_onlinecourse = post('id_onlinecourse');
$fecha = date("Y-m-d H:i");
query("INSERT INTO cursos_onlinecourse_chat(id_onlinecourse, id_usuario, roomcod, mensaje, fecha, sw_docente, estado) VALUES ('$id_onlinecourse','$id_usuario','$roomcod','$mensaje','$fecha','$sw_docente','1')");
?>

<?php
$rqmc1 = query("SELECT * FROM cursos_onlinecourse_chat WHERE id_onlinecourse='$id_onlinecourse' AND roomcod='$roomcod' AND estado='1' ORDER BY id DESC limit 50 ");
$data = array();
while ($rqmc2 = fetch($rqmc1)) {
    $data[] = $rqmc2;
}

echo '<div class="outgoing_msg">
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
        $nombre_docente = trim ($rqdd2['prefijo'].' '.$rqdd2['nombres'].' '.$rqdd2['apellidos']);
        $fecha_comentario = mydate_comment($chat['fecha']);
        ?>
        <div class="outgoing_msg">
            <div class="sent_msg">
                <p><?php echo $chat['mensaje']; ?></p>
                <span class="time_date"> <?php echo $nombre_docente; ?> <span class="pull-right"><?php echo $fecha_comentario ?></span></span>
            </div>
        </div>
        <?php
    } else {
        /* datos usuario */
        $id_usuario = $chat['id_usuario'];
        $rqdd1 = query("SELECT nombres,apellidos FROM cursos_usuarios WHERE id='$id_usuario' LIMIT 1 ");
        $rqdd2 = fetch($rqdd1);
        $nombre_usuario = ucfirst(strtolower(trim($rqdd2['nombres'].' '.$rqdd2['apellidos'])));
        $fecha_comentario = mydate_comment($chat['fecha']);
        ?>
        <div class="incoming_msg">
            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
            <div class="received_msg">
                <div class="received_withd_msg">
                    <p><?php echo $chat['mensaje']; ?></p>
                    <span class="time_date"> <?php echo $nombre_usuario; ?> <span class="pull-right"><?php echo $fecha_comentario ?></span></span>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
&nbsp;.&nbsp;

<?php
function mydate_comment($fecha){
    $d = date("d",strtotime($fecha));
    $m = date("m",strtotime($fecha));
    $hora = date("H:i",strtotime($fecha));
    $mes = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Nov','Dic');
    $r = $d.'/'.$mes[(int)$m].' '.$hora;
    return $r;
}