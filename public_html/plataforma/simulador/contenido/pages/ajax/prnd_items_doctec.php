<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_item = get('id_item');
$id_usuario = usuario('id_sim');

if (isset_post('agregar-doc')) {
    $id_item = post('id_item');
    $descDocumento = post('descDocumento');
    $tag_image = 'the-file';
    $codigo_doc = 'item_' . $id_item;
    $cuce = '21-0513-00-1114217-1-1';
    $carpeta_destino = $___path_raiz . 'contenido/imagenes/doc-usuarios/';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
            $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("INSERT INTO simulador_files (id_usuario,descripcion,cuce,codigo,nombre) VALUES ('$id_usuario','$descDocumento','$cuce','$codigo_doc','$nombre_imagen') ");
        }
    }
}

if (isset_get('addoc')) {
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

        <div _ngcontent-ptu-c42="" class="modal-body">
            <div _ngcontent-ptu-c42="" class="form-group">
                <!---->
                <div _ngcontent-ptu-c42="" class="form-group"><label _ngcontent-ptu-c42="" class="text-bold">Tipo Documento Adjunto:</label><select _ngcontent-ptu-c42="" class="form-control ng-untouched ng-pristine ng-valid" id="tipodoc" name="tipoDocu" required="">
                        <!---->
                        <option _ngcontent-ptu-c42="" value="550"> Propuesta Técnica</option>
                    </select>
                    <label _ngcontent-ptu-c42="">Nombre Documento Adjunto:</label>
                    <input _ngcontent-ptu-c42="" class="form-control ng-untouched ng-pristine ng-valid" maxlength="35" name="descDocumento" placeholder="Ingrese una descripcion del Documento" type="text">
                </div>
                <!---->
                <!---->
                <!---->
                <div _ngcontent-ptu-c42="" class="form-group"><label _ngcontent-ptu-c42="" class="file-upload" for="file2"><span id="aux-nom-file-1">- 0.00 MB</span> <button _ngcontent-ptu-c42="" class="btn btn-primary  btn-sm ">Adjuntar</button><input _ngcontent-ptu-c42="" class="form-control" ng2fileselect="" name="the-file" type="file" onchange="document.getElementById('aux-nom-file-1').innerHTML=value+'- 0.74 MB';document.getElementById('id-prnd_send-form-1').removeAttribute('disabled');"></label></div>
            </div>
            <div _ngcontent-ptu-c42="" class="card b">
                <div _ngcontent-ptu-c42="" class="card-body">
                    <h5 _ngcontent-ptu-c42=""><i _ngcontent-ptu-c42="" class="fa fa-info-circle text-primary"></i> INSTRUCCIONES:</h5>
                    <ul _ngcontent-ptu-c42="" class="text-muted p-0 list-unstyled">
                        <li _ngcontent-ptu-c42="">El tamaño de cada documento no debe ser mayor a 30 MB.</li>
                        <li _ngcontent-ptu-c42=""> Las extensiones permitidas son:&nbsp;
                            <!----><span _ngcontent-ptu-c42=""> &nbsp;Archivo Word (.doc) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Word (.docx) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Excel (.xls) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Excel (.xlsx) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo PDF (.pdf) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo JPG (.jpg) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo PNG (.png) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo BMP (.bmp) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Comprimido (.hqx) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Comprimido (.7z) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Comprimido (.zip) </span><span _ngcontent-ptu-c42=""> &nbsp;Archivo Comprimido (.rar) </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div _ngcontent-ptu-c42="" class="row">
                <div _ngcontent-ptu-c42="" class="col">
                    <spinner-http _ngcontent-ptu-c42="" _nghost-ptu-c13="">
                        <!---->
                    </spinner-http>
                </div>
            </div>
        </div>

        <input name="id_item" type="hidden" value="<?php echo $id_item; ?>">
        <input name="agregar-doc" type="hidden" value="1">
    </form>

    <div _ngcontent-ptu-c42="" class="modal-footer">
        <button onclick="close_modal();" _ngcontent-ptu-c42="" class="btn btn-secondary" type="button">Cancelar</button>
        <button onclick="prnd_items_doctec_addoc_send_file();" id="id-prnd_send-form-1" _ngcontent-ptu-c42="" class="btn btn-primary" type="submit" disabled="">Aceptar</button>
    </div>

<?php
} else {
?>

    <div _ngcontent-lsr-c41="" class="modal-body">
        <div _ngcontent-lsr-c41="" class="row">
            <!---->
            <div _ngcontent-lsr-c41="" class="col-lg-12 col-md-12">
                <div _ngcontent-lsr-c41="" class="btn-group opcionesMenuPagina">
                    <button onclick="prnd_items_doctec_addoc(<?php echo $id_item; ?>);" _ngcontent-lsr-c41="" class="btn btn-primary btn-sm"><i _ngcontent-lsr-c41="" class="fa fa-plus-circle"></i>&nbsp;Adjuntar Documento </button>
                </div>
            </div>
        </div>
        <div _ngcontent-lsr-c41="" class="row">
            <div _ngcontent-lsr-c41="" class="col-lg-12 col-md-12">
                <!---->
                <div _ngcontent-lsr-c41="" class="table-responsive">
                    <table _ngcontent-lsr-c41="" class="table table-bordered table-sm table-hover table-striped table-responsive">
                        <thead _ngcontent-lsr-c41="">
                            <tr _ngcontent-lsr-c41="">
                                <th _ngcontent-lsr-c41="" class="text-center w-cog">Opciones</th>
                                <th _ngcontent-lsr-c41="" class="text-center">Tipo Documento</th>
                                <th _ngcontent-lsr-c41="" class="text-center">Nombre Documento</th>
                                <th _ngcontent-lsr-c41="" class="text-center">Tamaño</th>
                                <th _ngcontent-lsr-c41="" class="text-center">Fecha y hora de Carga</th>
                            </tr>
                        </thead>
                        <tbody _ngcontent-lsr-c41="">
                            <!---->
                            <!---->
                            <?php
                            $codigo_doc = 'item_' . $id_item;
                            $rqfi1 = query("SELECT * FROM simulador_files WHERE codigo='$codigo_doc' AND id_usuario='$id_usuario' ");
                            if (num_rows($rqfi1) == 0) {
                            ?>
                                <tr _ngcontent-lsr-c41="">
                                    <td _ngcontent-lsr-c41="" colspan="5">No hay registro de documentos adjuntos</td>
                                </tr>
                            <?php
                            }
                            while ($rqfi2 = fetch($rqfi1)) {
                            ?>
                                <tr _ngcontent-ptu-c41="">
                                    <td _ngcontent-ptu-c41="" class="text-center">
                                        <div _ngcontent-ptu-c41="" class="btn-group" dropdown=""><button _ngcontent-ptu-c41="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-14"><span _ngcontent-ptu-c41="" class="fa fa-cog text-primary"></span></button>
                                            <!---->
                                        </div>
                                    </td>
                                    <td _ngcontent-ptu-c41="">Propuesta Técnica</td>
                                    <td _ngcontent-ptu-c41=""><a href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqfi2['nombre']; ?>" target="_blank"><?php echo $rqfi2['descripcion']; ?></a></td>
                                    <td _ngcontent-ptu-c41="" class="text-right">1.47 MB</td>
                                    <td _ngcontent-ptu-c41=""><?php echo $rqfi2['fecha_upload']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr _ngcontent-lsr-c41="" style="height: 90px;"></tr>
                        </tbody>
                    </table>
                </div>
                <!---->
                <div _ngcontent-lsr-c41="" class="row">
                    <div _ngcontent-lsr-c41="" class="col">
                        <spinner-http _ngcontent-lsr-c41="" _nghost-lsr-c13="">
                            <!---->
                        </spinner-http>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div _ngcontent-lsr-c41="" class="modal-footer">
        <button onclick="close_modal();" _ngcontent-lsr-c41="" class="btn btn-secondary btn-sm" type="submit">Cerrar</button>
    </div>

<?php
}
?>