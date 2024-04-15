<?php
/* verif acceso */
if (!acceso_cod('adm-mainconfig')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

/* mensaje */
$mensaje = '';

/* editar-configuracion */
if (isset_post('editar-configuracion')) {
    $id_configuracion = post('id_configuracion');
    $valor = post('valor');
    $sw_proceder = true;
    /* archivo imagen */
    $tag_image = 'archivo';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz . 'contenido/imagenes/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
            $nombre_imagen = $id_configuracion.'-'.substr(md5(rand(999, 99999)),5,7).'.'.$ext;
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            $rqdprsend1 = query("SELECT valor FROM configuracion_sistema WHERE id='$id_configuracion' limit 1 ");
            $rqdprsend2 = fetch($rqdprsend1);
            if(file_exists($carpeta_destino . $rqdprsend2['valor']) && $rqdprsend2['valor']!=""){
                unlink($carpeta_destino . $rqdprsend2['valor']);
            }
            $valor = $nombre_imagen;
            $mensaje .= '<br><div class="alert alert-success">
<strong>EXITO</strong> el archivo se subio correctamente.
</div>';
        } else {
            $sw_proceder = false;
            $mensaje .= '<br><div class="alert alert-danger">
<strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '] formatos permitidos: jpg, jpeg y png
</div>';
        }
    }
    /* enum value */
    if(isset_post('enum_value')){
        $valor = post('enum_value').'_'.post('enum_predeterminado');
    }
    /* configuracion */
    if($sw_proceder){
        query("UPDATE configuracion_sistema SET "
            . "valor='$valor'"
            . " WHERE id='$id_configuracion' LIMIT 1 ");
        logcursos('Edicion de configuracion', 'configuracion-edicion', 'configuracion', $id_configuracion);
        $mensaje .= '<div class="alert alert-success">
    <strong>Exito!</strong> registro editado correctamente.
    </div>';
    }
}

$resultado1 = query("SELECT * FROM configuracion_sistema c  ORDER BY c.orden ASC,c.id ASC ");
$total_registros = num_rows($resultado1);
$cnt = $total_registros;
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
        </ul>
        <h3 class="page-header">
            CONFIGURACIONES DEL SISTEMA <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                VARIABLES
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Listado de variables configurables del sistema
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CODIGO</th>
                                        <th>VALOR</th>
                                        <th>DESCRIPCION</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    while ($producto = fetch($resultado1)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><?php echo $producto['codigo']; ?></td>
                                            <td style="word-break: break-all;max-width: 570px;">
                                                <?php 
                                                if($producto['tipo']==4){
                                                    $ar1 = explode('_',$producto['valor']);
                                                    $valor_actual = $ar1[0];
                                                    echo '<span style="font-size:14pt;color:#4673c5;">ACTUAL</span> &nbsp: <b style="font-size:20pt;color:#4673c5;">'.$valor_actual.'</b>';
                                                    echo '<br><br>';
                                                    echo '<i style="font-size:14pt;color:gray;">Posibles: '.$ar1[1].'</i>';
                                                }elseif($producto['tipo']==3){
                                                    echo '<div style="border: 1px solid #e4e4e4;border-radius: 5px;padding: 10px;color: #6884a7;">
                                                    <img src="'.$dominio_www.'contenido/imagenes/'.$producto['valor'].'" style="background: #8080b7;max-width: 400px;max-height: 120px;color: white;" alt="No se encontro la imagen"/>
                                                </div>';
                                                }elseif($producto['tipo']==2){
                                                    echo $producto['valor']=='1'?'<b style="color:green;font-size: 14pt;">SI</b>':'<b style="color:gray;font-size: 14pt;">NO</b>';
                                                }else{
                                                    echo $producto['valor'];
                                                }
                                                ?>
                                            </td>
                                            <td style="word-break: break-all;max-width: 350px;"><?php echo $producto['descripcion']; ?></td>
                                            <td>
                                                <a data-toggle="modal" data-target="#MODAL-editar-configuracion-<?php echo $producto['id']; ?>" title="Editar">
                                                    <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i> EDITAR</button>
                                                </a>

                                                <!-- Modal -->
                                                <div id="MODAL-editar-configuracion-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="panel panel-default">
                                                                    <form action="" method="post" enctype="multipart/form-data">
                                                                        <div class="panel-body">
                                                                        <div class="row">
                                                                                <div class="form-group">
                                                                                    <label class="col-lg-3 col-md-3 control-label text-primary">CODIGO</label>
                                                                                    <div class="col-lg-9 col-md-9">
                                                                                        <input type="text" class="form-control form-cascade-control" name="" readonly value="<?php echo $producto['codigo']; ?>" placeholder="..." autocomplete="off" />
                                                                                        <br />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCION</label>
                                                                                    <div class="col-lg-9 col-md-9">
                                                                                        <div style="border: 1px solid #e4e4e4;border-radius: 5px;padding: 10px;color: #6884a7;"><?php echo $producto['descripcion']; ?></div>
                                                                                        <br />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <?php 
                                                                            if($producto['tipo']==3){
                                                                                ?>
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        <label class="col-lg-3 col-md-3 control-label text-primary">IMAGEN ACTUAL</label>
                                                                                        <div class="col-lg-9 col-md-9">
                                                                                            <div style="border: 1px solid #e4e4e4;border-radius: 5px;padding: 10px;color: #6884a7;">
                                                                                                <img src="<?php echo $dominio_www.'contenido/imagenes/'.$producto['valor']; ?>" style="background: #8080b7;max-width: 100%;max-height: 120px;color:white;" alt="No se encontro la imagen"/>
                                                                                            </div>
                                                                                            <br />
                                                                                        </div>                                                                                  
                                                                                     </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label class="col-lg-3 col-md-3 control-label text-primary">VALOR</label>
                                                                                    <div class="col-lg-9 col-md-9">
                                                                                        <?php 
                                                                                        if($producto['tipo']==4){
                                                                                            $ar1 = explode('_',$producto['valor']);
                                                                                            $valor_actual = $ar1[0];
                                                                                            $enums = explode(',',$ar1[1]);
                                                                                            ?>
                                                                                            <select class="form-control form-cascade-control" name="enum_value">
                                                                                                <?php
                                                                                                foreach ($enums as $value) {
                                                                                                    echo '<option value="'.$value.'" '.($value==$valor_actual?'selected=""':'').'>'.$value.'</option>';
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                            <input type="hidden" name="enum_predeterminado" value="<?php echo $ar1[1]; ?>"/>
                                                                                            <?php
                                                                                        }elseif($producto['tipo']==3){
                                                                                            ?>
                                                                                            <input type="file" class="form-control form-cascade-control" name="archivo" value="" required placeholder="..." autocomplete="off" />
                                                                                            <?php
                                                                                        }elseif($producto['tipo']==2){
                                                                                            ?>
                                                                                            <select class="form-control form-cascade-control" name="valor">
                                                                                                <option value="0">NO</option>
                                                                                                <option value="1" <?php echo $producto['valor']=='1'?'selected=""':''; ?>>SI</option>
                                                                                            </select>
                                                                                            <?php
                                                                                        }else{
                                                                                            ?>
                                                                                            <input type="text" class="form-control form-cascade-control" name="valor" value="<?php echo $producto['valor']; ?>" required placeholder="..." autocomplete="off" />
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        <br />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-footer">
                                                                            <input type="hidden" name="id_configuracion" value="<?php echo $producto['id']; ?>" />
                                                                            <input type="submit" name="editar-configuracion" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS" />
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>
        </div>

    </div>
</div>
