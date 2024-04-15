<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$ids_items = post('ids_items');

$ar1 = explode(',', $ids_items);
foreach ($ar1 as $id_item) {
    if (isset_post('plazo-' . $id_item)) {
        $precio_ofertado = post('plazo-' . $id_item);
        query("UPDATE simulador_items SET plazo_entrega='$precio_ofertado' WHERE id='$id_item' LIMIT 1 ");
    }
}

$cuce = '21-0513-00-1114217-1-1';
$id_usuario = usuario('id_sim');

$rqpto1 = query("SELECT sum(precio_ofertado*cantidad) AS total FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
$rqpto2 = fetch($rqpto1);
$precio_total_ofertado = $rqpto2['total'];

?>

<table _ngcontent-jun-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
    <thead _ngcontent-yfk-c39="">
        <!---->
        <tr _ngcontent-yfk-c39="">
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center border-right-color" colspan="5">Definido por la Entidad</th>
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
        </tr>
        <tr _ngcontent-yfk-c39="">
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center">#</th>
            <th _ngcontent-yfk-c39="" class="text-center">Descripción del Bien o Servicio</th>
            <th _ngcontent-yfk-c39="" class="text-center">Unidad de Medida</th>
            <th _ngcontent-yfk-c39="" class="text-center">Cantidad</th>
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center"> Plazo de Entrega Referencial </th>
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center"> Plazo de Entrega Propuesto (Dias Calendario) </th>
        </tr>
    </thead>
    <tbody _ngcontent-pjb-c39="">
        <!---->
        <!---->
        <?php
        $rqdir1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
        $cnt = 1;
        while ($rqdir2 = fetch($rqdir1)) {
        ?>
            <tr _ngcontent-pjb-c39="">
                <!---->
                <!---->
                <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['descripcion']; ?></td>
                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['unidad_medida']; ?></td>
                <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdir2['cantidad']; ?></td>
                <!---->
                <td _ngcontent-pjb-c39="" class="text-right"></td>
                <!---->
                <td _ngcontent-pjb-c39="" class="text-right"> <?php echo $rqdir2['plazo_entrega']; ?> d&iacute;as</td>
                <!---->
            </tr>
        <?php
        }
        ?>
        <!---->
    </tbody>
    <tfoot _ngcontent-jun-c39="">
        <!---->
        <!---->
        <tr _ngcontent-jun-c39="" style="height: 110px;"></tr>
    </tfoot>
</table>