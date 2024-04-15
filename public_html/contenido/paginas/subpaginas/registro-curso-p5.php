<?php
/* datos de formulario post */
$id_curso = post('id_curso');
$nro_participantes = post('nro_participantes');
$id_turno = post('id_turno');

/* datos del curso */
$titulo_identificador_curso = $get[3];
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);


/* cupon */
if (isset_post('cupon_cod') && post('cupon_cod') != '') {
    $_SESSION['cupon_cod'] = post('cupon_cod');
}

/* id_cur_free */
if (isset_post('id_cur_free') && post('id_cur_free') != '0') {
    $_SESSION['id_cur_free'] = post('id_cur_free');
}

?>


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
                <div>
                    <h3 style="background: #DDD;
    color: #444;
    margin-top: 20px;
    padding: 7px 0px;
    text-align: center;
    border-radius: 7px;
    border: 1px solid #bfbfbf;">Verificaci&oacute;n de datos</h3>
                    <p>
                        Por favor verifica que los datos esten correctos y presiona el boton 'Confirmar datos y proceder'.
                    </p>

                    <style>
                        .table-aux-static{
                            width:70%;margin:auto;
                        }
                        @media screen and (max-width: 650px){
                            .table-aux-static{
                                width:100% !important;
                                margin:auto;
                            }
                            .areaRegistro1 td {
                                float: left;
                                width: 50%;
                                text-align: left !important;
                            }
                        }
                    </style>
                    <br>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                    <table class="table table-bordered">

                        <?php
                        $correo_proceso_registro = post('correo_proceso_registro');
                        $celular_proceso_registro = post('celular_proceso_registro');
                        if ($nro_participantes == 1) {
                            $correo_proceso_registro = post('correo_p1');
                            $celular_proceso_registro = post('celular_p1');
                        }
                        ?>
                        <tr>
                            <td>
                                <b>Correo de contacto:</b>
                            </td>
                            <td>
                                <?php echo $correo_proceso_registro; ?>
                            </td>
                        </tr>
                        <?php
                        $sw_button_nextstep = false;
                        for ($p = 1; $p <= $nro_participantes; $p++) {
                            $rqvep1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE nombres LIKE '" . post('nombres_p' . $p) . "' AND apellidos LIKE '" . post('apellidos_p' . $p) . "' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                            if (num_rows($rqvep1) > 0) {
                                $rqvep2 = fetch($rqvep1);
                                $id_proceso_registro = $rqvep2['id_proceso_registro'];
                                $key_participante_registrado = substr(md5(rand(99,9999)),rand(2,12),15);
                                $_SESSION[$key_participante_registrado] = $id_proceso_registro;
                                ?>
                                <tr>
                                    <td colspan="2">
                                        <b style="color:orange;">Mensaje:</b> 
                                        El participante <b><?php echo post('nombres_p' . $p) . ' ' . post('apellidos_p' . $p); ?></b> ya fue registrado para este curso.
                                        <br/>
                                        Continuar con el proceso de registro -> <a href="<?php echo $dominio; ?>registro-curso-p5c/<?php echo $key_participante_registrado; ?>.html">CONTINUAR</a>
                                        <script>
                                            location.href='<?php echo $dominio; ?>registro-curso-p5c/<?php echo $key_participante_registrado; ?>.html';
                                        </script>
                                    </td>
                                </tr>
                                <?php
                            } else {
                                $sw_button_nextstep = true;
                                
                                $text_part = '';
                                if($nro_participantes>1){
                                $text_part = "(Part. $p) ";
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Nombres:</b>
                                    </td>
                                    <td>
                                        <?php echo post('nombres_p' . $p); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Apellidos:</b>
                                    </td>
                                    <td>
                                        <?php echo post('apellidos_p' . $p); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Prefijo:</b>
                                    </td>
                                    <td>
                                        <?php echo post('prefijo_p' . $p); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>C.I.:</b>
                                    </td>
                                    <td>
                                        <?php echo post('celular_p' . $p); ?> <?php echo post('ci_expedido_p' . $p); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Correo:</b>
                                    </td>
                                    <td>
                                        <?php echo post('correo_p' . $p); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Celular:</b>
                                    </td>
                                    <td>
                                        <?php echo post('tel_cel_p' . $p); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

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
                                    
                                    
                <?php
                if ($sw_button_nextstep) {
                    ?>

                                <form action="registro-curso-p5c.html" method="post">
                                    <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                                    <input type="hidden" name="nro_participantes" value="<?php echo $nro_participantes; ?>"/>
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
                                        <input type="hidden" name="ci_expedido_p<?php echo $p; ?>" value="<?php echo post('ci_expedido_p' . $p); ?>"/>
                                        <input type="hidden" name="tel_cel_p<?php echo $p; ?>" value="<?php echo post('tel_cel_p' . $p); ?>"/>
                                        <input type="hidden" name="id_dep_p<?php echo $p; ?>" value="<?php echo post('id_dep_p' . $p); ?>"/>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" name="correo_proceso_registro" value="<?php echo $correo_proceso_registro; ?>"/>
                                    <input type="hidden" name="celular_proceso_registro" value="<?php echo $celular_proceso_registro; ?>"/>

                                    <input type="hidden" name="nombre_institucion" value="<?php echo post('nombre_institucion'); ?>"/>
                                    <input type="hidden" name="telefono_institucion" value="<?php echo post('telefono_institucion'); ?>"/>
                                    <input type="hidden" name="razon_social" value="<?php echo post('razon_social'); ?>"/>
                                    <input type="hidden" name="nit" value="<?php echo post('nit'); ?>"/>
                                    
                                    <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>"/>
                                    <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>"/>
                                    <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>"/>
                                    <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>"/>

                                    <input type="submit" class="btn btn-success" value='CONFIRMAR DATOS Y PROCEDER' style="color:#FFF;color: #FFF;
    width: auto;
    padding: 10px 25px;
    border-radius: 7px;" name='inscripcion'/>
                                </form>

                                <br/>

                                <form action="registro-curso-p5b.html" method="post">
                                    <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                                    <input type="hidden" name="nro_participantes" value="<?php echo $nro_participantes; ?>"/>
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
                                        <input type="hidden" name="ci_expedido_p<?php echo $p; ?>" value="<?php echo post('ci_expedido_p' . $p); ?>"/>
                                        <input type="hidden" name="tel_cel_p<?php echo $p; ?>" value="<?php echo post('tel_cel_p' . $p); ?>"/>
                                        <input type="hidden" name="id_dep_p<?php echo $p; ?>" value="<?php echo post('id_dep_p' . $p); ?>"/>
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" name="correo_proceso_registro" value="<?php echo $correo_proceso_registro; ?>"/>
                                    <input type="hidden" name="celular_proceso_registro" value="<?php echo $celular_proceso_registro; ?>"/>

                                    <input type="hidden" name="nombre_institucion" value="<?php echo post('nombre_institucion'); ?>"/>
                                    <input type="hidden" name="telefono_institucion" value="<?php echo post('telefono_institucion'); ?>"/>
                                    <input type="hidden" name="razon_social" value="<?php echo post('razon_social'); ?>"/>
                                    <input type="hidden" name="nit" value="<?php echo post('nit'); ?>"/>
                                    
                                    <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>"/>
                                    <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>"/>
                                    <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>"/>
                                    <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>"/>

                                    <input type="submit" class="btn btn-warning" value='MODIFICAR DATOS' style="color:#FFF;color: #FFF;
    width: auto;
    padding: 10px 25px;
    border-radius: 7px;" name='modificar-datos'/>
                                </form>
                    <?php
                }
                ?>
                                    
                                    
                                </td>
                            </tr>
                    </table>
                        </div>
                    </div>
                </div>

                <br/>
                <br/>

                <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                <input type="hidden" name="nro_participantes" value="<?php echo $nro_participantes; ?>"/>

                
                
                <br/>
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