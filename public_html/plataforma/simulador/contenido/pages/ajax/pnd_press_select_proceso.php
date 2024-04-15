<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$busc = get('busc');

$id_usuario = usuario('id_sim');

$cuce = '21-0513-00-1114217-1-1';
$objeto = 'ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL';
$modalidad = 'LP';
$forma_adjudicacion = 'Por Items';
$precio_referencial = '4,470,110.00';
$rqdde1 = query("SELECT * FROM simulador_documentos WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
if(num_rows($rqdde1)==0){
    query("INSERT INTO simulador_documentos
    (id_usuario, cuce, objeto, modalidad, forma_adjudicacion, precio_referencial, fecha_registro, estado) 
    VALUES 
    ('$id_usuario','$cuce','$objeto','$modalidad','$forma_adjudicacion','$precio_referencial',NOW(),'0')");
}
?>

<router-outlet _ngcontent-ruw-c1=""></router-outlet>
<prv-docs-datos-generales-screen _nghost-ruw-c43="" class="ng-star-inserted">
    <!---->
    <div _ngcontent-ruw-c43="" class="content-heading p5 ng-star-inserted">
        <div _ngcontent-ruw-c43="" class="row row-cols-3 w-100">
            <div _ngcontent-ruw-c43="" class="col-5 pt10"> Mis Documentos </div>
            <div _ngcontent-ruw-c43="" class="col-lg-5 col-xs-4 pt10">
                <spinner-http _ngcontent-ruw-c43="" _nghost-ruw-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-ruw-c43="" class="col-lg-2 col-xs-3">
                <reloj-fragment _ngcontent-ruw-c43="" _nghost-ruw-c42="">
                    <div _ngcontent-ruw-c42="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-ruw-c42="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-ruw-c42="" class="text-center">
                                <div _ngcontent-ruw-c42="" class="text-sm">Febrero</div>
                                <div _ngcontent-ruw-c42="" class="h4 mt-0">26</div>
                            </div>
                        </div>
                        <div _ngcontent-ruw-c42="" class="col-8 rounded-right"><span _ngcontent-ruw-c42="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-ruw-c42="">
                            <div _ngcontent-ruw-c42="" class="h4 mt-0">17:20:28</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <prv-datos-doc-propuesta-fragment _ngcontent-ruw-c43="" _nghost-ruw-c44="">
        <div _ngcontent-ruw-c44="" class="card b">
            <div _ngcontent-ruw-c44="" class="card-header bb">
                <h4 _ngcontent-ruw-c44="" class="card-title">Datos del Documento</h4>
            </div>
            <div _ngcontent-ruw-c44="" class="card-body bt">
                <div _ngcontent-ruw-c44="" class="row">
                    <div _ngcontent-ruw-c44="" class="col">
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c44="" class="mt">Tipo de Operación:</label></div>
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                            <!----><select _ngcontent-ruw-c44="" class="form-control ng-valid ng-star-inserted ng-dirty ng-touched" name="selOperacion">
                                <!---->
                                <option _ngcontent-ruw-c44="" value="0: Object" class="ng-star-inserted">Presentación de Propuesta/Oferta </option>
                                <option _ngcontent-ruw-c44="" value="1: Object" class="ng-star-inserted">Retiro de Propuesta/Oferta </option>
                            </select>
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-ruw-c44="" class="col">
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c44="" class="mt">Nro. Documento:</label></div>
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c44="" class="form-control input-sm" disabled="true" type="text" value="1047"></div>
                    </div>
                    <div _ngcontent-ruw-c44="" class="col">
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c44="" class="mt">Estado:</label></div>
                        <div _ngcontent-ruw-c44="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c44="" class="form-control input-sm" disabled="true" type="text" value="ELABORADO"></div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <prv-hab-retiro-modal _ngcontent-ruw-c44="" id="modalRetiro" _nghost-ruw-c47="">
            <div _ngcontent-ruw-c47="" bsmodal="" class="modal fade">
                <div _ngcontent-ruw-c47="" class="modal-dialog modal-xl">
                    <div _ngcontent-ruw-c47="" class="modal-content">
                        <div _ngcontent-ruw-c47="" class="modal-header text-center">
                            <h4 _ngcontent-ruw-c47="" class="text-color-blanco w-100"> Propuesta Electrónica a retirar </h4><button _ngcontent-ruw-c47="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c47="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-ruw-c47="" class="modal-body">
                            <div _ngcontent-ruw-c47="" class="row">
                                <div _ngcontent-ruw-c47="" class="col-lg-6 col-md-6 col-sm-6">
                                    <div _ngcontent-ruw-c47="" class="card-title"></div>
                                </div>
                                <div _ngcontent-ruw-c47="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div _ngcontent-ruw-c47="" class="row">
                                        <div _ngcontent-ruw-c47="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <form _ngcontent-ruw-c47="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                <div _ngcontent-ruw-c47="" class="input-group"><input _ngcontent-ruw-c47="" class="form-control ng-untouched ng-pristine ng-valid" name="descDocumentoBusqueda" placeholder="" type="text"><span _ngcontent-ruw-c47="" class="input-group-btn"><button _ngcontent-ruw-c47="" class="btn btn-primary" type="submit"><span _ngcontent-ruw-c47="" class="fa fa-search"></span></button></span></div>
                                            </form><br _ngcontent-ruw-c47="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-ruw-c47="" class="row">
                                <div _ngcontent-ruw-c47="" class="col-lg-12 col-md-12">
                                    <div _ngcontent-ruw-c47="" class="table-responsive">
                                        <table _ngcontent-ruw-c47="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                            <thead _ngcontent-ruw-c47="">
                                                <tr _ngcontent-ruw-c47="">
                                                    <th _ngcontent-ruw-c47=""></th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Nro. Documento</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Tipo de Operación</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">CUCE</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Objeto de Contratación</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Modalidad</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Fecha de Aprobación</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Fecha de Presentación</th>
                                                    <th _ngcontent-ruw-c47="" class="tex-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-ruw-c47="">
                                                <!---->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-ruw-c47="" class="col-lg-12 col-md-12 text-center"></div>
                        </div>
                        <div _ngcontent-ruw-c47="" class="modal-footer"><button _ngcontent-ruw-c47="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </prv-hab-retiro-modal>
    </prv-datos-doc-propuesta-fragment>
    <datos-generales-fragment _ngcontent-ruw-c43="" _nghost-ruw-c45="">
        <div _ngcontent-ruw-c45="" class="card b">
            <div _ngcontent-ruw-c45="" class="card-header d-flex align-items-center">
                <div _ngcontent-ruw-c45="" class="d-flex col p-0">
                    <h4 _ngcontent-ruw-c45="" class="card-title">Datos del Proceso</h4>
                </div>
                <div _ngcontent-ruw-c45="" class="d-flex justify-content-end">
                    <!---->
                    <!---->
                    <div _ngcontent-ruw-c45="" class="btn-group ng-star-inserted" dropdown=""><button _ngcontent-ruw-c45="" class="btn btn-link" dropdowntoggle="" aria-haspopup="true"><em _ngcontent-ruw-c45="" class="fa fa-ellipsis-v fa-lg text-muted"></em></button>
                        <!---->
                    </div>
                    <!---->
                </div>
            </div>
            <div _ngcontent-ruw-c45="" class="card-body bt padding-top-0">
                <div _ngcontent-ruw-c45="" class="row">
                    <!---->
                    <div _ngcontent-ruw-c45="" class="col-lg ng-star-inserted"><button onclick="pnd_seleccionar_proceso();" _ngcontent-ruw-c45="" class="btn btn-primary btn-sm"> Seleccionar Proceso </button></div>
                </div>
                <div _ngcontent-ruw-c45="" class="row row-cols-2 mt-2">
                    <div _ngcontent-ruw-c45="" class="col-12 col-lg-4">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">CUCE:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> 21-0513-00-1114217-1-1 </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-lg-8">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Objeto de Contratación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL </div>
                    </div>
                </div>
                <div _ngcontent-ruw-c45="" class="row row-cols-3">
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Entidad:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> Yacimientos Petrolíferos Fiscales Bolivianos </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Fecha de Publicación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> 22/02/2021 16:40 </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Normativa:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> NB-SABS (D.S.0181) </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Modalidad:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> LP </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Tipo de Contratación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> Bienes </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Forma de Adjudicación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> Por Items </div>
                    </div>
                    <!---->
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Método de Selección y Adjudicación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> Precio evaluado más bajo </div>
                    </div>
                    <!---->
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Tipo de Convocatoria:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> Convocatoria Pública Nacional </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Moneda:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> BOLIVIANOS </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Tipo de Cambio:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> 1 </div>
                    </div>
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Fecha de Presentación:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> 17/03/2021 09:00 </div>
                    </div>
                    <!---->
                    <!---->
                    <div _ngcontent-ruw-c45="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                        <div _ngcontent-ruw-c45="" class="col"><b _ngcontent-ruw-c45="" class="text-bold">Fecha de Inicio de Subasta:</b></div>
                        <div _ngcontent-ruw-c45="" class="col"> 17/03/2021 09:30 </div>
                    </div>
                </div>
            </div>
        </div>
        <datos-cronograma-modal _ngcontent-ruw-c45="" id="idScPCronDModal" _nghost-ruw-c48="">
            <div _ngcontent-ruw-c48="" bsmodal="" class="modal fade">
                <div _ngcontent-ruw-c48="" class="modal-dialog modal-lg">
                    <div _ngcontent-ruw-c48="" class="modal-content">
                        <div _ngcontent-ruw-c48="" class="modal-header text-center">
                            <h4 _ngcontent-ruw-c48="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-ruw-c48="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c48="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-ruw-c48="" class="modal-body">
                            <div _ngcontent-ruw-c48="" class="row">
                                <div _ngcontent-ruw-c48="" class="col-lg-12 col-md-12">
                                    <!---->
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-ruw-c48="" class="modal-footer"><button _ngcontent-ruw-c48="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                    </div>
                </div>
            </div>
        </datos-cronograma-modal>
        <scpro-list-modal _ngcontent-ruw-c45="" id="idScproListModal" _nghost-ruw-c49="">
            <div _ngcontent-ruw-c49="" bsmodal="" class="modal fade" aria-hidden="true" aria-modal="true" style="display: none;">
                <div _ngcontent-ruw-c49="" class="modal-dialog modal-xl">
                    <div _ngcontent-ruw-c49="" class="modal-content">
                        <div _ngcontent-ruw-c49="" class="modal-header text-center">
                            <h4 _ngcontent-ruw-c49="" class="text-color-blanco w-100"> Seleccionar Proceso de Contratación </h4><button _ngcontent-ruw-c49="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c49="" aria-hidden="true">×</span></button>
                        </div>
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
                                                                <form _ngcontent-ruw-c50="" name="formBusquedaDocumento" novalidate="" class="ng-valid ng-dirty ng-touched">
                                                                    <div _ngcontent-ruw-c50="" class="input-group"><input _ngcontent-ruw-c50="" class="form-control ng-valid ng-dirty ng-touched" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-ruw-c50="" class="input-group-btn"><button _ngcontent-ruw-c50="" class="btn btn-primary" type="submit"><span _ngcontent-ruw-c50="" class="fa fa-search"></span></button></span></div>
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
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-0513-00-1115363-1-1</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICIÓN DE EQUIPOS DE COMPUTACIÓN ESPECIALIZAD
                                                                                <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Bienes</td>
                                                                        <td _ngcontent-ruw-c50="">LP</td>
                                                                        <td _ngcontent-ruw-c50="">Por Items</td>
                                                                        <td _ngcontent-ruw-c50="">25/03/2021 09:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-1312-00-1108868-1-2</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">UNIDAD DE EDUCACIÓN MUNICIPAL (SERVICIO DE INSTALA
                                                                                <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Servicios Generales</td>
                                                                        <td _ngcontent-ruw-c50="">ANPP</td>
                                                                        <td _ngcontent-ruw-c50="">Por el Total</td>
                                                                        <td _ngcontent-ruw-c50="">09/03/2021 08:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-1808-00-1115249-1-1</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICIÓN DE 10 COMPUTADORAS PARA LA UNIDAD EDUC
                                                                                <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Bienes</td>
                                                                        <td _ngcontent-ruw-c50="">CM</td>
                                                                        <td _ngcontent-ruw-c50="">Por el Total</td>
                                                                        <td _ngcontent-ruw-c50="">01/03/2021 09:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-0417-04-1114919-1-1</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">COMPRA EQUIPO DE COMPUTACION E IMPRESORAS CIMFA QU
                                                                                <!----><a _ngcontent-ruw-c40="" class="ng-star-inserted">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Bienes</td>
                                                                        <td _ngcontent-ruw-c50="">ANPP</td>
                                                                        <td _ngcontent-ruw-c50="">Por Items</td>
                                                                        <td _ngcontent-ruw-c50="">08/03/2021 08:48</td>
                                                                    </tr>
                                                                    <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-0513-00-1114217-1-1</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL
                                                                                <!---->
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Bienes</td>
                                                                        <td _ngcontent-ruw-c50="">LP</td>
                                                                        <td _ngcontent-ruw-c50="">Por Items</td>
                                                                        <td _ngcontent-ruw-c50="">17/03/2021 09:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-ruw-c50="" class="ng-star-inserted">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-ruw-c50="" class="text-center ng-star-inserted"><label _ngcontent-ruw-c50="" class="radio-inline c-radio"><input _ngcontent-ruw-c50="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-ruw-c50="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-ruw-c50="">21-0086-10-1113376-1-1</td>
                                                                        <td _ngcontent-ruw-c50="">
                                                                            <text-length _ngcontent-ruw-c50="" _nghost-ruw-c40="">COMPRA DE EQUIPOS DE COMPUTACIÓN E IMPRESORA
                                                                                <!---->
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-ruw-c50="">Bienes</td>
                                                                        <td _ngcontent-ruw-c50="">ANPP</td>
                                                                        <td _ngcontent-ruw-c50="">Por Items</td>
                                                                        <td _ngcontent-ruw-c50="">02/03/2021 10:00</td>
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
                        <div _ngcontent-ruw-c49="" class="modal-footer"><button _ngcontent-ruw-c49="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </scpro-list-modal>
    </datos-generales-fragment>
    <prv-datos-proveedor-fragment _ngcontent-ruw-c43="" _nghost-ruw-c46="">
        <div _ngcontent-ruw-c46="" class="card b">
            <div _ngcontent-ruw-c46="" class="card-header d-flex align-items-center">
                <div _ngcontent-ruw-c46="" class="d-flex col p-0">
                    <h4 _ngcontent-ruw-c46="" class="card-title">Datos del Proveedor</h4>
                </div>
                <div _ngcontent-ruw-c46="" class="d-flex justify-content-end"><button _ngcontent-ruw-c46="" class="btn btn-link text-muted"><em _ngcontent-ruw-c46="" class="fa fa-minus text-muted"></em></button></div>
            </div>
            <div _ngcontent-ruw-c46="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                <div _ngcontent-ruw-c46="" class="row row-cols-4">
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Razón Social:</label></div>
                        <div _ngcontent-ruw-c46="" class="col">
                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Documento:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> NIT - 2044323014 </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Rupe:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> 229523 </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Correo electrónico:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> info@nemabol.com </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Teléfono:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Fax:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Dirección:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> calle loayza Nro 250 piso 4 of 409 </div>
                    </div>
                    <div _ngcontent-ruw-c46="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-ruw-c46="" class="col"><label _ngcontent-ruw-c46="" class="text-bold">Matrícula de Comercio:</label></div>
                        <div _ngcontent-ruw-c46="" class="col"> 344712 </div>
                    </div>
                    <!---->
                    <!---->
                </div>
                <!---->
            </div>
        </div>
    </prv-datos-proveedor-fragment>
    <!---->
    <botones-opciones-footer _ngcontent-ruw-c43="" _nghost-ruw-c20="" class="ng-star-inserted">
        <div _ngcontent-ruw-c20="" class="row">
            <div _ngcontent-ruw-c20="" class="col-12 text-right">
                <!---->
                <!----><a onclick="page_reg_nuevo_documento_p1();" _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
            </div>
        </div>
    </botones-opciones-footer>
</prv-docs-datos-generales-screen>