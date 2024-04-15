<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>
<div class="modal-body">
    <spinner-http _nghost-lrx-c13="">
        <!---->
    </spinner-http>
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
        <scrollable height="60vh" style="position: inherit;overflow-y: scroll;overflow-x: hidden; width: auto; height: 60vh;">
            <sccatite-simple-tree _nghost-lrx-c21="">
                <!---->
                <!---->
                <div _ngcontent-lrx-c21="" class="row">
                    <div _ngcontent-lrx-c21="" class="col-lg-12">
                        <form _ngcontent-lrx-c21="" action="" novalidate="" class="ng-untouched ng-pristine ng-valid">
                            <div _ngcontent-lrx-c21="" class="input-group input-group-sm"><input _ngcontent-lrx-c21="" class="form-control ng-untouched ng-pristine ng-valid" id="buscarCatalogo" name="descripcionCodigo" placeholder="Buscar por descripción o código del item" type="text"><span _ngcontent-lrx-c21="" class="input-group-btn"><button onclick="pnb_simular_busqueda();" _ngcontent-lrx-c21="" class="btn btn-primary" type="submit"><span _ngcontent-lrx-c21="" class="fa fa-search"></span></button></span></div>
                        </form>
                    </div>
                </div><br _ngcontent-lrx-c21="">
                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-0">
                    <!---->
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">10000000 -</b> Material Vivo Vegetal y Animal, Accesorios y Suministros</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">11000000 -</b> Material Mineral, Textil y Vegetal y Animal No Comestible</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">12000000 -</b> Material Químico incluyendo Bioquímicos y Materiales de Gas</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">13000000 -</b> Materiales de Resina, Colofonia, Caucho, Espuma, Película y Elastómericos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">14000000 -</b> Materiales y Productos de Papel</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!---->
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-down"></span></button>
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15000000 -</b> Materiales Combustibles, Aditivos para Combustibles, Lubricantes y Anticorrosivos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                    <li _ngcontent-lrx-c21="">
                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                                        <!---->
                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15100000 -</b> Combustibles</span>
                                        <div _ngcontent-lrx-c21="">
                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                <!---->
                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                </ul>
                                            </sccatite-simple-tree>
                                        </div>
                                    </li>
                                    <li _ngcontent-lrx-c21="">
                                        <!---->
                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15110000 -</b> Combustibles gaseosos y aditivos</span>
                                        <div _ngcontent-lrx-c21="">
                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                <!---->
                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-lrx-c21="">
                                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                                                        <!---->
                                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111500 -</b> Combustibles gaseosos</span>
                                                        <div _ngcontent-lrx-c21="">
                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                <!---->
                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-lrx-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111700 -</b> Aditivos para carburante</span>
                                                        <div _ngcontent-lrx-c21="">
                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                <!---->
                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111701 -</b> Espesantes de combustible</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111702 -</b> Inhibidores de hielo para sistemas de combustibles</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111703 -</b> Tratamiento de combustible</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111704 -</b> Limpiador de combustible</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111705 -</b> Estabilizador de combustible</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111706 -</b> Aumento de octano</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-lrx-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-lrx-c21="" class="radio-inline c-radio"><input _ngcontent-lrx-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-lrx-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15111707 -</b> Impulso de cetano</span>
                                                                        <div _ngcontent-lrx-c21="">
                                                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </sccatite-simple-tree>
                                        </div>
                                    </li>
                                    <li _ngcontent-lrx-c21="">
                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                                        <!---->
                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15120000 -</b> Lubricantes, aceites, grasas y anticorrosivos</span>
                                        <div _ngcontent-lrx-c21="">
                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                <!---->
                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                </ul>
                                            </sccatite-simple-tree>
                                        </div>
                                    </li>
                                    <li _ngcontent-lrx-c21="">
                                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                                        <!---->
                                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">15130000 -</b> Combustible para reactores nucleares</span>
                                        <div _ngcontent-lrx-c21="">
                                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                                <!---->
                                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                </ul>
                                            </sccatite-simple-tree>
                                        </div>
                                    </li>
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">20000000 -</b> Maquinaria y Accesorios de Minería y Perforación de Pozos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">21000000 -</b> Maquinaria y Accesorios para Agricultura, Pesca, Silvicultura y Fauna</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">22000000 -</b> Maquinaria y Accesorios para Construcción y Edificación</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">23000000 -</b> Maquinaria y Accesorios para Manufactura y Procesamiento Industrial</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">24000000 -</b> Maquinaria, Accesorios y Suministros para Manejo, Acondicionamiento y Almacenamiento de Materiales</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">25000000 -</b> Vehículos Comerciales, Militares y Particulares, Accesorios y Componentes</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">26000000 -</b> Maquinaria y Accesorios para Generación y Distribución de Energía</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">27000000 -</b> Herramientas y Maquinaria General</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">30000000 -</b> Componentes y Suministros para Estructuras, Edificación, Construcción y Obras Civiles</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">31000000 -</b> Componentes y Suministros de Manufactura</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">32000000 -</b> Componentes y Suministros Electrónicos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">39000000 -</b> Componentes, Accesorios y Suministros de Sistemas Eléctricos e Iluminación</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">40000000 -</b> Componentes y Equipos para Distribución y Sistemas de Acondicionamiento</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">41000000 -</b> Equipos y Suministros de Laboratorio, de Medición, de Observación y de Pruebas</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">42000000 -</b> Equipo Médico, Accesorios y Suministros</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">43000000 -</b> Difusión de Tecnologías de Información y Telecomunicaciones</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">44000000 -</b> Equipos de Oficina, Accesorios y Suministros</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">45000000 -</b> Equipos y Suministros para Impresión, Fotografia y Audiovisuales</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">46000000 -</b> Equipos y Suministros de Defensa, Orden Publico, Proteccion, Vigilancia y Seguridad</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">47000000 -</b> Equipos de Limpieza y Suministros</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">48000000 -</b> Maquinaria, Equipo y Suministros para la Industria de Servicios</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">49000000 -</b> Equipos, Suministros y Accesorios para Deportes y Recreación</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">50000000 -</b> Alimentos, Bebidas y Tabaco</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">51000000 -</b> Medicamentos y Productos Farmacéuticos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">52000000 -</b> Artículos Domésticos, Suministros y Productos Electrónicos de Consumo</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">53000000 -</b> Ropa, Maletas y Productos de Aseo Personal</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">54000000 -</b> Productos para Relojería, Joyería y Piedras Preciosas</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">55000000 -</b> Publicaciones Impresas, Publicaciones Electrónicas y Accesorios</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">56000000 -</b> Muebles, Mobiliario y Decoración</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">60000000 -</b> Instrumentos Musicales, Juegos, Juguetes, Artes, Artesanías y Equipo educativo, Materiales, Accesorios y Suministros</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">64000000 -</b> Instrumentos financieros, productos, contratos y acuerdos</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-lrx-c21="">
                        <!----><button _ngcontent-lrx-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-lrx-c21="" class="fa fa-caret-right"></span></button>
                        <!---->
                        <!----><span _ngcontent-lrx-c21=""><b _ngcontent-lrx-c21="">95000000 -</b> Terrenos, Edificios, Estructuras y Vías</span>
                        <div _ngcontent-lrx-c21="">
                            <sccatite-simple-tree _ngcontent-lrx-c21="" _nghost-lrx-c21="">
                                <!---->
                                <ul _ngcontent-lrx-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                </ul>
            </sccatite-simple-tree>
        </scrollable>
        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 125.548px;"></div>
        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
    </div>
</div>


<!----><div _ngcontent-hmx-c7="" class="modal-footer"><button onclick="close_modal();" _ngcontent-hmx-c7="" class="btn test/insbtn-primary" data-dismiss="modal" type="button">Cerrar</button><button class="btn btn-primary" type="button" style="display: none;" id="boton-aceptar-select-item" onclick="close_modal();">Aceptar</button></div>