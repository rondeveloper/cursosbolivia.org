<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);
/* recepcion de datos POST */
$curso_id_modo_pago = post('id_modo_pago');
$curso_id_banco = post('id_banco');
$curso_id_cuenta_banco = post('id_cuenta_banco');
$curso_monto_deposito = post('monto_deposito');
$curso_imagen_deposito = post('imagen_deposito');
$curso_ciudad = post('ciudad');
$curso_fecha = post('fecha');
$curso_hora = post('hora');
$curso_id_tienda_registro = post('id_tienda_registro');
$curso_id_transaccion = post('transaccion_id');


/* imagen deposito */
if (is_uploaded_file(archivo('imagen_deposito'))) {
    $curso_imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
    move_uploaded_file(archivo('imagen_deposito'), $___path_raiz . 'contenido/imagenes/depositos/' . $curso_imagen_deposito);
}
/* Actualizar datos tienda registro */

query("UPDATE tienda_registros SET 
            id_modo_pago='$curso_id_modo_pago',
            id_banco='$curso_id_banco',
            id_cuenta_banco='$curso_id_cuenta_banco',
            monto_deposito='$curso_monto_deposito',
            imagen_deposito='$curso_imagen_deposito',
            ciudad='$curso_ciudad',
            fecha='$curso_fecha',
            hora='$curso_hora',
            id_transaccion='$curso_id_transaccion',
            estado=1 
            WHERE id='$curso_id_tienda_registro' ORDER BY id DESC limit 1 ");

$rq_tienda1 = query("SELECT *, mdp.titulo, b.nombre, cdb.numero_cuenta FROM tienda_registros AS tr LEFT JOIN modos_de_pago AS mdp ON mdp.id = tr.id_modo_pago LEFT JOIN bancos AS b ON b.id = tr.id_banco LEFT JOIN cuentas_de_banco AS cdb ON cdb.id = tr.id_cuenta_banco WHERE tr.id='$curso_id_tienda_registro' ORDER BY tr.id DESC limit 1 ");
$rq_tienda2 = fetch($rq_tienda1);
?>

<h3 class="text-center">Detalle del pago enviado</h3>

<div class="row lead">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <table class="table table-striped table-bordered" style="font-size: 11pt;">
            <tr>
                <td style='padding:5px;'>Metodo de pago:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['titulo']; ?></td>
            </tr>
            <?php
            if ($rq_tienda2['id_transaccion'] != '' && $rq_tienda2['id_transaccion'] != '0') {
            ?>
                <tr>
                    <td style='padding:5px;'>ID de transacción: (TIGO MONEY)</td>
                    <td style='padding:5px;'><?php echo $rq_tienda2['id_transaccion']; ?></td>
                </tr>
            <?php
            } ?>
            <tr>
                <td style='padding:5px;'>Banco:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['nombre']; ?></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Cuenta:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['numero_cuenta']; ?></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Monto en Bolivianos del pago:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['monto_deposito']; ?></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Foto del comprobante:</td>
                <td style='padding:5px;'><img src="<?= $dominio ?>contenido/imagenes/depositos/<?= $curso_imagen_deposito ?>" alt="Foto Comprobante" width="130px" height="80px" /></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Ciudad donde se hizo el pago:</td>
                <td style='padding:5px;'>
                    <?php
                    switch ($rq_tienda2['ciudad']) {
                        case '1':
                            echo 'Cochabamba';
                            break;
                        case '2':
                            echo 'Potosi';
                            break;
                        case '3':
                            echo 'La Paz';
                            break;
                        case '4':
                            echo 'Santa Cruz';
                            break;
                        case '5':
                            echo 'Tarija';
                            break;
                        case '6':
                            echo 'Chuquisaca';
                            break;
                        case '7':
                            echo 'Pando';
                            break;
                        case '8':
                            echo 'Oruro';
                            break;
                        case '9':
                            echo 'Beni';
                            break;
                        default:
                            echo '';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style='padding:5px;'>Fecha:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['fecha']; ?></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Hora:</td>
                <td style='padding:5px;'><?php echo $rq_tienda2['hora']; ?></td>
            </tr>
        </table>
    </div>
</div>