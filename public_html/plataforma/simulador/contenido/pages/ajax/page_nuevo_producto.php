<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$descripcion_corta = '';
$descripcion_larga = '';
$pais_origen = '';

$id_prod = (int)get('id_prod');

if($id_prod!=0){
    $_SESSION['id_prod__CURRENTADD'] = $id_prod;
}

if($id_prod==0){
    $_SESSION['id_prod__CURRENTADD'] = '0';
}

if(isset($_SESSION['id_prod__CURRENTADD']) && $_SESSION['id_prod__CURRENTADD']!='0'){
    $id_prod = $_SESSION['id_prod__CURRENTADD'];
    $rqdp1 = query("SELECT * FROM simulador_prods WHERE id='$id_prod' ");
    $rqdp2 = fetch($rqdp1);

    $descripcion_corta = $rqdp2['descripcion_corta'];
    $descripcion_larga = $rqdp2['descripcion_larga'];
    $pais_origen = $rqdp2['pais_origen'];
}
?>
<style>.opciones[_ngcontent-ruw-c23]{position:fixed;right:0;z-index:1}.opciones[_ngcontent-ruw-c23]   .btn[_ngcontent-ruw-c23]{box-shadow:0 0 10px #666;border:0;margin-top:20px;padding:0}.opciones[_ngcontent-ruw-c23]   .btn-inverse[_ngcontent-ruw-c23]{background:#5d9cec;border-radius:10px 0 0 10px;margin-left:35px;padding:5px}.opciones[_ngcontent-ruw-c23]   .btn-inverse[_ngcontent-ruw-c23]:hover{background:#115f77}.opciones[_ngcontent-ruw-c23]   .btn-inverse[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23], .opciones[_ngcontent-ruw-c23]   .btn[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23]{font-size:23px}.opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]{box-shadow:0 1px 5px rgba(0,0,0,.2);background:#fff;border-radius:5px;margin-top:20px;padding:5px 0!important}.opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   div[_ngcontent-ruw-c23], .opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   span[_ngcontent-ruw-c23]{text-align:center}.opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   div[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23], .opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   span[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23]{font-size:18px;cursor:pointer;margin:3px 10px;padding:5px}.opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   div[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23]:hover, .opciones[_ngcontent-ruw-c23]   .affix[_ngcontent-ruw-c23]   span[_ngcontent-ruw-c23]   i[_ngcontent-ruw-c23]:hover{background:#115f77;border-radius:3px;color:#fff;padding:5px}.popover[_ngcontent-ruw-c23]{max-width:100%}.cursor-pointer[_ngcontent-ruw-c23]{cursor:pointer}</style>

<router-outlet _ngcontent-ruw-c1=""></router-outlet>
<pcaitem-screen _nghost-ruw-c22="">
    <!---->
    <botones-opciones _ngcontent-ruw-c22="" _nghost-ruw-c23="">
        <div _ngcontent-ruw-c23="" class="opciones affix">
            <!---->
            <!---->
            <div _ngcontent-ruw-c23="" class="btn btn-inverse" placement="left" tooltip="Ocultar" aria-describedby="tooltip-6"><i _ngcontent-ruw-c23="" class="fa fa-chevron-circle-right"></i></div>
            <!---->
            <div _ngcontent-ruw-c23="" class="affix">
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
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center"><button onclick="page_nuevo_producto();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-primary btn-lg" id=" 1" aria-describedby="tooltip-7"><span style="background: white;
    padding: 3px 7px;
    color: #5d9cec;
    border-radius: 50%;
    font-size: 12pt;
    font-weight: bold;">P</span></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-active">Producto</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center"><button onclick="page_nuevo_producto_p2();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-gray disabled" id=" 2" aria-describedby="tooltip-8"><i _ngcontent-ruw-c24="" class="fas fa-camera"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-inactive">Imágenes</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center"><button onclick="page_nuevo_producto_p3();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-gray disabled" id=" 3" aria-describedby="tooltip-9"><i _ngcontent-ruw-c24="" class="fas fa-plus"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-inactive">Atributos</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center"><button onclick="page_nuevo_producto_p4();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-gray disabled" id=" 4" aria-describedby="tooltip-10"><i _ngcontent-ruw-c24="" class="fas fa-dollar-sign"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-inactive">Precios</span></div>
                    <div _ngcontent-ruw-c24="" class="p-2 bd-highlight text-center"><button onclick="page_nuevo_producto_p5();" _ngcontent-ruw-c24="" tooltip="" class="btn btn-circle btn-secondary btn-gray disabled" id=" 5" aria-describedby="tooltip-11"><i _ngcontent-ruw-c24="" class="fas fa-external-link-alt"></i></button><br _ngcontent-ruw-c24=""><span _ngcontent-ruw-c24="" class="sp-title-inactive">Adjuntos</span></div>
                </div>
            </wizard>
        </div>
    </div>
    <div _ngcontent-ruw-c22="" class="row w-100">
        <div _ngcontent-ruw-c22="" class="col-lg-3 col-sm-12 col-md-12 col-12">
            <!---->
        </div>
        <div _ngcontent-ruw-c22="" class="col-lg-9 col-sm-12 col-md-12 col-12">
            <!---->
            <!---->
            <pcaitem-doc-fragment _ngcontent-ruw-c22="" _nghost-ruw-c26="">
                <div _ngcontent-ruw-c26="" class="card card-default">
                    <div _ngcontent-ruw-c26="" class="card-header">
                        <div _ngcontent-ruw-c26="" class="card-title">Datos del Producto</div>
                    </div>
                    <div _ngcontent-ruw-c26="" class="card-body">
                    <form id="FORM-new-prod" action="" method="post" onsubmit="return false;">
                        <div _ngcontent-ruw-c26="" class="col-md-10 offset-md-1">
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c26="" class="mt">Descripción Corta</label></div>
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12">
                                <!----><input name="descripcion_corta" value="<?php echo $descripcion_corta; ?>" _ngcontent-ruw-c26="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="29" placeholder="" type="text">
                                <!---->
                                <!---->
                            </div>
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c26="" class="mt">Descripción Larga</label></div>
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12">
                                <!----><textarea name="descripcion_larga" _ngcontent-ruw-c26="" class="form-control note-editor note-editor-margin ng-untouched ng-pristine ng-valid" maxlength="600" rows="8" style="resize: none;padding-top:5px;"><?php echo $descripcion_larga; ?></textarea>
                                <!---->
                                <!---->
                            </div>
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-ruw-c26="" class="mt">País Origen</label></div>
                            <div _ngcontent-ruw-c26="" class="col-12 col-sm-12 col-md-12  col-lg-12">
                                <div _ngcontent-ruw-c26="" class="btn-group" dropdown="">
                                <select _ngcontent-ruw-c26="" class="form-control ng-untouched ng-pristine ng-invalid" name="pais_origen" required="">
                                        <!---->
                                        <option _ngcontent-ruw-c26="" value="BO">BOLIVIA </option>
                                        <option _ngcontent-ruw-c26="" value="US">ESTADOS UNIDOS </option>
                                        <option _ngcontent-ruw-c26="" value="CN">CHINA </option>
                                        <option _ngcontent-ruw-c26="" value="AF">AFGANISTAN </option>
                                        <option _ngcontent-ruw-c26="" value="AL">ALBANIA </option>
                                        <option _ngcontent-ruw-c26="" value="DE">ALEMANIA </option>
                                        <option _ngcontent-ruw-c26="" value="DZ">ALGERIA </option>
                                        <option _ngcontent-ruw-c26="" value="AD">ANDORRA </option>
                                        <option _ngcontent-ruw-c26="" value="AO">ANGOLA </option>
                                        <option _ngcontent-ruw-c26="" value="AR">ARGENTINA </option>
                                        <option _ngcontent-ruw-c26="" value="AM">ARMENIA </option>
                                        <option _ngcontent-ruw-c26="" value="AW">ARUBA </option>
                                        <option _ngcontent-ruw-c26="" value="AU">AUSTRALIA </option>
                                        <option _ngcontent-ruw-c26="" value="AT">AUSTRIA </option>
                                        <option _ngcontent-ruw-c26="" value="BS">BAHAMAS </option>
                                        <option _ngcontent-ruw-c26="" value="BD">BANGLADESH </option>
                                        <option _ngcontent-ruw-c26="" value="BB">BARBADOS </option>
                                        <option _ngcontent-ruw-c26="" value="BE">BELGICA </option>
                                        <option _ngcontent-ruw-c26="" value="BZ">BELICE </option>
                                        <option _ngcontent-ruw-c26="" value="BM">BERMUDA </option>
                                        <option _ngcontent-ruw-c26="" value="BA">BOSNIA-HERZEGOWINA </option>
                                        <option _ngcontent-ruw-c26="" value="BR">BRASIL </option>
                                        <option _ngcontent-ruw-c26="" value="BG">BULGARIA </option>
                                        <option _ngcontent-ruw-c26="" value="CV">CABO VERDE </option>
                                        <option _ngcontent-ruw-c26="" value="CA">CANADA </option>
                                        <option _ngcontent-ruw-c26="" value="CL">CHILE </option>
                                        <option _ngcontent-ruw-c26="" value="KY">CHIPRE </option>
                                        <option _ngcontent-ruw-c26="" value="CO">COLOMBIA </option>
                                        <option _ngcontent-ruw-c26="" value="KO">COREA </option>
                                        <option _ngcontent-ruw-c26="" value="CR">COSTA-RICA </option>
                                        <option _ngcontent-ruw-c26="" value="HR">CROACIA </option>
                                        <option _ngcontent-ruw-c26="" value="CU">CUBA </option>
                                        <option _ngcontent-ruw-c26="" value="CC">CURACAO </option>
                                        <option _ngcontent-ruw-c26="" value="DK">DINAMARCA </option>
                                        <option _ngcontent-ruw-c26="" value="EC">ECUADOR </option>
                                        <option _ngcontent-ruw-c26="" value="EG">EGIPTO </option>
                                        <option _ngcontent-ruw-c26="" value="SV">EL SALVADOR </option>
                                        <option _ngcontent-ruw-c26="" value="AE">EMIRATOS ARABES UNIDOS </option>
                                        <option _ngcontent-ruw-c26="" value="SK">ESLOVAQUIA </option>
                                        <option _ngcontent-ruw-c26="" value="ES">ESPAÑA </option>
                                        <option _ngcontent-ruw-c26="" value="PH">FILIPINAS </option>
                                        <option _ngcontent-ruw-c26="" value="FI">FINLANDIA </option>
                                        <option _ngcontent-ruw-c26="" value="FR">FRANCIA </option>
                                        <option _ngcontent-ruw-c26="" value="GH">GHANA </option>
                                        <option _ngcontent-ruw-c26="" value="GI">GIBRALTAR </option>
                                        <option _ngcontent-ruw-c26="" value="GR">GRECIA </option>
                                        <option _ngcontent-ruw-c26="" value="GT">GUATEMALA </option>
                                        <option _ngcontent-ruw-c26="" value="GN">GUINEA </option>
                                        <option _ngcontent-ruw-c26="" value="HT">HAITI </option>
                                        <option _ngcontent-ruw-c26="" value="NL">HOLANDA </option>
                                        <option _ngcontent-ruw-c26="" value="HN">HONDURAS </option>
                                        <option _ngcontent-ruw-c26="" value="HK">HONG KONG </option>
                                        <option _ngcontent-ruw-c26="" value="HU">HUNGRIA </option>
                                        <option _ngcontent-ruw-c26="" value="IN">INDIA </option>
                                        <option _ngcontent-ruw-c26="" value="ID">INDONESIA </option>
                                        <option _ngcontent-ruw-c26="" value="GB">INGLATERRA </option>
                                        <option _ngcontent-ruw-c26="" value="IR">IRAN </option>
                                        <option _ngcontent-ruw-c26="" value="IQ">IRAQ </option>
                                        <option _ngcontent-ruw-c26="" value="IE">IRLANDA </option>
                                        <option _ngcontent-ruw-c26="" value="IM">ISLA DE MAN </option>
                                        <option _ngcontent-ruw-c26="" value="IS">ISLANDIA </option>
                                        <option _ngcontent-ruw-c26="" value="CY">ISLAS CAIMAN </option>
                                        <option _ngcontent-ruw-c26="" value="IL">ISRAEL </option>
                                        <option _ngcontent-ruw-c26="" value="IT">ITALIA </option>
                                        <option _ngcontent-ruw-c26="" value="JM">JAMAICA </option>
                                        <option _ngcontent-ruw-c26="" value="JP">JAPON </option>
                                        <option _ngcontent-ruw-c26="" value="JO">JORDAN </option>
                                        <option _ngcontent-ruw-c26="" value="KW">KUWAIT </option>
                                        <option _ngcontent-ruw-c26="" value="LB">LIBANO </option>
                                        <option _ngcontent-ruw-c26="" value="LR">LIBERIA </option>
                                        <option _ngcontent-ruw-c26="" value="LT">LITUANIA </option>
                                        <option _ngcontent-ruw-c26="" value="MO">MACAU </option>
                                        <option _ngcontent-ruw-c26="" value="MG">MADAGASCAR </option>
                                        <option _ngcontent-ruw-c26="" value="MY">MALAYSIA </option>
                                        <option _ngcontent-ruw-c26="" value="MT">MALTA </option>
                                        <option _ngcontent-ruw-c26="" value="MX">MEXICO </option>
                                        <option _ngcontent-ruw-c26="" value="ML">MOLDAVIA </option>
                                        <option _ngcontent-ruw-c26="" value="MP">MULTIPAIS </option>
                                        <option _ngcontent-ruw-c26="" value="NI">NICARAGUA </option>
                                        <option _ngcontent-ruw-c26="" value="NG">NIGERIA </option>
                                        <option _ngcontent-ruw-c26="" value="NO">NORUEGA </option>
                                        <option _ngcontent-ruw-c26="" value="PB">PAÍSES BAJOS </option>
                                        <option _ngcontent-ruw-c26="" value="PK">PAKISTAN </option>
                                        <option _ngcontent-ruw-c26="" value="PA">PANAMA </option>
                                        <option _ngcontent-ruw-c26="" value="PY">PARAGUAY </option>
                                        <option _ngcontent-ruw-c26="" value="PE">PERU </option>
                                        <option _ngcontent-ruw-c26="" value="PL">POLONIA </option>
                                        <option _ngcontent-ruw-c26="" value="PT">PORTUGAL </option>
                                        <option _ngcontent-ruw-c26="" value="PR">PUERTO-RICO </option>
                                        <option _ngcontent-ruw-c26="" value="QA">QATAR </option>
                                        <option _ngcontent-ruw-c26="" value="UK">REINO UNIDO </option>
                                        <option _ngcontent-ruw-c26="" value="CZ">REPUBLICA CHECA </option>
                                        <option _ngcontent-ruw-c26="" value="MA">República de Malta </option>
                                        <option _ngcontent-ruw-c26="" value="DO">REPUBLICA-DOMINICANA </option>
                                        <option _ngcontent-ruw-c26="" value="RO">RUMANIA </option>
                                        <option _ngcontent-ruw-c26="" value="RU">RUSIA </option>
                                        <option _ngcontent-ruw-c26="" value="SG">SINGAPUR </option>
                                        <option _ngcontent-ruw-c26="" value="ZA">SUD-AFRICA </option>
                                        <option _ngcontent-ruw-c26="" value="SE">SUECIA </option>
                                        <option _ngcontent-ruw-c26="" value="CH">SUIZA </option>
                                        <option _ngcontent-ruw-c26="" value="TW">TAIWAN </option>
                                        <option _ngcontent-ruw-c26="" value="TR">TURQUIA </option>
                                        <option _ngcontent-ruw-c26="" value="UA">UCRANIA </option>
                                        <option _ngcontent-ruw-c26="" value="UY">URUGUAY </option>
                                        <option _ngcontent-ruw-c26="" value="VA">VATICANO </option>
                                        <option _ngcontent-ruw-c26="" value="VE">VENEZUELA </option>
                                    </select>
                                    </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <botones-opciones-footer _ngcontent-ruw-c26="" _nghost-ruw-c20="">
                    <div _ngcontent-ruw-c20="" class="row">
                        <div _ngcontent-ruw-c20="" class="col-12 text-right">
                            <!---->
                            <!----><a _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer" onclick="page_nuevo_bien();"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-left fa-stack-1x fa-inverse"></i></a>
                            <!----><a _ngcontent-ruw-c20="" class="fa-stack fa-lg cursor-pointer" onclick="page_nuevo_producto_p2();"><i _ngcontent-ruw-c20="" class="fa fa-circle fa-stack-2x text-primary"></i><i _ngcontent-ruw-c20="" class="fa fa-arrow-right fa-stack-1x fa-inverse"></i></a>
                        </div>
                    </div>
                </botones-opciones-footer>
            </pcaitem-doc-fragment>
            <!---->
            <!---->
            <!---->
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