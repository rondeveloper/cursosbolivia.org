<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>
<div style="padding: 20px;">
<table class="table table-bordered table-responsive">
    <thead _ngcontent-jbq-c12="">
        <tr _ngcontent-jbq-c12="">
            <th _ngcontent-jbq-c12="" class="w-cog">#</th>
            <th _ngcontent-jbq-c12="">Actividad</th>
            <th _ngcontent-jbq-c12="">Fecha y Hora</th>
            <th _ngcontent-jbq-c12="">Lugar</th>
        </tr>
    </thead>
    <tbody _ngcontent-jbq-c12="">
        <!---->
        <!---->
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">1</td>
            <td _ngcontent-jbq-c12="">Inicio del Proceso de Contrataci&oacute;n</td>
            <td _ngcontent-jbq-c12="">15/04/2021</td>
            <td _ngcontent-jbq-c12="">SUCRE CALLE ESPANA NRO 117 SECCION ALMACENES</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">2</td>
            <td _ngcontent-jbq-c12="">Inspecci&oacute;n previa</td>
            <td _ngcontent-jbq-c12=""></td>
            <td _ngcontent-jbq-c12=""></td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">3</td>
            <td _ngcontent-jbq-c12="">Reunion de aclaracion</td>
            <td _ngcontent-jbq-c12=""></td>
            <td _ngcontent-jbq-c12=""></td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">4</td>
            <td _ngcontent-jbq-c12="">Presentacion de propuestas</td>
            <td _ngcontent-jbq-c12="">21/04/2021 10:30</td>
            <td _ngcontent-jbq-c12="">ELECTR&Oacute;NICO</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">5</td>
            <td _ngcontent-jbq-c12="">Inicio de Subasta</td>
            <td _ngcontent-jbq-c12="">21/04/2021 10:46</td>
            <td _ngcontent-jbq-c12="">ELECTR&Oacute;NICO</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">6</td>
            <td _ngcontent-jbq-c12="">Cierre preeliminar de Subasta</td>
            <td _ngcontent-jbq-c12="">21/04/2021 11:19</td>
            <td _ngcontent-jbq-c12="">ELECTR&Oacute;NICO</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">7</td>
            <td _ngcontent-jbq-c12="">Resultado de Subasta</td>
            <td _ngcontent-jbq-c12="">21/04/2021 11:29</td>
            <td _ngcontent-jbq-c12="">ELECTR&Oacute;NICO</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">8</td>
            <td _ngcontent-jbq-c12="">Apertura de Sobres</td>
            <td _ngcontent-jbq-c12="">21/04/2021 11:30</td>
            <td _ngcontent-jbq-c12="">ELECTR&Oacute;NICO</td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">9</td>
            <td _ngcontent-jbq-c12="">Adjudicaci&oacute;n</td>
            <td _ngcontent-jbq-c12="">30/04/2021</td>
            <td _ngcontent-jbq-c12=""></td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">10</td>
            <td _ngcontent-jbq-c12="">Firma de contrato</td>
            <td _ngcontent-jbq-c12="">17/05/2021</td>
            <td _ngcontent-jbq-c12=""></td>
        </tr>
        <tr _ngcontent-jbq-c12="">
            <td _ngcontent-jbq-c12="">11</td>
            <td _ngcontent-jbq-c12="">Entrega definitiva</td>
            <td _ngcontent-jbq-c12="">01/06/2021</td>
            <td _ngcontent-jbq-c12=""></td>
        </tr>
    </tbody>
</table>
</div>

<div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cerrar</button>
</div>