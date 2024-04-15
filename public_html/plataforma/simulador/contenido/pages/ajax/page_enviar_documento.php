<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$busc = get('busc');

?>
<style>.opciones[_ngcontent-fmr-c20]{position:fixed;right:0;z-index:1}.opciones[_ngcontent-fmr-c20]   .btn[_ngcontent-fmr-c20]{box-shadow:0 0 10px #666;border:0;margin-top:20px;padding:0}.opciones[_ngcontent-fmr-c20]   .btn-inverse[_ngcontent-fmr-c20]{background:#5d9cec;border-radius:10px 0 0 10px;margin-left:35px;padding:5px}.opciones[_ngcontent-fmr-c20]   .btn-inverse[_ngcontent-fmr-c20]:hover{background:#115f77}.opciones[_ngcontent-fmr-c20]   .btn-inverse[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20], .opciones[_ngcontent-fmr-c20]   .btn[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20]{font-size:23px}.opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]{box-shadow:0 1px 5px rgba(0,0,0,.2);background:#fff;border-radius:5px;margin-top:20px;padding:5px 0!important}.opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   div[_ngcontent-fmr-c20], .opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   span[_ngcontent-fmr-c20]{text-align:center}.opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   div[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20], .opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   span[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20]{font-size:18px;cursor:pointer;margin:3px 10px;padding:5px}.opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   div[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20]:hover, .opciones[_ngcontent-fmr-c20]   .affix[_ngcontent-fmr-c20]   span[_ngcontent-fmr-c20]   i[_ngcontent-fmr-c20]:hover{background:#115f77;border-radius:3px;color:#fff;padding:5px}.popover[_ngcontent-fmr-c20]{max-width:100%}.cursor-pointer[_ngcontent-fmr-c20]{cursor:pointer}</style>

<router-outlet _ngcontent-fmr-c1=""></router-outlet>
<prv-propuestas-screen _nghost-fmr-c19="" class="ng-star-inserted">
    <botones-opciones _ngcontent-fmr-c19="" _nghost-fmr-c20="">
        <div _ngcontent-fmr-c20="" class="opciones affix">
            <!---->
            <!---->
            <div _ngcontent-fmr-c20="" class="btn btn-inverse ng-star-inserted" placement="left" tooltip="Ocultar" aria-describedby="tooltip-18"><i _ngcontent-fmr-c20="" class="fa fa-chevron-circle-right"></i></div>
            <!---->
            <div _ngcontent-fmr-c20="" class="affix">
                <!---->
                <div _ngcontent-fmr-c20="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-32"><span _ngcontent-fmr-c20="" class="cursor-pointer"><i _ngcontent-fmr-c20="" class="fa fa-window-close fas text-primary"></i> Anular Verificación</span></div>
                <!---->
                <div onclick="action_page_enviar_documento();" _ngcontent-fmr-c20="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-33"><span _ngcontent-fmr-c20="" class="cursor-pointer"><i _ngcontent-fmr-c20="" class="fa fa-check-circle text-primary"></i> Enviar</span></div>
                <!---->
            </div>
        </div>
    </botones-opciones>
    <div _ngcontent-fmr-c19="" class="content-heading p5 mb-0">
        <div _ngcontent-fmr-c19="" class="row w-100">
            <div _ngcontent-fmr-c19="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-fmr-c19="" class="col-lg-5 col-12 pt10"> Mis Propuestas Electrónicas </div>
            <div _ngcontent-fmr-c19="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-fmr-c19="" _nghost-fmr-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-fmr-c19="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-fmr-c19="" _nghost-fmr-c14="">
                    <div _ngcontent-fmr-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-fmr-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-fmr-c14="" class="text-center">
                                <div _ngcontent-fmr-c14="" class="text-sm">Marzo</div>
                                <div _ngcontent-fmr-c14="" class="h4 mt-0">04</div>
                            </div>
                        </div>
                        <div _ngcontent-fmr-c14="" class="col-8 rounded-right"><span _ngcontent-fmr-c14="" class="text-uppercase h5 m0">Jueves</span><br _ngcontent-fmr-c14="">
                            <div _ngcontent-fmr-c14="" class="h4 mt-0">17:00:16</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-fmr-c19="" class="row">
        <div _ngcontent-fmr-c19="" class="col-lg-6 offset-lg-3">
            <wizard _ngcontent-fmr-c19="" class="w-100" _nghost-fmr-c21="">
                <div _ngcontent-fmr-c21="" class="d-flex justify-content-around bd-highlight mb-0">
                    <!---->
                    <div _ngcontent-fmr-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-fmr-c21="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 1" aria-describedby="tooltip-21"><i _ngcontent-fmr-c21="" class="fas fa-file-alt"></i></button><br _ngcontent-fmr-c21=""><span _ngcontent-fmr-c21="" class="sp-title-active">Datos Generales</span></div>
                    <div _ngcontent-fmr-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-fmr-c21="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 2" aria-describedby="tooltip-22"><i _ngcontent-fmr-c21="" class="fas fa-upload"></i></button><br _ngcontent-fmr-c21=""><span _ngcontent-fmr-c21="" class="sp-title-pass">Documentos Adjuntos</span></div>
                    <div _ngcontent-fmr-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-fmr-c21="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 3" aria-describedby="tooltip-23"><i _ngcontent-fmr-c21="" class="fas fa-money-bill"></i></button><br _ngcontent-fmr-c21=""><span _ngcontent-fmr-c21="" class="sp-title-pass">Registro de Precios</span></div>
                    <div _ngcontent-fmr-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-fmr-c21="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 4" aria-describedby="tooltip-24"><i style="font-family: cursive;font-size: 11pt;">M</i></button><br _ngcontent-fmr-c21=""><span _ngcontent-fmr-c21="" class="sp-title-pass">Márgenes de Preferencia</span></div>
                    <div _ngcontent-fmr-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-fmr-c21="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 5" aria-describedby="tooltip-25"><i _ngcontent-fmr-c21="" class="fa fa-calendar"></i></button><br _ngcontent-fmr-c21=""><span _ngcontent-fmr-c21="" class="sp-title-pass">Plazo de entrega</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-fmr-c19="" class="row">
        <div _ngcontent-fmr-c19="" class="col-lg-12">
            <mensaje-documento-fragment _ngcontent-fmr-c19="" _nghost-fmr-c22="">
                <!---->
            </mensaje-documento-fragment>
        </div>
    </div>
    <div _ngcontent-fmr-c19="" class="row">
        <!---->
        <div _ngcontent-fmr-c19="" class="col-lg-12">
            <router-outlet _ngcontent-fmr-c19=""></router-outlet>
            <prv-docs-datos-generales-screen _nghost-fmr-c27="" class="ng-star-inserted">
                <!---->
                <prv-datos-doc-propuesta-fragment _ngcontent-fmr-c27="" _nghost-fmr-c28="">
                    <div _ngcontent-fmr-c28="" class="card b">
                        <div _ngcontent-fmr-c28="" class="card-header bb">
                            <h4 _ngcontent-fmr-c28="" class="card-title">Datos del Documento</h4>
                        </div>
                        <div _ngcontent-fmr-c28="" class="card-body bt">
                            <div _ngcontent-fmr-c28="" class="row">
                                <div _ngcontent-fmr-c28="" class="col">
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-fmr-c28="" class="mt">Tipo de Operación:</label></div>
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                        <!---->
                                        <!----><input _ngcontent-fmr-c28="" class="form-control input-sm ng-star-inserted" disabled="true" type="text" value="Presentación de Propuesta/Oferta">
                                        <!---->
                                    </div>
                                </div>
                                <div _ngcontent-fmr-c28="" class="col">
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-fmr-c28="" class="mt">Nro. Documento:</label></div>
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-fmr-c28="" class="form-control input-sm" disabled="true" type="text" value="22729.0"></div>
                                </div>
                                <div _ngcontent-fmr-c28="" class="col">
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-fmr-c28="" class="mt">Estado:</label></div>
                                    <div _ngcontent-fmr-c28="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-fmr-c28="" class="form-control input-sm" disabled="true" type="text" value="VERIFICADO"></div>
                                </div>
                                <!---->
                            </div>
                        </div>
                    </div>
                    <prv-hab-retiro-modal _ngcontent-fmr-c28="" id="modalRetiro" _nghost-fmr-c31="">
                        <div _ngcontent-fmr-c31="" bsmodal="" class="modal fade">
                            <div _ngcontent-fmr-c31="" class="modal-dialog modal-xl">
                                <div _ngcontent-fmr-c31="" class="modal-content">
                                    <div _ngcontent-fmr-c31="" class="modal-header text-center">
                                        <h4 _ngcontent-fmr-c31="" class="text-color-blanco w-100"> Propuesta Electrónica a retirar </h4><button _ngcontent-fmr-c31="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-fmr-c31="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-fmr-c31="" class="modal-body">
                                        <div _ngcontent-fmr-c31="" class="row">
                                            <div _ngcontent-fmr-c31="" class="col-lg-6 col-md-6 col-sm-6">
                                                <div _ngcontent-fmr-c31="" class="card-title"></div>
                                            </div>
                                            <div _ngcontent-fmr-c31="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div _ngcontent-fmr-c31="" class="row">
                                                    <div _ngcontent-fmr-c31="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                        <form _ngcontent-fmr-c31="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                            <div _ngcontent-fmr-c31="" class="input-group"><input _ngcontent-fmr-c31="" class="form-control ng-untouched ng-pristine ng-valid" name="descDocumentoBusqueda" placeholder="" type="text"><span _ngcontent-fmr-c31="" class="input-group-btn"><button _ngcontent-fmr-c31="" class="btn btn-primary" type="submit"><span _ngcontent-fmr-c31="" class="fa fa-search"></span></button></span></div>
                                                        </form><br _ngcontent-fmr-c31="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-fmr-c31="" class="row">
                                            <div _ngcontent-fmr-c31="" class="col-lg-12 col-md-12">
                                                <div _ngcontent-fmr-c31="" class="table-responsive">
                                                    <table _ngcontent-fmr-c31="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                                        <thead _ngcontent-fmr-c31="">
                                                            <tr _ngcontent-fmr-c31="">
                                                                <th _ngcontent-fmr-c31=""></th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Nro. Documento</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Tipo de Operación</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">CUCE</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Objeto de Contratación</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Modalidad</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Fecha de Aprobación</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Fecha de Presentación</th>
                                                                <th _ngcontent-fmr-c31="" class="tex-center">Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody _ngcontent-fmr-c31="">
                                                            <!---->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-fmr-c31="" class="col-lg-12 col-md-12 text-center"></div>
                                    </div>
                                    <div _ngcontent-fmr-c31="" class="modal-footer"><button _ngcontent-fmr-c31="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                                </div>
                            </div>
                        </div>
                    </prv-hab-retiro-modal>
                </prv-datos-doc-propuesta-fragment>
                <datos-generales-fragment _ngcontent-fmr-c27="" _nghost-fmr-c29="">
                    <div _ngcontent-fmr-c29="" class="card b">
                        <div _ngcontent-fmr-c29="" class="card-header d-flex align-items-center">
                            <div _ngcontent-fmr-c29="" class="d-flex col p-0">
                                <h4 _ngcontent-fmr-c29="" class="card-title">Datos del Proceso</h4>
                            </div>
                            <div _ngcontent-fmr-c29="" class="d-flex justify-content-end">
                                <!---->
                                <!---->
                                <div _ngcontent-fmr-c29="" class="btn-group ng-star-inserted" dropdown=""><button _ngcontent-fmr-c29="" class="btn btn-link" dropdowntoggle="" aria-haspopup="true"><em _ngcontent-fmr-c29="" class="fa fa-ellipsis-v fa-lg text-muted"></em></button>
                                    <!---->
                                </div>
                                <!---->
                            </div>
                        </div>
                        <div _ngcontent-fmr-c29="" class="card-body bt padding-top-0">
                            <div _ngcontent-fmr-c29="" class="row">
                                <!---->
                            </div>
                            <div _ngcontent-fmr-c29="" class="row row-cols-2 mt-2">
                                <div _ngcontent-fmr-c29="" class="col-12 col-lg-4">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">CUCE:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> 21-0513-00-1114217-1-1 </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-lg-8">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Objeto de Contratación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL </div>
                                </div>
                            </div>
                            <div _ngcontent-fmr-c29="" class="row row-cols-3">
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Entidad:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> Yacimientos Petrolíferos Fiscales Bolivianos </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Fecha de Publicación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> 22/02/2021 16:40 </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Normativa:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> NB-SABS (D.S.0181) </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Modalidad:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> LP </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Tipo de Contratación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> Bienes </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Forma de Adjudicación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> Por Items </div>
                                </div>
                                <!---->
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Método de Selección y Adjudicación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> Precio evaluado más bajo </div>
                                </div>
                                <!---->
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Tipo de Convocatoria:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> Convocatoria Pública Nacional </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Moneda:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> BOLIVIANOS </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Tipo de Cambio:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> 1 </div>
                                </div>
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Fecha de Presentación:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> 17/03/2021 09:00 </div>
                                </div>
                                <!---->
                                <!---->
                                <div _ngcontent-fmr-c29="" class="col-12 col-md-6 col-lg-4 mt-2 ng-star-inserted">
                                    <div _ngcontent-fmr-c29="" class="col"><b _ngcontent-fmr-c29="" class="text-bold">Fecha de Inicio de Subasta:</b></div>
                                    <div _ngcontent-fmr-c29="" class="col"> 17/03/2021 09:30 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <datos-cronograma-modal _ngcontent-fmr-c29="" id="idScPCronDModal" _nghost-fmr-c32="">
                        <div _ngcontent-fmr-c32="" bsmodal="" class="modal fade">
                            <div _ngcontent-fmr-c32="" class="modal-dialog modal-lg">
                                <div _ngcontent-fmr-c32="" class="modal-content">
                                    <div _ngcontent-fmr-c32="" class="modal-header text-center">
                                        <h4 _ngcontent-fmr-c32="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-fmr-c32="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-fmr-c32="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-fmr-c32="" class="modal-body">
                                        <div _ngcontent-fmr-c32="" class="row">
                                            <div _ngcontent-fmr-c32="" class="col-lg-12 col-md-12">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div _ngcontent-fmr-c32="" class="modal-footer"><button _ngcontent-fmr-c32="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                                </div>
                            </div>
                        </div>
                    </datos-cronograma-modal>
                    <scpro-list-modal _ngcontent-fmr-c29="" id="idScproListModal" _nghost-fmr-c33="">
                        <div _ngcontent-fmr-c33="" bsmodal="" class="modal fade">
                            <div _ngcontent-fmr-c33="" class="modal-dialog modal-xl">
                                <div _ngcontent-fmr-c33="" class="modal-content">
                                    <div _ngcontent-fmr-c33="" class="modal-header text-center">
                                        <h4 _ngcontent-fmr-c33="" class="text-color-blanco w-100"> Seleccionar Proceso de Contratación </h4><button _ngcontent-fmr-c33="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-fmr-c33="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-fmr-c33="" class="modal-body">
                                        <scpro-list _ngcontent-fmr-c33="" _nghost-fmr-c34="">
                                            <!---->
                                            <div _ngcontent-fmr-c34="" class="row">
                                                <div _ngcontent-fmr-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                    <div _ngcontent-fmr-c34="" class="card card-default">
                                                        <div _ngcontent-fmr-c34="" class="card-header">
                                                            <div _ngcontent-fmr-c34="" class="row">
                                                                <div _ngcontent-fmr-c34="" class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div _ngcontent-fmr-c34="" class="card-title"></div>
                                                                </div>
                                                                <div _ngcontent-fmr-c34="" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <div _ngcontent-fmr-c34="" class="row">
                                                                        <div _ngcontent-fmr-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                            <form _ngcontent-fmr-c34="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                                                <div _ngcontent-fmr-c34="" class="input-group"><input _ngcontent-fmr-c34="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por CUCE y Objeto de Contratación" type="text"><span _ngcontent-fmr-c34="" class="input-group-btn"><button _ngcontent-fmr-c34="" class="btn btn-primary" type="submit"><span _ngcontent-fmr-c34="" class="fa fa-search"></span></button></span></div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div _ngcontent-fmr-c34="" class="row">
                                                                        <div _ngcontent-fmr-c34="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                                            <button-filter _ngcontent-fmr-c34="" _nghost-fmr-c15="">
                                                                                <div _ngcontent-fmr-c15="" class="btn-group" dropdown=""><button _ngcontent-fmr-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-fmr-c15=""></b></button>
                                                                                    <!---->
                                                                                </div>
                                                                            </button-filter>
                                                                            <button-filter _ngcontent-fmr-c34="" _nghost-fmr-c15="">
                                                                                <div _ngcontent-fmr-c15="" class="btn-group" dropdown=""><button _ngcontent-fmr-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Subasta<b _ngcontent-fmr-c15="">: Todos</b></button>
                                                                                    <!---->
                                                                                </div>
                                                                            </button-filter>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div _ngcontent-fmr-c34="" class="card-body">
                                                            <div _ngcontent-fmr-c34="" class="row">
                                                                <div _ngcontent-fmr-c34="" class="col-lg-12 col-md-12">
                                                                    <div _ngcontent-fmr-c34="" class="table-responsive">
                                                                        <table _ngcontent-fmr-c34="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                                                            <thead _ngcontent-fmr-c34="">
                                                                                <tr _ngcontent-fmr-c34="">
                                                                                    <!---->
                                                                                    <!---->
                                                                                    <th _ngcontent-fmr-c34="" class="text-center ng-star-inserted"></th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">CUCE</th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">Objeto de Contratación</th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">Tipo Contratación</th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">Modalidad</th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">Forma de Adjudicación</th>
                                                                                    <th _ngcontent-fmr-c34="" class="text-center">Fecha Límite de Presentación de Propuestas</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody _ngcontent-fmr-c34="">
                                                                                <!---->
                                                                                <!---->
                                                                                <!---->
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div _ngcontent-fmr-c34="" class="col-lg-12 col-md-12 text-center">
                                                                        <pagination _ngcontent-fmr-c34="" class="pagination-sm justify-content-center ng-untouched ng-pristine ng-valid">
                                                                            <ul class="pagination pagination-sm justify-content-center">
                                                                                <!---->
                                                                                <li class="pagination-first page-item disabled ng-star-inserted"><a class="page-link" href="">Primero</a></li>
                                                                                <!---->
                                                                                <li class="pagination-prev page-item disabled ng-star-inserted"><a class="page-link" href="">Anterior</a></li>
                                                                                <!---->
                                                                                <li class="pagination-page page-item active ng-star-inserted"><a class="page-link" href="">1</a></li>
                                                                                <!---->
                                                                                <li class="pagination-next page-item disabled ng-star-inserted"><a class="page-link" href="">Siguiente</a></li>
                                                                                <!---->
                                                                                <li class="pagination-last page-item disabled ng-star-inserted"><a class="page-link" href="">Último</a></li>
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
                                    <div _ngcontent-fmr-c33="" class="modal-footer"><button _ngcontent-fmr-c33="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                                </div>
                            </div>
                        </div>
                    </scpro-list-modal>
                </datos-generales-fragment>
                <prv-datos-proveedor-fragment _ngcontent-fmr-c27="" _nghost-fmr-c30="">
                    <div _ngcontent-fmr-c30="" class="card b">
                        <div _ngcontent-fmr-c30="" class="card-header d-flex align-items-center">
                            <div _ngcontent-fmr-c30="" class="d-flex col p-0">
                                <h4 _ngcontent-fmr-c30="" class="card-title">Datos del Proveedor</h4>
                            </div>
                            <div _ngcontent-fmr-c30="" class="d-flex justify-content-end"><button _ngcontent-fmr-c30="" class="btn btn-link text-muted"><em _ngcontent-fmr-c30="" class="fa fa-minus text-muted"></em></button></div>
                        </div>
                        <div _ngcontent-fmr-c30="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                            <div _ngcontent-fmr-c30="" class="row row-cols-4">
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Razón Social:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col">
                                        <!----> ALIAGA QUENTA RODOLFO REYNALDO
                                        <!---->
                                    </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Documento:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> NIT - 2044323014 </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Rupe:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> 229523 </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Correo electrónico:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> info@nemabol.com </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Teléfono:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Fax:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Dirección:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> calle loayza Nro 250 piso 4 of 409 </div>
                                </div>
                                <div _ngcontent-fmr-c30="" class="col-12 col-md-6 col-lg-3 mt-2">
                                    <div _ngcontent-fmr-c30="" class="col"><label _ngcontent-fmr-c30="" class="text-bold">Matrícula de Comercio:</label></div>
                                    <div _ngcontent-fmr-c30="" class="col"> 344712 </div>
                                </div>
                                <!---->
                                <!---->
                            </div>
                            <!---->
                        </div>
                    </div>
                </prv-datos-proveedor-fragment>
                <!---->
            </prv-docs-datos-generales-screen>
            <div _ngcontent-fmr-c19="" class="h100"></div>
            <botones-opciones-footer _ngcontent-fmr-c19="" _nghost-fmr-c24="">
                <div _ngcontent-fmr-c24="" class="row">
                    <div _ngcontent-fmr-c24="" class="col-12 text-right">
                        <!---->
                        <!----><a _ngcontent-fmr-c24="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-fmr-c24="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-fmr-c24="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
                    </div>
                </div>
            </botones-opciones-footer>
        </div>
    </div>
    <propuesta-aprobar-modal _ngcontent-fmr-c19="" id="idModalPropuestaAprobar" _nghost-fmr-c25="">
        <div _ngcontent-fmr-c25="" bsmodal="" class="modal fade">
            <div _ngcontent-fmr-c25="" class="modal-dialog modal-lg">
                <div _ngcontent-fmr-c25="" class="modal-content">
                    <div _ngcontent-fmr-c25="" class="modal-header text-center">
                        <h4 _ngcontent-fmr-c25="" class="text-color-blanco w-100">
                            <!---->
                            <!----> POLÍTICAS Y CONDICIONES DE USO
                            <!---->
                        </h4><button _ngcontent-fmr-c25="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-fmr-c25="" aria-hidden="true">×</span></button>
                    </div>
                    <div _ngcontent-fmr-c25="" class="modal-body">
                        <p _ngcontent-fmr-c25="" class="text-justify">
                            <!---->
                            <!---->
                        <h4 _ngcontent-fmr-c25="" class="ng-star-inserted">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-fmr-c25="" class="ng-star-inserted"> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema.
                        <!---->
                        </p>
                        <div _ngcontent-fmr-c25="" class="border-top"><br _ngcontent-fmr-c25="">
                            <!---->
                            <div _ngcontent-fmr-c25="" class="ng-star-inserted"><span _ngcontent-fmr-c25="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-fmr-c25=""><input _ngcontent-fmr-c25="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-fmr-c25="" class="fa fa-check"></span><b _ngcontent-fmr-c25="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div>
                        </div>
                    </div>
                    <div _ngcontent-fmr-c25="" class="modal-footer"><button _ngcontent-fmr-c25="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button _ngcontent-fmr-c25="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                </div>
            </div>
        </div>
    </propuesta-aprobar-modal>
    <confirmacion-modal _ngcontent-fmr-c19="" id="idModalConfirmacion" _nghost-fmr-c26="">
        <div _ngcontent-fmr-c26="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
            <div _ngcontent-fmr-c26="" class="modal-dialog modal-sm">
                <div _ngcontent-fmr-c26="" class="modal-content">
                    <div _ngcontent-fmr-c26="" class="modal-body">
                        <div _ngcontent-fmr-c26="" class="row">
                            <div _ngcontent-fmr-c26="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                            <div _ngcontent-fmr-c26="" class="col-lg-12 col-md-12 text-center"> </div>
                        </div>
                    </div>
                    <div _ngcontent-fmr-c26="" class="modal-footer"><button _ngcontent-fmr-c26="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-fmr-c26="" type="button" class="btn btn-">Aceptar</button></div>
                </div>
            </div>
        </div>
    </confirmacion-modal>
</prv-propuestas-screen>