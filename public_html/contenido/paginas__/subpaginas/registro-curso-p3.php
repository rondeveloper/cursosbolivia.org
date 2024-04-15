<?php
/* datos del curso */
$titulo_identificador_curso = $get[3];
$rq1 = query("SELECT * FROM cursos WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (1,2) ORDER BY FIELD(estado,1,2),id DESC limit 1 ");
$curso = fetch($rq1);

/* numero de participantes */
$nro_participantes = abs((int) str_replace('-p', '', $get[2]));
if ($nro_participantes > 20) {
    $nro_participantes = 20;
}

/* id turno */
$id_turno = $get[4];
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>
                        <form action="registro-curso-p4.html" method="post">
                            <div class="row">
                                <?php
                                include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                ?>
                            </div>

                            <?php
                            if ($nro_participantes > 1) {
                                ?>
                                <div style="display:block;">
                                    <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">Datos del encargado en registrar a los participantes</h3>
                                    <p>
                                        Ingresa el correo electr&oacute;nico y n&uacute;mero celular de contacto para el proceso de inscripci&oacute;n. 
                                    </p>
                                    <table style="width:70%;margin:auto;">
                                        <tr>
                                            <td>
                                                <b>Correo:</b>
                                            </td>
                                            <td>
                                                <input class="form-control" type='email' size='80' name='correo_proceso_registro' placeholder="Correo..." required=""/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Celular:</b>
                                            </td>
                                            <td>
                                                <input class="form-control" type='number' size='80' name='celular_proceso_registro' placeholder="Celular..." required=""/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php
                            }

                            $txt_aux1 = "";
                            for ($p = 1; $p <= $nro_participantes; $p++) {
                                if ($p > 1) {
                                    $txt_aux1 = " - participante $p";
                                }

                                /* datos de primer participante */
                                $nombres = '';
                                $apellidos = '';
                                $celular = '';
                                $email = '';
                                if (isset_usuario() && ($p == 1)) {
                                    $id_usuario = usuario('id');
                                    $rqdu1 = query("SELECT nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' LIMIT 1 ");
                                    $rqdu2 = fetch($rqdu1);
                                    $nombres = $rqdu2['nombres'];
                                    $apellidos = $rqdu2['apellidos'];
                                    $celular = $rqdu2['celular'];
                                    $email = $rqdu2['email'];
                                }
                                ?>

                                <div style="display:block;">
                                    <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">Datos para el certificado<?php echo $txt_aux1; ?></h3>
                                    <p>
                                        Ingresa los datos del participante que tomara el curso, <b class="text-danger">estos datos tambi&eacute;n se utilizaran para la emision del certificado</b>:
                                    </p>
                                    <table style="width:70%;margin:auto;">
                                        <tr>
                                            <td style="min-width: 120px;">
                                                <b>C.I.:</b> (*)
                                            </td>
                                            <td>
                                                <div style="clear:both;;">
                                                    <div style="float:left;width:70%;">
                                                        <input class="form-control" type='text' size='80' name='celular_p<?php echo $p; ?>' id='celular_p<?php echo $p; ?>' placeholder="N&uacute;mero de C.I..." required="" value="<?php echo $celular; ?>" onkeyup="checkParticipante(this.value, '<?php echo $p; ?>');"/>
                                                    </div>
                                                    <div style="float:left;width:30%;">
                                                        <select class="form-control" name='ci_expedido_p<?php echo $p; ?>' id='ci_expedido_p<?php echo $p; ?>'>
                                                            <option value="">...</option>
                                                            <option value="LP">LP | La Paz</option>
                                                            <option value="CB">CB | Cochabamba</option>
                                                            <option value="SC">SC | Santa Cruz</option>
                                                            <option value="OR">OR | Oruro</option>
                                                            <option value="PT">PT | Potosi</option>
                                                            <option value="CH">CH | Chuquisaca</option>
                                                            <option value="PD">PA | Pando</option>
                                                            <option value="BN">BN | Beni</option>
                                                            <option value="TJ">TJ | Tarija</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Nombres:</b> (*)
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='nombres_p<?php echo $p; ?>' id='nombres_p<?php echo $p; ?>' placeholder="Nombres..." required="" value="<?php echo $nombres; ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Apellidos:</b> (*)
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='apellidos_p<?php echo $p; ?>' id='apellidos_p<?php echo $p; ?>' placeholder="Apellidos..." required="" value="<?php echo $apellidos; ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Profesi&oacute;n:</b> (*)
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='profesion_p<?php echo $p; ?>' id='profesion_p<?php echo $p; ?>' placeholder="Profesi&oacute;n del participante..." required="" value="" onkeyup="this.value = this.value.toUpperCase()"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Prefijo:</b>
                                            </td>
                                            <td>
                                                <select class="form-control" name='prefijo_p<?php echo $p; ?>' id='prefijo_p<?php echo $p; ?>'>
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
                                            <td>
                                                <b>Correo:</b> (*)
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='correo_p<?php echo $p; ?>' id='correo_p<?php echo $p; ?>' placeholder="Correo..." value="<?php echo $email; ?>" required=""/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Celular:</b> (*)
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='tel_cel_p<?php echo $p; ?>' id='tel_cel_p<?php echo $p; ?>' placeholder="Celular..." value="<?php echo $tel_cel; ?>" required=""/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <i>Los datos con (*) son obligatorios</i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <?php
                            }
                            ?>

                            <?php
                            if ($curso['id_modalidad']=='2') {
                                ?>
                                <div style="display:block;">
                                    <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">Env&iacute;o de certificado</h3>
                                    <p>
                                        En caso de no poder recoger el certificado en nuestras oficinas, podemos realizar un env&iacute;o a la direcci&oacute;n que nos proporcione:
                                    </p>
                                    <table style="width:70%;margin:auto;">
                                        <tr>
                                            <td style="width: 30%;">
                                                <b>Departamento:</b> 
                                            </td>
                                            <td>
                                                <select class="form-control" name='id_departamento_envio'>
                                                    <option value="">...</option>
                                                    <?php
                                                    $rqdd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                                    while ($rqdd2 = fetch($rqdd1)) {
                                                        ?>
                                                        <option value="<?php echo $rqdd2['id']; ?>"><?php echo $rqdd2['nombre']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Direcci&oacute;n:</b> 
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='180' name='direccion_envio' placeholder="Direcci&oacute;n de env&iacute;o..."/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Nombre destinatario:</b>
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='destinatario_envio' placeholder="Nombre de destinatario..."/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Celular destinatario:</b>
                                            </td>
                                            <td>
                                                <input class="form-control" type='text' size='80' name='celular_envio' placeholder="Celular destinatario..."/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php
                            }
                            ?>

                            <br/>
                            <br/>

                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <input type="submit" class="btn btn-success" value='FINALIZAR INSCRIPCION' style="color:#FFF;" name='inscripcion'/>
                                        <?php
                                        if((int)$curso['costo']>0){
                                        ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="submit" class="btn btn-warning" value='SOLICITAR FACTURA' style="color:#FFF;" name='inscripcion-factura'/>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                            <input type="hidden" name="nro_participantes" value="<?php echo $nro_participantes; ?>"/>
                            <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>"/>

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
            success: function(data) {
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
