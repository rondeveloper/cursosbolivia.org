<?php

//echo "123test";
?>


<router-outlet _ngcontent-jbq-c1=""></router-outlet>
<procesos-subasta-list-screen _nghost-jbq-c12="">
    <div _ngcontent-jbq-c12="" class="content-heading">Procesos de Contratación
    </div>
    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div _ngcontent-jbq-c12="" class="row">
                        <div _ngcontent-jbq-c12="" class="col-lg-6 col-md-6 col-sm-6">
                            <div _ngcontent-jbq-c12="" class="card-title"></div>
                        </div>
                        <div _ngcontent-jbq-c12="" class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div _ngcontent-jbq-c12="" class="row">
                                <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                    <div class="ng-valid ng-dirty ng-touched">
                                        <div _ngcontent-jbq-c12="" class="input-group"><input _ngcontent-jbq-c12="" id="input-busc" class="form-control ng-valid ng-dirty ng-touched" name="descripcionBusqueda" placeholder="Buscar por CUCE, Entidad y Objeto de Contratación" type="text"><span _ngcontent-jbq-c12="" class="input-group-btn"><button _ngcontent-jbq-c12="" class="btn btn-primary" type="submit" onclick="busqueda();"><span _ngcontent-jbq-c12="" class="fa fa-search"></span></button></span></div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-jbq-c12="" class="row">
                                <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                    <button-filter _ngcontent-jbq-c12="" _nghost-jbq-c13="">
                                        <div _ngcontent-jbq-c13="" class="btn-group" dropdown=""><button _ngcontent-jbq-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true" aria-expanded="false"> Modalidad<b _ngcontent-jbq-c13="">: Todas</b></button>
                                            <!---->
                                            <ul _ngcontent-jbq-c13="" class="dropdown-menu" role="menu" style="left: 0px; right: auto;">
                                                <!----><a _ngcontent-jbq-c13="" class="dropdown-item">Contratación Menor</a><a _ngcontent-jbq-c13="" class="dropdown-item">ANPE</a><a _ngcontent-jbq-c13="" class="dropdown-item">ANPP</a><a _ngcontent-jbq-c13="" class="dropdown-item">LP</a><a _ngcontent-jbq-c13="" class="dropdown-item">Excepción</a><a _ngcontent-jbq-c13="" class="dropdown-item">Emergencia</a><a _ngcontent-jbq-c13="" class="dropdown-item">Contratación Directa</a><a _ngcontent-jbq-c13="" class="dropdown-item">Otras</a><a _ngcontent-jbq-c13="" class="dropdown-item">Todas</a>
                                            </ul>
                                        </div>
                                    </button-filter>
                                    <button-filter _ngcontent-jbq-c12="" _nghost-jbq-c13="">
                                        <div _ngcontent-jbq-c13="" class="btn-group" dropdown=""><button _ngcontent-jbq-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true" aria-expanded="false"> Estado Subasta<b _ngcontent-jbq-c13="">: Todos</b></button>
                                            <!---->
                                            <ul _ngcontent-jbq-c13="" class="dropdown-menu" role="menu" style="left: 0px; right: auto;">
                                                <!----><a _ngcontent-jbq-c13="" class="dropdown-item">En Curso</a><a _ngcontent-jbq-c13="" class="dropdown-item">Finalizada</a><a _ngcontent-jbq-c13="" class="dropdown-item">Todos</a>
                                            </ul>
                                        </div>
                                    </button-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body">
                    <div _ngcontent-jbq-c12="" class="row">
                        <div _ngcontent-jbq-c12="" class="col-lg-12 col-md-12">
                            <div _ngcontent-jbq-c12="" class="table-responsive">
                                <table _ngcontent-jbq-c12="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                    <thead _ngcontent-jbq-c12="">
                                        <tr _ngcontent-jbq-c12="">
                                            <th _ngcontent-jbq-c12="" class="w-cog">Opciones</th>
                                            <th _ngcontent-jbq-c12="">CUCE</th>
                                            <th _ngcontent-jbq-c12="">Entidad</th>
                                            <th _ngcontent-jbq-c12="">Objeto de Contratación</th>
                                            <th _ngcontent-jbq-c12="">Modalidad</th>
                                            <th _ngcontent-jbq-c12="">Fecha Inicio Subasta</th>
                                            <th _ngcontent-jbq-c12="">Fecha Cierre Preliminar</th>
                                            <th _ngcontent-jbq-c12="">Estado Subasta</th>
                                        </tr>
                                    </thead>
                                    <tbody _ngcontent-jbq-c12="">
                                        <!---->
                                        <!---->
                                        <tr _ngcontent-jbq-c12="">
                                            <td _ngcontent-lkh-c21="" class="text-center">
                                                <div _ngcontent-lkh-c21="" class="btn-group open show" dropdown="">
                                                    <button onclick="dropdown_prnd_item(11);" _ngcontent-lkh-c21="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" id="button-autoclose1" type="button" aria-haspopup="true" aria-expanded="true"><span _ngcontent-lkh-c21="" class="fa fa-cog text-primary"></span></button>
                                                    <!---->
                                                    <ul _ngcontent-lkh-c21="" aria-labelledby="button-autoclose1" class="dropdown-menu show" id="id-dropdown_prnd_item-11" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);display:none;">
                                                        <a onclick="page_join_subasta('mod1');" style="cursor: pointer;color: #000;" _ngcontent-lkh-c21="" class="dropdown-item "><i class="fas fa-gavel"></i> &nbsp; Sala de Subasta </a>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td _ngcontent-jbq-c12="">21-0417-06-1121995-2-1</td>
                                            <td _ngcontent-jbq-c12="">Caja Nacional De Salud (Regional Chuquisaca)</td>
                                            <td _ngcontent-jbq-c12="">ADQUISICION DE 2.000 PIEZAS DE TRAJES DE BIOSEGURIDAD SOLICITADO POR LA JEFATURA REGIONAL DE ENFERMERIA</td>
                                            <td _ngcontent-jbq-c12="">ANPE</td>
                                            <td _ngcontent-jbq-c12=""><?php echo date("d/m/Y H:i:s"); ?></td>
                                            <td _ngcontent-jbq-c12=""><?php echo date("d/m/Y H:i:s",strtotime('+5 minute',time())); ?></td>
                                            <td _ngcontent-jbq-c12="">En curso</td>
                                        </tr>
                                        <!---->
                                        <tr _ngcontent-jbq-c12="">
                                            <td _ngcontent-lkh-c21="" class="text-center">
                                                <div _ngcontent-lkh-c21="" class="btn-group open show" dropdown="">
                                                    <button onclick="dropdown_prnd_item(22);" _ngcontent-lkh-c21="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" id="button-autoclose1" type="button" aria-haspopup="true" aria-expanded="true"><span _ngcontent-lkh-c21="" class="fa fa-cog text-primary"></span></button>
                                                    <!---->
                                                    <ul _ngcontent-lkh-c21="" aria-labelledby="button-autoclose1" class="dropdown-menu show" id="id-dropdown_prnd_item-22" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);display:none;">
                                                        <a onclick="page_join_subasta('mod2');" style="cursor: pointer;color: #000;" _ngcontent-lkh-c21="" class="dropdown-item "><i class="fas fa-gavel"></i> &nbsp; Sala de Subasta </a>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td _ngcontent-jbq-c12="">21-0293-00-1120509-1-1</td>
                                            <td _ngcontent-jbq-c12="">Fundacion Cultural del Banco Central de Bolivia</td>
                                            <td _ngcontent-jbq-c12="">ADQUISICION INSUMOS DE BIOSEGURIDAD</td>
                                            <td _ngcontent-jbq-c12="">LP</td>
                                            <td _ngcontent-jbq-c12=""><?php echo date("d/m/Y H:i:s"); ?></td>
                                            <td _ngcontent-jbq-c12=""><?php echo date("d/m/Y H:i:s",strtotime('+5 minute',time())); ?></td>
                                            <td _ngcontent-jbq-c12="">En curso</td>
                                        </tr>
                                        <tr _ngcontent-jbq-c12="" style="height:80px;"></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div _ngcontent-jbq-c12="" class="col-lg-12 col-md-12 text-center">
                                <pagination _ngcontent-jbq-c12="" class="pagination-sm justify-content-center ng-untouched ng-pristine ng-valid">
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
</procesos-subasta-list-screen>