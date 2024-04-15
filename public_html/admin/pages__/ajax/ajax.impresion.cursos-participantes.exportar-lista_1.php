<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador() || isset_get('k3y74ue8')) {

    $id_curso = get('id_curso');
    $sw_doc_cierre = false;

    /* datos requeridos */
    $sw_data_report_prefijo = false;
    if (get('data_report_prefijo') == '1') {
        $sw_data_report_prefijo = true;
    }
    $sw_data_report_departamento = false;
    if (get('data_report_departamento') == '1') {
        $sw_data_report_departamento = true;
    }
    $sw_data_report_nombres = false;
    if (get('data_report_nombres') == '1') {
        $sw_data_report_nombres = true;
    }
    $sw_data_report_apellidos = false;
    if (get('data_report_apellidos') == '1') {
        $sw_data_report_apellidos = true;
    }
    $sw_data_report_celular = false;
    if (get('data_report_celular') == '1') {
        $sw_data_report_celular = true;
    }
    $sw_data_report_correo = false;
    if (get('data_report_correo') == '1') {
        $sw_data_report_correo = true;
    }
    $sw_data_report_datosfacturacion = false;
    if (get('data_report_datosfacturacion') == '1') {
        $sw_data_report_datosfacturacion = true;
    }
    $sw_data_report_montopago = false;
    if (get('data_report_montopago') == '1') {
        $sw_data_report_montopago = true;
    }
    $sw_data_report_regpago = false;
    if (get('data_report_regpago') == '1') {
        $sw_data_report_regpago = true;
    }
    $sw_data_report_datoscontacto = false;
    if (get('data_report_datoscontacto') == '1') {
        $sw_data_report_datoscontacto = true;
    }
    $sw_data_report_modoregistro = false;
    if (get('data_report_modoregistro') == '1') {
        $sw_data_report_modoregistro = true;
    }
    $sw_data_report_fecharegistro = false;
    if (get('data_report_fecharegistro') == '1') {
        $sw_data_report_fecharegistro = true;
    }
    $sw_data_report_firma = false;
    if (get('data_report_firma') == '1') {
        $sw_data_report_firma = true;
    }
    $sw_data_report_eliminados = false;
    if (get('data_report_eliminados') == '1') {
        $sw_data_report_eliminados = true;
    }
    $sw_data_numeracion_certificado = false;
    if (get('data_numeracion_certificado') == '1') {
        $sw_data_numeracion_certificado = true;
    }
    $sw_data_ci = false;
    if (get('data_ci') == '1') {
        $sw_data_ci = true;
    }
    $data_solo_facturados = false;
    if (get('data_solo_facturados') == '1') {
        $data_solo_facturados = true;
    }

    /* id turno */
    $data_id_turno = (int) get('data_id_turno');
    $qr_turno = "";
    if ($data_id_turno > 0) {
        $qr_turno = " AND id_turno='$data_id_turno' ";
    } else {
        
    }
    
    /* solo facturados */
    $qr_solofacturados = "";
    if($data_solo_facturados){
        $qr_solofacturados = " AND id_proceso_registro IN (select id from cursos_proceso_registro where id_curso='$id_curso' and id_emision_factura>0 ) ";
    }

    if (isset_get('excel')) {
        $filename = "listado-curso-$id_curso.xls";
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
    } elseif (isset_get('word')) {
        $filename = "listado-curso-$id_curso.doc";
        header("Content-Type: application/vnd.ms-word;");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
    } elseif (isset_get('html')) {
        //muestra html
    }


    $resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_turno $qr_solofacturados ORDER BY id ASC ");

    /* datos del curso */
    $rqc1 = query("SELECT *,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $curso = fetch($rqc1);
    $nombre_del_curso = $curso['titulo'];
    $fecha_del_curso = $curso['fecha'];
    $codigo_de_certificado_del_curso = $curso['codigo_certificado'];
    $id_certificado_curso = $curso['id_certificado'];
    $id_certificado_2_curso = $curso['id_certificado_2'];
    $url_imagen_del_curso = $dominio_www."paginas/" . $curso['imagen'] . ".size=4.img";

    $numero_curso = $curso['numero'];


    $costo = $curso['costo'];
    $sw_descuento_costo2 = false;
    if ($curso['sw_fecha2'] == '1') {
        $sw_descuento_costo2 = true;
        $costo2 = $curso['costo2'];
    }
    $sw_descuento_costo3 = false;
    if ($curso['sw_fecha3'] == '1') {
        $sw_descuento_costo3 = true;
        $costo3 = $curso['costo3'];
    }
    $sw_descuento_costo_e2 = false;
    if ($curso['sw_fecha_e2'] == '1') {
        $sw_descuento_costo_e2 = true;
        $costo_e2 = $curso['costo_e2'];
    }

    /* fecha de inicio */
    $arf1 = explode('-', $curso['fecha']);
    $arf2 = date("N", strtotime($curso['fecha']));
    $array_dias = array('none', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $fecha_de_inicio = $arf1[2] . " de " . $array_meses[(int) $arf1[1]] . " de " . $arf1[0];
    $dia_de_inicio = $array_dias[$arf2];

    $duracion_curso = $curso['horarios'];
    if ($duracion_curso == '') {
        $duracion_curso = '4 Hrs.';
    }

    /* datos lugar */
    $rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
    $rqdl2 = fetch($rqdl1);
    $lugar_nombre = $rqdl2['nombre'];
    $lugar_salon = $rqdl2['salon'];
    $lugar_direccion = $rqdl2['direccion'];
    $lugar_google_maps = $rqdl2['google_maps'];

    /* ciudad departemento */
    $curso_id_ciudad = $curso['id_ciudad'];
    $rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
    $rqdcd2 = fetch($rqdcd1);
    $curso_nombre_departamento = $rqdcd2['departamento'];
    $curso_nombre_ciudad = $rqdcd2['ciudad'];
    $curso_text_ciudad = $curso_nombre_ciudad;
    if ($curso_nombre_departamento !== $curso_nombre_ciudad) {
        $curso_text_ciudad = $curso_nombre_ciudad . ' - ' . $curso_nombre_departamento;
    }

    /* docente */
    $rqdd1 = query("SELECT nombres,prefijo FROM cursos_docentes WHERE id='" . $curso['id_docente'] . "' LIMIT 1 ");
    $rqdd2 = fetch($rqdd1);
    $docente = $rqdd2['prefijo'] . ' ' . $rqdd2['nombres'];

    /* numeracion de cierre */
    if (isset_get('k3y74ue8_numeracion')) {
        $sw_doc_cierre = true;
        $numeracion_cierre = get('k3y74ue8_numeracion');
        $cod_documento = get('k3y74ue8_cod_documento');
        $apartado_cierre = get('k3y74ue8_apartado');
    } elseif (isset_get('rimp_cod_documento')) {
        $cod_documento = get('rimp_cod_documento');
        /* verif de existencia de documento */
        $rqddc1 = query("SELECT apartado,(select numeracion from cursos_cierres where id=cursos_cierres_documentos.id_cierre order by id desc limit 1)numeracion FROM cursos_cierres_documentos WHERE codigo='$cod_documento' ORDER BY id DESC limit 1 ");
        if (num_rows($rqddc1) == 0) {
            echo "Denegado.";
            exit;
        }
        $rqddc2 = fetch($rqddc1);
        $sw_doc_cierre = true;
        $numeracion_cierre = $rqddc2['numeracion'];
        $apartado_cierre = $rqddc2['apartado'];
    }

    /* helpers */
    $array_expedido_base = array('LP', 'OR', 'PS', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH');
    $array_expedido_short = array('LP', 'OR', 'PT', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
    ?>
    <style>
        td,th{
            border:1px solid #AAA;
            padding:10px 15px;
            font-family: arial;
            font-size: 9pt;
        }
    </style>
    <table style="width:100%;">
        <tr>
            <?php
            if ($sw_doc_cierre) {
                ?>
                <td colspan="5" style="text-align:center;">
                    &nbsp;
                    <br/>
                    <?php echo strtoupper($nombre_del_curso); ?>
                    <br/>
                    &nbsp;
                </td>
                <td style="text-align:center;">
                    <b style='font-size:25pt;'><?php echo $numero_curso; ?></b>
                    <br/>
                    N&Uacute;MERO DE CURSO
                </td>
                <?php
            } else {
                ?>
                <td colspan="5" style="text-align:center;">
                    &nbsp;
                    <br/>
                    <?php echo strtoupper($nombre_del_curso); ?>
                    <br/>
                    &nbsp;
                </td>
                <td style="text-align:center;">
                    <b style='font-size:25pt;'><?php echo $numero_curso; ?></b>
                    <br/>
                    N&Uacute;MERO DE CURSO
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td>
                Fecha
            </td>
            <td colspan="2">
                <?php echo $dia_de_inicio . ', ' . $fecha_de_inicio; ?>
            </td>
            <td>
                Duraci&oacute;n
            </td>
            <td colspan="2">
                <?php echo $duracion_curso; ?>
            </td>
        </tr>
        <tr>
            <td>
                Modalidad
            </td>
            <td colspan="2">
                Presencial
            </td>
            <td>
                Ciudad
            </td>
            <td colspan="2">
                <?php echo $curso_text_ciudad; ?>
            </td>
        </tr>
        <tr>
            <td>
                Lugar
            </td>
            <td colspan="2">
                <?php echo $lugar_nombre; ?>
            </td>
            <td>
                Sal&oacute;n
            </td>
            <td colspan="2">
                <?php echo $lugar_salon; ?>
            </td>
        </tr>
        <tr>
            <td>
                Inversi&oacute;n
            </td>
            <td colspan="2">
                <?php echo $costo; ?> Bs.
                <?php
                /* precio estudiantes */
                if ($curso['sw_fecha_e'] == '1') {
                    ?>
                    &nbsp; Estudiantes :: <?php echo $curso['costo_e']; ?> Bs.
                    <?php
                }
                ?>
            </td>
            <td>
                Descuento
            </td>
            <td colspan="2">
                <?php
                if ($sw_descuento_costo2) {
                    ?>
                    <?php echo $costo2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta <?php echo mydatefechacurso2($curso['fecha2']); ?>.</span>
                    <?php
                    if ($sw_descuento_costo3) {
                        ?>
                        <br/>
                        <?php echo $costo3; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta <?php echo mydatefechacurso2($curso['fecha3']); ?>.</span>
                        <?php
                    }
                    if ($sw_descuento_costo_e2) {
                        ?>
                        <br/>
                        Estudiantes: <?php echo $costo_e2; ?> Bs. <span style="font-size:8pt;color:#535353;">hasta <?php echo mydatefechacurso2($curso['fecha_e2']); ?>.</span>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Docente
            </td>
            <td colspan="2">
                <?php echo $docente; ?>
            </td>
            <?php
            if ($sw_doc_cierre) {
                ?>
                <td>
                    Cierre / ID
                </td>
                <td colspan="2">
                    <?php echo $numeracion_cierre; ?>-<?php echo $apartado_cierre; ?> <span style="font-size:8pt;color:#535353;"><?php echo $cod_documento; ?></span> / <b><?php echo $id_curso; ?></b>
                </td>
                <?php
            } else {
                ?>
                <td>
                    ID de curso
                </td>
                <td colspan="2">
                    <b><?php echo $id_curso; ?></b>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
    </table>
    <?php
    if (num_rows($resultado1) == 0) {
        echo "<table><tr><td colspan='4'>No se registraron participantes para este curso</td></tr></table>";
    }
    ?>
    <meta http-equiv="content-type" content="text/html; utf-8"/>
    <table class="table users-table table-condensed table-hover" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <?php
                if ($sw_data_report_prefijo) {
                    ?>
                    <th>Prof.</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_nombres) {
                    ?>
                    <th>Nombre</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_apellidos) {
                    ?>
                    <th>Apellidos</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_ci) {
                    ?>
                    <th>C.I.</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_celular) {
                    ?>
                    <th>Celular</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_correo) {
                    ?>
                    <th>Correo</th>
                    <?php
                }
                ?>  
                <?php
                if ($sw_data_numeracion_certificado) {
                    ?>
                    <th>Numeraci&oacute;n</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_datosfacturacion) {
                    ?>
                    <th>Facturaci&oacute;n</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_montopago) {
                    ?>
                    <th>Monto de pago</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_regpago) {
                    ?>
                    <th>Registro de pago</th>
                    <?php
                }
                ?>  
                <?php
                if ($sw_data_report_modoregistro) {
                    ?>
                    <th>Modo Registro</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_datoscontacto) {
                    ?>
                    <th>Contacto</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_fecharegistro) {
                    ?>
                    <th>Fecha Registro</th>
                    <?php
                }
                ?>
                <?php
                if ($sw_data_report_firma) {
                    ?>
                    <th style="min-width:90px;">Firma</th>
                    <?php
                }
                ?>
            </tr>
        </thead>

        <tbody>
            <?php
            $cnt = 1;
            while ($participante = fetch($resultado1)) {

                /* datos de registro */
                $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_administrador,sw_pago_enviado,id_cobro_khipu,paydata_fecha,paydata_id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                $data_registro = fetch($rqrp1);
                $codigo_de_registro = $data_registro['codigo'];
                $fecha_de_registro = $data_registro['fecha_registro'];
                $celular_de_registro = $data_registro['celular_contacto'];
                $correo_de_registro = $data_registro['correo_contacto'];
                $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                $id_emision_factura = $data_registro['id_emision_factura'];

                $id_modo_pago = $data_registro['id_modo_pago'];
                $monto_de_pago = $data_registro['monto_deposito'];
                $imagen_de_deposito = $data_registro['imagen_deposito'];

                $razon_social_de_registro = $data_registro['razon_social'];
                $nit_de_registro = $data_registro['nit'];

                $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                $id_cobro_khipu = $data_registro['id_cobro_khipu'];
                
                $paydata_fecha = $data_registro['paydata_fecha'];
                $paydata_id_administrador = $data_registro['paydata_id_administrador'];

                $numeracion_certificado = '';
                if ((int) $participante['id_emision_certificado'] == 0) {
                    $numeracion_certificado .= ' - ';
                } else {
                    $numeracion_certificado = '';
                    $rqidc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado'] . "' ORDER BY id DESC limit 1 ");
                    $rqidc2 = fetch($rqidc1);
                    //echo $rqidc2['certificado_id'];
                    /* segundo certificado */
                    if ($participante['id_emision_certificado_2'] !== '0') {
                        $rqidcc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_2'] . "' ORDER BY id DESC limit 1 ");
                        $rqidcc2 = fetch($rqidcc1);
                        //echo "<br/>";
                        //echo $rqidcc2['certificado_id'];
                    }
                }
                
                if ($sw_data_report_regpago) {
                    
                }
                ?>
                <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                    <td><?php echo $cnt++; ?></td>
                    <?php
                    if ($sw_data_report_prefijo) {
                        ?>
                        <td>
                            <?php
                            echo selutf(trim($participante['prefijo']));
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_nombres) {
                        ?>
                        <td>
                            <?php
                            echo selutf(trim($participante['nombres']));
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_apellidos) {
                        ?>
                        <td>
                            <?php
                            echo selutf(trim($participante['apellidos']));
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_ci) {
                        ?>
                        <td>
                            <?php
                            echo trim($participante['ci'] . " " . strtoupper(str_replace($array_expedido_base, $array_expedido_short, $participante['ci_expedido'])));
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_celular) {
                        ?>
                        <td>
                            <?php
                            echo trim($participante['celular']);
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_correo) {
                        ?>
                        <td>
                            <?php
                            echo trim($participante['correo']);
                            ?>
                        </td>
                        <?php
                    }
                    ?>  
                    <?php
                    if ($sw_data_numeracion_certificado) {
                        ?>
                        <td>
                            <?php
                            echo $participante['numeracion'];
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_datosfacturacion) {
                        ?>
                        <td>
                            <?php
                            echo $razon_social_de_registro;
                            echo "<br/>";
                            echo $nit_de_registro;
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_montopago) {
                        ?>
                        <td>
                            <?php
                            echo $monto_de_pago;
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_regpago) {
                        ?>
                        <td>
                            <?php
                            if($paydata_id_administrador!=='0'){
                                $rqdarp1 = query("SELECT nombre FROM administradores WHERE id='$paydata_id_administrador' LIMIT 1 ");
                                $rqdarp2 = fetch($rqdarp1);
                                echo date("d / M H:i", strtotime($paydata_fecha));
                                echo '<br/>';
                                echo $rqdarp2['nombre'];
                            }else{
                                echo 'Sin dato registrado';
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?> 
                    <?php
                    if ($sw_data_report_modoregistro) {
                        ?>
                        <td>
                            <?php
                            if ($id_modo_pago == '0') {
                                echo "SIST";
                                echo "<br/>";
                                echo "<span style='font-size:8pt;'>Sin pago</span>";
                            } elseif ($id_modo_pago == '3') {
                                echo "SIST";
                                echo "<br/>";
                                echo "<span style='font-size:8pt;'>DEPOSITO</span>";
                                if ($sw_pago_enviado == '1') {
                                    echo "<br/><span style='font-size:8pt;'>Enviado</span>";
                                } else {
                                    echo "<br/><span style='font-size:8pt;'>No enviado</span>";
                                }
                            } else {
                                $rqrck1 = query("SELECT estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
                                $rqrck2 = fetch($rqrck1);
                                echo "SIST";
                                echo "<br/>";
                                echo "<span style='font-size:8pt;'>KHIPU</span>";
                                if ($rqrck2['estado'] == '1') {
                                    echo "<br/><span style='font-size:8pt;'>Pagado</span>";
                                } else {
                                    echo "<br/><span style='font-size:8pt;'>No pagado</span>";
                                }
                            }
                            echo "ADM";
                            echo "<br/>";
                            if ($data_registro['id_administrador'] == '0') {
                                echo "<span style='font-size:8pt;'>Sistema</span>";
                            } else {
                                $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                $rqadr2 = fetch($rqadr1);
                                echo "<span style='font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_datoscontacto) {
                        ?>
                        <td>
                            <?php
                            echo $participante['celular'];
                            echo "<br/>";
                            echo "<span style='font-size:8pt;'>" . $participante['correo'] . "</span>";
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($sw_data_report_fecharegistro) {
                        ?>
                        <td>
                            <?php
                            echo date("d / M H:i", strtotime($fecha_de_registro));
                            ?>
                        </td>
                        <?php
                    }
                    ?>

                    <?php
                    if ($sw_data_report_firma) {
                        ?>
                        <td></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>


        </tbody>
    </table>

    <?php
    if ($sw_data_report_eliminados) {
        ?>

        <table>
            <tr>
                <td colspan="8">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="8">&nbsp;</td>
            </tr>
        </table>

        <table class="table users-table table-striped table-hover">
            <thead>
                <tr>
                    <th colspan="7">PARTICIPANTES ELIMINADOS</th>
                </tr>
                <tr>
                    <th colspan="2">Participante</th>
                    <th>Contacto</th>
                    <th>Datos facturaci&oacute;n</th>
                    <th>Factura</th>
                    <th>MR</th>
                    <th>Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /* participantes eliminados */
                $resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='0' ORDER BY id DESC ");
                if (num_rows($resultado1) == 0) {
                    echo "<tr><td colspan='4'>No existen participantes eliminados.</td></tr>";
                }
                while ($participante = fetch($resultado1)) {

                    /* datos de registro */
                    $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,sw_pago_enviado,id_cobro_khipu,sw_pago_enviado,id_cobro_khipu,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                    $data_registro = fetch($rqrp1);
                    $codigo_de_registro = $data_registro['codigo'];
                    $fecha_de_registro = $data_registro['fecha_registro'];
                    $celular_de_registro = $data_registro['celular_contacto'];
                    $correo_de_registro = $data_registro['correo_contacto'];
                    $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                    $id_emision_factura = $data_registro['id_emision_factura'];

                    $id_modo_pago = $data_registro['id_modo_pago'];
                    $monto_de_pago = $data_registro['monto_deposito'];
                    $imagen_de_deposito = $data_registro['imagen_deposito'];

                    $razon_social_de_registro = $data_registro['razon_social'];
                    $nit_de_registro = $data_registro['nit'];

                    $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                    $id_cobro_khipu = $data_registro['id_cobro_khipu'];


                    if ($participante['id_emision_certificado'] == '0') {
                        // "Sin certificado";
                    } else {
                        $rqidc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado'] . "' ORDER BY id DESC limit 1 ");
                        $rqidc2 = fetch($rqidc1);
                        //echo $rqidc2['certificado_id'];
                        /* segundo certificado */
                        if ($participante['id_emision_certificado_2'] !== '0') {
                            $rqidcc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_2'] . "' ORDER BY id DESC limit 1 ");
                            $rqidcc2 = fetch($rqidcc1);
                            //echo "<br/>";
                            //echo $rqidcc2['certificado_id'];
                        }
                    }
                    ?>
                    <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                        <td colspan="2">
                            <?php
                            echo selutf(trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $participante['celular'] . ' ' . $participante['correo'];
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $razon_social_de_registro . ' ' . $nit_de_registro;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($id_emision_factura != '0') {
                                echo '<b>Emitida</b></br>';
                            } else {
                                if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                    echo '<b>No solicitada</b></br>';
                                } else {
                                    echo '<b>No emitida</b></br>';
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($id_modo_pago == "NO-DEFINIDO") {
                                echo "SIST";
                                echo "<br/>";
                                if ((int) $monto_de_pago > 0) {
                                    echo "<span style='font-size:8pt;'>Pago en oficina</span>";
                                } else {
                                    echo "<span style='font-size:8pt;'>Pago no definido</span>";
                                }
                            } elseif ($id_modo_pago == "deposito") {
                                echo "SIST";
                                echo "<br/>";
                                echo "<span style='font-size:7pt;'>DEPOSITO</span>";
                                if ($sw_pago_enviado == '1') {
                                    echo "<br/><span style='font-size:8pt;'>Enviado</span>";
                                } else {
                                    echo "<br/><span style='font-size:8pt;'>No enviado</span>";
                                }
                            } else {
                                $rqrck1 = query("SELECT estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
                                $rqrck2 = fetch($rqrck1);
                                echo "SIST";
                                echo "<br/>";
                                echo "<span style='font-size:8pt;'>KHIPU</span>";
                                if ($rqrck2['estado'] == '1') {
                                    echo "<br/><span style='font-size:8pt;'>Pagado</span>";
                                } else {
                                    echo "<br/><span style='font-size:8pt;'>No pagado</span>";
                                }
                            }
                            echo "ADM";
                            echo "<br/>";
                            if ($data_registro['id_administrador'] == '0') {
                                echo "<span style='font-size:8pt;'>Sistema</span>";
                            } else {
                                $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                $rqadr2 = fetch($rqadr1);
                                echo "<span style='font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo date("d / M H:i", strtotime($fecha_de_registro));
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <?php
    }
    ?>


    <?php
    if (isset_get('impresion')) {
        echo "<script>window.print();</script>";
    }
    ?>




    <?php
} else {
    echo "Denegado!";
}

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}

function selutf($dat) {
    if (isset($_GET['excel'])) {
        return ($dat);
    } else {
        return $dat;
    }
}

function fecha_corta($data) {
    return date("d / m", strtotime($data));
}

function mydatefechacurso($dat) {
    $day = date("w", strtotime($dat));
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    return $array_dias[(int) $day] . " " . $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}

function mydatefechacurso2($dat) {
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    return $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

/*
function fecha_curso_D_d_m($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}
*/