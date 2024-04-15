<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');

/* ultima propuesta */
$rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=0 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
if (num_rows($rqulp1) > 0) {
    $rqulp2 = fetch($rqulp1);
    $monto = (int)$rqulp2['monto'];
} else {
    $monto = 500000;
}
?>

<input type="hidden" id="ultimo_valor_ofertado" value="<?php echo (int)($monto/2000); ?>"/>

<div _ngcontent-vgu-c14="" class="card">
    <div _ngcontent-vgu-c14="" class="card-header">
        <div _ngcontent-vgu-c14="" class="row">
            <div _ngcontent-vgu-c14="" class="col-lg-7 col-sm-6">
                <div _ngcontent-vgu-c14="" class="card-title"></div>
            </div>
            <div _ngcontent-vgu-c14="" class="col-lg-5 col-sm-6 col-xs-12">
                <div _ngcontent-vgu-c14="" class="row">
                    <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <form onsubmit="return false" _ngcontent-vgu-c14="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                            <div _ngcontent-vgu-c14="" class="input-group"><input _ngcontent-vgu-c14="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar" type="text"><span _ngcontent-vgu-c14="" class="input-group-btn"><button _ngcontent-vgu-c14="" class="btn btn-primary" type="submit"><span _ngcontent-vgu-c14="" class="fa fa-search"></span></button></span></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div _ngcontent-vgu-c14="" class="card-body">
        <div _ngcontent-vgu-c14="" class="row">
            <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12" id="id-tabla_items">
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
                            <td _ngcontent-nhg-c28=""><input id="monto-propuesta" type="number" name="" value="<?php echo (int)($monto/2000); ?>" class="form-control" onkeyup="pjs_calcular_precio(this.value);" onchange="pjs_calcular_precio(this.value);" /></td>
                            <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item"><?php echo number_format($monto,2,'.',','); ?></td>
                        </tr>
                        <tr _ngcontent-nhg-c28="">
                            <td _ngcontent-nhg-c28="" class="text-right" colspan="5"><b>Total Referencial:</b></td>
                            <td _ngcontent-nhg-c28="" class="text-right">500,000.00</td>
                            <td _ngcontent-nhg-c28="" class="text-right"><b>Total Ofertado:</b></td>
                            <td _ngcontent-nhg-c28="" class="text-right" id="id-total-ofertado"><?php echo number_format($monto,2,'.',','); ?></td>
                            </td>
                        </tr>
                    </tbody>
                </table><br _ngcontent-vgu-c14="">
            </div>
        </div>
    </div>
</div>

<div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cerrar</button>
    <button onclick="enviar_propuesta();" _ngcontent-pjb-c45="" class="btn btn-primary btn-sm" type="button">Aceptar</button>
</div>