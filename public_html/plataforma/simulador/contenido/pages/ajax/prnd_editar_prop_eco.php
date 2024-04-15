<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

?>

<form id="id-FORM_prnd_editar_prop_eco_p2" method="post" action="" onsubmit="return false;">
    <div _ngcontent-pjb-c45="" class="modal-body">
        <!---->
        <!---->
        <datos-items-fragment _ngcontent-pjb-c45="" _nghost-pjb-c39="">
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
                            <!---->
                            <!---->
                            <th _ngcontent-pjb-c39="" class="text-center border-right-color" colspan="6">Definido por la Entidad</th>
                            <th _ngcontent-pjb-c39="" class="text-center" colspan="2">Definido por el Proveedor</th>
                        </tr>
                        <tr _ngcontent-pjb-c39="">
                            <!---->
                            <!---->
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
                            <th _ngcontent-pjb-c39="" class="text-center"> Precio Unitario Ofertado</th>
                            <!---->
                            <th _ngcontent-pjb-c39="" class="text-center"> Precio Total Ofertado</th>
                            <!---->
                            <!---->
                            <!---->
                        </tr>
                    </thead>
                    <tbody _ngcontent-pjb-c39="">
                        <!---->
                        <!---->
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
                            if((int)$rqdir2['precio_ofertado']>0){
                                $pr_ofertado = number_format($rqdir2['precio_ofertado'],2);
                                $tl_ofertado = number_format(($rqdir2['precio_ofertado']*$rqdir2['cantidad']),2);
                            }

                            $input_id = 'prop-eco-'.$rqdir2['id'];
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
                                <td _ngcontent-pjb-c39="" class="text-right"> <?php echo number_format($rqdir2['precio_unitario'], 2); ?></td>
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-right"> <?php echo number_format($rqdir2['precio_total'], 2); ?></td>
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-right">
                                    <!---->
                                    <!----><input onkeyup="prnd_cal_prop_eco(this.value,'<?php echo (int)$rqdir2['cantidad']; ?>','<?php echo $cnt; ?>');" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" value="<?php echo $pr_ofertado; ?>" _ngcontent-pjb-c39="" class="form form-control input-sm ng-untouched ng-pristine ng-valid" type="text">
                                </td>
                                <!---->
                                <td _ngcontent-pjb-c39="" class="text-right"><span _ngcontent-pjb-c39="" id="val-prnd_cal_prop_eco-<?php echo $cnt; ?>"><?php echo $tl_ofertado; ?></span></td>
                                <!---->
                                <!---->
                                <!---->
                            </tr>
                        <?php
                        }
                        ?>

                        <!---->
                        <!---->
                    </tbody>
                    <tfoot _ngcontent-pjb-c39="">
                        <!---->
                        <!---->
                        <tr _ngcontent-pjb-c39="" style="height: 110px;"></tr>
                    </tfoot>
                </table>
            </div>
        </datos-items-fragment>
    </div>
    <input type="hidden" name="ids_items" value="<?php echo $ids_items; ?>">
</form>

<div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cancelar</button>
    <button onclick='validacion__prnd_editar_prop_eco_p2(`<?php echo json_encode($array_validaciones); ?>`);' _ngcontent-pjb-c45="" class="btn btn-primary btn-sm" type="submit">Aceptar</button>
</div>