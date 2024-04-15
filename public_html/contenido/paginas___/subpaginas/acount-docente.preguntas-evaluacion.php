<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_docente = docente('id');

/* verif usuario */
if (!isset_docente()) {
    echo "<br/><br/><br/>ACCESO DENEGADO";
    exit;
}

/* data */
$id_rel_cursoonlinecourse = $get[2];


$rqcu1 = query("SELECT o.*,(o.id)dr_id_onlinecourse,(c.titulo)dr_titulo_curso,(c.id)dr_id_curso,(r.id)dr_id_rel_cursoonlinecourse FROM cursos_onlinecourse o INNER JOIN cursos_rel_cursoonlinecourse r ON o.id=r.id_onlinecourse INNER JOIN cursos c ON c.id=r.id_curso WHERE r.id_docente='$id_docente' AND r.id='$id_rel_cursoonlinecourse' ");
$rqcu2 = fetch($rqcu1);

$nombre_curso = $rqcu2['dr_titulo_curso'];
$id_curso = $rqcu2['dr_id_curso'];
$id_onlinecourse = $rqcu2['dr_id_onlinecourse'];


/* agregar-pregunta-evaluacion */
if (isset_post('agregar-pregunta-evaluacion')) {
    $pregunta = post('pregunta');

    query("INSERT INTO cursos_onlinecourse_preguntas(id_onlinecourse,pregunta,estado) VALUES ('$id_onlinecourse','$pregunta','1')");
    $id_pregunta = insert_id();

    for ($i = 1; $i <= 7; $i++) {
        if (isset_post('respuesta-' . $i)) {
            $respuesta = post('respuesta-' . $i);
            if (isset_post('check-respuesta-' . $i)) {
                $sw_respuesta = '1';
            } else {
                $sw_respuesta = '0';
            }
            query("INSERT INTO cursos_onlinecourse_respuestas(id_pregunta, respuesta, sw_correcto) VALUES ('$id_pregunta','$respuesta','$sw_respuesta')");
        }
    }
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se completo correctamente.
    </div>';
    
    /* se habilita examen para el curso virtual */
    query("UPDATE cursos_onlinecourse SET sw_examen='1' WHERE id='$id_onlinecourse' ORDER BY id DESC limit 1 ");
}
/* editar-pregunta-evaluacion */
if (isset_post('editar-pregunta-evaluacion')) {
    $id_pregunta = post('id_pregunta');
    $pregunta = post('pregunta');
    $estado = post('estado');
    query("UPDATE cursos_onlinecourse_preguntas SET pregunta='$pregunta',estado='$estado' WHERE id='$id_pregunta' ORDER BY id DESC limit 1 ");
    query("DELETE FROM cursos_onlinecourse_respuestas WHERE id_pregunta='$id_pregunta' ");
    for ($i = 1; $i <= 7; $i++) {
        if (isset_post('respuesta-' . $i)) {
            $respuesta = post('respuesta-' . $i);
            if (isset_post('check-respuesta-' . $i)) {
                $sw_respuesta = '1';
            } else {
                $sw_respuesta = '0';
            }
            query("INSERT INTO cursos_onlinecourse_respuestas(id_pregunta, respuesta, sw_correcto) VALUES ('$id_pregunta','$respuesta','$sw_respuesta')");
        }
    }
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}

/* actualizar-num-preguntas */
if (isset_post('actualizar-num-preguntas')) {
    $id_rel_cursoonlinecourse;
    $num_preguntas = post('num_preguntas');
    $qrv1 = query("SELECT id FROM cursos_onlinecourse_examenes WHERE id_onlinecourse='$id_onlinecourse' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
    if (num_rows($qrv1) == 0) {
        query("INSERT INTO cursos_onlinecourse_examenes (id_onlinecourse, id_rel_cursoonlinecourse, num_preguntas) VALUES ('$id_onlinecourse','$id_rel_cursoonlinecourse','$num_preguntas')");
    } else {
        $qrv2 = fetch($qrv1);
        $id_evaluacion_virtual = $qrv2['id'];
        query("UPDATE cursos_onlinecourse_examenes SET num_preguntas='$num_preguntas' WHERE id='$id_evaluacion_virtual' ORDER BY id DESC limit 1 ");
    }
    $mensaje .= '<br><div class="alert alert-success">
      <strong>EXITO</strong> el registro se actualizo correctamente.
    </div>';
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_docente.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>PREGUNTAS DE EVALUACI&Oacute;N: <?php echo $nombre_curso; ?></h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        A continuaci&oacute;n se listan las preguntas asignadas para: <?php echo $nombre_curso; ?>.
                    </p>
                </div>


                <?php
                $rqmc1 = query("SELECT * FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' ");
                $num_preg = num_rows($rqmc1);
                if ($num_preg == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <div class="alert alert-info">
                            <strong>NOTA</strong> no se asignaron preguntas de evaluaci&oacute;n para este curso.
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th colspan="4">PREGUNTAS DE EVALUACI&Oacute;N</th>
                        </tr>
                        <?php
                        $cnt = 1;
                        while ($producto = fetch($rqmc1)) {
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt++; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['pregunta'];
                                    ?>         
                                </td>
                                <td>
                                    <?php
                                    if ($producto['estado'] == '1') {
                                        echo "<span class='label label-info'>Habilitado</label>";
                                    } else {
                                        echo "<span class='label label-default'>No Habilitado</label>";
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $rqdr1 = query("SELECT respuesta,sw_correcto FROM cursos_onlinecourse_respuestas WHERE id_pregunta='" . $producto['id'] . "' ");
                                    $num_resp = num_rows($rqdr1);
                                    ?>
                                    <b class="btn btn-xs btn-success btn-block" style="padding-top:2px;padding-bottom:2px;background: #d1ff88;color: green;" data-toggle="modal" data-target="#MODAL-view-resp-<?php echo $producto['id']; ?>"><?php echo $num_resp; ?> respuestas</b>
                                    <!-- Modal -->
                                    <div id="MODAL-view-resp-<?php echo $producto['id']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">RESPUESTAS</h4>
                                                </div>
                                                <div class="modal-body text-left">
                                                    <?php
                                                    while ($rqdr2 = fetch($rqdr1)) {
                                                        if ($rqdr2['sw_correcto'] == '1') {
                                                            echo "<label class='label label-success'>CORRECTO</label>";
                                                        } else {
                                                            echo "<label class='label label-default'>No correcto</label>";
                                                        }
                                                        echo ' | ' . $rqdr2['respuesta'] . '<br><br>';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <b class="btn btn-xs btn-success btn-block" style="padding-top:2px;padding-bottom:2px;background: #0cb5a5;" data-toggle="modal" data-target="#MODAL-editar_pregunta" onclick="editar_pregunta('<?php echo $producto['id']; ?>');"><i class="fa fa-edit"></i> EDITAR</b>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>


                <br>
                <hr>
                <div class="text-right">
                    <b class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-agregar-pregunta-evaluacion"><i class="fa fa-plus"></i> AGREGAR PREGUNTA DE EVALUACI&Oacute;N</b>
                </div>
                <hr>

                <?php
                if($num_preg>0){
                    $num_preguntas_habilitadas = $num_preg;
                    $qrv1 = query("SELECT num_preguntas FROM cursos_onlinecourse_examenes WHERE id_onlinecourse='$id_onlinecourse' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                    if (num_rows($qrv1) > 0) {
                        $qrv2 = fetch($qrv1);
                        $num_preguntas_habilitadas = $qrv2['num_preguntas'];
                    }
                    ?>
                    <form action="" method="post">
                        <table class='table table-striped table-bordered table-hover'>
                            <tr>
                                <td class="visible-lg">
                                    N&uacute;mero de <b>preguntas aleatorias</b> para evaluar:
                                    <br>
                                    Minimo: 1 &nbsp; | &nbsp; M&aacute;ximo: <?php echo $num_preg; ?>
                                </td>
                                <td class="visible-lg">
                                    <input type="number" required="" name="num_preguntas" min="1" max="<?php echo $num_preg; ?>" value="<?php echo $num_preguntas_habilitadas; ?>" class="form-control">       
                                </td>
                                <td class="visible-lg">
                                    <input type="submit" name="actualizar-num-preguntas" value="ACTUALIZAR" class="btn btn-info btn-sm"/>       
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                }
                ?>


                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <hr/>


                <br />
                <br />
                <br />
                <hr/>
            </div>

        </div>

    </section>
</div>                     



<script>
    function asistencia(id_participante) {
        $("#ajaxbox-" + id_participante).html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.acount-docente.participantes.asistencia.php',
            data: {id_participante: id_participante, id_rel_cursoonlinecourse: '<?php echo $id_rel_cursoonlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxbox-" + id_participante).html(data);
            }
        });
    }
</script>



<!-- Modal -->
<div id="MODAL-agregar-pregunta-evaluacion" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGAR PREGUNTA DE EVALUACI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <p>Ingresa los datos de la tarea a asignar a este curso en el siguiente formulario:</p>
                <br>
                <form action="" method="post">
                    <table class="table table-striped table-bordered table-hover">
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
                                                <input type="text" name="respuesta-1" class="form-control" placeholder="Ingresa la respuesta..." required=""/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-1" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-2" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-2" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-3" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-3" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-4" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-4" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-5" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-5" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-6" class="form-control" placeholder="Ingresa la respuesta..."/>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="check-respuesta-6" value="1" class="form-control"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="respuesta-7" class="form-control" placeholder="Ingresa la respuesta..."/>
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
                                    <input type="submit" name="agregar-pregunta-evaluacion" value="AGREGAR PREGUNTA" class="btn btn-success btn-lg btn-animate-demo"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>


<script>
    function editar_pregunta(id_pregunta) {
        $("#AJAXCONTENT-editar_pregunta").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.acount-docente.preguntas-evaluacion.editar_pregunta.php',
            data: {id_pregunta: id_pregunta},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-editar_pregunta").html(data);
            }
        });
    }
</script>

<!-- Modal -->
<div id="MODAL-editar_pregunta" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE PREGUNTA DE EVALUACI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-editar_pregunta"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>