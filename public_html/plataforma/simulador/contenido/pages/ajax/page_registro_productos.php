<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
?>

<router-outlet _ngcontent-vgu-c1=""></router-outlet>
<prouns-list-screen _nghost-vgu-c12="">
    <div _ngcontent-vgu-c12="" class="content-heading p5">
        <div _ngcontent-vgu-c12="" class="row row-cols-2 w-100">
            <div _ngcontent-vgu-c12="" class="col-6 pt10"> REGISTRO DE PRODUCTOS </div>
            <div _ngcontent-vgu-c12="" class="col-6">
                <spinner-http _ngcontent-vgu-c12="" _nghost-vgu-c13="">
                    <!---->
                </spinner-http>
            </div>
        </div>
    </div>
    <div _ngcontent-vgu-c12="" class="row">
        <div _ngcontent-vgu-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <prouns-list-fragment _ngcontent-vgu-c12="" _nghost-vgu-c14="">
                <div _ngcontent-vgu-c14="" class="row">
                    <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div _ngcontent-vgu-c14="" class="card card-default">
                            <div _ngcontent-vgu-c14="" class="card-header">
                                <div _ngcontent-vgu-c14="" class="row">
                                    <div _ngcontent-vgu-c14="" class="col-lg-7 col-sm-6">
                                        <div _ngcontent-vgu-c14="" class="card-title"></div>
                                    </div>
                                    <div _ngcontent-vgu-c14="" class="col-lg-5 col-sm-6 col-xs-12">
                                        <div _ngcontent-vgu-c14="" class="row">
                                            <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                <form _ngcontent-vgu-c14="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                    <div _ngcontent-vgu-c14="" class="input-group"><input _ngcontent-vgu-c14="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por Código o Descripción del Item" type="text"><span _ngcontent-vgu-c14="" class="input-group-btn"><button _ngcontent-vgu-c14="" class="btn btn-primary" type="submit"><span _ngcontent-vgu-c14="" class="fa fa-search"></span></button></span></div>
                                                </form>
                                            </div>
                                        </div>
                                        <div _ngcontent-vgu-c14="" class="row">
                                            <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                                <button-filter _ngcontent-vgu-c14="" _nghost-vgu-c15="">
                                                    <div _ngcontent-vgu-c15="" class="btn-group" dropdown=""><button _ngcontent-vgu-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Estado<b _ngcontent-vgu-c15="">: Activo</b></button>
                                                        <!---->
                                                    </div>
                                                </button-filter>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-vgu-c14="" class="card-body">
                                <div _ngcontent-vgu-c14="" class="row">
                                    <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12">
                                        <div _ngcontent-vgu-c14="" class="btn-group opcionesMenuPagina">
                                            <div _ngcontent-vgu-c14="" class="btn-group"><button onclick="page_nuevo_bien('0');" _ngcontent-vgu-c14="" aria-expanded="false" class="btn btn-primary btn-sm" type="button"><span _ngcontent-vgu-c14="" class="fa fa-plus-circle"></span> Nuevo Bien </button></div>
                                        </div>
                                    </div>
                                </div>
                                <div _ngcontent-vgu-c14="" class="row">
                                    <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12">
                                        <table _ngcontent-vgu-c14="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                            <thead _ngcontent-vgu-c14="">
                                                <tr _ngcontent-vgu-c14="">
                                                    <th _ngcontent-vgu-c14="" class="w-cog">Opciones</th>
                                                    <th _ngcontent-vgu-c14="" class="text-center"> Código Ítem UNSPSC</th>
                                                    <th _ngcontent-vgu-c14="" class="text-center"> Descripción Ítem UNSPSC</th>
                                                    <th _ngcontent-vgu-c14="" class="text-center"> Estado Ítem UNSPSC</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-vgu-c14="">
                                                <?php
                                                $rqit1 = query("SELECT * FROM simulador_prods_categ WHERE id_usuario='$id_usuario' ");
                                                if (num_rows($rqit1) == 0) {
                                                ?>
                                                    <tr _ngcontent-yfk-c39="">
                                                        <td _ngcontent-yfk-c39="" colspan="4">No hay registro de Ítems</td>
                                                    </tr>
                                                <?php
                                                }
                                                while ($rqit2 = fetch($rqit1)) {
                                                ?>
                                                    <tr _ngcontent-nhg-c28="">
                                                        <td _ngcontent-nhg-c28="" class="text-center">
                                                            <div _ngcontent-nhg-c28="" class="btn-group" dropdown="">
                                                                <button onclick="dropdown_btn_opciones();" _ngcontent-nhg-c28="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-nhg-c28="" class="fa fa-cog text-primary"></span></button>
                                                                <!---->
                                                                <ul id="dropdown-autoclose1" _ngcontent-nhg-c28="" aria-labelledby="button-autoclose1" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                                                                    <a onclick="page_nuevo_bien(<?php echo $rqit2['id']; ?>);" _ngcontent-nhg-c28="" class="dropdown-item"><span _ngcontent-nhg-c28="" class="fa fa-eye"></span> Ver Detalle </a>
                                                                    <a _ngcontent-nhg-c28="" class="dropdown-item"><span _ngcontent-nhg-c28="" class="fa fa-trash text-danger"></span> Eliminar </a>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td _ngcontent-nhg-c28=""><?php echo $rqit2['codigo']; ?></td>
                                                        <td _ngcontent-nhg-c28=""><?php echo $rqit2['descripcion']; ?></td>
                                                        <td _ngcontent-nhg-c28="">ACTIVO</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table><br _ngcontent-vgu-c14="">
                                        <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12 text-center">
                                            <pagination _ngcontent-vgu-c14="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                                <ul class="pagination pagination-sm justify-content-center">
                                                    <!---->
                                                    <li class="pagination-first page-item disabled"><a class="page-link">Primero</a></li>
                                                    <!---->
                                                    <li class="pagination-prev page-item disabled"><a class="page-link">Anterior</a></li>
                                                    <!---->
                                                    <li class="pagination-page page-item active"><a class="page-link">1</a></li>
                                                    <!---->
                                                    <li class="pagination-next page-item"><a class="page-link">Siguiente</a></li>
                                                    <!---->
                                                    <li class="pagination-last page-item"><a class="page-link">Último</a></li>
                                                </ul>
                                            </pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <confirmacion-modal _ngcontent-vgu-c14="" id="idProunsConfirmacion" _nghost-vgu-c16="">
                    <div _ngcontent-vgu-c16="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade modal-without-scroll" role="dialog" tabindex="-1">
                        <div _ngcontent-vgu-c16="" class="modal-dialog modal-sm">
                            <div _ngcontent-vgu-c16="" class="modal-content">
                                <div _ngcontent-vgu-c16="" class="modal-body">
                                    <div _ngcontent-vgu-c16="" class="row">
                                        <div _ngcontent-vgu-c16="" class="col-lg-12 col-md-12 text-center"> [object Object] </div>
                                        <div _ngcontent-vgu-c16="" class="col-lg-12 col-md-12 text-center"> </div>
                                    </div>
                                </div>
                                <div _ngcontent-vgu-c16="" class="modal-footer"><button _ngcontent-vgu-c16="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-vgu-c16="" type="button" class="btn btn-">Aceptar</button></div>
                            </div>
                        </div>
                    </div>
                </confirmacion-modal>
            </prouns-list-fragment>
        </div>
    </div>
</prouns-list-screen>