<?php
/* datos */
$codigo_registro = $get[2];
$hashcod_registro = $get[3];

$rqrc1 = query("SELECT * FROM cursos_proceso_registro WHERE codigo='$codigo_registro' AND hash_cod='$hashcod_registro' ");
if (num_rows($rqrc1) == 0) {
    echo "<script>alert('Acceso denegado!');</script>";
    exit;
}
$rqrc2 = fetch($rqrc1);
$id_proceso_registro = $rqrc2['id'];

/* datos de formulario post */
$id_curso = $rqrc2['id_curso'];
$nro_participantes = $rqrc2['cnt_participantes'];
$id_modo_pago = $rqrc2['id_modo_pago'];
$id_turno = $rqrc2['id_turno'];

/* datos del curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
$titulo_identificador_curso = $curso['titulo_identificador'];

if(isset_post('reportar-pago')){
        
    /* imagen de deposito */
    $monto_deposito = post('monto_deposito');
    $name_arch = 'imagen_deposito';
    $archivo_name = $_FILES[$name_arch]['name'];
    $archivo_type = $_FILES[$name_arch]['type'];
    $archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
    $archivo_size = $_FILES[$name_arch]['size'];
    $arext1 = explode('.', $archivo_name);
    $archivo_extension = strtolower($arext1[count($arext1) - 1]);
    $archivo_new_name = "deposito-" . date("ymd") . "-" . rand(99, 999) . "";

    if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {

        if ($archivo_size > (7 * 1048576)) {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                El archivo no debe superar los ' . 7 . ' Megabyte(s).
              </div>';
            echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');location.href='registro-curso-infosicoes/$url_curso.html';</script>";
            exit;
        } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='registro-curso-infosicoes/$url_curso.html';</script>";
            exit;
        } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='registro-curso-infosicoes/$url_curso.html';</script>";
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

                //query("UPDATE articulos SET imagen='$archivo_new_name_w_extension' WHERE id='$id_articulo' LIMIT 1 ");
                $mensaje = '<div class="alert alert-success alert-dismissible">
                <h4><i class="fa fa-thumbs-up"></i> Exito!</h4>
                El registro se realizo correctamente.
              </div>';
            }
        }
    } else {
        echo "<script>alert('Es necesario que se ingrese la foto del deposito para completar el registro!');location.href='$dominio';</script>";
        exit;
    }
    
    
    query("UPDATE cursos_proceso_registro SET imagen_deposito='$archivo_new_name_w_extension',monto_deposito='$monto_deposito',sw_pago_enviado='1',paydata_fecha=NOW(),paydata_id_administrador='1' WHERE id='$id_proceso_registro' LIMIT 1 ");
    echo "<script>alert('El registro se completo exitosamente!');location.href='$dominio';</script>";
    exit;
    
}

?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

<div class="box_seccion_a" style="width:100%;">
    <div class="seccion_a">
        <div class="contenido_seccion white-content-one">

            <div class="areaRegistro1 ftb-registro-5">
                <div>
                    <h3 class="tit-02">Inscripci&oacute;n de participantes para curso Infosicoes</h3>
                    <br/>
                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>
                        <h3 style="background:#DDD;color:#444;margin-top: 20px;">VERIFICACI&Oacute;N DE PAGO</span></h3>
                        <div class="row">

                            <div class="col-md-6">
                                <p>
                                    Es necesario que nos envie una <b>foto del deposito bancario</b> para que el proceso de inscripci&oacute;n se complete correctamente.
                                    <br/>
                                    Puedes subir la foto del deposito en el siguiente formulario: (solo se admiten imagenes formato PNG y JPG)
                                </p>
                                <br/>
                                <table style="width:70%;margin:auto;">
                                    <tr>
                                        <td>
                                            <b>Monto en Bolivianos del deposito bancario:</b>
                                            <br/>
                                            <br/>
                                            <input class="form-control" type='number' name='monto_deposito' required="required"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Foto del deposito bancario:</b>
                                            <br/>
                                            <br/>
                                            <input class="form-control" type='file' accept="image/*" name='imagen_deposito' required="required"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    Usted selecciono la modalidad de pago por deposito bancario, una vez se envie el reporte del deposito 
                                    se le enviar&aacute; la ficha de inscripci&oacute;n a su correo electronico, con el cual podra asistir al curso.
                                </p>
                                <div class="text-center">
                                    <img src="http://boliviaemprende.com/wp-content/uploads/2015/08/Banco-Union-atencion.jpg" style="width:50%;margin:10px auto;border-radius:10px;">
                                </div>
                            </div>
                        </div>

                        <br/>
                        <br/>

                        <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                        <input type="hidden" name="nro_participantes" value="<?php echo $nro_participantes; ?>"/>
                        <input type="hidden" name="id_modo_pago" value="<?php echo $id_modo_pago; ?>"/>
                        <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>"/>

                        <?php
                        $correo_proceso_registro = post('correo_proceso_registro');
                        $celular_proceso_registro = post('celular_proceso_registro');
                        if ($nro_participantes == 1) {
                            $correo_proceso_registro = post('correo_p1');
                            $celular_proceso_registro = post('celular_p1');
                        }
                        for ($p = 1; $p <= $nro_participantes; $p++) {
                            ?>
                            <input type="hidden" name="nombres_p<?php echo $p; ?>" value="<?php echo post('nombres_p' . $p); ?>"/>
                            <input type="hidden" name="apellidos_p<?php echo $p; ?>" value="<?php echo post('apellidos_p' . $p); ?>"/>
                            <input type="hidden" name="prefijo_p<?php echo $p; ?>" value="<?php echo post('prefijo_p' . $p); ?>"/>
                            <input type="hidden" name="correo_p<?php echo $p; ?>" value="<?php echo post('correo_p' . $p); ?>"/>
                            <input type="hidden" name="celular_p<?php echo $p; ?>" value="<?php echo post('celular_p' . $p); ?>"/>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="correo_proceso_registro" value="<?php echo $correo_proceso_registro; ?>"/>
                        <input type="hidden" name="celular_proceso_registro" value="<?php echo $celular_proceso_registro; ?>"/>

                        <input type="hidden" name="nombre_institucion" value="<?php echo post('nombre_institucion'); ?>"/>
                        <input type="hidden" name="telefono_institucion" value="<?php echo post('telefono_institucion'); ?>"/>
                        <input type="hidden" name="razon_social" value="<?php echo post('razon_social'); ?>"/>
                        <input type="hidden" name="nit" value="<?php echo post('nit'); ?>"/>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='reportar-pago'/>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

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


<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
?>