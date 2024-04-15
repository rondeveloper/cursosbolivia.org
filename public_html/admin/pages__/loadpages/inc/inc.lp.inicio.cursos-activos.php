<?php

/* CURSOS ACTIVOS */
$rqcmd1 = query("SELECT * FROM cursos WHERE estado='1' ORDER BY costo DESC ");
?>

<hr>
<h2 style="text-align: center;
    background: #057e1d;
    color: white;
    font-weight: bold;
    padding: 10px;
    font-size: 17pt;">CURSOS ACTIVOS</h2>
<hr>

<div class="table-responsive">
    <table class="table table-bordered tacle-striped">
        <tr>
            <th>
                ID
            </th>
            <th>
                Herramientas
            </th>
            <th>
                Costo
            </th>
            <th>
                Cursos disponibles
            </th>
            <th></th>
        </tr>
        <?php
        while ($curso = fetch($rqcmd1)) {
            $id_curso = $curso['id'];
            $titulo_curso = $curso['titulo'];
            $duracion_curso = $curso['horarios'];
            if ($duracion_curso == '') {
                $duracion_curso = '4 Hrs.';
            }
            $modalidad_curso = "Presencial";
            if ($curso['id_modalidad'] == '2') {
                $modalidad_curso = "Virtual";
            } elseif ($curso['id_modalidad'] == '3') {
                //$modalidad_curso = "Semi-presencial";
                $modalidad_curso = "VIRTUAL";
            } elseif ($curso['id_modalidad'] == '4') {
                //$modalidad_curso = "Semi-presencial";
                $modalidad_curso = "VIRTUAL EN VIVO";
            }

            $costo = $curso['costo'];
            $sw_descuento_costo2 = false;
            $f_h = date("H:i", strtotime($curso['fecha2']));
            if ($f_h !== '00:00') {
                $f_actual = strtotime(date("Y-m-d H:i"));
                $f_limite = strtotime($curso['fecha2']);
            } else {
                $f_actual = strtotime(date("Y-m-d"));
                $f_limite = strtotime(substr($curso['fecha2'], 0, 10));

                $f_limite_aux = strtotime($curso['fecha2']);
            }
            if ($curso['sw_fecha2'] == '1' && ($f_actual <= $f_limite)) {
                $sw_descuento_costo2 = true;
                $costo2 = $curso['costo2'];
            }
            $sw_descuento_costo3 = false;
            if ($curso['sw_fecha3'] == '1' && (date("Y-m-d") <= $curso['fecha3'])) {
                $sw_descuento_costo3 = true;
                $costo3 = $curso['costo3'];
            }
            $sw_descuento_costo_e2 = false;
            if ($curso['sw_fecha_e2'] == '1' && (date("Y-m-d") <= $curso['fecha_e2'])) {
                $sw_descuento_costo_e2 = true;
                $costo_e2 = $curso['costo_e2'];
            }

            $mensaje_wamsm_predeternimado = 'Hola, tengo interes en el curso: ' . trim($curso['titulo_formal']);
            $mensaje_wamsm = str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado));
            $rqdwn1 = query("SELECT w.numero,w.wap_shortlink FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $curso['id'] . "' ORDER BY r.id ASC LIMIT 1 ");
            $numero_wamsm_predeternimado = '69714008';
            $cel_wamsm = '591' . $numero_wamsm_predeternimado;
            $wap_shortlink = 'https://wa.me/59169714008';
            if (num_rows($rqdwn1) > 0) {
                $rqdwn2 = fetch($rqdwn1);
                $cel_wamsm = '591' . $rqdwn2['numero'];
                $wap_shortlink = 'https://wa.me/591' . $rqdwn2['numero'];
            }

            /* fecha de inicio */
            $arf1 = explode('-', $curso['fecha']);
            $arf2 = date("N", strtotime($curso['fecha']));
            $array_dias = array('none', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
            $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $fecha_de_inicio = $arf1[2] . " de " . $array_meses[(int) $arf1[1]] . " de " . $arf1[0];
            $dia_de_inicio = $array_dias[$arf2];

            /* numero tigomoney */
            $qrtm1 = query("SELECT n.numero FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros n ON r.id_numtigomoney=n.id WHERE r.id_curso='$id_curso' AND r.sw_numprin=1 AND n.estado=1 ");
            $numero_tigomoney = "69714008";
            if (num_rows($qrtm1) > 0) {
                $qrtm2 = fetch($qrtm1);
                $numero_tigomoney = $qrtm2['numero'];
            }

            /* url corta */
            $url_corta = $dominio . numIDcurso($id_curso) . '/';
            $url_registro = $dominio . 'R/' . $id_curso . '/';
            $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='" . $id_curso . "' AND r.estado=1 ");
            if (num_rows($rqenc1) > 0) {
                $rqenc2 = fetch($rqenc1);
                $url_corta = $dominio . $rqenc2['enlace'] . "/";
                $url_registro = $dominio . 'R/' . $id_curso . "/";
            }
        ?>
            <tr>
                <td>
                    <?php echo $curso['id']; ?>
                </td>
                <td style="min-width: 155px;">
                    <b class="btn btn-md btn-default" onclick="copyToClipboard(<?php echo $curso['id']; ?>);"><i class="fa fa-copy"></i></b>
                    &nbsp;&nbsp;
                    <b class="btn btn-default btn-sm" onclick="historial_curso('<?php echo $curso['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_curso"><i class="fa fa-list"></i></b>
                    &nbsp;&nbsp;
                    <b class="btn btn-default btn-sm" onclick="generador_post('<?php echo $curso['id']; ?>');"><i class="fa fa-flag"></i></b>
                </td>
                <td class="" style="padding: 0px 5px;min-width: 70px;font-size: 12pt;font-weight:bold;">
                    <?php
                    /* costo */
                    $costo_cur = $curso['costo'];
                    if ($curso['sw_fecha2'] == '1' && (date("Y-m-d") <= $curso['fecha2'])) {
                        $costo_cur = $curso['costo2'];
                    }
                    if ($curso['sw_fecha3'] == '1' && (date("Y-m-d") <= $curso['fecha3'])) {
                        $costo_cur = $curso['costo3'];
                    }
                    echo $costo_cur . ' Bs';
                    ?>
                </td>
                <td style="font-size: 12px;">
                    <a href="<?php echo $dominio . numIDcurso($id_curso) . '/'; ?>" target="_blank">
                        <?php echo $curso['titulo']; ?>
                    </a>
                </td>
                <td>
                    <div id="contentInfo-curso-<?php echo $curso['id']; ?>" style="display:none;">
                        <div>*<?php echo $curso['titulo']; ?>*</div>
                        <div><br></div>
                        <?php if ($curso['id_modalidad'] !== '2') { ?>
                            <div>*Fecha:* &nbsp; <?php echo $dia_de_inicio . ', ' . $fecha_de_inicio; ?></div>
                            <div><br></div>
                        <?php } ?>
                        <div>*Duraci&oacute;n:* &nbsp; <?php echo $duracion_curso; ?></div>
                        <div><br></div>
                        <?php if ($curso['id_modalidad'] == '3' || $curso['id_modalidad'] == '4') { ?>
                            <div>*Modalidad:* &nbsp;Online mediante ZOOM</div>
                        <?php } else { ?>
                            <div>*Modalidad:* &nbsp;<?php echo $modalidad_curso; ?></div>
                        <?php } ?>
                        <div><br></div>
                        <div>*Detalle completo del curso:* &nbsp; <?php echo $dominio . numIDcurso($id_curso) . '/'; ?></div>
                        <div><br></div>
                        <?php if ($curso['estado'] !== '0') { ?>
                            <div>
                                <?php if ((int) $costo > 0) { ?>
                                    *Inversi&oacute;n:* &nbsp; <?php echo $costo; ?> Bs.
                                    <div><br></div>
                                <?php } else { ?>
                                    *Ingreso:* GRATUITO con c&eacute;dula de identidad
                                    <div><br></div>
                                <?php } ?>
                            </div>
                            <?php if ($curso['sw_fecha2'] == '1' && ($f_actual <= $f_limite)) { ?>
                                <div>*DESCUENTO POR PAGO ANTICIPADO:*</div>
                                <div><br></div>
                                <div>*Inversi&oacute;n:* <?php echo $curso['costo2']; ?> Bs. hasta <?php echo date("d/m", strtotime($curso['fecha2'])); ?> <?php echo date("H:i", strtotime($curso['fecha2'])) == '00:00' ? '' : date("H:i", strtotime($curso['fecha2'])); ?></div>
                                <div><br></div>
                                <?php if ($curso['sw_fecha3'] == '1' && (date("Y-m-d") <= $curso['fecha3'])) { ?>
                                    <div>*Inversi&oacute;n:* <?php echo $curso['costo3']; ?> Bs. hasta <?php echo date("d/m", strtotime($curso['fecha3'])); ?> <?php echo date("H:i", strtotime($curso['fecha3'])) == '00:00' ? '' : date("H:i", strtotime($curso['fecha3'])); ?></div>
                                    <div><br></div>
                                <?php } ?>
                                <?php if ($curso['sw_fecha_e'] == '1' && ( date("Y-m-d") <= $curso['fecha_e'])) { ?>
                                    <div>*Estudiantes:* <?php echo $curso['costo_e']; ?> Bs. presentando carnet universitario</div>
                                    <div><br></div>
                                <?php } ?>
                            <?php } ?>
                            <div>*Whatsapp:* &nbsp; <?php echo 'https://wa.me/' . $cel_wamsm; ?></div>
                            <div><br></div>
                            <?php if ((int) $costo > 0) { ?>
                                <?php
                                $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                                $rqdtcb2 = fetch($rqdtcb1);
                                ?>
                                <div>*PAGOS:*</div>
                                <div><br></div>
                                <div><?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?></div>
                                <div><br></div>
                                <div>Pago por TigoMoney <?php echo $numero_tigomoney; ?> (sin recargo)</div>
                                <div><br></div>
                                <div>*Otras formas de pago:* <?php echo $dominio; ?>formas-de-pago.html</div>
                                <div><br></div>
                            <?php } ?>
                        <?php } ?>
                        <?php if ((int) $costo > 0) { ?>
                            <div>Una vez realizado el pago, tiene que registrarse en: <?php echo $url_registro; ?></div>
                            <div><br></div>
                        <?php } ?>
                        <div><br></div>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>


<script>
    function copyToClipboard(id_curso) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById("contentInfo-curso-" + id_curso).innerHTML;
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
        alert('Copiado al portapapeles Ctrl + C');
    }
</script>

<!-- historial_curso -->
<script>
    function historial_curso(id_curso) {
        $("#AJAXCONTENT-historial_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.historial_curso.php',
            data: {
                id_curso: id_curso
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_curso").html(data);
            }
        });
    }
</script>

<!-- MODAL historial_curso -->
<div id="MODAL-historial_curso" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_curso"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- generar_post -->
<script>
    function generador_post(id_curso) {
        $("#TITLE-modgeneral").html('GENERADOR DE POST');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.generador_post.php',
            data: {
                id_curso: id_curso
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>