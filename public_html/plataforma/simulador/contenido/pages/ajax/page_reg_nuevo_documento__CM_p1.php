<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);



$precio_unitario_ofertado = str_replace(',','',get('precio_unitario_ofertado'));
$valor_ofertado = str_replace(',','',get('valor_ofertado'));

$id_usuario = usuario('id_sim');
$cuce = '21-1712-00-1116922-1-1';

$rqr1 = query("SELECT * FROM simulador_documentos WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
$precio_unitario_ofertado = 0;
$valor_ofertado = 0;
if(num_rows($rqr1)>0){
    $rqr2 = fetch($rqr1);  
    $precio_unitario_ofertado = $rqr2['precio_unitario_ofertado'];
    $valor_ofertado = $rqr2['valor_ofertado'];  
}


?>
<style>
    .opciones[_ngcontent-crt-c20] {
        position: fixed;
        right: 0;
        z-index: 1
    }

    .opciones[_ngcontent-crt-c20] .btn[_ngcontent-crt-c20] {
        box-shadow: 0 0 10px #666;
        border: 0;
        margin-top: 20px;
        padding: 0
    }

    .opciones[_ngcontent-crt-c20] .btn-inverse[_ngcontent-crt-c20] {
        background: #5d9cec;
        border-radius: 10px 0 0 10px;
        margin-left: 35px;
        padding: 5px
    }

    .opciones[_ngcontent-crt-c20] .btn-inverse[_ngcontent-crt-c20]:hover {
        background: #115f77
    }

    .opciones[_ngcontent-crt-c20] .btn-inverse[_ngcontent-crt-c20] i[_ngcontent-crt-c20],
    .opciones[_ngcontent-crt-c20] .btn[_ngcontent-crt-c20] i[_ngcontent-crt-c20] {
        font-size: 23px
    }

    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] {
        box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
        background: #fff;
        border-radius: 5px;
        margin-top: 20px;
        padding: 5px 0 !important
    }

    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] div[_ngcontent-crt-c20],
    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] span[_ngcontent-crt-c20] {
        text-align: center
    }

    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] div[_ngcontent-crt-c20] i[_ngcontent-crt-c20],
    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] span[_ngcontent-crt-c20] i[_ngcontent-crt-c20] {
        font-size: 18px;
        cursor: pointer;
        margin: 3px 10px;
        padding: 5px
    }

    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] div[_ngcontent-crt-c20] i[_ngcontent-crt-c20]:hover,
    .opciones[_ngcontent-crt-c20] .affix[_ngcontent-crt-c20] span[_ngcontent-crt-c20] i[_ngcontent-crt-c20]:hover {
        background: #115f77;
        border-radius: 3px;
        color: #fff;
        padding: 5px
    }

    .popover[_ngcontent-crt-c20] {
        max-width: 100%
    }

    .cursor-pointer[_ngcontent-crt-c20] {
        cursor: pointer
    }
</style>

<div _ngcontent-crt-c1="" class="content-wrapper">
    <router-outlet _ngcontent-crt-c1=""></router-outlet>
    <prv-propuestas-screen _nghost-crt-c19="" class="ng-star-inserted">
        <botones-opciones _ngcontent-crt-c19="" _nghost-crt-c20="">
            <div _ngcontent-crt-c20="" class="opciones affix">
                <!---->
                <!---->
                <div _ngcontent-crt-c20="" class="btn btn-inverse ng-star-inserted" placement="left" tooltip="Ocultar" aria-describedby="tooltip-69"><i _ngcontent-crt-c20="" class="fa fa-chevron-circle-right"></i></div>
                <!---->
                <div _ngcontent-crt-c20="" class="affix">
                    <!---->
                    <div onclick="prnd_CM_verificar_doc();" _ngcontent-crt-c20="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-70"><span _ngcontent-crt-c20="" class="cursor-pointer"><i _ngcontent-crt-c20="" class="fa fa-check-circle text-primary"></i> Verificar</span></div>
                    <!---->
                    <div onclick="prnd_CM_elimnar_doc();" _ngcontent-crt-c20="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-71"><span _ngcontent-crt-c20="" class="cursor-pointer"><i _ngcontent-crt-c20="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
                    <!---->
                </div>
            </div>
        </botones-opciones>
        <div _ngcontent-crt-c19="" class="content-heading p5 mb-0">
            <div _ngcontent-crt-c19="" class="row w-100">
                <div _ngcontent-crt-c19="" class="row pt-5 col-12 d-md-none"></div>
                <div _ngcontent-crt-c19="" class="col-lg-5 col-12 pt10"> Mis Propuestas Electrónicas </div>
                <div _ngcontent-crt-c19="" class="col-lg-4 col-12 pt10 h30">
                    <spinner-http _ngcontent-crt-c19="" _nghost-crt-c13="">
                        <!---->
                    </spinner-http>
                </div>
                <div _ngcontent-crt-c19="" class="col-lg-3 col-12">
                    <reloj-fragment _ngcontent-crt-c19="" _nghost-crt-c14="">
                        <div _ngcontent-crt-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                            <div _ngcontent-crt-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                                <div _ngcontent-crt-c14="" class="text-center">
                                    <div _ngcontent-crt-c14="" class="text-sm">Marzo</div>
                                    <div _ngcontent-crt-c14="" class="h4 mt-0">03</div>
                                </div>
                            </div>
                            <div _ngcontent-crt-c14="" class="col-8 rounded-right"><span _ngcontent-crt-c14="" class="text-uppercase h5 m0">Miércoles</span><br _ngcontent-crt-c14="">
                                <div _ngcontent-crt-c14="" class="h4 mt-0">12:28:15</div>
                            </div>
                        </div>
                    </reloj-fragment>
                </div>
            </div>
        </div>
        <div _ngcontent-crt-c19="" class="row">
            <div _ngcontent-crt-c19="" class="col-lg-6 offset-lg-3">
                <wizard _ngcontent-crt-c19="" class="w-100" _nghost-crt-c21="">
                    <div _ngcontent-crt-c21="" class="d-flex justify-content-around bd-highlight mb-0">
                        <!---->
                        <div onclick="pnd_press_select_proceso('CM');" _ngcontent-crt-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-crt-c21="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 1" aria-describedby="tooltip-72"><i _ngcontent-crt-c21="" class="fas fa-file-alt"></i></button><br _ngcontent-crt-c21=""><span _ngcontent-crt-c21="" class="sp-title-pass">Datos Generales</span></div>
                        <div _ngcontent-crt-c21="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-crt-c21="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 3" aria-describedby="tooltip-73"><i _ngcontent-crt-c21="" class="fas fa-money-bill"></i></button><br _ngcontent-crt-c21=""><span _ngcontent-crt-c21="" class="sp-title-active">Registro de Precios</span></div>
                    </div>
                </wizard>
            </div>
        </div>
        <div _ngcontent-crt-c19="" class="row">
            <div _ngcontent-crt-c19="" class="col-lg-12">
                <mensaje-documento-fragment _ngcontent-crt-c19="" _nghost-crt-c22="">
                    <!---->
                    <!---->
                    <alert _ngcontent-crt-c22="" type="dark" class="ng-star-inserted">
                        <!---->
                        <div role="alert" class="alert alert-dark ng-star-inserted">
                            <!----><em _ngcontent-crt-c22="" class="fa fa-exclamation-circle fa-lg fa-fw"></em>
                            <!---->
                            <!----><span _ngcontent-crt-c22="" class="ng-star-inserted"><b _ngcontent-crt-c22="">Los precios registrados son encriptados y podrán ser desencriptados por la entidad en la fecha y hora de apertura de propuestas</b></span>
                        </div>
                    </alert>
                </mensaje-documento-fragment>
            </div>
        </div>
        <div _ngcontent-crt-c19="" class="row">
            <!---->
            <div _ngcontent-crt-c19="" class="col-lg-3 ng-star-inserted">
                <datos-generales-lateral-fragment _ngcontent-crt-c19="" _nghost-crt-c23="">
                    <div _ngcontent-crt-c23="" class="card b">
                        <div _ngcontent-crt-c23="" class="card-header d-flex align-items-center">
                            <div _ngcontent-crt-c23="" class="d-flex col p-0">
                                <h4 _ngcontent-crt-c23="" class="card-title">Datos Generales</h4>
                            </div>
                            <div _ngcontent-crt-c23="" class="d-flex justify-content-end"><button _ngcontent-crt-c23="" class="btn btn-link hidden-lg"><em _ngcontent-crt-c23="" class="fa fa-minus text-muted"></em></button></div>
                        </div>
                        <div _ngcontent-crt-c23="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
                                <scrollable _ngcontent-crt-c23="" class="list-group" height="60vh" style="overflow: hidden; overflow-y: scroll; width: auto; height: 60vh;">
                                    <div _ngcontent-crt-c23="" class="row">
                                        <div _ngcontent-crt-c23="" class="col-lg-12 col-md-6">
                                            <div _ngcontent-crt-c23="" class="col-lg-12 col-md-12">
                                                <h5 _ngcontent-crt-c23="" class="text-muted">Datos de la Propuesta</h5>
                                            </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-crt-c23="" class="text-bold">Nro. Documento Propuesta:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 22382.0 </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Tipo de Operación:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Presentación de Propuesta/Oferta </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Documento del Proveedor:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-3  col-lg-12"> 2044323014 </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Razón Social del Proveedor:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                                <!----> ALIAGA QUENTA RODOLFO REYNALDO
                                                <!---->
                                            </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Fecha de Elaboración:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 03/03/2021 12:27 </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Estado:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> ELABORADO </div>
                                        </div>
                                        <div _ngcontent-crt-c23="" class="col-lg-12 col-md-6">
                                            <div _ngcontent-crt-c23="" class="col-lg-12 col-md-12 mt-2">
                                                <h5 _ngcontent-crt-c23="" class="text-muted">Datos del Proceso</h5>
                                            </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-crt-c23="" class="text-bold">CUCE:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21-1712-00-1116922-1-1 </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Objeto de Contratación:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                                <text-length _ngcontent-crt-c23="" _nghost-crt-c16="">MANTENIMIENTO DE CANCHA DE FUTBOL ESTADIO INTEGRAC
                                                    <!----><a _ngcontent-crt-c16="" class="ng-star-inserted">Ver más</a>
                                                </text-length>
                                            </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Modalidad:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Contratación Menor </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Tipo de Contratación:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Servicios Generales </div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Forma de Adjudicación:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Por el Total </div>
                                            <!---->
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-crt-c23="" class="text-bold">Moneda:</label></div>
                                            <div _ngcontent-crt-c23="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> BOLIVIANOS </div>
                                        </div>
                                    </div>
                                </scrollable>
                                <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 203.313px;"></div>
                                <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                            </div>
                        </div>
                    </div>
                </datos-generales-lateral-fragment>
            </div>
            <div _ngcontent-crt-c19="" class="col-lg-9">
                <router-outlet _ngcontent-crt-c19=""></router-outlet>
                <prv-propuestas-economica-screen _nghost-crt-c43="" class="ng-star-inserted">
                    <!---->
                    <!---->
                    <!---->
                    <div _ngcontent-crt-c43="" class="card b ng-star-inserted">
                        <div _ngcontent-crt-c43="" class="card-header bb">
                            <h4 _ngcontent-crt-c43="" class="card-title">Registro de Precios</h4>
                        </div>
                        <div _ngcontent-crt-c43="" class="card-body bt bb">
                            <!---->
                            <!---->
                            <datos-lotes-fragment _ngcontent-crt-c43="" _nghost-crt-c37="">
                                <!---->
                                <!---->
                                <div _ngcontent-crt-c37="" class="card b ng-star-inserted" id="id-cont_panel_precios">


                                
<!---->
<div _ngcontent-crt-c37="" class="card-header ng-star-inserted" style="padding: 0 !important;">
    <table _ngcontent-crt-c37="" class="table table-sm table-bordered">
        <tr _ngcontent-crt-c37="">
            <!---->
            <!---->
            <td _ngcontent-crt-c37="" class="text-center w-cog ng-star-inserted">
                <div _ngcontent-crt-c37="" class="btn-group" dropdown=""><button onclick="dropdown_prnd_CM_2();" _ngcontent-crt-c37="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-74"><span _ngcontent-crt-c37="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                    <ul onclick="prnd_CM_registrar_precios();" id="id-sw_dropdown_prnd_CM_2" style="display: none;" _ngcontent-crt-c37="" class="dropdown-menu ng-star-inserted show" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);">
                        <!----><a _ngcontent-crt-c37="" class="dropdown-item text-dark ng-star-inserted"><span _ngcontent-crt-c37="" class="fa fa-edit text-primary"></span> Registrar precios unitarios </a>
                        <!---->
                        <!---->
                        <!---->
                    </ul>
                </div>
            </td>
            <td _ngcontent-crt-c37=""><b _ngcontent-crt-c37="">Descripción:</b>
                <!---->
                <!----> Todos los ítems
                <!---->
            </td>
            <!---->
            <!---->
            <td _ngcontent-crt-c37="" class="text-right ng-star-inserted"><b _ngcontent-crt-c37="">Total Ofertado:</b> <?php echo round($valor_ofertado,2); ?></td>
            <!---->
            <!---->
            <td _ngcontent-crt-c37="">
                <div _ngcontent-crt-c37="" class="float-right"><button onclick="dropdown_prnd_CM_1();" _ngcontent-crt-c37="" class="btn btn-xs btn-link text-muted"><em _ngcontent-crt-c37="" class="text-muted fa fa-plus"></em></button></div>
            </td>
        </tr>
    </table>
</div>
<!---->

<div _ngcontent-crt-c37="" class="card-body bt ng-star-inserted" id="id-sw_dropdown_prnd_CM_1" style="display: none;">
    <datos-items-fragment _ngcontent-crt-c37="" _nghost-crt-c39="">
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
                            <!----><span _ngcontent-crt-c39="" class="ng-star-inserted"><?php echo round($precio_unitario_ofertado,2); ?></span>
                            <!---->
                        </td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"><span _ngcontent-crt-c39=""><?php echo round($valor_ofertado,2); ?></span></td>
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
                        <th _ngcontent-crt-c39="" class="text-right"><?php echo round($valor_ofertado,2); ?></th>
                    </tr>
                    <!---->
                </tfoot>
            </table>
        </div>
    </datos-items-fragment>
</div>

                                </div>
                                <!---->
                            </datos-lotes-fragment>
                            <seleccion-lote-modal _ngcontent-crt-c43="" id="idSeleccionLoteModal" _nghost-crt-c38="">
                                <div _ngcontent-crt-c38="" bsmodal="" class="modal fade">
                                    <div _ngcontent-crt-c38="" class="modal-dialog modal-lg">
                                        <div _ngcontent-crt-c38="" class="modal-content">
                                            <div _ngcontent-crt-c38="" class="modal-header text-center">
                                                <h4 _ngcontent-crt-c38="" class="text-color-blanco w-100"> Selección de s a participar </h4><button _ngcontent-crt-c38="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c38="" aria-hidden="true">×</span></button>
                                            </div>
                                            <div _ngcontent-crt-c38="" class="modal-body">
                                                <datos-lotes-fragment _ngcontent-crt-c38="" _nghost-crt-c37="">
                                                    <!---->
                                                    <!---->
                                                    <!----> No hay registro de s
                                                </datos-lotes-fragment>
                                            </div>
                                            <div _ngcontent-crt-c38="" class="modal-footer"><button _ngcontent-crt-c38="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-crt-c38="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button></div>
                                        </div>
                                    </div>
                                </div>
                            </seleccion-lote-modal>
                        </div>
                    </div>
                    <!---->
                    <datos-cronograma-modal _ngcontent-crt-c43="" id="idCronDModal" _nghost-crt-c32="">
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
                    <prv-propuesta-econ-lote-modal _ngcontent-crt-c43="" id="idPropEconLoteModal" _nghost-crt-c45="">
                        <div _ngcontent-crt-c45="" bsmodal="" class="modal fade">
                            <div _ngcontent-crt-c45="" class="modal-dialog modal-xl">
                                <div _ngcontent-crt-c45="" class="modal-content">
                                    <div _ngcontent-crt-c45="" class="modal-header text-center">
                                        <!---->
                                        <!---->
                                        <h4 _ngcontent-crt-c45="" class="text-color-blanco w-100 ng-star-inserted"> Registro de Precios
                                            <!---->
                                        </h4><button _ngcontent-crt-c45="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c45="" aria-hidden="true">×</span></button>
                                    </div>
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
                                                                <!----> Precio Referencial Unitario
                                                                <!---->
                                                            </th>
                                                            <!---->
                                                            <!---->
                                                            <th _ngcontent-crt-c39="" class="text-center ng-star-inserted">
                                                                <!----> Precio Referencial Total
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
                                                    </tbody>
                                                    <tfoot _ngcontent-crt-c39="">
                                                        <!---->
                                                        <!---->
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </datos-items-fragment>
                                    </div>
                                    <div _ngcontent-crt-c45="" class="modal-footer"><button _ngcontent-crt-c45="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-crt-c45="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>
                                </div>
                            </div>
                        </div>
                    </prv-propuesta-econ-lote-modal>
                </prv-propuestas-economica-screen>
                <div _ngcontent-crt-c19="" class="h100"></div>
                <botones-opciones-footer _ngcontent-crt-c19="" _nghost-crt-c24="">
                    <div _ngcontent-crt-c24="" class="row">
                        <div _ngcontent-crt-c24="" class="col-12 text-right">
                            <!---->
                            <!----><a onclick="pnd_press_select_proceso('CM');" _ngcontent-crt-c24="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-crt-c24="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-crt-c24="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                        </div>
                    </div>
                </botones-opciones-footer>
            </div>
        </div>
        <propuesta-aprobar-modal _ngcontent-crt-c19="" id="idModalPropuestaAprobar" _nghost-crt-c25="">
            <div _ngcontent-crt-c25="" bsmodal="" class="modal fade">
                <div _ngcontent-crt-c25="" class="modal-dialog modal-lg">
                    <div _ngcontent-crt-c25="" class="modal-content">
                        <div _ngcontent-crt-c25="" class="modal-header text-center">
                            <h4 _ngcontent-crt-c25="" class="text-color-blanco w-100">
                                <!---->
                                <!----> POLÍTICAS Y CONDICIONES DE USO
                                <!---->
                            </h4><button _ngcontent-crt-c25="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-crt-c25="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-crt-c25="" class="modal-body">
                            <p _ngcontent-crt-c25="" class="text-justify">
                                <!---->
                                <!---->
                            <h4 _ngcontent-crt-c25="" class="ng-star-inserted">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-crt-c25="" class="ng-star-inserted"> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema.
                            <!---->
                            </p>
                            <div _ngcontent-crt-c25="" class="border-top"><br _ngcontent-crt-c25="">
                                <!---->
                                <div _ngcontent-crt-c25="" class="ng-star-inserted"><span _ngcontent-crt-c25="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-crt-c25=""><input _ngcontent-crt-c25="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-crt-c25="" class="fa fa-check"></span><b _ngcontent-crt-c25="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div>
                            </div>
                        </div>
                        <div _ngcontent-crt-c25="" class="modal-footer"><button _ngcontent-crt-c25="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button _ngcontent-crt-c25="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </propuesta-aprobar-modal>
        <confirmacion-modal _ngcontent-crt-c19="" id="idModalConfirmacion" _nghost-crt-c26="">
            <div _ngcontent-crt-c26="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
                <div _ngcontent-crt-c26="" class="modal-dialog modal-sm">
                    <div _ngcontent-crt-c26="" class="modal-content">
                        <div _ngcontent-crt-c26="" class="modal-body">
                            <div _ngcontent-crt-c26="" class="row">
                                <div _ngcontent-crt-c26="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                                <div _ngcontent-crt-c26="" class="col-lg-12 col-md-12 text-center"> </div>
                            </div>
                        </div>
                        <div _ngcontent-crt-c26="" class="modal-footer"><button _ngcontent-crt-c26="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-crt-c26="" type="button" class="btn btn-">Aceptar</button></div>
                    </div>
                </div>
            </div>
        </confirmacion-modal>
    </prv-propuestas-screen>
</div>