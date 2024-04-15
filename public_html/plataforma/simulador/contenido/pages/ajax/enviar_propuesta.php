<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$monto = (int)get('monto')*2000;

$id_usuario = usuario('id_sim');

if($id_usuario==15980){
    echo "DENEGADO";
    exit;
}

if ($monto > 0) {
    query("INSERT INTO simulador_sigep_propuestas (id_usuario,monto,item) VALUES ('$id_usuario','$monto','0') ");
}

/* verrificacion de estado */
$rqv1 = query("SELECT id_usuario FROM simulador_sigep_propuestas WHERE item=0 ORDER BY monto ASC,id ASC limit 1 ");
$rqv2 = fetch($rqv1);
$color_estado_1 = 'gray';
$color_estado_2 = 'gray';
if ($rqv2['id_usuario'] == $id_usuario) {
    $color_estado_1 = 'green';
} else {
    $color_estado_2 = 'red';
}

if ($monto == 0) {
    /* ultima propuesta */
    $rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=0 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
    if (num_rows($rqulp1) > 0) {
        $rqulp2 = fetch($rqulp1);
        $monto = (int)$rqulp2['monto'];
    } else {
        $monto = 500000;
    }
}
?>

<div _ngcontent-vgu-c12="" class="row">
    <div _ngcontent-vgu-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <prouns-list-fragment _ngcontent-vgu-c12="" _nghost-vgu-c14="">
            <div _ngcontent-vgu-c14="" class="row">
                <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div _ngcontent-vgu-c14="" class="card card-default">
                        <div class="card-header" style="background: #e4e9ec;">
                            <div class="row">
                                <div class="col-md-1"><button onclick="pjs_info_cronograma();" _ngcontent-nhg-c28="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-nhg-c28="" class="fa fa-cog text-primary"></span></button></div>
                                <div class="col-md-2"><b>Descripción:</b> Todos los Items</div>
                                <div class="col-md-2"><b>Inicio de Subasta:</b> <?php echo date("d/m/Y H:i:s"); ?></div>
                                <div class="col-md-2"><b>Cierre Preeliminar de Subasta:</b> <?php echo date("d/m/Y H:i:s", strtotime('+5 minute', time())); ?></div>
                                <div class="col-md-2"><b>Total Ofertado:</b> <?php echo number_format($monto,2,'.',','); ?></div>
                                <div class="col-md-2 text-center" style="margin-bottom: 5px;"><b id="btn-registrar-precio" class="btn btn-primary" onclick="pjs_registrar_precio_subasta_total();"><i class="fa fa-edit"></i> Registrar precios</b></div>
                                <div class="col-md-1" style="display: flex;justify-content: center;">
                                    <div style="background:<?php echo $color_estado_1; ?>;width: 30px;height: 30px;border-radius: 50%;margin: 0px 2px 2px 0px;float: left;">&nbsp;</div>
                                    <div style="background:<?php echo $color_estado_2; ?>;width: 30px;height: 30px;border-radius: 50%;margin: 0px 2px 0px 0px;float: left;">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-vgu-c14="" class="card-body">
                            <div _ngcontent-vgu-c14="" class="row">
                                <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12" id="id-tabla_items" style="padding-top: 20px;">
                                    <table _ngcontent-vgu-c14="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                        <thead _ngcontent-vgu-c14="">
                                            <tr _ngcontent-vgu-c14="">
                                                <th _ngcontent-vgu-c14="" colspan="6" class="text-center">Definido por la Entidad</th>
                                                <th _ngcontent-vgu-c14="" colspan="2" class="text-center">Definido por el Proveedor</th>
                                                <th _ngcontent-vgu-c14="" colspan="2"></th>
                                            </tr>
                                            <tr _ngcontent-vgu-c14="">
                                                <th _ngcontent-vgu-c14="">#</th>
                                                <th _ngcontent-vgu-c14="">Descripci&oacute;n del Bien o Servicio</th>
                                                <th _ngcontent-vgu-c14="">Unidad de Medida</th>
                                                <th _ngcontent-vgu-c14="">Cantidad</th>
                                                <th _ngcontent-vgu-c14="">Precio Referencial Unitario</th>
                                                <th _ngcontent-vgu-c14="">Precio Referencial Total</th>
                                                <th _ngcontent-vgu-c14="">Precio Unitario Ofertado</th>
                                                <th _ngcontent-vgu-c14="">Precio Total Ofertado</th>
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-vgu-c14="">
                                            <tr _ngcontent-nhg-c28="">
                                                <td _ngcontent-nhg-c28="">1</td>
                                                <td _ngcontent-nhg-c28="">TRAJE DE BIOSEGURIDAD</td>
                                                <td _ngcontent-nhg-c28="">PIEZA</td>
                                                <td _ngcontent-nhg-c28="" class="text-right">2,000</td>
                                                <td _ngcontent-nhg-c28="" class="text-right">250.00</td>
                                                <td _ngcontent-nhg-c28="" class="text-right">500,000.00</td>
                                                <td _ngcontent-nhg-c28="" class="text-right"><?php echo number_format($monto/2000,2,'.',','); ?></td>
                                                <td _ngcontent-nhg-c28="" class="text-right"><?php echo number_format($monto,2,'.',','); ?></td>
                                            </tr>
                                            <tr _ngcontent-nhg-c28="">
                                                <td _ngcontent-nhg-c28="" class="text-right" colspan="5"><b>Total Referencial:</b></td>
                                                <td _ngcontent-nhg-c28="" class="text-right">500,000.00</td>
                                                <td _ngcontent-nhg-c28="" class="text-right"><b>Total Ofertado:</b></td>
                                                <td _ngcontent-nhg-c28="" class="text-right"><?php echo number_format($monto,2,'.',','); ?></td>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table><br _ngcontent-vgu-c14="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </prouns-list-fragment>
    </div>
</div>