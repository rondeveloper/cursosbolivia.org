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
$id_item = get('id_item');
$rqvemp1 = query("SELECT id FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
if (num_rows($rqvemp1) > 0) {
    $checked = ' checked="checked" ';
}
?>

<form id="FORM-prnd_itemmargpref_addnew" action="" method="post" onsubmit="return false;">
    <div _ngcontent-grk-c39="" class="modal-body">
        <div _ngcontent-grk-c39="" class="row">
            <div _ngcontent-grk-c39="" class="col-lg-12 col-md-12">
                <div _ngcontent-grk-c39="" class="table-responsive">
                    <table _ngcontent-grk-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                        <thead _ngcontent-grk-c39="">
                            <tr _ngcontent-grk-c39="">
                                <th _ngcontent-grk-c39="" class="text-center">Selección</th>
                                <th _ngcontent-grk-c39="" class="text-center">Márgenes de Preferencia</th>
                                <th _ngcontent-grk-c39="" class="text-center">Porcentaje</th>
                                <th _ngcontent-grk-c39="" class="text-center">Observación</th>
                            </tr>
                        </thead>
                        <tbody _ngcontent-grk-c39="">
                            <?php
                            $rqdit1 = query("SELECT * FROM simulador_marg_pref WHERE cuce='99' ");
                            while($rqdit2 = fetch($rqdit1)){
                            ?>
                            <!---->
                            <tr _ngcontent-grk-c39="">
                                <td _ngcontent-grk-c39="" class="text-center"><span _ngcontent-grk-c39="" class="checkbox c-checkbox" style="display:inline"><label _ngcontent-grk-c39=""><input _ngcontent-grk-c39="" type="radio" name="<?php echo $rqdit2['grupo']; ?>" value="<?php echo $rqdit2['id']; ?>"><span _ngcontent-grk-c39="" class="fa fa-check"></span></label></span></td>
                                <td _ngcontent-grk-c39=""><?php echo $rqdit2['margen_pref']; ?></td>
                                <td _ngcontent-grk-c39=""><?php echo $rqdit2['porcentaje']; ?> %</td>
                                <td _ngcontent-grk-c39=""><?php echo $rqdit2['observacion']; ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div _ngcontent-eak-c44="" class="modal-footer">
            <button onclick="close_modal();" _ngcontent-eak-c44="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
            <button onclick="prnd_itemmargpref_addnew();" _ngcontent-eak-c44="" class="btn btn-primary btn-sm" type="submit">Aceptar</button>
        </div>
    </div>
    <input type="hidden" name="id_item" value="<?php echo $id_item; ?>">
</form>