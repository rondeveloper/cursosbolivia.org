<?php
/* mensaje */
$mensaje = "";

//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 150;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


if (isset_post('buscarr') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
} else {
    $sw_busqueda = false;
}

/* crear fondo */
if (isset_post('crear-fondo')) {
    $descripcion = post('descripcion');
    $modo = post('modo');
    /* archivo fondo */
    $tag_image = 'imagen';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz . 'contenido/imagenes/cursos/certificados/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('png'))) {
            $imagen = 'cert-'.$modo.'-'.substr(md5(rand(999, 99999)),5,4).'.'.$ext;
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $imagen);
            query("INSERT INTO certificados_imgfondo (descripcion,imagen,modo,estado) VALUES ('$descripcion','$imagen','$modo','1')");
        $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> el registro fue agregado correctamente.
</div>';
        } else {
            $sw_proceder = false;
            $mensaje .= '<br><div class="alert alert-danger">
<strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '] formatos permitidos: png
</div>';
        }
    }
}

/* editar fondo */
if (isset_post('editar-fondo')) {
    $id_fondo = post('id_fondo');
    $descripcion = post('descripcion');
    $imagen = post('imagen_previo');
    $modo = post('modo');
    $estado = post('estado');
    
    /* archivo fondo */
    $tag_image = 'imagen';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz . 'contenido/imagenes/cursos/certificados/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('png'))) {
            $imagen = 'cert-'.$modo.'-'.substr(md5(rand(999, 99999)),5,4).'.'.$ext;
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $imagen);
            $rqdprsend1 = query("SELECT imagen FROM certificados_imgfondo WHERE id='$id_fondo' limit 1 ");
            $rqdprsend2 = fetch($rqdprsend1);
            if(file_exists($carpeta_destino . $rqdprsend2['imagen']) && $rqdprsend2['imagen']!=""){
                unlink($carpeta_destino . $rqdprsend2['imagen']);
            }
        $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> el registro fue agregado correctamente.
</div>';
        } else {
            $sw_proceder = false;
            $mensaje .= '<br><div class="alert alert-danger">
<strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '] formatos permitidos: png
</div>';
        }
    }
    query("UPDATE certificados_imgfondo SET descripcion='$descripcion', imagen='$imagen', modo='$modo', estado='$estado' WHERE id='$id_fondo' ORDER BY id DESC limit 1 ");
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> el registro fue modificado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM certificados_imgfondo ORDER BY id ASC ");

$total_registros = num_rows($resultado1);
$cnt = 1;
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Fondos para certificados</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a data-toggle="modal" data-target="#MODAL-crear-fondo" style="cursor:pointer;" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR FONDO DIGITAL</a>
        </div>
        <h3 class="page-header"> Fondos para certificados digitales <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Fondos para certificados
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="font-size:10pt;">#</th>
                            <th style="font-size:10pt;">Fondo</th>
                            <th style="font-size:10pt;">Descripci&oacute;n</th>
                            <th style="font-size:10pt;">Imagen</th>
                            <th style="font-size:10pt;">Estado</th>
                            <th style="font-size:10pt;">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = fetch($resultado1)) {
                            ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td>
                                    <?php
                                    echo "<b style='color:green;text-transform:uppercase;'>".$producto['modo']."</b>";
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $producto['descripcion'];
                                    ?>         
                                </td>
                                <td class="text-center" style="padding: 5px;">
                                    <?php
                                    if ($producto['imagen']=='') {
                                        echo "<b>Sin imagen</b>";
                                    } else {
                                        ?>
                                        <a href="<?php echo $dominio_www; ?>contenido/imagenes/cursos/certificados/<?php echo $producto['imagen']; ?>" target="_blank">
                                            <img src='<?php echo $dominio_www; ?>contenido/imagenes/cursos/certificados/<?php echo $producto['imagen']; ?>' style='width:120px;height:auto;border:1px solid #DDD;border-radius:5px;'/>
                                            <br>
                                            Ver imagen
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($producto['estado']=='1'){
                                        echo '<label class="label label-success">Habilitado</label>';
                                    }else{
                                        echo '<label class="label label-danger">Des-habilitado</label>';
                                    }
                                    ?>         
                                </td>
                                <td style="width:120px;">
                                    <a data-toggle="modal" data-target="#MODAL-editar-fondo-<?php echo $producto['id']; ?>" style="cursor:pointer;" class='btn btn-xs btn-info active'><i class='fa fa-edit'></i> Editar</a>
                                </td>
                            </tr>



                            <!-- Modal-editar firma -->
                        <div id="MODAL-editar-fondo-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">EDICI&Oacute;N DE FONDO</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action='' method='post' enctype="multipart/form-data">
                                            <div>
                                                <p>Ingresa los datos correspondientes para el fondo.</p>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Fondo:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <select class="form-control" name="modo">
                                                            <option value="digital" <?php echo $producto['modo']=='digital'?'selected=""':''; ?>>DIGITAL</option>
                                                            <option value="fisico" <?php echo $producto['modo']=='fisico'?'selected=""':''; ?>>FISICO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Descripci&oacute;n:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <input type="text" class="form-control" name="descripcion" value='<?php echo $producto['descripcion']; ?>' required=""/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Imagen:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <input type="hidden" class="form-control" name="imagen_previo" value='<?php echo $producto['imagen']; ?>'/>
                                                        <input type="file" class="form-control" name="imagen" value='' accept="image/png"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Img. actual:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/cursos/certificados/" . $producto['imagen'];
                                                        if ($producto['imagen']=='') {
                                                            echo "<b>Sin imagen de firma!</b>";
                                                        } else {
                                                            ?>
                                                            <br/>
                                                            <img src='<?php echo $url_img; ?>' style='width:100%;border:1px solid #DDD;border-radius:5px;'/>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Estado:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-center">
                                                        <?php
                                                        $ckecked_si = "";
                                                        $ckecked_no = ' checked="" ';
                                                        if ($producto['estado'] == '1') {
                                                            $ckecked_si = ' checked="" ';
                                                            $ckecked_no = "";
                                                        }
                                                        ?>
                                                        <br/>
                                                        <label>
                                                            <input type="radio" name="estado" value="1" <?php echo $ckecked_si; ?>/> HABILITADO
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <label>
                                                            <input type="radio" name="estado" value="0" <?php echo $ckecked_no; ?>/> DES-HABILITADO
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr/>

                                            </div>
                                            <br/>
                                            <div class="text-center">
                                                <input type='hidden' name='id_fondo' value='<?php echo $producto['id']; ?>'/>
                                                <input type='submit' name='editar-fondo' class="btn btn-success" value="ACTUALIZAR FONDO"/>
                                            </div>

                                        </form>
                                        <hr/>                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-generar reporte -->

                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>





<!-- Modal-generar reporte -->
<div id="MODAL-crear-fondo" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREAR NUEVO FONDO</h4>
            </div>
            <div class="modal-body">
                <form action='' method='post' enctype="multipart/form-data">
                    <div>
                        <p>Ingresa los datos correspondientes para el fondo.</p>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Fondo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <select class="form-control" name="modo">
                                    <option value="digital">DIGITAL</option>
                                    <option value="fisico">FISICO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Descripci&oacute;n:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="descripcion" value='' required="" autocomplete="off"/>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Imagen:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="file" class="form-control" name="imagen" required="" accept="image/png"/>
                            </div>
                        </div>
                        <hr/>
                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='crear-fondo' class="btn btn-success" value="CREAR NUEVO FONDO"/>
                    </div>

                </form>
                <hr/>                                        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->
