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
?>

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
                    <h3>PARTICIPANTES: <?php echo $nombre_curso; ?></h3>
                </div>
                <?php
                $cnt = 0;
                $rqp1 = query("SELECT p.* FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id WHERE p.id_curso='$id_curso' AND p.estado='1' AND r.sw_pago_enviado='1' ORDER BY p.nombres ASC,p.apellidos ASC ");
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
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan los participantes registrados en: <?php echo $nombre_curso; ?>.
                        </p>
                    </div>
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
                            <th class="th-order">#</th>
                            <th class="th-order">Nombres</th>
                            <th class="th-order">Apellidos</th>
                            <th class="th-order">Estado</th>
                            <th class="th-order text-center">
                                Asistencia | <?php echo date("d M"); ?>
                            </th>
                        </tr>
                        <?php
                        while ($rqp2 = fetch($rqp1)) {
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
                                <td>
                                    <?php
                                    if ($rqp2['estado'] == '1') {
                                        echo "<span class='label label-info'>Activado</label>";
                                    } else {
                                        echo "<span class='label label-default'>Desactivado</label>";
                                    }
                                    ?>
                                </td>
                                <td class="text-center" id="ajaxbox-<?php echo $rqp2['id']; ?>">
                                    <?php
                                    $rqva1 = query("SELECT id FROM cursos_onlinecourse_asistencia WHERE fecha=CURDATE() AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' AND id_participante='" . $rqp2['id'] . "' ");
                                    if (num_rows($rqva1) == 0) {
                                        ?>
                                        <b class="btn btn-warning" onclick="asistencia('<?php echo $rqp2['id']; ?>');">NO &nbsp;&nbsp; <i class="fa fa-refresh"></i></b>
                                        <?php
                                    } else {
                                        ?>
                                        <b class="btn btn-success" onclick="asistencia('<?php echo $rqp2['id']; ?>');">SI &nbsp;&nbsp; <i class="fa fa-refresh"></i></b>
                                            <?php
                                        }
                                        ?>
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
    function asistencia(id_participante) {
        $("#ajaxbox-" + id_participante).html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.acount-docente.participantes.asistencia.php',
            data: {id_participante: id_participante, id_rel_cursoonlinecourse: '<?php echo $id_rel_cursoonlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxbox-" + id_participante).html(data);
            }
        });
    }
</script>


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
$('.th-order').click(function(){
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
})
function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
</script>
