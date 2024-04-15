<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$ubicacion = '';
$aux_disabled = ' disabled="" ';
if (isset_get('ubicacion')) {
    $ubicacion = get('ubicacion');
    $aux_disabled = '';
}

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];
?>
<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<div _ngcontent-nyk-c29="" class="card-body">
    <form id="FORM-nuevo-precio" method="post" action="" onsubmit="return false;">
        <div _ngcontent-nyk-c29="" class="row">
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-nyk-c29="" class="">Ubicación:</label>
                <div _ngcontent-nyk-c29="" class="row">
                    <div _ngcontent-nyk-c29="" class="col-lg-12">
                        <div _ngcontent-nyk-c29="" class="form-group">
                            <div _ngcontent-nyk-c29="" class="input-group"><input value="<?php echo $ubicacion; ?>" _ngcontent-nyk-c29="" class="form-control ng-untouched ng-pristine" disabled="" ng-reflect-is-disabled="true" type="text">
                                <!----><span _ngcontent-nyk-c29="" class="input-group-btn intput-sm">
                                    <button onclick="pnp_selec_ubicacion();" _ngcontent-nyk-c29="" class="btn btn-primary btn-sm" type="button"><span _ngcontent-nyk-c29="" class="fa fa-search"></span></button></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-nyk-c29="" class="">Precio:</label>
                <div _ngcontent-nyk-c29="" class="row">
                    <div _ngcontent-nyk-c29="" class="col-lg-12">
                        <div _ngcontent-nyk-c29="" class="form-group">
                            <div _ngcontent-nyk-c29="" class="input-group">
                                <input name="precio" _ngcontent-nyk-c29="" class="form-control input-sm ng-untouched ng-pristine ng-valid" type="text">
                                <!----><span _ngcontent-nyk-c29="" class="input-group-btn intput-sm">
                                    <div _ngcontent-nyk-c29="" class="btn-group" dropdown=""><button _ngcontent-nyk-c29="" class="btn btn-secondary btn-sm dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> BOLIVIANOS </button>
                                        <!---->
                                    </div>
                                </span>
                                <!---->
                            </div>
                            <!---->
                        </div>
                    </div>
                </div>
            </div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-nyk-c29="" class="">Cantidad en Stock:</label></div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><input name="cnt_stock" _ngcontent-nyk-c29="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="50" placeholder="" type="text"></div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-nyk-c29="" class="mt">Vigente Hasta (dd/mm/aaaa):</label></div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12"><input name="fecha_vigencia" _ngcontent-nyk-c29="" class="form-control input-sm ng-untouched ng-pristine ng-valid" name="date-picker" placeholder="dd/mm/aaaa" type="date"></div>
            <div _ngcontent-nyk-c29="" class="col-12 col-sm-12 col-md-12  col-lg-12">
                <pcaitemld-list-fragment _ngcontent-nyk-c29="" _nghost-nyk-c30="">
                    <div _ngcontent-nyk-c30="" class="row">
                        <div _ngcontent-nyk-c30="" class="col-lg-12 col-md-12"><label _ngcontent-nyk-c30="" class="mt">Descuentos por cantidad:</label></div>
                    </div>
                    <!---->
                    <div _ngcontent-nyk-c30="" class="row">
                        <div _ngcontent-nyk-c30="" class="col-lg-12 col-md-12">
                            <div _ngcontent-nyk-c30="" class="btn-group opcionesMenuPagina">
                                <div _ngcontent-nyk-c30="" class="btn-group">
                                    <button onclick="pnp_nuevo_precio_descuento();" _ngcontent-nyk-c30="" aria-expanded="false" class="btn btn-primary btn-sm" type="button"><i _ngcontent-nyk-c30="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Descuento </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div _ngcontent-nyk-c30="" class="row">
                        <div _ngcontent-nyk-c30="" class="col-lg-12 col-md-12">
                            <div id="id-pnp-tabla-descuentos">
                            <table _ngcontent-nyk-c30="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                <thead _ngcontent-nyk-c30="">
                                    <tr _ngcontent-nyk-c30="">
                                        <th _ngcontent-nyk-c30="" class="w-cog">Opciones</th>
                                        <th _ngcontent-nyk-c30="" class="text-center">Cantidad Desde</th>
                                        <th _ngcontent-nyk-c30="" class="text-center">Cantidad Hasta</th>
                                        <th _ngcontent-nyk-c30="" class="text-center">Precio</th>
                                        <th _ngcontent-nyk-c30="" class="text-center">Moneda</th>
                                        <th _ngcontent-nyk-c30="" class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody _ngcontent-nyk-c30="">
                                    <!---->
                                    <!---->
                                    <?php
                                    $rqdsc1 = query("SELECT * FROM simulador_descuentos WHERE id_prod='$id_prod' ");
                                    if(num_rows($rqdsc1)==0){
                                        ?>
                                        <tr _ngcontent-nyk-c30="">
                                            <td _ngcontent-nyk-c30="" colspan="6">No hay información de descuentos</td>
                                        </tr>
                                        <?php
                                    }
                                    while($rqdsc2 = fetch($rqdsc1)){
                                    ?>
                                    <tr _ngcontent-dvq-c32="">
                                        <td _ngcontent-dvq-c32="" class="text-center">
                                            <div _ngcontent-dvq-c32="" class="btn-group" dropdown=""><button _ngcontent-dvq-c32="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-31" aria-expanded="false"><span _ngcontent-dvq-c32="" class="fa fa-cog text-primary"></span></button>
                                                <!---->
                                                <ul _ngcontent-dvq-c32="" class="dropdown-menu" role="menu" style="left: 0px; right: auto;">
                                                    <!----><a _ngcontent-dvq-c32="" class="dropdown-item"><span _ngcontent-dvq-c32="" class="fa fa-edit text-primary"></span> Editar </a>
                                                    <!---->
                                                    <!---->
                                                    <!---->
                                                    <!----><a _ngcontent-dvq-c32="" class="dropdown-item"><span _ngcontent-dvq-c32="" class="fa fa-trash text-danger"></span> Eliminar </a>
                                                </ul>
                                            </div>
                                        </td>
                                        <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['cantidad_desde']; ?></td>
                                        <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['cantidad_hasta']; ?> </td>
                                        <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['precio_descuento']; ?> </td>
                                        <td _ngcontent-dvq-c32="">BOLIVIANOS</td>
                                        <td _ngcontent-dvq-c32="">ELABORADO</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <pcaitemld-doc-modal _ngcontent-nyk-c30="" _nghost-nyk-c31="">
                        <div id="id-pnp_nuevo_precio_minimodal" _ngcontent-nyk-c31="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade in show" role="dialog" tabindex="-1" style="display: none;">
                            <div _ngcontent-nyk-c31="" class="modal-dialog modal-sm" style="width: 350px;">
                                <div _ngcontent-dvq-c34="" class="modal-content">
                                    <div _ngcontent-dvq-c34="" class="modal-header">
                                        <h4 _ngcontent-dvq-c34="" class="text-color-blanco w-100"> Descuento </h4><button onclick="document.getElementById('id-pnp_nuevo_precio_minimodal').style.display='none';" _ngcontent-dvq-c34="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-dvq-c34="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-dvq-c34="" class="modal-body">
                                        <div _ngcontent-dvq-c34="" class="row">
                                            <div _ngcontent-dvq-c34="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dvq-c34="">Cantidad Desde:</label></div>
                                            <div _ngcontent-dvq-c34="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input id="id-input-cantidad_desde" onkeyup="document.getElementById('id-pnp_nuevo_precio_minimodal-btn-aceptar').removeAttribute('disabled');" _ngcontent-dvq-c34="" class="form-control ng-pristine ng-valid ng-touched" maxlength="50" placeholder="" type="text"></div>
                                            <div _ngcontent-dvq-c34="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dvq-c34="" class="mt">Cantidad Hasta:</label></div>
                                            <div _ngcontent-dvq-c34="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input  id="id-input-cantidad_hasta"_ngcontent-dvq-c34="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="50" placeholder="" type="text"></div>
                                            <div _ngcontent-dvq-c34="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-dvq-c34="" class="mt">Precio:</label>
                                                <div _ngcontent-dvq-c34="" class="form-group">
                                                    <div _ngcontent-dvq-c34="" class="input-group">
                                                    <input id="id-input-precio_descuento" _ngcontent-dvq-c34="" class="form-control input-sm ng-untouched ng-pristine ng-valid" type="text"><span _ngcontent-dvq-c34="" class="input-group-btn intput-sm">
                                                            <div _ngcontent-dvq-c34="" class="btn-group"><button _ngcontent-dvq-c34="" aria-expanded="false" class="btn btn-secondary btn-sm dropdown-toggle" type="button"> BOLIVIANOS </button></div>
                                                        </span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div _ngcontent-dvq-c34="" class="modal-footer">
                                    <button onclick="document.getElementById('id-pnp_nuevo_precio_minimodal').style.display='none';" _ngcontent-dvq-c34="" class="btn btn-secondary" type="button">
                                            <!---->
                                            <!---->
                                            <!----> Cancelar
                                        </button>
                                        <!----><button onclick="pnp_add_nuevo_descuento();" id="id-pnp_nuevo_precio_minimodal-btn-aceptar" _ngcontent-dvq-c34="" class="btn btn-primary" type="button" disabled="">Aceptar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </pcaitemld-doc-modal>
                    <confirmacion-modal _ngcontent-nyk-c30="" _nghost-nyk-c22="">
                        <div _ngcontent-nyk-c22="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
                            <div _ngcontent-nyk-c22="" class="modal-dialog modal-sm">
                                <div _ngcontent-nyk-c22="" class="modal-content">
                                    <div _ngcontent-nyk-c22="" class="modal-body">
                                        <div _ngcontent-nyk-c22="" class="row">
                                            <div _ngcontent-nyk-c22="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                                            <div _ngcontent-nyk-c22="" class="col-lg-12 col-md-12 text-center"> </div>
                                        </div>
                                    </div>
                                    <div _ngcontent-nyk-c22="" class="modal-footer"><button _ngcontent-nyk-c22="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-nyk-c22="" type="button" class="btn btn-">Aceptar</button></div>
                                </div>
                            </div>
                        </div>
                    </confirmacion-modal>
                </pcaitemld-list-fragment>
            </div>
        </div>
        <input name="ubicacion" value="<?php echo $ubicacion; ?>" type="hidden">
    </form>
    <div _ngcontent-nyk-c29="" class="modal-footer">
        <button onclick="close_modal();" _ngcontent-nyk-c29="" class="btn btn-secondary" type="button">
            <!---->
            <!---->
            <!----> Cancelar
        </button>
        <!---->
        <button onclick="pnp_add_nuevo_precio();" _ngcontent-nyk-c29="" class="btn btn-primary" type="button" <?php echo $aux_disabled; ?>>Aceptar</button>
    </div>
</div>