<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "ACCESO DENEGADO";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
$id_departamento_usuario = $rqdu2['id_departamento'];

/* enviar-archivo */
if (isset_post('enviar-archivo')) {
    $id_examen = post('id_examen');
    $tag_image = 'archivo';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz.'contenido/archivos/examen-2t/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg','txt','doc','docx','xls','xlsx','ppt'))) {
            $nombre_imagen = $id_examen.'-'.rand(999, 99999) . str_replace(' ','-',str_replace("'","",$_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("UPDATE segundos_turnos SET imagen_pago='$nombre_imagen' WHERE id='$id_examen' ORDER BY id DESC limit 1 ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el archivo se subio correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
}

/* enviar-video */
if (isset_post('enviar-video')) {
    $id_examen = post('id_examen');
    $tag_image = 'video';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz.'contenido/archivos/examen-2t/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('mp4', 'avi', 'wmv', 'mpg4', 'mpg'))) {
            $nombre_imagen = $id_examen.'-'.rand(999, 99999) . str_replace(' ','-',str_replace("'","",$_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("UPDATE segundos_turnos SET video='$nombre_imagen' WHERE id='$id_examen' ORDER BY id DESC limit 1 ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el video se subio correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    } else {
        $mensaje .= '<br><div class="alert alert-danger">
<strong>ERROR</strong> no se subio el video.
</div>';
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
                    <h3>EXAMEN DE SEGUNTO TURNO</h3>
                </div>
                <?php
                $rqcut1 = query("SELECT * FROM segundos_turnos WHERE id_usuario='$id_usuario' ");
                if (num_rows($rqcut1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <div class="alert alert-warning">
                            <strong>AVISO</strong> no se registr&oacute; examen habilitado para a su cuenta..
                        </div>
                    </div>
                    <?php
                } else {
                    $rqcut2 = fetch($rqcut1);
                    $id_examen = $rqcut2['id'];
                    $video = $rqcut2['video'];
                    $imagen_pago = $rqcut2['imagen_pago'];
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se muestra lo solicitado para el examen.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                Requerimiento
                            </th>
                            <th>
                                Estado
                            </th>
                            <th class="text-center">
                                Archivo
                            </th>
                        </tr>
                        <tr>
                            <td>
                                Comprobante de pago (50 BS)
                            </td>
                            <td>
                                <?php if ($imagen_pago=='') { ?>
                                    <i class="label label-danger">Sin env&iacute;o</i>
                                <?php } else { ?>
                                    <i class="label label-success">ENVIADO</i>
                                <?php } ?>
                            </td>
                            <td class="text-center" style="padding: 15px;">
                                <?php if ($imagen_pago=='') { ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="file" class="form-control" name="archivo" required=""/>
                                        <input type="hidden" name="id_examen" value="<?php echo $id_examen; ?>"/>
                                        <input type="submit" value="ENVIAR" class="btn btn-success btn-sm" name="enviar-archivo"/>
                                    </form>
                                <?php } else { ?>
                                    <a onclick='window.open("<?php echo $dominio_www.'contenido/archivos/examen-2t/' . $imagen_pago; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
                                        [VISUALIZAR ENV&Iacute;O]
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Video de duraci&oacute;n minima de 5 minutos, con presentaci&oacute;n y exposici&oacute;n sobre alg&uacute;n tema en el idioma del curso tomado.
                            </td>
                            <td>
                                <?php if ($video=='') { ?>
                                    <i class="label label-danger">Sin env&iacute;o</i>
                                <?php } else { ?>
                                    <i class="label label-success">ENVIADO</i>
                                <?php } ?>
                            </td>
                            <td class="text-center" style="padding: 15px;">
                                <?php if ($video=='') { ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="file" class="form-control" name="video" required=""/>
                                        <input type="hidden" name="id_examen" value="<?php echo $id_examen; ?>"/>
                                        <input type="submit" value="ENVIAR" class="btn btn-success btn-sm" name="enviar-video"/>
                                    </form>
                                <?php } else { ?>
                                    <a onclick='window.open("<?php echo $dominio_www.'contenido/archivos/examen-2t/' . $video; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
                                        [VISUALIZAR ENV&Iacute;O]
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
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