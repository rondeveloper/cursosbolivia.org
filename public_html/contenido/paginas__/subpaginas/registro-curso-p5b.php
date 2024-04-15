<?php
/* datos de formulario post */
$id_curso = post('id_curso');
$nro_participantes = post('nro_participantes');
$id_turno = post('id_turno');

/* datos del curso */
$titulo_identificador_curso = $get[3];
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

<div class="box_seccion_a" style="width:100%;">
    <div class="seccion_a">
        <div class="contenido_seccion white-content-one">

            <div class="areaRegistro1 ftb-registro-5">
                <form action="registro-curso-p5.html" method="post">

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
    border: 1px solid #bfbfbf;">Modificaci&oacute;n de datos</h3>

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
                                    <input class="form-control" type='text' size='40' name='correo_proceso_registro' placeholder="..." value="<?php echo $correo_proceso_registro; ?>"/>
                                </td>
                            </tr>
                            <input type='hidden' name='celular_proceso_registro' value="<?php echo $celular_proceso_registro; ?>"/>
                            <?php
                            for ($p = 1; $p <= $nro_participantes; $p++) {
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
                                        <input class="form-control" type='text' size='40' name='nombres_p<?php echo $p; ?>' placeholder="Nombres..." value="<?php echo post('nombres_p' . $p); ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Apellidos:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='apellidos_p<?php echo $p; ?>' placeholder="Apellidos..." value="<?php echo post('apellidos_p' . $p); ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Prefijo profesion:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='prefijo_p<?php echo $p; ?>' placeholder="Sr. / Sra. / Dr. / Arq. / Ing. / ..." value="<?php echo post('prefijo_p' . $p); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>C.I.:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='celular_p<?php echo $p; ?>' placeholder="N&uacute;mero de C.I..." value="<?php echo post('celular_p' . $p); ?>"/>
                                        <input type='hidden' name='ci_expedido_p<?php echo $p; ?>' value="<?php echo post('ci_expedido_p' . $p); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Correo:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='correo_p<?php echo $p; ?>' placeholder="Correo..." value="<?php echo post('correo_p' . $p); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $text_part; ?><b>Celular:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='tel_cel_p<?php echo $p; ?>' placeholder="N&uacute;mero de celular..." value="<?php echo post('tel_cel_p' . $p); ?>"/>
                                    </td>
                                </tr>
                                <input type="hidden" name="id_dep_p<?php echo $p; ?>" value="<?php echo post('id_dep_p' . $p); ?>"/>
                                <?php
                            }
                            ?>

                            <?php
                            if (isset_post('razon_social') && isset_post('nit')) {
                                ?>
                                <tr>
                                    <td>
                                        <b>Instituci&oacute;n / Empresa:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='nombre_institucion' placeholder="..." value="<?php echo post('nombre_institucion'); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Tel&eacute;fono:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='telefono_institucion' placeholder="..." value="<?php echo post('telefono_institucion'); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Raz&oacute;n Social:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='razon_social' placeholder="Razon social..." value="<?php echo post('razon_social'); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>NIT:</b>
                                    </td>
                                    <td>
                                        <input class="form-control" type='text' size='40' name='nit' placeholder="NIT..." value="<?php echo post('nit'); ?>"/>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                                <tr>
                                    <td colspan="2" style="padding: 10px 0px;text-align: center;">
                                        <input type="submit" class="btn btn-success" value='PROCEDER' style="color:#FFF;color: #FFF;
    width: auto;
    padding: 10px 25px;
    border-radius: 7px;" name='inscripcion'/>
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
                    <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>"/>
                    
                    <input type="hidden" name="id_departamento_envio" value="<?php echo post('id_departamento_envio'); ?>"/>
                    <input type="hidden" name="direccion_envio" value="<?php echo post('direccion_envio'); ?>"/>
                    <input type="hidden" name="destinatario_envio" value="<?php echo post('destinatario_envio'); ?>"/>
                    <input type="hidden" name="celular_envio" value="<?php echo post('celular_envio'); ?>"/>
                    
                    <br/>

                </form>

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