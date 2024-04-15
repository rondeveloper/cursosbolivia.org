<div class="row">
    <div class="col-md-12 h1-container-home">
        <h1>CURSOS VIRTUALES EN BOLIVIA</h1>
    </div>
    <br>
    <?php
    $counter_aux = 0;

    /* cursos comunes */
    $rcv1 = query("SELECT (c.id)dr_id_curso,c.titulo,c.titulo_identificador,c.imagen,c.imagen_gif,(cd.nombre)ciudad,c.fecha,c.horarios,c.sw_fecha,c.id_modalidad,c.costo,c.costo2,c.costo3,c.fecha2,c.sw_fecha2,c.fecha3,c.sw_fecha3 FROM cursos c INNER JOIN ciudades cd ON c.id_ciudad=cd.id WHERE c.estado IN (1) AND c.sw_siguiente_semana='0' AND c.sw_flag_cursosbo='1' AND c.sw_view_home='1' AND c.id_modalidad<>'1' ORDER BY c.fecha ASC ");
    while ($rc2 = fetch($rcv1)) {
        $titulo_de_curso = $rc2['titulo'];
        $ciudad_curso = $rc2['ciudad'];
        $fecha_curso = fecha_curso($rc2['fecha']);
        if ($rc2['id_modalidad'] == '2') {
            $fecha_curso = 'DISPONIBLE AHORA';
        }
        $horarios = $rc2['horarios'];
        if ($rc2['imagen_gif'] == '') {
            $url_imagen_curso = $dominio_www . "contenido/imagenes/paginas/" . $rc2['imagen'];
        } else {
            $url_imagen_curso = $dominio_www . "contenido/imagenes/paginas/" . $rc2['imagen_gif'];
        }
        /* url curso */
        $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
        $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='" . $rc2['dr_id_curso'] . "' AND r.estado=1 ");
        if (num_rows($rqenc1) > 0) {
            $rqenc2 = fetch($rqenc1);
            $url_pagina_curso = $dominio . $rqenc2['enlace'] . "/";
        }
        $url_registro_curso = "registro-curso/" . $rc2['titulo_identificador'] . ".html";
        $modalidad_curso = "PRESENCIAL";
        $icon_modalidad_curso = "icon-bookmark";
        if ($rc2['id_modalidad'] == '2') {
            $modalidad_curso = "VIRTUAL";
            $icon_modalidad_curso = "fa fa-street-view";
            $ciudad_curso = 'TODA BOLIVIA';
        } elseif ($rc2['id_modalidad'] == '3' || $rc2['id_modalidad'] == '4') {
            $modalidad_curso = "VIRTUAL";
            $icon_modalidad_curso = "fa fa-street-view";
        }

        /* costo */
        $costo_curso = $rc2['costo'];
        $f_h = date("H:i", strtotime($rc2['fecha2']));
        if ($f_h !== '00:00') {
            $f_actual = strtotime(date("Y-m-d H:i"));
            $f_limite = strtotime($rc2['fecha2']);
        } else {
            $f_actual = strtotime(date("Y-m-d"));
            $f_limite = strtotime(substr($rc2['fecha2'], 0, 10));
        }
        $texto_descuento = 'TODA BOLIVIA';
        if ($rc2['sw_fecha2'] == '1' && ($f_actual <= $f_limite)) {
            $texto_descuento = '<b style="color: #006ede;font-weight: bold;">' . $rc2['costo2'] . ' Bs</b> hasta el ' . date("d/m", strtotime($rc2['fecha2']));
        }
        if ($rc2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rc2['fecha3'])) {
            $texto_descuento = '<b style="color: #006ede;font-weight: bold;">' . $rc2['costo3'] . ' Bs</b> hasta el ' . date("d/m", strtotime($rc2['fecha3']));
        }

        $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $rc2['dr_id_curso'] . "' ORDER BY r.id ASC LIMIT 1 ");
        if (num_rows($rqdwn1) == 0) {
            $num_whatsapp = "69714008";
        } else {
            $rqdwn2 = fetch($rqdwn1);
            $num_whatsapp = $rqdwn2['numero'];
        }

        /* numero tigomoney */
        $qrtm1 = query("SELECT n.numero FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros n ON r.id_numtigomoney=n.id WHERE r.id_curso='" . $rc2['dr_id_curso'] . "' AND r.sw_numprin=1 AND n.estado=1 ");
        $numero_tigomoney = "";
        if (num_rows($qrtm1) > 0) {
            $qrtm2 = fetch($qrtm1);
            $numero_tigomoney = $qrtm2['numero'];
        }
    ?>
        <div class="col-xs-12 col-sm-6 col-md-4" align="left">
            <div class="blog-post-short">
                <div class="img-holder">
                    <div class="bg-img-holder bx-img-curso-f1">
                        <a href="<?php echo $url_pagina_curso; ?>">
                            <img src="<?php echo $url_imagen_curso; ?>" alt="<?php echo $titulo_de_curso; ?>" title="<?php echo $titulo_de_curso; ?>" class="img-responsive grafico img-curso-f1" />
                        </a>
                    </div>
                </div>
                <div class="box-label-ciudad"><?php echo strtoupper($ciudad_curso); ?></div>
                <div class="box-label-costo"><?php echo $costo_curso; ?> Bs</div>
            </div>
            <!----->
            <div class="blog-post-short list-group-item ">
                <div class="row">
                    <div class="col-md-12  titulo-curso-f1">
                        <h2 class="h2-to-normal"><a href="<?php echo $url_pagina_curso; ?>" class="Enlace_Curso_Main_Titulo"><?php echo $titulo_de_curso; ?></a></h2>
                    </div>
                </div>
                <div class="row hi ">
                    <div class="col-xs-6 col-sx-6 hi "><i class="icon-money"></i> Inversi&oacute;n: <?php echo $costo_curso; ?> Bs</div>
                    <div class="col-md-6 col-sx-6 hi " align="right"><i class="icon-screenshot"></i> <?php echo $texto_descuento; ?></div>

                    <div class="col-md-6 col-sx-6 hi "><i class="icon-calendar"></i> <?php echo $fecha_curso; ?></div>
                    <div class="col-md-6 col-sx-6 hi " align="right"><b style="color:red;"><i class="<?php echo $icon_modalidad_curso; ?>"></i> <?php echo $modalidad_curso; ?></b></div>

                    <div class="col-md-8 col-sx-8 hi "><i class="icon-time"></i> <?php echo $horarios; ?></div>
                    <div class="col-md-4 col-sx-4 hi  text-right"><a style="color: #00c728;font-weight: bold;font-size: 11pt;text-decoration: none;" target="_blank" href="https://api.whatsapp.com/send?phone=591<?php echo $num_whatsapp; ?>&text=Hola%2C%20tengo%20interes%20en%20<?php echo str_replace(' ', '%20', $titulo_de_curso); ?>"><img src="<?php echo $dominio_www; ?>contenido/alt/icono_whatsap.png" /> <?php echo $num_whatsapp; ?></a></div>
                </div>
                <div class="row hi  ">
                    <div class="blog-meta">
                        <div class="col-md-5 col-xs-5 hi " align="right">
                            <a href="<?php echo $url_pagina_curso; ?>" class="buttonlink btn-block boton-primario" style="border-radius: 3px;"> <i class="fa fa-eye"></i> Detalles</a>
                        </div>
                        <div class="col-md-2 col-xs-2 hi " style="padding: 5px;text-align: center;">
                            <b class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $rc2['dr_id_curso']; ?>');" title="Copiar informacion al clipboard." style="width: 100%;border-radius: 3px;"><i class="icon-copy text-contrast"></i></b>
                        </div>
                        <div class="col-md-5 col-xs-5 hi " align="right">
                            <a href="<?php echo $url_registro_curso; ?>" class="buttonlink rojo btn-block"> <i class="icon-edit text-contrast"></i> Registro</a>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div id="contentInfo-<?php echo $rc2['dr_id_curso']; ?>" style="display:none;">
            <div>*<?php echo $titulo_de_curso; ?>*</div>
            <div><br></div>
            <?php if ($rc2['id_modalidad'] !== '2') { ?>
                <div>*Fecha:* &nbsp; <?php echo $fecha_curso; ?></div>
                <div><br></div>
            <?php } ?>
            <div>*Duraci&oacute;n:* &nbsp; <?php echo $horarios; ?></div>
            <div><br></div>
            <?php if ($rc2['id_modalidad'] == '3' || $rc2['id_modalidad'] == '4') { ?>
                <div>*Modalidad:* &nbsp;Online mediante ZOOM</div>
            <?php } else { ?>
                <div>*Modalidad:* &nbsp;<?php echo $modalidad_curso; ?></div>
            <?php } ?>
            <div><br></div>
            <div>*Detalle completo del curso:* &nbsp; <?php echo $dominio . numIDcurso($rc2['dr_id_curso']) . '/'; ?></div>
            <div><br></div>
            <?php if ($rc2['estado'] !== '0') { ?>
                <div>
                    <?php if ((int) $costo_curso > 0) { ?>
                        *Inversi&oacute;n:* &nbsp; <?php echo $costo_curso; ?> Bs.
                        <div><br></div>
                    <?php } else { ?>
                        *Ingreso:* GRATUITO con c&eacute;dula de identidad
                        <div><br></div>
                    <?php } ?>
                </div>
                <?php if ($rc2['sw_fecha2'] == '1' && ($f_actual <= $f_limite)) { ?>
                    <div>*DESCUENTO POR PAGO ANTICIPADO:*</div>
                    <div><br></div>
                    <div>*Inversi&oacute;n:* <?php echo $rc2['costo2']; ?> Bs. hasta <?php echo date("d/m", strtotime($rc2['fecha2'])); ?> <?php echo date("H:i", strtotime($rc2['fecha2'])) == '00:00' ? '' : date("H:i", strtotime($rc2['fecha2'])); ?></div>
                    <div><br></div>
                    <?php if ($rc2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rc2['fecha3'])) { ?>
                        <div>*Inversi&oacute;n:* <?php echo $rc2['costo3']; ?> Bs. hasta <?php echo date("d/m", strtotime($rc2['fecha3'])); ?> <?php echo date("H:i", strtotime($rc2['fecha3'])) == '00:00' ? '' : date("H:i", strtotime($rc2['fecha3'])); ?></div>
                        <div><br></div>
                    <?php } ?>
                <?php } ?>
                <?php if ((int) $costo_curso > 0) { ?>
                    <?php
                    $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='" . $rc2['dr_id_curso'] . "' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                    $rqdtcb2 = fetch($rqdtcb1); ?>
                    <div>*PAGOS:*</div>
                    <div><br></div>
                    <div><?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?></div>
                    <div><br></div>
                    <div>Pago por TigoMoney <?php echo $numero_tigomoney; ?> (sin recargo)</div>
                    <div><br></div>
                    <div>*Otras formas de pago:* <?php echo $dominio; ?>formas-de-pago.html</div>
                    <div><br></div>
                    <div>Una vez realizado el pago, tiene que registrarse en: <?php echo $dominio; ?>R/<?php echo $rc2['dr_id_curso']; ?>/</div>
                    <div><br></div>
                <?php } ?>
            <?php } ?>
            <div>*Whatsapp:* &nbsp; <?php echo 'https://wa.me/591' . $num_whatsapp; ?></div>
            <div><br></div>
        </div>
    <?php
        $counter_aux++;
        if ($counter_aux % 3 == 0) {
            echo "<div class='courses-three-devider hidden-xs hidden-sm'></div>";
        }
        if ($counter_aux % 2 == 0) {
            echo "<div class='courses-two-devider'></div>";
        }
    }
    ?>
</div>