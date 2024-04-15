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


$rqcu1 = query("SELECT o.*,(c.titulo)dr_titulo_curso,(c.id)dr_id_curso,(r.id)dr_id_rel_cursoonlinecourse FROM cursos_onlinecourse o INNER JOIN cursos_rel_cursoonlinecourse r ON o.id=r.id_onlinecourse INNER JOIN cursos c ON c.id=r.id_curso WHERE r.id_docente='$id_docente' AND r.id='$id_rel_cursoonlinecourse' ");
$rqcu2 = fetch($rqcu1);

$nombre_curso = $rqcu2['dr_titulo_curso'];
$id_curso = $rqcu2['dr_id_curso'];


/* actualizar-tablero-notas */
if (isset_post('actualizar-tablero-notas')) {

    $rqvns1 = query("SELECT id FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='ASISTENCIA' ");
    $rqvnsi12 = fetch($rqvns1);
    $id_nota_asistencia = $rqvnsi12['id'];
    $rqvnsb1 = query("SELECT id FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='TAREAS' ");
    $rqvnsi14 = fetch($rqvnsb1);
    $id_nota_tareas = $rqvnsi14['id'];
    $rqvnsc1 = query("SELECT id FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='EXAMEN VIRTUAL' ");
    $rqvnsi17 = fetch($rqvnsc1);
    $id_nota_examenvirtual = $rqvnsi17['id'];

    $estado_asistencia = post('estado_asistencia');
    $porcentaje_asistencia = post('porcentaje_asistencia');
    $estado_tareas = post('estado_tareas');
    $porcentaje_tareas = post('porcentaje_tareas');
    $estado_examenvirtual = post('estado_examenvirtual');
    $porcentaje_examenvirtual = post('porcentaje_examenvirtual');

    query("UPDATE cursos_rel_cursoonlinecoursenotas SET porcentaje='$porcentaje_asistencia',estado='$estado_asistencia' WHERE id='$id_nota_asistencia' ORDER BY id DESC limit 1 ");
    query("UPDATE cursos_rel_cursoonlinecoursenotas SET porcentaje='$porcentaje_tareas',estado='$estado_tareas' WHERE id='$id_nota_tareas' ORDER BY id DESC limit 1 ");
    query("UPDATE cursos_rel_cursoonlinecoursenotas SET porcentaje='$porcentaje_examenvirtual',estado='$estado_examenvirtual' WHERE id='$id_nota_examenvirtual' ORDER BY id DESC limit 1 ");

    $notasadicionales = post('notasadicionales');
    for ($i = 1; $i <= $notasadicionales; $i++) {
        $id_nota = post('id_nota_adicional_' . $i);
        $nombre_nota = post('nombre_nota_adicional_' . $i);
        $porcentaje_nota = post('porcentaje_nota_adicional_' . $i);
        $estado_nota = post('estado_nota_adicional_' . $i);
        query("UPDATE cursos_rel_cursoonlinecoursenotas SET nombre='$nombre_nota',porcentaje='$porcentaje_nota',estado='$estado_nota' WHERE id='$id_nota' ORDER BY id DESC limit 1 ");
    }

    $mensaje = '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro fue modificado correctamente.
</div>';
}

/* notas sistematicas : ASISTENCIA */
$rqvns1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='ASISTENCIA' ");
if (num_rows($rqvns1) == 0) {
    $porcentaje_nota_asistencia = 30;
    $estado_nota_asistencia = 1;
    query("INSERT INTO cursos_rel_cursoonlinecoursenotas (id_rel_cursoonlinecourse,sw_sist,nombre,porcentaje,estado) VALUES ('$id_rel_cursoonlinecourse','1','ASISTENCIA','$porcentaje_nota_asistencia','$estado_nota_asistencia') ");
} else {
    $rqvns2 = fetch($rqvns1);
    $porcentaje_nota_asistencia = $rqvns2['porcentaje'];
    $estado_nota_asistencia = $rqvns2['estado'];
}
/* notas sistematicas : TAREAS */
$rqvnsb1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='TAREAS' ");
if (num_rows($rqvnsb1) == 0) {
    $porcentaje_nota_tareas = 30;
    $estado_nota_tareas = 1;
    query("INSERT INTO cursos_rel_cursoonlinecoursenotas (id_rel_cursoonlinecourse,sw_sist,nombre,porcentaje,estado) VALUES ('$id_rel_cursoonlinecourse','1','TAREAS','$porcentaje_nota_tareas','$estado_nota_tareas') ");
} else {
    $rqvns2 = fetch($rqvnsb1);
    $porcentaje_nota_tareas = $rqvns2['porcentaje'];
    $estado_nota_tareas = $rqvns2['estado'];
}
/* notas sistematicas : EXAMEN VIRTUAL */
$rqvnsc1 = query("SELECT porcentaje,estado FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='1' AND nombre='EXAMEN VIRTUAL' ");
if (num_rows($rqvnsc1) == 0) {
    $porcentaje_nota_examenvirtual = 40;
    $estado_nota_examenvirtual = 1;
    query("INSERT INTO cursos_rel_cursoonlinecoursenotas (id_rel_cursoonlinecourse,sw_sist,nombre,porcentaje,estado) VALUES ('$id_rel_cursoonlinecourse','1','EXAMEN VIRTUAL','$porcentaje_nota_examenvirtual','$estado_nota_examenvirtual') ");
} else {
    $rqvns2 = fetch($rqvnsc1);
    $porcentaje_nota_examenvirtual = $rqvns2['porcentaje'];
    $estado_nota_examenvirtual = $rqvns2['estado'];
}


/* agregar-nota */
if (isset_post('agregar-nota')) {
    $nombre_nota = post('nombre_nota');
    $estado_nota = post('estado_nota');
    $porcentaje_nota = post('porcentaje_nota');
    query("INSERT INTO cursos_rel_cursoonlinecoursenotas (id_rel_cursoonlinecourse,sw_sist,nombre,porcentaje,estado) VALUES ('$id_rel_cursoonlinecourse','0','$nombre_nota','$porcentaje_nota','$estado_nota') ");
    $mensaje = '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro fue agregado correctamente.
</div>';
}
?>
<style>
    .divbox-notas{
        background-color: #e3f7d9;
        border: 1px solid #3bdc78;
        padding: 20px 20px;
        border-radius: 10px;
    }
    .divbox-notas table{
        background: #FFF;
    }
    .divbox-notas td{
        padding: 15px 7px !important;
    }
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_docente.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>NOTAS / CALIFICACIONES: <?php echo $nombre_curso; ?></h3>
                </div>

                <div class="Titulo_texto1">
                    <p>
                        A continuaci&oacute;n se listan las notas asignadas a: <?php echo $nombre_curso; ?>.
                    </p>
                </div>

                <div class="divbox-notas">
                    <form action="" method="post">
                        <table class='table table-striped table-bordered table-hover'>
                            <tr>
                                <th>#</th>
                                <th>Nota</th>
                                <th>Estado</th>
                                <th>Porcentaje</th>
                            </tr>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td>
                                    <span class='label label-primary'>SIST</span>
                                    &nbsp;
                                    ASISTENCIA 
                                </td>
                                <td>
                                    <select class="form-control" name="estado_asistencia">
                                        <?php
                                        $htm_select1 = ' selected="selected" ';
                                        $htm_select2 = '';
                                        if ($estado_nota_asistencia == '0') {
                                            $htm_select1 = '';
                                            $htm_select2 = ' selected="selected" ';
                                            $porcentaje_nota_asistencia = 0;
                                        }
                                        ?>
                                        <option value="1" <?php echo $htm_select1; ?>>Habilitado</option>
                                        <option value="0" <?php echo $htm_select2; ?>>Des-habilitado</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" style="width: 80px;float: left;margin: 0px;height: 25px;" name="porcentaje_asistencia" value="<?php echo $porcentaje_nota_asistencia; ?>"/> &nbsp; <b style="font-size:14pt;">%</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2
                                </td>
                                <td>
                                    <span class='label label-primary'>SIST</span>
                                    &nbsp;
                                    TAREAS 
                                </td>
                                <td>
                                    <select class="form-control" name="estado_tareas">
                                        <?php
                                        $htm_select1 = ' selected="selected" ';
                                        $htm_select2 = '';
                                        if ($estado_nota_tareas == '0') {
                                            $htm_select1 = '';
                                            $htm_select2 = ' selected="selected" ';
                                            $porcentaje_nota_tareas = 0;
                                        }
                                        ?>
                                        <option value="1" <?php echo $htm_select1; ?>>Habilitado</option>
                                        <option value="0" <?php echo $htm_select2; ?>>Des-habilitado</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" style="width: 80px;float: left;margin: 0px;height: 25px;" name="porcentaje_tareas" value="<?php echo $porcentaje_nota_tareas; ?>"/> &nbsp; <b style="font-size:14pt;">%</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    3
                                </td>
                                <td>
                                    <span class='label label-primary'>SIST</span>
                                    &nbsp;
                                    EXAMEN VIRTUAL 
                                </td>
                                <td>
                                    <select class="form-control" name="estado_examenvirtual">
                                        <?php
                                        $htm_select1 = ' selected="selected" ';
                                        $htm_select2 = '';
                                        if ($estado_nota_examenvirtual == '0') {
                                            $htm_select1 = '';
                                            $htm_select2 = ' selected="selected" ';
                                            $porcentaje_nota_examenvirtual = 0;
                                        }
                                        ?>
                                        <option value="1" <?php echo $htm_select1; ?>>Habilitado</option>
                                        <option value="0" <?php echo $htm_select2; ?>>Des-habilitado</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" style="width: 80px;float: left;margin: 0px;height: 25px;" name="porcentaje_examenvirtual" value="<?php echo $porcentaje_nota_examenvirtual; ?>"/> &nbsp; <b style="font-size:14pt;">%</b>
                                </td>
                            </tr>
                            <?php
                            /* porcentaje total */
                            $porcentaje_total = $porcentaje_nota_asistencia + $porcentaje_nota_tareas + $porcentaje_nota_examenvirtual;

                            /* notas adicionales */
                            $array_notas_adicionales = array();
                            $rqvnna1 = query("SELECT * FROM cursos_rel_cursoonlinecoursenotas WHERE sw_sist='0' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ORDER BY id ASC ");
                            $cnt_notasadicionales = 0;
                            while ($rqvnna2 = fetch($rqvnna1)) {
                                $cnt_notasadicionales++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $cnt_notasadicionales + 3; ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo $rqvnna2['nombre']; ?>" name="nombre_nota_adicional_<?php echo $cnt_notasadicionales; ?>"/>
                                    </td>
                                    <td>
                                        <select class="form-control" name="estado_nota_adicional_<?php echo $cnt_notasadicionales; ?>">
                                            <?php
                                            $htm_select1 = '';
                                            $htm_select2 = ' selected="selected" ';
                                            $porcentaje_nota_adicional = 0;
                                            if ($rqvnna2['estado'] == '1') {
                                                $porcentaje_total += $rqvnna2['porcentaje'];
                                                $htm_select1 = ' selected="selected" ';
                                                $htm_select2 = '';
                                                $porcentaje_nota_adicional = $rqvnna2['porcentaje'];
                                                array_push($array_notas_adicionales, array('nombre' => $rqvnna2['nombre'], 'id' => $rqvnna2['id'], 'porcentaje' => $rqvnna2['porcentaje']));
                                            }
                                            ?>
                                            <option value="1" <?php echo $htm_select1; ?>>Habilitado</option>
                                            <option value="0" <?php echo $htm_select2; ?>>Des-habilitado</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?php echo $rqvnna2['id']; ?>" name="id_nota_adicional_<?php echo $cnt_notasadicionales; ?>"/>
                                        <input type="number" class="form-control" style="width: 80px;float: left;margin: 0px;height: 25px;" name="porcentaje_nota_adicional_<?php echo $cnt_notasadicionales; ?>" value="<?php echo $porcentaje_nota_adicional; ?>"/> &nbsp; <b style="font-size:14pt;">%</b>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <b class="btn btn-info btn-xs" style="background: skyblue;padding: 2px 10px;border-color: #688ce0;" data-toggle="modal" data-target="#MODAL-agregar_nota">
                                        <i class="fa fa-plus"></i> Agregar nota adicional
                                    </b>
                                </td>                            
                                <td>
                                    <b><?php echo $porcentaje_total; ?> %</b>
                                    <?php
                                    $sw_configuracion_correcta = true;
                                    if ($porcentaje_total != 100) {
                                        $sw_configuracion_correcta = false;
                                        echo "<br><b class='text-danger'>ERROR<br>la suma de porcentajes debe ser 100</b>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <input type="hidden" value="<?php echo $cnt_notasadicionales; ?>" name="notasadicionales"/>
                                    <input type="submit" name="actualizar-tablero-notas" value="ACTUALIZAR TABLERO DE NOTAS" class="btn btn-block btn-sm btn-success"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <br>
                <hr>
                <br>

                <?php
                /* total asistencias a verificar */
                $rqdcn1 = query("SELECT DISTINCT fecha FROM cursos_onlinecourse_asistencia WHERE id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                $cnt_asistencias = num_rows($rqdcn1);

                /* total tareas asignadas */
                $rqdcnt1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_tareas t INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=t.id_onlinecourse WHERE r.id='$id_rel_cursoonlinecourse' ");
                $rqdcnt2 = fetch($rqdcnt1);
                $cnt_tareas = $rqdcnt2['total'];
                
                $cnt = 0;
                $rqp1 = query("SELECT p.* FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id WHERE p.id_curso='$id_curso' AND p.estado='1' AND r.sw_pago_enviado='1' ORDER BY p.apellidos ASC,p.nombres ASC ");
                if (num_rows($rqp1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            No se registraron participantes para este curso.
                        </p>
                    </div>
                    <?php
                } else {
                    ?>
                    <table class='table table-striped table-bordered table-hover'>
                        <tr>
                            <th class="th-order">#</th>
                            <th class="th-order">Nombres</th>
                            <th class="th-order">Apellidos</th>
                            <?php
                            foreach ($array_notas_adicionales as $notaadicional) {
                                ?>
                                <th class="th-order">
                                    <?php echo $notaadicional['nombre']; ?>
                                    <br>
                                    <b><?php echo $notaadicional['porcentaje']; ?> %</b>
                                </th>
                                <?php
                            }
                            ?>
                            <?php if($porcentaje_nota_asistencia>0){ ?>
                            <th class="th-order">
                                Asistencia
                                <br>
                                <b><?php echo $porcentaje_nota_asistencia; ?> %</b>
                            </th>
                            <?php } ?>
                            <?php if($porcentaje_nota_tareas>0){ ?>
                            <th class="th-order">
                                Tareas
                                <br>
                                <b><?php echo $porcentaje_nota_tareas; ?> %</b>
                            </th>
                            <?php } ?>
                            <?php if($porcentaje_nota_examenvirtual>0){ ?>
                            <th class="th-order">
                                Ex. virtual
                                <br>
                                <b><?php echo $porcentaje_nota_examenvirtual; ?> %</b>
                            </th>
                            <?php } ?>
                            <th class="th-order">Nota final</th>
                        </tr>
                        <?php
                        while ($rqp2 = fetch($rqp1)) {
                            /* nota asistencia */
                            $rqdac1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_asistencia WHERE id_participante='" . $rqp2['id'] . "' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                            $rqdac2 = fetch($rqdac1);
                            $nota_asistencia = ceil($rqdac2['total'] * 100 / $cnt_asistencias);

                            /* nota tareas */
                            $rqdacet1 = query("SELECT sum(e.calificacion) AS total FROM cursos_onlinecourse_tareasenvios e INNER JOIN cursos_onlinecourse_tareas t ON t.id=e.id_tarea INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=t.id_onlinecourse WHERE e.id_usuario='" . $rqp2['id_usuario'] . "' AND r.id='$id_rel_cursoonlinecourse' LIMIT $cnt_tareas ");
                            $rqdacet2 = fetch($rqdacet1);
                            $nota_tareas = ceil($rqdacet2['total'] / $cnt_tareas);

                            /* nota examen */
                            $rqdacev1 = query("SELECT e.total_correctas,e.total_preguntas FROM cursos_onlinecourse_evaluaciones e INNER JOIN cursos_rel_cursoonlinecourse r ON e.id_onlinecourse=r.id_onlinecourse WHERE e.id_usuario='" . $rqp2['id_usuario'] . "' AND r.id='$id_rel_cursoonlinecourse' ORDER BY e.id DESC limit 1  ");
                            $nota_examen = 0;
                            if (num_rows($rqdacev1) > 0) {
                                $rqdacev2 = fetch($rqdacev1);
                                $nota_examen = ceil($rqdacev2['total_correctas'] * 100 / $rqdacev2['total_preguntas']);
                            }

                            /* nota final */  
                            $nota_final = 0;
                            if($porcentaje_nota_asistencia>0){
                                $nota_final += ($nota_asistencia * $porcentaje_nota_asistencia / 100);
                            }
                            if($porcentaje_nota_tareas>0){
                                $nota_final += ($nota_tareas * $porcentaje_nota_tareas / 100);
                            }
                            if($porcentaje_nota_examenvirtual>0){
                                $nota_final += ($nota_examen * $porcentaje_nota_examenvirtual / 100);
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo ++$cnt; ?>
                                </td>
                                <td>
                                    <?php echo $rqp2['nombres']; ?>
                                </td>
                                <td>
                                    <?php echo $rqp2['apellidos']; ?>
                                </td>
                                <?php
                                foreach ($array_notas_adicionales as $notaadicional) {
                                    $rqdnad1 = query("SELECT calificacion FROM rel_notacalificacion WHERE id_rel_cursoonlinecoursenotas='".$notaadicional['id']."' AND id_participante='".$rqp2['id']."' ORDER BY id DESC limit 1 ");
                                    if(num_rows($rqdnad1)>0){
                                        $rqdnad2 = fetch($rqdnad1);
                                        $calificacion = $rqdnad2['calificacion'];
                                    }else{
                                        $calificacion = 0;
                                    }
                                    ?>
                                    <td>
                                        <input type="number" class="form-control" value="<?php echo $calificacion; ?>" style="width: 70px;margin-bottom: 5px;float: left;" onkeyup="actualizar_nota(this.value,'<?php echo $notaadicional['id']; ?>','<?php echo $rqp2['id']; ?>');" onchange="actualizar_nota(this.value,'<?php echo $notaadicional['id']; ?>','<?php echo $rqp2['id']; ?>');" min="0" max="100" maxlength="3"/>
                                        &nbsp;
                                        <span id="not-<?php echo $rqp2['id']; ?>"></span>
                                    </td>
                                    <?php
                                    if($notaadicional['porcentaje']>0){
                                        $nota_final += ($calificacion * $notaadicional['porcentaje'] / 100);
                                    }
                                }
                                ?>
                                <?php if($porcentaje_nota_asistencia>0){ ?>
                                <td>
                                    <?php echo $nota_asistencia; ?>
                                </td>
                                <?php } ?>
                                <?php if($porcentaje_nota_tareas>0){ ?>
                                <td>
                                    <?php echo $nota_tareas; ?>
                                </td>
                                <?php } ?>
                                <?php if($porcentaje_nota_examenvirtual>0){ ?>
                                <td>
                                    <?php echo $nota_examen; ?>
                                </td>
                                <?php } ?>
                                <td id="notf-<?php echo $rqp2['id']; ?>">
                                    <?php echo round($nota_final,2); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
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
    function showupdate_nota(id_participante){
        $("#notf-"+id_participante).html('...');
        $.ajax({
            url: 'pages/ajax/ajax.acount-docente.notas.showupdate_nota.php',
            data: {id_rel_cursoonlinecourse: '<?php echo $id_rel_cursoonlinecourse?>', id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#notf-"+id_participante).html(data);
            }
        });
    }
</script>

<script>
    function actualizar_nota(calificacion,id_rel_cursoonlinecoursenotas,id_participante){
        $("#not-"+id_participante).html('...');
        $.ajax({
            url: 'pages/ajax/ajax.acount-docente.notas.actualizar_nota.php',
            data: {calificacion: calificacion, id_rel_cursoonlinecoursenotas: id_rel_cursoonlinecoursenotas, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#not-"+id_participante).html(data);
                showupdate_nota(id_participante);
            }
        });
    }
</script>
<!-- Modal -->
<div id="MODAL-agregar_nota" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGAR NUEVA NOTA</h4>
            </div>
            <div class="modal-body">
                <p>Ingresa en el formulario el nombre de la nota adicional y el porcentaje de evaluacion a conciderar:</p>
                <br>
                <form action="" method="post">
                    <table class='table table-striped table-bordered table-hover'>

                        <tr>
                            <td>
                                <b>Nota:</b>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="nombre_nota" placeholder="Ingrese el nombre de la nota..." required=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Estado:</b>
                            </td>
                            <td>
                                <select class="form-control" name="estado_nota">
                                    <option value="1">Habilitado</option>
                                    <option value="0">Des-habilitado</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Porcentaje:</b>
                            </td>
                            <td>
                                <input type="number" class="form-control" style="width: 80px;float: left;margin: 0px;height: 25px;" name="porcentaje_nota" value="0" required=""/> &nbsp; <b style="font-size:14pt;">%</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br>
                                <input type="submit" name="agregar-nota" value="AGREGAR NOTA" class="btn btn-block btn-sm btn-success"/>
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



<!--Ordet table-->
<style>
    .th-order{
        cursor: pointer;
    }
    .th-order:hover{
        background: #dffbf3;
    }
</style>
<script>
    $('.th-order').click(function () {
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc) {
            rows = rows.reverse()
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i])
        }
    })
    function comparer(index) {
        return function (a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index)
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
        }
    }
    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text()
    }
</script>
