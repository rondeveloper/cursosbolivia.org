<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>

<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 1100px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<div _ngcontent-crt-c45="" class="modal-body">
    <!---->
    <!---->
    <datos-items-fragment _ngcontent-crt-c45="" _nghost-crt-c39="" class="ng-star-inserted">
        <div _ngcontent-crt-c39="" class="row">
            <div _ngcontent-crt-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-crt-c39="" class="input-group input-group-sm"><input _ngcontent-crt-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-crt-c39="" class="input-group-btn"><button _ngcontent-crt-c39="" class="btn btn-primary" type="button"><span _ngcontent-crt-c39="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-crt-c39=""><br _ngcontent-crt-c39="">
        </div>
        <div _ngcontent-crt-c39="" class="table-responsive">
            <table _ngcontent-crt-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                <thead _ngcontent-crt-c39="">
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center border-right-color ng-star-inserted" colspan="6">Definido por la Entidad</th>
                        <th _ngcontent-crt-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                    </tr>
                    <tr _ngcontent-crt-c39="">
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center">#</th>
                        <th _ngcontent-crt-c39="" class="text-center">Descripción del Bien o Servicio</th>
                        <th _ngcontent-crt-c39="" class="text-center">Unidad de Medida</th>
                        <th _ngcontent-crt-c39="" class="text-center">Cantidad</th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted">
                            <!---->
                            <!----> Precio Unitario del Proveedor Preseleccionado
                            <!---->
                        </th>
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted">
                            <!---->
                            <!----> Precio Total del Proveedor Preseleccionado
                            <!---->
                        </th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted"> Precio Unitario Ofertado</th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted"> Precio Total Ofertado</th>
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                </thead>
                <tbody _ngcontent-crt-c39="">
                    <!---->
                    <!---->
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <!---->
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-center">1</td>
                        <td _ngcontent-crt-c39="">APLICACION DE PRODUCTOS ACELERANTES Y FOLIAR,TEPEADO DEL GRAMADO,ARENADO,APLICACION DE PRODUCTOS QUIMICOS,CORTE VERTICAL DEL CESPED,DEMARCACION DEL CAMPO DEPORTIVO Y LIMPIEZA</td>
                        <td _ngcontent-crt-c39="">M2</td>
                        <td _ngcontent-crt-c39="" class="text-right">7,000</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"> 6.80</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"> 47,600.00</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted">
                            <!---->
                            <!----><input onkeyup="prnd_CM_calcular_monto(this.value);" id="id-prnd_CM_monto-oferta" _ngcontent-crt-c39="" class="form form-control input-sm ng-untouched ng-pristine ng-valid ng-star-inserted" type="text">
                        </td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"><span _ngcontent-crt-c39="" id="id-prnd_CM_data-monto_total_1"></span></td>
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                    <!---->
                    <!---->
                </tbody>
                <tfoot _ngcontent-crt-c39="">
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <th _ngcontent-crt-c39="" class="text-right" colspan="5">Total Referencial:</th>
                        <th _ngcontent-crt-c39="" class="text-right">47,600.00 </th>
                        <th _ngcontent-crt-c39="" class="text-right">Total Ofertado:</th>
                        <th _ngcontent-crt-c39="" class="text-right" id="id-prnd_CM_data-monto_total_2">0.00</th>
                    </tr>
                    <!---->
                    <tr _ngcontent-crt-c39="" style="height: 110px;" class="ng-star-inserted"></tr>
                </tfoot>
            </table>
        </div>
    </datos-items-fragment>
</div>

<div _ngcontent-crt-c45="" class="modal-footer"><button onclick="close_modal();" _ngcontent-crt-c45="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button onclick="prnd_CM_registrar_precios_p2();" _ngcontent-crt-c45="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>