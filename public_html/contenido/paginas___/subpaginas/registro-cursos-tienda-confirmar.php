<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">
                    <div class="areaRegistro1 ftb-registro-5">
                        <div class="row" style="color: #464646;margin-top: 40px;font-size: 24px;font-weight: 600;font-family: sans-serif;border-bottom: 2px solid #32b313;padding-bottom: 10px;">
                            PROCEDIMIENTO DE REGISTRO A LOS CURSOS
                        </div>
                        <div>
                            <h3 style="background: #DDD;color: #444;margin-top: 20px;padding: 7px 0px;text-align: center;border-radius: 7px;border: 1px solid #bfbfbf;">Verificaci&oacute;n de datos</h3>
                            <p>
                                Por favor verifica que los datos esten correctos y presiona el boton 'FINALIZAR REGISTRO'. En caso de que exista algun error en los datos presione el boton 'MODIFICAR DATOS'.
                            </p>

                            <style>
                                .table-aux-static {
                                    width: 70%;
                                    margin: auto;
                                }

                                @media screen and (max-width: 650px) {
                                    .table-aux-static {
                                        width: 100% !important;
                                        margin: auto;
                                    }

                                    .areaRegistro1 td {
                                        float: left;
                                        width: 50%;
                                        text-align: left !important;
                                    }
                                }

                                .table-confirm td {
                                    padding: 12px !important;
                                    font-size: 14pt;
                                }
                            </style>
                            <br>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <div class="alert alert-danger">
                                        <strong><i class="fa fa-exclamation-triangle"></i> IMPORTANTE!</strong>
                                        <br>
                                        Debe verificar si su <b>correo</b> y <b>celular</b> esten correctos, ya que el material y su certificado se le enviar&aacute;n v&iacute;a correo electr&oacute;nico.
                                    </div>
                                    <table class="table table-bordered table-striped table-confirm">
                                        <tr>
                                            <td>
                                                <b>C.I.:</b>
                                            </td>
                                            <td>
                                                <?php echo post('ci_p1'); ?> <?php echo post('ci_expedido_p1'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Nombres:</b>
                                            </td>
                                            <td>
                                                <?php echo post('nombres_p1'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Apellidos:</b>
                                            </td>
                                            <td>
                                                <?php echo post('apellidos_p1'); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        if (post('prefijo_p1') !== '') {
                                        ?>
                                            <tr>
                                                <td>
                                                    <b>Prefijo:</b>
                                                </td>
                                                <td>
                                                    <?php echo post('prefijo_p1'); ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <b>Departamento:</b>
                                            </td>
                                            <td>
                                                <?php
                                                $rqddp1 = query("SELECT nombre FROM departamentos WHERE id='" . post('id_dep_p1') . "' LIMIT 1 ");
                                                $rqddp2 = fetch($rqddp1);
                                                echo $rqddp2['nombre'];
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Correo:</b>
                                            </td>
                                            <td>
                                                <?php echo post('correo_p1'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Celular:</b>
                                            </td>
                                            <td>
                                                <?php echo post('tel_cel_p1'); ?>
                                            </td>
                                        </tr>

                                        <?php
                                        if (post('nombre_institucion') !== '') {
                                        ?>
                                            <tr>
                                                <td>
                                                    <b>Instituci&oacute;n / Empresa:</b>
                                                </td>
                                                <td>
                                                    <?php echo post('nombre_institucion'); ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (post('telefono_institucion') !== '') {
                                        ?>
                                            <tr>
                                                <td>
                                                    <b>Tel&eacute;fono:</b>
                                                </td>
                                                <td>
                                                    <?php echo post('telefono_institucion'); ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (post('razon_social') !== '') {
                                        ?>
                                            <tr>
                                                <td>
                                                    <b>Raz&oacute;n Social:</b>
                                                </td>
                                                <td>
                                                    <?php echo post('razon_social'); ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (post('nit') !== '') {
                                        ?>
                                            <tr>
                                                <td>
                                                    <b>NIT:</b>
                                                </td>
                                                <td>
                                                    <?php echo post('nit'); ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 10px 0px;text-align: center;">

                                                <form action="registro-cursos-tienda-completado.html" method="post" onsubmit="return verificar_datos();">
                                                    <?php
                                                    $correo_proceso_registro = post('correo_p1');
                                                    $celular_proceso_registro = post('celular_p1');
                                                    ?>
                                                    <input type="hidden" name="nombres_p1" value="<?php echo post('nombres_p1'); ?>" />
                                                    <input type="hidden" name="apellidos_p1" value="<?php echo post('apellidos_p1'); ?>" />
                                                    <input type="hidden" name="prefijo_p1" value="<?php echo post('prefijo_p1'); ?>" />
                                                    <input type="hidden" name="correo_p1" value="<?php echo post('correo_p1'); ?>" />
                                                    <input type="hidden" name="ci_p1" value="<?php echo post('ci_p1'); ?>" />
                                                    <input type="hidden" name="ci_expedido_p1" value="<?php echo post('ci_expedido_p1'); ?>" />
                                                    <input type="hidden" name="tel_cel_p1" value="<?php echo post('tel_cel_p1'); ?>" />
                                                    <input type="hidden" name="id_dep_p1" value="<?php echo post('id_dep_p1'); ?>" />

                                                    <input type="hidden" name="correo_proceso_registro" value="<?php echo $correo_proceso_registro; ?>" />
                                                    <input type="hidden" name="celular_proceso_registro" value="<?php echo $celular_proceso_registro; ?>" />

                                                    <input type="hidden" name="nombre_institucion" value="<?php echo post('nombre_institucion'); ?>" />
                                                    <input type="hidden" name="telefono_institucion" value="<?php echo post('telefono_institucion'); ?>" />
                                                    <input type="hidden" name="razon_social" value="<?php echo post('razon_social'); ?>" />
                                                    <input type="hidden" name="nit" value="<?php echo post('nit'); ?>" />

                                                    <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>" />
                                                    <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>" />
                                                    <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>" />
                                                    <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>" />
                                                    <input type="hidden" name="cod_descuento" value="<?php echo post('cod_descuento'); ?>" />
                                                    <input type="hidden" name="id_cur_free" value="<?php echo post('id_cur_free'); ?>" />

                                                    <input type="hidden" name="entidad_cual_se_postula" value="<?php echo post('entidad_cual_se_postula'); ?>" />
                                                    <input type="hidden" name="lugar_de_trabajo" value="<?php echo post('lugar_de_trabajo'); ?>" />


                                                    <?php if (isset_post('profesion')) { ?>
                                                        <input type="hidden" name="profesion" value="<?php echo post('profesion'); ?>" />
                                                        <input type="hidden" name="fecha_nacimiento" value="<?php echo post('fecha_nacimiento'); ?>" />
                                                        <input type="hidden" name="direccion" value="<?php echo post('direccion'); ?>" />
                                                        <input type="hidden" name="genero" value="<?php echo post('genero'); ?>" />
                                                    <?php } ?>

                                                    <?php if (isset_post('certificado-fisico') && post('certificado-fisico') == '1') { ?>
                                                        <input type="hidden" name="certificado-fisico" value="<?php echo post('certificado-fisico'); ?>" />
                                                        <input type="hidden" name="direccion_envio_cert_fisico" value="<?php echo post('direccion_envio_cert_fisico') . ', Persona a recoger: ' . post('envio_cert_persona_a_recoger'); ?>" />
                                                    <?php } ?>

                                                    <?php if (isset_post('modalidad-presencial')) { ?>
                                                        <input type="hidden" name="modalidad-presencial" value="1" />
                                                    <?php } ?>
                                                    <?php if (isset_post('modalidad-virtual-vivo')) { ?>
                                                        <input type="hidden" name="modalidad-virtual-vivo" value="1" />
                                                    <?php } ?>
                                                    <?php if (isset_post('modalidad-virtual-grabado')) { ?>
                                                        <input type="hidden" name="modalidad-virtual-grabado" value="1" />
                                                    <?php } ?>


                                                    <input type="hidden" name="registrar" value="true" />
                                                    <input type="submit" class="btn btn-success" value='FINALIZAR REGISTRO' style="color:#FFF;color: #FFF;width: auto;padding: 10px 25px;border-radius: 7px;" name='inscripcion' />
                                                </form>

                                                <br />

                                                <b class="btn btn-warning" style="color:#FFF;color: #FFF;width: auto;padding: 10px 25px;border-radius: 7px;" onclick="history.back();">MODIFICAR DATOS</b>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <br />
                        <br />

                        <br />
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>

<script>
    function verificar_datos() {
        if (confirm('SU CORREO ELECTRONICO ES EL CORRECTO ? \n\n <?php echo post('correo_p1'); ?> \n\n Si es asi presione "ACEPTAR"')) {
            return true;
        } else {
            return false;
        }
    }
</script>