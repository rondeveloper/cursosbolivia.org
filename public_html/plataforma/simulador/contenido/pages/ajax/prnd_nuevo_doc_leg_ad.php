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

<form id="id-FORM_envia_file" action="" method="post" enctype="multipart/form-data">
<div _ngcontent-odc-c42="" class="modal-body">
    <div _ngcontent-odc-c42="" class="form-group">
        <!---->
        <!---->
        <!---->
        <div _ngcontent-odc-c42="" class="form-group"><label _ngcontent-odc-c42="" class="text-bold">Tipo Documento Adjunto:</label><select _ngcontent-odc-c42="" class="form-control ng-pristine ng-valid ng-touched" id="tipodoc" name="tipoDocu" required="">
                <!---->
                <option _ngcontent-odc-c42="" value="552"> Formulario A-1, A-2 u otros</option>
            </select><label _ngcontent-odc-c42="" class="text-bold mt-2">Nombre Documento Adjunto:</label>
            <input _ngcontent-odc-c42="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="35" name="descDocumento" placeholder="Ingrese una descripcion del Documento" type="text"></div>
        <!---->
        <div _ngcontent-odc-c42="" class="form-group"><label _ngcontent-odc-c42="" class="file-upload" for="file2">
        <span id="aux-nom-file-1">- 0.00 MB</span> <button _ngcontent-odc-c42="" class="btn btn-primary  btn-sm ">Adjuntar</button>
        <input _ngcontent-odc-c42="" class="form-control" ng2fileselect="" name="the-file" type="file" onchange="document.getElementById('aux-nom-file-1').innerHTML=value+'- 0.74 MB';document.getElementById('id-prnd_send-form-1').removeAttribute('disabled');">
        </label>
        </div>
    </div>
    <div _ngcontent-odc-c42="" class="card b">
        <div _ngcontent-odc-c42="" class="card-body">
            <h5 _ngcontent-odc-c42=""><i _ngcontent-odc-c42="" class="fa fa-info-circle text-primary"></i> INSTRUCCIONES:</h5>
            <ul _ngcontent-odc-c42="" class="text-muted p-0 list-unstyled">
                <li _ngcontent-odc-c42="">El tamaño de cada documento no debe ser mayor a 30 MB.</li>
                <li _ngcontent-odc-c42=""> Las extensiones permitidas son:&nbsp;
                    <!----><span _ngcontent-odc-c42=""> &nbsp;Archivo Word (.doc) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Word (.docx) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Excel (.xls) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Excel (.xlsx) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo PDF (.pdf) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo JPG (.jpg) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo PNG (.png) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo BMP (.bmp) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Comprimido (.hqx) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Comprimido (.7z) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Comprimido (.zip) </span><span _ngcontent-odc-c42=""> &nbsp;Archivo Comprimido (.rar) </span>
                </li>
            </ul>
        </div>
    </div>
    <div _ngcontent-odc-c42="" class="row">
        <div _ngcontent-odc-c42="" class="col">
            <spinner-http _ngcontent-odc-c42="" _nghost-odc-c13="">
                <!---->
            </spinner-http>
        </div>
    </div>
</div>

<div _ngcontent-odc-c42="" class="modal-footer">
<button onclick="close_modal();" _ngcontent-odc-c42="" class="btn btn-secondary" type="button">Cancelar</button>
<b onclick="prnd_enviar_file_formulario();" id="id-prnd_send-form-1" _ngcontent-odc-c42="" class="btn btn-primary" disabled="">Aceptar</b>
</div>

</form>