<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$id_cat = get('id_cat');

$nombre_categoria = '';
if ($id_cat != '0') {
    $rqc1 = query("SELECT * FROM simulador_prods_categ WHERE id='$id_cat' ");
    $rqc2 = fetch($rqc1);
    $nombre_categoria = $rqc2['codigo'] . ' ' . $rqc2['descripcion'];
}
?>
<router-outlet _ngcontent-acj-c1=""></router-outlet>
<pcaitem-list-screen class="ng-star-inserted">
    <div class="content-heading">REGISTRO DE PRODUCTOS
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
            <pcaitem-list-fragment _nghost-acj-c19="">
                <div _ngcontent-acj-c19="" class="row">
                    <div _ngcontent-acj-c19="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                        <div _ngcontent-acj-c19="" class="card card-default">
                            <div _ngcontent-acj-c19="" class="card-header">
                                <div _ngcontent-acj-c19="" class="card-title"> Ítem del Catálogo de Naciones Unidas(UNSPSC) </div>
                            </div>
                            <div _ngcontent-acj-c19="" class="card-body">
                                <div _ngcontent-acj-c19="" class="row">
                                    <div _ngcontent-acj-c19="" class="col-lg-12">
                                        <div _ngcontent-acj-c19="" class="form-group">
                                            <div _ngcontent-acj-c19="" class="input-group"><input value="<?php echo $nombre_categoria; ?>" _ngcontent-acj-c19="" class="form-control ng-untouched ng-pristine" disabled="" ng-reflect-is-disabled="true" type="text"><span _ngcontent-acj-c19="" class="input-group-btn"><button onclick="pnb_selecionar_item();" _ngcontent-acj-c19="" class="btn btn-primary" type="button">&nbsp;Seleccionar ítem</button></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div _ngcontent-acj-c19="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                        <div _ngcontent-acj-c19="" class="card card-default">
                            <div _ngcontent-acj-c19="" class="card-header">
                                <div _ngcontent-acj-c19="" class="row">
                                    <div _ngcontent-acj-c19="" class="col-lg-7 col-sm-12">
                                        <div _ngcontent-acj-c19="" class="card-title"></div>
                                    </div>
                                    <div _ngcontent-acj-c19="" class="col-lg-5 col-sm-12 col-12">
                                        <div _ngcontent-acj-c19="" class="row">
                                            <div _ngcontent-acj-c19="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                                <form onsubmit="return false;" _ngcontent-acj-c19="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                    <div _ngcontent-acj-c19="" class="input-group"><input _ngcontent-acj-c19="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar por Descripción del Producto" type="text"><span _ngcontent-acj-c19="" class="input-group-btn"><button _ngcontent-acj-c19="" class="btn btn-primary" type="submit"><span _ngcontent-acj-c19="" class="fa fa-search"></span></button></span></div>
                                                </form>
                                            </div>
                                        </div>
                                        <div _ngcontent-acj-c19="" class="row">
                                            <div _ngcontent-acj-c19="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                                <button-filter _ngcontent-acj-c19="" _nghost-acj-c15="">
                                                    <div _ngcontent-acj-c15="" class="btn-group" dropdown=""><button _ngcontent-acj-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Estado<b _ngcontent-acj-c15=""></b></button>
                                                        <!---->
                                                    </div>
                                                </button-filter>
                                                <button-filter _ngcontent-acj-c19="" _nghost-acj-c15="">
                                                    <div _ngcontent-acj-c15="" class="btn-group" dropdown=""><button _ngcontent-acj-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Publicado en Mercado Virtual<b _ngcontent-acj-c15=""></b></button>
                                                        <!---->
                                                    </div>
                                                </button-filter>
                                                <button-filter _ngcontent-acj-c19="" _nghost-acj-c15="">
                                                    <div _ngcontent-acj-c15="" class="btn-group" dropdown=""><button _ngcontent-acj-c15="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Precio Publicado<b _ngcontent-acj-c15=""></b></button>
                                                        <!---->
                                                    </div>
                                                </button-filter>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div _ngcontent-acj-c19="" class="card-body">
                                <div _ngcontent-acj-c19="" class="row" ng-if="!soloVerSolicitud">
                                    <div _ngcontent-acj-c19="" class="col-lg-12 col-md-12">
                                        <div _ngcontent-acj-c19="" class="btn-group opcionesMenuPagina">
                                            <div _ngcontent-acj-c19="" class="btn-group"><button onclick="page_nuevo_producto('0');" id="boton-nuevo-producto" _ngcontent-acj-c19="" aria-expanded="false" class="btn btn-primary btn-sm" type="button" disabled=""><i _ngcontent-acj-c19="" aria-hidden="true" class="fa fa-plus-circle"></i> Nuevo Producto </button></div>
                                        </div>
                                    </div>
                                </div>
                                <div _ngcontent-acj-c19="" class="row">
                                    <div _ngcontent-acj-c19="" class="col-lg-12 col-md-12">
                                        <table _ngcontent-acj-c19="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                            <thead _ngcontent-acj-c19="">
                                                <tr _ngcontent-acj-c19="">
                                                    <th _ngcontent-acj-c19="" class="w-cog">Opciones</th>
                                                    <th _ngcontent-acj-c19="" class="text-center">Código</th>
                                                    <th _ngcontent-acj-c19="" class="text-center">Descripción</th>
                                                    <th _ngcontent-acj-c19="" class="text-center">Público en Mercado Virtual</th>
                                                    <th _ngcontent-acj-c19="" class="text-center">Precio Público</th>
                                                    <th _ngcontent-acj-c19="" class="text-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody _ngcontent-acj-c19="">
                                                <!---->
                                                <?php
                                                $rqprd1 = query("SELECT * FROM simulador_prods WHERE id_cat='$id_cat' AND id_usuario='$id_usuario' ");
                                                while ($rqprd2 = fetch($rqprd1)) {
                                                ?>
                                                    <tr _ngcontent-hbi-c19="">
                                                        <td _ngcontent-hbi-c19="" class="text-center">
                                                            <div _ngcontent-hbi-c19="" class="btn-group" dropdown="">
                                                                <button onclick="pmd_dropdown_1(<?php echo $rqprd2['id']; ?>);" _ngcontent-hbi-c19="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-hbi-c19="" class="fa fa-cog text-primary"></span></button>
                                                                <!---->
                                                                <ul id="id-sw_pmd_dropdown_1-<?php echo $rqprd2['id']; ?>" _ngcontent-hbi-c19="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                                                                    <a _ngcontent-hbi-c19="" class="dropdown-item"><span _ngcontent-hbi-c19="" class="fa fa-eye"></span> Consultar </a>
                                                                    <!---->
                                                                    <?php 
                                                                    if($rqprd2['estado']=='0'){
                                                                    ?>
                                                                    <a _ngcontent-hbi-c19="" onclick="page_nuevo_producto(<?php echo $rqprd2['id']; ?>);" class="dropdown-item"><span _ngcontent-hbi-c19="" class="fa fa-edit text-primary"></span> Editar </a>
                                                                    <?php 
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td _ngcontent-hbi-c19=""><?php echo $rqprd2['id'] + 300; ?></td>
                                                        <td _ngcontent-hbi-c19=""><?php echo $rqprd2['descripcion_corta']; ?></td>
                                                        <td _ngcontent-hbi-c19="">
                                                            <!----> Si
                                                            <!---->
                                                        </td>
                                                        <td _ngcontent-hbi-c19="">
                                                            <!----> Si
                                                            <!---->
                                                        </td>
                                                        <td _ngcontent-hbi-c19="">
                                                        <?php 
                                                        if($rqprd2['estado']=='1'){
                                                            echo "ACTIVADO";
                                                        }else{
                                                            echo "ELABORADO";
                                                        }
                                                        ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div _ngcontent-acj-c19="" class="col-lg-12 col-md-12 text-center">
                                            <pagination _ngcontent-acj-c19="" class="pagination-sm justify-content-center ng-untouched ng-pristine ng-valid">
                                                <ul class="pagination pagination-sm justify-content-center">
                                                    <!---->
                                                    <li class="pagination-first page-item disabled ng-star-inserted"><a class="page-link">Primero</a></li>
                                                    <!---->
                                                    <li class="pagination-prev page-item disabled ng-star-inserted"><a class="page-link">Anterior</a></li>
                                                    <!---->
                                                    <li class="pagination-page page-item active ng-star-inserted"><a class="page-link">1</a></li>
                                                    <!---->
                                                    <li class="pagination-next page-item disabled ng-star-inserted"><a class="page-link">Siguiente</a></li>
                                                    <!---->
                                                    <li class="pagination-last page-item disabled ng-star-inserted"><a class="page-link">Último</a></li>
                                                </ul>
                                            </pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <sccatite-modal _ngcontent-acj-c19="" id="idCatitemModal">
                    <div aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" onshow="" role="dialog" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="text-color-blanco w-100"> Seleccionar ítem </h4><button aria-label="Close" class="close float-right" type="button"><span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body">
                                    <spinner-http _nghost-acj-c13="">
                                        <!---->
                                    </spinner-http>
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
                                        <scrollable height="60vh" style="overflow: hidden; width: auto; height: 60vh;">
                                            <sccatite-simple-tree _nghost-acj-c21="">
                                                <!---->
                                                <!---->
                                                <div _ngcontent-acj-c21="" class="row ng-star-inserted">
                                                    <div _ngcontent-acj-c21="" class="col-lg-12">
                                                        <form _ngcontent-acj-c21="" action="" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                            <div _ngcontent-acj-c21="" class="input-group input-group-sm"><input _ngcontent-acj-c21="" class="form-control ng-untouched ng-pristine ng-valid" id="buscarCatalogo" name="descripcionCodigo" placeholder="Buscar por descripción o código del item" type="text"><span _ngcontent-acj-c21="" class="input-group-btn"><button _ngcontent-acj-c21="" class="btn btn-primary" type="submit"><span _ngcontent-acj-c21="" class="fa fa-search"></span></button></span></div>
                                                        </form>
                                                    </div>
                                                </div><br _ngcontent-acj-c21="" class="ng-star-inserted">
                                                <ul _ngcontent-acj-c21="" class="list-without-style padding-left-0">
                                                    <!---->
                                                </ul>
                                            </sccatite-simple-tree>
                                        </scrollable>
                                        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div>
                                        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                                    </div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-secondary" type="button">Cancelar</button><button class="btn btn-primary" type="button">Aceptar</button></div>
                            </div>
                        </div>
                    </div>
                </sccatite-modal>
            </pcaitem-list-fragment>
        </div>
    </div>
    <botones-opciones-footer _nghost-acj-c20="">
        <div _ngcontent-acj-c20="" class="row">
            <div _ngcontent-acj-c20="" class="col-12 text-right">
                <!---->
                <!----><a _ngcontent-acj-c20="" onclick="page_registro_productos();" class="fa-stack fa-lg cursor-pointer ng-star-inserted"><i _ngcontent-acj-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-acj-c20="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
            </div>
        </div>
    </botones-opciones-footer>
</pcaitem-list-screen>