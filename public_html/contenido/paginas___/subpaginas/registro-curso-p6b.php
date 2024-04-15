<?php
/* datos de formulario post */
$id_curso = post('id_curso');
$nro_participantes = post('nro_participantes');
$id_modo_pago = post('id_modo_pago');
$id_turno = post('id_turno');

/* datos del curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
$titulo_identificador_curso = $curso['titulo_identificador'];
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>

                        <?php
                        $form_action = "registro-curso-p7.html";
                        if ($id_modo_pago == 6) {
                            $form_action = "registro-curso-p7-tarjeta.html";
                        }
                        ?>
<!--                <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">-->
                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>
                        <?php
                        if ($id_modo_pago == 3) {
                            ?>
                            <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">VERIFICACI&Oacute;N DE PAGO</span></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <p style="margin-bottom: 5px;">
                                                    Usted selecciono la modalidad de pago por deposito bancario, es necesario que nos envie una <b>foto del deposito bancario</b> para que el proceso de inscripci&oacute;n se complete correctamente.
                                                    <br/>
                                                    Puedes subir la foto del deposito en el siguiente formulario: (solo se admiten imagenes formato PNG y JPG)
                                                </p>
                                                <table style="width:70%;margin:auto;margin-top: 15px;margin-bottom: 7px;">
                                                    <tr>
                                                        <td>
                                                            <b>Monto en Bolivianos del deposito bancario:</b>
                                                            <br/>
                                                            <input class="form-control" type='number' name='monto_deposito' required="required"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Foto del deposito bancario:</b>
                                                            <br/>
                                                            <input class="form-control" type='file' accept="image/*" name='imagen_deposito' required="required"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Observaciones:</b>
                                                            <br/>
                                                            <input class="form-control" type='text' name='observaciones' placeholder="(Opcional...)"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <br/>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='inscripcion'/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <p>
                                                    En caso de a&uacute;n no haber hecho el deposito, tambi&eacute;n puedes subir la foto del deposito posteriormente 
                                                    al enlace que te enviaremos a tu correo
                                                    <?php
                                                    if ($correo_proceso_registro !== '') {
                                                        ?>
                                                        <b>(<?php echo $correo_proceso_registro ?>)</b>
                                                        <?php
                                                    }
                                                    ?>
                                                    . Una vez realizado el reporte de pago a dicho enlace 
                                                    se te enviara la ficha de inscripci&oacute;n correspondiente con el cual podras ingresar al curso.
                                                </p>
                                                <?php
                                                if ($correo_proceso_registro == '') {
                                                    ?>
                                                    <div class="text-center">
                                                        <img src="https://www.infosiscon.com/contenido/imagenes/images/img-pago-deposito.jpg" style="height:115px;margin:0px auto;border-radius:10px;">
                                                    </div>
                                                    <b>Ingresa tu correo electr&oacute;nico:</b>
                                                    <br/>
                                                    <input type='text' class='form-control' name='correo_proceso_registro' placeholder="Email..." required=""/>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input type="hidden" name="correo_proceso_registro" value="<?php echo $correo_proceso_registro; ?>"/>
                                                    <div class="text-center">
                                                        <img src="https://www.infosiscon.com/contenido/imagenes/images/img-pago-deposito.jpg" style="height:151px;margin:10px auto;border-radius:10px;">
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <br/>
                                                <b>Observaciones:</b>
                                                <br/>
                                                <input class="form-control" type='text' name='observaciones' placeholder="(Opcional...)"/>

                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <input type="submit" class="btn btn-warning" value='ENVIAR DEPOSITO POSTERIORMENTE' style="color:#FFF;" name='inscripcion-deposito-posterior'/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

                                        <input type="hidden" name="celular_proceso_registro" value="<?php echo $celular_proceso_registro; ?>"/>

                                        <input type="hidden" name="nombre_institucion" value="<?php echo post('nombre_institucion'); ?>"/>
                                        <input type="hidden" name="telefono_institucion" value="<?php echo post('telefono_institucion'); ?>"/>
                                        <input type="hidden" name="razon_social" value="<?php echo post('razon_social'); ?>"/>
                                        <input type="hidden" name="nit" value="<?php echo post('nit'); ?>"/>
                                    </form>
                                </div>
                            </div>
                            <?php
                        } elseif ($id_modo_pago == 6) {
                            ?>
                            <div>
                                <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">PROCESO DE PAGO CON TARJETA DE DEBITO / CREDITO</h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <p>
                                                        Usted selecciono la modalidad de pago por <b>'tarjeta de debito / credito'</b> para esto nuestra plataforma utiliza la pasarela de 
                                                        pago <b>Khipu</b> el cual te permitira realizar transacciones online de manera segura.
                                                        <br/>
                                                        Ingresa a continuaci&oacute;n el monto en bolivianos para efectuar la transferencia.
                                                    </p>
                                                    <br/>
                                                    <table style="width:70%;margin:auto;">
                                                        <tr>
                                                            <td>
                                                                <b>Monto en Bolivianos de la transferencia:</b>
                                                                <br/>
                                                                <br/>
                                                                <input class="form-control" type='number' name='monto_deposito' required="required"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 text-center">
                                                            <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='inscripcion'/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <p>
                                                    khipu te permite pagar en l&iacute;nea de manera f&aacute;cil y segura usando tarjeta de deb&iacute;to o cr&eacute;dito utilizando el sitio seguro PayMe de la Red Enlace ATC, asegurando tus transacciones.
                                                </p>
                                                <div class="text-center">
                                                    <img src="https://s3.amazonaws.com/static.khipu.com/simple-320.png" style="width:25%;margin:0px auto;border-radius:10px;">
                                                </div>
                                                <br/>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        Khipu
                                                    </div>
                                                </div>
                                            </div>
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

                        <!--                    <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='inscripcion'/>
                                                    </div>
                                                </div>
                                            </div>-->

                        <!--                </form>-->

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