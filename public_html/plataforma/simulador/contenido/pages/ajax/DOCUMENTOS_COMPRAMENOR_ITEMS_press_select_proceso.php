<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');


$cuce = '21-0513-00-1151485-1-1';
$objeto = 'ADQUISICION DE PAPEL DE 75 GRAMOS TAMAÑO CARTA Y OFICIO BLANCO Y DE COLORES PARA DISTRITO COMERCIAL TARIJA';
$modalidad = 'CM';
$forma_adjudicacion = 'Por Items';
$precio_referencial = '0';
$rqdde1 = query("SELECT * FROM simulador_documentos WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
if (num_rows($rqdde1) == 0) {
    query("INSERT INTO simulador_documentos
    (id_usuario, cuce, objeto, modalidad, forma_adjudicacion, precio_referencial, fecha_registro, estado) 
    VALUES 
    ('$id_usuario','$cuce','$objeto','$modalidad','$forma_adjudicacion','$precio_referencial',NOW(),'0')");
}
?>
<router-outlet _ngcontent-dgv-c1=""></router-outlet>
<prv-docs-datos-generales-screen _nghost-dgv-c19="">
    <!---->
    <div _ngcontent-dgv-c19="" class="content-heading p5">
        <div _ngcontent-dgv-c19="" class="row row-cols-3 w-100">
            <div _ngcontent-dgv-c19="" class="col-5 pt10"> Mis Documentos </div>
            <div _ngcontent-dgv-c19="" class="col-lg-5 col-xs-4 pt10">
                <spinner-http _ngcontent-dgv-c19="" _nghost-dgv-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-dgv-c19="" class="col-lg-2 col-xs-3">
                <reloj-fragment _ngcontent-dgv-c19="" _nghost-dgv-c14="">
                    <div _ngcontent-dgv-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-dgv-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-dgv-c14="" class="text-center">
                                <div _ngcontent-dgv-c14="" class="text-sm">Julio</div>
                                <div _ngcontent-dgv-c14="" class="h4 mt-0">22</div>
                            </div>
                        </div>
                        <div _ngcontent-dgv-c14="" class="col-8 rounded-right"><span _ngcontent-dgv-c14="" class="text-uppercase h5 m0">Jueves</span><br _ngcontent-dgv-c14="">
                            <div _ngcontent-dgv-c14="" class="h4 mt-0">15:32:13</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <prv-datos-doc-propuesta-fragment _ngcontent-dgv-c19="" _nghost-dgv-c20="">
        <div _ngcontent-dgv-c20="" class="card b">
            <div _ngcontent-dgv-c20="" class="card-header bb">
                <h4 _ngcontent-dgv-c20="" class="card-title">Datos del Documento</h4>
            </div>
            <div _ngcontent-dgv-c20="" class="card-body bt">
                <div _ngcontent-dgv-c20="" class="row">
                    <div _ngcontent-dgv-c20="" class="col">
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dgv-c20="" class="mt">Tipo de Operación:</label></div>
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                            <!----><select _ngcontent-dgv-c20="" class="form-control ng-untouched ng-pristine ng-valid" name="selOperacion">
                                <!---->
                                <option _ngcontent-dgv-c20="" value="0: Object">Presentación de Propuesta/Oferta </option>
                                <option _ngcontent-dgv-c20="" value="1: Object">Retiro de Propuesta/Oferta </option>
                            </select>
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-dgv-c20="" class="col">
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dgv-c20="" class="mt">Nro. Documento:</label></div>
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-dgv-c20="" class="form-control input-sm" disabled="true" type="text"></div>
                    </div>
                    <div _ngcontent-dgv-c20="" class="col">
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dgv-c20="" class="mt">Estado:</label></div>
                        <div _ngcontent-dgv-c20="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-dgv-c20="" class="form-control input-sm" disabled="true" type="text"></div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <prv-hab-retiro-modal _ngcontent-dgv-c20="" id="modalRetiro" _nghost-dgv-c24="">
            <div _ngcontent-dgv-c24="" bsmodal="" class="modal fade">
                <div _ngcontent-dgv-c24="" class="modal-dialog modal-xl">
                    <div _ngcontent-dgv-c24="" class="modal-content">
                        <div _ngcontent-dgv-c24="" class="modal-header text-center">
                            <h4 _ngcontent-dgv-c24="" class="text-color-blanco w-100"> Propuesta Electrónica a retirar </h4><button _ngcontent-dgv-c24="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-dgv-c24="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-dgv-c24="" class="modal-body">
                            <div _ngcontent-dgv-c24="" class="row">
                                <div _ngcontent-dgv-c24="" class="col-lg-6 col-md-6 col-sm-6">
                                    <div _ngcontent-dgv-c24="" class="card-title"></div>
                                </div>
                                <div _ngcontent-dgv-c24="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div _ngcontent-dgv-c24="" class="row">
                                        <div _ngcontent-dgv-c24="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <form _ngcontent-dgv-c24="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                <div _ngcontent-dgv-c24="" class="input-group"><input _ngcontent-dgv-c24="" class="form-control ng-untouched ng-pristine ng-valid" name="descDocumentoBusqueda" placeholder="" type="text"><span _ngcontent-dgv-c24="" class="input-group-btn"><button _ngcontent-dgv-c24="" class="btn btn-primary" type="submit"><span _ngcontent-dgv-c24="" class="fa fa-search"></span></button></span></div>
                                            </form><br _ngcontent-dgv-c24="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-dgv-c24="" class="row">
                                <div _ngcontent-dgv-c24="" class="col-lg-12 col-md-12">
                                    <div _ngcontent-dgv-c24="" class="table-responsive">
                                        <table _ngcontent-dgv-c24="" class="table table-bordered table-sm table-hover table-striped" id="tablaValues">
                                            <thead _ngcontent-dgv-c24="">
                                                <tr _ngcontent-dgv-c24="">
                                                    <th _ngcontent-dgv-c24=""></th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Nro. Documento</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Tipo de Operación</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">CUCE</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Objeto de Contratación</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Modalidad</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Fecha de Aprobación</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Fecha de Presentación</th>
                                                    <th _ngcontent-dgv-c24="" class="tex-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-dgv-c24="">
                                                <!---->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-dgv-c24="" class="col-lg-12 col-md-12 text-center"></div>
                        </div>
                        <div _ngcontent-dgv-c24="" class="modal-footer"><button _ngcontent-dgv-c24="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </prv-hab-retiro-modal>
    </prv-datos-doc-propuesta-fragment>
    <datos-generales-fragment _ngcontent-dgv-c19="" _nghost-dgv-c21="">
        <div _ngcontent-dgv-c21="" class="card b">
            <div _ngcontent-dgv-c21="" class="card-header d-flex align-items-center">
                <div _ngcontent-dgv-c21="" class="d-flex col p-0">
                    <h4 _ngcontent-dgv-c21="" class="card-title">Datos del Proceso</h4>
                </div>
                <div _ngcontent-dgv-c21="" class="d-flex justify-content-end">
                    <!---->
                    <!---->
                    <div _ngcontent-dgv-c21="" class="btn-group" dropdown=""><button _ngcontent-dgv-c21="" class="btn btn-link" dropdowntoggle="" aria-haspopup="true"><em _ngcontent-dgv-c21="" class="fa fa-ellipsis-v fa-lg text-muted"></em></button>
                        <!---->
                    </div>
                    <!---->
                </div>
            </div>
            <div _ngcontent-dgv-c21="" class="card-body bt padding-top-0">
                <div _ngcontent-dgv-c21="" class="row">
                    <!---->
                    <div _ngcontent-dgv-c21="" class="col-lg"><button onclick="pnd_seleccionar_proceso();" _ngcontent-dgv-c21="" class="btn btn-primary btn-sm"> Seleccionar Proceso </button></div>
                </div>
                <div _ngcontent-dgv-c21="" class="row row-cols-2 mt-2">
                    <div _ngcontent-dgv-c21="" class="col-12 col-lg-4">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">CUCE:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> 21-0513-00-1151485-1-1 </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-lg-8">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Objeto de Contratación:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> ADQUISICION DE PAPEL DE 75 GRAMOS TAMAÑO CARTA Y OFICIO BLANCO Y DE COLORES PARA DISTRITO COMERCIAL TARIJA </div>
                    </div>
                </div>
                <div _ngcontent-dgv-c21="" class="row row-cols-3">
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Entidad:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> Yacimientos Petroliferos Fiscales Bolivianos - Ypfb </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Fecha de Publicación:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> 21/07/2021 15:25 </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Normativa:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> NB-SABS (D.S.0181) </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Modalidad:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> Contratación Menor </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Tipo de Contratación:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> Bienes </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Forma de Adjudicación:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> Por Items </div>
                    </div>
                    <!---->
                    <!---->
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Moneda:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> BOLIVIANOS </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Tipo de Cambio:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> 1 </div>
                    </div>
                    <div _ngcontent-dgv-c21="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-dgv-c21="" class="col"><b _ngcontent-dgv-c21="" class="text-bold">Fecha de Presentación:</b></div>
                        <div _ngcontent-dgv-c21="" class="col"> 24/07/2021 08:00 </div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <datos-cronograma-modal _ngcontent-dgv-c21="" id="idScPCronDModal" _nghost-dgv-c25="">
            <div _ngcontent-dgv-c25="" bsmodal="" class="modal fade">
                <div _ngcontent-dgv-c25="" class="modal-dialog modal-lg">
                    <div _ngcontent-dgv-c25="" class="modal-content">
                        <div _ngcontent-dgv-c25="" class="modal-header text-center">
                            <h4 _ngcontent-dgv-c25="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-dgv-c25="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-dgv-c25="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-dgv-c25="" class="modal-body">
                            <div _ngcontent-dgv-c25="" class="row">
                                <div _ngcontent-dgv-c25="" class="col-lg-12 col-md-12">
                                    <!---->
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-dgv-c25="" class="modal-footer"><button _ngcontent-dgv-c25="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                    </div>
                </div>
            </div>
        </datos-cronograma-modal>
        <scpro-list-modal _ngcontent-dgv-c21="" id="idScproListModal" _nghost-dgv-c26="">
            <div _ngcontent-dgv-c26="" bsmodal="" class="modal fade" aria-hidden="true" aria-modal="true" style="display: none;">
                <div _ngcontent-dgv-c26="" class="modal-dialog modal-xl">
                    <div _ngcontent-dgv-c26="" class="modal-content">
                        <div _ngcontent-dgv-c26="" class="modal-header text-center">
                            <h4 _ngcontent-dgv-c26="" class="text-color-blanco w-100"> Seleccionar Proceso de Contratación </h4><button _ngcontent-dgv-c26="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-dgv-c26="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-dgv-c26="" class="modal-body">
                            <scpro-list _ngcontent-dgv-c26="" _nghost-dgv-c27="">
                                <!---->
                                <div _ngcontent-dgv-c27="" class="row">
                                    <div _ngcontent-dgv-c27="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div _ngcontent-dgv-c27="" class="card card-default">
                                            <div _ngcontent-dgv-c27="" class="card-header">
                                                <div _ngcontent-dgv-c27="" class="row">
                                                    <div _ngcontent-dgv-c27="" class="col-lg-6 col-md-6 col-sm-6">
                                                        <div _ngcontent-dgv-c27="" class="card-title"></div>
                                                    </div>
                                                    <div _ngcontent-dgv-c27="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div _ngcontent-dgv-c27="" class="row">
                                                            <div _ngcontent-dgv-c27="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                <form _ngcontent-dgv-c27="" name="formBusquedaDocumento" novalidate="" class="ng-valid ng-dirty ng-touched">
                                                                    <div _ngcontent-dgv-c27="" class="input-group"><input _ngcontent-dgv-c27="" class="form-control ng-valid ng-dirty ng-touched" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-dgv-c27="" class="input-group-btn"><button _ngcontent-dgv-c27="" class="btn btn-primary" type="submit"><span _ngcontent-dgv-c27="" class="fa fa-search"></span></button></span></div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div _ngcontent-dgv-c27="" class="row">
                                                            <div _ngcontent-dgv-c27="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                <button-filter _ngcontent-dgv-c27="" _nghost-dgv-c15="">
                                                                    <div _ngcontent-dgv-c15="" class="btn-group" dropdown=""><button _ngcontent-dgv-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-dgv-c15=""></b></button>
                                                                        <!---->
                                                                    </div>
                                                                </button-filter>
                                                                <button-filter _ngcontent-dgv-c27="" _nghost-dgv-c15="">
                                                                    <div _ngcontent-dgv-c15="" class="btn-group" dropdown=""><button _ngcontent-dgv-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Subasta<b _ngcontent-dgv-c15="">: Todos</b></button>
                                                                        <!---->
                                                                    </div>
                                                                </button-filter>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div _ngcontent-dgv-c27="" class="card-body">
                                                <div _ngcontent-dgv-c27="" class="row">
                                                    <div _ngcontent-dgv-c27="" class="col-lg-12 col-md-12">
                                                        <div _ngcontent-dgv-c27="" class="table-responsive">
                                                            <table _ngcontent-dgv-c27="" class="table table-bordered table-sm table-hover table-striped" id="tablaValues">
                                                                <thead _ngcontent-dgv-c27="">
                                                                    <tr _ngcontent-dgv-c27="">
                                                                        <!---->
                                                                        <!---->
                                                                        <th _ngcontent-dgv-c27="" class="text-center"></th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">CUCE</th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">Objeto de Contratación</th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">Tipo Contratación</th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">Modalidad</th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">Forma de Adjudicación</th>
                                                                        <th _ngcontent-dgv-c27="" class="text-center">Fecha Límite de Presentación de Propuestas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody _ngcontent-dgv-c27="">
                                                                    <!---->
                                                                    <tr _ngcontent-dgv-c27="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-dgv-c27="" class="text-center"><label _ngcontent-dgv-c27="" class="radio-inline c-radio"><input _ngcontent-dgv-c27="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-dgv-c27="" class="fa fa-circle"></span></label></td>
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
                                                                    <!---->
                                                                    <!---->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div _ngcontent-dgv-c27="" class="col-lg-12 col-md-12 text-center">
                                                            <pagination _ngcontent-dgv-c27="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                                                <ul class="pagination pagination-sm justify-content-center">
                                                                    <!---->
                                                                    <li class="pagination-first page-item disabled"><a class="page-link" href="">Primero</a></li>
                                                                    <!---->
                                                                    <li class="pagination-prev page-item disabled"><a class="page-link" href="">Anterior</a></li>
                                                                    <!---->
                                                                    <li class="pagination-page page-item active"><a class="page-link" href="">1</a></li>
                                                                    <!---->
                                                                    <li class="pagination-next page-item disabled"><a class="page-link" href="">Siguiente</a></li>
                                                                    <!---->
                                                                    <li class="pagination-last page-item disabled"><a class="page-link" href="">Último</a></li>
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
                        <div _ngcontent-dgv-c26="" class="modal-footer"><button _ngcontent-dgv-c26="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </scpro-list-modal>
    </datos-generales-fragment>
    <prv-datos-proveedor-fragment _ngcontent-dgv-c19="" _nghost-dgv-c22="">
        <div _ngcontent-dgv-c22="" class="card b">
            <div _ngcontent-dgv-c22="" class="card-header d-flex align-items-center">
                <div _ngcontent-dgv-c22="" class="d-flex col p-0">
                    <h4 _ngcontent-dgv-c22="" class="card-title">Datos del Proveedor</h4>
                </div>
                <div _ngcontent-dgv-c22="" class="d-flex justify-content-end"><button _ngcontent-dgv-c22="" class="btn btn-link text-muted"><em _ngcontent-dgv-c22="" class="fa fa-minus text-muted"></em></button></div>
            </div>
            <div _ngcontent-dgv-c22="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                <div _ngcontent-dgv-c22="" class="row row-cols-4">
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Razón Social:</label></div>
                        <div _ngcontent-dgv-c22="" class="col">
                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Documento:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> NIT - 2044323014 </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Rupe:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> 229523 </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Correo electrónico:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> info@nemabol.com </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Teléfono:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> 62360090 </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Fax:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Dirección:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> Plaza Sucre #1483 esq. Nicolas Acosta </div>
                    </div>
                    <div _ngcontent-dgv-c22="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-dgv-c22="" class="col"><label _ngcontent-dgv-c22="" class="text-bold">Matrícula de Comercio:</label></div>
                        <div _ngcontent-dgv-c22="" class="col"> 344712 </div>
                    </div>
                    <!---->
                    <!---->
                </div>
                <!---->
            </div>
        </div>
    </prv-datos-proveedor-fragment>
    <!---->
    <botones-opciones-footer _ngcontent-dgv-c19="" _nghost-dgv-c23="">
        <div _ngcontent-dgv-c23="" class="row">
            <div _ngcontent-dgv-c23="" class="col-12 text-right">
                <!---->
                <!----><button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_siguiente_a_registro_precios()" _ngcontent-dgv-c23="" class="btn btn-oval btn-primary"><i _ngcontent-dgv-c23="" class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </botones-opciones-footer>
</prv-docs-datos-generales-screen>