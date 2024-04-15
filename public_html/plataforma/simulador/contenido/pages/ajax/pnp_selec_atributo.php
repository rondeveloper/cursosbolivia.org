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
                            <div _ngcontent-nyk-c26="" class="col-lg-2 col-sm-3 col-md-3 col-12"><label _ngcontent-nyk-c26="" class="control-label">Descripción:</label></div>
                            <div _ngcontent-nyk-c26="" class="col-lg-10 col-sm-9 col-md-9 col-12">
                                <div _ngcontent-nyk-c26="" class="inputtitulogroup-sm">
                                    <!----><input _ngcontent-nyk-c26="" aria-describedby="sizing-addon3" class="form-control margin-top ng-untouched ng-pristine ng-valid" type="text" placeholder="Descripción">
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <th _ngcontent-nyk-c26="">Descripción</th>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                        </thead>
                        <tbody _ngcontent-nyk-c26="">
                            <!---->
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('MATERIAL');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> MATERIAL
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('MARCA');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> MARCA
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('MODELO');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> MODELO
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Alcance');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Alcance
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Altura');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Altura
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Altura de vuelo');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Altura de vuelo
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Amperaje');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Amperaje
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Ancho');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Ancho
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Calibre');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Calibre
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <tr _ngcontent-nyk-c26="">
                                <td _ngcontent-nyk-c26="" class="text-center">
                                    <!----><label _ngcontent-nyk-c26="" onclick="pnp_selec_atributo_ok('Campo de visión');" class="radio-inline c-radio"><input _ngcontent-nyk-c26="" id="inlineradio1" name="radioUnspsc" type="radio" value="S"><span _ngcontent-nyk-c26="" class="fa fa-circle"></span></label>
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
                                <td _ngcontent-nyk-c26="">
                                    <!---->
                                    <!----> Campo de visión
                                    <!---->
                                </td>
                                <!---->
                                <!---->
                                <!---->
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
    <button _ngcontent-nyk-c26="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
    <!---->
</div>