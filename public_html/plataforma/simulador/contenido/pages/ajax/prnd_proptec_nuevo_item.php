<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$id_usuario = usuario('id_sim');
$cuce = '21-0513-00-1114217-1-1';

?>

<form id="FORM-prnd_selec_items" method="post" action="">

<div _ngcontent-pjb-c40="" class="modal-body">
    <datos-items-fragment _ngcontent-pjb-c40="" _nghost-pjb-c39="">
        <div _ngcontent-pjb-c39="" class="row">
            <div _ngcontent-pjb-c39="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-pjb-c39="" class="input-group input-group-sm"><input _ngcontent-pjb-c39="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-pjb-c39="" class="input-group-btn"><button _ngcontent-pjb-c39="" class="btn btn-primary" type="button"><span _ngcontent-pjb-c39="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-pjb-c39=""><br _ngcontent-pjb-c39="">
        </div>
        <div _ngcontent-pjb-c39="" class="table-responsive">
            <table _ngcontent-pjb-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                <thead _ngcontent-pjb-c39="">
                    <!---->
                    <tr _ngcontent-pjb-c39="">
                        <!---->
                        <!---->
                        <th _ngcontent-pjb-c39="" class="text-center"></th>
                        <th _ngcontent-pjb-c39="" class="text-center">#</th>
                        <th _ngcontent-pjb-c39="" class="text-center">Descripción del Bien o Servicio</th>
                        <th _ngcontent-pjb-c39="" class="text-center">Unidad de Medida</th>
                        <th _ngcontent-pjb-c39="" class="text-center">Cantidad</th>
                        <!---->
                        <th _ngcontent-pjb-c39="" class="text-center">
                            <!----> Precio Referencial Unitario
                            <!---->
                        </th>
                        <!---->
                        <!---->
                        <th _ngcontent-pjb-c39="" class="text-center">
                            <!----> Precio Referencial Total
                            <!---->
                        </th>
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                </thead>
                <tbody _ngcontent-pjb-c39="">
                <?php
                $rqdi1 = query("SELECT * FROM simulador_items WHERE cuce='11' ");
                $cnt = 1;
                while($rqdi2 = fetch($rqdi1)){
                    $qrvex1 = query("SELECT id FROM simulador_items WHERE id_usuario='$id_usuario' AND cuce='$cuce' AND descripcion LIKE '".$rqdi2['descripcion']."' ");
                    if(num_rows($qrvex1)==0){
                ?>
                <tr _ngcontent-pjb-c39="">
                        <!---->
                        <!---->
                        <td _ngcontent-pjb-c39=""><span onclick="document.getElementById('id-prnd_btn-ac-1').removeAttribute('disabled');" _ngcontent-pjb-c39="" class="checkbox c-checkbox" style="display:inline"><label _ngcontent-pjb-c39=""><input name="item-<?php echo $rqdi2['id']; ?>" value="1" _ngcontent-pjb-c39="" type="checkbox"><span _ngcontent-pjb-c39="" class="fa fa-check"></span></label></span></td>
                        <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                        <td _ngcontent-pjb-c39=""><?php echo $rqdi2['descripcion']; ?></td>
                        <td _ngcontent-pjb-c39=""><?php echo $rqdi2['unidad_medida']; ?></td>
                        <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdi2['cantidad']; ?></td>
                        <!---->
                        <td _ngcontent-pjb-c39="" class="text-right"> <?php echo round($rqdi2['precio_unitario'],2); ?></td>
                        <!---->
                        <td _ngcontent-pjb-c39="" class="text-right"> <?php echo round($rqdi2['precio_total'],2); ?></td>
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                    </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
                <tfoot _ngcontent-pjb-c39="">
                    <!---->
                    <!---->
                </tfoot>
            </table>
        </div>
    </datos-items-fragment>
</div>

</form>

<div _ngcontent-pjb-c40="" class="modal-footer">
    <button onclick="close_modal()" _ngcontent-pjb-c40="" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    <button onclick="prnd_proctec_add_nuevo_item();" id="id-prnd_btn-ac-1" _ngcontent-pjb-c40="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button>
</div>
