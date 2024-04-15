<?php
/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

if (!isset_post('id_proceso_registro')) {
    echo "<script>alert('ERROR');history.back();</script>";
    exit;
}

/* datos del curso */
$id_proceso_registro = post('id_proceso_registro');

/* data proceso registro */
$rqdpr1 = query("SELECT id_curso,monto_deposito,sw_pago_enviado FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqdpr2 = fetch($rqdpr1);
$id_curso = $rqdpr2['id_curso'];
$monto_deposito = $rqdpr2['monto_deposito'];
$__sw_pago_enviado = $rqdpr2['sw_pago_enviado'];

/* curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
$curso = fetch($rq1);

/* participante */
$rqddprt1 = query("SELECT id,sw_pago FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqddprt2 = fetch($rqddprt1);
$id_participante = $rqddprt2['id'];
$__sw_pago = $rqddprt2['sw_pago'];

/* codigo de registro */
$codigo_de_registro = "R00$id_proceso_registro";

/* comprobante de pago */
$id_modo_pago = (int)post('id_modo_pago');
$id_banco = (int)post('id_banco');
$id_cuenta_banco = (int)post('id_cuenta_banco');
$monto_deposito = post('monto_deposito');
$transaccion_id = post('transaccion_id');
$ciudad_pago = post('ciudad');
$fecha_pago = post('fecha') . ' ' . post('hora');
$name_arch = 'imagen_deposito';
$archivo_name = $_FILES[$name_arch]['name'];
$archivo_type = $_FILES[$name_arch]['type'];
$archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
$archivo_size = $_FILES[$name_arch]['size'];
$arext1 = explode('.', $archivo_name);
$archivo_extension = strtolower($arext1[count($arext1) - 1]);
$archivo_new_name = $codigo_de_registro . "-" . date("ymdHi");

if ($id_modo_pago == 5) {
    $id_banco = 0;
    $id_cuenta_banco = 0;
}

if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {

    if ($archivo_size > (7 * 1048576)) {
        echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');location.href='$url_return_fail';</script>";
        exit;
    } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
        echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='$url_return_fail';</script>";
        exit;
    } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
        echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='$url_return_fail';</script>";
        exit;
    } else {

        /* Se carga la clase de redimencion de imagen */
        require_once ("contenido/librerias/classes/Thumbnail.php");

        $thumb = new Thumbnail($archivo_tmp_name);
        if ($thumb->error) {
            echo $thumb->error;
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                No se pudo subir el archivo - ' . $thumb->error . '
              </div>';
        } else {
            $thumb->maxHeight(1200);
            $thumb->save_jpg("", "$archivo_new_name");
            $archivo_new_name_w_extension = $archivo_new_name . ".jpeg";
            rename($archivo_new_name_w_extension, "contenido/imagenes/depositos/$archivo_new_name_w_extension");
            $mensaje = '<div class="alert alert-success alert-dismissible">
                <h4><i class="fa fa-thumbs-up"></i> Exito!</h4>
                El reporte de pago se realizo correctamente.
              </div>';
        }
    }
} else {
    echo "<script>alert('Es necesario que se ingrese la foto del deposito para completar el registro!');history.back();</script>";
    exit;
}

/* proceso registro */
$sw_pago_enviado = '1';
query("UPDATE cursos_proceso_registro SET id_modo_pago='$id_modo_pago',imagen_deposito='$archivo_new_name_w_extension',monto_deposito='$monto_deposito',sw_pago_enviado='$sw_pago_enviado',id_banco='$id_cuenta_banco',transaccion_id='$transaccion_id',paydata_fecha=NOW(),paydata_id_administrador='1',pago_id_departamento='$ciudad_pago',pago_fechahora='$fecha_pago' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
query("UPDATE cursos_participantes SET sw_pago='$sw_pago_enviado',id_modo_pago='$id_modo_pago' WHERE id_proceso_registro='$id_proceso_registro' ");

/* PARTICIPANTES DEL CURSO */
logcursos('ENVIO COMPROBANTE DE PAGO', 'participante-registro', 'participante', $id_participante);

/* matricula estudiante */
$name_fila_matricula = 'imagen_matricula';
if (isset($_FILES[$name_fila_matricula]) && is_uploaded_file($_FILES[$name_fila_matricula]['tmp_name'])) {
    $archivo_name = $_FILES[$name_fila_matricula]['name'];
    $archivo_type = $_FILES[$name_fila_matricula]['type'];
    $archivo_tmp_name = $_FILES[$name_fila_matricula]['tmp_name'];
    $archivo_size = $_FILES[$name_fila_matricula]['size'];
    $arext1 = explode('.', $archivo_name);
    $archivo_extension = strtolower($arext1[count($arext1) - 1]);
    $archivo_new_name = $codigo_de_registro . "-MTe-" . date("ymdHi");

    if ($archivo_size > (7 * 1048576)) {
        echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');location.href='$url_return_fail';</script>";
        exit;
    } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
        echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='$url_return_fail';</script>";
        exit;
    } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
        echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='$url_return_fail';</script>";
        exit;
    } else {

        $thumb = new Thumbnail($archivo_tmp_name);
        if ($thumb->error) {
            echo $thumb->error;
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                No se pudo subir el archivo matricula - ' . $thumb->error . '
              </div>';
        } else {
            $thumb->maxHeight(1200);
            $thumb->save_jpg("", "$archivo_new_name");
            $archivo_new_name_w_extension = $archivo_new_name . ".jpeg";
            rename($archivo_new_name_w_extension, "contenido/imagenes/depositos/$archivo_new_name_w_extension");
            $mensaje = '<div class="alert alert-success alert-dismissible">
                <h4><i class="fa fa-thumbs-up"></i> Exito!</h4>
                La matricula se subio correctamente.
              </div>';
            query("UPDATE cursos_proceso_registro SET imagen_matricula='$archivo_new_name_w_extension' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
        }
    }
}
?>

<style>
    .titulo-pagreg{
        background: #DDD;
        color: #444;
        margin-top: 20px;
        padding: 7px 0px;
        text-align: center;
        border-radius: 7px;
        border: 1px solid #bfbfbf;
    }
    .link-set-fpay{
        background: #46d023 !important;
    }
    .myinput{
        background: #dbffd9;
        padding: 10px 20px;
        height: auto;
        border-radius: 10px;
    }
</style>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>
                        <h3 class="titulo-pagreg">FICHA DE INSCRIPCI&Oacute;N</h3>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <?php
                                $rqdcl1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                $rqdcl2 = fetch($rqdcl1);
                                $nombre_cliente = $rqdcl2['nombres'] . ' ' . $rqdcl2['apellidos'];
                                ?>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td style='padding:5px;'>C&oacute;digo de registro:</td>
                                        <td style='padding:5px;'><?php echo $codigo_de_registro; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Participante:</td>
                                        <td style='padding:5px;'><?php echo $nombre_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Curso:</td>
                                        <td style='padding:5px;'><?php echo $curso['titulo']; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>TOTAL BS:</td>
                                        <td style='padding:5px;'><?php echo $monto_deposito; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Ficha:</td>
                                        <td style='padding:5px;'>
                                            <div class="text-center">
                                                <br/>
                                                <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                        return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                                <br/>
                                                <br/>
                                                <a href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                                <br/>

                                                <?php if ($__sw_pago_enviado == '0' && $__sw_pago == '0') { ?>
                                                    <hr>
                                                    <b class="btn btn-success" onclick="finalizarPago();"><i class="fa fa-ok"></i> FINALIZAR PROCESO DE PAGO</b>
                                                    <br/>
                                                <?php } ?>

                                                <br/>
                                                &nbsp;
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php
                        if ((int) $curso['costo'] > 0) {
                            ?>
                            <div class="panel panel-info">
                                <br>
                                <?php
                                if ((int) $curso['sw_askfactura'] == 1) {
                                    ?>
                                    <p>
                                        En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>, puede llenar los datos de facturaci&oacute;n en el siguiente enlace: <a href="registro-curso-facturacion/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>.html" style="color:#004eff;text-decoration: underline;">datos de facturaci&oacute;n</a>.
                                    </p>
                                    <?php
                                } else {
                                    ?>
                                    <p>
                                        En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>.
                                    </p>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        } else {
                            query("UPDATE cursos_proceso_registro SET monto_deposito='0',id_modo_pago='10' WHERE id='$id_proceso_registro' LIMIT 1 ");
                        }
                        ?>

                        <br/>
                        <br/>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <?php echo $___nombre_del_sitio; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<form id="form-finalizar-pago" action="compra-finalizada.html" method="post">
    <input type="hidden" name="id_proceso_registro" value="<?= $id_proceso_registro ?>">
</form>

<script>
    function finalizarPago() {
        let formulario = document.getElementById('form-finalizar-pago');
        formulario.submit();
    }
</script>

<?php
function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
