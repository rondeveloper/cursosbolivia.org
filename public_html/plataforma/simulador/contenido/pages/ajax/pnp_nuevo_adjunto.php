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
            max-width: 500px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<form id="FORM-nuevo-adjunto" onsubmit="return false;" _ngcontent-cyf-c35="" novalidate="" class="ng-pristine ng-valid ng-touched">
<div _ngcontent-cyf-c35="" class="modal-body">
    <div _ngcontent-cyf-c35="" class="row">
        <div _ngcontent-cyf-c35="" class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div _ngcontent-cyf-c35="" class="col-12"><label _ngcontent-cyf-c35="" class="control-obligatorio control-obligatorio">Tipo Enlace:</label></div>
            <div _ngcontent-cyf-c35="" class="col-lg-9 col-md-9 col-sm-9 col-12 form-group margin-bottom-5">
                <select name="tipo_enlace" class="form-control" style="width: 200px;" onchange="pnp_selec_arch_enlace(this.value);">
                    <option value="">Tipo Enlace</option>
                    <option value="Archivo">Archivo</option>
                    <option value="P&aacute;gina Web">P&aacute;gina Web</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Youtube">Youtube</option>
                    <option value="Whatsapp">Whatsapp</option>
                </select>
                <!---->
            </div>
        </div>

        <!---->
    </div>
    <div class="row">
        <div class="col-12">
            
                <!---->
                <!---->
                <div id="id-frm-adjuntos"></div>
                <!---->
                <!---->
            
        </div>
    </div>
</div>
</form>

<div _ngcontent-cyf-c35="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-cyf-c35="" class="btn btn-secondary" type="button">
        <!---->
        <!---->
        <!----> Cancelar
    </button>
    <!----><button onclick="pnp_add_nuevo_adjunto();" _ngcontent-cyf-c35="" class="btn btn-primary" type="button">Aceptar</button>
</div>