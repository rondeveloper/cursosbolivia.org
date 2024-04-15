<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$cuce = '21-0513-00-1114217-1-1';
?>
<style>.opciones[_ngcontent-yfk-c29]{position:fixed;right:0;z-index:1}.opciones[_ngcontent-yfk-c29]   .btn[_ngcontent-yfk-c29]{box-shadow:0 0 10px #666;border:0;margin-top:20px;padding:0}.opciones[_ngcontent-yfk-c29]   .btn-inverse[_ngcontent-yfk-c29]{background:#5d9cec;border-radius:10px 0 0 10px;margin-left:35px;padding:5px}.opciones[_ngcontent-yfk-c29]   .btn-inverse[_ngcontent-yfk-c29]:hover{background:#115f77}.opciones[_ngcontent-yfk-c29]   .btn-inverse[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29], .opciones[_ngcontent-yfk-c29]   .btn[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29]{font-size:23px}.opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]{box-shadow:0 1px 5px rgba(0,0,0,.2);background:#fff;border-radius:5px;margin-top:20px;padding:5px 0!important}.opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   div[_ngcontent-yfk-c29], .opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   span[_ngcontent-yfk-c29]{text-align:center}.opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   div[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29], .opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   span[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29]{font-size:18px;cursor:pointer;margin:3px 10px;padding:5px}.opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   div[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29]:hover, .opciones[_ngcontent-yfk-c29]   .affix[_ngcontent-yfk-c29]   span[_ngcontent-yfk-c29]   i[_ngcontent-yfk-c29]:hover{background:#115f77;border-radius:3px;color:#fff;padding:5px}.popover[_ngcontent-yfk-c29]{max-width:100%}.cursor-pointer[_ngcontent-yfk-c29]{cursor:pointer}</style>

    <router-outlet _ngcontent-yfk-c1=""></router-outlet>
    <prv-propuestas-screen _nghost-yfk-c28="">
        <botones-opciones _ngcontent-yfk-c28="" _nghost-yfk-c29="">
            <div _ngcontent-yfk-c29="" class="opciones affix">
                <!---->
                <!---->
                <div _ngcontent-yfk-c29="" class="btn btn-inverse" placement="left" tooltip="Ocultar" aria-describedby="tooltip-0"><i _ngcontent-yfk-c29="" class="fa fa-chevron-circle-right"></i></div>
                <!---->
                <div _ngcontent-yfk-c29="" class="affix">
                    <!---->
                    <div onclick="pnd_verificar_doc()" _ngcontent-yfk-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-23"><span _ngcontent-yfk-c29="" class="cursor-pointer"><i _ngcontent-yfk-c29="" class="fa fa-check-circle text-primary"></i> Verificar</span></div>
                    <!---->
                    <div onclick="pnd_elimnar_doc()" _ngcontent-yfk-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-24"><span _ngcontent-yfk-c29="" class="cursor-pointer"><i _ngcontent-yfk-c29="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
                    <!---->
                </div>
            </div>
        </botones-opciones>
        <div _ngcontent-yfk-c28="" class="content-heading p5 mb-0">
            <div _ngcontent-yfk-c28="" class="row w-100">
                <div _ngcontent-yfk-c28="" class="row pt-5 col-12 d-md-none"></div>
                <div _ngcontent-yfk-c28="" class="col-lg-5 col-12 pt10"> Mis Propuestas Electrónicas </div>
                <div _ngcontent-yfk-c28="" class="col-lg-4 col-12 pt10 h30">
                    <spinner-http _ngcontent-yfk-c28="" _nghost-yfk-c13="">
                        <!---->
                    </spinner-http>
                </div>
                <div _ngcontent-yfk-c28="" class="col-lg-3 col-12">
                    <reloj-fragment _ngcontent-yfk-c28="" _nghost-yfk-c14="">
                        <div _ngcontent-yfk-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                            <div _ngcontent-yfk-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                                <div _ngcontent-yfk-c14="" class="text-center">
                                    <div _ngcontent-yfk-c14="" class="text-sm">Febrero</div>
                                    <div _ngcontent-yfk-c14="" class="h4 mt-0">26</div>
                                </div>
                            </div>
                            <div _ngcontent-yfk-c14="" class="col-8 rounded-right"><span _ngcontent-yfk-c14="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-yfk-c14="">
                                <div _ngcontent-yfk-c14="" class="h4 mt-0">17:49:08</div>
                            </div>
                        </div>
                    </reloj-fragment>
                </div>
            </div>
        </div>
        <div _ngcontent-yfk-c28="" class="row">
            <div _ngcontent-yfk-c28="" class="col-lg-6 offset-lg-3">
                <wizard _ngcontent-yfk-c28="" class="w-100" _nghost-yfk-c30="">
                    <div _ngcontent-yfk-c30="" class="d-flex justify-content-around bd-highlight mb-0">
                        <!---->
                        <div onclick="pnd_press_select_proceso();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 1" aria-describedby="tooltip-3"><i _ngcontent-yfk-c30="" class="fas fa-file-alt"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Datos Generales</span></div>
                        <div onclick="page_reg_nuevo_documento_p1();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 2" aria-describedby="tooltip-4"><i _ngcontent-yfk-c30="" class="fas fa-upload"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Documentos Adjuntos</span></div>
                        <div onclick="page_reg_nuevo_documento_p2();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 3" aria-describedby="tooltip-5"><i _ngcontent-yfk-c30="" class="fas fa-money-bill"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Registro de Precios</span></div>
                        <div onclick="page_reg_nuevo_documento_p3();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 4" aria-describedby="tooltip-6"><i style="font-family: cursive;font-size: 11pt;">M</i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-active">Márgenes de Preferencia</span></div>
                        <div onclick="page_reg_nuevo_documento_p4();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 5" aria-describedby="tooltip-7"><i _ngcontent-yfk-c30="" class="fa fa-calendar"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Plazo de entrega</span></div>
                    </div>
                </wizard>
            </div>
        </div>
        <div _ngcontent-yfk-c28="" class="row">
            <div _ngcontent-yfk-c28="" class="col-lg-12">
                <mensaje-documento-fragment _ngcontent-yfk-c28="" _nghost-yfk-c31="">
                    <!---->
                </mensaje-documento-fragment>
            </div>
        </div>
        <div _ngcontent-yfk-c28="" class="row">
            <!---->
            <div _ngcontent-yfk-c28="" class="col-lg-3">
                <datos-generales-lateral-fragment _ngcontent-yfk-c28="" _nghost-yfk-c32="">
                    <div _ngcontent-yfk-c32="" class="card b">
                        <div _ngcontent-yfk-c32="" class="card-header d-flex align-items-center">
                            <div _ngcontent-yfk-c32="" class="d-flex col p-0">
                                <h4 _ngcontent-yfk-c32="" class="card-title">Datos Generales</h4>
                            </div>
                            <div _ngcontent-yfk-c32="" class="d-flex justify-content-end"><button _ngcontent-yfk-c32="" class="btn btn-link hidden-lg"><em _ngcontent-yfk-c32="" class="fa fa-minus text-muted"></em></button></div>
                        </div>
                        <div _ngcontent-yfk-c32="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
                                <scrollable _ngcontent-yfk-c32="" class="list-group" height="60vh" style="overflow: hidden; overflow-y: scroll;width: auto; height: 60vh;">
                                    <div _ngcontent-yfk-c32="" class="row">
                                        <div _ngcontent-yfk-c32="" class="col-lg-12 col-md-6">
                                            <div _ngcontent-yfk-c32="" class="col-lg-12 col-md-12">
                                                <h5 _ngcontent-yfk-c32="" class="text-muted">Datos de la Propuesta</h5>
                                            </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-yfk-c32="" class="text-bold">Nro. Documento Propuesta:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21040.0 </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Tipo de Operación:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Presentación de Propuesta/Oferta </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Documento del Proveedor:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-3  col-lg-12"> 2044323014 </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Razón Social del Proveedor:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                                <!----> ALIAGA QUENTA RODOLFO REYNALDO
                                                <!---->
                                            </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Fecha de Elaboración:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 26/02/2021 17:33 </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Estado:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> ELABORADO </div>
                                        </div>
                                        <div _ngcontent-yfk-c32="" class="col-lg-12 col-md-6">
                                            <div _ngcontent-yfk-c32="" class="col-lg-12 col-md-12 mt-2">
                                                <h5 _ngcontent-yfk-c32="" class="text-muted">Datos del Proceso</h5>
                                            </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-yfk-c32="" class="text-bold">CUCE:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21-0513-00-1114217-1-1 </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Objeto de Contratación:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                                <text-length _ngcontent-yfk-c32="" _nghost-yfk-c16="">ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL
                                                    <!---->
                                                </text-length>
                                            </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Modalidad:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> LP </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Tipo de Contratación:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Bienes </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Forma de Adjudicación:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Por Items </div>
                                            <!---->
                                            <!---->
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Método de Selección y Adjudicación:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Precio evaluado más bajo </div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-yfk-c32="" class="text-bold">Moneda:</label></div>
                                            <div _ngcontent-yfk-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> BOLIVIANOS </div>
                                        </div>
                                    </div>
                                </scrollable>
                                <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 259.561px;"></div>
                                <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                            </div>
                        </div>
                    </div>
                </datos-generales-lateral-fragment>
            </div>
            <div _ngcontent-yfk-c28="" class="col-lg-9">
                <router-outlet _ngcontent-yfk-c28=""></router-outlet>
                <prv-propuestas-margenes-screen _nghost-yfk-c47="">
                    <prv-propuestas-margenes-prov-fragment _ngcontent-yfk-c47="" _nghost-yfk-c48="">
                        <div _ngcontent-yfk-c48="" class="card b">
                            <div _ngcontent-yfk-c48="" class="card-header bb">
                                <h4 _ngcontent-yfk-c48="" class="card-title">Márgenes de Preferencia aplicables al Proponente</h4>
                            </div>
                            <div _ngcontent-yfk-c48="" class="card-body bt">
                                <!----><button _ngcontent-yfk-c48="" onclick="prnd_margpref_new();" class="btn btn-sm btn-primary" type="button">Nuevo margen</button>
                                <div id="id-prnd_tabla_margpref">
                                <table _ngcontent-yfk-c48="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                    <thead _ngcontent-yfk-c48="">
                                        <th _ngcontent-yfk-c48="" class="text-center w-cog" style="width: 80px;">Opciones</th>
                                        <th _ngcontent-yfk-c48="" class="text-center">Márgenes de Preferencia</th>
                                        <th _ngcontent-yfk-c48="" class="text-center">Porcentaje</th>
                                    </thead>
                                    <tbody _ngcontent-yfk-c48="">
                                        <!---->
                                        <!---->
                                        <?php

                                        $rqvemp1 = query("SELECT id FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
                                        if(num_rows($rqvemp1)>0){
                                        ?>
                                        <tr _ngcontent-woi-c36="">
                                            <td _ngcontent-woi-c36="" class="text-center">
                                                <!---->
                                                <div _ngcontent-woi-c36="" class="btn-group" dropdown=""><button _ngcontent-woi-c36="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-12"><span _ngcontent-woi-c36="" class="fa fa-cog text-primary"></span></button>
                                                    <!---->
                                                </div>
                                                <!---->
                                            </td>
                                            <td _ngcontent-woi-c36="">Tipo de Proponente (MyPE, OECA, APP)</td>
                                            <td _ngcontent-woi-c36="">20</td>
                                        </tr>
                                        <?php
                                        }else{
                                        ?>
                                        <tr _ngcontent-yfk-c48="">
                                            <td _ngcontent-yfk-c48="" colspan="3">No hay registro de márgenes de preferencia</td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div _ngcontent-yfk-c48="" class="card-footer bb"><b _ngcontent-yfk-c48="">Los márgenes de preferencia seleccionados se aplicará a todos los ítems en los que el proponente se presente</b></div>
                        </div>
                    </prv-propuestas-margenes-prov-fragment>
                    <!---->
                    <!---->
                    <!---->
                    <div _ngcontent-yfk-c47="" class="card b">
                        <div _ngcontent-yfk-c47="" class="card-header bb">
                            <h4 _ngcontent-yfk-c47="" class="card-title">Márgenes de Preferencia aplicables a Ítems</h4>
                        </div>
                        <div _ngcontent-yfk-c47="" class="card-body bt">
                            <datos-items-fragment _ngcontent-yfk-c47="" _nghost-yfk-c39="">
                                <div _ngcontent-yfk-c39="" class="row">
                                    <div _ngcontent-yfk-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                        <div _ngcontent-yfk-c39="" class="input-group input-group-sm"><input _ngcontent-yfk-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-yfk-c39="" class="input-group-btn"><button _ngcontent-yfk-c39="" class="btn btn-primary" type="button"><span _ngcontent-yfk-c39="" class="fa fa-search"></span></button></span></div>
                                    </div><br _ngcontent-yfk-c39=""><br _ngcontent-yfk-c39="">
                                </div>
                                <div _ngcontent-yfk-c39="" class="table-responsive" id="id-prnd_tabla_itemmargpref">
                                    <table _ngcontent-yfk-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                        <thead _ngcontent-yfk-c39="">
                                            <!---->
                                            <tr _ngcontent-yfk-c39="">
                                                <!---->
                                                <th _ngcontent-yfk-c39="" class="text-center"></th>
                                                <!---->
                                                <th _ngcontent-yfk-c39="" class="text-center">#</th>
                                                <th _ngcontent-yfk-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                                <th _ngcontent-yfk-c39="" class="text-center">Unidad de Medida</th>
                                                <th _ngcontent-yfk-c39="" class="text-center">Cantidad</th>
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <th _ngcontent-yfk-c39="" class="text-center">Márgenes de Preferencia</th>
                                                <!---->
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-yfk-c39="">
                                            <!---->
                                            <?php
                                            $rqdir1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                                            $cnt = 1;
                                            if(num_rows($rqdir1)==0){
                                                ?>
                                                <!---->
                                                <tr _ngcontent-yfk-c39="">
                                                    <td _ngcontent-yfk-c39="" colspan="20">No hay registro de Ítems</td>
                                                </tr>
                                                <?php
                                            }
                                            while($rqdir2 = fetch($rqdir1)){
                                            ?>
                                            <tr _ngcontent-pjb-c39="">
                                                <!---->
                                                <!---->
                                                <td _ngcontent-grk-c38="" class="text-center w-cog">
                                                    <div _ngcontent-grk-c38="" class="btn-group open show" dropdown="">
                                                    <button onclick="dropdown_prnd_item(<?php echo $rqdir2['id']; ?>);" _ngcontent-grk-c38="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-10" aria-expanded="true"><span _ngcontent-grk-c38="" class="fa fa-cog text-primary"></span></button>
                                                    <!---->
                                                    <ul id="id-dropdown_prnd_item-<?php echo $rqdir2['id']; ?>" _ngcontent-grk-c38="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);"><!----><!---->
                                                        <a onclick="prnd_itemmargpref_new(<?php echo $rqdir2['id']; ?>);" _ngcontent-grk-c38="" class="dropdown-item text-dark"><b>m</b> Registrar Márgenes </a><!---->
                                                    </ul>
                                                    </div>
                                                </td>
                                                <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                                                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['descripcion']; ?></td>
                                                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['unidad_medida']; ?></td>
                                                <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdir2['cantidad']; ?></td>
                                                <!---->
                                                <td _ngcontent-pjb-c39="">
                                                <?php
                                                $rqmrgpr1 = query("SELECT * FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' AND id_item='".$rqdir2['id']."' ");
                                                if(num_rows($rqmrgpr1)==0){
                                                    echo 'No registró Márgenes de Preferencia';
                                                }
                                                while($rqmrgpr2 = fetch($rqmrgpr1)){
                                                    echo $rqmrgpr2['margen_pref']."<br>";
                                                }
                                                ?>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>   
                                        </tbody>
                                        <tfoot _ngcontent-yfk-c39="">
                                            <!---->
                                            <!---->
                                        </tfoot>
                                    </table>
                                    <br>
                                    <br>
                                </div>
                            </datos-items-fragment>
                        </div>
                    </div>
                    <!---->
                    <prv-propuestas-margenes-modal _ngcontent-yfk-c47="" id="idMargenesModal" _nghost-yfk-c49="">
                        <div _ngcontent-yfk-c49="" bsmodal="" class="modal fade">
                            <div _ngcontent-yfk-c49="" class="modal-dialog modal-lg">
                                <div _ngcontent-yfk-c49="" class="modal-content">
                                    <div _ngcontent-yfk-c49="" class="modal-header text-center">
                                        <h4 _ngcontent-yfk-c49="" class="text-color-blanco w-100"> Márgenes de Preferencia </h4><button _ngcontent-yfk-c49="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-yfk-c49="" aria-hidden="true">×</span></button>
                                        <h4 _ngcontent-yfk-c49="" class="text-color-blanco ng-binding"></h4>
                                    </div>
                                    <div _ngcontent-yfk-c49="" class="modal-body">
                                        <div _ngcontent-yfk-c49="" class="row">
                                            <div _ngcontent-yfk-c49="" class="col-lg-12 col-md-12">
                                                <div _ngcontent-yfk-c49="" class="table-responsive">
                                                    <table _ngcontent-yfk-c49="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                                        <thead _ngcontent-yfk-c49="">
                                                            <tr _ngcontent-yfk-c49="">
                                                                <th _ngcontent-yfk-c49="" class="text-center">Selección</th>
                                                                <th _ngcontent-yfk-c49="" class="text-center">Márgenes de Preferencia</th>
                                                                <th _ngcontent-yfk-c49="" class="text-center">Porcentaje</th>
                                                                <th _ngcontent-yfk-c49="" class="text-center">Observación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody _ngcontent-yfk-c49="">
                                                            <!---->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-yfk-c49="" class="modal-footer"><button _ngcontent-yfk-c49="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button><button _ngcontent-yfk-c49="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </prv-propuestas-margenes-modal>
                </prv-propuestas-margenes-screen>
                <div _ngcontent-yfk-c28="" class="h100"></div>
                <botones-opciones-footer _ngcontent-yfk-c28="" _nghost-yfk-c23="">
                    <div _ngcontent-yfk-c23="" class="row">
                        <div _ngcontent-yfk-c23="" class="col-12 text-right">
                            <!---->
                            <!----><a onclick="page_reg_nuevo_documento_p2();" _ngcontent-yfk-c23="" class="fa-stack fa-lg cursor-pointer"><i _ngcontent-yfk-c23="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-yfk-c23="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                            <!----><a onclick="page_reg_nuevo_documento_p4();" _ngcontent-yfk-c23="" class="fa-stack fa-lg cursor-pointer"><i _ngcontent-yfk-c23="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-yfk-c23="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
                        </div>
                    </div>
                </botones-opciones-footer>
            </div>
        </div>
        <propuesta-aprobar-modal _ngcontent-yfk-c28="" id="idModalPropuestaAprobar" _nghost-yfk-c33="">
            <div _ngcontent-yfk-c33="" bsmodal="" class="modal fade">
                <div _ngcontent-yfk-c33="" class="modal-dialog modal-lg">
                    <div _ngcontent-yfk-c33="" class="modal-content">
                        <div _ngcontent-yfk-c33="" class="modal-header text-center">
                            <h4 _ngcontent-yfk-c33="" class="text-color-blanco w-100">
                                <!---->
                                <!----> POLÍTICAS Y CONDICIONES DE USO
                                <!---->
                            </h4><button _ngcontent-yfk-c33="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-yfk-c33="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-yfk-c33="" class="modal-body">
                            <p _ngcontent-yfk-c33="" class="text-justify">
                                <!---->
                                <!---->
                            <h4 _ngcontent-yfk-c33="">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-yfk-c33=""> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema.
                            <!---->
                            </p>
                            <div _ngcontent-yfk-c33="" class="border-top"><br _ngcontent-yfk-c33="">
                                <!---->
                                <div _ngcontent-yfk-c33=""><span _ngcontent-yfk-c33="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-yfk-c33=""><input _ngcontent-yfk-c33="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-yfk-c33="" class="fa fa-check"></span><b _ngcontent-yfk-c33="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div>
                            </div>
                        </div>
                        <div _ngcontent-yfk-c33="" class="modal-footer"><button _ngcontent-yfk-c33="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button _ngcontent-yfk-c33="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                    </div>
                </div>
            </div>
        </propuesta-aprobar-modal>
        <confirmacion-modal _ngcontent-yfk-c28="" id="idModalConfirmacion" _nghost-yfk-c34="">
            <div _ngcontent-yfk-c34="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1" aria-modal="true" style="display: none;">
                <div _ngcontent-yfk-c34="" class="modal-dialog modal-sm">
                    <div _ngcontent-yfk-c34="" class="modal-content">
                        <div _ngcontent-yfk-c34="" class="modal-body">
                            <div _ngcontent-yfk-c34="" class="row">
                                <div _ngcontent-yfk-c34="" class="col-lg-12 col-md-12 text-center"> ¿Está seguro que desea verificar el documento? </div>
                                <div _ngcontent-yfk-c34="" class="col-lg-12 col-md-12 text-center"> </div>
                            </div>
                        </div>
                        <div _ngcontent-yfk-c34="" class="modal-footer"><button _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-yfk-c34="" type="button" class="btn btn-primary">Aceptar</button></div>
                    </div>
                </div>
            </div>
        </confirmacion-modal>
    </prv-propuestas-screen>