<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>


<table _ngcontent-pcb-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
    <thead _ngcontent-pcb-c39="">
        <!---->
        <tr _ngcontent-pcb-c39="">
            <!---->
            <th _ngcontent-pcb-c39="" class="text-center"></th>
            <!---->
            <th _ngcontent-pcb-c39="" class="text-center">#</th>
            <th _ngcontent-pcb-c39="" class="text-center">Descripción del Bien o Servicio</th>
            <th _ngcontent-pcb-c39="" class="text-center">Unidad de Medida</th>
            <th _ngcontent-pcb-c39="" class="text-center">Cantidad</th>
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <th _ngcontent-pcb-c39="" class="text-center">Documentos adjuntos</th>
            <!---->
            <!---->
        </tr>
    </thead>
    <tbody _ngcontent-pcb-c39="">
        <?php
        $id_usuario = usuario('id_sim');
        $cuce = '21-0513-00-1114217-1-1';
        $rqitm1 = query("SELECT * FROM simulador_items WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
        $cnt = 1;
        if (num_rows($rqitm1) == 0) {
        ?>
            <tr _ngcontent-ruw-c59="" class="ng-star-inserted">
                <td _ngcontent-ruw-c59="" colspan="20">No hay registro de Ítems</td>
            </tr>
        <?php
        }
        while ($rqitm2 = fetch($rqitm1)) {
        ?>
            <tr _ngcontent-pcb-c39="">
                <!---->
                <td _ngcontent-pcb-c39="" class="text-center w-cog">
                    <div _ngcontent-pcb-c39="" class="btn-group" dropdown="">
                        <button onclick="dropdown_prnd_item(<?php echo $rqitm2['id']; ?>);" _ngcontent-pcb-c39="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-14">
                            <span _ngcontent-pcb-c39="" class="fa fa-cog text-primary"></span>
                        </button>
                        <!---->
                        <ul id="id-dropdown_prnd_item-<?php echo $rqitm2['id']; ?>" _ngcontent-lsr-c39="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                            <!----><a onclick="prnd_items_doctec(<?php echo $rqitm2['id']; ?>);dropdown_prnd_item(<?php echo $rqitm2['id']; ?>);" _ngcontent-lsr-c39="" class="dropdown-item text-dark"><span _ngcontent-lsr-c39="" class="fa fa-upload text-primary"></span> Documentos Adjuntos </a>
                            <!---->
                            <!----><a _ngcontent-lsr-c39="" class="dropdown-item text-dark"><span _ngcontent-lsr-c39="" class="fa fa-trash text-danger"></span> Eliminar </a>
                        </ul>
                    </div>
                </td>
                <!---->
                <td _ngcontent-pcb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                <td _ngcontent-pcb-c39=""><?php echo $rqitm2['descripcion']; ?></td>
                <td _ngcontent-pcb-c39=""><?php echo $rqitm2['unidad_medida']; ?></td>
                <td _ngcontent-pcb-c39="" class="text-right"><?php echo $rqitm2['cantidad']; ?></td>
                <!---->
                <!---->
                <!---->
                <!---->
                <!---->
                <td _ngcontent-pcb-c39="">
                    <?php
                    $codigo_doc = 'item_' . $rqitm2['id'];
                    $id_usuario = usuario('id_sim');
                    $rqfi1 = query("SELECT * FROM simulador_files WHERE codigo='$codigo_doc' AND id_usuario='$id_usuario' ");
                    if (num_rows($rqfi1) == 0) {
                    ?>
                        <!----><a onclick="prnd_items_doctec(<?php echo $rqitm2['id']; ?>);" _ngcontent-pcb-c39="" class="text-danger">Sin Doc. Adjuntos</a>
                    <?php
                    }
                    while ($rqfi2 = fetch($rqfi1)) {
                    ?>
                        <a href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqfi2['nombre']; ?>" target="_blank"><?php echo $rqfi2['descripcion']; ?></a>
                        <br>
                    <?php
                    }
                    ?>
                    <!---->
                </td>
                <!---->
                <!---->
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot _ngcontent-pcb-c39="">
        <!---->
        <!---->
        <tr _ngcontent-pcb-c39="" style="height: 110px;"></tr>
    </tfoot>
</table>