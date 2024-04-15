<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
$id_departamento_usuario = $rqdu2['id_departamento'];

/* enviar-tarea */
if (isset_post('enviar-tarea')) {
    $id_tarea = post('id_tarea');
    $tag_image = 'tarea';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz.'contenido/archivos/tareas/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg','txt','doc','docx','xls','xlsx','ppt'))) {
            $nombre_imagen = rand(999, 99999) . str_replace(' ','-',str_replace("'","",$_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("INSERT INTO cursos_onlinecourse_tareasenvios (id_usuario,id_tarea,archivo) VALUES ('$id_usuario','$id_tarea','$nombre_imagen') ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> la tarea se subio correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
}

/* re-enviar-tarea */
if (isset_post('re-enviar-tarea')) {
    $id_tarea = post('id_tarea');
    $id_envio = post('id_envio');
    $tag_image = 'tarea';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz.'contenido/archivos/tareas/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg','txt','doc','docx','xls','xlsx','ppt'))) {
            $nombre_imagen = rand(999, 99999) . str_replace(' ','-',str_replace("'","",$_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            $rqdprsend1 = query("SELECT archivo FROM cursos_onlinecourse_tareasenvios WHERE id='$id_envio' ORDER BY id DESC limit 1 ");
            $rqdprsend2 = fetch($rqdprsend1);
            unlink($carpeta_destino.$rqdprsend2['archivo']);
            query("UPDATE cursos_onlinecourse_tareasenvios SET archivo='$nombre_imagen' WHERE id='$id_envio' ORDER BY id DESC limit 1 ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> la tarea se volvio a subir correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>TAREAS ASIGNADAS</h3>
                </div>
                <?php
                $rqcut1 = query("SELECT (o.titulo)dr_titulo_cursovirtual,t.tarea,t.descripcion,(t.id)dr_id_tarea FROM cursos_onlinecourse_tareas t INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=t.id_onlinecourse INNER JOIN cursos_onlinecourse o ON r.id_onlinecourse=o.id INNER JOIN cursos_participantes p ON r.id_curso=p.id_curso WHERE p.id_usuario='$id_usuario' ");
                if (num_rows($rqcut1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <div class="alert alert-warning">
                            <strong>AVISO</strong> no se registraron tareas asignadas a su cuenta..
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan las tareas asociadas a sus cursos.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                Curso
                            </th>
                            <th>
                                Tarea
                            </th>
                            <th>
                                Estado
                            </th>
                            <th class="text-center">
                                Archivo
                            </th>
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcut1)) {
                            $qrdtr1 = query("SELECT id,archivo FROM cursos_onlinecourse_tareasenvios WHERE id_tarea='" . $rqcu2['dr_id_tarea'] . "' AND id_usuario='$id_usuario' ");
                            $sw_enviado = true;
                            if (num_rows($qrdtr1) == 0) {
                                $sw_enviado = false;
                            } else {
                                $qrdtr2 = fetch($qrdtr1);
                                $archivo_tarea = $qrdtr2['archivo'];
                                $id_envio_tarea = $qrdtr2['id'];
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['dr_titulo_cursovirtual']; ?>
                                </td>
                                <td>
                                    <b><?php echo $rqcu2['tarea']; ?></b>
                                    <br>
                                    <?php echo $rqcu2['descripcion']; ?>
                                </td>
                                <td>
                                    <?php if (!$sw_enviado) { ?>
                                        <i class="label label-danger">Sin env&iacute;o</i>
                                    <?php } else { ?>
                                        <i class="label label-success">TAREA ENVIADA</i>
                                    <?php } ?>
                                </td>
                                <td class="text-center" style="padding: 15px;">
                                    <?php if (!$sw_enviado) { ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="file" class="form-control" name="tarea" required=""/>
                                            <input type="hidden" name="id_tarea" value="<?php echo $rqcu2['dr_id_tarea']; ?>"/>
                                            <input type="submit" value="ENVIAR TAREA" class="btn btn-success btn-sm" name="enviar-tarea"/>
                                        </form>
                                    <?php } else { ?>
                                        <a onclick='window.open("<?php echo $dominio_www.'contenido/archivos/tareas/' . $archivo_tarea; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
                                            [ VISUALIZAR ENV&Iacute;O ]
                                        </a>
                                        <br>
                                        <br>
                                        <i style="color: gray;cursor: pointer;text-decoration: underline;" data-toggle="collapse" data-target="#tarea-<?php echo $rqcu2['dr_id_tarea']; ?>">Volver a enviar</i>
                                        <div id="tarea-<?php echo $rqcu2['dr_id_tarea']; ?>" class="collapse">
                                            <br>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input type="file" class="form-control" name="tarea" required=""/>
                                                <input type="hidden" name="id_tarea" value="<?php echo $rqcu2['dr_id_tarea']; ?>"/>
                                                <input type="hidden" name="id_envio" value="<?php echo $id_envio_tarea; ?>"/>
                                                <input type="submit" value="VOLVER A ENVIAR" class="btn btn-info btn-sm" name="re-enviar-tarea"/>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>

                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <hr>
                <br>
                <hr>
            </div>
        </div>
    </section>
</div>                     



<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
?>