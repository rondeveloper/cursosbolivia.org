<?php

//echo "123test";
?>
<router-outlet _ngcontent-lkh-c1=""></router-outlet>
<prv-docs-datos-generales-screen _nghost-lkh-c23="">
    <!---->
    <div _ngcontent-lkh-c23="" class="content-heading p5">
        <div _ngcontent-lkh-c23="" class="row row-cols-3 w-100">
            <div _ngcontent-lkh-c23="" class="col-5 pt10"> Mis Documentos </div>
            <div _ngcontent-lkh-c23="" class="col-lg-5 col-xs-4 pt10">
                <spinner-http _ngcontent-lkh-c23="" _nghost-lkh-c18="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-lkh-c23="" class="col-lg-2 col-xs-3">
                <reloj-fragment _ngcontent-lkh-c23="" _nghost-lkh-c22="">
                    <div _ngcontent-lkh-c22="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-lkh-c22="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-lkh-c22="" class="text-center">
                                <div _ngcontent-lkh-c22="" class="text-sm">Febrero</div>
                                <div _ngcontent-lkh-c22="" class="h4 mt-0">19</div>
                            </div>
                        </div>
                        <div _ngcontent-lkh-c22="" class="col-8 rounded-right"><span _ngcontent-lkh-c22="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-lkh-c22="">
                            <div _ngcontent-lkh-c22="" class="h4 mt-0">14:26:38</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <prv-datos-doc-propuesta-fragment _ngcontent-lkh-c23="" _nghost-lkh-c24="">
        <div _ngcontent-lkh-c24="" class="card b">
            <div _ngcontent-lkh-c24="" class="card-header bb">
                <h4 _ngcontent-lkh-c24="" class="card-title">Datos del Documento</h4>
            </div>
            <div _ngcontent-lkh-c24="" class="card-body bt">
                <div _ngcontent-lkh-c24="" class="row">
                    <div _ngcontent-lkh-c24="" class="col">
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-lkh-c24="" class="mt">Tipo de Operación:</label></div>
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                            <!----><select _ngcontent-lkh-c24="" class="form-control ng-untouched ng-pristine ng-valid" name="selOperacion">
                                <!---->
                                <option _ngcontent-lkh-c24="" value="0: Object">Presentación de Propuesta/Oferta </option>
                                <option _ngcontent-lkh-c24="" value="1: Object">Retiro de Propuesta/Oferta </option>
                            </select>
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-lkh-c24="" class="col">
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-lkh-c24="" class="mt">Nro. Documento:</label></div>
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-lkh-c24="" class="form-control input-sm" disabled="true" type="text"></div>
                    </div>
                    <div _ngcontent-lkh-c24="" class="col">
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-lkh-c24="" class="mt">Estado:</label></div>
                        <div _ngcontent-lkh-c24="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-lkh-c24="" class="form-control input-sm" disabled="true" type="text" value="INICIAL"></div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <prv-hab-retiro-modal _ngcontent-lkh-c24="" id="modalRetiro" _nghost-lkh-c28="">
            <div _ngcontent-lkh-c28="" bsmodal="" class="modal fade">
                <div _ngcontent-lkh-c28="" class="modal-dialog modal-xl">
                    <div _ngcontent-lkh-c28="" class="modal-content">
                        <div _ngcontent-lkh-c28="" class="modal-header text-center">
                            <h4 _ngcontent-lkh-c28="" class="text-color-blanco w-100"> Propuesta Electrónica a retirar </h4><button _ngcontent-lkh-c28="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-lkh-c28="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-lkh-c28="" class="modal-body">
                            <div _ngcontent-lkh-c28="" class="row">
                                <div _ngcontent-lkh-c28="" class="col-lg-6 col-md-6 col-sm-6">
                                    <div _ngcontent-lkh-c28="" class="card-title"></div>
                                </div>
                                <div _ngcontent-lkh-c28="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div _ngcontent-lkh-c28="" class="row">
                                        <div _ngcontent-lkh-c28="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <form _ngcontent-lkh-c28="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                <div _ngcontent-lkh-c28="" class="input-group"><input _ngcontent-lkh-c28="" class="form-control ng-untouched ng-pristine ng-valid" name="descDocumentoBusqueda" placeholder="" type="text"><span _ngcontent-lkh-c28="" class="input-group-btn"><button _ngcontent-lkh-c28="" class="btn btn-primary" type="submit"><span _ngcontent-lkh-c28="" class="fa fa-search"></span></button></span></div>
                                            </form><br _ngcontent-lkh-c28="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-lkh-c28="" class="row">
                                <div _ngcontent-lkh-c28="" class="col-lg-12 col-md-12">
                                    <div _ngcontent-lkh-c28="" class="table-responsive">
                                        <table _ngcontent-lkh-c28="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                            <thead _ngcontent-lkh-c28="">
                                                <tr _ngcontent-lkh-c28="">
                                                    <th _ngcontent-lkh-c28=""></th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Nro. Documento</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Tipo de Operación</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">CUCE</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Objeto de Contratación</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Modalidad</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Fecha de Aprobación</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Fecha de Presentación</th>
                                                    <th _ngcontent-lkh-c28="" class="tex-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-lkh-c28="">
                                                <!---->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-lkh-c28="" class="col-lg-12 col-md-12 text-center"></div>
                        </div>
                        <div _ngcontent-lkh-c28="" class="modal-footer"><button _ngcontent-lkh-c28="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </prv-hab-retiro-modal>
    </prv-datos-doc-propuesta-fragment>
    <datos-generales-fragment _ngcontent-lkh-c23="" _nghost-lkh-c25="">
        <div _ngcontent-lkh-c25="" class="card b">
            <div _ngcontent-lkh-c25="" class="card-header d-flex align-items-center">
                <div _ngcontent-lkh-c25="" class="d-flex col p-0">
                    <h4 _ngcontent-lkh-c25="" class="card-title">Datos del Proceso</h4>
                </div>
                <div _ngcontent-lkh-c25="" class="d-flex justify-content-end">
                    <!---->
                </div>
            </div>
            <div _ngcontent-lkh-c25="" class="card-body bt padding-top-0">
                <div _ngcontent-lkh-c25="" class="row">
                    <!---->
                    <div _ngcontent-lkh-c25="" class="col-lg"><button onclick="pnd_seleccionar_proceso();" _ngcontent-lkh-c25="" class="btn btn-primary btn-sm"> Seleccionar Proceso </button></div>
                </div>
                <div _ngcontent-lkh-c25="" class="row row-cols-2 mt-2">
                    <div _ngcontent-lkh-c25="" class="col-12 col-lg-4">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">CUCE:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-lg-8">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Objeto de Contratación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                </div>
                <div _ngcontent-lkh-c25="" class="row row-cols-3">
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Entidad:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Fecha de Publicación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Normativa:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Modalidad:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Tipo de Contratación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Forma de Adjudicación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <!---->
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Método de Selección y Adjudicación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <!---->
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Tipo de Convocatoria:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Moneda:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Tipo de Cambio:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c25="" class="col-12 col-md-6 col-lg-4 mt-2">
                        <div _ngcontent-lkh-c25="" class="col"><b _ngcontent-lkh-c25="" class="text-bold">Fecha de Presentación:</b></div>
                        <div _ngcontent-lkh-c25="" class="col"> </div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
        <datos-cronograma-modal _ngcontent-lkh-c25="" id="idScPCronDModal" _nghost-lkh-c29="">
            <div _ngcontent-lkh-c29="" bsmodal="" class="modal fade">
                <div _ngcontent-lkh-c29="" class="modal-dialog modal-lg">
                    <div _ngcontent-lkh-c29="" class="modal-content">
                        <div _ngcontent-lkh-c29="" class="modal-header text-center">
                            <h4 _ngcontent-lkh-c29="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-lkh-c29="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-lkh-c29="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-lkh-c29="" class="modal-body">
                            <div _ngcontent-lkh-c29="" class="row">
                                <div _ngcontent-lkh-c29="" class="col-lg-12 col-md-12">
                                    <!---->
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-lkh-c29="" class="modal-footer"><button _ngcontent-lkh-c29="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                    </div>
                </div>
            </div>
        </datos-cronograma-modal>
        <scpro-list-modal _ngcontent-lkh-c25="" id="idScproListModal" _nghost-lkh-c30="">
            <div _ngcontent-lkh-c30="" bsmodal="" class="modal fade" aria-hidden="true" aria-modal="true" style="display: none;">
                <div _ngcontent-lkh-c30="" class="modal-dialog modal-xl">
                    <div _ngcontent-lkh-c30="" class="modal-content">
                        <div _ngcontent-lkh-c30="" class="modal-header text-center">
                            <h4 _ngcontent-lkh-c30="" class="text-color-blanco w-100"> Seleccionar Proceso de Contratación </h4><button _ngcontent-lkh-c30="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-lkh-c30="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-lkh-c30="" class="modal-body">
                            <scpro-list _ngcontent-lkh-c30="" _nghost-lkh-c31="">
                                <!---->
                                <div _ngcontent-lkh-c31="" class="row">
                                    <div _ngcontent-lkh-c31="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <div _ngcontent-lkh-c31="" class="card card-default">
                                            <div _ngcontent-lkh-c31="" class="card-header">
                                                <div _ngcontent-lkh-c31="" class="row">
                                                    <div _ngcontent-lkh-c31="" class="col-lg-6 col-md-6 col-sm-6">
                                                        <div _ngcontent-lkh-c31="" class="card-title"></div>
                                                    </div>
                                                    <div _ngcontent-lkh-c31="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div _ngcontent-lkh-c31="" class="row">
                                                            <div _ngcontent-lkh-c31="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                <form _ngcontent-lkh-c31="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                                    <div _ngcontent-lkh-c31="" class="input-group"><input _ngcontent-lkh-c31="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-lkh-c31="" class="input-group-btn"><button _ngcontent-lkh-c31="" class="btn btn-primary" type="submit"><span _ngcontent-lkh-c31="" class="fa fa-search"></span></button></span></div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div _ngcontent-lkh-c31="" class="row">
                                                            <div _ngcontent-lkh-c31="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                <button-filter _ngcontent-lkh-c31="" _nghost-lkh-c13="">
                                                                    <div _ngcontent-lkh-c13="" class="btn-group" dropdown=""><button _ngcontent-lkh-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-lkh-c13=""></b></button>
                                                                        <!---->
                                                                    </div>
                                                                </button-filter>
                                                                <button-filter _ngcontent-lkh-c31="" _nghost-lkh-c13="">
                                                                    <div _ngcontent-lkh-c13="" class="btn-group" dropdown=""><button _ngcontent-lkh-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Subasta<b _ngcontent-lkh-c13="">: Todos</b></button>
                                                                        <!---->
                                                                    </div>
                                                                </button-filter>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div _ngcontent-lkh-c31="" class="card-body">
                                                <div _ngcontent-lkh-c31="" class="row">
                                                    <div _ngcontent-lkh-c31="" class="col-lg-12 col-md-12">
                                                        <div _ngcontent-lkh-c31="" class="table-responsive">
                                                            <table _ngcontent-lkh-c31="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                                                <thead _ngcontent-lkh-c31="">
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <th _ngcontent-lkh-c31="" class="text-center"></th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">CUCE</th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">Objeto de Contratación</th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">Tipo Contratación</th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">Modalidad</th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">Forma de Adjudicación</th>
                                                                        <th _ngcontent-lkh-c31="" class="text-center">Fecha Límite de Presentación de Propuestas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody _ngcontent-lkh-c31="">
                                                                    <!---->
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1702-00-1113655-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">COMPRA DE REPUESTO Y TRABAJO MECÁNICO CORRECTIVO P
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Bienes</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 10:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-0907-08-1113663-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">ADQUISICIÓN DE MEZCLA ASFALTICA PARA BACHEO EN TRA
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Bienes</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 08:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1605-00-1109287-1-2</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">SUPERVISION TECNICA " MEJ. Y AMPLIACIÓN SISTEMA DE
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Consultoría</td>
                                                                        <td _ngcontent-lkh-c31="">ANPP</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">04/03/2021 09:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1312-00-1113658-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">ADMINISTRACION HOSPITAL SEGUNDO NIVEL MEXICO (MANT
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Servicios Generales</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 08:15</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1205-00-1113604-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">CONST. ENLOSETADO CALLES DIONISIO INCA Y TUPAC KAT
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Obras</td>
                                                                        <td _ngcontent-lkh-c31="">ANPE</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">25/02/2021 09:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-0139-00-1113635-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">COMPRA DE "6000 BOLÍGRAFOS ATOMIZADORES SERIGRAFIA
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Servicios Generales</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 11:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1312-00-1113317-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">UNIDAD DE EDUCACION MUNICIPAL (MANT. DE INF. EDUCA
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Servicios Generales</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 08:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-0907-08-1113615-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">ADQUISICIÓN DE ALCANTARILLAS METÁLICAS PARA ATENCI
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Bienes</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 08:30</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1205-00-1113603-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">CONST. CANCHA MULTIFUNCIONAL CONJUNTO HABITACIONAL
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Obras</td>
                                                                        <td _ngcontent-lkh-c31="">ANPE</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">25/02/2021 09:00</td>
                                                                    </tr>
                                                                    <tr _ngcontent-lkh-c31="">
                                                                        <!---->
                                                                        <!---->
                                                                        <td _ngcontent-lkh-c31="" class="text-center"><label _ngcontent-lkh-c31="" class="radio-inline c-radio"><input _ngcontent-lkh-c31="" id="selectProceso" name="seleccionarProceso" type="radio"><span _ngcontent-lkh-c31="" class="fa fa-circle"></span></label></td>
                                                                        <td _ngcontent-lkh-c31="">21-1734-00-1113613-1-1</td>
                                                                        <td _ngcontent-lkh-c31="">
                                                                            <text-length _ngcontent-lkh-c31="" _nghost-lkh-c14="">ADQUISICIÓN DE EQUIPOS MÉDICOS BOMBA DE INFUSIÓN P
                                                                                <!----><a _ngcontent-lkh-c14="">Ver más</a>
                                                                            </text-length>
                                                                        </td>
                                                                        <td _ngcontent-lkh-c31="">Bienes</td>
                                                                        <td _ngcontent-lkh-c31="">CM</td>
                                                                        <td _ngcontent-lkh-c31="">Por el Total</td>
                                                                        <td _ngcontent-lkh-c31="">24/02/2021 09:00</td>
                                                                    </tr>
                                                                    <!---->
                                                                    <!---->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div _ngcontent-lkh-c31="" class="col-lg-12 col-md-12 text-center">
                                                            <pagination _ngcontent-lkh-c31="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                                                <ul class="pagination pagination-sm justify-content-center">
                                                                    <!---->
                                                                    <li class="pagination-first page-item disabled"><a class="page-link" href="">Primero</a></li>
                                                                    <!---->
                                                                    <li class="pagination-prev page-item disabled"><a class="page-link" href="">Anterior</a></li>
                                                                    <!---->
                                                                    <li class="pagination-page page-item active"><a class="page-link" href="">1</a></li>
                                                                    <li class="pagination-page page-item"><a class="page-link" href="">2</a></li>
                                                                    <li class="pagination-page page-item"><a class="page-link" href="">3</a></li>
                                                                    <li class="pagination-page page-item"><a class="page-link" href="">4</a></li>
                                                                    <li class="pagination-page page-item"><a class="page-link" href="">5</a></li>
                                                                    <!---->
                                                                    <li class="pagination-next page-item"><a class="page-link" href="">Siguiente</a></li>
                                                                    <!---->
                                                                    <li class="pagination-last page-item"><a class="page-link" href="">Último</a></li>
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
                        <div _ngcontent-lkh-c30="" class="modal-footer"><button _ngcontent-lkh-c30="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </scpro-list-modal>
    </datos-generales-fragment>
    <prv-datos-proveedor-fragment _ngcontent-lkh-c23="" _nghost-lkh-c26="">
        <div _ngcontent-lkh-c26="" class="card b">
            <div _ngcontent-lkh-c26="" class="card-header d-flex align-items-center">
                <div _ngcontent-lkh-c26="" class="d-flex col p-0">
                    <h4 _ngcontent-lkh-c26="" class="card-title">Datos del Proveedor</h4>
                </div>
                <div _ngcontent-lkh-c26="" class="d-flex justify-content-end"><button _ngcontent-lkh-c26="" class="btn btn-link text-muted"><em _ngcontent-lkh-c26="" class="fa fa-minus text-muted"></em></button></div>
            </div>
            <div _ngcontent-lkh-c26="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                <div _ngcontent-lkh-c26="" class="row row-cols-4">
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Razón Social:</label></div>
                        <div _ngcontent-lkh-c26="" class="col">
                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                            <!---->
                        </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Documento:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> NIT - 2044323014 </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Rupe:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> 229523 </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Correo electrónico:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> info@nemabol.com </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Teléfono:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Fax:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Dirección:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> calle loayza Nro 250 piso 4 of 409 </div>
                    </div>
                    <div _ngcontent-lkh-c26="" class="col-12 col-md-6 col-lg-3 mt-2">
                        <div _ngcontent-lkh-c26="" class="col"><label _ngcontent-lkh-c26="" class="text-bold">Matrícula de Comercio:</label></div>
                        <div _ngcontent-lkh-c26="" class="col"> 344712 </div>
                    </div>
                    <!---->
                    <!---->
                </div>
                <!---->
            </div>
        </div>
    </prv-datos-proveedor-fragment>
    <!---->
    <botones-opciones-footer _ngcontent-lkh-c23="" _nghost-lkh-c27="">
        <div _ngcontent-lkh-c27="" class="row">
            <div _ngcontent-lkh-c27="" class="col-12 text-right">
                <!---->
                <!----><a onclick="alert('ERROR Para continuar debe seleccionar un proceso de contratación');" _ngcontent-lkh-c27="" class="fa-stack fa-lg cursor-pointer"><i _ngcontent-lkh-c27="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-lkh-c27="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
            </div>
        </div>
    </botones-opciones-footer>
</prv-docs-datos-generales-screen>