<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$cuce = '21-0513-00-1151485-1-1';

?>
<style>
    @media (min-width: 1070px) {
        .modal-dialog {
            max-width: 1100px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<div _ngcontent-mbu-c41="" class="modal-body">
    <!---->
    <!---->
    <datos-items-fragment _ngcontent-mbu-c41="" _nghost-mbu-c39="">
        <div _ngcontent-mbu-c39="" class="row">
            <div _ngcontent-mbu-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-mbu-c39="" class="input-group input-group-sm"><input _ngcontent-mbu-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-mbu-c39="" class="input-group-btn"><button _ngcontent-mbu-c39="" class="btn btn-primary" type="button"><span _ngcontent-mbu-c39="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-mbu-c39=""><br _ngcontent-mbu-c39="">
        </div>
        <div _ngcontent-mbu-c39="" class="table-responsive">
            <form id="FORM-modal" action="" method="post">
                <table _ngcontent-mbu-c39="" class="table table-bordered table-sm table-hover table-striped">
                    <thead _ngcontent-mbu-c39="">
                        <!---->
                        <tr _ngcontent-mbu-c39="">
                            <!---->
                            <!---->
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center border-right-color" colspan="6">Definido por la Entidad</th>
                            <th _ngcontent-mbu-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                        </tr>
                        <tr _ngcontent-mbu-c39="">
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">#</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Descripción del Bien o Servicio</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Unidad de Medida</th>
                            <th _ngcontent-mbu-c39="" class="text-center">Cantidad</th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">
                                <!---->
                                <!----> Precio Unitario del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center">
                                <!---->
                                <!----> Precio Total del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center"> Precio Unitario Ofertado</th>
                            <!---->
                            <th _ngcontent-mbu-c39="" class="text-center"> Precio Total Ofertado</th>
                            <!---->
                            <!---->
                            <!---->
                        </tr>
                    </thead>
                    <tbody _ngcontent-mbu-c39="">
                        <!---->
                        <?php
                        $rqit1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                        $cnt = 1;
                        while ($rqit2 = fetch($rqit1)) {
                            ?>
                            <!---->
                            <!---->
                            <tr _ngcontent-mbu-c39="">
                                <!---->
                                <!---->
                                <td _ngcontent-mbu-c39="" class="text-center"><?php echo $cnt++; ?></td>
                                <td _ngcontent-mbu-c39="">
                                    <?php echo $rqit2['descripcion']; ?>
                                </td>
                                <td _ngcontent-mbu-c39=""><?php echo $rqit2['unidad_medida']; ?></td>
                                <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['cantidad']; ?></td>
                                <!---->
                                <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['precio_unitario']; ?></td>
                                <!---->
                                <td _ngcontent-mbu-c39="" class="text-right"><?php echo $rqit2['precio_total']; ?></td>
                                <!---->
                                <td _ngcontent-mbu-c39="" class="text-right">
                                    <!---->
                                    <!----><input name="item-<?php echo $rqit2['id']; ?>" onkeyup="prnd_cal_prop_eco(this.value, <?php echo $rqit2['cantidad']; ?>, <?php echo $rqit2['id']; ?>);document.getElementById('boton-modal-aceptar').removeAttribute('disabled');" _ngcontent-mbu-c39="" class="form form-control input-sm ng-untouched ng-pristine ng-valid" type="text" value="<?php echo $rqit2['precio_ofertado']; ?>">
                                </td>
                                <!---->
                                <td _ngcontent-mbu-c39="" class="text-right"><span id="val-prnd_cal_prop_eco-<?php echo $rqit2['id']; ?>" _ngcontent-mbu-c39=""><?php echo $rqit2['precio_ofertado']*$rqit2['cantidad']; ?></span></td>
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                            <!---->
                            <!---->
                            <?php
                        }
                        ?>
                        <!---->
                    </tbody>
                    <tfoot _ngcontent-mbu-c39="">
                        <!---->
                        <!---->
                        <tr _ngcontent-mbu-c39="" style="height: 50px;"></tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </datos-items-fragment>
</div>
<div _ngcontent-pws-c42="" class="modal-footer">
    <button onclick="close_modal()" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    <button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_guardado_de_precios__nocontent()" id="boton-modal-aceptar" _ngcontent-pws-c42="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button>
</div>