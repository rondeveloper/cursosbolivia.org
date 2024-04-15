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

/* subir-formulario */
if (isset_post('subir-formulario')) {
    $tag_image = 'imagen-envio';
    $tipo = post('tipo');
    $detalle = post('detalle');
    $id_departamento = post('id_departamento');
    $carpeta_destino = $___path_raiz.'contenido/imagenes/doc-usuarios/';
    $nombre_imagen = '';

    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
            $nombre_imagen = 'u'.$id_usuario.'-'.rand(9, 999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
<strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
    query("INSERT INTO quejas_reclamos(id_usuario, tipo, detalle, archivo, fecha, estado) VALUES ('$id_usuario','$tipo','$detalle','$nombre_imagen',NOW(),'1')");
    $mensaje .= '<br><div class="alert alert-success">
<strong>EXITO</strong> el formulario se envi&oacute; correctamente.
</div>';
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
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>SUGERENCIAS, QUEJAS Y RECLAMOS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        <b>Estimado usuario(a):​</b> En esta secci&oacute;n usted puede formular de manera respetuosa su petici&oacute;n, queja, reclamo, sugerencia o denuncia a trav&eacute;s del siguiente formulario web. Esta ser&aacute; tramitada por el equipo de atenci&oacute;n al cliente de nuestra plataforma, de acuerdo con los lineamientos establecidos.​
                    </p>
                </div>
                <br>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th class="text-center" colspan="2">
                                FORMULARIO
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <b>Tipo:</b>
                            </td>
                            <td>
                                <select name="tipo" class="form-control">
                                    <option value="Sugerencia">Sugerencia</option>
                                    <option value="Queja">Queja</option>
                                    <option value="Reclamo">Reclamo</option>
                                    <option value="Peticion">Petici&oacute;n</option>
                                    <option value="Denuncia">Denuncia</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Detalle:</b>
                            </td>
                            <td>
                                <textarea name="detalle" class="form-control" required="" style="height: 120px;" placeholder="Ingrese los detalles..."></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Archivo/Imagen adjunto:</b>
                                <br>
                                (Opcional)
                            </td>
                            <td>
                                <input type="file" name="imagen-envio" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-formulario" value="ENVIAR"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>


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
