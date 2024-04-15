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



/* query principal */
/* participantes eliminados */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='0' $qr_busqueda $qr_turno ORDER BY id DESC ");
?>

<table class="table users-table table-striped table-hover">
    <thead>
        <tr>
            <th class="visible-lg">Participantes eliminados</th>
            <th class="visible-lg">Facturaci&oacute;n</th>
            <th class="visible-lg">MR</th>
            <th class="visible-lg">Registro</th>
            <th class="visible-lg">.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if (mysql_num_rows($resultado1) == 0) {
            echo "<tr><td colspan='4'>No existen participantes eliminados.</td></tr>";
        }
        while ($participante = mysql_fetch_array($resultado1)) {

            /* datos de registro */
            $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
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
                <td class="visible-lg">
                    <?php
                    echo ' - ' . trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']);
                    echo " | ";
                    echo $participante['celular'] . ' ' . $participante['correo'];
                    echo " | ";
                    echo $razon_social_de_registro . ' ' . $nit_de_registro;
                    ?>
                </td>
                <td class="visible-lg">
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
                <td class="visible-lg">
                    <?php
                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                        echo "Sis";
                    } elseif ($id_modo_de_registro == '2') {
                        echo "Adm";
                    }
                    ?>
                </td>
                <td class="visible-lg">
                    <?php
                    echo date("d / M H:i", strtotime($fecha_de_registro));
                    ?>
                </td>
                <td class="visible-lg">
                    <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-primary">R.</a>
                    <?php
                    if ($sw_habilitacion_procesos) {
                        ?>
                        &nbsp;&nbsp;
                        <a data-toggle="modal" data-target="#MODAL-habilita-participante" onclick="habilita_participante_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-warning"> Habilitar </a>
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
