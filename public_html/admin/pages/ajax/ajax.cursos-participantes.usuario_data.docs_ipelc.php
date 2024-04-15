<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_usuario = post('id_usuario');
$id_curso = post('id_curso');

/* mensaje */
$mensaje = "";

/* subir doc-deposito */
if (isset_post('subir-doc-deposito')) {
    $tag_image = 'doc-deposito';
    $codigo_doc = 'dep-iplc';
    $carpeta_destino = $___path_raiz . 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                logcursos('Subida de doc ipelc', 'usuario-doc', 'usuario', $id_usuario);
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
                logcursos('Subida de doc ipelc', 'usuario-doc', 'usuario', $id_usuario);
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



/* subir-cert-ipelc */
if (isset_post('subir-cert-ipelc')) {
    $tag_image = 'doc-cert-i';
    $codigo_doc = 'cert-ipelc';
    $carpeta_destino = $___path_raiz . 'contenido/imagenes/doc-usuarios/';
    $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
    if (num_rows($vr1) == 0) {
        if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
                $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
                move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
                query("INSERT INTO documentos_usuario (id_usuario,codigo,nombre) VALUES ('$id_usuario','$codigo_doc','$nombre_imagen') ");
                logcursos('Subida de doc ipelc', 'usuario-doc', 'usuario', $id_usuario);
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
                logcursos('Subida de doc ipelc', 'usuario-doc', 'usuario', $id_usuario);
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

<?php echo $mensaje; ?>

<h4 style="text-align: center;background: #f7f7f7;padding: 10px 0px;border: 1px solid #85d3ec;">
    DOCUMENTOS IPELC
</h4>

<br>
<br>

<form id="FORM-subir-comp-iplc">
    <table class="table table-striped table-bordered">
        <tr>
            <td class="text-center"><b>Comprobante de pago IPELC</b></td>
        </tr>
        <?php
        $codigo_doc = 'dep-iplc';
        $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
        if (num_rows($vr1) > 0) {
            $vr2 = fetch($vr1);
        ?>
            <tr>
                <td class="text-center">
                    <b class="text-success">ARCHIVO SUBIDO</b>
                    <br>
                    <a href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $vr2['nombre']; ?>" target="_blank"><?php echo $vr2['nombre']; ?></a>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td class="text-center">
                <input type="file" name="doc-deposito" class="form-control" required="" />
            </td>
        </tr>
        <tr>
            <td class="text-center" style="padding:25px;">
                <input type="submit" class="btn btn-block btn-success" value="SUBIR" />
                <input type="hidden" name="subir-doc-deposito" value="1" />
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
            </td>
        </tr>
    </table>
</form>

<br>
<br>

<form id="FORM-subir-cert-iplc">
    <table class="table table-striped table-bordered">
        <tr>
            <td class="text-center"><b>Certificado IPELC</b></td>
        </tr>
        <?php
        $codigo_doc = 'cert-ipelc';
        $vr1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
        if (num_rows($vr1) > 0) {
            $vr2 = fetch($vr1);
        ?>
            <tr>
                <td class="text-center">
                    <b class="text-success">ARCHIVO SUBIDO</b>
                    <br>
                    <a href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $vr2['nombre']; ?>" target="_blank"><?php echo $vr2['nombre']; ?></a>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td class="text-center"><input type="file" name="doc-cert-i" class="form-control" /></td>
        </tr>
        <tr>
            <td class="text-center" style="padding:25px;">
                <input type="submit" class="btn btn-block btn-success" value="SUBIR" />
                <input type="hidden" name="subir-cert-ipelc" value="1" />
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
            </td>
        </tr>
    </table>
</form>

<script>
    $('#FORM-subir-comp-iplc').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.docs_ipelc.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    });
</script>

<script>
    $('#FORM-subir-cert-iplc').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());
        $("#CONTENT-paneles").html('Cargando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.docs_ipelc.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#CONTENT-paneles").html(data);
            }
        });
    });
</script>