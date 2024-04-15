<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$cantidad_desde = get('cantidad_desde');
$cantidad_hasta = get('cantidad_hasta');
$precio_descuento = get('precio_descuento');

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];

query("INSERT INTO simulador_descuentos 
(id_precio, id_prod, cantidad_desde, cantidad_hasta, precio_descuento)
 VALUES 
('0','$id_prod','$cantidad_desde','$cantidad_hasta','$precio_descuento')")

?>

<table _ngcontent-dvq-c32="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-dvq-c32="">
        <tr _ngcontent-dvq-c32="">
            <th _ngcontent-dvq-c32="" class="w-cog">Opciones</th>
            <th _ngcontent-dvq-c32="" class="text-center">Cantidad Desde</th>
            <th _ngcontent-dvq-c32="" class="text-center">Cantidad Hasta</th>
            <th _ngcontent-dvq-c32="" class="text-center">Precio</th>
            <th _ngcontent-dvq-c32="" class="text-center">Moneda</th>
            <th _ngcontent-dvq-c32="" class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody _ngcontent-dvq-c32="">
        <!---->
        <?php
        $rqdsc1 = query("SELECT * FROM simulador_descuentos WHERE id_prod='$id_prod' ");
        while($rqdsc2 = fetch($rqdsc1)){
        ?>
        <tr _ngcontent-dvq-c32="">
            <td _ngcontent-dvq-c32="" class="text-center">
                <div _ngcontent-dvq-c32="" class="btn-group" dropdown=""><button _ngcontent-dvq-c32="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-31" aria-expanded="false"><span _ngcontent-dvq-c32="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                    <ul _ngcontent-dvq-c32="" class="dropdown-menu" role="menu" style="left: 0px; right: auto;">
                        <!----><a _ngcontent-dvq-c32="" class="dropdown-item"><span _ngcontent-dvq-c32="" class="fa fa-edit text-primary"></span> Editar </a>
                        <!---->
                        <!---->
                        <!---->
                        <!----><a _ngcontent-dvq-c32="" class="dropdown-item"><span _ngcontent-dvq-c32="" class="fa fa-trash text-danger"></span> Eliminar </a>
                    </ul>
                </div>
            </td>
            <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['cantidad_desde']; ?></td>
            <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['cantidad_hasta']; ?> </td>
            <td _ngcontent-dvq-c32="" class="text-right"><?php echo $rqdsc2['precio_descuento']; ?> </td>
            <td _ngcontent-dvq-c32="">BOLIVIANOS</td>
            <td _ngcontent-dvq-c32="">ELABORADO</td>
        </tr>
        <?php
        }
        ?>
        <!---->
    </tbody>
</table>