<?php

$id = $get[2];
$mensaje = '';

if (post('eliminar-banner')) {

    $r1 = query("DELETE FROM banners WHERE id='$id' ");
    if ($r1) {
        movimiento('Eliminación de Banner', 'eliminacion-banner', 'banner', $id);
        echo "<script>alert('Banner Eliminado!');location.href='banners-listar.adm';</script>";
    } else {
        echo "<script>alert('Error!');location.href='banners-listar.adm';</script>";
    }
    exit;
}

$r1 = query("SELECT * FROM banners WHERE id='$id' ");
$r2 = fetch($r1);
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="banners-listar.adm">Banners</a></li>
            <li class="active">Eliminación</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Eliminación de Banner <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Aqui puedes eliminar los Banners de INFOSICOES.
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">

                <b style="font-size:25pt;">Desea eliminar este banner permanentemente?</b>
                <div class='formulario-agregar-usuario'>
                    <form action='' method='post' enctype="multipart/form-data">
                        <table style='margin:auto;width:80%;'>
                            <tr>
                                <td style="width:50%">
                                    <div class="form-group">
                                        <label for="nombre">Imagen</label> <br/>
            <!--                            <input type="file" name="imagen" style="width:90%;border:2px solid gray; border-radius:25px;padding:5%;"/>-->
                                        <img src="<?php echo $dominio_www; ?>contenido/imagenes/banners/<?php echo $r2['imagen']; ?>" style="width:100%"/>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nivel">Apertura</label>
                                        <select name="target" id="role" class="form-control" disabled>
                                            <option value="_blank" <?php
                                            if ($r2['target'] == '_blank') {
                                                echo ' selected="selected" ';
                                            }
                                            ?> >Ventana Nueva</option>
                                            <option value="_self" <?php
                                            if ($r2['target'] == '_self') {
                                                echo ' selected="selected" ';
                                            }
                                            ?> >Misma Ventana</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="email">Url de Direccionamiento</label>
                                        <input disabled name='url' class="form-control" id="email" placeholder="http://www.ejemplo.com" value="<?php echo $r2['url']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nivel">Estado</label>
                                        <select name="estado" id="role" class="form-control" disabled>
                                            <option value="1" <?php
                                            if ($r2['estado'] == '1') {
                                                echo ' selected="selected" ';
                                            }
                                            ?> >Publico</option>
                                            <option value="0" <?php
                                            if ($r2['estado'] == '0') {
                                                echo ' selected="selected" ';
                                            }
                                            ?> >Oculto</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group">
                                        <label for="email">Descripcion (title/alt)</label>
                                        <input disabled name='descripcion' class="form-control" value="<?php echo $r2['descripcion']; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-sm-offset-4">
                                                 <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                                <input type="submit" name="eliminar-banner" class="btn btn-success" value="ELIMINAR BANNER">
                                                <button type="button" class="btn btn-danger" onclick="location.href='banners-listar.adm';">CANCELAR</button>
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








