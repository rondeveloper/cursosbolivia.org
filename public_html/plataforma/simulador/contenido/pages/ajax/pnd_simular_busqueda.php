<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* permisos */
$sw_acceso_envio_formularios = false;
$sw_acceso_compras_menores = false;
$sw_acceso_anpe_lp = false;
$sw_acceso_subastas = false;
if (usuario('sw_acceso_envio_formularios') == '1') {
    $sw_acceso_envio_formularios = true;
}
if (usuario('sw_acceso_compras_menores') == '1') {
    $sw_acceso_compras_menores = true;
}
if (usuario('sw_acceso_anpe_lp') == '1') {
    $sw_acceso_anpe_lp = true;
}
if (usuario('sw_acceso_subastas') == '1') {
    $sw_acceso_subastas = true;
}
?>


<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 1100px !important;
            margin: 1.75rem auto;
        }
    }
</style>


<div _ngcontent-crt-c33="" class="modal-body">
    <scpro-list _ngcontent-crt-c33="" _nghost-crt-c34="">
        <!---->
        <div _ngcontent-crt-c34="" class="row">
            <div _ngcontent-crt-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div _ngcontent-crt-c34="" class="card card-default">
                    <div _ngcontent-crt-c34="" class="card-header">
                        <div _ngcontent-crt-c34="" class="row">
                            <div _ngcontent-crt-c34="" class="col-lg-6 col-md-6 col-sm-6">
                                <div _ngcontent-crt-c34="" class="card-title"></div>
                            </div>
                            <div _ngcontent-crt-c34="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div _ngcontent-crt-c34="" class="row">
                                    <div _ngcontent-crt-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div _ngcontent-crt-c34="" name="formBusquedaDocumento" novalidate="" class="ng-valid ng-dirty ng-touched">
                                            <div _ngcontent-crt-c34="" class="input-group"><input _ngcontent-crt-c34="" class="form-control ng-valid ng-dirty ng-touched" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text" value="21-1712-00-1116922-1-1"><span _ngcontent-crt-c34="" class="input-group-btn"><button onclick="pnd_simular_busqueda();" _ngcontent-crt-c34="" class="btn btn-primary" type="submit"><span _ngcontent-crt-c34="" class="fa fa-search"></span></button></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div _ngcontent-crt-c34="" class="row">
                                    <div _ngcontent-crt-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <button-filter _ngcontent-crt-c34="" _nghost-crt-c15="">
                                            <div _ngcontent-crt-c15="" class="btn-group" dropdown=""><button _ngcontent-crt-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-crt-c15=""></b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                        <button-filter _ngcontent-crt-c34="" _nghost-crt-c15="">
                                            <div _ngcontent-crt-c15="" class="btn-group" dropdown=""><button _ngcontent-crt-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Subasta<b _ngcontent-crt-c15="">: Todos</b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div _ngcontent-crt-c34="" class="card-body">
                        <div _ngcontent-crt-c34="" class="row">
                            <div _ngcontent-crt-c34="" class="col-lg-12 col-md-12">
                                <div _ngcontent-crt-c34="" class="table-responsive">
                                    <table _ngcontent-crt-c34="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                        <thead _ngcontent-crt-c34="">
                                            <tr _ngcontent-crt-c34="">
                                                <!---->
                                                <!---->
                                                <th _ngcontent-crt-c34="" class="text-center ng-star-inserted"></th>
                                                <th _ngcontent-crt-c34="" class="text-center">CUCE</th>
                                                <th _ngcontent-crt-c34="" class="text-center">Objeto de Contratación</th>
                                                <th _ngcontent-crt-c34="" class="text-center">Tipo Contratación</th>
                                                <th _ngcontent-crt-c34="" class="text-center">Modalidad</th>
                                                <th _ngcontent-crt-c34="" class="text-center">Forma de Adjudicación</th>
                                                <th _ngcontent-crt-c34="" class="text-center">Fecha Límite de Presentación de Propuestas</th>
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-crt-c34="">
                                            <!---->
                                            <tr _ngcontent-crt-c34="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-crt-c34="" class="text-center ng-star-inserted">
                                                    <label _ngcontent-crt-c34="" class="radio-inline c-radio">
                                                        <input _ngcontent-crt-c34="" id="selectProceso" name="seleccionarProceso" type="radio">
                                                        <?php if ($sw_acceso_compras_menores) { ?>
                                                            <span _ngcontent-crt-c34="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');"></span>
                                                        <?php } else { ?>
                                                            <span _ngcontent-crt-c34="" class="fa fa-circle" onclick="error_alert('SU CUENTA NO ESTA HABILITADA PARA COMPRAS MENORES');"></span>
                                                        <?php } ?>
                                                    </label>
                                                </td>
                                                <td _ngcontent-crt-c34="">21-1712-00-1116922-1-1</td>
                                                <td _ngcontent-crt-c34="">
                                                    <text-length _ngcontent-crt-c34="" _nghost-crt-c16="">MANTENIMIENTO DE CANCHA DE FUTBOL ESTADIO INTEGRAC
                                                        <!----><a _ngcontent-crt-c16="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-crt-c34="">Servicios Generales</td>
                                                <td _ngcontent-crt-c34="">CM</td>
                                                <td _ngcontent-crt-c34="">Por el Total</td>
                                                <td _ngcontent-crt-c34="">08/03/2021 09:00</td>
                                            </tr>
                                            <!---->
                                            <tr _ngcontent-for-c27="">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-for-c27="" class="text-center">
                                                    <label _ngcontent-for-c27="" class="radio-inline c-radio">
                                                        <input _ngcontent-for-c27="" id="selectProceso" name="seleccionarProceso" type="radio">
                                                        <?php if ($sw_acceso_anpe_lp) { ?>
                                                            <span _ngcontent-for-c27="" class="fa fa-circle" onclick="pnd_press_select_proceso('LP');"></span>
                                                        <?php } else { ?>
                                                            <span _ngcontent-crt-c34="" class="fa fa-circle" onclick="error_alert('SU CUENTA NO ESTA HABILITADA PARA LP');"></span>
                                                        <?php } ?>
                                                    </label>
                                                </td>
                                                <td _ngcontent-for-c27="">21-0513-00-1114217-1-1</td>
                                                <td _ngcontent-for-c27="">
                                                    <text-length _ngcontent-for-c27="" _nghost-for-c16="">ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL
                                                        <!---->
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-for-c27="">Bienes</td>
                                                <td _ngcontent-for-c27="">LP</td>
                                                <td _ngcontent-for-c27="">Por Items</td>
                                                <td _ngcontent-for-c27="">17/03/2021 09:00</td>
                                            </tr>
                                            <!---->
                                            <tr _ngcontent-dgv-c27="">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-dgv-c27="" class="text-center">
                                                    <label _ngcontent-dgv-c27="" class="radio-inline c-radio">
                                                        <input _ngcontent-dgv-c27="" id="selectProceso" name="seleccionarProceso" type="radio">
                                                        <?php if ($sw_acceso_compras_menores) { ?>
                                                            <span _ngcontent-for-c27="" class="fa fa-circle" onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso();"></span>
                                                        <?php } else { ?>
                                                            <span _ngcontent-crt-c34="" class="fa fa-circle" onclick="error_alert('SU CUENTA NO ESTA HABILITADA PARA COMPRAS MENORES');"></span>
                                                        <?php } ?>
                                                    </label>
                                                </td>
                                                <td _ngcontent-dgv-c27="">21-0513-00-1151485-1-1</td>
                                                <td _ngcontent-dgv-c27="">
                                                    <text-length _ngcontent-dgv-c27="" _nghost-dgv-c16="">ADQUISICION DE PAPEL DE 75 GRAMOS TAMAÑO CARTA Y
                                                        <!----><a _ngcontent-dgv-c16="">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-dgv-c27="">Bienes</td>
                                                <td _ngcontent-dgv-c27="">CM</td>
                                                <td _ngcontent-dgv-c27="">Por Items</td>
                                                <td _ngcontent-dgv-c27="">24/07/2021 08:00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div _ngcontent-crt-c34="" class="col-lg-12 col-md-12 text-center">
                                    <pagination _ngcontent-crt-c34="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                        <ul class="pagination pagination-sm justify-content-center">
                                            <!---->
                                            <li class="pagination-first page-item disabled ng-star-inserted"><a class="page-link" href="">Primero</a></li>
                                            <!---->
                                            <li class="pagination-prev page-item disabled ng-star-inserted"><a class="page-link" href="">Anterior</a></li>
                                            <!---->
                                            <li class="pagination-page page-item active ng-star-inserted"><a class="page-link" href="">1</a></li>
                                            <!---->
                                            <li class="pagination-next page-item ng-star-inserted disabled"><a class="page-link" href="">Siguiente</a></li>
                                            <!---->
                                            <li class="pagination-last page-item ng-star-inserted disabled"><a class="page-link" href="">Último</a></li>
                                        </ul>
                                    </pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </scpro-list>
</div>

<div _ngcontent-ruw-c49="" class="modal-footer"><button onclick="close_modal();" _ngcontent-ruw-c49="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>