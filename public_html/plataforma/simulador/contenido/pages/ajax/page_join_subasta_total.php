<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');

?>

<router-outlet _ngcontent-jbq-c1=""></router-outlet>
<procesos-subasta-list-screen _nghost-jbq-c12="">
    <div _ngcontent-lkh-c21="" class="content-heading p5">
        <div _ngcontent-lkh-c21="" class="row w-100">
            <div _ngcontent-lkh-c21="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-lkh-c21="" class="col-lg-5 col-12 pt10">
                <div _ngcontent-lkh-c21="" class="row">
                    <div _ngcontent-lkh-c21="" class="col-12"> Sala de Subasta </div>
                </div>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-lkh-c21="" _nghost-lkh-c18="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-lkh-c21="" _nghost-lkh-c22="">
                    <div _ngcontent-lkh-c22="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-lkh-c22="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-lkh-c22="" class="text-center">
                                <div _ngcontent-lkh-c22="" class="text-sm">Abril</div>
                                <div _ngcontent-lkh-c22="" class="h4 mt-0">16</div>
                            </div>
                        </div>
                        <div _ngcontent-lkh-c22="" class="col-8 rounded-right"><span _ngcontent-lkh-c22="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-lkh-c22="">
                            <div _ngcontent-lkh-c22="" class="h4 mt-0">12:09:18</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-10 col-sm-12 col-md-10 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">Datos Generales</div>
                    <span style="font-size: 20px;text-transform: uppercase;">
                    ADQUISICION DE 2,000 PIEZAS DE TRAJES DE BIOSEGURIDAD SOLICITADO POR LA JEFATURA REGIONAL DE ENFERMERIA
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
                            Forma de Adjudicaci&oacute;n: Por el Total
                        </div>
                        <div class="col-md-4 text-center">
                            Precio Total Ofertado: 500,000.00
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div _ngcontent-jbq-c12="" class="col-lg-2 col-sm-12 col-md-2 col-12 text-center">
            <span style="color: #3071c3;">Actualizado <?php echo date("d/m/Y"); ?> a Hrs. <?php echo date("H:i:s"); ?></span>
            <br>
            <b class="btn btn-primary btn-block" onclick="actualizar_estado_subasta();">Actualizar</b>
        </div>
    </div>
</procesos-subasta-list-screen>

<div id="box-msj-alert">
<div style="background: #dfe2e2;
    padding: 15px 25px;
    font-size: 12pt;
    color: #000;
    border: 1px solid #cecece;
    border-radius: 5px;font-weight:bold;">
    <i class="fa fa-exclamation-circle"></i> &nbsp; El cierre preliminar es el <?php echo date("d/m/Y H:i:s",strtotime('+5 minute',time())); ?>
</div>
</div>

<br>

<div id="panel-estado-subasta">
<?php
/* ultima propuesta */
$rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=0 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
if (num_rows($rqulp1) > 0) {
    $rqulp2 = fetch($rqulp1);
    $monto = (int)$rqulp2['monto'];
} else {
    $monto = 500000;
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
                                    <div class="col-md-2"><b>Cierre Preeliminar de Subasta:</b> <?php echo date("d/m/Y H:i:s",strtotime('+5 minute',time())); ?></div>
                                    <div class="col-md-2"><b>Total Ofertado:</b> <?php echo number_format($monto,2,'.',','); ?></div>
                                    <div class="col-md-2 text-center" style="margin-bottom: 5px;"><b id="btn-registrar-precio" class="btn btn-primary" onclick="pjs_registrar_precio_subasta_total();"><i class="fa fa-edit"></i> Registrar precios</b></div>
                                    <div class="col-md-1" style="display: flex;justify-content: center;">
                                        <div style="background:gray;width: 30px;height: 30px;border-radius: 50%;margin: 0px 2px 2px 0px;float: left;">&nbsp;</div>
                                        <div style="background:gray;width: 30px;height: 30px;border-radius: 50%;margin: 0px 2px 0px 0px;float: left;">&nbsp;</div>
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
</div>

<div id="panel-historial-subsata"></div>
<div id="panel-resultados-subsata"></div>


    
<br>
<br>

