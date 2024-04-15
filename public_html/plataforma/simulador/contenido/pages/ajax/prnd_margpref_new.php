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
$checked = '';
$rqvemp1 = query("SELECT id FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
if (num_rows($rqvemp1) > 0) {
    $checked = ' checked="checked" ';
}
?>

<form id="FORM-prnd_margpref_addnew" action="" method="post" onsubmit="return false;">
    <div _ngcontent-eak-c44="" class="modal-body">
        <div _ngcontent-eak-c44="" class="row">
            <div _ngcontent-eak-c44="" class="col-lg-12 col-md-12">
                <div _ngcontent-eak-c44="" class="table-responsive">
                    <table _ngcontent-eak-c44="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                        <thead _ngcontent-eak-c44="">
                            <tr _ngcontent-eak-c44="">
                                <th _ngcontent-eak-c44="" class="text-center">Selección</th>
                                <th _ngcontent-eak-c44="" class="text-center">Márgenes de Preferencia</th>
                                <th _ngcontent-eak-c44="" class="text-center">Porcentaje</th>
                                <th _ngcontent-eak-c44="" class="text-center">Observación</th>
                            </tr>
                        </thead>
                        <tbody _ngcontent-eak-c44="">
                            <!---->
                            <tr _ngcontent-eak-c44="">
                                <td _ngcontent-eak-c44="" class="text-center"><span _ngcontent-eak-c44="" class="checkbox c-checkbox" style="display:inline"><label _ngcontent-eak-c44=""><input name="checkbox-margpref" _ngcontent-eak-c44="" type="checkbox" <?php echo $checked; ?>><span _ngcontent-eak-c44="" class="fa fa-check"></span></label></span></td>
                                <td _ngcontent-eak-c44="">Tipo de Proponente (MyPE, OECA, APP)</td>
                                <td _ngcontent-eak-c44="">
                                    <!---->
                                    <!----> 20 %
                                    <!---->
                                </td>
                                <td _ngcontent-eak-c44="">Seleccionar si cuenta con la certificación</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div _ngcontent-eak-c44="" class="modal-footer">
            <button onclick="close_modal();" _ngcontent-eak-c44="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
            <button onclick="prnd_margpref_addnew();" _ngcontent-eak-c44="" class="btn btn-primary btn-sm" type="submit">Aceptar</button>
        </div>
    </div>
</form>