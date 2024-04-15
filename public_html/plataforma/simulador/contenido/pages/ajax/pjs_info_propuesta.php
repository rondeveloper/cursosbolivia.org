<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_prop = get('id_prop');
$rqulpr1 = query("SELECT * FROM simulador_sigep_propuestas WHERE id='$id_prop' ");
$rqulpr2 = fetch($rqulpr1);


if($rqulpr2['item']!='0'){
    echo '<br>ERROR<br><br><div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cerrar</button>
</div>';
    exit;
}

$rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=0 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
if (num_rows($rqulp1) > 0) {
    $rqulp2 = fetch($rqulp1);
    $monto = (int)$rqulp2['monto'];
} else {
    $monto = 192000;
}
?>

<div style="padding: 20px;">
    <div _ngcontent-jbq-c12="" class="card card-default">
        <div _ngcontent-jbq-c12="" class="card-header">
            <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">Datos Generales</div>
            <span style="font-size: 20px;text-transform: uppercase;">
                ADQUISICION DE 1.600 PIEZAS DE TRAJES DE BIOSEGURIDAD SOLICITADO POR LA JEFATURA REGIONAL DE ENFERMERIA
            </span>
        </div>
        <div _ngcontent-jbq-c12="" class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <span style="font-size: 11pt;">
                        Cuce: 21-0417-06-1121995-2-1
                    </span>
                </div>
                <div class="col-md-4 text-center">
                    Forma de Adjudicación: Por el Total
                </div>
                <div class="col-md-4 text-center">
                    Precio Total Ofertado: <?php echo number_format($monto, 2, '.', ','); ?>
                </div>
            </div>
            <br>
        </div>
    </div>


    <div _ngcontent-jbq-c12="" class="card card-default">
        <div _ngcontent-jbq-c12="" class="card-body">
            <table _ngcontent-vgu-c14="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                <thead _ngcontent-vgu-c14="">
                    <tr _ngcontent-vgu-c14="">
                        <th _ngcontent-vgu-c14="">#</th>
                        <th _ngcontent-vgu-c14="">Descripción del Bien o Servicio</th>
                        <th _ngcontent-vgu-c14="">Unidad de Medida</th>
                        <th _ngcontent-vgu-c14="">Cantidad</th>
                        <th _ngcontent-vgu-c14="">Precio Unitario Ofertado</th>
                        <th _ngcontent-vgu-c14="">Precio Total Ofertado</th>
                    </tr>
                </thead>
                <tbody _ngcontent-vgu-c14="">
                    <tr _ngcontent-nhg-c28="">
                        <td _ngcontent-nhg-c28="">1</td>
                        <td _ngcontent-nhg-c28="">TRAJE DE BIOSEGURIDAD</td>
                        <td _ngcontent-nhg-c28="">PIEZA</td>
                        <td _ngcontent-nhg-c28="" class="text-right">1,600</td>
                        <td _ngcontent-nhg-c28="" class="text-right"><?php echo number_format($rqulpr2['monto']/1600, 2, '.', ','); ?></td>
                        <td _ngcontent-nhg-c28="" class="text-right"><?php echo number_format($rqulpr2['monto'], 2, '.', ','); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    Fecha de Aprobaci&oacute;n: <?php echo date("d/m/Y H:i", strtotime($rqulpr2['fecha'])); ?>
</div>

<div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cerrar</button>
</div>