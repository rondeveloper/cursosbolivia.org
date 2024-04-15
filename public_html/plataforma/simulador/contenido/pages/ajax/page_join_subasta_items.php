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
                    <div _ngcontent-lkh-c21="" class="col-12"> Subasta Electr&oacute;nica </div>
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
                    ADQUISICION INSUMOS DE BIOSEGURIDAD
                    </span>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <span style="font-size: 11pt;">
                            Cuce: 21-0293-00-1120509-1-1
                            </span>
                        </div>
                        <div class="col-md-4 text-center">
                            Forma de adjudicacion: Por items
                        </div>
                        <div class="col-md-4 text-center">
                            Precio total ofertado: 1,024,400.00
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div _ngcontent-jbq-c12="" class="col-lg-2 col-sm-12 col-md-2 col-12 text-center">
            <span style="color: #3071c3;">Actualizado <?php echo date("d/m/Y"); ?> a Hrs. <?php echo date("H:i:s"); ?></span>
            <br>
            <b class="btn btn-primary btn-block" onclick="pjsi_actualizar_tabla();">Actualizar</b>
        </div>
    </div>
</procesos-subasta-list-screen>

<div id="box-msj-alert">
<div style="background: #dfe2e2;
    padding: 15px 25px;
    font-size: 12pt;
    color: #000;
    border: 1px solid #cecece;
    border-radius: 5px;" onclick="start_subasta();">
    <i class="fa fa-exclamation"></i> &nbsp; El cierre preliminar es el <?php echo date("d/m/Y H:i:s",strtotime('+5 minute',time())); ?>
</div>
</div>

<br>

<div _ngcontent-vgu-c12="" class="row">
        <div _ngcontent-vgu-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <prouns-list-fragment _ngcontent-vgu-c12="" _nghost-vgu-c14="">
                <div _ngcontent-vgu-c14="" class="row">
                    <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div _ngcontent-vgu-c14="" class="card card-default">
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
                                                    <th _ngcontent-vgu-c14=""></th>
                                                    <th _ngcontent-vgu-c14=""></th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-vgu-c14="">
                                            <tr _ngcontent-nhg-c28="">
                                                    <td _ngcontent-nhg-c28="">1</td>
                                                    <td _ngcontent-nhg-c28="">TRAJES DE BIOSEGURIDAD 1 PIEZA</td>
                                                    <td _ngcontent-nhg-c28="">PIEZA</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">3,200</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">120.00</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">384,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><input id="id-input-item-1" type="number" name="" value="120" class="form-control" onkeyup="pjsi_calcular_precio(1,this.value,3200);"/></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-1">384,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-1" onclick="pjsi_registrar_precio(1);">Registrar precio</b></td>
                                                    <td _ngcontent-nhg-c28="">
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
                                                    </td>
                                                </tr>
                                                <tr _ngcontent-nhg-c28="">
                                                    <td _ngcontent-nhg-c28="">2</td>
                                                    <td _ngcontent-nhg-c28="">TRAJES DE BIOSEGURIDAD 2 PIEZAS</td>
                                                    <td _ngcontent-nhg-c28="">PIEZA</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">3,500</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">170.00</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">595,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><input id="id-input-item-2" type="number" name="" value="170" class="form-control" onkeyup="pjsi_calcular_precio(2,this.value,3500);"/></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-2">595,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-2" onclick="pjsi_registrar_precio(2);">Registrar precio</b></td>
                                                    <td _ngcontent-nhg-c28="">
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
                                                    </td>
                                                </tr>
                                                <tr _ngcontent-nhg-c28="">
                                                    <td _ngcontent-nhg-c28="">3</td>
                                                    <td _ngcontent-nhg-c28="">GUANTES LATEX</td>
                                                    <td _ngcontent-nhg-c28="">CAJA</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">1,000</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">40.00</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">40,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><input id="id-input-item-3" type="number" name="" value="40" class="form-control" onkeyup="pjsi_calcular_precio(3,this.value,1000);"/></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-3">40,000.00</td>
                                                    <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-3" onclick="pjsi_registrar_precio(3);">Registrar precio</b></td>
                                                    <td _ngcontent-nhg-c28="">
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
                                                    </td>
                                                </tr>
                                                <tr _ngcontent-nhg-c28="">
                                                    <td _ngcontent-nhg-c28="">4</td>
                                                    <td _ngcontent-nhg-c28="">ALCOHOL EN GEL</td>
                                                    <td _ngcontent-nhg-c28="">PIEZA</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">270</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">20.00</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">5,400.00</td>
                                                    <td _ngcontent-nhg-c28=""><input id="id-input-item-4" type="number" name="" value="20" class="form-control" onkeyup="pjsi_calcular_precio(4,this.value,270);"/></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-4">5,400.00</td>
                                                    <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-4" onclick="pjsi_registrar_precio(4);">Registrar precio</b></td>
                                                    <td _ngcontent-nhg-c28="">
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                                                        <div style="background: #c1c1c1;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
                                                    </td>
                                                </tr>   
                                                <tr _ngcontent-nhg-c28="">
                                                    <td _ngcontent-nhg-c28="" class="text-right" colspan="5"><b>Total Referencial:</b></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right">1,024,400.00</td>
                                                    <td _ngcontent-nhg-c28="" class="text-right"><b>Total Ofertado:</b></td>
                                                    <td _ngcontent-nhg-c28="" class="text-right" id="id-total-ofertado">1,024,400.00</td>
                                                    <td _ngcontent-nhg-c28="" colspan="2"></td>
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

<br>
<br>


<div id="id-tabla_hitorial"></div>
<div id="id-tabla_resultados"></div>


    
<br>
<br>

