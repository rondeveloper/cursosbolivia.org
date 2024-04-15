<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$descDocumento = post('descDocumento');
$id_usuario = usuario('id_sim');
$tag_image = 'the-file';
$codigo_doc = 'doc_leg_ad';
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

if(isset_get('id_arch_delete')){
    $id_arch_delete = get('id_arch_delete');
    $rqdarch1 = query("SELECT * FROM simulador_files WHERE id='$id_arch_delete' LIMIT 1 ");
    $rqdarch2 = fetch($rqdarch1);
    unlink($carpeta_destino.$rqdarch2['nombre']);
    query("DELETE FROM simulador_files WHERE id='$id_arch_delete' LIMIT 1 ");
    echo '<br><div class="alert alert-success">
    Archivo eliminado
  </div>';
}


?>
<table _ngcontent-rov-c36="" class="table table-bordered table-sm table-striped">
    <thead _ngcontent-rov-c36="">
        <tr _ngcontent-rov-c36="">
            <th _ngcontent-rov-c36="" class="text-center w-cog" style="width: 80px;">Opciones</th>
            <th _ngcontent-rov-c36="" class="text-center">Documentos Adjuntos</th>
        </tr>
    </thead>
    <tbody _ngcontent-rov-c36="">
        <!---->
        <?php
        $rqdas1 = query("SELECT * FROM simulador_files WHERE cuce='$cuce' AND id_usuario='$id_usuario' AND codigo='$codigo_doc' ");
        while($rqdas2 = fetch($rqdas1)){
            ?>
            <tr _ngcontent-rov-c36="">
                <td _ngcontent-rov-c36="" class="text-center">
                    <div onclick="pmd_dropdown_1(<?php echo $rqdas2['id']; ?>);" _ngcontent-rov-c36="" class="btn-group" dropdown="">
                        <button _ngcontent-rov-c36="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-10">
                            <span _ngcontent-rov-c36="" class="fa fa-cog text-primary"></span>
                        </button>
                        <!---->
                        <!---->
                        <ul id="id-sw_pmd_dropdown_1-<?php echo $rqdas2['id']; ?>" style="display: none;" _ngcontent-crt-c37="" class="dropdown-menu ng-star-inserted show" role="menu" style="inset: 100% auto auto 0px; transform: translateY(0px);">
                            <!---->
                            <a onclick="prnd_eliminar_archivo(<?php echo $rqdas2['id']; ?>);" _ngcontent-crt-c37="" class="dropdown-item text-dark ng-star-inserted"><span _ngcontent-crt-c37="" class="fa fa-trash"></span> Eliminar archivo </a>
                        </ul>
                    </div>
                </td>
                <td _ngcontent-rov-c36=""><a _ngcontent-rov-c36="" href="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqdas2['nombre']; ?>" target="_blank">Formulario A-1, A-2 u otros(<?php echo $rqdas2['descripcion']; ?>)</a></td>
            </tr>
            <?php
        }
        ?>

    </tbody>
</table>