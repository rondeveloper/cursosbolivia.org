<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$tipo_enlace = post('tipo_enlace');
$enlace = post('enlace');

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];

query("INSERT INTO simulador_prods_enlaces 
(id_usuario, id_prod, tipo_enlace, enlace) 
VALUES 
('$id_usuario','$id_prod','$tipo_enlace','$enlace') ");

?>

<table _ngcontent-sxb-c30="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-sxb-c30="">
        <tr _ngcontent-sxb-c30="">
            <th _ngcontent-sxb-c30="" class="text-center w-cog">Opciones</th>
            <th _ngcontent-sxb-c30="" class="text-center">Tipo Enlace</th>
            <th _ngcontent-sxb-c30="" class="text-center">Enlace</th>
            <th _ngcontent-sxb-c30="" class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody _ngcontent-sxb-c30="">
        <!---->
        <?php
        $rqatr1 = query("SELECT * FROM simulador_prods_enlaces WHERE id_usuario='$id_usuario' AND id_prod='$id_prod' ");
        while ($rqatr2 = fetch($rqatr1)) {
        ?>
            <tr _ngcontent-sxb-c30="">
            <td _ngcontent-sxb-c30="" class="text-center">
                <div _ngcontent-sxb-c30="" class="btn-group" dropdown=""><button _ngcontent-sxb-c30="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true" aria-expanded="false"><span _ngcontent-sxb-c30="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                    <ul _ngcontent-sxb-c30="" class="dropdown-menu" role="menu" style="left: 0px; right: auto;"><a _ngcontent-sxb-c30="" class="dropdown-item"><span _ngcontent-sxb-c30="" class="fa fa-eye"></span> Consultar </a>
                        <!----><a _ngcontent-sxb-c30="" class="dropdown-item"><span _ngcontent-sxb-c30="" class="fa fa-edit text-primary"></span> Editar </a>
                        <!---->
                        <!---->
                        <!---->
                        <!----><a _ngcontent-sxb-c30="" class="dropdown-item"><span _ngcontent-sxb-c30="" class="fa fa-trash text-danger"></span> Eliminar </a>
                        <!----><a _ngcontent-sxb-c30="" class="dropdown-item"><span _ngcontent-sxb-c30="" class="fa fa-download text-primary"></span> Descargar </a>
                    </ul>
                </div>
            </td>
            <td _ngcontent-sxb-c30=""><?php echo $rqatr2['tipo_enlace']; ?></td>
            <td _ngcontent-sxb-c30=""><?php echo $rqatr2['enlace']; ?></td>
            <td _ngcontent-sxb-c30="">ELABORADO</td>
        </tr>
        <?php
        }
        ?>
        <tr _ngcontent-sxb-c30="" style="height: 125px;"></tr>
    </tbody>
</table>
