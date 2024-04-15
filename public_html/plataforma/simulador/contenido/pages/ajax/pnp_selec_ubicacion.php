<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>

<div _ngcontent-nyk-c26="" class="modal-body">
    <div _ngcontent-nyk-c26="" class="row">
        <div _ngcontent-nyk-c26="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <form _ngcontent-nyk-c26="" class="form-horizontal ng-untouched ng-pristine ng-valid" id="form" ng-submit="buscarPorParametros()" novalidate="">
                <!---->
                <div _ngcontent-nyk-c26="">
                    <!---->
                    <div _ngcontent-nyk-c26="" class="form-group">
                        <div _ngcontent-nyk-c26="" class="row">
                            <div _ngcontent-nyk-c26="" class="col-lg-2 col-sm-3 col-md-3 col-12"><label _ngcontent-nyk-c26="" class="control-label">Buscar:</label></div>
                            <div _ngcontent-nyk-c26="" class="col-lg-10 col-sm-9 col-md-9 col-12">
                                <div _ngcontent-nyk-c26="" class="inputtitulogroup-sm">
                                    <!----><input _ngcontent-nyk-c26="" aria-describedby="sizing-addon3" class="form-control margin-top ng-untouched ng-pristine ng-valid" type="text" placeholder="Buscar por Municipio o Departamento">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div _ngcontent-nyk-c26="">
                    <!---->
                </div>
                <div _ngcontent-nyk-c26="">
                    <!---->
                </div>
                <div _ngcontent-nyk-c26="" class="row form-group text-right">
                    <div _ngcontent-nyk-c26="" class="col-lg-12 col-sm-12 col-md-12 col-12"><button _ngcontent-nyk-c26="" class="btn btn-primary btn-sm margin-top" type="submit">Buscar</button><button _ngcontent-nyk-c26="" class="btn btn-secondary btn-sm margin-top" type="button">Limpiar</button></div>
                </div>
            </form>
            <div _ngcontent-nyk-c26="" class="row">
                <div _ngcontent-nyk-c26="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                    <table _ngcontent-nyk-c26="" class="table table-bordered table-sm table-hover" id="tablaValues">
                        <thead _ngcontent-nyk-c26="">
                            <tr _ngcontent-nyk-c26="">
                                <th _ngcontent-nyk-c26="">Opciones</th>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <th _ngcontent-nyk-c26="">Municipio</th>
                                <!---->
                                <!---->
                                <!---->
                                <th _ngcontent-nyk-c26="">Departamento</th>
                                <!---->
                            </tr>
                        </thead>
                        <tbody _ngcontent-nyk-c26="">
                            <!---->
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Sucre');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Sucre
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Villa Vaca Guzmán');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Villa Vaca Guzmán
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Camataqui (Villa Abecia)');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Camataqui (Villa Abecia)
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Villa Serrano');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Villa Serrano
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Camargo');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Camargo
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Tarabuco');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Tarabuco
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Monteagudo');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Monteagudo
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Padilla');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Padilla
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Villa Zudañez (Tacopaya)');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Villa Zudañez (Tacopaya)
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" class="radio-inline c-radio" onclick="pnp_selec_ubicacion_ok('Villa Azurduy');"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Villa Azurduy
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!---->
                                    <!----> CHUQUISACA
                                </td>
                                <!---->
                            </tr>
                            <!---->
                        </tbody>
                    </table>
                    <div _ngcontent-nyk-c26="" class="col-lg-12 col-md-12 text-center">
                        <pagination _ngcontent-nyk-c26="" class="pagination-sm pag-margen ng-untouched ng-valid ng-dirty" id="pagPaginacion">
                            <ul class="pagination pagination-sm pag-margen" style="margin: 0px;">
                                <!---->
                                <li class="pagination-first page-item disabled"><a class="page-link" href="">Primero</a></li>
                                <!---->
                                <li class="pagination-prev page-item disabled"><a class="page-link" href="">Anterior</a></li>
                                <!---->
                                <li class="pagination-page page-item active"><a class="page-link" href="">1</a></li>
                                <li class="pagination-page page-item"><a class="page-link" href="">2</a></li>
                                <li class="pagination-page page-item"><a class="page-link" href="">3</a></li>
                                <li class="pagination-page page-item"><a class="page-link" href="">4</a></li>
                                <li class="pagination-page page-item"><a class="page-link" href="">5</a></li>
                                <!---->
                                <li class="pagination-next page-item"><a class="page-link" href="">Siguiente</a></li>
                                <!---->
                                <li class="pagination-last page-item"><a class="page-link" href="">Último</a></li>
                            </ul>
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div _ngcontent-nyk-c26="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-nyk-c26="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
    <!---->
</div>