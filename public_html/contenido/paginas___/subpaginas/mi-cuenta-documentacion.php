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

/* subir doc-ci-a */
if (isset_post('subir-doc-ci-a')) {
    $tag_image = 'doc-ci-a';
    $codigo_doc = 'ci-anverso';
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    } else {
        $vr2 = fetch($vr1);
        $id_reg = $vr2['id'];
        $nombre_prev = $vr2['nombre'];
        unlink($carpeta_destino . $nombre_prev);
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("UPDATE documentos_usuario SET nombre='$nombre_imagen',fecha_upload=NOW() WHERE id='$id_reg' LIMIT 1 ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    }
}

/* subir doc-ci-b */
if (isset_post('subir-doc-ci-b')) {
    $tag_image = 'doc-ci-b';
    $codigo_doc = 'ci-reverso';
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    } else {
        $vr2 = fetch($vr1);
        $id_reg = $vr2['id'];
        $nombre_prev = $vr2['nombre'];
        unlink($carpeta_destino . $nombre_prev);
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("UPDATE documentos_usuario SET nombre='$nombre_imagen',fecha_upload=NOW() WHERE id='$id_reg' LIMIT 1 ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    }
}

/* subir doc-titulo */
if (isset_post('subir-doc-titulo')) {
    $tag_image = 'doc-titulo';
    $codigo_doc = 'titulo';
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    } else {
        $vr2 = fetch($vr1);
        $id_reg = $vr2['id'];
        $nombre_prev = $vr2['nombre'];
        unlink($carpeta_destino . $nombre_prev);
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("UPDATE documentos_usuario SET nombre='$nombre_imagen',fecha_upload=NOW() WHERE id='$id_reg' LIMIT 1 ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    }
}


/* subir doc-titulo */
if (isset_post('subir-doc-deposito')) {
    $tag_image = 'doc-deposito';
    $codigo_doc = 'dep-iplc';
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    } else {
        $vr2 = fetch($vr1);
        $id_reg = $vr2['id'];
        $nombre_prev = $vr2['nombre'];
        unlink($carpeta_destino . $nombre_prev);
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("UPDATE documentos_usuario SET nombre='$nombre_imagen',fecha_upload=NOW() WHERE id='$id_reg' LIMIT 1 ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    }
}

/* subir doc-fotocarnet */
if (isset_post('subir-doc-fotocarnet')) {
    $tag_image = 'doc-fotocarnet';
    $codigo_doc = 'fotocarnet';
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    } else {
        $vr2 = fetch($vr1);
        $id_reg = $vr2['id'];
        $nombre_prev = $vr2['nombre'];
        unlink($carpeta_destino . $nombre_prev);
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp','pdf','tif','tiff','raw','bmp','svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[',']',' '),'-',$_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("UPDATE documentos_usuario SET nombre='$nombre_imagen',fecha_upload=NOW() WHERE id='$id_reg' LIMIT 1 ");
                $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
            } else {
                $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
            }
        }
    }
}
?>
<style>
    .btn-default {
        background-color: #bdbdbd;
    }
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>SUBIR DOCUMENTOS IPELC</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Para ciertos procesos de certificaci&oacute;n es necesario que se envie documentos de verificaci&oacute;n de informaci&oacute;n. 
                        En caso de ser solicitado por favor suba una fotografia o escaneo del documento requerido en el formulario mostrado a continuaci&oacute;n.
                    </p>
                </div>
                <table class='table table-striped table-bordered table-hover'>
                    <tr>
                        <th>
                            DOCUMENTO
                        </th>
                        <th>
                            IMAGEN
                        </th>
                        <th>
                            ACCI&Oacute;N
                        </th>
                    </tr>
                    <tr>
                        <td>
                            Carnet de identidad (anverso y reverso)
                        </td>
                        <td>
                            <?php
                            $codigo_doc = 'ci-anverso';
                            $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                            if (num_rows($vr1) > 0) {
                                $txt_subir = 'ACTUALIZAR';
                                $htm_btn = 'btn-default';
                                $vr2 = fetch($vr1);
                                $url_img = 'contenido/imagenes/doc-usuarios/' . $vr2['nombre'];
                                ?>
                                <b class="label label-success"><i class="fa fa-check"></i> SUBIDO</b> &nbsp;&nbsp; | &nbsp;&nbsp;
                                <a href="<?php echo $url_img; ?>" target="_blank" style="text-decoration: underline">Ver imagen</a>
                                <?php
                            } else {
                                $txt_subir = 'SUBIR';
                                $htm_btn = 'btn-primary';
                                ?>
                                No se subio la imagen
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <b class="btn btn-sm <?php echo $htm_btn; ?> btn-block" class="btn btn-info btn-lg" data-toggle="modal" data-target="#MODAL-doc1"><?php echo $txt_subir; ?></b>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td>
                            Carnet de identidad (reverso)
                        </td>
                        <td>
                            <?php
                            $codigo_doc = 'ci-reverso';
                            $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                            if (num_rows($vr1) > 0) {
                                $txt_subir = 'ACTUALIZAR';
                                $htm_btn = 'btn-default';
                                $vr2 = fetch($vr1);
                                $url_img = 'contenido/imagenes/doc-usuarios/' . $vr2['nombre'];
                                ?>
                                <b class="label label-success"><i class="fa fa-check"></i> SUBIDO</b> &nbsp;&nbsp; | &nbsp;&nbsp;
                                <a href="<?php echo $url_img; ?>" target="_blank" style="text-decoration: underline">Ver imagen</a>
                                <?php
                            } else {
                                $txt_subir = 'SUBIR';
                                $htm_btn = 'btn-primary';
                                ?>
                                No se subio la imagen
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <b class="btn btn-sm <?php echo $htm_btn; ?> btn-block" class="btn btn-info btn-lg" data-toggle="modal" data-target="#MODAL-doc2"><?php echo $txt_subir; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            T&iacute;tulo acad&eacute;mico (universitario/t&eacute;cnico/otros)
                        </td>
                        <td>
                            <?php
                            $codigo_doc = 'titulo';
                            $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                            if (num_rows($vr1) > 0) {
                                $txt_subir = 'ACTUALIZAR';
                                $htm_btn = 'btn-default';
                                $vr2 = fetch($vr1);
                                $url_img = 'contenido/imagenes/doc-usuarios/' . $vr2['nombre'];
                                ?>
                                <b class="label label-success"><i class="fa fa-check"></i> SUBIDO</b> &nbsp;&nbsp; | &nbsp;&nbsp;
                                <a href="<?php echo $url_img; ?>" target="_blank" style="text-decoration: underline">Ver imagen</a>
                                <?php
                            } else {
                                $txt_subir = 'SUBIR';
                                $htm_btn = 'btn-primary';
                                ?>
                                No se subio la imagen
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <b class="btn btn-sm <?php echo $htm_btn; ?> btn-block" class="btn btn-info btn-lg" data-toggle="modal" data-target="#MODAL-doc3"><?php echo $txt_subir; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Dep&oacute;sito para IPELC
                            <br>
                            <i>100 BS para IPELC a nombre de NEMABOL, se acepta deposito, tranferencia y giro.</i>
                        </td>
                        <td>
                            <?php
                            $codigo_doc = 'dep-iplc';
                            $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                            if (num_rows($vr1) > 0) {
                                $txt_subir = 'ACTUALIZAR';
                                $htm_btn = 'btn-default';
                                $vr2 = fetch($vr1);
                                $url_img = 'contenido/imagenes/doc-usuarios/' . $vr2['nombre'];
                                ?>
                                <b class="label label-success"><i class="fa fa-check"></i> SUBIDO</b> &nbsp;&nbsp; | &nbsp;&nbsp;
                                <a href="<?php echo $url_img; ?>" target="_blank" style="text-decoration: underline">Ver imagen</a>
                                <?php
                            } else {
                                $txt_subir = 'SUBIR';
                                $htm_btn = 'btn-primary';
                                ?>
                                No se subio la imagen
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <b class="btn btn-sm <?php echo $htm_btn; ?> btn-block" class="btn btn-info btn-lg" data-toggle="modal" data-target="#MODAL-doc4"><?php echo $txt_subir; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Fotografia tipo carnet (se sugiere 4x4, fondo rojo)
                            <br>
                            <b style="color:#0f61a9;">(OPCIONAL)</b>
                        </td>
                        <td>
                            <?php
                            $codigo_doc = 'fotocarnet';
                            $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                            if (num_rows($vr1) > 0) {
                                $txt_subir = 'ACTUALIZAR';
                                $htm_btn = 'btn-default';
                                $vr2 = fetch($vr1);
                                $url_img = 'contenido/imagenes/doc-usuarios/' . $vr2['nombre'];
                                ?>
                                <b class="label label-success"><i class="fa fa-check"></i> SUBIDO</b> &nbsp;&nbsp; | &nbsp;&nbsp;
                                <a href="<?php echo $url_img; ?>" target="_blank" style="text-decoration: underline">Ver imagen</a>
                                <?php
                            } else {
                                $txt_subir = 'SUBIR';
                                $htm_btn = 'btn-primary';
                                ?>
                                No se subio la imagen
                                <br>
                                <b style="color:#0f61a9;">(OPCIONAL)</b>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <b class="btn btn-sm <?php echo $htm_btn; ?> btn-block" class="btn btn-info btn-lg" data-toggle="modal" data-target="#MODAL-doc5"><?php echo $txt_subir; ?></b>
                        </td>
                    </tr>
                </table>


                <hr>

                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
            </div>
        </div>
    </section>
</div>


<!-- Modals -->
<div id="MODAL-doc1" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUBIR C.I. (anverso y reverso)</h4>
            </div>
            <div class="modal-body">
                <p>Sube la foto o escaneo de tu carnet de identidad.</p>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <td>
                                <b>Documento:</b>
                            </td>
                            <td>
                                Carnet de identidad (anverso y reverso)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Imagen:</b>
                            </td>
                            <td>
                                <input type="file" name="doc-ci-a" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-doc-ci-a" value="SUBIR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="MODAL-doc2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUBIR C.I. (reverso)</h4>
            </div>
            <div class="modal-body">
                <p>Sube la foto o escaneo de tu carnet de identidad, el lado reverso. (lado del nombre)</p>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <td>
                                <b>Documento:</b>
                            </td>
                            <td>
                                Carnet de identidad (reverso)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Imagen:</b>
                            </td>
                            <td>
                                <input type="file" name="doc-ci-b" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-doc-ci-b" value="SUBIR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="MODAL-doc3" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUBIR TITULO ACADEMICO</h4>
            </div>
            <div class="modal-body">
                <p>Sube la foto o escaneo de tu t&iacute;tulo acad&eacute;mico principal. (universitario/t&eacute;cnico/otros)</p>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <td>
                                <b>Documento:</b>
                            </td>
                            <td>
                                T&iacute;tulo acad&eacute;mico principal
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Imagen:</b>
                            </td>
                            <td>
                                <input type="file" name="doc-titulo" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-doc-titulo" value="SUBIR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="MODAL-doc4" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUBIR DEPOSITO IPELC</h4>
            </div>
            <div class="modal-body">
                <p>Sube la foto o escaneo del deposito realizado a IPELC.<br>100 BS para IPELC a nombre de NEMABOL, se acepta deposito, tranferencia y giro.</p>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <td>
                                <b>Documento:</b>
                            </td>
                            <td>
                                Deposito realizado a IPELC
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Imagen:</b>
                            </td>
                            <td>
                                <input type="file" name="doc-deposito" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-doc-deposito" value="SUBIR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="MODAL-doc5" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUBIR FOTOGRAFIA</h4>
            </div>
            <div class="modal-body">
                <p>Sube la fotografia tipo carnet (se sugiere 4x4, fondo rojo) en la mejor calidad posible.</p>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <td>
                                <b>Documento:</b>
                            </td>
                            <td>
                                Fotografia tipo carnet (se sugiere 4x4, fondo rojo)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Imagen:</b>
                            </td>
                            <td>
                                <input type="file" name="doc-fotocarnet" required="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-doc-fotocarnet" value="SUBIR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
