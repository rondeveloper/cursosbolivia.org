<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$busc = get('busc');

?>

<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px !important;
            margin: 1.75rem auto;
        }
    }
</style>

<div class="modal-body">
    <form id="FORM-prod-img" method="post" action="" onsubmit="return false;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="col-lg-4 col-sm-3 col-md-2 col-12"><label class="control-obligatorio control-obligatorio">Nombre de Imagen:</label></div>
            <div class="col-lg-8 col-md-9 col-sm-9 col-12 form-group margin-bottom-5">
                <form novalidate="" class="ng-untouched ng-pristine ng-valid">
                    <!----><input name="descripcion_imagen" onkeyup="document.getElementById('aux-btn-aceptar').removeAttribute('disabled');" class="form-control input-sm ng-untouched ng-pristine ng-valid ng-star-inserted" formcontrolname="maxlen" maxlength="50" type="text">
                </form>
                <!---->
            </div>
        </div>
        <!---->
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ng-star-inserted">
            <div class="col-lg-4 col-sm-3 col-md-2 col-12"></div>
            <div class="col-lg-8 col-md-9 col-sm-9 col-12 form-group margin-bottom-5">
                <span id="aux-nom-file-1"></span>
                <!----><label class="file-upload" for="image-input">
                    <button class="btn btn-primary  btn-sm ">Cargar Imagen</button>
                    <input onchange="readURL(this);document.getElementById('aux-nom-file-1').innerHTML=this.value;" name="imagen" accept="image/jpg,image/jpeg,image/png,image/bmp,image/tiff" ng2fileselect="" type="file" >
                </label>
            </div>
        </div>
        <!---->
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="col-lg-4 col-sm-3 col-md-2 col-12"></div>
            <div class="col-lg-8 col-md-9 col-sm-9 col-12 form-group margin-bottom-5">
                <!----><img id="img-to-show" alt="No Imagen" class="img-fluid img-miniatura ng-star-inserted" src="">
                <!---->
            </div>
        </div>
    </div>
    </form>
</div>

<div class="card b">
    <div class="card-body">
        <h5><i class="fa fa-info-circle text-primary"></i> INSTRUCCIONES:</h5>
        <ul class="text-muted p-0 list-unstyled">
            <li> Las extensiones permitidas son:&nbsp; jpeg, png, bmp, tiff </li>
        </ul>
    </div>
</div>

<div class="modal-footer"><button onclick="close_modal();" class="btn btn-secondary" type="button">
        <!---->
        <!---->
        <!----> Cancelar
    </button>
    <!----><button onclick="pnp_add_nueva_imagen();" id="aux-btn-aceptar" class="btn btn-primary ng-star-inserted" type="button" disabled="">Aceptar</button>
</div>