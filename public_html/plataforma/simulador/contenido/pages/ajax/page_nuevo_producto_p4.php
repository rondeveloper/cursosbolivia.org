<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];
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
<pcaitem-screen _nghost-ruw-c22="" class="ng-star-inserted">
    <!---->
    <botones-opciones _ngcontent-ruw-c22="" _nghost-ruw-c23="" class="ng-star-inserted">
        <div _ngcontent-ruw-c23="" class="opciones affix">
            <!---->
            <!---->
            <div _ngcontent-ruw-c23="" class="btn btn-inverse ng-star-inserted" placement="left" tooltip="Ocultar" aria-describedby="tooltip-34"><i _ngcontent-ruw-c23="" class="fa fa-chevron-circle-right"></i></div>
            <!---->
            <div _ngcontent-ruw-c23="" class="affix">
                <!---->
                <div _ngcontent-ruw-c23="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-59"><span onclick="pnp_activar_prod();" _ngcontent-ruw-c23="" class="cursor-pointer"><i _ngcontent-ruw-c23="" class="fa fa-check-circle text-primary"></i> Activar</span></div>
                <!---->
                <div _ngcontent-ruw-c23="" class="pr-3 text-left ng-star-inserted" placement="left" aria-describedby="tooltip-60"><span onclick="page_nuevo_bien();" _ngcontent-ruw-c23="" class="cursor-pointer"><i _ngcontent-ruw-c23="" class="fa fa-trash text-primary"></i> Eliminar</span></div>
                <!---->
            </div>
        </div>
    </botones-opciones>
    <!---->
    <div _ngcontent-ruw-c22="" class="content-heading">
        <div _ngcontent-ruw-c22="" class="row w-100">
            <div _ngcontent-ruw-c22="" class="col-8"> REGISTRO DE PRODUCTOS <h6 _ngcontent-ruw-c22="" class="margin-0">Producto Ofertado</h6>
            </div>
            <div _ngcontent-ruw-c22="" class="col-4">
                <spinner-http _ngcontent-ruw-c22="" _nghost-ruw-c13="">
                    <!---->
                </spinner-http>
            </div>
        </div>
    </div>
    <div _ngcontent-ruw-c22="" class="row">
        <div _ngcontent-ruw-c22="" class="col-lg-6 offset-lg-3">
            <wizard _ngcontent-ruw-c22="" _nghost-ruw-c24="">
                <div _ngcontent-ruw-c24="" class="d-flex justify-content-around bd-highlight mb-0">
                    <!---->
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button onclick="page_nuevo_producto();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 1" aria-describedby="tooltip-54"><span style="background: #5d9cec;
    padding: 3px 7px;
    color: white;
    border-radius: 50%;
    font-size: 12pt;
    font-weight: bold;">P</span></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Producto</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button onclick="page_nuevo_producto_p2();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 2" aria-describedby="tooltip-55"><i _ngcontent-ruw-c24="" class="fas fa-camera"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Imágenes</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button onclick="page_nuevo_producto_p3();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 3" aria-describedby="tooltip-56"><i _ngcontent-ruw-c24="" class="fas fa-plus"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Atributos</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button onclick="page_nuevo_producto_p4();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 4" aria-describedby="tooltip-57"><i _ngcontent-ruw-c24="" class="fas fa-dollar-sign"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-active">Precios</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center ng-star-inserted"><button onclick="page_nuevo_producto_p5();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-lg btn-pass" id=" 5" aria-describedby="tooltip-58"><i _ngcontent-ruw-c24="" class="fas fa-external-link-alt"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-pass">Adjuntos</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-ruw-c22="" class="row w-100">
        <div _ngcontent-ruw-c22="" class="col-lg-3 col-sm-12 col-md-12 col-12">
            <!---->
            <!---->
            <pcaitem-datos-fragment _ngcontent-ruw-c22="" _nghost-ruw-c25="" class="ng-star-inserted">
                <div _ngcontent-ruw-c25="" class="card card-default">
                    <div _ngcontent-ruw-c25="" class="card-header d-flex align-items-center">
                        <div _ngcontent-ruw-c25="" class="d-flex col p-0">
                            <h4 _ngcontent-ruw-c25="" class="card-title">Datos del Producto</h4>
                        </div>
                        <div _ngcontent-ruw-c25="" class="d-flex justify-content-end"><button _ngcontent-ruw-c25="" class="btn btn-link hidden-lg"><em _ngcontent-ruw-c25="" class="fa fa-minus text-muted"></em></button></div>
                    </div>
                    <div _ngcontent-ruw-c25="" class="card-body collapse in show" aria-expanded="true" aria-hidden="false" style="display: block;">
                        <div _ngcontent-ruw-c25="" class="row">
                            <div _ngcontent-ruw-c25="" class="col-lg-12 col-md-6">
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c25="" class="text-bold">Código:</label></div>
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"> 330 </div>
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c25="" class="text-bold">Descripción:</label></div>
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"> Software controlador </div>
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c25="" class="text-bold">Estado:</label></div>
                                <div _ngcontent-ruw-c25="" class="col-12 col-sm-12 col-md-12  col-lg-12"> ELABORADO </div>
                            </div>
                        </div>
                    </div>
                </div>
            </pcaitem-datos-fragment>
        </div>
        <div _ngcontent-ruw-c22="" class="col-lg-9 col-sm-12 col-md-12 col-12">
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <pcaiteml-list-fragment _ngcontent-ruw-c22="" _nghost-ruw-c29="" class="ng-star-inserted">
                <div _ngcontent-ruw-c29="" class="card card-default">
                    <div _ngcontent-ruw-c29="" class="card-header">
                        <div _ngcontent-ruw-c29="" class="row">
                            <div _ngcontent-ruw-c29="" class="col-lg-7 col-sm-6">
                                <div _ngcontent-ruw-c29="" class="card-title"> Precios por Ubicación del Producto </div>
                            </div>
                            <div _ngcontent-ruw-c29="" class="col-lg-5 col-sm-6 col-12">
                                <div _ngcontent-ruw-c29="" class="row">
                                    <div _ngcontent-ruw-c29="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                        <form _ngcontent-ruw-c29="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                            <div _ngcontent-ruw-c29="" class="input-group"><input _ngcontent-ruw-c29="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por Precio y Saldo" type="text"><span _ngcontent-ruw-c29="" class="input-group-btn"><button _ngcontent-ruw-c29="" class="btn btn-primary" type="submit"><span _ngcontent-ruw-c29="" class="fa fa-search"></span></button></span></div>
                                        </form>
                                    </div>
                                </div>
                                <div _ngcontent-ruw-c29="" class="row">
                                    <div _ngcontent-ruw-c29="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                        <button-filter _ngcontent-ruw-c29="" _nghost-ruw-c15="">
                                            <div _ngcontent-ruw-c15="" class="btn-group" dropdown=""><button _ngcontent-ruw-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Estado<b _ngcontent-ruw-c15=""></b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                        <button-filter _ngcontent-ruw-c29="" _nghost-ruw-c15="">
                                            <div _ngcontent-ruw-c15="" class="btn-group" dropdown=""><button _ngcontent-ruw-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Departamento<b _ngcontent-ruw-c15=""></b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                        <button-filter _ngcontent-ruw-c29="" _nghost-ruw-c15="">
                                            <div _ngcontent-ruw-c15="" class="btn-group" dropdown=""><button _ngcontent-ruw-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Moneda<b _ngcontent-ruw-c15=""></b></button>
                                                <!---->
                                            </div>
                                        </button-filter>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div _ngcontent-ruw-c29="" class="card-body">
                        <!---->
                        <div _ngcontent-ruw-c29="" class="row ng-star-inserted">
                            <div _ngcontent-ruw-c29="" class="col-lg-12 col-md-12">
                                <div _ngcontent-ruw-c29="" class="btn-group opcionesMenuPagina">
                                    <div _ngcontent-ruw-c29="" class="btn-group">
                                        <button onclick="pnp_nuevo_precio();" _ngcontent-ruw-c29="" aria-expanded="false" class="btn btn-primary btn-sm" type="button"><i _ngcontent-ruw-c29="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Precio por Ubicación </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-ruw-c29="" class="row">
                            <div _ngcontent-ruw-c29="" class="col-lg-12 col-md-12">
                                <div _ngcontent-ruw-c29="" class="table-responsive">
                                <div id="id-pnp_tabla_precios">
                                    <table _ngcontent-ruw-c29="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                        <thead _ngcontent-ruw-c29="">
                                            <tr _ngcontent-ruw-c29="">
                                                <th _ngcontent-ruw-c29="" class="text-center w-cog">Opciones</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Ubicación</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Precio Actual</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Moneda</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Vigente Hasta</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Cantidad en Stock</th>
                                                <th _ngcontent-ruw-c29="" class="text-center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody _ngcontent-ruw-c29="">
                                            <!---->
                                            <?php
                                            $rqatr1 = query("SELECT * FROM simulador_prods_precios WHERE id_usuario='$id_usuario' AND id_prod='$id_prod' ");
                                            while ($rqatr2 = fetch($rqatr1)) {
                                            ?>
                                                <!---->
                                                <tr _ngcontent-cyf-c29="">
                                                    <td _ngcontent-cyf-c29="" class="text-center">
                                                        <div _ngcontent-cyf-c29="" class="btn-group" dropdown=""><button _ngcontent-cyf-c29="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-cyf-c29="" class="fa fa-cog text-primary"></span></button>
                                                            <!---->
                                                        </div>
                                                    </td>
                                                    <td _ngcontent-cyf-c29=""><?php echo $rqatr2['ubicacion']; ?></td>
                                                    <td _ngcontent-cyf-c29="" class="text-right"><?php echo $rqatr2['precio']; ?> </td>
                                                    <td _ngcontent-cyf-c29="">BOLIVIANOS</td>
                                                    <td _ngcontent-cyf-c29=""><?php echo date("d/m/Y",strtotime($rqatr2['fecha_vigencia'])); ?></td>
                                                    <td _ngcontent-cyf-c29="" class="text-right"><?php echo $rqatr2['cnt_stock']; ?></td>
                                                    <td _ngcontent-cyf-c29="">ELABORADO</td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr _ngcontent-ruw-c29="" style="height: 120px;"></tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <div _ngcontent-ruw-c29="" class="col-lg-12 col-md-12 text-center">
                                    <pagination _ngcontent-ruw-c29="" class="pagination-sm justify-content-center ng-untouched ng-pristine ng-valid">
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
                <confirmacion-modal _ngcontent-ruw-c29="" id="idConfirmacionLugaresModal" _nghost-ruw-c16="">
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
                <pcaiteml-modal _ngcontent-ruw-c29="" id="idLugaresModal" _nghost-ruw-c35="">
                    <div _ngcontent-ruw-c35="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="”static”" data-keyboard="”false”" role="dialog" tabindex="-1">
                        <div _ngcontent-ruw-c35="" class="modal-dialog modal-md">
                            <div _ngcontent-ruw-c35="" class="modal-content">
                                <div _ngcontent-ruw-c35="" class="modal-header text-center">
                                    <h4 _ngcontent-ruw-c35="" class="text-color-blanco w-100"> Precio por Ubicación </h4><button _ngcontent-ruw-c35="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c35="" aria-hidden="true">×</span></button>
                                </div>
                                <div _ngcontent-ruw-c35="" class="card-body">
                                    <div _ngcontent-ruw-c35="" class="row">
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c35="" class="">Ubicación:</label>
                                            <div _ngcontent-ruw-c35="" class="row">
                                                <div _ngcontent-ruw-c35="" class="col-lg-12">
                                                    <div _ngcontent-ruw-c35="" class="form-group">
                                                        <div _ngcontent-ruw-c35=""><input _ngcontent-ruw-c35="" class="form-control ng-untouched ng-pristine" disabled="" ng-reflect-is-disabled="true" type="text">
                                                            <!---->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c35="" class="">Precio:</label>
                                            <div _ngcontent-ruw-c35="" class="row">
                                                <div _ngcontent-ruw-c35="" class="col-lg-12">
                                                    <div _ngcontent-ruw-c35="" class="form-group">
                                                        <div _ngcontent-ruw-c35="" class="input-group"><input _ngcontent-ruw-c35="" class="form-control input-sm ng-untouched ng-pristine" type="text" disabled="">
                                                            <!---->
                                                            <!----><span _ngcontent-ruw-c35="" class="input-group-btn intput-sm ng-star-inserted">
                                                                <div _ngcontent-ruw-c35="" class="btn-group"><button _ngcontent-ruw-c35="" aria-expanded="false" class="btn btn-secondary btn-sm dropdown-toggle" type="button"> <span _ngcontent-ruw-c35="" class="caret"></span></button></div>
                                                            </span>
                                                        </div>
                                                        <!---->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c35="" class="">Cantidad en Stock:</label></div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c35="" class="form-control ng-untouched ng-pristine" maxlength="50" placeholder="" type="text" disabled=""></div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c35="" class="mt">Vigente Hasta (dd/mm/aaaa):</label></div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c35="" class="form-control input-sm ng-untouched ng-pristine" name="date-picker" placeholder="dd/mm/aaaa" type="date" disabled=""></div>
                                        <div _ngcontent-ruw-c35="" class="col-12 col-sm-12 col-md-12  col-lg-12">
                                            <pcaitemld-list-fragment _ngcontent-ruw-c35="" _nghost-ruw-c36="">
                                                <div _ngcontent-ruw-c36="" class="row">
                                                    <div _ngcontent-ruw-c36="" class="col-lg-12 col-md-12"><label _ngcontent-ruw-c36="" class="mt">Descuentos por cantidad:</label></div>
                                                </div>
                                                <!---->
                                                <div _ngcontent-ruw-c36="" class="row">
                                                    <div _ngcontent-ruw-c36="" class="col-lg-12 col-md-12">
                                                        <table _ngcontent-ruw-c36="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                                            <thead _ngcontent-ruw-c36="">
                                                                <tr _ngcontent-ruw-c36="">
                                                                    <th _ngcontent-ruw-c36="" class="w-cog">Opciones</th>
                                                                    <th _ngcontent-ruw-c36="" class="text-center">Cantidad Desde</th>
                                                                    <th _ngcontent-ruw-c36="" class="text-center">Cantidad Hasta</th>
                                                                    <th _ngcontent-ruw-c36="" class="text-center">Precio</th>
                                                                    <th _ngcontent-ruw-c36="" class="text-center">Moneda</th>
                                                                    <th _ngcontent-ruw-c36="" class="text-center">Estado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody _ngcontent-ruw-c36="">
                                                                <!---->
                                                                <!---->
                                                                <tr _ngcontent-ruw-c36="" class="ng-star-inserted">
                                                                    <td _ngcontent-ruw-c36="" colspan="6">No hay información de descuentos</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <pcaitemld-doc-modal _ngcontent-ruw-c36="" _nghost-ruw-c37="">
                                                    <div _ngcontent-ruw-c37="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" role="dialog" tabindex="-1">
                                                        <div _ngcontent-ruw-c37="" class="modal-dialog modal-sm">
                                                            <div _ngcontent-ruw-c37="" class="modal-content">
                                                                <div _ngcontent-ruw-c37="" class="modal-header">
                                                                    <h4 _ngcontent-ruw-c37="" class="text-color-blanco w-100"> Descuento </h4><button _ngcontent-ruw-c37="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c37="" aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div _ngcontent-ruw-c37="" class="modal-body">
                                                                    <div _ngcontent-ruw-c37="" class="row">
                                                                        <div _ngcontent-ruw-c37="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c37="">Cantidad Desde:</label></div>
                                                                        <div _ngcontent-ruw-c37="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c37="" class="form-control ng-untouched ng-pristine" maxlength="50" placeholder="" type="text" disabled=""></div>
                                                                        <div _ngcontent-ruw-c37="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c37="" class="mt">Cantidad Hasta:</label></div>
                                                                        <div _ngcontent-ruw-c37="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><input _ngcontent-ruw-c37="" class="form-control ng-untouched ng-pristine" maxlength="50" placeholder="" type="text" disabled=""></div>
                                                                        <div _ngcontent-ruw-c37="" class="col-xs-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c37="" class="mt">Precio:</label>
                                                                            <div _ngcontent-ruw-c37="" class="form-group">
                                                                                <div _ngcontent-ruw-c37="" class="input-group"><input _ngcontent-ruw-c37="" class="form-control input-sm ng-untouched ng-pristine" type="text" disabled=""><span _ngcontent-ruw-c37="" class="input-group-btn intput-sm">
                                                                                        <div _ngcontent-ruw-c37="" class="btn-group"><button _ngcontent-ruw-c37="" aria-expanded="false" class="btn btn-secondary btn-sm dropdown-toggle" type="button"> </button></div>
                                                                                    </span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div _ngcontent-ruw-c37="" class="modal-footer"><button _ngcontent-ruw-c37="" class="btn btn-secondary" type="button">
                                                                        <!---->
                                                                        <!----> Cerrar
                                                                        <!---->
                                                                    </button>
                                                                    <!---->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </pcaitemld-doc-modal>
                                                <confirmacion-modal _ngcontent-ruw-c36="" _nghost-ruw-c16="">
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
                                            </pcaitemld-list-fragment>
                                        </div>
                                    </div>
                                    <div _ngcontent-ruw-c35="" class="modal-footer"><button _ngcontent-ruw-c35="" class="btn btn-secondary" type="button">
                                            <!---->
                                            <!----> Cerrar
                                            <!---->
                                        </button>
                                        <!---->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <lista-valores _ngcontent-ruw-c35="" _nghost-ruw-c34="">
                        <div _ngcontent-ruw-c34="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" role="dialog" tabindex="-1">
                            <div _ngcontent-ruw-c34="" class="modal-dialog modal-">
                                <div _ngcontent-ruw-c34="" class="modal-content">
                                    <div _ngcontent-ruw-c34="" class="modal-header text-center">
                                        <h4 _ngcontent-ruw-c34="" class="text-color-blanco w-100"> </h4><button _ngcontent-ruw-c34="" aria-label="Close" class="close float-right" type="button"><span _ngcontent-ruw-c34="" aria-hidden="true">×</span></button>
                                    </div>
                                    <div _ngcontent-ruw-c34="" class="modal-body">
                                        <div _ngcontent-ruw-c34="" class="row">
                                            <div _ngcontent-ruw-c34="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                                <form _ngcontent-ruw-c34="" class="form-horizontal ng-untouched ng-pristine ng-valid" id="form" ng-submit="buscarPorParametros()" novalidate="">
                                                    <!---->
                                                    <div _ngcontent-ruw-c34="" class="row form-group text-right">
                                                        <div _ngcontent-ruw-c34="" class="col-lg-12 col-sm-12 col-md-12 col-12"><button _ngcontent-ruw-c34="" class="btn btn-primary btn-sm margin-top" type="submit">Buscar</button><button _ngcontent-ruw-c34="" class="btn btn-secondary btn-sm margin-top" type="button">Limpiar</button></div>
                                                    </div>
                                                </form>
                                                <div _ngcontent-ruw-c34="" class="row">
                                                    <div _ngcontent-ruw-c34="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                                        <table _ngcontent-ruw-c34="" class="table table-bordered table-sm table-hover" id="tablaValues">
                                                            <thead _ngcontent-ruw-c34="">
                                                                <tr _ngcontent-ruw-c34="">
                                                                    <th _ngcontent-ruw-c34="">Opciones</th>
                                                                    <!---->
                                                                </tr>
                                                            </thead>
                                                            <tbody _ngcontent-ruw-c34="">
                                                                <!---->
                                                                <!---->
                                                                <tr _ngcontent-ruw-c34="" class="ng-star-inserted">
                                                                    <td _ngcontent-ruw-c34="" colspan="*">No hay registros</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div _ngcontent-ruw-c34="" class="col-lg-12 col-md-12 text-center">
                                                            <pagination _ngcontent-ruw-c34="" class="pagination-sm pag-margen ng-untouched ng-pristine ng-valid" id="pagPaginacion">
                                                                <ul class="pagination pagination-sm pag-margen">
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
                                    <div _ngcontent-ruw-c34="" class="modal-footer"><button _ngcontent-ruw-c34="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
                                        <!---->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </lista-valores>
                </pcaiteml-modal>
                <botones-opciones-footer _ngcontent-ruw-c29="" _nghost-ruw-c20="">
                    <div _ngcontent-ruw-c20="" class="row">
                        <div _ngcontent-ruw-c20="" class="col-12 text-right">
                            <!---->
                            <!----><a onclick="page_nuevo_producto_p3();" _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                            <!----><a onclick="page_nuevo_producto_p5();" _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
                        </div>
                    </div>
                </botones-opciones-footer>
            </pcaiteml-list-fragment>
            <!---->
        </div>
    </div>
    <confirmacion-modal _ngcontent-ruw-c22="" id="idPcaitemConfirmacionModal" _nghost-ruw-c16="">
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
</pcaitem-screen>