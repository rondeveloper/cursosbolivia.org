<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');


$sw_selec_cert = true;
$tipo_cert = '';
if (isset_post('tipo_cert')) {
    $sw_selec_cert = false;
    $tipo_cert = post('tipo_cert');
}


/* curso */
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);
?>

<?php
$aux_txt_texto_qr = $curso['texto_qr'];
if ($aux_txt_texto_qr == '') {
    $aux_txt_texto_qr = refactor_titcurso($curso['titulo']);
}


if ($sw_selec_cert) {
?>
    <p>Seleccione el tipo de certificado a agregar:</p>
    <br>
    <div class="text-center">
        <b class="btn btn-info" onclick="selec_cert('participacion');">CERTIFICADO DE PARTICIPACI&Oacute;N</b>
        <br>
        <br>
        <br>
        <b class="btn btn-primary" onclick="selec_cert('aprobacion');">CERTIFICADO DE APROBACI&Oacute;N</b>
    </div>
    <br>
    <hr>
    <!-- agregar_certificado -->
    <script>
        function selec_cert(tipo_cert) {
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-editar.agregar_certificado.php',
                data: {
                    id_curso: '<?php echo $id_curso; ?>',
                    tipo_cert: tipo_cert
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    </script>
<?php
} elseif ($tipo_cert == 'participacion') {
?>
    <form action='' method='post' enctype="multipart/form-data">
        <table class="table table-bordered">
            <tr>
                <td>
                    <span class=""><b>CERTIFICADO (TEXTO QR):</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="texto_qr" rows="2"><?php echo $aux_txt_texto_qr; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>FECHA INICIO (Fecha 1 QR):</b></span>
                </td>
                <td>
                    <input type="date" class="form-control" name="fecha_qr" value="<?php echo $curso['fecha']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>FECHA FINAL (Fecha 2 QR):</b></span>
                </td>
                <td>
                    <input type="date" class="form-control" name="fecha2_qr" value="<?php echo $curso['fecha']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>TITULO:</b></span>
                </td>
                <td>
                    <input type="text" class="form-control" name="titulo_certificado" value="<?php echo ('CERTIFICADO DE PARTICIPACIÓN') ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 1:</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo ("Por cuanto se reconoce que completó satisfactoriamente el curso de capacitación"); ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 2:</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $aux_txt_texto_qr; ?>", con una carga horaria de 10 horas.</textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                </td>
                <td>
                    <?php
                    $dia_curso = date("d", strtotime($curso['fecha']));
                    $mes_curso = date("m", strtotime($curso['fecha']));
                    $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    $rqcc1 = query("SELECT nombre FROM ciudades WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                    $rqcc2 = fetch($rqcc1);
                    if ($curso['id_modalidad'] != '1') {
                        $dia_fin_curso = date("d", strtotime("+3 day", strtotime($curso['fecha'])));
                        /*
                                    if((int)$dia_fin_curso>(int)$dia_curso){
                                        $texto_cont3 = 'Realizado en [DEPARTAMENTO-PARTICIPANTE] del '.$dia_curso.' al '.$dia_fin_curso.' de '.$array_meses[(int) $mes_curso].' de '.date('Y').'.';
                                    }else{
                                        $texto_cont3 = 'Realizado en [DEPARTAMENTO-PARTICIPANTE] del '.$dia_curso.' de '.$array_meses[(int) $mes_curso].' al '.$dia_fin_curso.' de '.$array_meses[(int) $mes_curso+1].' de '.date('Y').'.';
                                    }
                                    */
                        $texto_cont3 = 'Realizado en [DEPARTAMENTO-PARTICIPANTE] [FECHAS-INICIO-FINAL].';
                    } else {
                        /* $texto_cont3 = 'Realizado en '.$rqcc2['nombre'].', Bolivia a los '.$dia_curso.' d&iacute;as del mes de '.$array_meses[(int) $mes_curso].' de '.date('Y').'.'; */
                        $texto_cont3 = 'Realizado en ' . $rqcc2['nombre'] . ', Bolivia [FECHAS-INICIO-FINAL].';
                    }
                    ?>
                    <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $texto_cont3; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2" style="background: #f5f5f5;">
                    <i>CARACTERISTICAS DE VISUALIZACI&Oacute;N DIGITAL</i>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>FONDO DIGITAL :</b></span>
                </td>
                <td>
                    <select type="text" class="form-control" name="id_fondo_digital">
                        <?php
                        $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado=1 AND modo='digital' ORDER BY id ASC ");
                        while ($rqfc2 = fetch($rqfc1)) {
                        ?>
                            <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['descripcion']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2" style="background: #f5f5f5;">
                    <i>CARACTERISTICAS DE IMPRESION FISICO</i>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>RESTRICCION DE IMPRESION:</b></span>
                </td>
                <td class="text-center">
                    <input type="radio" name="sw_solo_nombre" value="0" checked="" />
                    CON FIRMA
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="sw_solo_nombre" value="1" />
                    SIN FIRMA
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>PLANTILLA DE FIRMAS :</b></span>
                </td>
                <td>
                    <select type="text" class="form-control" name="id_fondo_fisico">
                        <option value="0">Sin plantilla de firmas</option>
                        <?php
                        $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado=1 AND modo='fisico' ORDER BY id ASC ");
                        while ($rqfc2 = fetch($rqfc1)) {
                        ?>
                            <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['descripcion']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <br>
                    <input type='hidden' name='id_tipo_cert' value="1" />
                    <input type='submit' name='agregar-certificado-adicional' class="btn btn-success" value="ASIGNAR CERTIFICADO" />
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    <br>
                    &nbsp;
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table table-striped table-bordered" style="font-size: 9pt;">
            <tr>
                <td>
                    [DEPARTAMENTO-PARTICIPANTE]
                </td>
                <td>
                    CONT. 3
                </td>
                <td>
                    Nombre del departamento del participante
                </td>
            </tr>
            <tr>
                <td>
                    [FECHAS-INICIO-FINAL]
                </td>
                <td>
                    CONT. 3
                </td>
                <td>
                    Fechas inicio y final configurados en el certificado
                </td>
            </tr>
        </table>
        <p>
            En la opci&oacute;n impresion SIN FIRMA, solamente se generara un certificado con unicamente
            el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
        </p>
    </form>

    <hr />


<?php
} else {
    /* APROBACION */
?>
    <form action='' method='post' enctype="multipart/form-data">
        <table class="table table-striped table-bordered">
            <tr>
                <td>
                    <span class=""><b>CERTIFICADO (TEXTO QR):</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="texto_qr" rows="2"><?php echo $aux_txt_texto_qr; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>FECHA INICIO (Fecha 1 QR):</b></span>
                </td>
                <td>
                    <input type="date" class="form-control" name="fecha_qr" value="<?php echo $curso['fecha']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>FECHA FINAL (Fecha 2 QR):</b></span>
                </td>
                <td>
                    <input type="date" class="form-control" name="fecha2_qr" value="<?php echo $curso['fecha']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>TITULO:</b></span>
                </td>
                <td>
                    <input type="text" class="form-control" name="titulo_certificado" value="<?php echo ('CERTIFICADO DE APROBACIÓN') ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 1:</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo ("Por cuanto se reconoce que completó satisfactoriamente el curso de capacitación"); ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 2:</b></span>
                </td>
                <td>
                    <textarea class="form-control" name="contenido_dos_certificado" rows="2">"<?php echo $aux_txt_texto_qr; ?>", con una nota de [NOTA-PARTICIPANTE].</textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
                </td>
                <td>
                    <?php
                    $dia_curso = date("d", strtotime($curso['fecha']));
                    $mes_curso = date("m", strtotime($curso['fecha']));
                    $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    $rqcc1 = query("SELECT nombre FROM ciudades WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                    $rqcc2 = fetch($rqcc1);
                    if ($curso['id_modalidad'] != '1') {
                        $dia_fin_curso = date("d", strtotime("+3 day", strtotime($curso['fecha'])));
                        $texto_cont3 = 'Realizado en [DEPARTAMENTO-PARTICIPANTE] [FECHAS-INICIO-FINAL].';
                    } else {
                        /* $texto_cont3 = 'Realizado en '.$rqcc2['nombre'].', Bolivia a los '.$dia_curso.' d&iacute;as del mes de '.$array_meses[(int) $mes_curso].' de '.date('Y').'.'; */
                        $texto_cont3 = 'Realizado en ' . $rqcc2['nombre'] . ', Bolivia [FECHAS-INICIO-FINAL].';
                    }
                    ?>
                    <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $texto_cont3; ?></textarea>
                </td>
            </tr>

            <tr>
                <td>
                    <span class=""><b>FONDO DIGITAL :</b></span>
                </td>
                <td>
                    <select type="text" class="form-control" name="id_fondo_digital">
                        <?php
                        $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado='1' ORDER BY id ASC ");
                        while ($rqfc2 = fetch($rqfc1)) {
                        ?>
                            <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['descripcion']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <span class=""><b>Impresi&oacute;n:</b></span>
                </td>
                <td class="text-center">
                    <br />
                    <input type="radio" name="sw_solo_nombre" value="0" checked="" />
                    CON FIRMA
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="sw_solo_nombre" value="1" />
                    SIN FIRMA
                    <br />
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <br>
                    <input type='hidden' name='id_tipo_cert' value="2" />
                    <input type='submit' name='agregar-certificado-adicional' class="btn btn-success" value="ASIGNAR CERTIFICADO" />
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    <br>
                    &nbsp;
                </td>
            </tr>
        </table>
        <table class="table table-striped table-bordered">
            <tr>
                <td>
                    [NOTA-PARTICIPANTE]
                </td>
                <td>
                    CONT. 2
                </td>
                <td>
                    Nota asignada al participante
                </td>
            </tr>
            <tr>
                <td>
                    [DEPARTAMENTO-PARTICIPANTE]
                </td>
                <td>
                    CONT. 3
                </td>
                <td>
                    Nombre del departamento del participante
                </td>
            </tr>
            <tr>
                <td>
                    [FECHAS-INICIO-FINAL]
                </td>
                <td>
                    CONT. 3
                </td>
                <td>
                    Fechas inicio y final configurados en el certificado
                </td>
            </tr>
        </table>
        <p>
            En la opci&oacute;n impresion SIN FIRMA, solamente se generara un certificado con unicamente
            el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
        </p>
    </form>

    <hr />


<?php
}






function refactor_titcurso($dat)
{
    $rqc1 = query("SELECT nombre FROM ciudades ");
    $busc = array();
    while ($rqc2 = fetch($rqc1)) {
        array_push($busc, "en " . $rqc2['nombre']);
    }
    $remm = '';
    return trim(str_replace($busc, $remm, $dat));
}
