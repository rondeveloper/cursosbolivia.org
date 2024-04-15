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
    $id_compromiso = post('id_compromiso');
    $tag_image = 'archivo';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz.'contenido/archivos/documentos/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg','txt','doc','docx','xls','xlsx','ppt'))) {
            $nombre_imagen = 'CFN'.$id_compromiso.'-'.rand(999, 99999) . str_replace(' ','-',str_replace("'","",$_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("UPDATE compromisos_finalizacion SET archivo='$nombre_imagen',estado='2' WHERE id='$id_compromiso' ORDER BY id DESC limit 1 ");
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
                    <h3>DOCUMENTOS</h3>
                </div>
                <?php
                $rqcut1 = query("SELECT * FROM compromisos_finalizacion WHERE id_usuario='$id_usuario' ");
                if (num_rows($rqcut1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <div class="alert alert-warning">
                            <strong>AVISO</strong> no se registr&oacute; documentos requeridos para a su cuenta..
                        </div>
                    </div>
                    <?php
                } else {
                    $rqcut2 = fetch($rqcut1);
                    $id_compromiso = $rqcut2['id'];
                    $archivo = $rqcut2['archivo'];
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se muestran los documentos solicitados.
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
                                <b>Compromiso firmado</b>
                                <br>
                                Detalles: debe descargar el siguiente documento: 
                                <a onclick='window.open("<?php echo $dominio_www; ?>contenido/paginas/procesos/pdfs/compromiso-finalizacion.php?id_compromiso=<?php echo $id_compromiso; ?>&hash=<?php echo md5(md5($id_compromiso."0012151")); ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">'compromiso finalizacion'</a> 
                                el cual usted debe imprimir y firmar, para posteriormente escanear o tomar fotografia legible y subir en este formulario.
                            </td>
                            <td>
                                <?php if ($archivo=='') { ?>
                                    <i class="label label-danger">Sin env&iacute;o</i>
                                <?php } else { ?>
                                    <i class="label label-success">ENVIADO</i>
                                <?php } ?>
                            </td>
                            <td class="text-center" style="padding: 15px;">
                                <?php if ($archivo=='') { ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="file" class="form-control" name="archivo" required=""/>
                                        <input type="hidden" name="id_compromiso" value="<?php echo $id_compromiso; ?>"/>
                                        <input type="submit" value="ENVIAR" class="btn btn-success btn-sm" name="enviar-archivo"/>
                                    </form>
                                <?php } else { ?>
                                    <a onclick='window.open("<?php echo $dominio_www."contenido/archivos/documentos/" . $archivo; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
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