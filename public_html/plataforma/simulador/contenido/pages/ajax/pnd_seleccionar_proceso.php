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

<div _ngcontent-ruw-c49="" class="modal-body">
    <scpro-list _ngcontent-ruw-c49="" _nghost-ruw-c50="">
        <!---->
        <div _ngcontent-ruw-c50="" class="row">
            <div _ngcontent-ruw-c50="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div _ngcontent-ruw-c50="" class="card card-default">
                    <div _ngcontent-ruw-c50="" class="card-header">
                        <div _ngcontent-ruw-c50="" class="row">
                            <div _ngcontent-ruw-c50="" class="col-lg-6 col-md-6 col-sm-6">
                                <div _ngcontent-ruw-c50="" class="card-title"></div>
                            </div>
                            <div _ngcontent-ruw-c50="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div _ngcontent-ruw-c50="" class="row">
                                    <div _ngcontent-ruw-c50="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <form _ngcontent-ruw-c50="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                            <div _ngcontent-ruw-c50="" class="input-group"><input _ngcontent-ruw-c50="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-ruw-c50="" class="input-group-btn"><button onclick="pnd_simular_busqueda();" _ngcontent-ruw-c50="" class="btn btn-primary" type="submit"><span _ngcontent-ruw-c50="" class="fa fa-search"></span></button></span></div>
                                        </form>
                                    </div>
                                </div>
                                <div _ngcontent-ruw-c50="" class="row">
                                    <div _ngcontent-ruw-c50="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <button-filter _ngcontent-ruw-c50="" _nghost-ruw-c15="">
                                            <div _ngcontent-ruw-c15="" class="btn-group" dropdown=""><button _ngcontent-ruw-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-ruw-c15=""></b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                        <button-filter _ngcontent-ruw-c50="" _nghost-ruw-c15="">
                                            <div _ngcontent-ruw-c15="" class="btn-group" dropdown=""><button _ngcontent-ruw-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Subasta<b _ngcontent-ruw-c15="">: Todos</b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div _ngcontent-ruw-c50="" class="card-body">
                        <div _ngcontent-ruw-c50="" class="row">
                            <div _ngcontent-ruw-c50="" class="col-lg-12 col-md-12">
                                <div _ngcontent-ruw-c50="" class="table-responsive">
                                    <table _ngcontent-ruw-c50="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                        <thead _ngcontent-ruw-c50="">
                                            <tr _ngcontent-ruw-c50="">
                                                <!---->
                                                <!---->
                                                <th _ngcontent-ruw-c50="" class="text-center ng-star-inserted"></th>
                                                <th _ngcontent-ruw-c50="" class="text-center">CUCE</th>
                                                <th _ngcontent-ruw-c50="" class="text-center">Objeto de Contratación</th>
                                                <th _ngcontent-ruw-c50="" class="text-center">Tipo Contratación</th>
                                                <th _ngcontent-ruw-c50="" class="text-center">Modalidad</th>
                                                <th _ngcontent-ruw-c50="" class="text-center">Forma de Adjudicación</th>
                                                <th _ngcontent-ruw-c50="" class="text-center">Fecha Límite de Presentación de Propuestas</th>
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-ruw-c50="">
                                            <!---->
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0517-00-1106653-2-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">CLQ-21-ANPE-13/2021 ADQUISICION DE AGENTE REACTIVO
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Bienes</td>
                                                <td _ngcontent-ruw-c50="">ANPE</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">04/03/2021 09:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0517-00-1106634-2-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">CLQ-21-ANPE-12/2021 ADQUISICIÓN DE AGENTE REACTIVO
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Bienes</td>
                                                <td _ngcontent-ruw-c50="">ANPE</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">04/03/2021 09:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0903-00-1115899-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICION DE ACEITE PARA STOCK DE ALMACEN CENTRA
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Bienes</td>
                                                <td _ngcontent-ruw-c50="">CM</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">03/03/2021 14:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0513-00-1115700-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICIÓN EPP BÁSICO PARA PERSONAL DEL DCOR Y GR
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Bienes</td>
                                                <td _ngcontent-ruw-c50="">ANPP</td>
                                                <td _ngcontent-ruw-c50="">Por Items</td>
                                                <td _ngcontent-ruw-c50="">10/03/2021 10:30</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0086-10-1115950-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">CONSULTORIA INDIVIDUAL DE LINEA INGENIERO CIVIL II
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Consultoría</td>
                                                <td _ngcontent-ruw-c50="">ANPE</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">05/03/2021 09:30</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0680-00-1115847-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICION DE DISPOSITIVOS DE ACCESO INALAMBRICO
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Bienes</td>
                                                <td _ngcontent-ruw-c50="">ANPE</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">05/03/2021 13:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-1301-00-1115945-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">CONST. GRADERIAS P/CAMPO DEPORTIVO OTB SEÑOR DE MA
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Obras</td>
                                                <td _ngcontent-ruw-c50="">ANPE</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">04/03/2021 08:30</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0513-00-1114338-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">SERVICIO DE TRANSPORTE PARA EL PERSONAL OPERATIVO
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Servicios Generales</td>
                                                <td _ngcontent-ruw-c50="">LP</td>
                                                <td _ngcontent-ruw-c50="">Por Items</td>
                                                <td _ngcontent-ruw-c50="">24/03/2021 10:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-1406-00-1115942-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">MEJORAMIENTO DE 2 AULAS PARA TALLERES U. E. FRANZ
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Obras</td>
                                                <td _ngcontent-ruw-c50="">CM</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">03/03/2021 10:00</td>
                                            </tr>
                                            <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle" onclick="pnd_press_select_proceso('CM');" ></span></label></td>
                                                <td _ngcontent-ruw-c50="">21-0901-00-1115108-1-1</td>
                                                <td _ngcontent-ruw-c50="">
                                                    <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">AMPL. ELECTRIFICACION HUACARETA- CULPINA (TRAMO HU
                                                        <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                    </text-length>
                                                </td>
                                                <td _ngcontent-ruw-c50="">Obras</td>
                                                <td _ngcontent-ruw-c50="">LP</td>
                                                <td _ngcontent-ruw-c50="">Por el Total</td>
                                                <td _ngcontent-ruw-c50="">22/03/2021 09:00</td>
                                            </tr>
                                            <!---->
                                            <!---->
                                        </tbody>
                                    </table>
                                </div>
                                <div _ngcontent-ruw-c50="" class="col-lg-12 col-md-12 text-center">
                                    <pagination _ngcontent-ruw-c50="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                        <ul class="pagination pagination-sm justify-content-center">
                                            <!---->
                                            <li class="pagination-first page-item disabled ng-star-inserted"><a class="page-link" href="">Primero</a></li>
                                            <!---->
                                            <li class="pagination-prev page-item disabled ng-star-inserted"><a class="page-link" href="">Anterior</a></li>
                                            <!---->
                                            <li class="pagination-page page-item active ng-star-inserted"><a class="page-link" href="">1</a></li>
                                            <li class="pagination-page page-item ng-star-inserted"><a class="page-link" href="">2</a></li>
                                            <li class="pagination-page page-item ng-star-inserted"><a class="page-link" href="">3</a></li>
                                            <li class="pagination-page page-item ng-star-inserted"><a class="page-link" href="">4</a></li>
                                            <li class="pagination-page page-item ng-star-inserted"><a class="page-link" href="">5</a></li>
                                            <!---->
                                            <li class="pagination-next page-item ng-star-inserted"><a class="page-link" href="">Siguiente</a></li>
                                            <!---->
                                            <li class="pagination-last page-item ng-star-inserted"><a class="page-link" href="">Último</a></li>
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