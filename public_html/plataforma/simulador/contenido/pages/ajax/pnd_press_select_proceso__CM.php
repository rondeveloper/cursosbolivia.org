<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');


$cuce = '21-1712-00-1116922-1-1';
$objeto = 'MANTENIMIENTO DE CANCHA DE FUTBOL ESTADIO INTEGRACION BOLIVIA.';
$modalidad = 'CM';
$forma_adjudicacion = 'Por el Total';
$precio_referencial = '47,600.00';
$rqdde1 = query("SELECT * FROM simulador_documentos WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
if(num_rows($rqdde1)==0){
    query("INSERT INTO simulador_documentos
    (id_usuario, cuce, objeto, modalidad, forma_adjudicacion, precio_referencial, fecha_registro, estado) 
    VALUES 
    ('$id_usuario','$cuce','$objeto','$modalidad','$forma_adjudicacion','$precio_referencial',NOW(),'0')");
}
?>
<router-outlet _ngcontent-crt-c1=""></router-outlet>
<prv-docs-datos-generales-screen _nghost-crt-c27="" class="ng-star-inserted">
    <!---->
    <div _ngcontent-crt-c27="" class="content-heading p5 ng-star-inserted">
        <div _ngcontent-crt-c27="" class="row row-cols-3 w-100">
            <div _ngcontent-crt-c27="" class="col-5 pt10"> Mis Documentos </div>
            <div _ngcontent-crt-c27="" class="col-lg-5 col-xs-4 pt10">
                <spinner-http _ngcontent-crt-c27="" _nghost-crt-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-crt-c27="" class="col-lg-2 col-xs-3">
                <reloj-fragment _ngcontent-crt-c27="" _nghost-crt-c14="">
                    <div _ngcontent-crt-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-crt-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-crt-c14="" class="text-center">
                                <div _ngcontent-crt-c14="" class="text-sm">Marzo</div>
                                <div _ngcontent-crt-c14="" class="h4 mt-0">03</div>
                            </div>
                        </div>
                        <div _ngcontent-crt-c14="" class="col-8 rounded-right"><span _ngcontent-crt-c14="" class="text-uppercase h5 m0">Miércoles</span><br _ngcontent-crt-c14="">
                            <div _ngcontent-crt-c14="" class="h4 mt-0">12:25:14</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <prv-datos-doc-propuesta-fragment _ngcontent-crt-c27="" _nghost-crt-c28="">
        <div _ngcontent-crt-c28="" class="card b">
            <div _ngcontent-crt-c28="" class="card-header bb">
                <h4 _ngcontent-crt-c28="" class="card-title">Datos del Documento</h4>
            </div>
            <div _ngcontent-crt-c28="" class="card-body bt">
                <div _ngcontent-crt-c28="" class="row">
                    <div _ngcontent-crt-c28="" class="col">
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-crt-c28="" class="mt">Tipo de Operación:</label></div>
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                            <!----><select _ngcontent-crt-c28="" class="form-control ng-untouched ng-pristine ng-valid ng-star-inserted" name="selOperacion" disabled>
                                <!---->
                                <option _ngcontent-crt-c28="" value="0: Object" class="ng-star-inserted">Presentación de Propuesta/Oferta </option>
                                <option _ngcontent-crt-c28="" value="1: Object" class="ng-star-inserted">Retiro de Propuesta/Oferta </option>
                            </select>
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-crt-c28="" class="col">
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-crt-c28="" class="mt">Nro. Documento:</label></div>
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-crt-c28="" class="form-control input-sm" disabled="true" type="text" value="1047"></div>
                    </div>
                    <div _ngcontent-crt-c28="" class="col">
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-crt-c28="" class="mt">Estado:</label></div>
                        <div _ngcontent-crt-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-crt-c28="" class="form-control input-sm" disabled="true" type="text" value="ELABORADO"></div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <prv-hab-retiro-modal _ngcontent-crt-c28="" id="modalRetiro" _nghost-crt-c31="">
            <div _ngcontent-crt-c31="" bsmodal="" class="modal fade">
                <div _ngcontent-crt-c31="" class="modal-dialog modal-xl">
                    <div _ngcontent-crt-c31="" class="modal-content">
                        <div _ngcontent-crt-c31="" class="modal-header text-center">
                            <h4 _ngcontent-crt-c31="" class="text-color-blanco w-100"> Propuesta Electrónica a retirar </h4><button _ngcontent-crt-c31="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c31="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-crt-c31="" class="modal-body">
                            <div _ngcontent-crt-c31="" class="row">
                                <div _ngcontent-crt-c31="" class="col-lg-6 col-md-6 col-sm-6">
                                    <div _ngcontent-crt-c31="" class="card-title"></div>
                                </div>
                                <div _ngcontent-crt-c31="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div _ngcontent-crt-c31="" class="row">
                                        <div _ngcontent-crt-c31="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <form _ngcontent-crt-c31="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                <div _ngcontent-crt-c31="" class="input-group"><input _ngcontent-crt-c31="" class="form-control ng-untouched ng-pristine ng-valid" name="descDocumentoBusqueda" placeholder="" type="text"><span _ngcontent-crt-c31="" class="input-group-btn"><button _ngcontent-crt-c31="" class="btn btn-primary" type="submit"><span _ngcontent-crt-c31="" class="fa fa-search"></span></button></span></div>
                                            </form><br _ngcontent-crt-c31="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-crt-c31="" class="row">
                                <div _ngcontent-crt-c31="" class="col-lg-12 col-md-12">
                                    <div _ngcontent-crt-c31="" class="table-responsive">
                                        <table _ngcontent-crt-c31="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                            <thead _ngcontent-crt-c31="">
                                                <tr _ngcontent-crt-c31="">
                                                    <th _ngcontent-crt-c31=""></th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Nro. Documento</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Tipo de Operación</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">CUCE</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Objeto de Contratación</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Modalidad</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Fecha de Aprobación</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Fecha de Presentación</th>
                                                    <th _ngcontent-crt-c31="" class="tex-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-crt-c31="">
                                                <!---->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-crt-c31="" class="col-lg-12 col-md-12 text-center"></div>
                        </div>
                        <div _ngcontent-crt-c31="" class="modal-footer"><button _ngcontent-crt-c31="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </prv-hab-retiro-modal>
    </prv-datos-doc-propuesta-fragment>
    <datos-generales-fragment _ngcontent-crt-c27="" _nghost-crt-c29="">
        <div _ngcontent-crt-c29="" class="card b">
            <div _ngcontent-crt-c29="" class="card-header d-flex align-items-center">
                <div _ngcontent-crt-c29="" class="d-flex col p-0">
                    <h4 _ngcontent-crt-c29="" class="card-title">Datos del Proceso</h4>
                </div>
                <div _ngcontent-crt-c29="" class="d-flex justify-content-end">
                    <!---->
                    <!---->
                    <div _ngcontent-crt-c29="" class="btn-group ng-star-inserted" dropdown=""><button _ngcontent-crt-c29="" class="btn btn-link" dropdowntoggle="" aria-haspopup="true"><em _ngcontent-crt-c29="" class="fa fa-ellipsis-v fa-lg text-muted"></em></button>
                        <!---->
                    </div>
                    <!---->
                </div>
            </div>
            <div _ngcontent-crt-c29="" class="card-body bt padding-top-0">
                <div _ngcontent-crt-c29="" class="row">
                    <!---->
                    <div _ngcontent-crt-c29="" class="col-lg ng-star-inserted"><button onclick="pnd_seleccionar_proceso();" _ngcontent-crt-c29="" class="btn btn-primary btn-sm"> Seleccionar Proceso </button></div>
                </div>
                <div _ngcontent-crt-c29="" class="row row-cols-2 mt-2">
                    <div _ngcontent-crt-c29="" class="col-12 col-lg-4">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">CUCE:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> 21-1712-00-1116922-1-1 </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-lg-8">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Objeto de Contratación:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> MANTENIMIENTO DE CANCHA DE FUTBOL ESTADIO INTEGRACION BOLIVIA. </div>
                    </div>
                </div>
                <div _ngcontent-crt-c29="" class="row row-cols-3">
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Entidad:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> Gobierno Autónomo Municipal de Yapacaní </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Fecha de Publicación:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> 03/03/2021 10:33 </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Normativa:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> NB-SABS (D.S.0181) </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Modalidad:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> Contratación Menor </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Tipo de Contratación:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> Servicios Generales </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Forma de Adjudicación:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> Por el Total </div>
                    </div>
                    <!---->
                    <!---->
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Moneda:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> BOLIVIANOS </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Tipo de Cambio:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> 1 </div>
                    </div>
                    <div _ngcontent-crt-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-crt-c29="" class="col"><b _ngcontent-crt-c29="" class="text-bold">Fecha de Presentación:</b></div>
                        <div _ngcontent-crt-c29="" class="col"> 08/03/2021 09:00 </div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <datos-cronograma-modal _ngcontent-crt-c29="" id="idScPCronDModal" _nghost-crt-c32="">
            <div _ngcontent-crt-c32="" bsmodal="" class="modal fade">
                <div _ngcontent-crt-c32="" class="modal-dialog modal-lg">
                    <div _ngcontent-crt-c32="" class="modal-content">
                        <div _ngcontent-crt-c32="" class="modal-header text-center">
                            <h4 _ngcontent-crt-c32="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-crt-c32="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c32="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-crt-c32="" class="modal-body">
                            <div _ngcontent-crt-c32="" class="row">
                                <div _ngcontent-crt-c32="" class="col-lg-12 col-md-12">
                                    <!---->
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-crt-c32="" class="modal-footer"><button _ngcontent-crt-c32="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                    </div>
                </div>
            </div>
        </datos-cronograma-modal>
        <scpro-list-modal _ngcontent-crt-c29="" id="idScproListModal" _nghost-crt-c33="">
            <div _ngcontent-crt-c33="" bsmodal="" class="modal fade" aria-hidden="true" aria-modal="true" style="display: none;">
                <div _ngcontent-crt-c33="" class="modal-dialog modal-xl">
                    <div _ngcontent-crt-c33="" class="modal-content">
                        <div _ngcontent-crt-c33="" class="modal-header text-center">
                            <h4 _ngcontent-crt-c33="" class="text-color-blanco w-100"> Seleccionar Proceso de Contratación </h4><button _ngcontent-crt-c33="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c33="" aria-hidden="true">×</span></button>
                        </div>
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
                                                                <form _ngcontent-crt-c34="" name="formBusquedaDocumento" novalidate="" class="ng-valid ng-dirty ng-touched">
                                                                    <div _ngcontent-crt-c34="" class="input-group"><input _ngcontent-crt-c34="" class="form-control ng-valid ng-dirty ng-touched" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-crt-c34="" class="input-group-btn"><button _ngcontent-crt-c34="" class="btn btn-primary" type="submit"><span _ngcontent-crt-c34="" class="fa fa-search"></span></button></span></div>
                                                                </form>
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
                                                                        <td _ngcontent-crt-c34="" class="text-center ng-star-inserted"><label _ngcontent-crt-c34="" class="radio-inline c-radio"><input _ngcontent-crt-c34="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-crt-c34="" class="fa fa-circle"></span></label></td>
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
                                                                    <!---->
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
                        <div _ngcontent-crt-c33="" class="modal-footer"><button _ngcontent-crt-c33="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </scpro-list-modal>
    </datos-generales-fragment>
    <prv-datos-proveedor-fragment _ngcontent-crt-c27="" _nghost-crt-c30="">
        <div _ngcontent-crt-c30="" class="card b">
            <div _ngcontent-crt-c30="" class="card-header d-flex align-items-center">
                <div _ngcontent-crt-c30="" class="d-flex col p-0">
                    <h4 _ngcontent-crt-c30="" class="card-title">Datos del Proveedor</h4>
                </div>
                <div _ngcontent-crt-c30="" class="d-flex justify-content-end"><button _ngcontent-crt-c30="" class="btn btn-link text-muted"><em _ngcontent-crt-c30="" class="fa fa-minus text-muted"></em></button></div>
            </div>
            <div _ngcontent-crt-c30="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                <div _ngcontent-crt-c30="" class="row row-cols-4">
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Razón Social:</label></div>
                        <div _ngcontent-crt-c30="" class="col">
                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Documento:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> NIT - 2044323014 </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Rupe:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> 229523 </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Correo electrónico:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> info@nemabol.com </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Teléfono:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Fax:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Dirección:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> calle loayza Nro 250 piso 4 of 409 </div>
                    </div>
                    <div _ngcontent-crt-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-crt-c30="" class="col"><label _ngcontent-crt-c30="" class="text-bold">Matrícula de Comercio:</label></div>
                        <div _ngcontent-crt-c30="" class="col"> 344712 </div>
                    </div>
                    <!---->
                    <!---->
                </div>
                <!---->
            </div>
        </div>
    </prv-datos-proveedor-fragment>
    <!---->
    <botones-opciones-footer _ngcontent-crt-c27="" _nghost-crt-c24="" class="ng-star-inserted">
        <div _ngcontent-crt-c24="" class="row">
            <div _ngcontent-crt-c24="" class="col-12 text-right">
                <!---->
                <!----><a onclick="page_reg_nuevo_documento_p1('CM');" _ngcontent-crt-c24="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-crt-c24="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-crt-c24="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
            </div>
        </div>
    </botones-opciones-footer>
</prv-docs-datos-generales-screen>