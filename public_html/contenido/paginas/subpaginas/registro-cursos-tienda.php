<?php
$id_curso =123;
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
                        <form action="registro-cursos-tienda-confirmar.html" method="post" enctype="multipart/form-data" onsubmit="return verificar_datos()">
                            <div class="row" style="color: #464646;margin-top: 40px;font-size: 24px;font-weight: 600;font-family: sans-serif;border-bottom: 2px solid #32b313;padding-bottom: 10px;">
                                PROCEDIMIENTO DE REGISTRO A LOS CURSOS
                            </div>

                            <?php
                            /* RECOMENDACION */
                            if ($curso['sw_recomendaciones'] == '1' && (!isset($get[4]))) {
                                ?>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div style="border: 3px solid #dadada;padding: 10px 20px;border-radius: 10px;margin-top: 19px;background: #ff0000;color: #FFF;text-align: center;font-size: 11pt;padding-bottom: 17px;">
                                            <?php
                                            if ($curso['rec_limitdesc'] == '100') {
                                                ?>
                                                <b style="font-size: 25pt;">CURSO GRATUITO</b>
                                                <?php
                                            } else {
                                                ?>
                                                <b style="font-size: 25pt;">DESCUENTO</b>
                                                <?php
                                            }
                                            ?>
                                            <br>
                                            Obten 1% de descuento por cada recomendaci&oacute;n que realices
                                            <br>
                                            <span style="color:#d8efa0;font-size: 9pt;">( habilitado hasta <?php echo $curso['rec_limitdesc']; ?>% )</span>
                                            <br>
                                            <br>
                                            <a href="recomendar/<?php echo $titulo_identificador_curso; ?>.html" style="    text-decoration: underline;background: #ececec;padding: 3px 15px;color: #1000ff;border-radius: 4px;border: 1px solid #bfbaba;font-size: 9pt;">OBTENER DESCUENTO</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }

                            /* datos de participante cuando esta logeado*/
                            $nombres = '';
                            $apellidos = '';
                            $celular = '';
                            $email = '';
                            if (isset_usuario()) {
                                $id_usuario = usuario('id');
                                $rqdu1 = query("SELECT nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' LIMIT 1 ");
                                $rqdu2 = fetch($rqdu1);
                                $nombres = $rqdu2['nombres'];
                                $apellidos = $rqdu2['apellidos'];
                                $celular = $rqdu2['celular'];
                                $email = $rqdu2['email'];
                            }
                            ?>

                            <input type='hidden' id='id_turno' name='id_turno' value='0'/>

                            <div style="display:block;">
                                <h3 class="titulo-pagreg">Datos para el certificado</h3>
                                <p class="text-center">
                                    Ingresa los datos del participante que tomara el curso, <b style="color:green;">estos datos tambi&eacute;n se utilizaran para la emision del certificado</b>:
                                </p>
                                <br>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <i>Los datos con (*) son obligatorios</i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="min-width: 120px;">
                                                    <b>C.I.:</b> (*)
                                                </td>
                                                <td>
                                                    <div style="clear:both;;">
                                                        <div style="float:left;width:70%;">
                                                            <input class="form-control myinput" type='text' name='ci_p1' id='input_ci_participante' placeholder="N&uacute;mero de C.I..." required="" value="<?php echo $celular; ?>" onkeyup="checkParticipante(this.value);"/>
                                                        </div>
                                                        <div style="float:left;width:30%;">
                                                            <select class="form-control myinput" name='ci_expedido_p1' id='input_ciexpedido_participante'>
                                                                <option value="Cod. QR">Cod. QR | Nueva cedula con c&oacute;digo QR &nbsp;&nbsp; </option>
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
                                                    <input class="form-control myinput" type='text' name='nombres_p1' id='input_nombre_participante' placeholder="Nombres..." required="" value="<?php echo $nombres; ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Apellidos:</b> (*)
                                                </td>
                                                <td>
                                                    <input class="form-control myinput" type='text' name='apellidos_p1' id='input_apellidos_participante' placeholder="Apellidos..." required="" value="<?php echo $apellidos; ?>" onkeyup="this.value = this.value.toUpperCase()"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Prefijo:</b>
                                                </td>
                                                <td>
                                                    <select class="form-control myinput" name='prefijo_p1' id='input_prefijo_participante'>
                                                        <option value="">Sin prefijo</option>
                                                        <option value="Lic.">Lic.</option>
                                                        <option value="Ing.">Ing.</option>
                                                        <option value="Arq.">Arq.</option>
                                                        <option value="Abg.">Abg.</option>
                                                        <option value="Dr.">Dr.</option>
                                                        <option value="Dra.">Dra.</option>
                                                        <option value="Abog.">Abog.</option>
                                                        <option value="Sr.">Sr.</option>
                                                        <option value="Sra.">Sra.</option>
                                                        <option value="Stria.">Stria.</option>
                                                        <option value="Aux.">Aux.</option>
                                                        <option value="Aux. Enf.">Aux. Enf.</option>
                                                        <option value="Tec.">Tec.</option>
                                                        <option value="Tec. Med.">Tec. Med.</option>
                                                        <option value="Tec. Sup.">Tec. Sup.</option>
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
                                                    <input class="form-control myinput" type='email' name='correo_p1' id='input_correo_participante' placeholder="Correo..." value="<?php echo $email; ?>" required=""/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Confirmar correo:</b> (*)
                                                </td>
                                                <td>
                                                    <input class="form-control myinput" type='email' name='correo_2_p1' id='input_correo_2_participante' placeholder="Vuelve a ingresar tu Correo..." value="" required="" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Celular:</b> (*)
                                                </td>
                                                <td>
                                                    <input class="form-control myinput" type='text' name='tel_cel_p1' id='input_celular_participante' placeholder="Celular..." value="<?php echo $tel_cel; ?>" required=""/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Departamento:</b>
                                                </td>
                                                <td>
                                                    <select class="form-control myinput" name='id_dep_p1' id='id_dep_p1'>
                                                        <?php
                                                        $rqddpr1 = query("SELECT id,nombre FROM departamentos WHERE estado='1' ORDER BY orden ASC ");
                                                        while ($rqddpr2 = fetch($rqddpr1)) {
                                                            ?>
                                                            <option value="<?php echo $rqddpr2['id']; ?>"><?php echo $rqddpr2['nombre']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <i>Datos opcionales</i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Entidad al cual se postula:</b>
                                                </td>
                                                <td>
                                                    <input class="form-control myinput" type='text' name='entidad_cual_se_postula' placeholder="Entidad al cual se postula... (opcional)" value=""/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Lugar de trabajo:</b>
                                                </td>
                                                <td>
                                                    <input class="form-control myinput" type='text' name='lugar_de_trabajo' placeholder="Lugar de trabajo... (opcional)" value=""/>
                                                </td>
                                            </tr>

                                            <?php if ($curso['sw_ipelc']=='1') { ?>
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <i>Datos adicionales para certificaci&oacute;n con IPELC</i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Profesión:</b></td>
                                                    <td><input type="text" class="form-control myinput" name="profesion" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Fecha de nacimiento:</b></td>
                                                    <td><input type="date" class="form-control myinput" name="fecha_nacimiento" value="" style="line-height: initial;"></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Dirección:</b></td>
                                                    <td><input type="text" class="form-control myinput" name="direccion" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Género:</b></td>
                                                    <td>
                                                        <select class="form-control myinput" name="genero">
                                                            <option value="M">MASCULINO</option>
                                                            <option value="F">FEMENINO</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            <?php 
                                            /* envio certificado fisico */
                                            $rqvecf1 = query("SELECT costo_envio FROM direnvio_certs WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                            ?>
                                            <?php 
                                            if (num_rows($rqvecf1)>0) { 
                                                ?>
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <i>Certificados</i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Emisi&oacute;n de certificado:</b></td>
                                                    <td style="padding: 10px 20px;line-height: 2.5;">
                                                        <label><input type="radio" name="certificado-fisico" value="0" style="height: 15px;width: 15px;" onchange="change_input_direnvcert();" checked/> &nbsp; CERTIFICADO DIGITAL</label>
                                                        <br>
                                                        <label><input type="radio" name="certificado-fisico" value="1" style="height: 15px;width: 15px;" id="direnvcert-check" onchange="change_input_direnvcert();"/> &nbsp; CERTIFICADO F&Iacute;SICO </label>
                                                    </td>
                                                </tr>
                                                <tr id="tr-env-cert-r1" style="display:none;">
                                                    <td><b>Ciudad de env&iacute;o:</b></td>
                                                    <td>
                                                        <b>Nota:</b> El certificado se mandara por flota a la ciudad seleccionada:
                                                        <select class="form-control myinput" name='direccion_envio_cert_fisico' id="input-direccion-envio-cert">
                                                            <?php
                                                            $rqddpr1 = query("SELECT id,nombre FROM departamentos WHERE estado='1' ORDER BY orden ASC ");
                                                            while ($rqddpr2 = fetch($rqddpr1)) {
                                                                ?>
                                                                <option value="Ciudad de envio: <?php echo $rqddpr2['nombre']; ?>"><?php echo $rqddpr2['nombre']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr id="tr-env-cert-r2" style="display:none;">
                                                    <td><b>Persona a recoger:</b></td>
                                                    <td>
                                                        Ingrese los datos de la persona a recoger el certificado: nombre, ci, celular
                                                        <input  type="text" class="form-control myinput" name="envio_cert_persona_a_recoger" value="" placeholder="Persona a recoger el certificado: nombre/ci/celular..."/>
                                                    </td>
                                                </tr>
                                                <?php 
                                                } 
                                            ?>

                                        </table>
                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-sm-12 text-center" style="padding: 20px;">
                                                    <input type="submit" class="btn btn-lg btn-success" value='PROCEDER' style="border-radius: 7px;" name='subir-comprobante'/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br/>
                            <br/>
                            <input type="hidden" name="id_curso" value="<?php echo $curso['id']; ?>"/>
                        </form>
                    </div>
                    <hr/>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function verificar_datos() {
        var input_nombre_participante = $("#input_nombre_participante").val();
        var input_apellidos_participante = $("#input_apellidos_participante").val();
        var input_ci_participante = $("#input_ci_participante").val();
        var input_ciexpedido_participante = $("#input_ciexpedido_participante").val();
        var input_correo_participante = $("#input_correo_participante").val();
        var input_correo_2_participante = $("#input_correo_2_participante").val();
        var input_celular_participante = $("#input_celular_participante").val();

        if(input_correo_participante!=input_correo_2_participante){
            alert('LOS CORREOS NO COINCIDEN');
            return false;
        }else{
            return true;
        }
    }
</script>


<script>
    function checkParticipante(dat) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    $("#input_nombre_participante").val(data_json_parsed['nombres']);
                    $("#input_apellidos_participante").val(data_json_parsed['apellidos']);
                    $("#input_correo_participante").val(data_json_parsed['correo']);
                    $("#input_prefijo_participante").val(data_json_parsed['prefijo']).change();
                }
            }
        });
    }
</script>

<script>
    function change_input_direnvcert() {
        if(document.getElementById('direnvcert-check').checked){
            document.getElementById('tr-env-cert-r1').style.setProperty('display', 'table-row');
            document.getElementById('tr-env-cert-r2').style.setProperty('display', 'table-row');
        }else{
            document.getElementById('tr-env-cert-r1').style.setProperty('display', 'none');
            document.getElementById('tr-env-cert-r2').style.setProperty('display', 'none');
        }
    }
</script>

