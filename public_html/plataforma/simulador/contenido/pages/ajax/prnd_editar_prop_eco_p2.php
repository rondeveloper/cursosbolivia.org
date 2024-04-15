<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$ids_items = post('ids_items');

$ar1 = explode(',',$ids_items);
foreach ($ar1 as $id_item) {
    if(isset_post('prop-eco-'.$id_item)){
        $precio_ofertado = post('prop-eco-'.$id_item);
        query("UPDATE simulador_items SET precio_ofertado='$precio_ofertado' WHERE id='$id_item' LIMIT 1 ");
    }
}

$cuce = '21-0513-00-1114217-1-1';
$id_usuario = usuario('id_sim');

$rqpto1 = query("SELECT sum(precio_ofertado*cantidad) AS total FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
$rqpto2 = fetch($rqpto1);
$precio_total_ofertado = $rqpto2['total'];

?>

<!---->
<datos-totales-fragment _ngcontent-jun-c43="" _nghost-jun-c44="">
    <div _ngcontent-jun-c44="" class="card b">
        <div _ngcontent-jun-c44="" class="card-header d-flex align-items-center">
            <div _ngcontent-jun-c44="" class="d-flex col p-0">
                <h4 _ngcontent-jun-c44="" class="card-title"> Total General</h4>
            </div>
            <div _ngcontent-jun-c44="" class="d-flex justify-content-end"><b _ngcontent-jun-c44="">Precio Total Ofertado:</b> <?php echo number_format($precio_total_ofertado, 2); ?> </div>
        </div>
    </div>
</datos-totales-fragment>
<!---->
<div _ngcontent-jun-c43="" class="d-flex col p-0">
    <h4 _ngcontent-jun-c43="" class="card-title">
        <!---->
    </h4>
</div>
<div _ngcontent-jun-c43="" class="card b">
    <div _ngcontent-jun-c43="" class="card-header bb">
        <h4 _ngcontent-jun-c43="" class="card-title">
            <!---->
            <div _ngcontent-yfk-c44="" class="btn-group" dropdown="">
                <button onclick="prnd_rp_dropdown_1()" _ngcontent-yfk-c44="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-8"><span _ngcontent-yfk-c44="" class="fa fa-cog text-primary"></span></button>
                <!---->
                <ul id="id-sw_prnd_rp_dropdown_1" _ngcontent-pjb-c43="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);"><a onclick="prnd_editar_prop_eco();" _ngcontent-pjb-c43="" class="dropdown-item cursor-pointer text-dark"><span _ngcontent-pjb-c43="" class="fa fa-edit text-primary"></span> Editar Propuesta Económica </a></ul>
            </div>
            <!----> Registro de Precios
        </h4>
    </div>
    <div _ngcontent-jun-c43="" class="card-body bt bb">
        <datos-items-fragment _ngcontent-jun-c43="" _nghost-jun-c39="">
            <div _ngcontent-jun-c39="" class="row">
                <div _ngcontent-jun-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                    <div _ngcontent-jun-c39="" class="input-group input-group-sm"><input _ngcontent-jun-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-jun-c39="" class="input-group-btn"><button _ngcontent-jun-c39="" class="btn btn-primary" type="button"><span _ngcontent-jun-c39="" class="fa fa-search"></span></button></span></div>
                </div><br _ngcontent-jun-c39=""><br _ngcontent-jun-c39="">
            </div>
            <div _ngcontent-jun-c39="" class="table-responsive">
                <table _ngcontent-jun-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                    <thead _ngcontent-jun-c39="">
                        <!---->
                        <tr _ngcontent-jun-c39="">
                            <!---->
                            <th _ngcontent-jun-c39=""></th>
                            <!---->
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center border-right-color" colspan="5">Definido por la Entidad</th>
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                        </tr>
                        <tr _ngcontent-jun-c39="">
                            <!---->
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center">#</th>
                            <th _ngcontent-jun-c39="" class="text-center">Descripción del Bien o Servicio</th>
                            <th _ngcontent-jun-c39="" class="text-center">Unidad de Medida</th>
                            <th _ngcontent-jun-c39="" class="text-center">Cantidad</th>
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center">
                                <!----> Precio Referencial Unitario
                                <!---->
                            </th>
                            <!---->
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center">
                                <!----> Precio Referencial Total
                                <!---->
                            </th>
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center"> Precio Unitario Ofertado</th>
                            <!---->
                            <th _ngcontent-jun-c39="" class="text-center"> Precio Total Ofertado</th>
                            <!---->
                            <!---->
                            <!---->
                        </tr>
                    </thead>
                    <tbody _ngcontent-pjb-c39="">
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
                    <tfoot _ngcontent-jun-c39="">
                        <!---->
                        <!---->
                        <tr _ngcontent-jun-c39="" style="height: 110px;"></tr>
                    </tfoot>
                </table>
            </div>
        </datos-items-fragment>
        <seleccion-items-modal _ngcontent-jun-c43="" id="idSeleccionItemsModal" _nghost-jun-c40="">
            <div _ngcontent-jun-c40="" bsmodal="" class="modal fade">
                <div _ngcontent-jun-c40="" class="modal-dialog modal-lg">
                    <div _ngcontent-jun-c40="" class="modal-content">
                        <div _ngcontent-jun-c40="" class="modal-header text-center">
                            <h4 _ngcontent-jun-c40="" class="text-color-blanco w-100"> Selección de undefineds a participar </h4><button _ngcontent-jun-c40="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-jun-c40="" aria-hidden="true">×</span></button>
                        </div>
                        <div _ngcontent-jun-c40="" class="modal-body">
                            <datos-items-fragment _ngcontent-jun-c40="" _nghost-jun-c39="">
                                <div _ngcontent-jun-c39="" class="row">
                                    <div _ngcontent-jun-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                        <div _ngcontent-jun-c39="" class="input-group input-group-sm"><input _ngcontent-jun-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-jun-c39="" class="input-group-btn"><button _ngcontent-jun-c39="" class="btn btn-primary" type="button"><span _ngcontent-jun-c39="" class="fa fa-search"></span></button></span></div>
                                    </div><br _ngcontent-jun-c39=""><br _ngcontent-jun-c39="">
                                </div>
                                <div _ngcontent-jun-c39="" class="table-responsive">
                                    <table _ngcontent-jun-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                        <thead _ngcontent-jun-c39="">
                                            <!---->
                                            <tr _ngcontent-jun-c39="">
                                                <!---->
                                                <!---->
                                                <th _ngcontent-jun-c39="" class="text-center"></th>
                                                <th _ngcontent-jun-c39="" class="text-center">#</th>
                                                <th _ngcontent-jun-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                                <th _ngcontent-jun-c39="" class="text-center">Unidad de Medida</th>
                                                <th _ngcontent-jun-c39="" class="text-center">Cantidad</th>
                                                <!---->
                                                <th _ngcontent-jun-c39="" class="text-center">
                                                    <!----> Precio Referencial Unitario
                                                    <!---->
                                                </th>
                                                <!---->
                                                <!---->
                                                <th _ngcontent-jun-c39="" class="text-center">
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
                                        <tbody _ngcontent-jun-c39="">
                                            <!---->
                                            <!---->
                                        </tbody>
                                        <tfoot _ngcontent-jun-c39="">
                                            <!---->
                                            <!---->
                                        </tfoot>
                                    </table>
                                </div>
                            </datos-items-fragment>
                        </div>
                        <div _ngcontent-jun-c40="" class="modal-footer"><button _ngcontent-jun-c40="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-jun-c40="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button></div>
                    </div>
                </div>
            </div>
        </seleccion-items-modal>
    </div>
</div>
<!---->
<datos-cronograma-modal _ngcontent-jun-c43="" id="idCronDModal" _nghost-jun-c32="">
    <div _ngcontent-jun-c32="" bsmodal="" class="modal fade">
        <div _ngcontent-jun-c32="" class="modal-dialog modal-lg">
            <div _ngcontent-jun-c32="" class="modal-content">
                <div _ngcontent-jun-c32="" class="modal-header text-center">
                    <h4 _ngcontent-jun-c32="" class="text-color-blanco w-100"> Cronograma del Proceso </h4><button _ngcontent-jun-c32="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-jun-c32="" aria-hidden="true">×</span></button>
                </div>
                <div _ngcontent-jun-c32="" class="modal-body">
                    <div _ngcontent-jun-c32="" class="row">
                        <div _ngcontent-jun-c32="" class="col-lg-12 col-md-12">
                            <!---->
                        </div>
                    </div>
                </div>
                <div _ngcontent-jun-c32="" class="modal-footer"><button _ngcontent-jun-c32="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button></div>
            </div>
        </div>
    </div>
</datos-cronograma-modal>
<prv-propuesta-econ-lote-modal _ngcontent-jun-c43="" id="idPropEconLoteModal" _nghost-jun-c45="">
    <div _ngcontent-jun-c45="" bsmodal="" class="modal fade" aria-hidden="true" aria-modal="true" style="display: none;">
        <div _ngcontent-jun-c45="" class="modal-dialog modal-xl">
            <div _ngcontent-jun-c45="" class="modal-content">
                <div _ngcontent-jun-c45="" class="modal-header text-center">
                    <!---->
                    <!---->
                    <h4 _ngcontent-jun-c45="" class="text-color-blanco w-100"> Registro de Precios
                        <!---->
                    </h4><button _ngcontent-jun-c45="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-jun-c45="" aria-hidden="true">×</span></button>
                </div>
                <div _ngcontent-jun-c45="" class="modal-body">
                    <!---->
                    <!---->
                    <datos-items-fragment _ngcontent-jun-c45="" _nghost-jun-c39="">
                        <div _ngcontent-jun-c39="" class="row">
                            <div _ngcontent-jun-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                                <div _ngcontent-jun-c39="" class="input-group input-group-sm"><input _ngcontent-jun-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-jun-c39="" class="input-group-btn"><button _ngcontent-jun-c39="" class="btn btn-primary" type="button"><span _ngcontent-jun-c39="" class="fa fa-search"></span></button></span></div>
                            </div><br _ngcontent-jun-c39=""><br _ngcontent-jun-c39="">
                        </div>
                        <div _ngcontent-jun-c39="" class="table-responsive">
                            <table _ngcontent-jun-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                                <thead _ngcontent-jun-c39="">
                                    <!---->
                                    <tr _ngcontent-jun-c39="">
                                        <!---->
                                        <!---->
                                        <!---->
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center border-right-color" colspan="6">Definido por la Entidad</th>
                                        <th _ngcontent-jun-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                                    </tr>
                                    <tr _ngcontent-jun-c39="">
                                        <!---->
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center">#</th>
                                        <th _ngcontent-jun-c39="" class="text-center">Descripción del Bien o Servicio</th>
                                        <th _ngcontent-jun-c39="" class="text-center">Unidad de Medida</th>
                                        <th _ngcontent-jun-c39="" class="text-center">Cantidad</th>
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center">
                                            <!----> Precio Referencial Unitario
                                            <!---->
                                        </th>
                                        <!---->
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center">
                                            <!----> Precio Referencial Total
                                            <!---->
                                        </th>
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center"> Precio Unitario Ofertado</th>
                                        <!---->
                                        <th _ngcontent-jun-c39="" class="text-center"> Precio Total Ofertado</th>
                                        <!---->
                                        <!---->
                                        <!---->
                                    </tr>
                                </thead>
                                <tbody _ngcontent-jun-c39="">
                                    <!---->
                                    <!---->
                                    <!---->
                                    <tr _ngcontent-jun-c39="">
                                        <!---->
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-center">1</td>
                                        <td _ngcontent-jun-c39="">COMPUTADORAS POSRTATILES PROFESIONAL - TIPO A</td>
                                        <td _ngcontent-jun-c39="">PIEZA</td>
                                        <td _ngcontent-jun-c39="" class="text-right">50</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"> 17,500.00</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"> 875,000.00</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right">
                                            <!---->
                                            <!----><input _ngcontent-jun-c39="" class="form form-control input-sm ng-valid ng-touched ng-dirty" type="text">
                                        </td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"><span _ngcontent-jun-c39="">875,000.00</span></td>
                                        <!---->
                                        <!---->
                                        <!---->
                                    </tr>
                                    <!---->
                                    <!---->
                                    <!---->
                                    <tr _ngcontent-jun-c39="">
                                        <!---->
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-center">2</td>
                                        <td _ngcontent-jun-c39="">COMPUTADORAS POSRTATILES PROFESIONAL - TIPO B</td>
                                        <td _ngcontent-jun-c39="">PIEZA</td>
                                        <td _ngcontent-jun-c39="" class="text-right">100</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"> 16,800.00</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"> 1,680,000.00</td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right">
                                            <!---->
                                            <!----><input _ngcontent-jun-c39="" class="form form-control input-sm ng-valid ng-dirty ng-touched" type="text">
                                        </td>
                                        <!---->
                                        <td _ngcontent-jun-c39="" class="text-right"><span _ngcontent-jun-c39="">1,680,000.00</span></td>
                                        <!---->
                                        <!---->
                                        <!---->
                                    </tr>
                                    <!---->
                                    <!---->
                                </tbody>
                                <tfoot _ngcontent-jun-c39="">
                                    <!---->
                                    <!---->
                                    <tr _ngcontent-jun-c39="" style="height: 110px;"></tr>
                                </tfoot>
                            </table>
                        </div>
                    </datos-items-fragment>
                </div>
                <div _ngcontent-jun-c45="" class="modal-footer"><button _ngcontent-jun-c45="" class="btn btn-secondary btn-sm" type="button">Cancelar</button><button _ngcontent-jun-c45="" class="btn btn-primary btn-sm" type="submit">Aceptar</button></div>
            </div>
        </div>
    </div>
</prv-propuesta-econ-lote-modal>