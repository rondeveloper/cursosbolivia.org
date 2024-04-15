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


/* agregar-tarea */
if(isset_post('agregar-tarea')){
    $tarea = post('tarea');
    $descripcion = post('descripcion');
    $estado = post('estado');
    query("INSERT INTO cursos_onlinecourse_tareas (id_onlinecourse,id_docente,tarea,descripcion,estado) VALUES ('$id_onlinecourse','$id_docente','$tarea','$descripcion','$estado') ");
    $mensaje = '<br><div class="alert alert-success">
<strong>EXITO</strong> la tarea fue agregada correctamente.
</div>';
}

/* editar-tarea */
if(isset_post('editar-tarea')){
    $id_tarea = post('id_tarea');
    $tarea = post('tarea');
    $descripcion = post('descripcion');
    $estado = post('estado');
    query("UPDATE cursos_onlinecourse_tareas SET tarea='$tarea',descripcion='$descripcion',estado='$estado' WHERE id='$id_tarea' ORDER BY id DESC limit 1 ");
    $mensaje = '<br><div class="alert alert-success">
<strong>EXITO</strong> la tarea fue actualizada correctamente.
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
                    <h3>TAREAS: <?php echo $nombre_curso; ?></h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        A continuaci&oacute;n se listan las tareas asignadas para: <?php echo $nombre_curso; ?>.
                    </p>
                </div>
                <?php
                $cnt = 0;
                $rqp1 = query("SELECT * FROM cursos_onlinecourse_tareas WHERE id_onlinecourse='$id_onlinecourse' ");
                if (num_rows($rqp1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <div class="alert alert-info">
                            <strong>NOTA</strong> no se asignaron tareas para este curso.
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="text-center" style="display: none;">
                        C&oacute;digo de asistencia para hoy:
                        <br>
                        <br>
                        <b style="font-size: 25pt;
                           color: #fff;
                           background-color: #31d59f;
                           border: 1px solid #3075ce;
                           padding: 5px 20px;
                           margin-top: 20px;"><?php echo strtoupper(substr(md5(date("Y-m-d H") . '32461'), 12, 4)); ?></b>
                        <br>
                        <br>
                    </div>

                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th>#</th>
                            <th>Tarea</th>
                            <th>Descripci&oacute;n</th>
                            <th>Estado</th>
                            <th class="text-center"> Env&iacute;os </th>
                            <th>Acciones</th>
                        </tr>
                        <?php
                        while ($rqp2 = fetch($rqp1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo ++$cnt; ?>
                                </td>
                                <td>
                                    <?php echo $rqp2['tarea']; ?>
                                </td>
                                <td>
                                    <?php echo $rqp2['descripcion']; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($rqp2['estado'] == '1') {
                                        echo "<span class='label label-info'>Habilitado</label>";
                                    } else {
                                        echo "<span class='label label-default'>No Habilitado</label>";
                                    }
                                    ?>
                                </td>
                                <td class="text-center" id="ajaxbox-<?php echo $rqp2['id']; ?>">
                                    <?php
                                    $rqva1 = query("SELECT id FROM cursos_onlinecourse_tareasenvios WHERE id_tarea='".$rqp2['id']."' ");
                                    if (num_rows($rqva1) == 0) {
                                        ?>
                                        0 env&iacute;os
                                        <?php
                                    } else {
                                        ?>
                                        <?php echo num_rows($rqva1); ?> env&iacute;os
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-center" id="ajaxbox-<?php echo $rqp2['id']; ?>">
                                    <b class="btn btn-xs btn-success btn-block" style="padding-top:2px;padding-bottom:2px;background: #0cb5a5;background: #fdf08a;color: green;" data-toggle="modal" data-target="#MODAL-envios_tarea" onclick="envios_tarea('<?php echo $rqp2['id']; ?>');"><i class="fa fa-eye"></i> Ver envios</b>
                                    <br>
                                    <b class="btn btn-xs btn-success btn-block" style="padding-top:2px;padding-bottom:2px;background: #0cb5a5;" data-toggle="modal" data-target="#MODAL-editar-tarea-<?php echo $rqp2['id']; ?>"><i class="fa fa-edit"></i> Editar tarea</b>
                                    
                                    
                                    
                                    
                                    
                                    <!-- Modal edicion -->
                                    <div id="MODAL-editar-tarea-<?php echo $rqp2['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">EDITAR TAREA</h4>
                                          </div>
                                          <div class="modal-body">
                                            <form action="" method="post">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><b>Tarea:</b></td>
                                                        <td><input type="text" name="tarea" required="" class="form-control" value="<?php echo $rqp2['tarea']; ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Descripci&oacute;n:</b></td>
                                                        <td><textarea name="descripcion" required="" class="form-control" style="height: 120px;"><?php echo $rqp2['descripcion']; ?></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Estado:</b></td>
                                                        <td class="text-center">
                                                            <?php
                                                            $htm_check_a = '';
                                                            $htm_check_b = ' checked="" ';
                                                            if ($rqp2['estado'] == '1') {
                                                                $htm_check_a = ' checked="" ';
                                                                $htm_check_b = '';
                                                            }
                                                            ?>
                                                            <input type="radio" name="estado" value="1" style="width: auto;" <?php echo $htm_check_a; ?>> Habilitado
                                                            &nbsp;&nbsp;|&nbsp;&nbsp;
                                                            <input type="radio" name="estado" value="0" style="width: auto;" <?php echo $htm_check_b; ?>> No Habilitado
                                                            <br>
                                                            &nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <br>
                                                            <input type="hidden" name="id_tarea" value="<?php echo $rqp2['id']; ?>"/>
                                                            <input type="submit" name="editar-tarea" class="btn btn-info btn-block" value="ACTUALIZAR TAREA"/>
                                                            <br>
                                                            &nbsp;
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
                    <b class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-agregar-tarea"><i class="fa fa-plus"></i> AGREGAR TAREA</b>
                </div>
                

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
<div id="MODAL-agregar-tarea" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">AGREGAR TAREA</h4>
      </div>
      <div class="modal-body">
        <p>Ingresa los datos de la tarea a asignar a este curso en el siguiente formulario:</p>
        <form action="" method="post">
        <table class="table table-striped table-bordered">
            <tr>
                <td><b>Tarea:</b></td>
                <td><input type="text" name="tarea" required="" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Descripci&oacute;n:</b></td>
                <td><textarea name="descripcion" required="" class="form-control" style="height: 120px;"></textarea></td>
            </tr>
            <tr>
                <td><b>Estado:</b></td>
                <td class="text-center">
                    <input type="radio" name="estado" value="1" style="width: auto;" checked=""> Habilitado
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <input type="radio" name="estado" value="0" style="width: auto;"> No Habilitado
                    <br>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <input type="submit" name="agregar-tarea" class="btn btn-success btn-block" value="+ ASIGNAR TAREA"/>
                    <br>
                    &nbsp;
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
    function envios_tarea(id_tarea) {
        $("#AJAXCONTENT-envios_tarea").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.acount-docente.tareas.envios_tarea.php',
            data: {id_tarea: id_tarea, id_rel_cursoonlinecourse: '<?php echo $id_rel_cursoonlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-envios_tarea").html(data);
            }
        });
    }
</script>

<!-- Modal -->
<div id="MODAL-envios_tarea" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ENVIOS DE TAREA</h4>
      </div>
      <div class="modal-body">
          <p>A continuaci&oacute;n se lista los envios de los participantes para la tarea solicitada:</p>
          <div id="AJAXCONTENT-envios_tarea"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>