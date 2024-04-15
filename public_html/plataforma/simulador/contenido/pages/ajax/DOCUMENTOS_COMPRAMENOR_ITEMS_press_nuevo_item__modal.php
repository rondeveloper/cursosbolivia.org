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
<div _ngcontent-pws-c42="" class="modal-body">
    <datos-items-fragment _ngcontent-pws-c42="" _nghost-pws-c41="">
        <div _ngcontent-pws-c41="" class="row">
            <div _ngcontent-pws-c41="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-pws-c41="" class="input-group input-group-sm"><input _ngcontent-pws-c41="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-pws-c41="" class="input-group-btn"><button _ngcontent-pws-c41="" class="btn btn-primary" type="button"><span _ngcontent-pws-c41="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-pws-c41=""><br _ngcontent-pws-c41="">
        </div>
        <div _ngcontent-pws-c41="" class="table-responsive">
            <form id="FORM-modal" action="" method="post">
                <table _ngcontent-pws-c41="" class="table table-bordered table-sm table-hover table-striped">
                    <thead _ngcontent-pws-c41="">
                        <!---->
                        <tr _ngcontent-pws-c41="">
                            <!---->
                            <!---->
                            <th _ngcontent-pws-c41="" class="text-center"></th>
                            <th _ngcontent-pws-c41="" class="text-center">#</th>
                            <th _ngcontent-pws-c41="" class="text-center">Descripción del Bien o Servicio</th>
                            <th _ngcontent-pws-c41="" class="text-center">Unidad de Medida</th>
                            <th _ngcontent-pws-c41="" class="text-center">Cantidad</th>
                            <!---->
                            <th _ngcontent-pws-c41="" class="text-center">
                                <!---->
                                <!----> Precio Unitario del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <!---->
                            <th _ngcontent-pws-c41="" class="text-center">
                                <!---->
                                <!----> Precio Total del Proveedor Preseleccionado
                                <!---->
                            </th>
                            <!---->
                            <!---->
                            <!---->
                            <!---->
                            <!---->
                        </tr>
                    </thead>
                    <tbody _ngcontent-pws-c41="">
                        <!---->
                        <?php
                        $rqit1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='0' ");
                        $cnt = 1;
                        while ($rqit2 = fetch($rqit1)) {
                            $rqverif1 = query("SELECT id FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' AND descripcion LIKE '".$rqit2['descripcion']."' AND cantidad='".$rqit2['cantidad']."' ORDER BY id DESC limit 1 ");
                            if(num_rows($rqverif1)==0){
                                ?>
                                    <!---->
                                    <!---->
                                    <tr _ngcontent-pws-c41="">
                                        <!---->
                                        <!---->
                                        <td _ngcontent-pws-c41="">
                                            <input name="item-<?php echo $rqit2['id']; ?>" value="1" type="checkbox" style="width: 20px;height: 20px;" onclick="document.getElementById('boton-modal-aceptar').removeAttribute('disabled')" />
                                        </td>
                                        <td _ngcontent-pws-c41="" class="text-center"><?php echo $cnt++; ?></td>
                                        <td _ngcontent-pws-c41="">
                                            <?php echo $rqit2['descripcion']; ?>
                                        </td>
                                        <td _ngcontent-pws-c41=""><?php echo $rqit2['unidad_medida']; ?></td>
                                        <td _ngcontent-pws-c41="" class="text-right"><?php echo $rqit2['cantidad']; ?></td>
                                        <!---->
                                        <td _ngcontent-pws-c41="" class="text-right"><?php echo $rqit2['precio_unitario']; ?></td>
                                        <!---->
                                        <td _ngcontent-pws-c41="" class="text-right"><?php echo $rqit2['precio_total']; ?></td>
                                        <!---->
                                        <!---->
                                        <!---->
                                        <!---->
                                        <!---->
                                    </tr>
                                    <!---->
                                    <!---->
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot _ngcontent-pws-c41="">
                        <!---->
                        <!---->
                    </tfoot>
                </table>
            </form>
        </div>
    </datos-items-fragment>
</div>
<div _ngcontent-pws-c42="" class="modal-footer">
    <button onclick="close_modal()" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    <button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_agregado_de_items__display()" id="boton-modal-aceptar" _ngcontent-pws-c42="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button>
</div>