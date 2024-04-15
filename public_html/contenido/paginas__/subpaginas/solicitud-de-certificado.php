<?php
/* mensaje */
$mensaje = '';

/* envio de solicitud */
if (isset_post('enviar-solicitud')) {
    $motivo_solicitud = post('motivo_solicitud');
    $motivo_especifico = post('motivo_especifico');
    $modalidad_curso = post('modalidad_curso');
    $nombre_curso = post('nombre_curso');
    $ci = post('ci');
    $ci_extension = post('ci_extension');
    $nombre = post('nombre');
    $apellidos = post('apellidos');
    $prefijo = post('prefijo');
    $correo = post('correo');
    $celular = post('celular');
    $observaciones = post('observaciones');
    $fecha_registro = date("Y-m-d H:i");

    /* imagenes */
    $imagen1 = "";
    $imagen2 = "";
    /* Se carga la clase de redimencion de imagen */
    require_once ("contenido/librerias/classes/Thumbnail.php");

    /* imagen de deposito */
    $name_arch = 'imagen1';
    if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {
        $archivo_name = $_FILES[$name_arch]['name'];
        $archivo_type = $_FILES[$name_arch]['type'];
        $archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
        $archivo_size = $_FILES[$name_arch]['size'];
        $arext1 = explode('.', $archivo_name);
        $archivo_extension = strtolower($arext1[count($arext1) - 1]);
        $archivo_new_name = "img_dep-" . date("ymd") . "-" . rand(99, 999) . "";

        if ($archivo_size > (7 * 1048576)) {
            echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');history.back();</script>";
            exit;
        } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');history.back();</script>";
            exit;
        } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');history.back();</script>";
            exit;
        } else {
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
                $imagen1 = $archivo_new_name_w_extension;
            }
        }
    }
    /* imagen de deposito */
    $name_arch = 'imagen2';
    if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {
        $archivo_name = $_FILES[$name_arch]['name'];
        $archivo_type = $_FILES[$name_arch]['type'];
        $archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
        $archivo_size = $_FILES[$name_arch]['size'];
        $arext1 = explode('.', $archivo_name);
        $archivo_extension = strtolower($arext1[count($arext1) - 1]);
        $archivo_new_name = "img_dep-" . date("ymd") . "-" . rand(99, 999) . "";

        if ($archivo_size > (7 * 1048576)) {
            echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');history.back();</script>";
            exit;
        } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');history.back();</script>";
            exit;
        } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');history.back();</script>";
            exit;
        } else {
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
                $imagen2 = $archivo_new_name_w_extension;
            }
        }
    }

    query("INSERT INTO cursos_solicitudes_de_certificado(
                motivo_solicitud, 
                motivo_especifico, 
                modalidad_curso, 
                nombre_curso, 
                ci, 
                ci_extension, 
                nombre, 
                apellidos, 
                prefijo, 
                correo, 
                celular, 
                observaciones, 
                imagen1, 
                imagen2, 
                fecha_registro, 
                estado
                ) VALUES (
                '$motivo_solicitud',
                '$motivo_especifico',
                '$modalidad_curso',
                '$nombre_curso',
                '$ci',
                '$ci_extension',
                '$nombre',
                '$apellidos',
                '$prefijo',
                '$correo',
                '$celular',
                '$observaciones',
                '$imagen1',
                '$imagen2',
                '$fecha_registro',
                '1'
                )");

    $mensaje = '<div class="alert alert-success">
  <strong>EXITO!</strong> solicitud enviada exitosamente.
</div>';
}
?>
<style>
    .tr-top-f td{
        padding: 12px 12px !important;
    }
    .td-top-f{
        background: #25a6e4;
        color: #FFF;
        font-weight: bold;
        padding: 5px !important;
        text-align: center;
    }
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>SOLICITUD DE CERTIFICADO</h3>
                </div>
                
                <?php echo $mensaje; ?>
                
                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">

                    <form action="" method="post" enctype="multipart/form-data">
                        <table class="table table-top-f table-bordered" style="background: white;">
                            <tr>
                                <td colspan="2" class="td-top-f">
                                    DATOS DE LA SOLICITUD
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Motivo de la solicitud:</b>
                                </td>
                                <td>
                                    <select class="form-control" name="motivo_solicitud" onchange="s_motivo_solicitud();" id="select_motivo">
                                        <option value="cert-no-entregado">NO ME ENTREGARON MI CERTIFICADO</option>
                                        <option value="error-en-cert">EXISTE UN ERROR EN MI CERTIFICADO</option>
                                        <option value="motivo-especifico">OTRO MOTIVO</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="tr-top-f" style="display:none;" id="row_esp_mot">
                                <td>
                                    <b>Especifique el motivo:</b>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="motivo_especifico" placeholder="Especifique el motivo..."/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="td-top-f">
                                    DATOS DEL CURSO
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Modalidad de curso:</b>
                                </td>
                                <td>
                                    <select class="form-control" name="modalidad_curso">
                                        <option value="1">CURSO PRESENCIAL</option>
                                        <option value="2">CURSO VIRTUAL</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Nombre del curso:</b> (*)
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nombre_curso" placeholder="Ingrese el nombre del curso..."  required=""/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="td-top-f">
                                    DATOS DEL PARTICIPANTE
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>C.I.:</b> (*)
                                </td>
                                <td>
                                    <div style="clear:both;;">
                                        <div style="float:left;width:70%;">
                                            <input class="form-control" type='text' name="ci" placeholder="N&uacute;mero de C.I..." required="" value="<?php echo $celular; ?>" onkeyup="checkParticipante(this.value, '<?php echo $p; ?>');"/>
                                        </div>
                                        <div style="float:left;width:30%;">
                                            <select class="form-control" name="ci_extension">
                                                <option value="">...</option>
                                                <option value="LP">LP | La Paz</option>
                                                <option value="CB">CB | Cochabamba</option>
                                                <option value="SC">SC | Santa Cruz</option>
                                                <option value="OR">OR | Oruro</option>
                                                <option value="PS">PT | Potosi</option>
                                                <option value="CH">CH | Chuquisaca</option>
                                                <option value="PD">PA | Pando</option>
                                                <option value="BN">BN | Beni</option>
                                                <option value="TJ">TJ | Tarija</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Nombres:</b> (*)
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nombre" placeholder="Nombres para el certificado..." required=""/>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Apellidos:</b> (*)
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="apellidos" placeholder="Apellidos para el certificado..." required=""/>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Prefijo de profesi&oacute;n:</b>
                                </td>
                                <td>
                                    <select class="form-control" name="prefijo">
                                        <option value="">Sin prefijo</option>
                                        <option value="Lic.">Lic.</option>
                                        <option value="Ing.">Ing.</option>
                                        <option value="Arq.">Arq.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Dra.">Dra.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="Sra.">Sra.</option>
                                        <option value="Stria.">Stria.</option>
                                        <option value="Aux.">Aux.</option>
                                        <option value="Aux. enf.">Aux. enf.</option>
                                        <option value="Tec.">Tec.</option>
                                        <option value="Tec. med.">Tec. med.</option>
                                        <option value="Tec. sup.">Tec. sup.</option>
                                        <option value="Msc. Lic.">Msc. Lic.</option>
                                        <option value="Msc.">Msc.</option>
                                        <option value="Phd. Lic.">Phd. Lic.</option>
                                        <option value="Phd.">Phd.</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="td-top-f">
                                    DATOS DE CONTACTO
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Correo de contacto:</b> (*)
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="correo" placeholder="Ingrese su correo electr&oacute;nico..." required=""/>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Celular de contacto:</b> (*)
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="celular" placeholder="Ingrese el n&uacute;mero de celular..." required=""/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="td-top-f">
                                    DATOS ADICIONALES
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Imagen de deposito 1:</b>
                                </td>
                                <td>
                                    <input type="file" class="form-control" name="imagen1"/>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Imagen de deposito 2:</b>
                                </td>
                                <td>
                                    <input type="file" class="form-control" name="imagen2"/>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td>
                                    <b>Observaciones:</b>
                                </td>
                                <td>
                                    <textarea class="form-control" name="observaciones" placeholder="Observaciones..." style="width:100%;height:70px;resize: none;"></textarea>
                                </td>
                            </tr>
                            <tr class="tr-top-f">
                                <td colspan="2">
                                    <br/>
                                    <input type="submit" class="btn btn-warning btn-lg" name="enviar-solicitud" value="ENVIAR SOLICITUD"/>
                                    <br/>
                                </td>
                            </tr>
                        </table>
                    </form>

                </div>


                <br/>
                <br/>
                <hr/>
                <br/>
                <br/>

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>
                <div class="">
                </div>
            </div>
        </div>
    </section>
</div>                     




<script>
    function s_motivo_solicitud() {
        var m = $("#select_motivo").val();
        if (m === 'motivo-especifico') {
            $("#row_esp_mot").css("display", "table-row");
        } else {
            $("#row_esp_mot").css("display", "none");
        }
    }
</script>