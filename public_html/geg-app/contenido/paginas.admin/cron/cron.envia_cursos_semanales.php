<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* departamento */
$id_departamento = '5';
?>
<div style="font-family:arial;">
    <table style="width: 100%;">
        <?php
        $data_required = "c.titulo,c.titulo_identificador,c.imagen,c.imagen_gif,(cd.nombre)ciudad,c.fecha,c.horarios,c.duracion,c.sw_fecha,c.id_modalidad,c.id_lugar,c.costo,c.fecha2,c.sw_fecha2,c.costo2,c.fecha3,c.sw_fecha3,c.costo3";
        $rc1 = query("SELECT $data_required FROM cursos c INNER JOIN ciudades cd ON c.id_ciudad=cd.id WHERE c.estado IN (1) AND c.id_modalidad<>'2' AND cd.id_departamento='$id_departamento' ORDER BY c.fecha DESC ");
        while ($curso = mysql_fetch_array($rc1)) {
            $titulo_de_curso = $curso['titulo'];
            $ciudad_curso = $curso['ciudad'];
            $fecha_curso = fecha_curso($curso['fecha']);
            if ($curso['id_modalidad'] == '2') {
                $fecha_curso = 'DISPONIBLE AHORA';
            }
            $horarios = $curso['horarios'];
            $duracion_curso = $curso['duracion'] . ' ' . $curso['horarios'];
            if ($duracion_curso == '') {
                $duracion_curso = '4 Hrs.';
            }
            if ($curso['imagen_gif'] == '') {
                $url_imagen_curso = $dominio . "contenido/imagenes/paginas/" . $curso['imagen'];
                if (!file_exists("../../imagenes/paginas/" . $curso['imagen'])) {
                    $url_imagen_curso = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $curso['imagen'];
                }
            } else {
                $url_imagen_curso = $dominio . "contenido/imagenes/paginas/" . $curso['imagen_gif'];
            }
            $url_pagina_curso = $dominio . $curso['titulo_identificador'] . ".html";
            $url_registro_curso = $dominio . "registro-curso/" . $curso['titulo_identificador'] . ".html";
            $modalidad_curso = "Presencial";

            /* datos lugar */
            $rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
            $rqdl2 = mysql_fetch_array($rqdl1);
            $lugar_nombre = $rqdl2['nombre'];
            $lugar_salon = $rqdl2['salon'];
            $lugar_direccion = $rqdl2['direccion'];
            $lugar_google_maps = $rqdl2['google_maps'];

            /* costo */
            $costo = $curso['costo'];
            $sw_descuento_costo2 = false;
            $f_h = date("H:i", strtotime($curso['fecha2']));
            if ($f_h !== '00:00') {
                $f_actual = strtotime(date("Y-m-d H:i"));
                $f_limite = strtotime($curso['fecha2']);
            } else {
                $f_actual = strtotime(date("Y-m-d"));
                $f_limite = strtotime(substr($curso['fecha2'], 0, 10));
            }
            if ($curso['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
                $sw_descuento_costo2 = true;
                $costo2 = $curso['costo2'];
            }
            $sw_descuento_costo3 = false;
            if ($curso['sw_fecha3'] == '1' && ( date("Y-m-d") <= $curso['fecha3'])) {
                $sw_descuento_costo3 = true;
                $costo3 = $curso['costo3'];
            }
            ?>
            <tr>
                <td style="border: 1px solid #9fbce8;padding: 10px;">
                    <div style="">
                        <a href="<?php echo $url_pagina_curso; ?>" style="font-size: 27pt;
                           text-decoration: none;
                           color: #326bb1;
                           font-weight: bold;">
                           <?php echo $titulo_de_curso; ?>
                        </a>
                        <table style="width: 100%;margin: 10px 0px;">
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Fecha:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php echo $fecha_curso; ?>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Inversi&oacute;n:</b> <span style="font-size: 14pt;color: #565656;"><?php echo $costo; ?> BS.</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Horarios:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php echo $duracion_curso; ?>
                                </td>
                                <?php
                                if ($sw_descuento_costo2) {
                                    ?>
                                    <td style="padding: 7px 5px;vertical-align: top;" rowspan="7">
                                        <b style="color: #42569e;font-size: 12pt;">Descuentos:</b> 
                                        <br/>
                                        <div style="background:#FFF;color:#005982;border-radius: 3px;padding: 3px;margin:2px 7px 2px 0px;border-left: 1px solid #adadad;padding-left: 10px;">
                                            <b style='color:#439a43;font-size:8pt;'>POR PAGO ANTICIPADO:</b> <span style="font-size:9pt;color:gray;">(mediante dep&oacute;sito Bancarios y/o Transferencias)</span>
                                            <br/>
                                            Inversi&oacute;n: <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha2']); ?></span>
                                            <?php
                                            if ($sw_descuento_costo3) {
                                                ?>
                                                <br/>
                                                Inversi&oacute;n: <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha3']); ?></span>
                                                <?php
                                            }
                                            if ($sw_descuento_costo_e2) {
                                                ?>
                                                <br/>
                                                Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta el <?php echo mydatefechacurso2($curso['fecha_e2']); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Ciudad:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php echo $ciudad_curso; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Lugar:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php echo $lugar_nombre; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Sal&oacute;n:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php
                                    if ($lugar_salon == '') {
                                        echo "verificar en detalles";
                                    } else {
                                        echo $lugar_salon;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 5px;">
                                    <b style="color: #42569e;font-size: 12pt;">Direcci&oacute;n:</b>
                                </td>
                                <td style="padding: 7px 5px;">
                                    <?php echo $lugar_direccion; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="border: 1px solid #9fbce8;padding: 10px;text-align:center;vertical-align: top;">
                    <a href="<?php echo $url_pagina_curso; ?>">
                        <img src="<?php echo $url_imagen_curso; ?>" alt="<?php echo $titulo_de_curso; ?>" title="<?php echo $titulo_de_curso; ?>" style="width: 190px;"/>
                    </a>
                    <br/>
                    <br/>
                    <br/>
                    <a href="<?php echo $url_pagina_curso; ?>" style="color: #FFF;
                       background: orange;
                       padding: 10px 20px;
                       border-radius: 5px;
                       font-weight: bold;
                       text-decoration: none;">VER DETALLES</a>
                    <br/>
                    <br/>
                    <br/>
                    <a href="<?php echo $url_registro_curso; ?>" style="color: #FFF;
                       background: #1ab91a;
                       padding: 10px 20px;
                       border-radius: 5px;
                       font-weight: bold;
                       text-decoration: none;">QUIERO REGISTRARME</a>
                    <br/>
                    <br/>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td style="border: 1px solid #9fbce8;padding: 10px 30px;line-height: 2;color: #2f64a5;" colspan="2">
                <b style="color:green;">PUEDE REALIZAR EL PAGO DE LOS CURSOS MEDIANTE DEPOSITO O TRANSFERENCIA</b>
                <br>
                <b>DEPOSITO BANCARIO:</b>
                <br>
                A nombre de : NEMABOL, Banco UNION cuenta <b>1-00000-24033833</b>
                <br>
                <b>GIRO TigoMoney:</b> Pago mediante TigoMoney  a la linea <b>69714008</b>
                <br>
                <b>TRANSFERENCIA BANCARIA:</b>
                <br>
                <b>Banco Uni&oacute;n  Cuenta:</b> 124033833  &nbsp; <b>NIT:</b> 2044323014    &nbsp; <b>CI:</b> 2044323 LP Caja de ahorro
            </td>

        </tr>
    </table>
</div>
<?php

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

function fecha_corta($data) {
    $d = date("d", strtotime($data));
    $m = date("m", strtotime($data));
    $me = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $d . " de " . $me[(int) $m];
}

function mydatefechacurso2($dat) {
    $ds = date("w", strtotime($dat));
    $d = date("d", strtotime($dat));
    $m = date("m", strtotime($dat));
    $h = date("H:i", strtotime($dat));
    $txt_hour = '';
    if ($h !== '00:00') {
        $txt_hour = ' hasta ' . $h;
    }
    $array_dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $array_meses = array('NONE', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $array_dias[$ds] . " " . $d . " de " . ucfirst($array_meses[(int) ($m)]) . '' . $txt_hour;
}
