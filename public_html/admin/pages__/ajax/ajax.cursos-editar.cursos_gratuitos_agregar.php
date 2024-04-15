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

/* agregar */
if(isset_post('id_curso_free')){
    $id_curso_free = post('id_curso_free');
    query("INSERT INTO cursos_rel_cursofreecur(id_curso, id_curso_free, estado) VALUES ('$id_curso','$id_curso_free','1')");
    logcursos('Asignacion de curso gratuito asociado [id:'.$id_curso_free.']', 'curso-edicion', 'curso', $id_curso);
    logcursos('Asignado como gratuito asociado de [id:'.$id_curso.']', 'curso-edicion', 'curso', $id_curso_free);
    echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
}

?>
<table class="table table-bordered table-striped table-hover">
    <?php
    $rqc1 = query("SELECT id,titulo FROM cursos WHERE id<>'$id_curso' AND estado IN (1,2) AND id NOT IN (select id_curso_free from cursos_rel_cursofreecur where id_curso='$id_curso' and estado='1') ");
    while ($rqc2 = fetch($rqc1)) {
        ?>
        <tr>
            <td>
                <?php echo $rqc2['titulo']; ?>
            </td>
            <td>
                <b class="btn btn-success btn-sm btn-block" onclick="agregar_cursogratuito_p2('<?php echo $rqc2['id']; ?>');">+ AGREGAR</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>


<!--agregar_cursogratuito_p2-->
<script>
    function agregar_cursogratuito_p2(id_curso_free) {
        $("#AJAXCONTENT-cursos_gratuitos_agregar").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.cursos_gratuitos_agregar.php',
            data: {id_curso: '<?php echo $id_curso; ?>', id_curso_free: id_curso_free},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_gratuitos_agregar").html(data);
                cursos_gratuitos('<?php echo $id_curso; ?>');
            }
        });
    }
</script>
