<?php

require_once 'autoload.inc.php';

define("DOMPDF_ENABLE_CSS_FLOAT", true);

// reference the Dompdf namespace
use Dompdf\Dompdf;


$varrr = <<<EOF
        
        
        <style>td{font-size:9pt;}th{background:gray;}</style>
       
<div class="cont-f">
                    <table width="100%" border="1" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="13%" class="f5" align="center" height="61">FORM PAC<br></td>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="87%">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody><tr>
                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center"><img src="https://www.sicoes.gob.bo/img/logo_mefp_forms.gif" height="87" width="113"></td>
                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="73%" class="f3"><p>  Programa Anual de Contrataciones</p>
                    </td>
                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center"><img src="https://www.sicoes.gob.bo/imagenes/logosicoes.gif" width="130" height="54"></td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>

    <table border="1" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="9" class="FilaPar"> 1.&nbsp;ENTIDAD CONVOCANTE </td>
        </tr>
        <tr align="center">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="4" class="FilaImpar"><div align="center">Código de la entidad </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="2" class="FilaImpar"><div align="center">Denominación de la Entidad</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="111" class="FilaImpar" align="center"><div align="center">Fax</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="136" colspan="2" class="FilaImpar"><div align="center">Teléfono</div></td>
        </tr>
        <tr align="center">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="76" class="TextoBotones">1606</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="6" class="TextoBotones"> - </td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="2" class="TextoBotones">00</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="2" class="TextoBotones">GOBIERNO AUTONOMO MUNICIPAL DE VILLAMONTES</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" class="TextoBotones">6722347</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="2" class="TextoBotones">6722379 / 6722993</td>
        </tr>
    </tbody></table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">2. CONTRATACIONES MENORES</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Forma de contratar</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                           <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">1</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICIÓN DE MATERIAL DE ESCRITORIO - DIRECCIÓN DE MERCADOS (SMDEL)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">36185</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 27/01/2017 08:43</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/01/2017 08:15</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">2</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICIÓN DE MATERIAL DE ESCRITORIO - SECRETARIA MUNICIPAL DE GESTIÓN TÉCNICA TERRITORIAL Y MEDIO AMBIENTE</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Recursos Especificos de las Municipalidades</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">49427,5</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 27/01/2017 08:37</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/01/2017 08:15</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">3</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE PROFESIONAL LIC. CONTADOR PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">38560</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:06</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">4</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE PROFESIONAL LIC. ADMINISTRADOR PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">40390</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:00</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">5</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE  PROFESIONAL ING. EN INFORMÁTICA PARA PRESTAR SUS SERVICIOS  EN EL HOSPITAL BÁSICO DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">38560</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 18:51</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">6</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud  Biotecnología Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Biotecnología 1)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">30960</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:32</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">7</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Lic. Bioquímica Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Bioquímica 11)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41600</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:23</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">8</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesionales En Salud Lic. Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Enfermería 8)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">39150</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:13</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">9</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesionales En Salud Lic. Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Enfermería 7)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">39150</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:11</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">10</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE PROFESIONAL LIC. EN NUTRICIÓN Y DIETETICA PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">34800</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 18:38</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">11</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACION DE PROFESIONAL ABOGADO PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">40390</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 18:44</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">12</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Lic. Bioquímica Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Bioquímica 6)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41600</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:12</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:20</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">13</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesionales En Salud Lic. Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Enfermería 26)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">39150</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:23</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:24</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">14</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 11)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:04</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">15</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Biotecnología Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Biotecnologa 4)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">30960</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:46</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">16</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesionales En Salud Lic. Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Enfermería 25)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">39150</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:23</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">17</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACION DE UN MEDICO GENERAL PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">40390</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:13</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">18</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 20)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:12</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">19</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 19)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:12</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">20</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Servicios Generales</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">APOYO AL TRANSPORTE ESCOLAR RURAL DISTRITO 5 RUTA 7: LAPACHAL - EL OCULTO - MONTEVEO - LA TRINCHERA - TUSCAL - LAS LOMAS - RODRIGUEZ - Km 1 - U.E. \"APLICACION BILINGUE\" VILLA MONTES, LA MISION Y VICEVERSA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">50000</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 08:56</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">21</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 8)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:01</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">22</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 6)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:58</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">23</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 5)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:58</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">24</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE PROFESIONAL EN SALUD AUXILIAR EN ENFERMERÍA PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO DE VILLA MONTES (AUXILIAR EN ENFERMERÍA 1)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:37</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">25</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De Un Medico General Para Prestar Sus Servicios En El Hospital Básico Villa Montes (Medico 4)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">40390</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:26</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">26</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De LIC. EN ENFERMERÍA PARA CENTROS DE SALUD DEPENDIENTES DE LA RED DE SALUD VILLA MONTES (LIC. ENFERMERÍA 1)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">34800</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 17:59</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">27</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De Profecional Medico Odontologo Para Centro De Salud Dependiente De La Red De Salud Villa Montes (Medico Odontologo 4 Tiempo Completo)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41760</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:04</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">28</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De Profecional Medico Odontologo Para Centro De Salud Dependiente De La Red De Salud Villa Montes (Medico Odontologo 3 Tiempo Completo)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41760</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:03</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">29</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Servicios Generales</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">APOYO AL TRANSPORTE ESCOLAR RURAL DISTRITO 5 RUTA 3: CIRCULACION - EL CRUCE - BRECHA LORETO - LORETO - EL BAÑADO - PICAFLOR - U.E.\"MISION SUECA\" CAPIRENDITA, CAPIRENDITA Y VICEVERSA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">50000</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 08:38</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">30</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De LIC. EN ENFERMERÍA PARA CENTROS DE SALUD DEPENDIENTES DE LA RED DE SALUD VILLA MONTES (LIC. ENFERMERÍA 9)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">34800</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 18:06</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">31</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 18)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:12</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">32</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Auxiliar En Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Auxiliar En Enfermería 7)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">25680</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 19:59</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 09/01/2017 20:18</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">33</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesional En Salud Lic. Bioquímica  Para Prestar Sus Servicios En El Hospital Básico de Villa Montes (Lic. Bioquímica 1)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41600</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 17:06</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">34</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratacion De Profecional Medico Odontologo Para Centro De Salud Dependiente De La Red De Salud Villa Montes (Medico Odontologo 2 Tiempo Completo)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">41760</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 18:59</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/01/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">35</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE  PROFESIONAL EN SALUD ECOGRAFISTA PARA PRESTAR SUS SERVICIOS EN EL HOSPITAL BÁSICO DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">30960</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 18:09</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">36</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">Contratación De Profesionales En Salud Lic. Enfermería Para Prestar Sus Servicios En El Hospital Básico De Villa Montes (Lic. Enfermería 11)</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Contratacion Menor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">39150</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 16:13</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 10/01/2017 19:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

             </table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">3. INSCRIPCION DE CONTRATACIONES PARA APOYO NACIONAL A LA PRODUCCION Y EMPLEO (ANPE)</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Forma de contratar</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                           <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">1</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICION DE LLANTAS PARA EL PARQUE AUTOMOTOR DEL G.A.M.V.M. CON CARGO AL PROGRAMA MANTENIMIENTO DE CALLES Y AV. VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Recursos Especificos de las Municipalidades</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Febrero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">385600</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/01/2017 10:47</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 13/02/2017 19:16</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 13/02/2017 19:42</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">2</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">SUPERVISION TECNICA: CONSTRUCCION PUENTES VEHICULARES VILLA MONTES CALLE VILLA NUEVA Y COCHABAMBA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Tesoro General de la Nacion - Coparticipacion Tributaria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Febrero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">640353,8</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 07/02/2017 19:08</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 15/02/2017 16:54</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 15/02/2017 16:54</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">3</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICION DE FILTROS, ACEITES Y ELEMENTOS DE DESGASTES PARA MANTENIMIENTO PREVENTIVO DE LA MIQUINARIA DEL G.A.M.V.M. CON CARGO AL PROGRAMA MANTENIMIENTO DE CALLES Y AV. VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Recursos Especificos de las Municipalidades</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Febrero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">272869,17</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/01/2017 10:45</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 31/01/2017 09:09</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">4</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICIÓN DE PANTALLAS Y MATERIAL ELÉCTRICO PARA ALUMBRADO PUBLICO ÁREA URBANA VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Recursos Especificos de las Municipalidades</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Marzo</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">999486</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/03/2017 11:34</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 07/03/2017 11:45</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">5</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Consultoria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">SUPERVISION TECNICA: CONST. AMP. SISTEMA DE ALCANTARILLADO SANITARIO COMUNIDAD TIGUIPA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Tesoro General de la Nacion - Impuesto Directo a los Hidrocarburos</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Marzo</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">311227,74</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 02/03/2017 11:47</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 07/03/2017 11:45</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">6</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Servicios Generales</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ALQUILER DE VIVIENDA PARA BRIGADA DE MÉDICOS CUBANOS</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (hasta Bs. 200.000)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">100000</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 27/03/2017 12:13</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/03/2017 11:15</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">7</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICIÓN DE PANTALLAS Y MATERIAL ELÉCTRICO PARA ALUMBRADO PUBLICO ÁREA RURAL VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Recursos Especificos de las Municipalidades</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">449482</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/04/2017 08:33</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 06/04/2017 08:34</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">8</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">EQUIPAMIENTO CENTRO DE SALUD BARRIO BOLIVAR</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Apoyo Nacional a la Produccion y Empleo (de Bs. 200.001 adelante)</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">358261,16</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 29/03/2017 18:05</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/03/2017 11:17</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/03/2017 11:17</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

            </tbody></table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">4. INSCRIPCION DE CONTRATACIONES POR LICITACION PUBLICA (LP)</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Tipo de Convocatoria</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                           <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">1</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Servicios Generales</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONTRATACIÓN DE SEGUROS EN LOS RAMOS AUTOMOTOR Y TODO RIESGO DE EQUIPO Y MAQUINARIA DE CONTRATISTA DEL GOBIERNO AUTÓNOMO MUNICIPAL DE VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Convocatoria Publica Nacional</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Enero</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">936510</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 16/01/2017 08:18</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 13/03/2017 19:27</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 13/03/2017 19:27</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">2</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Obras</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONST. AMP. SISTEMA DE ALCANTARILLADO SANITARIO COMUNIDAD TIGUIPA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Convocatoria Publica Nacional</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Tesoro General de la Nacion - Impuesto Directo a los Hidrocarburos</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Marzo</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">4857069,7</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 02/03/2017 11:46</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 07/03/2017 11:45</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">3</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Obras</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">AMPL. UNIDAD EDUCATIVA FAUSTINO SUAREZ ARNEZ B° EL CHAÑAR VILLA MONTES</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Convocatoria Publica Nacional</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Mayo</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">1781806,89</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 04/05/2017 08:37</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 04/05/2017 19:06</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

            </tbody></table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">5. INSCRIPCION DE CONTRATACIONES POR EXCEPCION</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Causal</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                           <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">1</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICION DEL ALIMENTO COMPLEMENTARIO NUTRIBEBE - MUNICIPIO DE VILLA MONTES - GESTION 2017</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Unico Proveedor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Regalias</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">349988</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 29/03/2017 18:00</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/03/2017 11:15</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

                    <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">2</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Bienes</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">ADQUISICION DEL COMPLEMENTO NUTRICIONAL CARMELO - MUNICIPIO DE VILLA MONTES - GESTION 2017</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Unico Proveedor</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Tesoro General de la Nacion - Coparticipacion Tributaria</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">848988</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 29/03/2017 08:30</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 30/03/2017 17:30</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 31/03/2017 19:28</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

            </tbody></table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">6. INSCRIPCION DE CONTRATACIONES DIRECTAS</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Causal</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                           <tr class="TextoBotones">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%" align="center">1</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Obras</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%" align="center">CONSTRUCCIÓN UNIDAD EDUCATIVA NIVEL INICIAL COLORADOS DE BOLIVIA</td>
                                    <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%" align="center">Otras causales señaladas en disposiciones específicas de contratación directa</td>
                                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%" align="center">Tesoro General de la Nacion</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">Abril</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%" align="center">2937109,44</td>
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%">
                    <table align="center" border="1" class="width100">
                        <tbody><tr class="FilaImpar">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Creación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%" align="center">Modificación</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">Publicación</td>
                        </tr>
                        <tr class="TextoBotones">
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 24/04/2017 10:51</td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center"> - </td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia - 27/04/2017 08:58</td>
                        </tr>
                    </tbody></table>
                </td>

            </tr>

            </tbody></table>

    <table class="width100" border="1">
        <tbody><tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" class="FilaPar">7. INSCRIPCION DE OTRAS MODALIDADES DEFINIDADS POR EL ORGANISMO FINANCIADOR</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="3%"><div align="center">#</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Tipo de Contratación</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="36%"><div align="center">Objeto de la contratación </div></td>
                            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="12%"><div align="center">Tipo de Convocatoria</div></td>
            
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="16%"><div align="center">Principal organismo financiador</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Mes estimado de inicio </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="4%"><div align="center">Precio referencial </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="21%" colspan="3"><div align="center">Datos del registro </div></td>
        </tr>
                   <tr class="TextoNegro11A">
                <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="10" align="left">No Existen Registros</td>
            </tr>
                    </tbody></table>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tbody><tr>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="9" class="FilaPar">8.      &nbsp;INFORMACION DE LAS VERSIONES REGISTRADAS DEL PAC </td>
    </tr>
    <tr class="FilaImpar">
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="11%" class="FilaImpar"><div align="center">Versión PAC </div></td>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%"><div align="center">Responsable Creación </div></td>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="14%"><div align="center">Fecha Creación</div></td>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="33%"><div align="center">Responsable Publicación</div></td>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="14%"><div align="center">Fecha  Publicación </div></td>
    </tr>
                <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.40</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">05/05/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">10/05/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.20</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">22/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">01/03/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.19</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">17/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">17/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.18</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">17/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">17/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.17</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">15/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">15/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.16</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">15/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">15/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.15</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">14/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">14/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.14</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">09/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">13/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.13</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">08/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">08/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.12</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">07/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">07/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.11</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">03/02/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">06/02/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.10</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">31/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">31/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.9</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">30/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">31/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.8</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">25/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">30/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.7</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">20/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">20/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.6</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">19/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">19/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.5</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">16/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">19/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.4</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">11/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">16/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.3</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">10/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">10/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.2</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">09/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">09/01/2017</td>
        </tr>
            <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">v.1</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">06/01/2017</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">hchuquimia</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" align="center">06/01/2017</td>
        </tr>
    </tbody></table>


    <table border="1" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tbody><tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="15" class="FilaPar">9. DATOS DEL ENVIO DEL FORMULARIO</td>
        </tr>
        <tr class="FilaImpar">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" height="20" colspan="6" rowspan="2" class="FilaImpar"><div align="right">Datos de la persona responsable del envío : </div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="81" align="center">Paterno</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="75" align="center">Materno</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="117" align="center">Nombre(s)</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="273" align="center">Cargo</td>
        </tr>
        <tr class="TextoBotones">
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" height="15">CHUQUIMIA</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="75">COSTANO</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="117">HEINOR</td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="273">Operador Sicoes Vm</td>
        </tr>
        <tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" height="20" colspan="6" class="FilaImpar"> <div align="right">Responsable publicación :</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="12"> <span class="TextoBotones">hchuquimia</span> </td>
        </tr>
        <tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" height="20" colspan="6" class="FilaImpar"> <div align="right">Fecha de envío :</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="12"> <span class="TextoBotones">10/05/2017</span> </td>
        </tr>
        <tr>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" height="20" colspan="6" class="FilaImpar"> <div align="right">Medio de envío :</div></td>
            <td style="font-size:9pt;color:#444444;border:1px solid gray;" colspan="12" class="TextoBotones">Internet</td>
        </tr>
    </tbody></table>

<table align="center" width="100%">
    <tbody><tr>
        <td style="font-size:9pt;color:#444444;border:1px solid gray;" width="15%" align="center"><input name="btnImprimir" type="button" class="TextoNegro10" onclick="openWindowmonx3('laKztuShsai04NfL06nHvdKmgrDm0t3WtcWW1JuitJTGoL6htrnRv6N5nYmXbYqD')" style="display:none;" value="Obtener Confirmación">
        </td><td style="font-size:9pt;color:#444444;border:1px solid gray;" width="77%" align="right">
        </td><td style="font-size:9pt;color:#444444;border:1px solid gray;" width="8%" align="center"><input name="btnImprimir" type="button" class="TextoNegro10" onclick="openWindownxprint();" style="display:none;" value="Imprimir">
    </td></tr>
</tbody></table>
                </div>
        
        
EOF;




$busc_imagenes = array('https://www.sicoes.gob.bo/img/logo_mefp_forms.gif');
$remm_imagenes = array('/home/infosico/public_html/contenido/imagenes/images/logo_infosicoes.png');




// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml(utf8_encode(str_replace($busc_imagenes,$remm_imagenes,$varrr)));

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A3', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>