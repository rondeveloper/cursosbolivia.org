<?php
/* ID ONLINE COURSE */
$id_examen = (int) $get[2];

/* MENSAJE */
$mensaje = '';

/* eliminar pregunta */
if (isset($get[5]) && $get[3] == 'eliminar-pregunta') {
    if (md5('442' . $get[4]) == $get[5]) {
        $id_pregunta = $get[4];
        $vrif1 = query("SELECT id FROM cursos_examenes_preguntas WHERE id='$id_pregunta' LIMIT 1 ");
        if (num_rows($vrif1) > 0) {
            query("DELETE FROM cursos_examenes_respuestas WHERE id_pregunta='$id_pregunta' ");
            query("DELETE FROM cursos_examenes_preguntas WHERE id='$id_pregunta' ");
            logcursos('Eliminacion de pregunta de examen', 'examen-edicion', 'examen-general', $id_examen);
            $mensaje .= '<div class="alert alert-success">
          <strong>Exito!</strong> el registro fue eliminado correctamente.
        </div>';
        }
    }
}

/* agregar-pregunta-examen */
if (isset_post('agregar-pregunta-examen')) {
    $pregunta = post('pregunta');
    $vrif1 = query("SELECT id FROM cursos_examenes_preguntas WHERE id_onlinecourse='$id_examen' AND pregunta LIKE '$pregunta' LIMIT 1 ");
    if (num_rows($vrif1) == 0) {
        query("INSERT INTO cursos_examenes_preguntas(id_onlinecourse,id_leccion,pregunta,estado) VALUES ('$id_examen','0','$pregunta','1')");
        $id_pregunta = insert_id();
        for ($i = 1; $i <= 7; $i++) {
            if (isset_post('respuesta-' . $i)) {
                $respuesta = post('respuesta-' . $i);
                if (isset_post('check-respuesta-' . $i)) {
                    $sw_respuesta = '1';
                } else {
                    $sw_respuesta = '0';
                }
                query("INSERT INTO cursos_examenes_respuestas(id_pregunta, respuesta, sw_correcto) VALUES ('$id_pregunta','$respuesta','$sw_respuesta')");
            }
        }
        logcursos('Agregado de pregunta de examen', 'examen-edicion', 'examen-general', $id_examen);
        $mensaje .= '<div class="alert alert-success">
          <strong>Exito!</strong> el regsitro se completo correctamente.
        </div>';
    }
}


/* editar-pregunta-evaluacion */
if (isset_post('editar-pregunta-evaluacion')) {
    $id_pregunta = post('id_pregunta');
    $pregunta = post('pregunta');
    $estado = post('estado');
    query("UPDATE cursos_examenes_preguntas SET pregunta='$pregunta',estado='$estado' WHERE id='$id_pregunta' ORDER BY id DESC limit 1 ");
    query("DELETE FROM cursos_examenes_respuestas WHERE id_pregunta='$id_pregunta' ");
    for ($i = 1; $i <= 7; $i++) {
        if (isset_post('respuesta-' . $i)) {
            $respuesta = post('respuesta-' . $i);
            if (isset_post('check-respuesta-' . $i)) {
                $sw_respuesta = '1';
            } else {
                $sw_respuesta = '0';
            }
            query("INSERT INTO cursos_examenes_respuestas(id_pregunta, respuesta, sw_correcto) VALUES ('$id_pregunta','$respuesta','$sw_respuesta')");
        }
    }
    logcursos('Edicion de pregunta de examen [ID preg:' . $id_pregunta . ']', 'examen-edicion', 'examen-general', $id_examen);
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}

/* actualizar-num-preguntas */
if (isset_post('actualizar-num-preguntas')) {
    $num_preguntas = post('num_preguntas');
    query("UPDATE cursos_examenes_generales SET num_preguntas='$num_preguntas' WHERE id='$id_examen' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de numero de preguntas de examen', 'examen-edicion', 'examen-general', $id_examen);
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}

/* actualizar-minutos-limite */
if (isset_post('actualizar-minutos-limite')) {
    $minutos = post('minutos');
    query("UPDATE cursos_examenes_generales SET minutos='$minutos' WHERE id='$id_examen' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de tiempo de examen', 'examen-edicion', 'examen-general', $id_examen);
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}

/* actualizar-estado-habilitacion */
if (isset_post('actualizar-estado-habilitacion')) {
    $sw_examen = post('sw_examen');
    query("UPDATE cursos_examenes_generales SET sw_examen='$sw_examen' WHERE id='$id_examen' ORDER BY id DESC limit 1 ");
    logcursos('Edicion habilitacion de examen', 'examen-edicion', 'examen-general', $id_examen);
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}

$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

/* examen */
$rqdco1 = query("SELECT * FROM cursos_examenes_generales WHERE id='$id_examen' LIMIT 1 ");
$examen = fetch($rqdco1);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="cursos-virtuales-editar/<?php echo $id_examen; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO VIRTUAL</a>
        </div>
        <h3 class="page-header"> <i class="btn btn-success active btn-sm">EXAMEN</i> <?php echo $examen['titulo']; ?> </h3>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <?php
                $rqlc1 = query("SELECT * FROM cursos_examenes_preguntas WHERE id_onlinecourse='$id_examen' AND estado='1' ORDER BY id ASC ");
                $cnt_preguntas = num_rows($rqlc1);
                if ($cnt_preguntas == 0) {
                    ?>
                    <div class="alert alert-danger">
                        <strong>AVISO</strong> este examen no tiene asignado ninguna pregunta de evaluaci&oacute;n.
                    </div>
                    <?php
                }
                ?>

                <div class="row">
                    <div class="col-md-2 col-lg-3"></div>
                    <div class="col-md-8 col-lg-6">
                        <div style="background: #9ce09c;border: 5px solid #66ce66;border-radius: 5px;padding: 20px;padding-bottom: 5px;">
                             <?php
                             $num_preguntas_habilitadas = $examen['num_preguntas'];
                             $minutos_habilitados = $examen['minutos'];
                             ?>
                            <form action="" method="post">
                                <table class="table table-bordered" style="background: #FFF;">
                                    <tr>
                                        <td colspan="3" class="text-center"><b>EXAMEN</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;"><b>TITULO:</b></td>
                                        <td colspan="2">
                                            <?php echo $examen['titulo']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>PREGUNTAS:</b></td>
                                        <td>
                                            <?php echo $cnt_preguntas; ?> preguntas 
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-success btn-block"  data-toggle="modal" data-target="#MODAL-preguntas-onlinecourse"><i class="fa fa-plus"></i> AGREGAR</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>N&Uacute;MERO DE PREGUNTAS ALEATORIAS</b>:
                                            <br>
                                            Minimo: 1 &nbsp; | &nbsp; M&aacute;ximo: <?php echo $cnt_preguntas; ?>
                                        </td>
                                        <td>
                                            <input type="number" required="" name="num_preguntas" min="1" max="<?php echo $cnt_preguntas; ?>" value="<?php echo $num_preguntas_habilitadas; ?>" class="form-control">       
                                        </td>
                                        <td style="width: 114px;">
                                            <input type="submit" name="actualizar-num-preguntas" value="ACTUALIZAR" class="btn btn-info btn-sm btn-block"/>       
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>MINUTOS PARA REALIZAR EL EXAMEN</b>:
                                            <br>
                                            (Configurar 0 para sin limite)
                                        </td>
                                        <td>
                                            <input type="number" required="" name="minutos" min="0" max="1000" value="<?php echo $minutos_habilitados; ?>" class="form-control">       
                                        </td>
                                        <td style="width: 114px;">
                                            <input type="submit" name="actualizar-minutos-limite" value="ACTUALIZAR" class="btn btn-info btn-sm btn-block"/>       
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <table class="table table-striped table-condensed table-hover table-bordered">
                        <tr>
                            <th colspan="3">PREGUNTAS DE EVALUACI&Oacute;N</th>
                        </tr>
                        <?php
                        $cnt_pregunta = 1;
                        while ($producto = fetch($rqlc1)) {
                            ?>
                            <tr>
                                <td style="width: 75px;color: #1296d6;">
                                    <b>Pregunta <?php echo $cnt_pregunta++; ?></b>
                                    <br>
                                    <?php
                                    if ($producto['estado'] == '1') {
                                        echo '<span class="label label-success">Habilitado</span>';
                                    } else {
                                        echo '<span class="label label-danger">No habilitado</span>';
                                    }
                                    ?>
                                    <br>
                                    <br>
                                    <a href="examenes-preguntas/<?php echo $id_examen; ?>/eliminar-pregunta/<?php echo $producto['id']; ?>/<?php echo md5('442' . $producto['id']); ?>.adm" class="btn btn-xs btn-danger" onclick="return confirm('DESEA ELIMINAR LA PREGUNTA ?')"><i class="fa fa-trash-o"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                    <b class="btn btn-xs btn-primary" onclick="editar_pregunta('<?php echo $producto['id']; ?>');"><i class="fa fa-edit"></i></b>
                                </td>
                                <td>
                                    <span style="font-size: 15pt;"><?php echo $producto['pregunta']; ?></span>
                                </td>
                                <td style="width: 40%;">
                                    <table class="table table-striped table-condensed table-hover table-bordered">
                                        <?php
                                        $id_pregunta = $producto['id'];
                                        $rqres1 = query("SELECT * FROM cursos_examenes_respuestas WHERE id_pregunta='$id_pregunta' ORDER BY id ASC ");
                                        $cnt = 1;
                                        while ($respuesta = fetch($rqres1)) {
                                            ?>
                                            <tr>
                                                <td style="width: 45px;color: #1296d6;"> Res. <?php echo $cnt++; ?></td>
                                                <td>
                                                    <?php
                                                    echo $respuesta['respuesta'];
                                                    ?>         
                                                </td>
                                                <td style="width: 80px;">
                                                    <?php
                                                    if ($respuesta['sw_correcto'] == '1') {
                                                        echo '<span class="label label-success">CORRECTO</span>';
                                                    } else {
                                                        echo '<span class="text-danger">INCORRECTO</span>';
                                                    }
                                                    ?> 
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Modal - preguntas-onlinecourse -->
<div id="MODAL-preguntas-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PREGUNTAS DE EVALUACI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <p>
                    Ingrese a continuaci&oacute;n las preguntas de evaluaci&oacute;n del curso.
                </p>
                <hr/>
                <form action="" method="post">
                    <table style="width:100%;" class="table table-striped table-bordered table-hover">
                        <tr>
                            <td>
                                <i class="fa fa-tags"></i> &nbsp; Pregunta de evaluaci&oacute;n:
                                <br/>
                                <br/>
                                <textarea name="pregunta" class="form-control" placeholder="Ingresa la pregunta..." required="" style="height: 70px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px;">
                                <b>RESPUESTAS</b>
                                <div>
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-1" class="form-control" placeholder="Ingresa la respuesta..." required="" autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-1" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-2" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-2" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-3" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-3" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-4" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-4" value="1" class="form-control" autocomplete="off"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-5" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-5" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-6" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-6" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-7" class="form-control" placeholder="Ingresa la respuesta..." autocomplete="off"/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-7" value="1" class="form-control"/>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="agregar-pregunta-examen" value="AGREGAR PREGUNTA" class="btn btn-success btn-lg btn-animate-demo active"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal - preguntas-onlinecourse -->


<!--editar_pregunta-->
<script>
    function editar_pregunta(id_pregunta) {
        $("#TITLE-modgeneral").html('EDICI&Oacute;N DE PREGUNTA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.examenes-preguntas.editar_pregunta.php',
            data: {id_pregunta: id_pregunta},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
