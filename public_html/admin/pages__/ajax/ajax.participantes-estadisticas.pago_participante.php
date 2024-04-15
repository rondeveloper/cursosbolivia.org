<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = fetch($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT * FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
if ($imagen_de_deposito == '') {
    $url_imagen = "https://www.eternacadencia.com.ar/components/com_virtuemart/assets/images/vmgeneral/no-image.jpg";
} else {
    $url_imagen = $dominio_www. "contenido/imagenes/depositos/" . $imagen_de_deposito;
}

$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$paydata_id_administrador = $data_registro['paydata_id_administrador'];
$paydata_fecha = $data_registro['paydata_fecha'];

$id_cobro_khipu = $data_registro['id_cobro_khipu'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* curso */
$rqddcr1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqddcr2 = fetch($rqddcr1);
$sw_procesos = false;
$htm_disabled = ' disabled="" ';
if ($rqddcr2['estado'] == '1' || $rqddcr2['estado'] == '2') {
    $sw_procesos = true;
    $htm_disabled = '';
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;">
           <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
        <?php
        if ($sw_pago_enviado == '1') {
            ?>
            <b class="pull-right btn btn-success active">PAGO DEFINIDO</b>
            <?php
        } else {
            ?>
            <b class="pull-right btn btn-danger active">PAGO NO DEFINIDO</b>
            <?php
        }
        ?>
        <br/>
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
    <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-center">
                    Monto de pago
                </h5>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Monto de pago (BS):</span>
                    <input type="text" class="form-control text-center" name="monto_pago" value="<?php echo $monto_de_pago; ?>" placeholder="Monto..." disabled=""/>
                </div>
                <br/>
                <h5 class="text-center">
                    Modo de pago
                </h5>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Modalidad de pago:</span>
                    <select name="id_modo_pago" class="form-control" disabled="">
                        <option value="">Sin pago</option>
                        <?php
                        $rqdmdp1 = query("SELECT * FROM modos_de_pago WHERE estado='1' ");
                        while ($rqdmdp2 = fetch($rqdmdp1)) {
                            $selected_aux1 = '';
                            if ($data_registro['id_modo_pago'] == $rqdmdp2['id']) {
                                $selected_aux1 = ' selected="selected" ';
                            }
                            ?>
                            <option value="<?php echo $rqdmdp2['id']; ?>" <?php echo $selected_aux1; ?> >
                                <?php echo $rqdmdp2['titulo']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <br/>
                <h5 class="text-center">
                    Banco
                </h5>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Modalidad de pago:</span>
                    <select name="id_banco" class="form-control" id="id-banco" <?php echo $htm_disabled; ?>>
                        <option value="0">No aplica</option>
                        <?php
                        $rqdmdp1 = query("SELECT c.*,(b.nombre)nombre_banco FROM cuentas_de_banco c LEFT JOIN bancos b ON c.id_banco=b.id WHERE c.estado='1' ORDER BY b.nombre DESC ");
                        while ($rqdmdp2 = fetch($rqdmdp1)) {
                            $selected_aux2 = '';
                            if ($data_registro['id_banco'] == $rqdmdp2['id']) {
                                $selected_aux2 = ' selected="selected" ';
                            }
                        ?>
                            <option value="<?php echo $rqdmdp2['id']; ?>" <?= $selected_aux2 ?>>
                                <?php echo $rqdmdp2['nombre_banco'] . ' &nbsp; | &nbsp; ' . $rqdmdp2['numero_cuenta']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($imagen_de_deposito == '') {
                    echo "<br>Sin imagen";
                } else {
                    ?>
                    <img src="<?php echo $url_imagen; ?>" style="width:100%;"/>
                    <br/>
                    <?php
                    echo '<a href="' . $url_imagen . '" target="_blank" style="color: #4CAF50;text-decoration: underline;">' . $imagen_de_deposito . '</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <?php
    if ($id_cobro_khipu != '0') {
        $rqdckv1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ");
        $rqdckv2 = fetch($rqdckv1);
        ?>
        <br/>

        <div style="border: 1px solid #8cc2e6;">
            <table class="table table-bordered table-dtriped">
                <tr>
                    <td colspan="3">PAGO CON TARJETA ( KHIPU )</td>
                </tr>
                <tr>
                    <td>
                        <?php echo strtoupper($rqdckv2['payment_id']); ?>
                    </td>
                    <td>
                        <?php
                        if ($rqdckv2['estado'] == '1') {
                            echo '<b class="btn btn-xs btn-success active small">PAGO REALIZADO</b>';
                        } else {
                            echo '<b class="btn btn-xs btn-danger active small">PAGO NO EFECTUADO</b>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo "<a href='https://khipu.com/payment/info/" . $rqdckv2['payment_id'] . "' target='_blank'>https://khipu.com/payment/info/" . $rqdckv2['payment_id'] . "</a>"; ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    ?>

    <br/>

    <div style="border: 1px solid #8cc2e6;">
        <table class="table table-bordered table-dtriped">
            <tr>
                <td colspan="4">DATOS DE REGISTRO/ACTUALIZACI&Oacute;N DE PAGO</td>
            </tr>
            <tr>
                <td><b>Administrador:</b></td>
                <td>
                    <?php
                    if ($paydata_id_administrador == '1') {
                        echo "SISTEMA";
                    } elseif ($paydata_id_administrador == '0') {
                        echo "Sin datos registrados";
                    } else {
                        $rqda1 = query("SELECT nombre FROM administradores WHERE id='$paydata_id_administrador' LIMIT 1 ");
                        $rqda2 = fetch($rqda1);
                        echo $rqda2['nombre'];
                    }
                    ?>
                </td>
                <td><b>Fecha:</b></td>
                <td>
                    <?php
                    if ($paydata_id_administrador == '0') {
                        echo "Sin datos registrados";
                    } else {
                        echo date("d / y / Y H:i", strtotime($paydata_fecha));
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <br/>
</div>
