<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* mensaje */
$mensaje = '';

/* actualizar-cert-culminacion */
if (isset_post('texto_a')) {
    $sw_habilitado = (int)post('sw_habilitado');
    $texto_a = post('texto_a');
    $texto_b = post('texto_b');
    $texto_c = post('texto_c');
    $fecha = post('fecha');

    $verp1 = query("SELECT id FROM certificados_culminacion WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if (num_rows($verp1) == 0) {
        query("INSERT INTO certificados_culminacion(id_curso, texto_a, texto_b, texto_c, fecha, estado) VALUES ('$id_curso','$texto_a','$texto_b','$texto_c','$fecha','$sw_habilitado')");
    } else {
        $verp2 = fetch($verp1);
        $id_reg = $verp2['id'];
        query("UPDATE certificados_culminacion SET texto_a='$texto_a', texto_b='$texto_b', texto_c='$texto_c', fecha='$fecha', estado='$sw_habilitado' WHERE id='$id_reg' ORDER BY id DESC limit 1 ");
    }
    logcursos('Configuracion de CERTIFICADO DE CULMINACION', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<div class="alert alert-success">
      <strong>EXITO</strong> el registro fue actualizado correctamente.
    </div>';
}

$sw_habilitado = '0';
$texto_a = ('EL INSTITUTO SUPERIOR DE FORMACIÓN SUPERIOR INTERCULTURAL "KHANA MARKA" precedida por el Msc. Lic. Santiago Condori Apaza con el cargo de RECTOR Y POR CUANTO EL DERECHO LE FACULTA:');
$texto_b = ('Que: [NOMBRE-PARTICIPANTE], con C.I. [CI-PARTICIPANTE]. Esta legalmente inscrito/a en los programas de capacitación: Lengua Originaria QUECHUA, NIVEL BÁSICO, [MATRICULA-REGISTRO]el/la interesado/a ha concluido satisfactoriamente el curso, [NOTA]el certificado de APROBACIÓN se otorgara con 300 horas académicas por la IPELC y el INSTITUTO con una duración de tres meses.');
$texto_c = ('Es cuanto certifico en honor a la verdad para fines que convenga al interesado/a.');
$fecha = date("Y-m-d");
$vfcd1 = query("SELECT * FROM certificados_culminacion WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if (num_rows($vfcd1)>0) {
    $vfcd2 = fetch($vfcd1);
    $sw_habilitado = $vfcd2['estado'];
    $texto_a = $vfcd2['texto_a'];
    $texto_b = $vfcd2['texto_b'];
    $texto_c = $vfcd2['texto_c'];
    $fecha = $vfcd2['fecha'];
}else{
    $mensaje .= '<div class="alert alert-info">
    <strong>AVISO</strong> este curso no tiene configurado el certificado de culminaci&oacute;n.
</div>';
}

echo $mensaje;
?>

<b class="text-info">Etiquetas:</b> &nbsp;&nbsp;|&nbsp;&nbsp; [NOMBRE-PARTICIPANTE] &nbsp;&nbsp;|&nbsp;&nbsp; [CI-PARTICIPANTE] &nbsp;&nbsp;|&nbsp;&nbsp; [NOTA]
<hr>

<form id="FORM-cert_culminacion" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td>
                <b>ESTADO</b>
            </td>
            <td class="text-center">
                <?php $htm_ckecked = $sw_habilitado=='1'?' checked="" ':''; ?>
                <label><input type="radio" name="sw_habilitado" value="1" <?php echo $htm_ckecked; ?>/> Habilitado</label>
                &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                <?php $htm_ckecked = $sw_habilitado=='0'?' checked="" ':''; ?>
                <label><input type="radio" name="sw_habilitado" value="0" <?php echo $htm_ckecked; ?>/> No habilitado</label>
            </td>
        </tr>
        <tr>
            <td>
                <b>TEXTO A</b>
            </td>
            <td>
                <textarea name="texto_a" class="form-control" style="height: 125px;"><?php echo $texto_a; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <b>TEXTO B</b>
            </td>
            <td>
                <textarea name="texto_b" class="form-control" style="height: 125px;"><?php echo $texto_b; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <b>TEXTO C</b>
            </td>
            <td>
                <textarea name="texto_c" class="form-control" style="height: 125px;"><?php echo $texto_c; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <b>FECHA</b>
            </td>
            <td>
                <input type="date" name="fecha" value="<?php echo $fecha; ?>" class="form-control"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                <input type="submit" name="actualizar-cert-culminacion" value="ACTUALIZAR CERTIFICADO DE CULMINACION" class="btn btn-success"/>
            </td>
        </tr>
    </table>
</form>

<script>
    $("#FORM-cert_culminacion").on('submit', function(evt) {
        evt.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-cert_culminacion").html("<h3>CARGANDO...<h3>");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cert_culminacion.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                $("#AJAXCONTENT-cert_culminacion").html(data);
            }
        });
    });
</script>