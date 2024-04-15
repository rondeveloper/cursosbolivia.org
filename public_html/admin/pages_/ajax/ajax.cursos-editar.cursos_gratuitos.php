<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* quitar */
if (isset_post('id_curso_free')) {
    $id_curso_free = post('id_curso_free');
    query("UPDATE cursos_rel_cursofreecur SET estado='0' WHERE id_curso_free='$id_curso_free' AND id_curso='$id_curso' ");
    logcursos('Quitado de curso gratuito asociado [id:' . $id_curso_free . ']', 'curso-edicion', 'curso', $id_curso);
    logcursos('Quitado como gratuito asociado de [id:' . $id_curso . ']', 'curso-edicion', 'curso', $id_curso_free);
    echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro se modifico correctamente.
</div>';
}

$rqc1 = query("SELECT id,titulo,estado FROM cursos WHERE id IN (select id_curso_free from cursos_rel_cursofreecur where id_curso='$id_curso' and estado='1') ");
if (num_rows($rqc1) == 0) {
    echo '<div class="alert alert-warning">
  <strong>AVISO</strong> este curso no tiene <b>cursos gratuitos</b> asociados.
</div>';
} else {
    ?>
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>
                CURSO
                </td>
            <th>
                ESTADO
                </td>
            <th>

                </td>
        </tr>
        <?php
        while ($rqc2 = fetch($rqc1)) {
            ?>
            <tr>
                <td>
                    <?php echo $rqc2['titulo']; ?>
                </td>
                <td>
                    <?php
                    if ($rqc2['estado'] == '1') {
                        echo 'ACTIVADO';
                    } elseif ($rqc2['estado'] == '2') {
                        echo 'TEMPORAL';
                    } else {
                        echo 'DESACTIVADO';
                    }
                    ?>
                </td>
                <td>
                    <b class="btn btn-danger btn-xs pull-right" onclick="quitar_cursogratuito('<?php echo $rqc2['id']; ?>');">Quitar</b>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
}
?>
<hr>
<b class="btn btn-default btn-lg btn-block" data-toggle="modal" data-target="#MODAL-cursos_gratuitos_agregar" onclick="cursos_gratuitos_agregar('<?php echo $id_curso; ?>');">+ AGREGAR CURSO GRATUITO ASOCIADO</b>

<!--quitar_cursogratuito-->
<script>
    function quitar_cursogratuito(id_curso_free) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cursos_gratuitos.php',
            data: {id_curso: '<?php echo $id_curso; ?>', id_curso_free: id_curso_free},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_gratuitos").html(data);
            }
        });
    }
</script>