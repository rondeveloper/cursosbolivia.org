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

?>
<!---->
<datos-totales-fragment _ngcontent-mbu-c35="" _nghost-mbu-c36="">
    <div _ngcontent-mbu-c36="" class="card b">
        <div _ngcontent-mbu-c36="" class="card-header d-flex align-items-center">
            <div _ngcontent-mbu-c36="" class="d-flex col p-0">
                <h4 _ngcontent-mbu-c36="" class="card-title"> Total General</h4>
            </div>
            <div _ngcontent-mbu-c36="" class="d-flex justify-content-end"><b _ngcontent-mbu-c36="">Precio Total Ofertado:</b> 0.00 </div>
        </div>
    </div>
</datos-totales-fragment>
<!---->
<div _ngcontent-mbu-c35="" class="d-flex col p-0">
    <h4 _ngcontent-mbu-c35="" class="card-title">
        <!----><button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_nuevo_item__modal()" _ngcontent-mbu-c35="" class="btn btn-primary btn-sm" type="button"><i _ngcontent-mbu-c35="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Ítem</button>
    </h4>
</div>
<div _ngcontent-mbu-c35="" class="card b">
    <div _ngcontent-mbu-c35="" class="card-header bb">
        <h4 _ngcontent-mbu-c35="" class="card-title">
            <!---->
            <div _ngcontent-mbu-c35="" class="btn-group" dropdown="">
                <button onclick="dropdown_btn_opciones()" _ngcontent-mbu-c35="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-5"><span _ngcontent-mbu-c35="" class="fa fa-cog text-primary"></span></button>
                <!---->
                <ul id="dropdown-autoclose1" _ngcontent-mbu-c35="" class="dropdown-menu show" role="menu" style="display:none;;inset: 100% auto auto 0px; transform: translateY(0px);">
                    <a onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_editar_propuesta_economica_modal()" _ngcontent-mbu-c35="" class="dropdown-item cursor-pointer text-dark"><span _ngcontent-mbu-c35="" class="fa fa-edit text-primary"></span> Editar Propuesta Económica </a>
                </ul>
            </div>
            <!----> Registro de Precios
        </h4>
    </div>
    <div _ngcontent-mbu-c35="" class="card-body bt bb">
        <datos-items-fragment _ngcontent-mbu-c35="" _nghost-mbu-c39="">
            <div _ngcontent-mbu-c39="" class="row">
                <div _ngcontent-mbu-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                    <div _ngcontent-mbu-c39="" class="input-group input-group-sm"><input _ngcontent-mbu-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-mbu-c39="" class="input-group-btn"><button _ngcontent-mbu-c39="" class="btn btn-primary" type="button"><span _ngcontent-mbu-c39="" class="fa fa-search"></span></button></span></div>
                </div><br _ngcontent-mbu-c39=""><br _ngcontent-mbu-c39="">
            </div>
            <div _ngcontent-mbu-c39="" class="table-responsive">
                <table _ngcontent-mbu-c39="" class="table table-bordered table-sm table-hover table-striped">
                    <thead _ngcontent-mbu-c39="">
                        <!---->
                        <tr _ngcontent-mbu-c39="">
                            <!---->
                            <th _ngcontent-mbu-c39=""></th>
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center border-right-color" colspan="5">Definido por la Entidad</th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                        </tr>
                        <tr _ngcontent-mbu-c39="">
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">#</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Descripción del Bien o Servicio</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Unidad de Medida</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Cantidad</th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">
                                <!---->
                                <!----> Precio Unitario del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">
                                <!---->
                                <!----> Precio Total del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center"> Precio Unitario Ofertado</th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center"> Precio Total Ofertado</th>
                            <!---->
                            <!---->
                            <!---->
                        </tr>
                    </thead>
                    <tbody _ngcontent-mbu-c39="">
                        <!---->
                        <?php
                        $rqit1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                        $cnt = 1;
                        while ($rqit2 = fetch($rqit1)) {
                            ?>
                                <tr _ngcontent-mbu-c39="">
                                    <!---->
                                    <!---->
                                    <td _ngcontent-mbu-c39="" class="text-center"><?php echo $cnt++; ?></td>
                                    <td _ngcontent-mbu-c39="">
                                        <?php echo $rqit2['descripcion']; ?>
                                    </td>
                                    <td _ngcontent-mbu-c39=""><?php echo $rqit2['unidad_medida']; ?></td>
                                    <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['cantidad']; ?></td>
                                    <!---->
                                    <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['precio_unitario']; ?></td>
                                    <!---->
                                    <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['precio_total']; ?></td>
                                    <!---->
                                    <td _ngcontent-mbu-c39="" class="text-right">
                                        <!----><span _ngcontent-mbu-c39=""><?php echo $rqit2['precio_ofertado']; ?></span>
                                        <!---->
                                    </td>
                                    <!---->
                                    <td _ngcontent-mbu-c39="" class="text-right"><span _ngcontent-mbu-c39=""><?php echo $rqit2['precio_ofertado']*$rqit2['cantidad']; ?></span></td>
                                    <!---->
                                    <!---->
                                </tr>
                            <?php
                        }
                        ?>
                        <!---->
                        <!---->
                    </tbody>
                    <tfoot _ngcontent-mbu-c39="">
                        <!---->
                        <!---->
                        <tr _ngcontent-mbu-c39="" style="height: 110px;"></tr>
                    </tfoot>
                </table>
            </div>
        </datos-items-fragment>
    </div>
</div>
<!---->
<datos-cronograma-modal _ngcontent-mbu-c35="" id="idCronDModal" _nghost-mbu-c32="">
    <div _ngcontent-mbu-c32="" bsmodal="" class="modal fade">
        <div _ngcontent-mbu-c32="" class="modal-dialog modal-lg">
            <div _ngcontent-mbu-c32="" class="modal-content">
                <div _ngcontent-mbu-c32="" class="modal-header text-center">
                    <h4 _ngcontent-mbu-c32="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-mbu-c32="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-mbu-c32="" aria-hidden="true">×</span></button>
                </div>
                <div _ngcontent-mbu-c32="" class="modal-body">
                    <div _ngcontent-mbu-c32="" class="row">
                        <div _ngcontent-mbu-c32="" class="col-lg-12 col-md-12">
                            <!---->
                        </div>
                    </div>
                </div>
                <div _ngcontent-mbu-c32="" class="modal-footer"><button _ngcontent-mbu-c32="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
            </div>
        </div>
    </div>
</datos-cronograma-modal>
<prv-propuesta-econ-lote-modal _ngcontent-mbu-c35="" id="idPropEconLoteModal" _nghost-mbu-c41="">
    <div _ngcontent-mbu-c41="" bsmodal="" class="modal fade">
        <div _ngcontent-mbu-c41="" class="modal-dialog modal-xl">
            <div _ngcontent-mbu-c41="" class="modal-content">
                <div _ngcontent-mbu-c41="" class="modal-header text-center">
                    <!---->
                    <!---->
                    <h4 _ngcontent-mbu-c41="" class="text-color-blanco w-100"> Registro de Precios
                        <!---->
                    </h4><button _ngcontent-mbu-c41="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-mbu-c41="" aria-hidden="true">×</span></button>
                </div>
                <div _ngcontent-mbu-c41="" class="modal-body">
                    <!---->
                    <!---->
                    <datos-items-fragment _ngcontent-mbu-c41="" _nghost-mbu-c39="">
                        <div _ngcontent-mbu-c39="" class="row">
                            <div _ngcontent-mbu-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                <div _ngcontent-mbu-c39="" class="input-group input-group-sm"><input _ngcontent-mbu-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-mbu-c39="" class="input-group-btn"><button _ngcontent-mbu-c39="" class="btn btn-primary" type="button"><span _ngcontent-mbu-c39="" class="fa fa-search"></span></button></span></div>
                            </div><br _ngcontent-mbu-c39=""><br _ngcontent-mbu-c39="">
                        </div>
                        <div _ngcontent-mbu-c39="" class="table-responsive">
                            <table _ngcontent-mbu-c39="" class="table table-bordered table-sm table-hover table-striped">
                                <thead _ngcontent-mbu-c39="">
                                    <!---->
                                    <tr _ngcontent-mbu-c39="">
                                        <!---->
                                        <!---->
                                        <!---->
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center border-right-color" colspan="6">Definido por la Entidad</th>
                                        <th _ngcontent-mbu-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                                    </tr>
                                    <tr _ngcontent-mbu-c39="">
                                        <!---->
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center">#</th>
                                        <th _ngcontent-mbu-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                        <th _ngcontent-mbu-c39="" class="text-center">Unidad de Medida</th>
                                        <th _ngcontent-mbu-c39="" class="text-center">Cantidad</th>
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center">
                                            <!----> Precio Referencial Unitario
                                            <!---->
                                        </th>
                                        <!---->
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center">
                                            <!----> Precio Referencial Total
                                            <!---->
                                        </th>
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center"> Precio Unitario Ofertado</th>
                                        <!---->
                                        <th _ngcontent-mbu-c39="" class="text-center"> Precio Total Ofertado</th>
                                        <!---->
                                        <!---->
                                        <!---->
                                    </tr>
                                </thead>
                                <tbody _ngcontent-mbu-c39="">
                                    <!---->
                                    <!---->
                                </tbody>
                                <tfoot _ngcontent-mbu-c39="">
                                    <!---->
                                    <!---->
                                </tfoot>
                            </table>
                        </div>
                    </datos-items-fragment>
                </div>
                <div _ngcontent-mbu-c41="" class="modal-footer"><button _ngcontent-mbu-c41="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-mbu-c41="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>
            </div>
        </div>
    </div>
</prv-propuesta-econ-lote-modal>