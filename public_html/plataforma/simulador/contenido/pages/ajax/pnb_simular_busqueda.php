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
    <spinner-http _nghost-ruw-c13="">
        <!---->
    </spinner-http>
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 60vh;">
    <scrollable height="60vh" style="position: inherit;overflow-y: scroll;overflow-x: hidden; width: auto; height: 60vh;">
            <sccatite-simple-tree _nghost-ruw-c21="">
                <!---->
                <!---->
                <div _ngcontent-ruw-c21="" class="row">
                    <div _ngcontent-ruw-c21="" class="col-lg-12">
                        <form _ngcontent-ruw-c21="" action="" novalidate="" class="ng-valid ng-dirty ng-touched">
                            <div _ngcontent-ruw-c21="" class="input-group input-group-sm"><input _ngcontent-ruw-c21="" class="form-control ng-valid ng-dirty ng-touched" id="buscarCatalogo" name="descripcionCodigo" placeholder="Buscar por descripción o código del item" type="text"><span _ngcontent-ruw-c21="" class="input-group-btn"><button _ngcontent-ruw-c21="" class="btn btn-primary" type="submit"><span _ngcontent-ruw-c21="" class="fa fa-search"></span></button></span></div>
                        </form>
                    </div>
                </div><br _ngcontent-ruw-c21="">
                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-0">
                    <!---->
                    <li _ngcontent-ruw-c21="">
                        <!---->
                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42000000 -</b> Equipo Médico, Accesorios y Suministros</span>
                        <div _ngcontent-ruw-c21="">
                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                <!---->
                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                    <li _ngcontent-ruw-c21="">
                                        <!---->
                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42200000 -</b> Productos de hacer imágenes diagnósticas médicas y de medicina nuclear</span>
                                        <div _ngcontent-ruw-c21="">
                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                <!---->
                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42203600 -</b> Productos de archivar y información de toma de imágenes radiológicos médicos</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42203605 -</b> Software de sistema de archivo de película de rayos x para usos médicos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42203607 -</b> Software de detección asistido por computadora</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
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
                                    <li _ngcontent-ruw-c21="">
                                        <!---->
                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42290000 -</b> Productos quirúrgicos</span>
                                        <div _ngcontent-ruw-c21="">
                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                <!---->
                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42296600 -</b> Sistemas médicos estereotáctica</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">42296611 -</b> Software de confirmación, planificación y seguimiento de objetivos del sistema estereotáxico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
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
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-ruw-c21="">
                        <!---->
                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43000000 -</b> Difusión de Tecnologías de Información y Telecomunicaciones</span>
                        <div _ngcontent-ruw-c21="">
                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                <!---->
                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                    <li _ngcontent-ruw-c21="">
                                        <!---->
                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43210000 -</b> Equipo informático y accesorios</span>
                                        <div _ngcontent-ruw-c21="">
                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                <!---->
                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43212100 -</b> Impresoras de computador</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43212117 -</b> Controlador para impresoras</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
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
                                    <li _ngcontent-ruw-c21="">
                                        <!---->
                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43230000 -</b> Software</span>
                                        <div _ngcontent-ruw-c21="">
                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                <!---->
                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231500 -</b> Software funcional específico de la empresa</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231501 -</b> Software de mesa de ayuda o centro de llamadas (call center)</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231503 -</b> Software de adquisiciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231505 -</b> Software de recursos humanos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231506 -</b> Software de logística de planeación de requerimiento de materiales y cadena de suministros</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231507 -</b> Software de manejo de proyectos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231508 -</b> Software de manejo de inventarios</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231509 -</b> Software de barras de códigos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231510 -</b> Software para hacer etiquetas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231511 -</b> Software de sistemas expertos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231512 -</b> Software de manejo de licencias</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231513 -</b> Software para oficinas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231514 -</b> Software de ventas y mercadeo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231515 -</b> Software de envío y embarque</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231516 -</b> Software de auditoría</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231517 -</b> Software de gestión de procedimientos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231600 -</b> Software de planificación de recursos empresariales (ERP) y contabilidad financiera</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231601 -</b> Software de contabilidad</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231602 -</b> Software de planeación de recursos del negocio erp</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231603 -</b> Software de preparación tributaria</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231604 -</b> Software de análisis financiero</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43231605 -</b> Software de contabilidad de tiempo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232000 -</b> Software de entretenimiento o juegos de computador</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232004 -</b> Software familiar</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232005 -</b> Software de edición de música o sonido</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232100 -</b> Software de edición y creación de contenidos</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232102 -</b> Software de imágenes gráficas o de fotografía</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232103 -</b> Software de creación y edición de video</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232104 -</b> Software de procesamiento de palabras</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232105 -</b> Software de gráficas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232106 -</b> Software de presentación</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232107 -</b> Software de creación y edición de páginas web</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232108 -</b> Software de calendario y programación de citas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232110 -</b> Software de hoja de cálculo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232111 -</b> Software de lector de caracteres ópticos ocr o de escáner</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232112 -</b> Software de autoedición</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232200 -</b> Software de gestión de contenidos</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232201 -</b> Software de flujo de trabajo de contenido</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232202 -</b> Software de manejo de documentos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232203 -</b> Software de versiones de archivo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232204 -</b> Software de ingreso de texto incrustado</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232205 -</b> Software de tipos de letra</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232300 -</b> Software de consultas y gestión de datos</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232301 -</b> Software de categorización o clasificación</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232303 -</b> Software de manejo de relaciones con el cliente crm</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232304 -</b> Software de sistemas de manejo de base datos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232305 -</b> Software de reportes de bases de datos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232306 -</b> Software de interface y preguntas de usuario de base de datos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232307 -</b> Software de extracción de datos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232309 -</b> Software de recuperación o búsqueda de información</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232310 -</b> Software de manejo de metadata</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232311 -</b> Software de manejo de base de datos orientada al objeto</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232312 -</b> Software de servidor de portales</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232313 -</b> Software de servidor de transacciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232314 -</b> Business intelligence y software de análisis de datos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232400 -</b> Programas de desarrollo</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232401 -</b> Software de manejo de configuraciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232402 -</b> Software de entorno de desarrollo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232403 -</b> Software de integración de aplicaciones de empresas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232404 -</b> Software de desarrollo de interface de usuario gráfica</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232405 -</b> Software de desarrollo orientado a objetos o componentes</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232406 -</b> Software de pruebas de programas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232407 -</b> Software de arquitectura de sistemas y análisis de requerimientos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232408 -</b> Software de desarrollo de plataformas web</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232409 -</b> Software para compilar y descompilar</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232500 -</b> Software educativo o de referencia</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232501 -</b> Software de idiomas extranjeros (traductores)</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232502 -</b> Software de entrenamiento basado en computadores</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232504 -</b> Software de navegación de rutas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232505 -</b> Software educacional multimedios</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232506 -</b> Software de enciclopedias</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232507 -</b> Software de diccionarios</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232508 -</b> Software de libretas de teléfonos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232509 -</b> Sintetizador de voz y software de reconocimiento</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232600 -</b> Software específico para la industria</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232601 -</b> Software de soporte de aviación en tierra</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232602 -</b> Software de pruebas de aviación</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232603 -</b> Software de manejo de instalaciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232604 -</b> Software de diseño asistido de computador cad</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232605 -</b> Software analítico o científico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232606 -</b> Software de cumplimiento (compliance)</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232607 -</b> Software de control de vuelos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232608 -</b> Software de control industrial</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232609 -</b> Software de bibliotecas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232610 -</b> Software médico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232611 -</b> Software de puntos de venta pos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232612 -</b> Software de fabricación asistida por computador cam</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232613 -</b> Software de sistema de ejecución de fabricación mes</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232615 -</b> Software de reconocimiento facial</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232616 -</b> Software de gestión legal</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232617 -</b> Software de control meteorológico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232618 -</b> Software de tratamiento de imágenes de radar</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232619 -</b> Software de tratamiento de imágenes satelitales</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232700 -</b> Software de aplicaciones de red</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232701 -</b> Software de servidor de aplicaciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232702 -</b> Software de comunicaciones de escritorio</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232703 -</b> Software de respuesta de voz interactiva</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232704 -</b> Software de servicios de directorio por internet</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232705 -</b> Software de navegador de internet</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232800 -</b> Software de administración de redes</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232801 -</b> Software de monitoreo de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232802 -</b> Software de optimización del sistema operativo de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232803 -</b> Software de manejo de red óptica</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232804 -</b> Software de administración</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232805 -</b> Software de subsistema de multimedia de protocolo de internet ip</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232900 -</b> Software para trabajo en redes</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232901 -</b> Software de acceso</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232902 -</b> Software de servidor de comunicaciones</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232903 -</b> Software de centro de contactos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232904 -</b> Software de fax</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232905 -</b> Software de lan</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232906 -</b> Software de multiplexor</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232907 -</b> Software de almacenamiento de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232908 -</b> Software de interruptor o enrutador</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232909 -</b> Software y firmware de interruptor wan</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232910 -</b> Software inalámbrico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232911 -</b> Software de emulación de terminal de conectividad de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232912 -</b> Software de puerta de acceso</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232913 -</b> Software de puente</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232914 -</b> Software de módem</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232915 -</b> Software de interconectividad de plataformas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43232916 -</b> Software irda de transferencia de información infrarrojo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233000 -</b> Software de entorno operativo</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233001 -</b> Software de sistema de archivo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233002 -</b> Software de sistema de operación de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233004 -</b> Software de sistema operativo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233006 -</b> Software de máquina virtual</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233007 -</b> Software de imágenes por computadora</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233200 -</b> Software de seguridad y protección</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233201 -</b> Software de servidor de autenticación</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233203 -</b> Software de manejo de seguridad de red o de redes privadas virtuales vpn</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233204 -</b> Software de equipos de seguridad de red y de redes privadas virtuales vpn</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233205 -</b> Software de seguridad de transacciones y de protección contra virus</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233400 -</b> Software de controladores de dispositivos y utilidades</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233401 -</b> Software de servidor de discos compactos cd</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233402 -</b> Software de conversión de información</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233403 -</b> Software de compresión de información</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233404 -</b> Software discos compactos cd o dvd o tarjetas de sonido</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233405 -</b> Software de controladores o sistemas de dispositivos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233406 -</b> Software de controladores de ethernet</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233407 -</b> Software de controladores de tarjetas de gráficos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233410 -</b> Software de controladores de impresoras</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233411 -</b> Software de protectores de pantalla</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233413 -</b> Software de reconocimiento de voz</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233414 -</b> Software de carga de almacenamiento de medios</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233415 -</b> Software de respaldo o archivo</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233417 -</b> Componentes de software de reconocimiento de escritura manuscrita</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233420 -</b> Software de conversión de texto a voz</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233500 -</b> Software de intercambio de información</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233501 -</b> Software de correo electrónico</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233502 -</b> Software de video conferencias</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233503 -</b> Software de conferencias de red</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233504 -</b> Software de mensajería instantánea</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233505 -</b> Software de música ambiental o publicidad para mensajería</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233506 -</b> Software de creación de mapas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233507 -</b> Software estándar específico para operadores de móviles</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233508 -</b> Software de aplicación específica para operadores de móviles</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233509 -</b> Software de servicios de mensajería para móviles</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233510 -</b> Software de servicios de internet para móviles</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233511 -</b> Software de servicios basados en ubicación para móviles</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233512 -</b> Software de tonos de timbre</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233600 -</b> Software de equipo eléctrico</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233601 -</b> Software de controlador del motor</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233602 -</b> Software de monitor de energía</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233603 -</b> Software de control lógico programable</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                                    <!---->
                                                                                </ul>
                                                                            </sccatite-simple-tree>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </sccatite-simple-tree>
                                                        </div>
                                                    </li>
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233700 -</b> Software de administración de sistemas</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">43233701 -</b> Software de manejo de sistemas de empresas</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
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
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                    <li _ngcontent-ruw-c21="">
                        <!---->
                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">55000000 -</b> Publicaciones Impresas, Publicaciones Electrónicas y Accesorios</span>
                        <div _ngcontent-ruw-c21="">
                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                <!---->
                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                    <!---->
                                    <li _ngcontent-ruw-c21="">
                                        <!---->
                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">55110000 -</b> Material electrónico de referencia</span>
                                        <div _ngcontent-ruw-c21="">
                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                <!---->
                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                    <!---->
                                                    <li _ngcontent-ruw-c21="">
                                                        <!---->
                                                        <!----><button _ngcontent-ruw-c21="" class="btn btn-xs btn-secondary" type="button"><span _ngcontent-ruw-c21="" class="fa fa-caret-down"></span></button>
                                                        <!----><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">55111600 -</b> Material de referencia de software electrónico</span>
                                                        <div _ngcontent-ruw-c21="">
                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                <!---->
                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
                                                                    <!---->
                                                                    <li _ngcontent-ruw-c21="">
                                                                        <!---->
                                                                        <!---->
                                                                        <!----><label _ngcontent-ruw-c21="" class="radio-inline c-radio"><input _ngcontent-ruw-c21="" id="inlineradio2" name="i-radio" type="radio" value="option2"><span _ngcontent-ruw-c21="" class="fa fa-circle" onclick="pnb_press_select_item();" ></span></label><span _ngcontent-ruw-c21=""><b _ngcontent-ruw-c21="">55111601 -</b> Documentación de software o manuales de usuario electrónicos</span>
                                                                        <div _ngcontent-ruw-c21="">
                                                                            <sccatite-simple-tree _ngcontent-ruw-c21="" _nghost-ruw-c21="">
                                                                                <!---->
                                                                                <ul _ngcontent-ruw-c21="" class="list-without-style padding-left-20">
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
                                </ul>
                            </sccatite-simple-tree>
                        </div>
                    </li>
                </ul>
            </sccatite-simple-tree>
        </scrollable>
        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 30.9849px;"></div>
        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
    </div>
</div>


<!----><div _ngcontent-hmx-c7="" class="modal-footer"><button onclick="close_modal();" _ngcontent-hmx-c7="" class="btn test/insbtn-primary" data-dismiss="modal" type="button">Cerrar</button><button class="btn btn-primary" type="button" style="display: none;" id="boton-aceptar-select-item" onclick="close_modal();">Aceptar</button></div>