<?php

$mensaje = '';

if (archivo('imagen')) {

    if (archivo('imagen')) {

        $nombreImagen = 'bann-' . time() . archivoName('imagen');

        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/banners/" . $nombreImagen);

        $r1 = query("INSERT INTO banners ("
                . "imagen,"
                . "descripcion,"
                . "url,"
                . "target,"
                . "seccion,"
                . "estado"
                . ") VALUES("
                . "'" . $nombreImagen . "',"
                . "'" . post('descripcion') . "',"
                . "'" . post('url') . "',"
                . "'" . post('target') . "',"
                . "'principal',"
                . "'" . post('estado') . "'"
                . ")");
        if ($r1) {
            $rraux1 = query("SELECT id FROM banners WHERE imagen='$nombreImagen' ");
            $rraux2 = fetch($rraux1);
            $id = $rraux2['id'];
            movimiento('Creación de Banner', 'creacion-banner', 'banner', $id);
            $mensaje .= "<h3>Banner Agregado Exitosamente!</h3>";
            $mensaje .= "<a href='banners-listar.adm'>Listar Banners</a><hr/><br/>";
        } else {
            $mensaje .= "<script>alert('Error en el proceso!');history.back();</script>";
        }
    } else {
        $mensaje .= "<h3>No se selecciono ninguna imagen!</h3>";
    }
}
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="banners-listar.adm">Banners</a></li>
            <li class="active">Crear banner</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Creación de Banners <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Aqui puedes Crear nuevos Banners para INFOSICOES.
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">

                <div class='formulario-agregar-usuario'>
                    <form action='' method='post' enctype="multipart/form-data">
                        <table style='margin:auto;width:80%;'>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="nombre">Imagen</label> *
                                        <input type="file" name="imagen" style="width:90%;border:2px solid gray; border-radius:25px;padding:5%;"/>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nivel">Apertura</label>
                                        <select name="target" id="role" class="form-control">
                                            <option value="_blank">Ventana Nueva</option>
                                            <option value="_self">Misma Ventana</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="email">Url de Direccionamiento</label>
                                        <input name='url' class="form-control" id="email" placeholder="http://www.ejemplo.com">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nivel">Estado</label>
                                        <select name="estado" id="role" class="form-control">
                                            <option value="1">Publico</option>
                                            <option value="0">Oculto</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label for="email">Descripcion (title/alt)</label>
                                        <input name='descripcion' class="form-control">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-sm-offset-4">
                                                <input type="submit" name="crear-banner" class="btn btn-success" value="AGREGAR BANNER">
                                                <button type="button" class="btn btn-danger" onclick="location.href = 'banners-listar.adm';">CANCELAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Mas campos aqui -->
                        </table>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>




