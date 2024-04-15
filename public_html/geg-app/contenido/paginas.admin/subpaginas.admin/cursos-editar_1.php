<?php

//id pagina
$id_curso = (int)$get[2];

$mensaje = '';

if (isset_post('formulario')) {
    
    $id = post('id');
    $contenido = post_html('formulario');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $estado = post('estado');
    $sw_siguiente_semana = post('sw_siguiente_semana');
    $incrustacion = post_html('incrustacion');
    $google_maps = post_html('google_maps');
    
    $id_departamento = post('id_departamento');
    $fecha = post('fecha');
    $lugar = post('lugar');
    $horarios = post('horarios');
    $expositor = post('expositor');
    $colaborador = post('colaborador');
    $gastos = post('gastos');
    $observaciones = post('observaciones');
    
    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');
    
    $pixelcode = post_html('pixelcode');
    $short_link = post_html('short_link');
    
    if(isset_archivo('imagen')){
        $imagen = time() . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
        //sube_imagen(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
    }
    if(isset_archivo('imagen2')){
        $imagen2 = time() . archivoName('imagen2');
        move_uploaded_file(archivo('imagen2'), "contenido/imagenes/paginas/$imagen2");
    }
    if(isset_archivo('imagen3')){
        $imagen3 = time() . archivoName('imagen3');
        move_uploaded_file(archivo('imagen3'), "contenido/imagenes/paginas/$imagen3");
    }
    if(isset_archivo('imagen4')){
        $imagen4 = time() . archivoName('imagen4');
        move_uploaded_file(archivo('imagen4'), "contenido/imagenes/paginas/$imagen4");
    }

    query("UPDATE cursos SET 
              contenido='$contenido',
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              estado='$estado',
              sw_siguiente_semana='$sw_siguiente_semana',
              incrustacion='$incrustacion',
              google_maps='$google_maps',
              id_ciudad='$id_departamento',
              lugar='$lugar',
              horarios='$horarios',
              fecha='$fecha',
              expositor='$expositor',
              colaborador='$colaborador',
              gastos='$gastos',
              observaciones='$observaciones',
              imagen='$imagen',
              imagen2='$imagen2',
              imagen3='$imagen3',
              imagen4='$imagen4',
              short_link='$short_link',
              pixelcode='$pixelcode' 
               WHERE id='$id' ORDER BY id DESC limit 1 ");
    
    movimiento('Edicion de pagina', 'edicion-pagina', 'pagina', $id);
    
    $mensaje .= "<h4>Pagina Actualizada!</h4>";
    
    //echo "<script>location.href='pagina-editar/$titulo_identificador.adm';</script>";
}

$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);

editorTinyMCE('editor1');
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a >Cursos</a></li>
            <li class="active">Edici&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            
        </div>
        <h3 class="page-header"> <?php echo $curso['titulo']; ?> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de Cursos registrados en Infosicoes.
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">

                <form enctype="multipart/form-data" action="" method="post">
                    <table>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del Curso: </span>
                                    <input type="text" name="titulo" value="<?php echo $curso['titulo']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                    <p class="form-control text-center">
                                        <?php 
                                        $check = '';
                                        if($curso['estado']=='1'){
                                            $check = 'checked="checked"';
                                        }
                                        ?>
                                        <input type="radio" name="estado" <?php echo $check; ?> value="1" id="act"/> <label for="act">Activado</label> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php 
                                        $check2 = '';
                                        if($curso['estado']=='0'){
                                            $check2 = 'checked="checked"';
                                        }
                                        ?>
                                        <input type="radio" name="estado" <?php echo $check2; ?> value="0" id="dact"/> <label for="dact"> Desactivado</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php 
                                        $check2 = '';
                                        if($curso['estado']=='2'){
                                            $check2 = 'checked="checked"';
                                        }
                                        ?>
                                        <input type="radio" name="estado" <?php echo $check2; ?> value="2" id="temp"/> <label for="temp"> Temporal</label>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Semana del curso: </span>
                                    <p class="form-control text-center">
                                        <?php 
                                        $check = '';
                                        if($curso['sw_siguiente_semana']=='0'){
                                            $check = 'checked="checked"';
                                        }
                                        ?>
                                        <input type="radio" name="sw_siguiente_semana" <?php echo $check; ?> value="0" id="act_s"/> <label for="act_s">Esta Semana</label> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php 
                                        $check2 = '';
                                        if($curso['sw_siguiente_semana']=='1'){
                                            $check2 = 'checked="checked"';
                                        }
                                        ?>
                                        <input type="radio" name="sw_siguiente_semana" <?php echo $check2; ?> value="1" id="dact_s"/> <label for="dact_s"> Siguiente Semana</label>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del curso: </span>
                                    <input type="date" name="fecha" class="form-control" value="<?php echo $curso['fecha']; ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Departamento: </span>
                                    <select class="form-control" name="id_departamento">
                                        <?php
                                        $rqd1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ");
                                        while($rqd2 = mysql_fetch_array($rqd1)){
                                            $selected = '';
                                            if($curso['id_ciudad']==$rqd2['id']){
                                                $selected = ' selected="selected" ';
                                            }
                                            echo '<option value="'.$rqd2['id'].'" '.$selected.' >'.$rqd2['nombre'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Lugar: </span>
                                    <input type="text" name="lugar" value="<?php echo $curso['lugar']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Google Maps: </span>
                                    <input type="text" name="google_maps" class="form-control" value='<?php echo ($curso['google_maps']); ?>' placeholder='<iframe src="https://www.google.com/maps/embed?pb=!1m17...'>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Expositor: </span>
                                    <input type="text" name="expositor" value="<?php echo $curso['expositor']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Colaborador: </span>
                                    <input type="text" name="colaborador" value="<?php echo $curso['colaborador']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Gastos: </span>
                                    <input type="text" name="gastos" value="<?php echo $curso['gastos']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Observaciones: </span>
                                    <input type="text" name="observaciones" value="<?php echo $curso['observaciones']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; URL corta: </span>
                                    <input type="text" name="short_link" value="<?php echo $curso['short_link']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Pixel Code: </span>
                                    <textarea name="pixelcode" class="form-control" ><?php echo $curso['pixelcode']; ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                    <input type="file" name="imagen" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/<?php echo $curso['imagen']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 2: </span>
                                    <input type="file" name="imagen2" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/<?php echo $curso['imagen2']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 3: </span>
                                    <input type="file" name="imagen3" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/<?php echo $curso['imagen3']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 4: </span>
                                    <input type="file" name="imagen4" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/<?php echo $curso['imagen4']; ?>.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <b>Palabras reservadas:</b> [imagen-1] , [imagen-2] , [imagen-3] , [imagen-4] 
                                <br/>
                                <br/>
                                <textarea name="formulario" id="editor1" style="width:100%;margin:auto;" rows="25"><?php echo $curso['contenido']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <br/>
                                Incrustacion
                                <br/>
                                <br/>
                                <textarea name="incrustacion" style="width:100%;margin:auto;" rows="5"><?php echo $curso['incrustacion']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="hidden" name="id" value="<?php echo $curso['id']; ?>"/>
                                    <input type="hidden" name="preimagen" value="<?php echo $curso['imagen']; ?>"/>
                                    <input type="hidden" name="preimagen2" value="<?php echo $curso['imagen2']; ?>"/>
                                    <input type="hidden" name="preimagen3" value="<?php echo $curso['imagen3']; ?>"/>
                                    <input type="hidden" name="preimagen4" value="<?php echo $curso['imagen4']; ?>"/>
                                    <input type="submit" value="ACTUALIZAR PAGINA" class="btn btn-success btn-lg btn-animate-demo"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>




