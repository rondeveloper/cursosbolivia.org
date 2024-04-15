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

/* crear firma */
if (isset_post('crear-firma')) {
    $firma_nombre = post('firma_nombre');
    $firma_cargo = post('firma_cargo');
    $sw_incluir_nombres = post('incluir_nombres');
    $firma_imagen = "";
    /* imagen firma */
    if (is_uploaded_file(archivo('firma_imagen'))) {
        $firma_imagen = time() . archivoName('firma_imagen');
        move_uploaded_file(archivo('firma_imagen'), $___path_raiz."contenido/imagenes/firmas/$firma_imagen");
    }
    query("INSERT INTO cursos_certificados_firmas(nombre, cargo, imagen, sw_incluir_nombres, estado) VALUES ('$firma_nombre','$firma_cargo','$firma_imagen','$sw_incluir_nombres','1')");
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> la firma fue agregada correctamente.
</div>';
}

/* editar firma */
if (isset_post('editar-firma')) {
    $id_firma = post('id_firma');
    $firma_nombre = post('firma_nombre');
    $firma_cargo = post('firma_cargo');
    $firma_imagen = post('firma_imagen_previo');
    $sw_incluir_nombres = post('incluir_nombres');

    /* imagen firma */
    if (is_uploaded_file(archivo('firma_imagen'))) {
        $firma_imagen = time() . archivoName('firma_imagen');
        move_uploaded_file(archivo('firma_imagen'), $___path_raiz."contenido/imagenes/firmas/$firma_imagen");
    }
    query("UPDATE cursos_certificados_firmas SET nombre='$firma_nombre', cargo='$firma_cargo', imagen='$firma_imagen', sw_incluir_nombres='$sw_incluir_nombres' WHERE id='$id_firma' ");
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> la firma fue editada correctamente.
</div>';
}



$resultado1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");

$total_registros = num_rows($resultado1);
$cnt = $total_registros;
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Firmas para certificados</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a data-toggle="modal" data-target="#MODAL-crear-firma" style="cursor:pointer;" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR FIRMA PARA CERTIFICADO</a>
        </div>
        <h3 class="page-header"> Firmas para certificados <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Firmas para certificados
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">#</th>
                            <th class="visible-lg" style="font-size:10pt;">Nombre</th>
                            <th class="visible-lg" style="font-size:10pt;">Cargo</th>
                            <th class="visible-lg" style="font-size:10pt;">Firma</th>
                            <th class="visible-lg" style="font-size:10pt;">Inclusion de nombres</th>
                            <th class="visible-lg" style="font-size:10pt;">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = fetch($resultado1)) {
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['nombre'];
                                    ?>         
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['cargo'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    $url_img = $dominio_www."contenido/imagenes/firmas/" . $producto['imagen'];
                                    if ($producto['imagen']=='') {
                                        echo "<b>Sin imagen de firma!</b>";
                                    } else {
                                        ?>
                                        <img src='<?php echo $url_img; ?>' style='width:120px;height:40px;border:1px solid #DDD;border-radius:5px;'/>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($producto['sw_incluir_nombres']=='1') {
                                        echo "<b>Si</b>";
                                    } else {
                                        echo "<b>No</b>";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a data-toggle="modal" data-target="#MODAL-editar-firma-<?php echo $producto['id']; ?>" style="cursor:pointer;" class='btn btn-xs btn-info active'><i class='fa fa-edit'></i> Editar</a>
                                </td>
                            </tr>



                            <!-- Modal-editar firma -->
                        <div id="MODAL-editar-firma-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">EDICI&Oacute;N DE FIRMA</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action='' method='post' enctype="multipart/form-data">
                                            <div>
                                                <p>Ingresa los datos correspondientes para la firma.</p>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Nombre:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <input type="text" class="form-control" name="firma_nombre" value='<?php echo $producto['nombre']; ?>'/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Cargo:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <input type="text" class="form-control" name="firma_cargo" value='<?php echo $producto['cargo']; ?>'/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Imagen:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <input type="hidden" class="form-control" name="firma_imagen_previo" value='<?php echo $producto['imagen']; ?>'/>
                                                        <input type="file" class="form-control" name="firma_imagen" value=''/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Img. actual:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-left">
                                                        <?php
                                                        $url_img = $dominio_www."contenido/imagenes/firmas/" . $producto['imagen'];
                                                        if ($producto['imagen']=='') {
                                                            echo "<b>Sin imagen de firma!</b>";
                                                        } else {
                                                            ?>
                                                            <br/>
                                                            <img src='<?php echo $url_img; ?>' style='width:200px;height:75px;border:1px solid #DDD;border-radius:5px;'/>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 text-right">
                                                        <span class="input-group-addon"><b>Incluir nombres:</b></span>
                                                    </div>
                                                    <div class="col-md-9 text-center">
                                                        <?php
                                                        $ckecked_si = "";
                                                        $ckecked_no = ' checked="" ';
                                                        if ($producto['sw_incluir_nombres'] == '1') {
                                                            $ckecked_si = ' checked="" ';
                                                            $ckecked_no = "";
                                                        }
                                                        ?>
                                                        <br/>
                                                        <label for="rb1-<?php echo $producto['id']; ?>">
                                                            <input type="radio" name="incluir_nombres" value="1" id="rb1-<?php echo $producto['id']; ?>" <?php echo $ckecked_si; ?>/> SI
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <label for="rb2-<?php echo $producto['id']; ?>">
                                                            <input type="radio" name="incluir_nombres" value="0" id="rb2-<?php echo $producto['id']; ?>" <?php echo $ckecked_no; ?>/> NO
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr/>

                                            </div>
                                            <br/>
                                            <div class="text-center">
                                                <input type='hidden' name='id_firma' value='<?php echo $producto['id']; ?>'/>
                                                <input type='submit' name='editar-firma' class="btn btn-success" value="ACTUALIZAR FIRMA"/>
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

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <?php
                            $urlget3 = '';
                            if (isset($get[3])) {
                                $urlget3 = '/' . $get[3];
                            }
                            $urlget4 = '';
                            if (isset($get[4])) {
                                $urlget4 = '/' . $get[4];
                            }
                            $urlget5 = '';
                            if (isset($buscar)) {
                                if ($urlget3 == '') {
                                    $urlget3 = '/--';
                                }
                                if ($urlget4 == '') {
                                    $urlget4 = '/--';
                                }
                                $urlget5 = '/' . $buscar;
                            }
                            ?>

                            <li><a href="cursos-listar/1.adm">Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_cursos = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_cursos) {
                                $fin_paginador = $total_cursos;
                            }

                            if ($total_cursos > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="cursos-listar/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="cursos-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>





<!-- Modal-generar reporte -->
<div id="MODAL-crear-firma" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREAR NUEVA FIRMA</h4>
            </div>
            <div class="modal-body">
                <form action='' method='post' enctype="multipart/form-data">
                    <div>
                        <p>Ingresa los datos correspondientes para la nueva firma.</p>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Nombre:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma_nombre" value='<?php echo $rqc2['firma1_nombre']; ?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Cargo:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="firma_cargo" value='<?php echo $rqc2['firma1_cargo']; ?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Imagen:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="file" class="form-control" name="firma_imagen" value=''/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>Incluir nombres:</b></span>
                            </div>
                            <div class="col-md-9 text-center">
                                <br/>
                                <label for="rb1-0">
                                    <input type="radio" name="incluir_nombres" value="1" id="rb1-0" checked=""/> SI
                                </label>
                                &nbsp;&nbsp;&nbsp;
                                <label for="rb2-0">
                                    <input type="radio" name="incluir_nombres" value="0" id="rb2-0" /> NO
                                </label>
                            </div>
                        </div>
                        <hr/>

                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='crear-firma' class="btn btn-success" value="CREAR NUEVA FIRMA"/>
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

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}
?>