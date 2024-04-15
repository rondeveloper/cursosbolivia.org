<style>.opciones[_ngcontent-dgv-c29]{position:fixed;right:0;z-index:1}.opciones[_ngcontent-dgv-c29]   .btn[_ngcontent-dgv-c29]{box-shadow:0 0 10px #666;border:0;margin-top:20px;padding:0}.opciones[_ngcontent-dgv-c29]   .btn-inverse[_ngcontent-dgv-c29]{background:#5d9cec;border-radius:10px 0 0 10px;margin-left:35px;padding:5px}.opciones[_ngcontent-dgv-c29]   .btn-inverse[_ngcontent-dgv-c29]:hover{background:#115f77}.opciones[_ngcontent-dgv-c29]   .btn-inverse[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29], .opciones[_ngcontent-dgv-c29]   .btn[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29]{font-size:23px}.opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]{box-shadow:0 1px 5px rgba(0,0,0,.2);background:#fff;border-radius:5px;margin-top:20px;padding:5px 0!important}.opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   div[_ngcontent-dgv-c29], .opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   span[_ngcontent-dgv-c29]{text-align:center}.opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   div[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29], .opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   span[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29]{font-size:18px;cursor:pointer;margin:3px 10px;padding:5px}.opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   div[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29]:hover, .opciones[_ngcontent-dgv-c29]   .affix[_ngcontent-dgv-c29]   span[_ngcontent-dgv-c29]   i[_ngcontent-dgv-c29]:hover{background:#115f77;border-radius:3px;color:#fff;padding:5px}.popover[_ngcontent-dgv-c29]{max-width:100%}.cursor-pointer[_ngcontent-dgv-c29]{cursor:pointer}</style>
<router-outlet _ngcontent-dgv-c1=""></router-outlet>
<prv-propuestas-screen _nghost-dgv-c28="">
    <botones-opciones _ngcontent-dgv-c28="" _nghost-dgv-c29="">
        <div _ngcontent-dgv-c29="" class="opciones affix">
            <!---->
            <!---->
            <div _ngcontent-dgv-c29="" class="btn btn-inverse" placement="left" tooltip="Ocultar" aria-describedby="tooltip-0"><i _ngcontent-dgv-c29="" class="fa fa-chevron-circle-right"></i></div>
            <!---->
            <div _ngcontent-dgv-c29="" class="affix">
                <!---->
                <div onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_verificar_doc();" _ngcontent-dgv-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-14"><span _ngcontent-dgv-c29="" class="cursor-pointer"><i _ngcontent-dgv-c29="" class="fa fa-check-circle text-primary"></i> Verificar</span></div>
                <!---->
                <div onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc();" _ngcontent-dgv-c29="" class="pr-3 text-left" placement="left" aria-describedby="tooltip-15"><span _ngcontent-dgv-c29="" class="cursor-pointer"><i _ngcontent-dgv-c29="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
                <!---->
            </div>
        </div>
    </botones-opciones>
    <div _ngcontent-dgv-c28="" class="content-heading p5 mb-0">
        <div _ngcontent-dgv-c28="" class="row w-100">
            <div _ngcontent-dgv-c28="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-dgv-c28="" class="col-lg-5 col-12 pt10"> Mis Propuestas Electrónicas </div>
            <div _ngcontent-dgv-c28="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-dgv-c28="" _nghost-dgv-c13="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-dgv-c28="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-dgv-c28="" _nghost-dgv-c14="">
                    <div _ngcontent-dgv-c14="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-dgv-c14="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-dgv-c14="" class="text-center">
                                <div _ngcontent-dgv-c14="" class="text-sm">Julio</div>
                                <div _ngcontent-dgv-c14="" class="h4 mt-0">22</div>
                            </div>
                        </div>
                        <div _ngcontent-dgv-c14="" class="col-8 rounded-right"><span _ngcontent-dgv-c14="" class="text-uppercase h5 m0">Jueves</span><br _ngcontent-dgv-c14="">
                            <div _ngcontent-dgv-c14="" class="h4 mt-0">15:55:31</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-dgv-c28="" class="row">
        <div _ngcontent-dgv-c28="" class="col-lg-6 offset-lg-3">
            <wizard _ngcontent-dgv-c28="" class="w-100" _nghost-dgv-c30="">
                <div _ngcontent-dgv-c30="" class="d-flex justify-content-around bd-highlight mb-0">
                    <!---->
                    <div _ngcontent-dgv-c30="" class="p-2 bd-highlight text-center"><button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso()" _ngcontent-dgv-c30="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 1" aria-describedby="tooltip-4"><i _ngcontent-dgv-c30="" class="fas fa-file-alt"></i></button><br _ngcontent-dgv-c30=""><span _ngcontent-dgv-c30="" class="sp-title-pass">Datos Generales</span></div>
                    <div _ngcontent-dgv-c30="" class="p-2 bd-highlight text-center"><button _ngcontent-dgv-c30="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 3" aria-describedby="tooltip-5"><i _ngcontent-dgv-c30="" class="fas fa-money-bill"></i></button><br _ngcontent-dgv-c30=""><span _ngcontent-dgv-c30="" class="sp-title-active">Registro de Precios</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-dgv-c28="" class="row">
        <div _ngcontent-dgv-c28="" class="col-lg-12">
            <mensaje-documento-fragment _ngcontent-dgv-c28="" _nghost-dgv-c31="">
                <!---->
                <!---->
                <alert _ngcontent-dgv-c31="" type="dark">
                    <!---->
                    <div role="alert" class="alert alert-dark">
                        <!----><em _ngcontent-dgv-c31="" class="fa fa-exclamation-circle fa-lg fa-fw"></em>
                        <!---->
                        <!----><span _ngcontent-dgv-c31=""><b _ngcontent-dgv-c31="">Los precios registrados son encriptados y podrán ser desencriptados por la entidad en la fecha y hora de apertura de propuestas</b></span>
                    </div>
                </alert>
            </mensaje-documento-fragment>
        </div>
    </div>
    <div _ngcontent-dgv-c28="" class="row">
        <!---->
        <div _ngcontent-dgv-c28="" class="col-lg-3">
            <datos-generales-lateral-fragment _ngcontent-dgv-c28="" _nghost-dgv-c32="">
                <div _ngcontent-dgv-c32="" class="card b">
                    <div _ngcontent-dgv-c32="" class="card-header d-flex align-items-center">
                        <div _ngcontent-dgv-c32="" class="d-flex col p-0">
                            <h4 _ngcontent-dgv-c32="" class="card-title">Datos Generales</h4>
                        </div>
                        <div _ngcontent-dgv-c32="" class="d-flex justify-content-end"><button _ngcontent-dgv-c32="" class="btn btn-link hidden-lg"><em _ngcontent-dgv-c32="" class="fa fa-minus text-muted"></em></button></div>
                    </div>
                    <div _ngcontent-dgv-c32="" class="card-body bt collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
                            <scrollable _ngcontent-dgv-c32="" class="list-group" height="60vh" style="overflow-x: hidden;overflow-y: scroll;; width: auto; height: 60vh;">
                                <div _ngcontent-dgv-c32="" class="row">
                                    <div _ngcontent-dgv-c32="" class="col-lg-12 col-md-6">
                                        <div _ngcontent-dgv-c32="" class="col-lg-12 col-md-12">
                                            <h5 _ngcontent-dgv-c32="" class="text-muted">Datos de la Propuesta</h5>
                                        </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dgv-c32="" class="text-bold">Nro. Documento Propuesta:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 64655.0 </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Tipo de Operación:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Presentación de Propuesta/Oferta </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Documento del Proveedor:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-3  col-lg-12"> 2044323014 </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Razón Social del Proveedor:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                            <!----> ALIAGA QUENTA RODOLFO REYNALDO
                                            <!---->
                                        </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Fecha de Elaboración:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 22/07/2021 15:50 </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Estado:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> ELABORADO </div>
                                    </div>
                                    <div _ngcontent-dgv-c32="" class="col-lg-12 col-md-6">
                                        <div _ngcontent-dgv-c32="" class="col-lg-12 col-md-12 mt-2">
                                            <h5 _ngcontent-dgv-c32="" class="text-muted">Datos del Proceso</h5>
                                        </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dgv-c32="" class="text-bold">CUCE:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> 21-0513-00-1151485-1-1 </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Objeto de Contratación:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12">
                                            <text-length _ngcontent-dgv-c32="" _nghost-dgv-c16="">ADQUISICION DE PAPEL DE 75 GRAMOS TAMAÑO CARTA Y
                                                <!----><a _ngcontent-dgv-c16="">Ver más</a>
                                            </text-length>
                                        </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Modalidad:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Contratación Menor </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Tipo de Contratación:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Bienes </div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Forma de Adjudicación:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> Por Items </div>
                                        <!---->
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12 mt-2"><label _ngcontent-dgv-c32="" class="text-bold">Moneda:</label></div>
                                        <div _ngcontent-dgv-c32="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"> BOLIVIANOS </div>
                                    </div>
                                </div>
                            </scrollable>
                            <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 493.453px;"></div>
                            <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                        </div>
                    </div>
                </div>
            </datos-generales-lateral-fragment>
        </div>
        <div _ngcontent-dgv-c28="" class="col-lg-9">
            <router-outlet _ngcontent-dgv-c28=""></router-outlet>
            <prv-propuestas-economica-screen _nghost-dgv-c35="" id="display-content"></prv-propuestas-economica-screen>
            <div _ngcontent-dgv-c28="" class="h100"></div>
            <botones-opciones-footer _ngcontent-dgv-c28="" _nghost-dgv-c23="">
                <div _ngcontent-dgv-c23="" class="row">
                    <div _ngcontent-dgv-c23="" class="col-12 text-right">
                        <!---->
                        <!----><button _ngcontent-dgv-c23="" class="btn btn-oval btn-primary"><i _ngcontent-dgv-c23="" class="fa fa-arrow-left"></i></button>
                    </div>
                </div>
            </botones-opciones-footer>
        </div>
    </div>
    <propuesta-aprobar-modal _ngcontent-dgv-c28="" id="idModalPropuestaAprobar" _nghost-dgv-c33="">
        <div _ngcontent-dgv-c33="" bsmodal="" class="modal fade">
            <div _ngcontent-dgv-c33="" class="modal-dialog modal-lg">
                <div _ngcontent-dgv-c33="" class="modal-content">
                    <div _ngcontent-dgv-c33="" class="modal-header text-center">
                        <h4 _ngcontent-dgv-c33="" class="text-color-blanco w-100">
                            <!---->
                            <!----> POLÍTICAS Y CONDICIONES DE USO
                            <!---->
                        </h4><button _ngcontent-dgv-c33="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-dgv-c33="" aria-hidden="true">×</span></button>
                    </div>
                    <div _ngcontent-dgv-c33="" class="modal-body">
                        <p _ngcontent-dgv-c33="" class="text-justify">
                            <!---->
                            <!---->
                        <h4 _ngcontent-dgv-c33="">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-dgv-c33=""> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema.
                        <!---->
                        </p>
                        <div _ngcontent-dgv-c33="" class="border-top"><br _ngcontent-dgv-c33="">
                            <!---->
                            <div _ngcontent-dgv-c33=""><span _ngcontent-dgv-c33="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-dgv-c33=""><input _ngcontent-dgv-c33="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-dgv-c33="" class="fa fa-check"></span><b _ngcontent-dgv-c33="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div>
                        </div>
                    </div>
                    <div _ngcontent-dgv-c33="" class="modal-footer"><button _ngcontent-dgv-c33="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button _ngcontent-dgv-c33="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>
                </div>
            </div>
        </div>
    </propuesta-aprobar-modal>
    <confirmacion-modal _ngcontent-dgv-c28="" id="idModalConfirmacion" _nghost-dgv-c34="">
        <div _ngcontent-dgv-c34="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
            <div _ngcontent-dgv-c34="" class="modal-dialog modal-sm">
                <div _ngcontent-dgv-c34="" class="modal-content">
                    <div _ngcontent-dgv-c34="" class="modal-body">
                        <div _ngcontent-dgv-c34="" class="row">
                            <div _ngcontent-dgv-c34="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                            <div _ngcontent-dgv-c34="" class="col-lg-12 col-md-12 text-center"> </div>
                        </div>
                    </div>
                    <div _ngcontent-dgv-c34="" class="modal-footer"><button _ngcontent-dgv-c34="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-dgv-c34="" type="button" class="btn btn-">Aceptar</button></div>
                </div>
            </div>
        </div>
    </confirmacion-modal>
</prv-propuestas-screen>