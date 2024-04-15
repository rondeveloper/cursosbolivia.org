<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
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


/* datos de curso */
$rqc1 = query("SELECT id_certificado,id_certificado_2 FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];

/* sw_turno */
$sw_turnos = false;
if ($id_turno_curso > 0) {
    $sw_turnos = true;
    $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso'  ");
    while ($rqtc2 = mysql_fetch_array($rqtc1)) {
        $turno[$rqtc2['id']] = $rqtc2['titulo'];
    }
}



/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");

/* contador */
$cnt = mysql_num_rows($resultado1);

/* aux ids almacenador */
$aux_idsalmacenador = '0';
?>

<table class="table users-table table-condensed table-hover">
    <thead>
        <tr>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">#</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Prof.</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Facturaci&oacute;n</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">R/Pago</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Registro</th>
            <?php
            if ($sw_turnos) {
                echo '<th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Turno</th>';
            }
            ?>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Contacto</th>
            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;width: 270px;">
                Acci&oacute;n 
                &nbsp;&nbsp;
                <a class='btn btn-xs btn-default' data-toggle="modal" data-target="#MODAL-generar-reporte">Generar Reporte</a>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php
        $sw_existencia_certificado_uno = false;
        $sw_existencia_certificado_dos = false;
        $sw_existencia_facturas = false;

        while ($participante = mysql_fetch_array($resultado1)) {

            /* datos de registro */
            $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
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
                <td class="visible-lg" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                    <?php echo $cnt--; ?>
                </td>
                <td class="visible-lg" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                    <?php
                    echo trim($participante['prefijo']);
                    ?>
                </td>
                <td class="visible-lg" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                    <?php
                    echo trim($participante['nombres']);
                    ?>
                </td>
                <td class="visible-lg" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                    <?php
                    echo trim($participante['apellidos']);
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    if ($id_emision_factura != '0') {
                        $sw_existencia_facturas = true;
                        echo '<i class="btn btn-xs btn-success">Emitida</i>';
                        if ($participante['correo'] !== '') {
                            $rqefaux1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                            $rqefaux2 = mysql_fetch_array($rqefaux1);
                            echo '&nbsp;&nbsp;<span id="ffl-' . $rqefaux2['nro_factura'] . '"><i class="btn btn-xs btn-default" onclick="enviar_factura2(\'' . $rqefaux2['nro_factura'] . '\');"><b class="fa fa-envelope"></b></i></span>';
                        }
                        echo '</br>';
                    } else {
                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                            echo '<i class="btn btn-xs btn-warning">No solicitada</i></br>';
                        } else {
                            echo '<i class="btn btn-xs btn-danger">No emitida</i></br>';
                        }
                    }



                    echo $razon_social_de_registro;
                    echo "<br/>";
                    echo $nit_de_registro;
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                        if ($metodo_de_pago == "deposito") {
                            echo "Sistema";
                            echo "<br/>";
                            echo "<b style='color:green;'>DEPOSITO</b>";
                            if($sw_pago_enviado=='1'){
                                echo "<br/><span class='btn btn-xs btn-success active small'>Enviado</span>";
                            }else{
                                echo "<br/><span class='btn btn-xs btn-danger active small'>No enviado</span>";
                            }
                        } else {
                            $rqrck1 = query("SELECT estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
                            $rqrck2 = mysql_fetch_array($rqrck1);
                            echo "Sistema";
                            echo "<br/>";
                            echo "<b style='color:blue;'>KHIPU</b>";
                            if($rqrck2['estado']=='1'){
                                echo "<br/><span class='btn btn-xs btn-success active small'>Pagado</span>";
                            }else{
                                echo "<br/><span class='btn btn-xs btn-danger active small'>No pagado</span>";
                            }
                        }
                    } elseif ($id_modo_de_registro == '2') {
                        echo "<b style='color:#444;'>ADMIN</b>";
                    }
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo date("d / M H:i", strtotime($fecha_de_registro));
                    ?>
                </td>
                <?php
                if ($sw_turnos) {
                    echo '<td class="visible-lg">' . $turno[$participante['id_turno']] . '</td>';
                }
                ?>
                <td class="visible-lg">
                    <?php
                    echo $participante['celular'];
                    echo "<br/>";
                    echo $participante['correo'];
                    ?>
                </td>
                <td class="visible-lg">
                    <input type="checkbox" id="<?php echo $participante['id']; ?>" name="" checked=""/>
                    <?php
                    if ($sw_habilitacion_procesos) {
                        ?>
                        &nbsp;
                        <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-info active"> <i class="fa fa-edit"></i> </a>
                        <?php
                    }
                    ?>
                    &nbsp;
                    <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-primary active">R.</a>
                    &nbsp;
                    <a data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-success active">Fact</a>
                    &nbsp;
                    <?php
                    /* primer certificado */
                    if ($participante['id_emision_certificado'] == '0' && $sw_habilitacion_procesos && $id_certificado_curso !== '0') {
                        ?>
                        <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);" class="btn btn-xs btn-info active">Cert</a>
                        </span>
                        <?php
                    } elseif ($participante['id_emision_certificado'] !== '0') {
                        $sw_existencia_certificado_uno = true;
                        ?>
                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado']; ?>');" class="btn btn-xs btn-warning active">Cert</a>
                        <?php
                    }

                    /* segundo certificado */
                    if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0') {
                        ?>
                        <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);" class="btn btn-xs btn-info active">C2</a>
                        </span>
                        <?php
                    } elseif ($participante['id_emision_certificado_2'] !== '0') {
                        $sw_existencia_certificado_dos = true;
                        ?>
                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado_2']; ?>');" class="btn btn-xs btn-warning active">C2</a>
                        <?php
                    }
                    ?>

                    <?php
                    if ($sw_habilitacion_procesos) {
                        $rqmc1 = query("SELECT id FROM cursos_modelos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
                        if (mysql_num_rows($rqmc1) > 0) {
                            ?>
                            &nbsp;           
                            <a data-toggle="modal" data-target="#MODAL-certificados-secundarios" onclick="procesar_certificados_secundarios('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-primary active">CS</a>
                            <?php
                        }
                    }
                    ?>

                    <?php
                    if ($sw_habilitacion_procesos) {
                        ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;            
                        <span id="ajaxbox-button-eliminar-participante-<?php echo $participante['id']; ?>">
                            <a data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-danger active"> X </a>
                        </span>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<?php
if ($sw_habilitacion_procesos) {
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">EMISION / IMPRESION MULTIPLE DE CERTIFICADOS</div>
                <div class="panel-body">
                    <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>PRIMER CERTIFICADO</h4>
                    <br/>
                    <?php
                    if ($sw_existencia_certificado_uno) {
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('1');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                    <input type="checkbox" checked="" disabled=""/>
                    &nbsp;&nbsp;&nbsp; 
                    <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emision_multiple_certificados();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                    <br/>
                    <br/>
                    <?php
                    if ($id_certificado_2_curso !== '0' && $sw_habilitacion_procesos) {
                        ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>SEGUNDO CERTIFICADO</h4>
                        <br/>
                        <?php
                        if ($sw_existencia_certificado_dos) {
                            ?>
                            <input type="checkbox" checked="" disabled=""/>
                            &nbsp;&nbsp;&nbsp; 
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('2');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                            <br/>
                            <br/>
                            <?php
                        }
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emision_multiple_c2_certificados();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">EMISION / IMPRESION MULTIPLE DE FACTURAS</div>
                <div class="panel-body">

                    <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>FACTURAS</h4>
                    <br/>
                    <?php
                    if ($sw_existencia_facturas) {
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a class="btn btn-xs btn-default" onclick="imprimir_facturas();"> <i class="fa fa-print"></i> Imprimir facturas </a> 
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                    <input type="checkbox" checked="" disabled=""/>
                    &nbsp;&nbsp;&nbsp; 
                    <a data-toggle="modal" data-target="#MODAL-emite-facturas-multiple" onclick="emision_multiple_facturas();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir facturas </a>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">PROCESOS PARTICULARES</div>
                <div class="panel-body">
<!--                    <button class="btn btn-info" onclick="procesa_checked_participantes();">MOSTRAR SELECCIONADOS</button>-->
<!--                    <hr/>-->
                    <i>Deshabilitaci&oacute;n a los participantes no seleccionados.</i>
                    <br/>
                    <br/>
                    <button class="btn btn-danger active" onclick="deshabilita_participantes_no_seleccionados();" data-toggle="modal" data-target="#MODAL-deshabilita_participantes_no_seleccionados">
                        DESHABILITAR PARTICIPANTES
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>



<script>

    var array_check_participante = new Object();

    var aux_idsalmacenador = "<?php echo $aux_idsalmacenador; ?>";

    
</script>