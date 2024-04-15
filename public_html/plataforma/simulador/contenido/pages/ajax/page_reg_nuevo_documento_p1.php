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
    .opciones[_ngcontent-ruw-c23] {
        position: fixed;
        right: 0;
        z-index: 1
    }

    .opciones[_ngcontent-ruw-c23] .btn[_ngcontent-ruw-c23] {
        box-shadow: 0 0 10px #666;
        border: 0;
        margin-top: 20px;
        padding: 0
    }

    .opciones[_ngcontent-ruw-c23] .btn-inverse[_ngcontent-ruw-c23] {
        background: #5d9cec;
        border-radius: 10px 0 0 10px;
        margin-left: 35px;
        padding: 5px
    }

    .opciones[_ngcontent-ruw-c23] .btn-inverse[_ngcontent-ruw-c23]:hover {
        background: #115f77
    }

    .opciones[_ngcontent-ruw-c23] .btn-inverse[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23],
    .opciones[_ngcontent-ruw-c23] .btn[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23] {
        font-size: 23px
    }

    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] {
        box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
        background: #fff;
        border-radius: 5px;
        margin-top: 20px;
        padding: 5px 0 !important
    }

    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] div[_ngcontent-ruw-c23],
    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] span[_ngcontent-ruw-c23] {
        text-align: center
    }

    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] div[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23],
    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] span[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23] {
        font-size: 18px;
        cursor: pointer;
        margin: 3px 10px;
        padding: 5px
    }

    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] div[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23]:hover,
    .opciones[_ngcontent-ruw-c23] .affix[_ngcontent-ruw-c23] span[_ngcontent-ruw-c23] i[_ngcontent-ruw-c23]:hover {
        background: #115f77;
        border-radius: 3px;
        color: #fff;
        padding: 5px
    }

    .popover[_ngcontent-ruw-c23] {
        max-width: 100%
    }

    .cursor-pointer[_ngcontent-ruw-c23] {
        cursor: pointer
    }
</style>

<router-outlet _ngcontent-ruw-c1=""></router-outlet>
<prv-propuestas-screen _nghost-ruw-c51="" class="ng-star-inserted">
    <botones-opciones _ngcontent-ruw-c51="" _nghost-ruw-c23="">
        <div _ngcontent-ruw-c23="" class="opciones affix">
            <!---->
            <!---->
            <div _ngcontent-ruw-c23="" class="btn btn-inverse ng-star-inserted" placement="left" tooltip="Ocultar" aria-describedby="tooltip-300"><i _ngcontent-ruw-c23="" class="fa fa-chevron-circle-right"></i></div>
            <!---->
            <div _ngcontent-ruw-c23="" class="affix">
                <!---->
                <div onclick="pnd_verificar_doc()" _ngcontent-ruw-c23="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-301"><span _ngcontent-ruw-c23="" class="cursor-pointer"><i _ngcontent-ruw-c23="" class="fa fa-check-circle text-primary"></i> Verificar</span></div>
                <!---->
                <div onclick="pnd_elimnar_doc()" _ngcontent-ruw-c23="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-302"><span _ngcontent-ruw-c23="" class="cursor-pointer"><i _ngcontent-ruw-c23="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
                <!---->
            </div>
        </div>
    </botones-opciones>
    <div _ngcontent-ruw-c51="" class="content-heading p5 mb-0">
        <div _ngcontent-ruw-c51="" class="row w-100">
            <div _ngcontent-ruw-c51="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-ruw-c51="" class="col-lg-5 col-12 pt10"> Mis Propuestas Electrónicas </div>
            <div _ngcontent-ruw-c51="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-ruw-c51="" _nghost-ruw-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-ruw-c51="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-ruw-c51="" _nghost-ruw-c42="">
                    <div _ngcontent-ruw-c42="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-ruw-c42="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-ruw-c42="" class="text-center">
                                <div _ngcontent-ruw-c42="" class="text-sm">Febrero</div>
                                <div _ngcontent-ruw-c42="" class="h4 mt-0">26</div>
                            </div>
                        </div>
                        <div _ngcontent-ruw-c42="" class="col-8 rounded-right"><span _ngcontent-ruw-c42="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-ruw-c42="">
                            <div _ngcontent-ruw-c42="" class="h4 mt-0">17:25:25</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-ruw-c51="" class="row">
        <div _ngcontent-ruw-c51="" class="col-lg-6 offset-lg-3">
            <wizard _ngcontent-ruw-c51="" class="w-100" _nghost-ruw-c24="">
                <div _ngcontent-ruw-c24="" class="d-flex justify-content-around bd-highlight mb-0">
                    <!---->
                    <div onclick="pnd_press_select_proceso();" _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 1" aria-describedby="tooltip-303"><i _ngcontent-ruw-c24="" class="fas fa-file-alt"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Datos Generales</span></div>
                    <div onclick="page_reg_nuevo_documento_p1();" _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 2" aria-describedby="tooltip-304"><i _ngcontent-ruw-c24="" class="fas fa-upload"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-active">Documentos Adjuntos</span></div>
                    <div onclick="page_reg_nuevo_documento_p2();" _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 3" aria-describedby="tooltip-305"><i _ngcontent-ruw-c24="" class="fas fa-money-bill"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Registro de Precios</span></div>
                    <div onclick="page_reg_nuevo_documento_p3();" _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 4" aria-describedby="tooltip-306"><i style="font-family: cursive;font-size: 11pt;">M</i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Márgenes de Preferencia</span></div>
                    <div onclick="page_reg_nuevo_documento_p4();" _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 5" aria-describedby="tooltip-307"><i _ngcontent-yfk-c30="" class="fa fa-calendar"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Plazo de entrega</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-ruw-c51="" class="row">
        <div _ngcontent-ruw-c51="" class="col-lg-12">
            <mensaje-documento-fragment _ngcontent-ruw-c51="" _nghost-ruw-c52="">
                <!---->
                <!---->
                <alert _ngcontent-ruw-c52="" type="dark" class="ng-star-inserted">
                    <!---->
                    <div role="alert" class="alert alert-dark ng-star-inserted">
                        <!----><em _ngcontent-ruw-c52="" class="fa fa-exclamation-circle fa-lg fa-fw"></em>
                        <!----><span _ngcontent-ruw-c52="" class="ng-star-inserted"><b _ngcontent-ruw-c52="">Los documentos adjuntos son encriptados y podrán ser desencriptados por la entidad en la fecha y hora de apertura de propuestas</b></span>
                        <!---->
                    </div>
                </alert>
            </mensaje-documento-fragment>
        </div>
    </div>
    <div _ngcontent-ruw-c51="" class="row">
        <!---->
        <div _ngcontent-ruw-c51="" class="col-lg-3 ng-star-inserted">
            <datos-generales-lateral-fragment _ngcontent-ruw-c51="" _nghost-ruw-c53="">
                <div _ngcontent-ruw-c53="" class="card b">
                    <div _ngcontent-ruw-c53="" class="card-header d-flex align-items-center">
                        <div _ngcontent-ruw-c53="" class="d-flex col p-0">
                            <h4 _ngcontent-ruw-c53="" class="card-title">Datos Generales</h4>
                        </div>
                        <div _ngcontent-ruw-c53="" class="d-flex justify-content-end"><button _ngcontent-ruw-c53="" class="btn btn-link hidden-lg"><em _ngcontent-ruw-c53="" class="fa fa-minus text-muted"></em></button></div>
                    </div>
                    <div _ngcontent-ruw-c53="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
                            <scrollable _ngcontent-ruw-c53="" class="list-group" height="60vh" style="overflow: hidden;overflow-y: scroll; width: auto; height: 60vh;">
                                <div _ngcontent-ruw-c53="" class="row">
                                    <div _ngcontent-ruw-c53="" class="col-lg-12 col-md-6">
                                        <div _ngcontent-ruw-c53="" class="col-lg-12 col-md-12">
                                            <h5 _ngcontent-ruw-c53="" class="text-muted">Datos de la Propuesta</h5>
                                        </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c53="" class="text-bold">Nro. Documento Propuesta:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21038.0 </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Tipo de Operación:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Presentación de Propuesta/Oferta </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Documento del Proveedor:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-3  col-lg-12"> 2044323014 </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Razón Social del Proveedor:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                                            <!---->
                                        </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Fecha de Elaboración:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 26/02/2021 17:22 </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Estado:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> ELABORADO </div>
                                    </div>
                                    <div _ngcontent-ruw-c53="" class="col-lg-12 col-md-6">
                                        <div _ngcontent-ruw-c53="" class="col-lg-12 col-md-12 mt-2">
                                            <h5 _ngcontent-ruw-c53="" class="text-muted">Datos del Proceso</h5>
                                        </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c53="" class="text-bold">CUCE:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21-0513-00-1114217-1-1 </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Objeto de Contratación:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                            <text-length _ngcontent-ruw-c53="" _nghost-ruw-c40="">ADQUISICION DE EQUIPOS DE COMPUTACION PORTATIL
                                                <!---->
                                            </text-length>
                                        </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Modalidad:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> LP </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Tipo de Contratación:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Bienes </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Forma de Adjudicación:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Por Items </div>
                                        <!---->
                                        <!---->
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 ng-star-inserted"><label _ngcontent-ruw-c53="" class="text-bold">Método de Selección y Adjudicación:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ng-star-inserted"> Precio evaluado más bajo </div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-ruw-c53="" class="text-bold">Moneda:</label></div>
                                        <div _ngcontent-ruw-c53="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> BOLIVIANOS </div>
                                    </div>
                                </div>
                            </scrollable>
                            <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 185.689px;"></div>
                            <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                        </div>
                    </div>
                </div>
            </datos-generales-lateral-fragment>
        </div>
        <div _ngcontent-ruw-c51="" class="col-lg-9">
            <router-outlet _ngcontent-ruw-c51=""></router-outlet>
            <prv-propuestas-tecnica-screen _nghost-ruw-c55="" class="ng-star-inserted">
                <!---->
                <!---->
                <prv-propuestas-archivos-doc-fragment _ngcontent-ruw-c55="" _nghost-ruw-c56="" class="ng-star-inserted">
                    <div _ngcontent-ruw-c56="" class="card b">
                        <div _ngcontent-ruw-c56="" class="card-header bb">
                            <h4 _ngcontent-ruw-c56="" class="card-title">Documentos Legales o Administrativos</h4>
                        </div>
                        <div _ngcontent-ruw-c56="" class="card-body bt bb">
                            <!----><button onclick="prnd_nuevo_doc_leg_ad();" _ngcontent-ruw-c56="" class="btn btn-primary btn-sm ng-star-inserted" type="button"><i _ngcontent-ruw-c56="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Documento</button>
                            <div id="id-prnd_table-doc_leg_ad">
                                <table _ngcontent-ruw-c56="" class="table table-bordered table-sm table-striped">
                                    <thead _ngcontent-ruw-c56="">
                                        <tr _ngcontent-ruw-c56="">
                                            <th _ngcontent-ruw-c56="" class="text-center w-cog" style="width: 80px;">Opciones</th>
                                            <th _ngcontent-ruw-c56="" class="text-center">Documentos Adjuntos</th>
                                        </tr>
                                    </thead>
                                    <tbody _ngcontent-ruw-c56="">
                                        <!---->
                                        <?php
                                        $id_usuario = usuario('id_sim');
                                        $codigo_doc = 'doc_leg_ad';
                                        $cuce = '21-0513-00-1114217-1-1';
                                        $rqdas1 = query("SELECT * FROM simulador_files WHERE cuce='$cuce' AND id_usuario='$id_usuario' AND codigo='$codigo_doc' ");
                                        if (num_rows($rqdas1) == 0) {
                                        ?>
                                            <!---->
                                            <tr _ngcontent-ruw-c56="" class="ng-star-inserted">
                                                <td _ngcontent-ruw-c56="" colspan="2">No hay registro de documentos adjuntos</td>
                                            </tr>
                                        <?php
                                        }
                                        while ($rqdas2 = fetch($rqdas1)) {
                                        ?>
                                            <tr _ngcontent-rov-c36="">
                                                <td _ngcontent-rov-c36="" class="text-center">
                                                    <div _ngcontent-rov-c36="" class="btn-group" dropdown="">
                                                        <button onclick="pmd_dropdown_1(<?php echo $rqdas2['id']; ?>);" _ngcontent-rov-c36="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-10">
                                                            <span _ngcontent-rov-c36="" class="fa fa-cog text-primary"></span>
                                                        </button>
                                                        <!---->
                                                        <ul id="id-sw_pmd_dropdown_1-<?php echo $rqdas2['id']; ?>" style="display: none;" _ngcontent-crt-c37="" class="dropdown-menu ng-star-inserted show" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);">
                                                            <!---->
                                                            <a onclick="prnd_eliminar_archivo(<?php echo $rqdas2['id']; ?>);" _ngcontent-crt-c37="" class="dropdown-item text-dark ng-star-inserted"><span _ngcontent-crt-c37="" class="fa fa-trash"></span> Eliminar archivo </a>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td _ngcontent-rov-c36=""><a _ngcontent-rov-c36="" href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqdas2['nombre']; ?>" target="_blank">Formulario A-1, A-2 u otros(<?php echo $rqdas2['descripcion']; ?>)</a></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </prv-propuestas-archivos-doc-fragment>
                <!---->
                <div _ngcontent-ruw-c55="" class="card b ng-star-inserted">
                    <div _ngcontent-ruw-c55="" class="card-header bb">
                        <h4 _ngcontent-ruw-c55="" class="card-title">
                            <!----> Documentos de Propuesta Técnica
                            <!---->
                        </h4>
                    </div>
                    <div _ngcontent-ruw-c55="" class="card-body bt bb">
                        <!----><button onclick="prnd_proptec_nuevo_item();" _ngcontent-ruw-c55="" class="btn btn-primary btn-sm ng-star-inserted" type="button"><i _ngcontent-ruw-c55="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Ítem</button>
                        <datos-items-fragment _ngcontent-ruw-c55="" _nghost-ruw-c59="">
                            <div _ngcontent-ruw-c59="" class="row">
                                <div _ngcontent-ruw-c59="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                    <div _ngcontent-ruw-c59="" class="input-group input-group-sm"><input _ngcontent-ruw-c59="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-ruw-c59="" class="input-group-btn"><button _ngcontent-ruw-c59="" class="btn btn-primary" type="button"><span _ngcontent-ruw-c59="" class="fa fa-search"></span></button></span></div>
                                </div><br _ngcontent-ruw-c59=""><br _ngcontent-ruw-c59="">
                            </div>
                            <div _ngcontent-ruw-c59="" class="table-responsive">
                                <div id="id-prnd_table-items">
                                    <table _ngcontent-pcb-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                        <thead _ngcontent-pcb-c39="">
                                            <!---->
                                            <tr _ngcontent-pcb-c39="">
                                                <!---->
                                                <th _ngcontent-pcb-c39="" class="text-center"></th>
                                                <!---->
                                                <th _ngcontent-pcb-c39="" class="text-center">#</th>
                                                <th _ngcontent-pcb-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                                <th _ngcontent-pcb-c39="" class="text-center">Unidad de Medida</th>
                                                <th _ngcontent-pcb-c39="" class="text-center">Cantidad</th>
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                                <th _ngcontent-pcb-c39="" class="text-center">Documentos adjuntos</th>
                                                <!---->
                                                <!---->
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-pcb-c39="">
                                            <?php
                                            $id_usuario = usuario('id_sim');
                                            $cuce = '21-0513-00-1114217-1-1';
                                            $rqitm1 = query("SELECT * FROM simulador_items WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
                                            $cnt = 1;
                                            if (num_rows($rqitm1) == 0) {
                                            ?>
                                                <tr _ngcontent-ruw-c59="" class="ng-star-inserted">
                                                    <td _ngcontent-ruw-c59="" colspan="20">No hay registro de Ítems</td>
                                                </tr>
                                            <?php
                                            }
                                            while ($rqitm2 = fetch($rqitm1)) {
                                            ?>
                                                <tr _ngcontent-pcb-c39="">
                                                    <!---->
                                                    <td _ngcontent-pcb-c39="" class="text-center w-cog">
                                                        <div _ngcontent-pcb-c39="" class="btn-group" dropdown="">
                                                            <button onclick="dropdown_prnd_item(<?php echo $rqitm2['id']; ?>);" _ngcontent-pcb-c39="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-14">
                                                                <span _ngcontent-pcb-c39="" class="fa fa-cog text-primary"></span>
                                                            </button>
                                                            <!---->
                                                            <ul id="id-dropdown_prnd_item-<?php echo $rqitm2['id']; ?>" _ngcontent-lsr-c39="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                                                                <!----><a onclick="prnd_items_doctec(<?php echo $rqitm2['id']; ?>);dropdown_prnd_item(<?php echo $rqitm2['id']; ?>);" _ngcontent-lsr-c39="" class="dropdown-item text-dark"><span _ngcontent-lsr-c39="" class="fa fa-upload text-primary"></span> Documentos Adjuntos </a>
                                                                <!---->
                                                                <!----><a _ngcontent-lsr-c39="" class="dropdown-item text-dark"><span _ngcontent-lsr-c39="" class="fa fa-trash text-danger"></span> Eliminar </a>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <!---->
                                                    <td _ngcontent-pcb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                                                    <td _ngcontent-pcb-c39=""><?php echo $rqitm2['descripcion']; ?></td>
                                                    <td _ngcontent-pcb-c39=""><?php echo $rqitm2['unidad_medida']; ?></td>
                                                    <td _ngcontent-pcb-c39="" class="text-right"><?php echo $rqitm2['cantidad']; ?></td>
                                                    <!---->
                                                    <!---->
                                                    <!---->
                                                    <!---->
                                                    <!---->
                                                    <td _ngcontent-pcb-c39="">
                                                        <?php
                                                        $codigo_doc = 'item_' . $rqitm2['id'];
                                                        $id_usuario = usuario('id_sim');
                                                        $rqfi1 = query("SELECT * FROM simulador_files WHERE codigo='$codigo_doc' AND id_usuario='$id_usuario' ");
                                                        if (num_rows($rqfi1) == 0) {
                                                        ?>
                                                            <!----><a onclick="prnd_items_doctec(<?php echo $rqitm2['id']; ?>);" _ngcontent-pcb-c39="" class="text-danger">Sin Doc. Adjuntos</a>
                                                        <?php
                                                        }
                                                        while ($rqfi2 = fetch($rqfi1)) {
                                                        ?>
                                                            <a href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqfi2['nombre']; ?>" target="_blank"><?php echo $rqfi2['descripcion']; ?></a>
                                                            <br>
                                                        <?php
                                                        }
                                                        ?>
                                                        <!---->
                                                    </td>
                                                    <!---->
                                                    <!---->
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot _ngcontent-pcb-c39="">
                                            <!---->
                                            <!---->
                                            <tr _ngcontent-pcb-c39="" style="height: 110px;"></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </datos-items-fragment>
                        <seleccion-items-modal _ngcontent-ruw-c55="" id="idSeleccionItemsModal" _nghost-ruw-c60="">
                            <div _ngcontent-ruw-c60="" bsmodal="" class="modal fade">
                                <div _ngcontent-ruw-c60="" class="modal-dialog modal-lg">
                                    <div _ngcontent-ruw-c60="" class="modal-content">
                                        <div _ngcontent-ruw-c60="" class="modal-header text-center">
                                            <h4 _ngcontent-ruw-c60="" class="text-color-blanco w-100"> Selección de undefineds a participar </h4><button _ngcontent-ruw-c60="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c60="" aria-hidden="true">×</span></button>
                                        </div>
                                        <div _ngcontent-ruw-c60="" class="modal-body">
                                            <datos-items-fragment _ngcontent-ruw-c60="" _nghost-ruw-c59="">
                                                <div _ngcontent-ruw-c59="" class="row">
                                                    <div _ngcontent-ruw-c59="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                                        <div _ngcontent-ruw-c59="" class="input-group input-group-sm"><input _ngcontent-ruw-c59="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-ruw-c59="" class="input-group-btn"><button _ngcontent-ruw-c59="" class="btn btn-primary" type="button"><span _ngcontent-ruw-c59="" class="fa fa-search"></span></button></span></div>
                                                    </div><br _ngcontent-ruw-c59=""><br _ngcontent-ruw-c59="">
                                                </div>
                                                <div _ngcontent-ruw-c59="" class="table-responsive">
                                                    <table _ngcontent-ruw-c59="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                                        <thead _ngcontent-ruw-c59="">
                                                            <!---->
                                                            <tr _ngcontent-ruw-c59="">
                                                                <!---->
                                                                <!---->
                                                                <th _ngcontent-ruw-c59="" class="text-center ng-star-inserted"></th>
                                                                <th _ngcontent-ruw-c59="" class="text-center">#</th>
                                                                <th _ngcontent-ruw-c59="" class="text-center">Descripción del Bien o Servicio</th>
                                                                <th _ngcontent-ruw-c59="" class="text-center">Unidad de Medida</th>
                                                                <th _ngcontent-ruw-c59="" class="text-center">Cantidad</th>
                                                                <!---->
                                                                <th _ngcontent-ruw-c59="" class="text-center ng-star-inserted">
                                                                    <!----> Precio Referencial Unitario
                                                                    <!---->
                                                                </th>
                                                                <!---->
                                                                <!---->
                                                                <th _ngcontent-ruw-c59="" class="text-center ng-star-inserted">
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
                                                        <tbody _ngcontent-ruw-c59="">
                                                            <!---->
                                                            <!---->
                                                        </tbody>
                                                        <tfoot _ngcontent-ruw-c59="">
                                                            <!---->
                                                            <!---->
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </datos-items-fragment>
                                        </div>
                                        <div _ngcontent-ruw-c60="" class="modal-footer"><button _ngcontent-ruw-c60="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-ruw-c60="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button></div>
                                    </div>
                                </div>
                            </div>
                        </seleccion-items-modal>
                    </div>
                </div>
                <!---->
                <datos-cronograma-modal _ngcontent-ruw-c55="" id="idCronTecModal" _nghost-ruw-c48="">
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
                <propuesta-archivos-modal _ngcontent-ruw-c55="" id="idModalPropuestaArchivos" _nghost-ruw-c61="">
                    <div _ngcontent-ruw-c61="" bsmodal="" class="modal fade">
                        <div _ngcontent-ruw-c61="" class="modal-dialog modal-lg">
                            <div _ngcontent-ruw-c61="" class="modal-content">
                                <div _ngcontent-ruw-c61="" class="modal-header text-center">
                                    <h4 _ngcontent-ruw-c61="" class="text-color-blanco w-100"> Documentos Adjuntos </h4><button _ngcontent-ruw-c61="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c61="" aria-hidden="true">×</span></button>
                                </div>
                                <div _ngcontent-ruw-c61="" class="modal-body">
                                    <div _ngcontent-ruw-c61="" class="row">
                                        <!---->
                                    </div>
                                    <div _ngcontent-ruw-c61="" class="row">
                                        <div _ngcontent-ruw-c61="" class="col-lg-12 col-md-12">
                                            <!---->
                                            <!---->
                                            <div _ngcontent-ruw-c61="" class="row">
                                                <div _ngcontent-ruw-c61="" class="col">
                                                    <spinner-http _ngcontent-ruw-c61="" _nghost-ruw-c13="">
                                                        <!---->
                                                    </spinner-http>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div _ngcontent-ruw-c61="" class="modal-footer"><button _ngcontent-ruw-c61="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
                            </div>
                        </div>
                    </div>
                    <propuesta-adjuntar-archivo-modal _ngcontent-ruw-c61="" id="idModalPropuestaAdjuntarArchivo" _nghost-ruw-c62="">
                        <div _ngcontent-ruw-c62="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1">
                            <div _ngcontent-ruw-c62="" class="modal-dialog modal-md">
                                <form _ngcontent-ruw-c62="" name="formArchivo" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                    <div _ngcontent-ruw-c62="" class="modal-content">
                                        <div _ngcontent-ruw-c62="" class="modal-header text-center">
                                            <h4 _ngcontent-ruw-c62="" class="text-color-blanco w-100"> Documento Adjunto </h4><button _ngcontent-ruw-c62="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c62="" aria-hidden="true">×</span></button>
                                        </div>
                                        <div _ngcontent-ruw-c62="" class="modal-body">
                                            <div _ngcontent-ruw-c62="" class="form-group">
                                                <!---->
                                                <!---->
                                                <!---->
                                                <!---->
                                            </div>
                                            <div _ngcontent-ruw-c62="" class="card b">
                                                <div _ngcontent-ruw-c62="" class="card-body">
                                                    <h5 _ngcontent-ruw-c62=""><i _ngcontent-ruw-c62="" class="fa fa-info-circle text-primary"></i> INSTRUCCIONES:</h5>
                                                    <ul _ngcontent-ruw-c62="" class="text-muted p-0 list-unstyled">
                                                        <li _ngcontent-ruw-c62="">El tamaño de cada documento no debe ser mayor a 30 MB.</li>
                                                        <li _ngcontent-ruw-c62=""> Las extensiones permitidas son:&nbsp;
                                                            <!----><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Word (.doc) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Word (.docx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Excel (.xls) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Excel (.xlsx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo PDF (.pdf) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo JPG (.jpg) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo PNG (.png) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo BMP (.bmp) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.hqx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.7z) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.zip) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.rar) </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div _ngcontent-ruw-c62="" class="row">
                                                <div _ngcontent-ruw-c62="" class="col">
                                                    <spinner-http _ngcontent-ruw-c62="" _nghost-ruw-c13="">
                                                        <!---->
                                                    </spinner-http>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-ruw-c62="" class="modal-footer"><button _ngcontent-ruw-c62="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-ruw-c62="" class="btn btn-primary" type="submit">Aceptar</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </propuesta-adjuntar-archivo-modal>
                </propuesta-archivos-modal>
                <propuesta-adjuntar-archivo-modal _ngcontent-ruw-c55="" id="idModalAdjuntarArchivoDoc" _nghost-ruw-c62="">
                    <div _ngcontent-ruw-c62="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1">
                        <div _ngcontent-ruw-c62="" class="modal-dialog modal-md">
                            <form _ngcontent-ruw-c62="" name="formArchivo" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                <div _ngcontent-ruw-c62="" class="modal-content">
                                    <div _ngcontent-ruw-c62="" class="modal-header text-center">
                                        <h4 _ngcontent-ruw-c62="" class="text-color-blanco w-100"> Documento Adjunto </h4><button _ngcontent-ruw-c62="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c62="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-ruw-c62="" class="modal-body">
                                        <div _ngcontent-ruw-c62="" class="form-group">
                                            <!---->
                                            <!---->
                                            <!---->
                                            <!---->
                                        </div>
                                        <div _ngcontent-ruw-c62="" class="card b">
                                            <div _ngcontent-ruw-c62="" class="card-body">
                                                <h5 _ngcontent-ruw-c62=""><i _ngcontent-ruw-c62="" class="fa fa-info-circle text-primary"></i> INSTRUCCIONES:</h5>
                                                <ul _ngcontent-ruw-c62="" class="text-muted p-0 list-unstyled">
                                                    <li _ngcontent-ruw-c62="">El tamaño de cada documento no debe ser mayor a 30 MB.</li>
                                                    <li _ngcontent-ruw-c62=""> Las extensiones permitidas son:&nbsp;
                                                        <!----><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Word (.doc) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Word (.docx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Excel (.xls) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Excel (.xlsx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo PDF (.pdf) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo JPG (.jpg) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo PNG (.png) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo BMP (.bmp) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.hqx) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.7z) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.zip) </span><span _ngcontent-ruw-c62="" class="ng-star-inserted"> &nbsp;Archivo Comprimido (.rar) </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div _ngcontent-ruw-c62="" class="row">
                                            <div _ngcontent-ruw-c62="" class="col">
                                                <spinner-http _ngcontent-ruw-c62="" _nghost-ruw-c13="">
                                                    <!---->
                                                </spinner-http>
                                            </div>
                                        </div>
                                    </div>
                                    <div _ngcontent-ruw-c62="" class="modal-footer"><button _ngcontent-ruw-c62="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-ruw-c62="" class="btn btn-primary" type="submit">Aceptar</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </propuesta-adjuntar-archivo-modal>
            </prv-propuestas-tecnica-screen>
            <div _ngcontent-ruw-c51="" class="h100"></div>
            <botones-opciones-footer _ngcontent-ruw-c51="" _nghost-ruw-c20="">
                <div _ngcontent-ruw-c20="" class="row">
                    <div _ngcontent-ruw-c20="" class="col-12 text-right">
                        <!---->
                        <!----><a onclick="handle_menu();pnd_press_select_proceso();" _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                        <!----><a onclick="page_reg_nuevo_documento_p2();" _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
                    </div>
                </div>
            </botones-opciones-footer>
        </div>
    </div>
    <propuesta-aprobar-modal _ngcontent-ruw-c51="" id="idModalPropuestaAprobar" _nghost-ruw-c54="">
        <div _ngcontent-ruw-c54="" bsmodal="" class="modal fade">
            <div _ngcontent-ruw-c54="" class="modal-dialog modal-lg">
                <div _ngcontent-ruw-c54="" class="modal-content">
                    <div _ngcontent-ruw-c54="" class="modal-header text-center">
                        <h4 _ngcontent-ruw-c54="" class="text-color-blanco w-100">
                            <!---->
                            <!----> POLÍTICAS Y CONDICIONES DE USO
                            <!---->
                        </h4><button _ngcontent-ruw-c54="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c54="" aria-hidden="true">×</span></button>
                    </div>
                    <div _ngcontent-ruw-c54="" class="modal-body">
                        <p _ngcontent-ruw-c54="" class="text-justify">
                            <!---->
                            <!---->
                        <h4 _ngcontent-ruw-c54="" class="ng-star-inserted">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-ruw-c54="" class="ng-star-inserted"> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema.
                        <!---->
                        </p>
                        <div _ngcontent-ruw-c54="" class="border-top"><br _ngcontent-ruw-c54="">
                            <!---->
                            <div _ngcontent-ruw-c54="" class="ng-star-inserted"><span _ngcontent-ruw-c54="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-ruw-c54=""><input _ngcontent-ruw-c54="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-ruw-c54="" class="fa fa-check"></span><b _ngcontent-ruw-c54="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div>
                        </div>
                    </div>
                    <div _ngcontent-ruw-c54="" class="modal-footer"><button _ngcontent-ruw-c54="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button _ngcontent-ruw-c54="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                </div>
            </div>
        </div>
    </propuesta-aprobar-modal>
    <confirmacion-modal _ngcontent-ruw-c51="" id="idModalConfirmacion" _nghost-ruw-c16="">
        <div _ngcontent-ruw-c16="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
            <div _ngcontent-ruw-c16="" class="modal-dialog modal-sm">
                <div _ngcontent-ruw-c16="" class="modal-content">
                    <div _ngcontent-ruw-c16="" class="modal-body">
                        <div _ngcontent-ruw-c16="" class="row">
                            <div _ngcontent-ruw-c16="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                            <div _ngcontent-ruw-c16="" class="col-lg-12 col-md-12 text-center"> </div>
                        </div>
                    </div>
                    <div _ngcontent-ruw-c16="" class="modal-footer"><button _ngcontent-ruw-c16="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-ruw-c16="" type="button" class="btn btn-">Aceptar</button></div>
                </div>
            </div>
        </div>
    </confirmacion-modal>
</prv-propuestas-screen>