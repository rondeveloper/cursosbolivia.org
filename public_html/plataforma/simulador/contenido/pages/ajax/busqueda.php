<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$busc = get('busc');

?>

<thead _ngcontent-jbq-c12="">
    <tr _ngcontent-jbq-c12="">
        <th _ngcontent-jbq-c12="" class="w-cog">Opciones</th>
        <th _ngcontent-jbq-c12="">CUCE</th>
        <th _ngcontent-jbq-c12="">Entidad</th>
        <th _ngcontent-jbq-c12="">Objeto de Contratación</th>
        <th _ngcontent-jbq-c12="">Modalidad</th>
        <th _ngcontent-jbq-c12="">Fecha Inicio Subasta</th>
        <th _ngcontent-jbq-c12="">Fecha Cierre Preliminar</th>
        <th _ngcontent-jbq-c12="">Estado Subasta</th>
    </tr>
</thead>
<tbody _ngcontent-jbq-c12="">
    <!---->
    <!---->
    <tr _ngcontent-jbq-c12="">
    <td _ngcontent-lkh-c21="" class="text-center"><div _ngcontent-lkh-c21="" class="btn-group open show" dropdown=""><button _ngcontent-lkh-c21="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" id="button-autoclose1" type="button" aria-haspopup="true" aria-expanded="true"><span _ngcontent-lkh-c21="" class="fa fa-cog text-primary"></span></button><!----><ul _ngcontent-lkh-c21="" aria-labelledby="button-autoclose1" class="dropdown-menu show" id="dropdown-autoclose1" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);"><a _ngcontent-lkh-c21="" class="dropdown-item "><i _ngcontent-lkh-c21="" class="far fa-file-pdf"></i> Ficha del Proceso </a><!----><!----><!----><a _ngcontent-lkh-c21="" class="dropdown-item "><span _ngcontent-lkh-c21="" class="fa fa-eye text-primary"></span> Consultar </a></ul></div></td>
        <td _ngcontent-jbq-c12="">21-0585-00-1112108-1-1</td>
        <td _ngcontent-jbq-c12="">Agencia Boliviana Espacial</td>
        <td _ngcontent-jbq-c12="">Compra De Material De Escritorio Para El Abastecimiento Del Almacen De La Abe - Gestión 2021</td>
        <td _ngcontent-jbq-c12="">ANPE</td>
        <td _ngcontent-jbq-c12="">19/02/2021 15:20</td>
        <td _ngcontent-jbq-c12="">20/02/2021</td>
        <td _ngcontent-jbq-c12="">En curso</td>
    </tr>
    <tr _ngcontent-jbq-c12="" style="height:80px;"></tr>
</tbody>