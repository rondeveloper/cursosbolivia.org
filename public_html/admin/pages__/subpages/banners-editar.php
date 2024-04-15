<?php

$id = $get[2];
$mensaje = '';

if (post('actualizar-banner')) {
    
    $imagen = post('imagen');
    if (is_uploaded_file($_FILES['nueva_imagen']['tmp_name'])) {
        $imagen = time() . $_FILES['nueva_imagen']['name'];
        move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $___path_raiz."contenido/imagenes/banners/" . $imagen);
    }

    $r1 = query("UPDATE banners SET "
            . "descripcion='" . post('descripcion') . "',"
            . "mes_referencia='" . post('mes_referencia') . "',"
            . "dia_referencia='" . post('dia_referencia') . "',"
            . "imagen='$imagen',"
            . "url='" . post('url') . "',"
            . "target='" . post('target') . "',"
            . "estado='" . post('estado') . "'"
            . " WHERE id='$id' ");
    if ($r1) {
        movimiento('Edición de Banner', 'edicion-banner', 'banner', $id);
        $mensaje .= "<h3>Banner Editado Exitosamente!</h3>";
        $mensaje .= "<a href='banners-listar.adm'>Listar Banners</a><hr/><br/>";
    } else {
        $mensaje .= "<script>alert('Error en el proceso!');history.back();</script>";
    }
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
            <li class="active">Edicion</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Edicion de Banners <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Aqui puedes editar los Banners de INFOSICOES.
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
                                <td style="width:50%">
                                    <div class="form-group">
                                        <label for="nombre">Imagen actual</label> <br/>
            <!--                            <input type="file" name="imagen" style="width:90%;border:2px solid gray; border-radius:25px;padding:5%;"/>-->
                                        <img src="banners/<?php echo $r2['imagen']; ?>.size=5.img" style="width:100%;border:1px solid gray;padding:1px;"/>
                                        <input type='hidden' name='imagen' value='<?php echo $r2['imagen']; ?>'/>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nueva-imagen">Nueva imagen</label>
                                        <input type='file' name='nueva_imagen' value='' id='nueva-imagen' class="form-control"/>
                                        <br/>
                                        <label for="nivel">Apertura</label>
                                        <select name="target" id="role" class="form-control">
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
                                        <br/>
                                        <label for="nueva-imagen">Mes referencia</label>
                                        <input type='text' name='mes_referencia' value='<?php echo $r2['mes_referencia']; ?>' class="form-control"/>
                                        <br/>
                                        <label for="nueva-imagen">D&iacute;a referencia</label>
                                        <input type='text' name='dia_referencia' value='<?php echo $r2['dia_referencia']; ?>' class="form-control"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="email">Url de Direccionamiento</label>
                                        <input name='url' class="form-control" id="email" placeholder="http://www.ejemplo.com" value="<?php echo $r2['url']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="nivel">Estado</label>
                                        <select name="estado" id="role" class="form-control">
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
                                        <input name='descripcion' class="form-control" value="<?php echo $r2['descripcion']; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-sm-offset-4">
                                                 <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                                <input type="submit" name="actualizar-banner" class="btn btn-success" value="ACTUALIZAR BANNER">
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








