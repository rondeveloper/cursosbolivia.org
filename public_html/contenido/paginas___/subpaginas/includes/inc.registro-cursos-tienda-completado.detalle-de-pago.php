<?php

/* id */
$id_tienda_registro;

$rq_tienda1 = query("SELECT 
tr.*, mdp.titulo, b.nombre, cdb.numero_cuenta 
FROM tienda_registros AS tr 
LEFT JOIN modos_de_pago AS mdp ON mdp.id = tr.id_modo_pago 
LEFT JOIN bancos AS b ON b.id = tr.id_banco 
LEFT JOIN cuentas_de_banco AS cdb ON cdb.id = tr.id_cuenta_banco 
WHERE tr.id='$id_tienda_registro' 
ORDER BY tr.id DESC limit 1 ");
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
                <td style='padding:5px;'><img src="<?= $dominio ?>contenido/imagenes/depositos/<?= $rq_tienda2['imagen_deposito'] ?>" alt="Foto Comprobante" width="130px" height="80px" /></td>
            </tr>
            <tr>
                <td style='padding:5px;'>Ciudad:</td>
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

        <div class="text-center">
            <br>
            <a href="registro-cursos-tienda-completado/<?= $id_tienda_registro ?>/<?= HashUtil::hashIdRegistroTienda($id_tienda_registro) ?>/actualizar-pago.html" style="text-decoration:underline;font-size:12pt;">
                Volver a enviar reporte
            </a>
            <br>
        </div>
    </div>
</div>