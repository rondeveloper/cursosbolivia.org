<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$id_prod = $_SESSION['id_prod__CURRENTADD'];
$descDocumento = post('descripcion_imagen');
$id_usuario = usuario('id_sim');
$tag_image = 'imagen';
$codigo_doc = 'imgprod' . $id_prod;
$cuce = 'prod-' . $id_prod;
$carpeta_destino = $___path_raiz . 'contenido/imagenes/doc-usuarios/';
if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
    $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
    if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg'))) {
        $nombre_imagen = rand(999, 99999) . str_replace(array('[', ']', ' '), '-', $_FILES[$tag_image]['name']);
        move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
        query("INSERT INTO simulador_files 
        (id_usuario,descripcion,cuce,codigo,nombre) 
        VALUES 
        ('$id_usuario','$descDocumento','$cuce','$codigo_doc','$nombre_imagen') ");
    }
}
?>

<table _ngcontent-nhg-c18="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-nhg-c18="">
        <tr _ngcontent-nhg-c18="">
            <th _ngcontent-nhg-c18="" class="w-cog" style="width: 90px;">Opciones</th>
            <th _ngcontent-nhg-c18="" class="text-center">Imagen</th>
            <th _ngcontent-nhg-c18="" class="text-center">Nombre Imagen</th>
            <th _ngcontent-nhg-c18="" class="text-center">Por Defecto</th>
            <th _ngcontent-nhg-c18="" class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody _ngcontent-nhg-c18="">
        <!---->
        <?php
        $rqdas1 = query("SELECT * FROM simulador_files WHERE cuce='$cuce' AND id_usuario='$id_usuario' AND codigo='$codigo_doc' ");
        while ($rqdas2 = fetch($rqdas1)) {
        ?>
            <tr _ngcontent-nhg-c18="">
                <td _ngcontent-nhg-c18="" class="text-center">
                    <div _ngcontent-nhg-c18="" class="btn-group" dropdown=""><button _ngcontent-nhg-c18="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-nhg-c18="" class="fa fa-cog text-primary"></span></button>
                        <!---->
                    </div>
                </td>
                <td _ngcontent-nhg-c18=""><img _ngcontent-nhg-c18="" class="img-responsive img-miniatura" height="100px" width="auto" alt="test" src="<?php echo $dominio_www; ?>contenido/imagenes/doc-usuarios/<?php echo $rqdas2['nombre']; ?>"></td>
                <td _ngcontent-nhg-c18=""><?php echo $rqdas2['descripcion']; ?></td>
                <td _ngcontent-nhg-c18=""> Si </td>
                <td _ngcontent-nhg-c18="">ELABORADO</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>