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
                    <h3>CURSOS ASIGNADOS</h3>
                </div>
                <?php
                $rqcu1 = query("SELECT o.*,(c.titulo)dr_titulo_curso,(o.id)dr_id_onlinecourse,(c.id)dr_id_curso,(r.id)dr_id_rel_cursoonlinecourse FROM cursos_onlinecourse o INNER JOIN cursos_rel_cursoonlinecourse r ON o.id=r.id_onlinecourse INNER JOIN cursos c ON c.id=r.id_curso WHERE r.id_docente='$id_docente' ");
                if (num_rows($rqcu1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            No se te asign&oacute; como docente a ning&uacute;n curso.
                        </p>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan los cursos a los cuales se te asigno como docente.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered table-hover table-responsive'>
                        <tr>
                            <th>Curso</th>
                            <th class="text-center">Participantes</th>
                            <th class="text-center">Tareas</th>
                            <th class="text-center">Evaluaci&oacute;n</th>
                            <th class="text-center">Notas</th>
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcu1)) {
                            $rqdcp1 = query("SELECT count(*) AS total FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id WHERE p.id_curso='".$rqcu2['dr_id_curso']."' AND p.estado='1' AND r.sw_pago_enviado='1' ");
                            $rqdcp2 = fetch($rqdcp1);
                            $cnt_participantes = $rqdcp2['total'];
                            
                            $rqdcpt1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_tareas WHERE id_onlinecourse='".$rqcu2['dr_id_onlinecourse']."' ");
                            $rqdcpt2 = fetch($rqdcpt1);
                            $cnt_tareas = $rqdcpt2['total'];
                            
                            $rqdcpte1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='".$rqcu2['dr_id_onlinecourse']."' ");
                            $rqdcpte2 = fetch($rqdcpte1);
                            $cnt_preg_examen = $rqdcpte2['total'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['dr_titulo_curso']; ?>
                                    <br>
                                    <b>[C-<?php echo $rqcu2['dr_id_curso']; ?>]</b> 
                                    <?php
                                    if ($rqcu2['estado'] == '1') {
                                        echo "<label class='label label-info'>Activo</label>";
                                    } else {
                                        echo "<label class='label label-default'>No activo</label>";
                                    }
                                    ?>
                                    <br>
                                    <i>C-virtual:</i> <?php echo $rqcu2['titulo']; ?>  
                                    <a href="curso-online/<?php echo $rqcu2['urltag']; ?>.html" class="btn btn-success btn-xs pull-right" target="_blank" style="padding: 1px 10px;"><i class="fa fa-eye"></i> Ver</a>
                                </td>
                                <td class="text-center">
                                    <?php echo $cnt_participantes; ?> participantes
                                    <br>
                                    <br>
                                    <a href="acount-docente.participantes/<?php echo $rqcu2['dr_id_rel_cursoonlinecourse']; ?>.html" class="btn btn-info btn-xs" style="background: #FF9800;padding: 2px 10px;border-color: #a0a0a0;"><i class="fa fa-list"></i> Listar participantes</a>
                                </td>
                                <td class="text-center">
                                    <?php echo $cnt_tareas; ?> tareas
                                    <br>
                                    <br>
                                    <a href="acount-docente.tareas/<?php echo $rqcu2['dr_id_rel_cursoonlinecourse']; ?>.html" class="btn btn-info btn-xs" style="background: #2c81ee;padding: 2px 10px;border-color: #a0a0a0;"><i class="fa fa-copy"></i> Ver tareas</a>
                                </td>
                                <td class="text-center">
                                    <?php echo $cnt_preg_examen; ?> preguntas
                                    <br>
                                    <br>
                                    <a href="acount-docente.preguntas-evaluacion/<?php echo $rqcu2['dr_id_rel_cursoonlinecourse']; ?>.html" class="btn btn-info btn-xs" style="background: #b1b1b1;padding: 2px 10px;border-color: #688ce0;"><i class="fa fa-laptop"></i> Configurar</a>
                                </td>
                                <td class="text-center">
                                    Calificaciones
                                    <br>
                                    <br>
                                    <a href="acount-docente.notas/<?php echo $rqcu2['dr_id_rel_cursoonlinecourse']; ?>.html" class="btn btn-info btn-xs" style="background: #88ca6d;padding: 2px 10px;border-color: #688ce0;"><i class="fa fa-check"></i> Ver notas</a>
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
