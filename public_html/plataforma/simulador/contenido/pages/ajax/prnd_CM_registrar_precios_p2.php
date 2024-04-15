<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$precio_unitario_ofertado = str_replace(',','',get('precio_unitario_ofertado'));
$valor_ofertado = str_replace(',','',get('valor_ofertado'));

$id_usuario = usuario('id_sim');
$cuce = '21-1712-00-1116922-1-1';

query("UPDATE simulador_documentos SET precio_unitario_ofertado='$precio_unitario_ofertado',valor_ofertado='$valor_ofertado' WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
?>

<!---->
<div _ngcontent-crt-c37="" class="card-header ng-star-inserted" style="padding: 0 !important;">
    <table _ngcontent-crt-c37="" class="table table-sm table-bordered">
        <tr _ngcontent-crt-c37="">
            <!---->
            <!---->
            <td _ngcontent-crt-c37="" class="text-center w-cog ng-star-inserted">
                <div _ngcontent-crt-c37="" class="btn-group" dropdown=""><button onclick="dropdown_prnd_CM_2();" _ngcontent-crt-c37="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-74"><span _ngcontent-crt-c37="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                    <ul onclick="prnd_CM_registrar_precios();" id="id-sw_dropdown_prnd_CM_2" style="display: none;" _ngcontent-crt-c37="" class="dropdown-menu ng-star-inserted show" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);">
                        <!----><a _ngcontent-crt-c37="" class="dropdown-item text-dark ng-star-inserted"><span _ngcontent-crt-c37="" class="fa fa-edit text-primary"></span> Registrar precios unitarios </a>
                        <!---->
                        <!---->
                        <!---->
                    </ul>
                </div>
            </td>
            <td _ngcontent-crt-c37=""><b _ngcontent-crt-c37="">Descripción:</b>
                <!---->
                <!----> Todos los ítems
                <!---->
            </td>
            <!---->
            <!---->
            <td _ngcontent-crt-c37="" class="text-right ng-star-inserted"><b _ngcontent-crt-c37="">Total Ofertado:</b> <?php echo round($valor_ofertado,2); ?></td>
            <!---->
            <!---->
            <td _ngcontent-crt-c37="">
                <div _ngcontent-crt-c37="" class="float-right"><button onclick="dropdown_prnd_CM_1();" _ngcontent-crt-c37="" class="btn btn-xs btn-link text-muted"><em _ngcontent-crt-c37="" class="text-muted fa fa-plus"></em></button></div>
            </td>
        </tr>
    </table>
</div>
<!---->

<div _ngcontent-crt-c37="" class="card-body bt ng-star-inserted" id="id-sw_dropdown_prnd_CM_1" style="display: block;">
    <datos-items-fragment _ngcontent-crt-c37="" _nghost-crt-c39="">
        <div _ngcontent-crt-c39="" class="row">
            <div _ngcontent-crt-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-crt-c39="" class="input-group input-group-sm"><input _ngcontent-crt-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-crt-c39="" class="input-group-btn"><button _ngcontent-crt-c39="" class="btn btn-primary" type="button"><span _ngcontent-crt-c39="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-crt-c39=""><br _ngcontent-crt-c39="">
        </div>
        <div _ngcontent-crt-c39="" class="table-responsive">
            <table _ngcontent-crt-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                <thead _ngcontent-crt-c39="">
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center border-right-color ng-star-inserted" colspan="6">Definido por la Entidad</th>
                        <th _ngcontent-crt-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                    </tr>
                    <tr _ngcontent-crt-c39="">
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center">#</th>
                        <th _ngcontent-crt-c39="" class="text-center">Descripción del Bien o Servicio</th>
                        <th _ngcontent-crt-c39="" class="text-center">Unidad de Medida</th>
                        <th _ngcontent-crt-c39="" class="text-center">Cantidad</th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted">
                            <!---->
                            <!----> Precio Unitario del Proveedor Preseleccionado
                            <!---->
                        </th>
                        <!---->
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted">
                            <!---->
                            <!----> Precio Total del Proveedor Preseleccionado
                            <!---->
                        </th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted"> Precio Unitario Ofertado</th>
                        <!---->
                        <th _ngcontent-crt-c39="" class="text-center ng-star-inserted"> Precio Total Ofertado</th>
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                </thead>
                <tbody _ngcontent-crt-c39="">
                    <!---->
                    <!---->
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <!---->
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-center">1</td>
                        <td _ngcontent-crt-c39="">APLICACION DE PRODUCTOS ACELERANTES Y FOLIAR,TEPEADO DEL GRAMADO,ARENADO,APLICACION DE PRODUCTOS QUIMICOS,CORTE VERTICAL DEL CESPED,DEMARCACION DEL CAMPO DEPORTIVO Y LIMPIEZA</td>
                        <td _ngcontent-crt-c39="">M2</td>
                        <td _ngcontent-crt-c39="" class="text-right">7,000</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"> 6.80</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"> 47,600.00</td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted">
                            <!----><span _ngcontent-crt-c39="" class="ng-star-inserted"><?php echo round($precio_unitario_ofertado,2); ?></span>
                            <!---->
                        </td>
                        <!---->
                        <td _ngcontent-crt-c39="" class="text-right ng-star-inserted"><span _ngcontent-crt-c39=""><?php echo round($valor_ofertado,2); ?></span></td>
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                    <!---->
                    <!---->
                </tbody>
                <tfoot _ngcontent-crt-c39="">
                    <!---->
                    <tr _ngcontent-crt-c39="" class="ng-star-inserted">
                        <th _ngcontent-crt-c39="" class="text-right" colspan="5">Total Referencial:</th>
                        <th _ngcontent-crt-c39="" class="text-right">47,600.00 </th>
                        <th _ngcontent-crt-c39="" class="text-right">Total Ofertado:</th>
                        <th _ngcontent-crt-c39="" class="text-right"><?php echo round($valor_ofertado,2); ?></th>
                    </tr>
                    <!---->
                </tfoot>
            </table>
        </div>
    </datos-items-fragment>
</div>