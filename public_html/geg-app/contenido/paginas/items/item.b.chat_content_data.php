<?php
/* data required: $id_onlinecourse,$roomcod,$id_onlinecourse_leccion */
?>
<div>
    <script>
        var sw_openclosechat = false;
        function openclosechat() {
            if (sw_openclosechat) {
                sw_openclosechat = false;
                $("#chat-curso").addClass("chat-curso-close");
                $("#chat-curso").removeClass("chat-curso-open");
            } else {
                sw_openclosechat = true;
                $("#chat-curso").addClass("chat-curso-open");
                $("#chat-curso").removeClass("chat-curso-close");
            }
        }
    </script>

    <div class="chat-curso-close" id="chat-curso" style="display: none;">
        <h5 class="chatcurso-op" id="chat-curso-opener" onclick="openclosechat();">CHAT INTERACTIVO <b class="pull-right">_</b></h5>
        <div class="b_msg_lt">
            <div class="container-chat">
                <div class="messaging">
                    <div class="inbox_msg">
                        <div class="mesgs">
                            <div class="box_msg_history">
                                <div class="msg_history" id="msg_history">
                                    <div class="outgoing_msg">
                                        <div class="sent_msg">
                                            <p>Hola! te damos la bienvenida a este curso, puedes consultarme tus dudas mediante este chat, atte: el docente</p>
                                            <span class="time_date"> Tutor virtual </span> 
                                        </div>
                                    </div>
                                    <?php
                                    $rqmc1 = query("SELECT * FROM cursos_onlinecourse_chat WHERE id_onlinecourse='$id_onlinecourse' AND roomcod='$roomcod' AND estado='1' ORDER BY id DESC limit 50 ");
                                    $data = array();
                                    while ($rqmc2 = mysql_fetch_array($rqmc1)) {
                                        $data[] = $rqmc2;
                                    }
                                    $chats = array_reverse($data);
                                    foreach ($chats as $chat) {
                                        if ($chat['sw_docente'] == '1') {
                                            /* datos docente */
                                            $id_docente = $chat['id_usuario'];
                                            $rqdd1 = query("SELECT nombres,apellidos,prefijo FROM cursos_docentes WHERE id='$id_docente' LIMIT 1 ");
                                            $rqdd2 = mysql_fetch_array($rqdd1);
                                            $nombre_docente = trim($rqdd2['prefijo'] . ' ' . $rqdd2['nombres'] . ' ' . $rqdd2['apellidos']);
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
                                            $rqdd2 = mysql_fetch_array($rqdd1);
                                            $nombre_usuario = ucfirst(strtolower(trim($rqdd2['nombres'] . ' ' . $rqdd2['apellidos'])));
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
                                </div>
                            </div>
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <input type="text" class="write_msg" name="mensaje-chat" id="mensaje-chat" placeholder="Escribe tu mensaje..." autocomplete="off"/>
                                    <button class="msg_send_btn" type="submit" onclick="update_messages();"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var lastIdComent = '0';
                var swSendPeticion = true;
            </script>
            <script>
                var myCont = document.getElementById("msg_history");
                myCont.scrollLeft = 0;
                myCont.scrollTop = 80000;
            </script>
            <script>
                $('#mensaje-chat').keypress(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        send_message();
                    }
                });
            </script>
            <script>
                function send_message() {
                    var message = $("#mensaje-chat").val();
                    $("#mensaje-chat").val("");
                    $.ajax({
                        url: 'contenido/paginas/ajax/ajax.chat.send_message.php',
                        data: {message: message, id_onlinecourse: '<?php echo $id_onlinecourse; ?>', roomcod: '<?php echo $roomcod; ?>'},
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            $("#msg_history").html(data);
                            $("#mensaje-chat").val("");
                            var current_scroll = $('#msg_history').prop("scrollHeight");
                            $("#msg_history").animate({scrollTop: current_scroll}, current_scroll + 100);
                        }
                    });
                }
            </script>
            <script>
                function update_messages() {
                    if (swSendPeticion) {
                        swSendPeticion = false;
                        $.ajax({
                            url: 'contenido/paginas/ajax/ajax.chat.get_messages.php',
                            data: {id_onlinecourse: '<?php echo $id_onlinecourse; ?>', last_id_coment: lastIdComent, roomcod: '<?php echo $roomcod; ?>', id_onlinecourse_leccion: '<?php echo $id_onlinecourse_leccion; ?>'},
                            type: 'POST',
                            dataType: 'json',
                            success: function(dataJson) {
                                swSendPeticion = true;
                                if (dataJson['result'] === '1') {
                                    lastIdComent = dataJson['lastIdComent'];
                                    $("#msg_history").html(dataJson['html']);
                                    var current_scroll = $('#msg_history').prop("scrollHeight");
                                    $("#msg_history").animate({scrollTop: current_scroll}, current_scroll + 100);
                                }
                            }
                        });
                    }
                }
            </script>
            <script>
                setInterval(update_messages, 40000);
            </script>
        </div>
    </div>
</div>

<?php
function mydate_comment($fecha){
    $d = date("d",strtotime($fecha));
    $m = date("m",strtotime($fecha));
    $hora = date("H:i",strtotime($fecha));
    $mes = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Nov','Dic');
    $r = $d.'/'.$mes[(int)$m].' '.$hora;
    return $r;
}