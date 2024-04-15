<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$ubicacion = post('ubicacion');
$precio = post('precio');
$cnt_stock = post('cnt_stock');
$fecha_vigencia = post('fecha_vigencia');

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];

query("INSERT INTO simulador_prods_precios  
(id_usuario, id_prod, ubicacion, precio, cnt_stock, fecha_vigencia) 
VALUES 
('$id_usuario','$id_prod','$ubicacion','$precio','$cnt_stock','$fecha_vigencia') ");
$id_precio = insert_id();

query("UPDATE simulador_descuentos SET id_precio='$id_precio',id_prod='0' WHERE id_prod='$id_prod' ");

?>
<table _ngcontent-cyf-c29="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-cyf-c29="">
        <tr _ngcontent-cyf-c29="">
            <th _ngcontent-cyf-c29="" class="text-center w-cog">Opciones</th>
            <th _ngcontent-cyf-c29="" class="text-center">Ubicación</th>
            <th _ngcontent-cyf-c29="" class="text-center">Precio Actual</th>
            <th _ngcontent-cyf-c29="" class="text-center">Moneda</th>
            <th _ngcontent-cyf-c29="" class="text-center">Vigente Hasta</th>
            <th _ngcontent-cyf-c29="" class="text-center">Cantidad en Stock</th>
            <th _ngcontent-cyf-c29="" class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody _ngcontent-cyf-c29="">
        <?php
        $rqatr1 = query("SELECT * FROM simulador_prods_precios WHERE id_usuario='$id_usuario' AND id_prod='$id_prod' ");
        while ($rqatr2 = fetch($rqatr1)) {
        ?>
            <!---->
            <tr _ngcontent-cyf-c29="">
                <td _ngcontent-cyf-c29="" class="text-center">
                    <div _ngcontent-cyf-c29="" class="btn-group" dropdown=""><button _ngcontent-cyf-c29="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-cyf-c29="" class="fa fa-cog text-primary"></span></button>
                        <!---->
                    </div>
                </td>
                <td _ngcontent-cyf-c29=""><?php echo $rqatr2['ubicacion']; ?></td>
                <td _ngcontent-cyf-c29="" class="text-right"><?php echo $rqatr2['precio']; ?> </td>
                <td _ngcontent-cyf-c29="">BOLIVIANOS</td>
                <td _ngcontent-cyf-c29=""><?php echo date("d/m/Y",strtotime($rqatr2['fecha_vigencia'])); ?></td>
                <td _ngcontent-cyf-c29="" class="text-right"><?php echo $rqatr2['cnt_stock']; ?></td>
                <td _ngcontent-cyf-c29="">ELABORADO</td>
            </tr>
        <?php
        }
        ?>
        <tr _ngcontent-cyf-c29="" style="height: 120px;"></tr>
    </tbody>
</table>