<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_curso = post('id_curso');


/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = mysql_fetch_array($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

/* datos de curso */
$sw_turnos = false;
$rqtc1 = query("SELECT id FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}


/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('busc')) {
    $busqueda = post('busc');
    $qr_busqueda = " AND ( id='$busqueda' OR nombres LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' ) ";
    $vista = 1;
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset_post('id_turno') && (post('id_turno') > 0)) {
    $id_turno_curso = (int) post('id_turno');
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* modo_pago */
$qr_modo_pago = "";
/*
if (isset_post('modo_pago') && post('modo_pago') !== 'todos' && post('modo_pago') !== '') {
    $modo_pago = post('modo_pago');
    if ($modo_pago == 'sinpago') {
        $modo_pago = '';
    }
    $qr_modo_pago = " AND modo_pago='$modo_pago' ";
}
*/
if (isset_post('modo_pago')) {
    $modo_pago = post('modo_pago');
    switch ($modo_pago) {
        case 'conpago':
            $qr_modo_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='1' AND id_curso='$id_curso' ) ";
            break;
        case 'sinpago':
            $qr_modo_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='0' AND id_curso='$id_curso' ) ";
            $modo_pago = '';
            break;
        default:
            break;
    }
}

/* query previa : PARTICIPANTES APARTADOS */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id IN (select id_participante from cursos_part_apartados) AND id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno $qr_modo_pago ORDER BY id DESC ");
if (mysql_num_rows($resultado1) > 0) {
    /* contador */
    $cnt = mysql_num_rows($resultado1);
    /* aux ids almacenador */
    $aux_idsalmacenador = '0';
    ?>
    <hr/>
    <div class="panel panel-success">
        <div class="panel-heading">PARTICIPANTES APARTADOS DE LISTA DE PROCESOS</div>
        <div class="panel-body">

            <div class="table-responsive">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">#</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Prof.</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Facturaci&oacute;n</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">M/Pago</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Modo/Registro</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Fecha/Registro</th>
                            <?php
                            if ($sw_turnos) {
                                echo '<th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Turno</th>';
                            }
                            ?>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Contacto</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;width:25px;"></th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Certs</th>
                            <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;width:100px;text-align:right;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $sw_existencia_certificado_uno = false;
                        $sw_existencia_certificado_dos = false;
                        $sw_existencia_facturas = false;

                        if (mysql_num_rows($resultado1) == 0) {
                            echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
                        }

                        while ($participante = mysql_fetch_array($resultado1)) {

                            /* datos de registro */
                            $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                            $data_registro = mysql_fetch_array($rqrp1);
                            $id_proceso_de_registro = $data_registro['id'];
                            $codigo_de_registro = $data_registro['codigo'];
                            $fecha_de_registro = $data_registro['fecha_registro'];
                            $celular_de_registro = $data_registro['celular_contacto'];
                            $correo_de_registro = $data_registro['correo_contacto'];
                            $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                            $id_modo_de_registro = $data_registro['id_modo_de_registro'];
                            $id_emision_factura = $data_registro['id_emision_factura'];

                            $metodo_de_pago = $data_registro['metodo_de_pago'];
                            $monto_de_pago = $data_registro['monto_deposito'];
                            $imagen_de_deposito = $data_registro['imagen_deposito'];

                            $razon_social_de_registro = $data_registro['razon_social'];
                            $nit_de_registro = $data_registro['nit'];

                            $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                            $id_cobro_khipu = $data_registro['id_cobro_khipu'];

                            $aux_idsalmacenador .= ',' . $participante['id'];
                            ?>
                            <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                                <td class="visible-lgNOT" style="background: #dff0d8;">
                                    <?php echo $cnt--; ?>
                                    <br/>
                                    <br/>
                                    <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                        <i class="fa fa-list" style="color:#8f8f8f;"></i>
                                    </b>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo trim($participante['prefijo']);
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo trim($participante['nombres']);
                                    ?>
                                    <br/>
                                    <b style="font-size:7pt;color:#2180be;">No. <?php echo $participante['numeracion']; ?></b>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo trim($participante['apellidos']);
                                    ?>
                                    <br/>
                                    <b style="font-size:7pt;color:#1b6596;"><?php echo $participante['ci'] . ' ' . $participante['ci_expedido']; ?></b>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    if ($id_emision_factura != '0') {
                                        $sw_existencia_facturas = true;
                                        echo '<i class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">Emitida</i>';
                                        if ($participante['correo'] !== '') {
                                            $rqefaux1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                            $rqefaux2 = mysql_fetch_array($rqefaux1);
                                            echo '&nbsp;&nbsp;<span id="ffl-' . $rqefaux2['nro_factura'] . '"><i class="btn btn-xs btn-default" onclick="enviar_factura2(\'' . $rqefaux2['nro_factura'] . '\');"><b class="fa fa-envelope"></b></i></span>';
                                            echo '<input type="hidden" id="correo-de-envio-' . $rqefaux2['nro_factura'] . '" value="' . $participante['correo'] . '"/>';
                                        }
                                        echo '</br>';
                                    } else {
                                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                            echo '<i class="btn btn-xs btn-warning" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">No solicitada</i></br>';
                                        } else {
                                            echo '<i class="btn btn-xs btn-danger" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">No emitida</i></br>';
                                        }
                                    }



                                    echo $razon_social_de_registro;
                                    echo "<br/>";
                                    echo $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    $sw_pago_definido = false;
                                    if ($monto_de_pago !== '' && $participante['modo_pago'] !== '') {
                                        $sw_pago_definido = true;
                                        echo $monto_de_pago;
                                        echo "<br/>";
                                        echo "<span style='color:gray;font-size:8pt;'>" . $participante['modo_pago'] . "</span>";
                                    } elseif ($sw_habilitacion_procesos) {
                                        ?>
                                        <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>, '<?php echo ($cnt + 1); ?>');" class="btn btn-xs btn-danger">
                                            DEFINIR PAGO <i class="fa fa-edit"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                                        if ($metodo_de_pago == "NO-DEFINIDO") {
                                            echo "Sistema";
                                            echo "<br/>";
                                            if ((int) $monto_de_pago > 0) {
                                                echo "<span class='btn btn-xs btn-success active small'>Pago en oficina</span>";
                                            } else {
                                                echo "<b style='color:#e74c3c;'>PAGO NO DEFINIDO</b>";
                                            }
                                            //echo "<br/><span class='btn btn-xs btn-danger small'>Pago no enviado</span>";
                                        } elseif ($metodo_de_pago == "deposito") {
                                            echo "Sistema";
                                            echo "<br/>";
                                            echo "<b style='color:green;'>DEPOSITO</b>";
                                            if ($sw_pago_enviado == '1') {
                                                echo "<br/><span class='btn btn-xs btn-success active small'>Enviado</span>";
                                            } else {
                                                echo "<br/><span class='btn btn-xs btn-danger active small'>No enviado</span>";
                                            }
                                        } else {
                                            $rqrck1 = query("SELECT estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
                                            $rqrck2 = mysql_fetch_array($rqrck1);
                                            echo "Sistema";
                                            echo "<br/>";
                                            echo "<b style='color:blue;'>KHIPU</b>";
                                            if ($rqrck2['estado'] == '1') {
                                                echo "<br/><span class='btn btn-xs btn-success active small'>Pagado</span>";
                                            } else {
                                                echo "<br/><span class='btn btn-xs btn-danger active small'>No pagado</span>";
                                            }
                                        }
                                    } elseif ($id_modo_de_registro == '2') {
                                        echo "<b style='color:#444;'>ADMIN</b>";
                                        echo "<br/>";
                                        if ($data_registro['id_administrador'] == '0') {
                                            echo "<span style='color:gray;'>Sin datos del registrador</span>";
                                        } else {
                                            $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                            $rqadr2 = mysql_fetch_array($rqadr1);
                                            echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    echo "<br/>";
                                    echo "<span style='color:gray;font-size:8pt;'>" . $codigo_de_registro . "</span>";
                                    if ($participante['ultima_impresion_certificado'] !== '0000-00-00 00:00:00') {
                                        echo "<br/>";
                                        echo "<span style='color:#AAA;font-size:7pt;'>" . date("d-m-y H:i", strtotime($participante['ultima_impresion_certificado'])) . "</span>";
                                    }
                                    ?>
                                </td>
                                <?php
                                if ($sw_turnos) {
                                    echo '<td class="visible-lgNOT">' . $turno[$participante['id_turno']] . '</td>';
                                }
                                ?>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo $participante['celular'];
                                    echo "<br/>";
                                    echo $participante['correo'];
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <span style="font-size: 10pt;"><?php echo $participante['cnt_impresion_certificados']; ?></span>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    $sw_existencia_certificado_uno = $sw_existencia_certificado_dos = false;
                                    /* primer certificado */
                                    if ($participante['id_emision_certificado'] == '0' && $sw_habilitacion_procesos && $id_certificado_curso !== '0' && $sw_pago_definido) {
                                        $sw_existencia_certificado_uno = false;
                                        ?>
                                        <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);" class="btn btn-xs btn-primary active">C1</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado'] !== '0') {
                                        $sw_existencia_certificado_uno = true;
                                        ?>
                                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado']; ?>');" class="btn btn-xs btn-warning active">C1</a>
                                        <?php
                                    }

                                    /* segundo certificado */
                                    if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0' && $sw_pago_definido) {
                                        $sw_existencia_certificado_dos = false;
                                        ?>
                                        <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);" class="btn btn-xs btn-primary active">C2</a>
                                        </span>
                                        <?php
                                        if (!$sw_existencia_certificado_uno) {
                                            ?>
                                            <span id='box-modal_emision_certificado-button-12-<?php echo $participante['id']; ?>'>
                                                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 12);" class="btn btn-xs btn-primary">C12</a>
                                            </span>
                                            <?php
                                        }
                                    } elseif ($participante['id_emision_certificado_2'] !== '0') {
                                        $sw_existencia_certificado_dos = true;
                                        ?>
                                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado_2']; ?>');" class="btn btn-xs btn-warning active">C2</a>
                                        <?php
                                    }

                                    if ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos) {
                                        ?>
                                        <a onclick="imprimir_dos_certificados('<?php echo $participante['id_emision_certificado'] . ',' . $participante['id_emision_certificado_2']; ?>');" class="btn btn-xs btn-warning">C12</a>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($sw_habilitacion_procesos && false) {
                                        $rqmc1 = query("SELECT id FROM cursos_modelos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
                                        if (mysql_num_rows($rqmc1) > 0) {
                                            ?>
                                            &nbsp;           
                                            <a data-toggle="modal" data-target="#MODAL-certificados-secundarios" onclick="procesar_certificados_secundarios('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-default">CS</a>
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <div style="width: 90px;float: right;">
                                        <?php
                                        if ($sw_habilitacion_procesos) {
                                            ?>
                                            <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>, '<?php echo ($cnt + 1); ?>');" class="btn btn-xs btn-info active"> <i class="fa fa-edit"></i> </a>
                                            &nbsp;
                                            <?php
                                        }
                                        ?>
                                        <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-default">R</a>
                                        <?php
                                        if ($sw_habilitacion_procesos) {
                                            ?>
                                            &nbsp;
                                            <a data-toggle="modal" data-target="#MODAL-habilita-participante" onclick="habilita_participante_p1(<?php echo $participante['id']; ?>, 1);" class="btn btn-xs btn-success active">H</a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}



/* query principal */
/* participantes eliminados */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='0' $qr_busqueda $qr_turno $qr_modo_pago ORDER BY id DESC ");
if (mysql_num_rows($resultado1) > 0) {
    ?>
    <hr/>
    <div class="panel panel-danger">
        <div class="panel-heading">PARTICIPANTES ELIMINADOS</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lgNOT">#</th>
                            <th class="visible-lgNOT">Nombre</th>
                            <th class="visible-lgNOT">Contacto</th>
                            <th class="visible-lgNOT">Fecha/Registro</th>
                            <th class="visible-lgNOT">Facturaci&oacute;n</th>
                            <th class="visible-lgNOT">Certs.</th>
                            <th class="visible-lgNOT">MR</th>
                            <th class="visible-lgNOT">.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 0;
                        while ($participante = mysql_fetch_array($resultado1)) {

                            /* datos de registro */
                            $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                            $data_registro = mysql_fetch_array($rqrp1);
                            $codigo_de_registro = $data_registro['codigo'];
                            $fecha_de_registro = $data_registro['fecha_registro'];
                            $celular_de_registro = $data_registro['celular_contacto'];
                            $correo_de_registro = $data_registro['correo_contacto'];
                            $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                            $id_modo_de_registro = $data_registro['id_modo_de_registro'];
                            $id_emision_factura = $data_registro['id_emision_factura'];

                            $metodo_de_pago = $data_registro['metodo_de_pago'];
                            $monto_de_pago = $data_registro['monto_deposito'];
                            $imagen_de_deposito = $data_registro['imagen_deposito'];

                            $razon_social_de_registro = $data_registro['razon_social'];
                            $nit_de_registro = $data_registro['nit'];
                            ?>
                            <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                                <td class="visible-lgNOT" style="background: #f2dede;">
                                    <?php
                                    echo ++$cnt;
                                    ?>
                                    <br/>
                                    <br/>
                                    <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                        <i class="fa fa-list" style="color:#8f8f8f;"></i>
                                    </b>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo trim($participante['prefijo'] . ' ' . strtoupper($participante['nombres'] . ' ' . $participante['apellidos']));
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo $participante['celular'] . ' ' . $participante['correo'];
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    echo "<br/>";
                                    echo $codigo_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    if ($id_emision_factura != '0') {
                                        echo '<b style="color:green;">Emitida</b></br>';
                                    } else {
                                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                            echo '<i>No solicitada</i></br>';
                                        } else {
                                            echo '<b>No emitida</b></br>';
                                        }
                                    }
                                    echo "<br/>";
                                    echo $razon_social_de_registro . ' ' . $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    $sw_existencia_certificado_uno = $sw_existencia_certificado_dos = false;
                                    /* primer certificado */
                                    if ($participante['id_emision_certificado'] == '0' && $sw_habilitacion_procesos && $id_certificado_curso !== '0') {
                                        $sw_existencia_certificado_uno = false;
                                        ?>
                                        <span >
                                            <a  class="btn btn-xs btn-primary active">C1</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado'] !== '0') {
                                        $sw_existencia_certificado_uno = true;
                                        ?>
                                        <a  class="btn btn-xs btn-warning active">C1</a>
                                        <?php
                                    }

                                    /* segundo certificado */
                                    if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0') {
                                        $sw_existencia_certificado_dos = false;
                                        ?>
                                        <span >
                                            <a  class="btn btn-xs btn-primary active">C2</a>
                                        </span>
                                        <span >
                                            <a  class="btn btn-xs btn-primary">C12</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado_2'] !== '0') {
                                        $sw_existencia_certificado_dos = true;
                                        ?>
                                        <a  class="btn btn-xs btn-warning active">C2</a>
                                        <?php
                                    }

                                    if ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos) {
                                        ?>
                                        <a  class="btn btn-xs btn-warning">C12</a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                                        echo "Sistema";
                                    } elseif ($id_modo_de_registro == '2') {
                                        if ($data_registro['id_administrador'] == '0') {
                                            echo "<span style='color:gray;'>Sin datos del registrador</span>";
                                        } else {
                                            $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                            $rqadr2 = mysql_fetch_array($rqadr1);
                                            echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-primary">R.</a>
                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        ?>
                                        &nbsp;&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-habilita-participante" onclick="habilita_participante_p1(<?php echo $participante['id']; ?>, 0);" class="btn btn-xs btn-warning"> Habilitar </a>
                                        <?php
                                    }
                                    ?>
                                </td>

                                <!-- Modal-3 -->
                        <div id="MODAL-datos-registro-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">DATOS DE REGISTRO</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                                                <br/>
                                                <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                                                <br/>
                                                <b>REGISTRO:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>PARTICIPANTE:</b> &nbsp; <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <h3 class="text-center">
                                                    <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                </h3>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <b>Fecha de registro:</b> &nbsp; <?php echo $fecha_de_registro; ?>
                                                <br/>
                                                <!--                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                                                                <br/>
                                                                                                <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                                                                <br/>-->
                                                <b>Registro:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>Nro. de participantes:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
                                                <br/>
                                                <?php
                                                if ($metodo_de_pago == 'deposito') {
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; DEPOSITO BANCARIO
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <br/>
                                                    <b>Imagen del deposito:</b> &nbsp; <a href="depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                                                    <br/>
                                                    <br/>
                                                    <img src="depositos/<?php echo $imagen_de_deposito; ?>.size=3.img" style="width:100%;border:1px solid #DDD;padding:1px;">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; PAGO EN OFICINA
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <hr/>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-3 -->

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
?>