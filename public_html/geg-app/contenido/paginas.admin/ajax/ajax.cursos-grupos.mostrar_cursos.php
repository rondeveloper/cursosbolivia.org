<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_grupo = post('id_grupo');

/* grupo */
$rqdm1 = query("SELECT * FROM cursos_agrupaciones WHERE id='$id_grupo' LIMIT 1 ");
$rqdm2 = mysql_fetch_array($rqdm1);
$nombre_grupo = $rqdm2['nombre_grupo'];

echo "<h3 style='margin-top: 0px;font-weight: bold;'>$nombre_grupo</h3>";
echo "<hr/>";

/* agregado de curso */
if (isset_post('id_curso')) {
    $id_curso = post('id_curso');
    /* verif */
    $rqv1 = query("SELECT id FROM cursos_rel_agrupcursos WHERE id_grupo='$id_grupo' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqv1) == 0) {
        query("INSERT INTO cursos_rel_agrupcursos (id_grupo,id_curso) VALUES ('$id_grupo','$id_curso') ");
        logcursos('Agregado de curso a agrupacion [C:' . $id_curso . ']', 'grupo-addcurso', 'grupo', $id_grupo);
        echo '<div class="alert alert-success">
      <strong>EXITO</strong> registro agregado correctamente.
    </div>';
    }
}

/* eliminado de curso */
if (isset_post('del_id_curso')) {
    $id_curso = post('del_id_curso');
    /* verif */
    query("DELETE FROM cursos_rel_agrupcursos WHERE id_grupo='$id_grupo' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    logcursos('Quitado de curso en agrupacion [C:' . $id_curso . ']', 'grupo-addcurso', 'grupo', $id_grupo);
    echo '<div class="alert alert-success">
      <strong>EXITO</strong> registro eliminado correctamente.
    </div>';
}

/* cursos */
$rqda1 = query("SELECT c.titulo,c.fecha,c.id FROM cursos_rel_agrupcursos r INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id_grupo='$id_grupo' ");
if (mysql_num_rows($rqda1) == 0) {
    ?>
    <div class="alert alert-info">
        <strong>AVISO</strong> no se encontraron registros.
    </div>
    <?php
}
?>
<table class="table table-striped table-bordered">
    <?php
    $cnt = 1;
    while ($rqda2 = mysql_fetch_array($rqda1)) {
        ?>
        <tr>
            <td>
                <?php echo $cnt++; ?>
            </td>
            <td>
                <?php echo $rqda2['titulo']; ?>
            </td>
            <td>
                <?php echo date("d/M/y", strtotime($rqda2['fecha'])); ?>
            </td>
            <td>
                <button class="btn btn-default btn-xs" onclick="quitar_curso('<?php echo $rqda2['id']; ?>');">Quitar</button> 
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr/>

<div class="panel panel-info">
    <div class="panel-heading">AGREGAR CURSO</div>
    <div class="panel-body">
        <div id="AJAXCONTENT-agrega_curso">
            <div class="form-group">
                <label for="busc">Buscar curso:</label>
                <input type="text" class="form-control" name="nombre_digital" id="busc" placeholder="Nombre del curso..." onkeyup="buscar_curso();" autocomplete="off"/>
            </div>
            <div id="AJAXCONTENT-buscar_curso">
                <br>
                <button class="btn btn-default" name="agregar-curso" onclick="buscar_curso();">BUSCAR</button> 
            </div>
        </div>
    </div>
</div>

<hr>

<script>
    function buscar_curso() {
        var busc = $("#busc").val();
        if (busc.length > 0) {
            $("#AJAXCONTENT-buscar_curso").html('Cargando...');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.buscar_curso.php',
                data: {busc: busc, id_grupo: '<?php echo $id_grupo; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-buscar_curso").html(data);
                }
            });
        }
    }
</script>
<script>
    function quitar_curso(id_curso) {
        $("#AJAXCONTENT-mostrar_cursos").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.mostrar_cursos.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', del_id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-mostrar_cursos").html(data);
            }
        });
    }
</script>
