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
<router-outlet _ngcontent-lkh-c1=""></router-outlet>
<scdopr-list _nghost-lkh-c21="">
    <div _ngcontent-lkh-c21="" class="content-heading p5">
        <div _ngcontent-lkh-c21="" class="row w-100">
            <div _ngcontent-lkh-c21="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-lkh-c21="" class="col-lg-5 col-12 pt10">
                <div _ngcontent-lkh-c21="" class="row">
                    <div _ngcontent-lkh-c21="" class="col-12"> Mis Documentos </div>
                </div>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-lkh-c21="" _nghost-lkh-c18="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-lkh-c21="" _nghost-lkh-c22="">
                    <div _ngcontent-lkh-c22="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-lkh-c22="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-lkh-c22="" class="text-center">
                                <div _ngcontent-lkh-c22="" class="text-sm">Febrero</div>
                                <div _ngcontent-lkh-c22="" class="h4 mt-0">19</div>
                            </div>
                        </div>
                        <div _ngcontent-lkh-c22="" class="col-8 rounded-right"><span _ngcontent-lkh-c22="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-lkh-c22="">
                            <div _ngcontent-lkh-c22="" class="h4 mt-0">14:09:18</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-lkh-c21="" class="row">
        <div _ngcontent-lkh-c21="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div _ngcontent-lkh-c21="" class="card card-default">
                <div _ngcontent-lkh-c21="" class="card-header">
                    <div _ngcontent-lkh-c21="" class="row">
                        <div _ngcontent-lkh-c21="" class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                            <div _ngcontent-lkh-c21="" class="card-title"></div>
                        </div>
                        <div _ngcontent-lkh-c21="" class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                            <div _ngcontent-lkh-c21="" class="row">
                                <div _ngcontent-lkh-c21="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <form _ngcontent-lkh-c21="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                        <div _ngcontent-lkh-c21="" class="input-group"><input _ngcontent-lkh-c21="" class="form-control ng-untouched ng-pristine ng-valid" name="Busqueda" placeholder="Buscar por CUCE, Nro. Documento y Objeto de Contratación" type="text"><span _ngcontent-lkh-c21="" class="input-group-btn"><button _ngcontent-lkh-c21="" class="btn btn-primary" type="submit"><span _ngcontent-lkh-c21="" class="fa fa-search"></span></button></span></div>
                                    </form>
                                </div>
                            </div>
                            <div _ngcontent-lkh-c21="" class="row">
                                <div _ngcontent-lkh-c21="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <button-filter _ngcontent-lkh-c21="" _nghost-lkh-c13="">
                                        <div _ngcontent-lkh-c13="" class="btn-group" dropdown=""><button _ngcontent-lkh-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Operación<b _ngcontent-lkh-c13="">: Presentación de Propuesta/Oferta</b></button>
                                            <!---->
                                        </div>
                                    </button-filter>
                                    <button-filter _ngcontent-lkh-c21="" _nghost-lkh-c13="">
                                        <div _ngcontent-lkh-c13="" class="btn-group" dropdown=""><button _ngcontent-lkh-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Modalidad<b _ngcontent-lkh-c13=""></b></button>
                                            <!---->
                                        </div>
                                    </button-filter>
                                    <button-filter _ngcontent-lkh-c21="" _nghost-lkh-c13="">
                                        <div _ngcontent-lkh-c13="" class="btn-group" dropdown=""><button _ngcontent-lkh-c13="" class="btn btn-secondary btn-xs dropdown-toggle" dropdowntoggle="" type="button" aria-haspopup="true"> Estado<b _ngcontent-lkh-c13=""></b></button>
                                            <!---->
                                        </div>
                                    </button-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div _ngcontent-lkh-c21="" class="card-body">
                    <div _ngcontent-lkh-c21="" class="row">
                        <div _ngcontent-lkh-c21="" class="col-lg-12 col-md-12">
                            <div _ngcontent-lkh-c21="" class="btn-group opcionesMenuPagina"><button onclick="page_nuevo_documento();" _ngcontent-lkh-c21="" class="btn btn-primary btn-sm"><i _ngcontent-lkh-c21="" class="fa fa-plus-circle"></i>&nbsp;Nuevo Documento </button></div>
                        </div>
                    </div>
                    <div _ngcontent-lkh-c21="" class="row">
                        <div _ngcontent-lkh-c21="" class="col-lg-12 col-md-12">
                            <div _ngcontent-lkh-c21="" class="table-responsive">
                                <table _ngcontent-lkh-c21="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
                                    <thead _ngcontent-lkh-c21="">
                                        <tr _ngcontent-lkh-c21="">
                                            <th _ngcontent-lkh-c21="" class="text-center w-cog">Opciones</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Nro. Documento</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Tipo Operación</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">CUCE</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Objeto de Contratación</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Modalidad</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Forma de Adjudicación</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Precio Referencial</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Fecha Operación</th>
                                            <th _ngcontent-lkh-c21="" class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody _ngcontent-lkh-c21="">
                                        <?php
                                        $rqdde1 = query("SELECT * FROM simulador_documentos WHERE id_usuario='$id_usuario' ");
                                        if (num_rows($rqdde1) == 0) {
                                        ?>
                                            <tr _ngcontent-lkh-c21="">
                                                <td _ngcontent-lkh-c21="" class="text-left" colspan="10" style="padding: 15px;">
                                                    No hay documentos con las condiciones de busqueda.
                                                </td>
                                            </tr>
                                        <?php
                                        } else {
                                            while($rqdde2 = fetch($rqdde1)){
                                            ?>
                                                <tr _ngcontent-brx-c12="">
                                                    <td _ngcontent-brx-c12="" class="text-center">
                                                        <div _ngcontent-brx-c12="" class="btn-group" dropdown="">
                                                            <button onclick="pmd_dropdown_1('<?php echo $rqdde2['id']; ?>');" _ngcontent-brx-c12="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" id="button-autoclose1" type="button" aria-haspopup="true"><span _ngcontent-brx-c12="" class="fa fa-cog text-primary"></span></button>
                                                            <!---->
                                                            <ul id="id-sw_pmd_dropdown_1-<?php echo $rqdde2['id']; ?>" _ngcontent-dvn-c12="" aria-labelledby="button-autoclose1" class="dropdown-menu show"  role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                                                            <?php
                                                            if($rqdde2['cuce']=='21-0513-00-1151485-1-1'){
                                                                if ($rqdde2['estado'] == '0') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso();"><span _ngcontent-dvn-c12="" class="fa fa-edit text-primary"></span> Editar </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso();"><span _ngcontent-dvn-c12="" class="fa fa-trash text-danger"></span> Eliminar </span>
                                                                    <?php
                                                                } elseif ($rqdde2['estado'] == '1') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><span _ngcontent-dvn-c12="" class="fas fa-window-close text-warning"></span> Anular Verificación </span>
                                                                    <span onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento();" _ngcontent-dvn-c12="" class="dropdown-item "><span _ngcontent-dvn-c12="" class="fa fa-check-circle text-success"></span> Enviar </span>
                                                                    <?php
                                                                } elseif ($rqdde2['estado'] == '2') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted"><span _ngcontent-dvn-c12="" class="fa fa-eye text-primary"></span> Consultar </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted">&nbsp;</span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc_send()"><span _ngcontent-dvn-c12="" class="fa fa-trash text-danger"></span> Eliminar (Opci&oacute;n disponible solo en el simulador)</span>
                                                                    <?php
                                                                }
                                                            }else{
                                                                if ($rqdde2['estado'] == '0') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="page_reg_nuevo_documento_p1('<?php echo $rqdde2['modalidad']; ?>');"><span _ngcontent-dvn-c12="" class="fa fa-edit text-primary"></span> Editar </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="page_reg_nuevo_documento_p1('<?php echo $rqdde2['modalidad']; ?>');"><span _ngcontent-dvn-c12="" class="fa fa-trash text-danger"></span> Eliminar </span>
                                                                    <?php
                                                                } elseif ($rqdde2['estado'] == '1') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><span _ngcontent-dvn-c12="" class="fas fa-window-close text-warning"></span> Anular Verificación </span>
                                                                    <span onclick="page_enviar_documento('<?php echo $rqdde2['modalidad']; ?>');" _ngcontent-dvn-c12="" class="dropdown-item "><span _ngcontent-dvn-c12="" class="fa fa-check-circle text-success"></span> Enviar </span>
                                                                    <?php
                                                                } elseif ($rqdde2['estado'] == '2') {
                                                                    ?>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item "><i _ngcontent-dvn-c12="" class="far fa-file-pdf"></i> Ficha del Proceso </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted"><span _ngcontent-dvn-c12="" class="fa fa-eye text-primary"></span> Consultar </span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted">&nbsp;</span>
                                                                    <span _ngcontent-dvn-c12="" class="dropdown-item ng-star-inserted" onclick="prnd_action_eliminar_doc('<?php echo $rqdde2['modalidad']; ?>')"><span _ngcontent-dvn-c12="" class="fa fa-trash text-danger"></span> Eliminar (Opci&oacute;n disponible solo en el simulador)</span>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td _ngcontent-brx-c12=""><?php echo (int)($rqdde2['id'])+11000; ?>.0</td>
                                                    <td _ngcontent-brx-c12="">Presentación de Propuesta/Oferta</td>
                                                    <td _ngcontent-brx-c12=""><?php echo $rqdde2['cuce']; ?></td>
                                                    <td _ngcontent-brx-c12="">
                                                        <text-length _ngcontent-brx-c12="" _nghost-brx-c16=""><?php echo $rqdde2['objeto']; ?>
                                                            <!---->
                                                        </text-length>
                                                    </td>
                                                    <td _ngcontent-brx-c12=""><?php echo $rqdde2['modalidad']; ?></td>
                                                    <td _ngcontent-brx-c12=""><?php echo $rqdde2['forma_adjudicacion']; ?></td>
                                                    <td _ngcontent-brx-c12="" class="text-right"> <?php echo $rqdde2['precio_referencial']; ?> </td>
                                                    <td _ngcontent-brx-c12="">
                                                    <?php
                                                        if ($rqdde2['fecha_operacion'] != '0000-00-00 00:00:00') {
                                                            echo date("d/m/Y H:i",strtotime($rqdde2['fecha_operacion']));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td _ngcontent-brx-c12="">
                                                        <?php
                                                        if ($rqdde2['estado'] == '0') {
                                                            echo "ELABORADO";
                                                        } elseif ($rqdde2['estado'] == '1') {
                                                            echo "VERIFICADO";
                                                        } elseif ($rqdde2['estado'] == '2') {
                                                            echo "ENVIADO";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                        ?>
                                        <tr _ngcontent-lkh-c21="" style="height:120px;"></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div _ngcontent-lkh-c21="" class="col-lg-12 col-md-12 text-center">
                                <pagination _ngcontent-lkh-c21="" class="pagination-sm justify-content-center ng-untouched ng-valid ng-dirty">
                                    <ul class="pagination pagination-sm justify-content-center">
                                        <!---->
                                        <li class="pagination-first page-item disabled"><a class="page-link" href="">Primero</a></li>
                                        <!---->
                                        <li class="pagination-prev page-item disabled"><a class="page-link" href="">Anterior</a></li>
                                        <!---->
                                        <li class="pagination-page page-item active"><a class="page-link" href="">1</a></li>
                                        <!---->
                                        <li class="pagination-next page-item disabled"><a class="page-link" href="">Siguiente</a></li>
                                        <!---->
                                        <li class="pagination-last page-item disabled"><a class="page-link" href="">Último</a></li>
                                    </ul>
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</scdopr-list>