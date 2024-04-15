<?php
/* data get */
$id_proceso_registro = $get[3];
$hash = $get[2];
if ($hash !== md5('idr-' . $id_proceso_registro)) {
    echo "<script>location.href='$dominio';</script>";exit;
}

$sw_proceso_de_envio_realizado = false;
if (isset_post('finalizar-registro')) {
    /* comprobante de pago */
    $dat_id_banco = post('id_banco');
    /* imagen de deposito */
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
    $archivo_new_name = "deposito-" . date("ymd") . "-" . rand(99, 999) . "";
    $sw_pago_enviado = '1';

    /* modo pago / banco */
    if ($dat_id_banco == 'tigomoney') {
        $id_modo_pago = 5;
        $id_cuenta_banco = 0;
    } elseif ($dat_id_banco == 'paypal') {
        $id_modo_pago = 11;
        $id_cuenta_banco = 0;
    } else {
        $id_modo_pago = 3;
        $id_cuenta_banco = (int) post('id_cuenta_banco');
    }

    if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {
        if ($archivo_size > (7 * 1048576)) {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                El archivo no debe superar los ' . 7 . ' Megabyte(s).
              </div>';
            echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');location.href='$url_return_fail';</script>";
            exit;
        } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='$url_return_fail';</script>";
            exit;
        } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
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
                $sw_proceso_de_envio_realizado = true;
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
    query("UPDATE cursos_proceso_registro SET id_modo_pago='$id_modo_pago',imagen_deposito='$archivo_new_name_w_extension',monto_deposito='$monto_deposito',sw_pago_enviado='$sw_pago_enviado',id_banco='$id_cuenta_banco',transaccion_id='$transaccion_id',paydata_fecha=NOW(),paydata_id_administrador='1',pago_id_departamento='$ciudad_pago',pago_fechahora='$fecha_pago' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    query("UPDATE cursos_participantes SET sw_pago='$sw_pago_enviado',id_modo_pago='$id_modo_pago' WHERE id_proceso_registro='$id_proceso_registro' ");

    /* PARTICIPANTES DEL CURSO */
    logcursos('ENVIO IMAGEN DEPOSITO DE PAGO', 'participante-registro', 'participante', $id_participante);
}

/* proceso registro */
$rqdprp1 = query("SELECT id_curso,codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqdprp2 = fetch($rqdprp1);
$id_curso = $rqdprp2['id_curso'];
$codigo_de_registro = $rqdprp2['codigo'];

/* datos del curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' AND estado IN (1,2) ORDER BY FIELD(estado,1,2),id DESC limit 1 ");
$curso = fetch($rq1);
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
        background: #d9faff;
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
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <?php
                                include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                ?>
                            </div>
                            <?php
                            if($sw_proceso_de_envio_realizado){
                                ?>
                                <h3 class="titulo-pagreg">REPORTE DE PAGO ENVIADO</h3>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <?php
                                        $rqdcl1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                        $rqdcl2 = fetch($rqdcl1);
                                        $nombre_cliente = $rqdcl2['nombres'] . ' ' . $rqdcl2['apellidos'];
                                        ?>
                                        <br>
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
                                                        <br/>
                                                        &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }else{
                                ?>
                                <h3 class="titulo-pagreg">Modificaci&oacute;n de reporte de pago</h3>
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
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="titulo-pagreg">Reporte de pago</h3>
                                    <p class="text-center">
                                        Para modificar su selecci&oacute;n seleccione el metodo de pago que m&aacute;s le convenga <b style="color:green;">haciendo clic sobre alguna de las imagenenes referencia a metodos de pago</b>.
                                        <br>
                                        <b>Posteriormente presione el bot&oacute;n SUBIR COMPROBANTE</b>
                                    </p>

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <style>
                                                .img-mod-pay{
                                                    width: 110px;
                                                    height: 90px;
                                                    cursor: pointer;
                                                    transition: .3s;
                                                }
                                                .img-mod-pay:hover{
                                                    width: 105px;
                                                    height: 85px;
                                                    padding: 10px;
                                                    background: #8bdc77;
                                                    transition: .3s;
                                                }
                                                @media (max-width: 500px){
                                                    .img-mod-pay, .img-mod-pay:hover {
                                                        width: 80px;
                                                        height: 70px;
                                                        padding: 5px;
                                                        margin-bottom: 5px;
                                                    }
                                                }
                                            </style>
                                            <script>
                                                function show_form_pay(data) {
                                                    $('#CONTENT-formpay').html('<b style="font-size:12pt;">Cargando...</b>');
                                                    $(".content-wvid").animate({scrollTop: $(".ancla-forma-pago").offset().top}, 500);
                                                    $('#COLLAPSE-formpay').collapse();
                                                    var celular_participante = $('#input_celular_participante').val();
                                                    $.ajax({
                                                        url: 'contenido/paginas/ajax/ajax.registro-curso.show_form_pay.php',
                                                        data: {cod: '<?php echo $id_curso; ?>', data: data, celular_participante: celular_participante},
                                                        type: 'POST',
                                                        dataType: 'html',
                                                        success: function (data) {
                                                            $('#CONTENT-formpay').html(data);
                                                        }
                                                    });                                                    
                                                }
                                            </script>
                                            <br>
                                            <br>
                                            <div style="min-height: 470px;">
                                                <div class="" style="
                                                     color: #494444;
                                                     background: #f8f8f8;
                                                     padding: 7px 5px;
                                                     border: 1px solid #c7c7c7;
                                                     font-size: 17pt;
                                                     font-weight: bold;
                                                     text-align: left;
                                                     ">FORMAS DE PAGO:</div>
                                                <div class="text-center" style="background: #f0f0f0;padding: 10px 0px;border: 1px solid #d8d8d8;border-top: 0px;">
                                                    <img src="contenido/imagenes/bancos/tigo-money.jpg" class="img-mod-pay" onclick="show_form_pay('tigomoney');"/>
    <!--                                                    <img src="contenido/imagenes/bancos/visa.jpg" class="img-mod-pay" onclick="show_form_pay('visa');"/>
                                                    <img src="contenido/imagenes/bancos/mastercard.jpg" class="img-mod-pay" onclick="show_form_pay('mastercard');"/>-->
                                                    <img src="contenido/imagenes/bancos/paypal.jpg" class="img-mod-pay" onclick="show_form_pay('paypal');"/>
                                                    <?php
                                                    /* metodos de pago */
                                                    $rqb1 = query("SELECT * FROM bancos WHERE estado=1 ");
                                                    while ($rqb2 = fetch($rqb1)) {
                                                        ?>
                                                        <img src="contenido/imagenes/bancos/<?php echo $rqb2['imagen']; ?>" class="img-mod-pay" onclick="show_form_pay('<?php echo $rqb2['id']; ?>');"/>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div style="
                                                     font-size: 12pt;
                                                     border: 1px solid #c7c7c7;
                                                     padding: 5px;
                                                     color: #fe0000;
                                                     text-transform: uppercase;font-weight: bold;
                                                     " class="text-center ancla-forma-pago">
                                                    Haga clic en alguna de las anteriores imagenes para continuar
                                                </div>
                                                <div id="COLLAPSE-formpay" class="collapse"><div id="CONTENT-formpay" style="border: 1px solid #cbcbcb;border-top: 0px;padding: 20px 30px;"></div></div>
                                                <br>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <br/>
                            <br/>
                            <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                            <input type="hidden" name="id_modo_pago" value="<?php echo $id_modo_pago; ?>"/>
                        </form>
                    </div>
                    <hr/>
                </div>
            </div>
        </div>



    </section>
</div>                     



<script>
    function habilitar_participantes(nro) {

        if (nro > 1) {
            $("#correo_part").css("display", "block");
            $("#cel_part").css("display", "block");
        } else {
            $("#correo_part").css("display", "none");
            $("#cel_part").css("display", "none");
        }

        for (var i = 1; i <= 7; i++) {

            if (i <= nro) {
                $("#box-participante-" + i).css("display", "block");
            } else {
                $("#box-participante-" + i).css("display", "none");
            }
        }
    }
</script>

<script>
    function checkParticipante(dat, p) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    $("#nombres_p" + p).val(data_json_parsed['nombres']);
                    $("#apellidos_p" + p).val(data_json_parsed['apellidos']);
                    $("#correo_p" + p).val(data_json_parsed['correo']);
                    $("#prefijo_p" + p).val(data_json_parsed['prefijo']).change();
                }
            }
        });
    }
</script>



<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
