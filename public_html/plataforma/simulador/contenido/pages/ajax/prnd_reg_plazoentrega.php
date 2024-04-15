<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>
<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 1100px !important;
            margin: 1.75rem auto;
        }
    }
</style>
<form id="id-prnd_reg_plazoentrega_p2" method="post" action="" onsubmit="return false;">
<div _ngcontent-mks-c33="" class="modal-body">
    <!---->
    <datos-items-fragment _ngcontent-mks-c33="" _nghost-mks-c25="">
        <div _ngcontent-mks-c25="" class="row">
            <div _ngcontent-mks-c25="" class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                <div _ngcontent-mks-c25="" class="input-group input-group-sm"><input _ngcontent-mks-c25="" class="form-control" name="inpBusqueda" placeholder="Buscar" type="text"><span _ngcontent-mks-c25="" class="input-group-btn"><button _ngcontent-mks-c25="" class="btn btn-primary" type="button"><span _ngcontent-mks-c25="" class="fa fa-search"></span></button></span></div>
            </div><br _ngcontent-mks-c25=""><br _ngcontent-mks-c25="">
        </div>
        <div _ngcontent-mks-c25="" class="table-responsive">
            <table _ngcontent-mks-c25="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                <thead _ngcontent-mks-c25="">
                    <!---->
                    <tr _ngcontent-mks-c25="">
                        <!---->
                        <!---->
                        <th _ngcontent-mks-c25="" class="text-center border-right-color" colspan="5">Definido por la Entidad</th>
                        <!---->
                        <!---->
                        <th _ngcontent-mks-c25="" class="text-center" colspan="2">Definido por el Proveedor</th>
                    </tr>
                    <tr _ngcontent-mks-c25="">
                        <!---->
                        <!---->
                        <th _ngcontent-mks-c25="" class="text-center">#</th>
                        <th _ngcontent-mks-c25="" class="text-center">Descripción del Bien o Servicio</th>
                        <th _ngcontent-mks-c25="" class="text-center">Unidad de Medida</th>
                        <th _ngcontent-mks-c25="" class="text-center">Cantidad</th>
                        <!---->
                        <!---->
                        <th _ngcontent-mks-c25="" class="text-center"> Plazo de Entrega Referencial </th>
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <th _ngcontent-mks-c25="" class="text-center"> Plazo de Entrega Propuesto (Dias Calendario) </th>
                    </tr>
                </thead>
                <tbody _ngcontent-mks-c25="">
                    <!---->
                    <!--
                    <tr _ngcontent-mks-c25="">
                        <td _ngcontent-mks-c25="" colspan="20">No hay registro de Ítems</td>
                    </tr>
                    -->
                    <?php
                        $cuce = '21-0513-00-1114217-1-1';
                        $id_usuario = usuario('id_sim');
                        $rqdir1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
                        $cnt = 1;
                        $ids_items = '0';
                        $array_validaciones = array();
                        while ($rqdir2 = fetch($rqdir1)) {
                            $cnt++;
                            $ids_items .= ','.$rqdir2['id'];

                            $pr_ofertado = '';
                            $tl_ofertado = '';
                            if((int)$rqdir2['precio_ofertado']>0 && false){
                                $pr_ofertado = number_format($rqdir2['precio_ofertado'],2);
                                $tl_ofertado = number_format(($rqdir2['precio_ofertado']*$rqdir2['cantidad']),2);
                            }

                            $input_id = 'plazo-'.$rqdir2['id'];
                            $array_validacion = array('input_id'=>$input_id,'precio_referencial'=>$rqdir2['precio_unitario'],'desc_item'=>$rqdir2['descripcion']);
                            array_push($array_validaciones,$array_validacion);
                        ?>
                            <tr _ngcontent-pjb-c39="">
                                <!---->
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt; ?></td>
                                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['descripcion']; ?></td>
                                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['unidad_medida']; ?></td>
                                <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdir2['cantidad']; ?></td>
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-right"></td>
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-right">
                                    <!---->
                                    <!----><input  name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" value="<?php echo $pr_ofertado; ?>" _ngcontent-pjb-c39="" class="form form-control input-sm ng-untouched ng-pristine ng-valid" type="text">
                                </td>
                                <!---->
                                <!---->
                            </tr>
                        <?php
                        }
                        ?>
                </tbody>
                <tfoot _ngcontent-mks-c25="">
                    <!---->
                    <!---->
                </tfoot>
            </table>
        </div>
    </datos-items-fragment>
    <!---->
</div>
<input type="hidden" name="ids_items" value="<?php echo $ids_items; ?>"/>
</form>

<div _ngcontent-mks-c33="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-mks-c33="" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    <button onclick="prnd_reg_plazoentrega_p2();" _ngcontent-mks-c33="" class="btn btn-primary btn-sm" type="submit">Aceptar</button>
</div>