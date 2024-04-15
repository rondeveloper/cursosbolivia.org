<?php
/* datos de formulario post */
$id_curso = post('id_curso');
$nro_participantes = post('nro_participantes');
$id_turno = post('id_turno');

/* datos del curso */
$titulo_identificador_curso = $get[3];
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);

/* registro de participantes */
$sw_inscripcion = false;


/* cupon */
if(isset_post('cupon_cod') && post('cupon_cod')!=''){
    $_SESSION['cupon_cod'] = post('cupon_cod');
}

/* id_cur_free */
if(isset_post('id_cur_free') && post('id_cur_free')!='0'){
    $_SESSION['id_cur_free'] = post('id_cur_free');
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">



<div class="box_seccion_a" style="width:100%;">
    <div class="seccion_a">
        <div class="contenido_seccion white-content-one">


            <?php
            if (isset_post('inscripcion-factura')) {
                ?>

                <div class="areaRegistro1 ftb-registro-5">

                    <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>
                    <p>
                        Llena el siguiente formulario para poder participar del curso.
                    </p>

                    <br/>

                    <form action="registro-curso-p5.html" method="post">

                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>

                        <div>
                            <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">Datos de la Instituci&oacute;n <span style="color:gray;font-weight:normal;">(Opcional)</span></h3>

                            <p>
                                Ingresa los datos de la instituci&oacute;n a la que pertenecen los participantes:
                            </p>

                            <table style="width:70%;margin:auto;">
                                <tr>
                                    <td>
                                        <b>Instituci&oacute;n / Empresa:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='nombre_institucion' placeholder="Opcional..."/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Tel&eacute;fono:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='telefono_institucion' placeholder="Opcional..."/>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">Datos de Facturaci&oacute;n</h3>

                            <p>
                                Ingresa los datos de facturaci&oacute;n:
                            </p>

                            <table style="width:70%;margin:auto;">
                                <tr>
                                    <td>
                                        <b>Factura a nombre de:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='razon_social' placeholder="Factura a nombre de..."/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>N&uacute;mero NIT:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='nit' placeholder="NIT..."/>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <br/>
                        <br/>

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
                        
                        <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>"/>
                        <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>"/>
                        <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>"/>
                        <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>"/>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='inscripcion'/>
                                </div>
                            </div>
                        </div>
                        
                        <br/>

                    </form>

                </div>

                <?php
            } else {
                ?>
                <div class='row'>
                    <div class='panel'>
                        <div class='panel-body'>

                            <h1>Procesando inscripci&oacute;n</h1>
                            <p>...</p>
                            <br/>
                            <br/>

                            <form action="registro-curso-p5.html" method="post" name="form_regiter">

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
                                
                                <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>"/>
                                <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>"/>
                                <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>"/>
                                <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>"/>
                                
                                <input type="hidden" name="inscripcion" value="FINALIZAR INSCRIPCION"/>

                            </form>

                            <script>
                                document.form_regiter.submit();
                            </script>

                        </div>    
                    </div>
                </div>
                <?php
            }
            ?>
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