<?php
$correo = $get[2];
$id_curso = $get[3];
$verif_a = $get[4];
$verif_b = md5($correo . 'dardebaja');
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>UNSUBSCRIBE</h3>
                </div>
                <?php
                if ($verif_a !== $verif_b) {
                    ?>
                    <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                        <h4 class="text-center">DATOS INCORRECTOS</h4>
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <br/>
                            <p>...</p>
                        </div>
                    </div>
                    <?php
                } elseif (isset_post('unsubscribe')) {
                    /* proceso */
                    $qrv1 = query("SELECT id FROM cursos_unsubscribe_emails WHERE email='$correo' LIMIT 1 ");
                    if (num_rows($qrv1) == 0) {
                        query("INSERT INTO cursos_unsubscribe_emails (email) VALUES ('$correo') ");
                        query("UPDATE cursos_usuarios SET sw_notif=0 WHERE email='$correo' LIMIT 2 ");
                        query("UPDATE cursos_participantes SET sw_notif=0 WHERE correo='$correo' LIMIT 2 ");
                    }
                    ?>
                    <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                        <h4 class="text-center">Proceso de des suscripci&oacute;n completa</h4>
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <br/>
                            <p class="text-center">Se desactivo el envio de notificaciones para '<?php echo $correo; ?>'.</p>
                        </div>
                    </div>
                    <?php
                } elseif (!isset_post('unsubscribe') && ($id_curso == '0001')) {
                    ?>
                    <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                        <h4 class="text-center">&iquest;Deseas dejar de recibir notificaciones sobre nuevos cursos?</h4>
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <br/>
                            <p>Se dejara de enviar notificaciones acerca de nuevos cursos a '<?php echo $correo; ?>', presiona el siguiente boton para concluir el proceso.</p>
                            <br/>
                            <form action="" method="post">
                                <input type="submit" name="unsubscribe" class="btn btn-info" value="DEJAR DE RECIBIR NOTIFICACIONES"/>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif (!isset_post('unsubscribe') && ($id_curso !== '0001')) {
                    /* proceso */
                    $rqt1 = query("SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='".(int)$id_curso."' ");
                    while ($rqt2 = fetch($rqt1)) {
                        $id_tag = $rqt2['id_tag'];
                        $qrv1 = query("SELECT id FROM cursos_unsubscribe_ustag WHERE email='$correo' AND id_tag='$id_tag' LIMIT 1 ");
                        if (num_rows($qrv1) == 0) {
                            query("INSERT INTO cursos_unsubscribe_ustag (id_tag,email) VALUES ('$id_tag','$correo') ");
                        }
                    }
                    ?>
                    <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                        <h4 class="text-center">No se te enviara notificaciones del curso que ya tomaste</h4>
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <br/>
                            <p>Como ya tomaste un curso similar anteriormente no se te enviar&aacute; notificaciones de cursos iguales a ese en el futuro.
                                <br/>
                                Gracias por darnos tu retroalimentaci&oacute;n.</p>
                            <br/>
                            <input type="submit" name="unsubscribe" class="btn btn-info" value="PROCESO TERMINADO"/>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>

