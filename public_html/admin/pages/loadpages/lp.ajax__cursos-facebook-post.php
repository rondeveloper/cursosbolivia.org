<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* mensaje */
$mensaje = '';
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
        </ul>

        <div class="row" style="padding: 10px 0px;">
            <div class="col-md-12">
                <h3 style="padding: 0px; margin: 0px; padding-top: 5px;">
                    POST DE CURSOS [FACEBOOK] <i class="fa fa-info-circle animated bounceInDown show-info"></i>
                </h3>
                <blockquote class="page-information hidden">
                    <p>
                        Listado de cursos.
                    </p>
                </blockquote>
            </div>
        </div>
    </div>
</div>


<?php echo $mensaje; ?>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td {
        background: #ebefdd !important;
    }

    .tr_curso_cerrado td {
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }

    .tr_curso_cerrado:hover td {
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }

    .tr_curso_eliminado td {
        background: #f3e3e3 !important;
    }
</style>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="">
            <iframe src="<?php echo $dominio_www; ?>contenido/librerias/editor-fb/index.php" style="width: 100%;height:250px;border: 0px;overflow: hidden;"></iframe>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <?php
            $rqdcl1 = query("SELECT id FROM cursos WHERE estado='1' ORDER BY id_ciudad ASC,fecha ASC");
            $title_nombre_ciudad = '';
            while ($rqdcl2 = fetch($rqdcl1)) {
                $id_curso = $rqdcl2['id'];

                /* curso */
                $rqdc1 = query("SELECT id,titulo,fecha,id_ciudad,fb_txt_requisitos,fb_txt_dirigido,fb_hashtags FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                $rqdc2 = fetch($rqdc1);
                $id_curso = $rqdc2['id'];
                $nombre_curso = $rqdc2['titulo'];
                $id_ciudad_curso = $rqdc2['id_ciudad'];
                $fecha_curso = fecha_curso_D_d_m($rqdc2['fecha']);
                $numero_wamsm_predeternimado = '69714008';
                $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $id_curso . "' ORDER BY r.id ASC LIMIT 1 ");
                if (num_rows($rqdwn1) == 0) {
                    $whats_numero_curso = $numero_wamsm_predeternimado;
                } else {
                    $rqdwn2 = fetch($rqdwn1);
                    $whats_numero_curso = $rqdwn2['numero'];
                }
                $url_corta = $dominio . numIDcurso($id_curso) . '/';
                $fb_txt_requisitos = $rqdc2['fb_txt_requisitos'];
                $fb_txt_dirigido = $rqdc2['fb_txt_dirigido'];
                $fb_hashtags = trim($rqdc2['fb_hashtags']);

                $htar1 = explode(',', $fb_hashtags);
                $txt_hashtag = '';
                foreach ($htar1 as $ht) {
                    if ($ht !== '') {
                        $txt_hashtag .= '#' . trim($ht) . ' ';
                    }
                }

                /* ciudad */
                $rqdcd1 = query("SELECT nombre FROM ciudades WHERE id='$id_ciudad_curso' LIMIT 1 ");
                $rqdcd2 = fetch($rqdcd1);
                $nombre_ciudad = str_replace(' ', '', str_replace('CHUQUISACA', 'SUCRE', strtoupper($rqdcd2['nombre'])));

                $data = ('#' . $nombre_ciudad . ' ' . trim($txt_hashtag) . ' &#10148; ' . $fecha_curso . ' &#10148; ' . $nombre_curso . ' &#10148; ' . $fb_txt_requisitos . ' &#10148; ' . $fb_txt_dirigido . ' &#10148; Consultas &oacute; WhatsApp ' . $whats_numero_curso . ' &#10148; Registro y descuentos en ' . $url_corta);

                if ($title_nombre_ciudad !== $nombre_ciudad) {
                    $title_nombre_ciudad = $nombre_ciudad;
                    echo "<tr><td colspan='5'><h3>$title_nombre_ciudad</h3></td></tr>";
                }

            ?>
                <tr>
                    <td><b style="color:red;font-size:12pt;"><?php echo $id_curso; ?></b></td>
                    <td><?php echo $nombre_curso; ?></td>
                    <td><?php echo $fecha_curso; ?></td>
                    <td style="width: 260px;">
                        <form id="FORM-g-post-<?php echo $id_curso; ?>">
                            <label><input type="checkbox" name="data_detalle" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Detalle</label>
                            <br>
                            <label><input type="checkbox" name="data_forma_pago" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Formas de pago Bancos/ TigoMoney</label>
                            <br>
                            <label><input type="checkbox" name="data_reporte_pago" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Reportar Pago</label>
                            <br>
                            <label><input type="checkbox" name="data_whatsapp" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; WhatsApp</label>
                            <br>
                            <label><input type="checkbox" name="data_direccion" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Direcci&oacute;n</label>
                            <br>
                            <label><input type="checkbox" name="data_link_curso" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Link Detalle curso</label>
                            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                        </form>
                    </td>
                    <td>
                        <b class="btn btn-success" onclick="generar_post('<?php echo $id_curso; ?>');">GENERAR POST</b>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

<!-- generar_post -->
<script>
    function generar_post(id_curso) {
        $("#TITLE-modgeneral").html('GENERAR POST');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        var form = $("#FORM-g-post-"+id_curso).serialize();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-facebook-post.generar_post.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
