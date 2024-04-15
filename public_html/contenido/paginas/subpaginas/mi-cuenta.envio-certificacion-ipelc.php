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

/* subir-comprobante */
if (isset_post('subir-comprobante')) {
    $tag_image = 'imagen-envio';
    $codigo_doc = 'pago-envio';
    $id_departamento = post('id_departamento');
    $carpeta_destino = 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                query("INSERT cursos_envio_certificados (id_usuario,id_departamento) VALUES ('$id_usuario','$id_departamento') ");
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
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
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
                    <h3>ENV&Iacute;O DE CERTIFICACI&Oacute;N IPELC</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        NEMABOL informa a todos los participantes de los cursos de Aymara - Quechua, que debido a que se continuar&aacute; con la restricci&oacute;n de funcionamiento de centros de estudio, y para no perjudicar a ning&uacute;n participante, nos vemos en la necesidad de coordinar el env&iacute;o de certificados hasta las ciudades de cada participante, el mismo tendr&aacute; un costo adicional dado que este gasto est&aacute; fuera de presupuesto.
                        <br>
                        El mismo podr&aacute; ser a elecci&oacute;n seg&uacute;n lugar destino
                        <br>
                        ENV&Iacute;O POR:
                        <br>
                        Flota  20 Bs.  Ciudades Capitales
                        <br>
                        Courrier  35 Bs.  Ciudades Capitales - Centro ciudad
                        <br>
                        Avi&oacute;n 50 Bs.  
                        <br>
                        Par&aacute; dicho servicio el participante deber&aacute; realizar el abono de esta suma a las cuentas de NEMABOL, y mandar por este medio su comprobante, una vez realizado este paso las encargadas de cada curso coordinar&aacute;n con ustedes el env&iacute;o. 
                        <br>
                        Esperamos contar con su comprensi&oacute;n, saludos cordiales.
                    </p>
                </div>
                <br>
                <?php
                $codigo_doc = 'pago-envio';
                $vrs1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                if (num_rows($vrs1)>0) {
                    echo '<div class="alert alert-info">
  <strong>SOLICITUD DE ENVIO REALIZADA</strong>
  <br>Usted ya envi&oacute; el comprobante de pago.
</div>
';
                    $vrs2 = fetch($vrs1);
                    $url_imagen = 'contenido/imagenes/doc-usuarios/'.$vrs2['nombre'];
                    $vrscd1 = query("SELECT d.nombre FROM cursos_envio_certificados e INNER JOIN departamentos d ON e.id_departamento=d.id WHERE e.id_usuario='$id_usuario' ORDER BY e.id DESC limit 1 ");
                    $vrscd2 = fetch($vrscd1);
                    $nombre_ciudad = $vrscd2['nombre'];
                    echo "<hr>";
                    echo "<b>Ciudad:</b> $nombre_ciudad";
                    echo "<br>";
                    echo "<b>Comprobante:</b> <a href='$url_imagen' target='_blank' style='text-decoration:underline;color:blue'>VER IMAGEN ENVIADA</a>";
                }else{

                ?>
                <br>
                <form action="" method="post" enctype="multipart/form-data">
                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th class="text-center" colspan="2">
                                SOLICITUD DE ENV&Iacute;O
                            </th>
                        </tr>
                        <tr>
                            <td>
                                Ciudad:
                            </td>
                            <td>
                                <select name="id_departamento" class="form-control">
                                    <?php
                                    $rqdd1 = query("SELECT id,nombre FROM departamentos WHERE estado='1' ");
                                    while ($rqdd2 = fetch($rqdd1)) {
                                        ?>
                                    <option value="<?php echo $rqdd2['id']; ?>"><?php echo $rqdd2['nombre']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Comprobante de pago:</b>
                            </td>
                            <td>
                                <input type="file" name="imagen-envio" required="" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-cemter">
                                <br>
                                <input type="submit" class="btn btn-sm btn-success btn-block" name="subir-comprobante" value="SUBIR COMPROBANTE DE PAGO"/>
                                <br>
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
                }
                ?>


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
