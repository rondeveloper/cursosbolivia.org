<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$atributo = '';
$aux_disabled = ' disabled="" ';
if(isset_get('atributo')){
    $atributo = get('atributo');
    $aux_disabled = '';
}

?>
<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 370px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<form id="FORM-nuevo-atributo" method="post" action="" onsubmit="return false;">
<div _ngcontent-yfk-c63="" class="modal-body">
    <div _ngcontent-yfk-c63="" class="row">
        <div _ngcontent-yfk-c63="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-yfk-c63="" class="mt">Atributo :</label>
            <div _ngcontent-yfk-c63="" class="row">
                <div _ngcontent-yfk-c63="" class="col-lg-12">
                    <div _ngcontent-yfk-c63="" class="form-group">
                        <div _ngcontent-yfk-c63="" class="input-group"><input name="" _ngcontent-yfk-c63="" value="<?php echo $atributo; ?>" class="form-control ng-untouched ng-pristine" disabled="" ng-reflect-is-disabled="true" type="text">
                            <!----><span _ngcontent-yfk-c63="" class="input-group-btn intput-sm ng-star-inserted">
                                <button onclick="pnp_selec_atributo();" _ngcontent-yfk-c63="" class="btn btn-primary btn-sm" type="button"><span _ngcontent-yfk-c63="" class="fa fa-search"></span></button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div _ngcontent-yfk-c63="" class="col-12 col-sm-12 col-md-12  col-lg-12"><label _ngcontent-yfk-c63="" class="mt">Descripción o valor :</label></div>
        <div _ngcontent-yfk-c63="" class="col-12 col-sm-12 col-md-12  col-lg-12"><input name="valor" _ngcontent-yfk-c63="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="70" placeholder="" type="text"></div>
    </div>
</div>
<input name="atributo" value="<?php echo $atributo; ?>" type="hidden">
</form>

<div _ngcontent-yfk-c63="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c63="" class="btn btn-secondary" type="button">
        <!---->
        <!---->
        <!----> Cancelar
    </button>
    <!----><button onclick="pnp_add_nuevo_atributo();" _ngcontent-yfk-c63="" class="btn btn-primary ng-star-inserted" type="button" <?php echo $aux_disabled; ?>>Aceptar</button>
</div>