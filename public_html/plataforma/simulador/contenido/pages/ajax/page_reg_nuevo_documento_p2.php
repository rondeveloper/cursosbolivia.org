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
    .opciones[_ngcontent-yfk-c29] {
        position: fixed;
        right: 0;
        z-index: 1
    }

    .opciones[_ngcontent-yfk-c29] .btn[_ngcontent-yfk-c29] {
        box-shadow: 0 0 10px #666;
        border: 0;
        margin-top: 20px;
        padding: 0
    }

    .opciones[_ngcontent-yfk-c29] .btn-inverse[_ngcontent-yfk-c29] {
        background: #5d9cec;
        border-radius: 10px 0 0 10px;
        margin-left: 35px;
        padding: 5px
    }

    .opciones[_ngcontent-yfk-c29] .btn-inverse[_ngcontent-yfk-c29]:hover {
        background: #115f77
    }

    .opciones[_ngcontent-yfk-c29] .btn-inverse[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29],
    .opciones[_ngcontent-yfk-c29] .btn[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29] {
        font-size: 23px
    }

    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] {
        box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
        background: #fff;
        border-radius: 5px;
        margin-top: 20px;
        padding: 5px 0 !important
    }

    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] div[_ngcontent-yfk-c29],
    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] span[_ngcontent-yfk-c29] {
        text-align: center
    }

    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] div[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29],
    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] span[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29] {
        font-size: 18px;
        cursor: pointer;
        margin: 3px 10px;
        padding: 5px
    }

    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] div[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29]:hover,
    .opciones[_ngcontent-yfk-c29] .affix[_ngcontent-yfk-c29] span[_ngcontent-yfk-c29] i[_ngcontent-yfk-c29]:hover {
        background: #115f77;
        border-radius: 3px;
        color: #fff;
        padding: 5px
    }

    .popover[_ngcontent-yfk-c29] {
        max-width: 100%
    }

    .cursor-pointer[_ngcontent-yfk-c29] {
        cursor: pointer
    }
</style>

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
                <div onclick="pnd_verificar_doc()" _ngcontent-yfk-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-9"><span _ngcontent-yfk-c29="" class="cursor-pointer"><i _ngcontent-yfk-c29="" class="fa fa-check-circle text-primary"></i> Verificar</span></div>
                <!---->
                <div onclick="pnd_elimnar_doc()" _ngcontent-yfk-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-10"><span _ngcontent-yfk-c29="" class="cursor-pointer"><i _ngcontent-yfk-c29="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
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
                            <div _ngcontent-yfk-c14="" class="h4 mt-0">17:45:03</div>
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
                    <div onclick="page_reg_nuevo_documento_p2();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 3" aria-describedby="tooltip-5"><i _ngcontent-yfk-c30="" class="fas fa-money-bill"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-active">Registro de Precios</span></div>
                    <div onclick="page_reg_nuevo_documento_p3();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 4" aria-describedby="tooltip-6"><i style="font-family: cursive;font-size: 11pt;">M</i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Márgenes de Preferencia</span></div>
                    <div onclick="page_reg_nuevo_documento_p4();" _ngcontent-yfk-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-yfk-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 5" aria-describedby="tooltip-7"><i _ngcontent-yfk-c30="" class="fa fa-calendar"></i></button><br _ngcontent-yfk-c30=""><span _ngcontent-yfk-c30="" class="sp-title-pass">Plazo de entrega</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-yfk-c28="" class="row">
        <div _ngcontent-yfk-c28="" class="col-lg-12">
            <mensaje-documento-fragment _ngcontent-yfk-c28="" _nghost-yfk-c31="">
                <!---->
                <!---->
                <alert _ngcontent-yfk-c31="" type="dark">
                    <!---->
                    <div role="alert" class="alert alert-dark">
                        <!----><em _ngcontent-yfk-c31="" class="fa fa-exclamation-circle fa-lg fa-fw"></em>
                        <!---->
                        <!----><span _ngcontent-yfk-c31=""><b _ngcontent-yfk-c31="">Los precios registrados son encriptados y podrán ser desencriptados por la entidad en la fecha y hora de apertura de propuestas</b></span>
                    </div>
                </alert>
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
            <prv-propuestas-economica-screen _nghost-yfk-c44="" id="pnrd-propuestas-economicas">
                <?php
                $cuce = '21-0513-00-1114217-1-1';
                $id_usuario = usuario('id_sim');

                $rqpto1 = query("SELECT sum(precio_ofertado*cantidad) AS total FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                $rqpto2 = fetch($rqpto1);
                $precio_total_ofertado = $rqpto2['total'];

                ?>
                <!---->
                <datos-totales-fragment _ngcontent-yfk-c44="" _nghost-yfk-c45="">
                    <div _ngcontent-yfk-c45="" class="card b">
                        <div _ngcontent-yfk-c45="" class="card-header d-flex align-items-center">
                            <div _ngcontent-yfk-c45="" class="d-flex col p-0">
                                <h4 _ngcontent-yfk-c45="" class="card-title"> Total General</h4>
                            </div>
                            <div _ngcontent-yfk-c45="" class="d-flex justify-content-end"><b _ngcontent-yfk-c45="">Precio Total Ofertado:</b> <?php echo number_format($precio_total_ofertado, 2); ?> </div>
                        </div>
                    </div>
                </datos-totales-fragment>
                <!---->
                <div _ngcontent-yfk-c44="" class="d-flex col p-0">
                    <h4 _ngcontent-yfk-c44="" class="card-title">
                        <!---->
                    </h4>
                </div>
                <div _ngcontent-yfk-c44="" class="card b">
                    <div _ngcontent-yfk-c44="" class="card-header bb">
                        <h4 _ngcontent-yfk-c44="" class="card-title">
                            <!---->
                            <div _ngcontent-yfk-c44="" class="btn-group" dropdown="">
                                <button onclick="prnd_rp_dropdown_1()" _ngcontent-yfk-c44="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-8"><span _ngcontent-yfk-c44="" class="fa fa-cog text-primary"></span></button>
                                <!---->
                                <ul id="id-sw_prnd_rp_dropdown_1" _ngcontent-pjb-c43="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);"><a onclick="prnd_editar_prop_eco();" _ngcontent-pjb-c43="" class="dropdown-item cursor-pointer text-dark"><span _ngcontent-pjb-c43="" class="fa fa-edit text-primary"></span> Editar Propuesta Económica </a></ul>
                            </div>
                            <!----> Registro de Precios
                        </h4>
                    </div>
                    <div _ngcontent-yfk-c44="" class="card-body bt bb">
                        <datos-items-fragment _ngcontent-yfk-c44="" _nghost-yfk-c39="">
                            <div _ngcontent-yfk-c39="" class="row">
                                <div _ngcontent-yfk-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                    <div _ngcontent-yfk-c39="" class="input-group input-group-sm"><input _ngcontent-yfk-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-yfk-c39="" class="input-group-btn"><button _ngcontent-yfk-c39="" class="btn btn-primary" type="button"><span _ngcontent-yfk-c39="" class="fa fa-search"></span></button></span></div>
                                </div><br _ngcontent-yfk-c39=""><br _ngcontent-yfk-c39="">
                            </div>
                            <div _ngcontent-yfk-c39="" class="table-responsive">
                                <table _ngcontent-pjb-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                    <thead _ngcontent-pjb-c39="">
                                        <!---->
                                        <tr _ngcontent-pjb-c39="">
                                            <!---->
                                            <th _ngcontent-pjb-c39=""></th>
                                            <!---->
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center border-right-color" colspan="5">Definido por la Entidad</th>
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                                        </tr>
                                        <tr _ngcontent-pjb-c39="">
                                            <!---->
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center">#</th>
                                            <th _ngcontent-pjb-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                            <th _ngcontent-pjb-c39="" class="text-center">Unidad de Medida</th>
                                            <th _ngcontent-pjb-c39="" class="text-center">Cantidad</th>
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center">
                                                <!----> Precio Referencial Unitario
                                                <!---->
                                            </th>
                                            <!---->
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center">
                                                <!----> Precio Referencial Total
                                                <!---->
                                            </th>
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center"> Precio Unitario Ofertado</th>
                                            <!---->
                                            <th _ngcontent-pjb-c39="" class="text-center"> Precio Total Ofertado</th>
                                            <!---->
                                            <!---->
                                            <!---->
                                        </tr>
                                    </thead>
                                    <tbody _ngcontent-pjb-c39="">
                                        <!---->
                                        <!---->
                                        <!---->
                                        <?php
                                        $rqdir1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                                        $cnt = 1;
                                        while($rqdir2 = fetch($rqdir1)){
                                        ?>
                                        <tr _ngcontent-pjb-c39="">
                                            <!---->
                                            <!---->
                                            <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                                            <td _ngcontent-pjb-c39=""><?php echo $rqdir2['descripcion']; ?></td>
                                            <td _ngcontent-pjb-c39=""><?php echo $rqdir2['unidad_medida']; ?></td>
                                            <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdir2['cantidad']; ?></td>
                                            <!---->
                                            <td _ngcontent-pjb-c39="" class="text-right"> <?php echo number_format($rqdir2['precio_unitario'],2); ?></td>
                                            <!---->
                                            <td _ngcontent-pjb-c39="" class="text-right"> <?php echo number_format($rqdir2['precio_total'],2); ?></td>
                                            <!---->
                                            <td _ngcontent-pjb-c39="" class="text-right">
                                                <!----><span _ngcontent-pjb-c39=""><?php echo number_format($rqdir2['precio_ofertado'],2); ?></span>
                                                <!---->
                                            </td>
                                            <!---->
                                            <td _ngcontent-pjb-c39="" class="text-right"><span _ngcontent-pjb-c39=""><?php echo number_format($rqdir2['precio_ofertado']*$rqdir2['cantidad'],2); ?></span></td>
                                            <!---->
                                            <!---->
                                            <!---->
                                        </tr>
                                        <?php
                                        }
                                        ?>                                        
                                        <!---->
                                    </tbody>
                                    <tfoot _ngcontent-pjb-c39="">
                                        <!---->
                                        <!---->
                                        <tr _ngcontent-pjb-c39="" style="height: 110px;"></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </datos-items-fragment>
                        <seleccion-items-modal _ngcontent-yfk-c44="" id="idSeleccionItemsModal" _nghost-yfk-c40="">
                            <div _ngcontent-yfk-c40="" bsmodal="" class="modal fade">
                                <div _ngcontent-yfk-c40="" class="modal-dialog modal-lg">
                                    <div _ngcontent-yfk-c40="" class="modal-content">
                                        <div _ngcontent-yfk-c40="" class="modal-header text-center">
                                            <h4 _ngcontent-yfk-c40="" class="text-color-blanco w-100"> Selección de undefineds a participar </h4><button _ngcontent-yfk-c40="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-yfk-c40="" aria-hidden="true">×</span></button>
                                        </div>
                                        <div _ngcontent-yfk-c40="" class="modal-body">
                                            <datos-items-fragment _ngcontent-yfk-c40="" _nghost-yfk-c39="">
                                                <div _ngcontent-yfk-c39="" class="row">
                                                    <div _ngcontent-yfk-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                                        <div _ngcontent-yfk-c39="" class="input-group input-group-sm"><input _ngcontent-yfk-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-yfk-c39="" class="input-group-btn"><button _ngcontent-yfk-c39="" class="btn btn-primary" type="button"><span _ngcontent-yfk-c39="" class="fa fa-search"></span></button></span></div>
                                                    </div><br _ngcontent-yfk-c39=""><br _ngcontent-yfk-c39="">
                                                </div>
                                                <div _ngcontent-yfk-c39="" class="table-responsive">
                                                    <table _ngcontent-yfk-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                                        <thead _ngcontent-yfk-c39="">
                                                            <!---->
                                                            <tr _ngcontent-yfk-c39="">
                                                                <!---->
                                                                <!---->
                                                                <th _ngcontent-yfk-c39="" class="text-center"></th>
                                                                <th _ngcontent-yfk-c39="" class="text-center">#</th>
                                                                <th _ngcontent-yfk-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                                                <th _ngcontent-yfk-c39="" class="text-center">Unidad de Medida</th>
                                                                <th _ngcontent-yfk-c39="" class="text-center">Cantidad</th>
                                                                <!---->
                                                                <th _ngcontent-yfk-c39="" class="text-center">
                                                                    <!----> Precio Referencial Unitario
                                                                    <!---->
                                                                </th>
                                                                <!---->
                                                                <!---->
                                                                <th _ngcontent-yfk-c39="" class="text-center">
                                                                    <!----> Precio Referencial Total
                                                                    <!---->
                                                                </th>
                                                                <!---->
                                                                <!---->
                                                                <!---->
                                                                <!---->
                                                                <!---->
                                                            </tr>
                                                        </thead>
                                                        <tbody _ngcontent-yfk-c39="">
                                                            <!---->
                                                            <!---->
                                                        </tbody>
                                                        <tfoot _ngcontent-yfk-c39="">
                                                            <!---->
                                                            <!---->
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </datos-items-fragment>
                                        </div>
                                        <div _ngcontent-yfk-c40="" class="modal-footer"><button _ngcontent-yfk-c40="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-yfk-c40="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button></div>
                                    </div>
                                </div>
                            </div>
                        </seleccion-items-modal>
                    </div>
                </div>
                <!---->
                <datos-cronograma-modal _ngcontent-yfk-c44="" id="idCronDModal" _nghost-yfk-c25="">
                    <div _ngcontent-yfk-c25="" bsmodal="" class="modal fade">
                        <div _ngcontent-yfk-c25="" class="modal-dialog modal-lg">
                            <div _ngcontent-yfk-c25="" class="modal-content">
                                <div _ngcontent-yfk-c25="" class="modal-header text-center">
                                    <h4 _ngcontent-yfk-c25="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-yfk-c25="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-yfk-c25="" aria-hidden="true">×</span></button>
                                </div>
                                <div _ngcontent-yfk-c25="" class="modal-body">
                                    <div _ngcontent-yfk-c25="" class="row">
                                        <div _ngcontent-yfk-c25="" class="col-lg-12 col-md-12">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div _ngcontent-yfk-c25="" class="modal-footer"><button _ngcontent-yfk-c25="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                            </div>
                        </div>
                    </div>
                </datos-cronograma-modal>
                <prv-propuesta-econ-lote-modal _ngcontent-yfk-c44="" id="idPropEconLoteModal" _nghost-yfk-c46="">
                    <div _ngcontent-yfk-c46="" bsmodal="" class="modal fade">
                        <div _ngcontent-yfk-c46="" class="modal-dialog modal-xl">
                            <div _ngcontent-yfk-c46="" class="modal-content">
                                <div _ngcontent-yfk-c46="" class="modal-header text-center">
                                    <!---->
                                    <!---->
                                    <h4 _ngcontent-yfk-c46="" class="text-color-blanco w-100"> Registro de Precios
                                        <!---->
                                    </h4><button _ngcontent-yfk-c46="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-yfk-c46="" aria-hidden="true">×</span></button>
                                </div>
                                <div _ngcontent-yfk-c46="" class="modal-body">
                                    <!---->
                                    <!---->
                                    <datos-items-fragment _ngcontent-yfk-c46="" _nghost-yfk-c39="">
                                        <div _ngcontent-yfk-c39="" class="row">
                                            <div _ngcontent-yfk-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                                <div _ngcontent-yfk-c39="" class="input-group input-group-sm"><input _ngcontent-yfk-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-yfk-c39="" class="input-group-btn"><button _ngcontent-yfk-c39="" class="btn btn-primary" type="button"><span _ngcontent-yfk-c39="" class="fa fa-search"></span></button></span></div>
                                            </div><br _ngcontent-yfk-c39=""><br _ngcontent-yfk-c39="">
                                        </div>
                                        <div _ngcontent-yfk-c39="" class="table-responsive">
                                            <table _ngcontent-yfk-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                                <thead _ngcontent-yfk-c39="">
                                                    <!---->
                                                    <tr _ngcontent-yfk-c39="">
                                                        <!---->
                                                        <!---->
                                                        <!---->
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center border-right-color" colspan="6">Definido por la Entidad</th>
                                                        <th _ngcontent-yfk-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                                                    </tr>
                                                    <tr _ngcontent-yfk-c39="">
                                                        <!---->
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center">#</th>
                                                        <th _ngcontent-yfk-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                                        <th _ngcontent-yfk-c39="" class="text-center">Unidad de Medida</th>
                                                        <th _ngcontent-yfk-c39="" class="text-center">Cantidad</th>
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center">
                                                            <!----> Precio Referencial Unitario
                                                            <!---->
                                                        </th>
                                                        <!---->
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center">
                                                            <!----> Precio Referencial Total
                                                            <!---->
                                                        </th>
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center"> Precio Unitario Ofertado</th>
                                                        <!---->
                                                        <th _ngcontent-yfk-c39="" class="text-center"> Precio Total Ofertado</th>
                                                        <!---->
                                                        <!---->
                                                        <!---->
                                                    </tr>
                                                </thead>
                                                <tbody _ngcontent-yfk-c39="">
                                                    <!---->
                                                    <!---->
                                                </tbody>
                                                <tfoot _ngcontent-yfk-c39="">
                                                    <!---->
                                                    <!---->
                                                </tfoot>
                                            </table>
                                        </div>
                                    </datos-items-fragment>
                                </div>
                                <div _ngcontent-yfk-c46="" class="modal-footer"><button _ngcontent-yfk-c46="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-yfk-c46="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>
                            </div>
                        </div>
                    </div>
                </prv-propuesta-econ-lote-modal>
            </prv-propuestas-economica-screen>
            <div _ngcontent-yfk-c28="" class="h100"></div>
            <botones-opciones-footer _ngcontent-yfk-c28="" _nghost-yfk-c23="">
                <div _ngcontent-yfk-c23="" class="row">
                    <div _ngcontent-yfk-c23="" class="col-12 text-right">
                        <!---->
                        <!----><a onclick="page_reg_nuevo_documento_p1();" _ngcontent-yfk-c23="" class="fa-stack fa-lg cursor-pointer"><i _ngcontent-yfk-c23="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-yfk-c23="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                        <!----><a onclick="page_reg_nuevo_documento_p3();" _ngcontent-yfk-c23="" class="fa-stack fa-lg cursor-pointer"><i _ngcontent-yfk-c23="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-yfk-c23="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
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