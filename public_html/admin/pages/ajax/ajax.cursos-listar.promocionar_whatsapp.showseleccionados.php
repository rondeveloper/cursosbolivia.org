<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$mensaje = '';

$id_curso = post('id_curso');
$cursos_selecionados = post('cursos_selecionados');

if ($cursos_selecionados == '') {
    exit;
}

$rqdc1 = query("SELECT titulo,id,fecha FROM cursos WHERE id IN ($cursos_selecionados) LIMIT 50 ");
if (num_rows($rqdc1) == 0) {
    echo "Sin resultados";
}
?>
<div style="background:#e4e4e4;padding: 7px;">
    <table class="table table-bordered table-striped table-hover" style="background: #FFF;">
        <tr>
            <th>Curso</th>
            <th>#</th>
            <th>.</th>
        </tr>
        <?php
        while ($rqdc2 = fetch($rqdc1)) {
            $rqcn1 = query("SELECT count(*) AS total FROM cursos_interesados_wap WHERE id_curso='" . $rqdc2['id'] . "' ");
            $rqcn2 = fetch($rqcn1);
        ?>
            <tr>
                <td class="text-left">
                    <span style="font-size: 8pt;"><?php echo $rqdc2['titulo']; ?></span>
                    <br>
                    <b class="label label-warning"><?php echo $rqdc2['fecha']; ?></b> &nbsp;&nbsp;&nbsp;&nbsp; <b class="label label-info">ID: <?php echo $rqdc2['id']; ?></b>
                </td>
                <td class="text-left">
                    <?php echo $rqcn2['total']; ?>
                </td>
                <td>
                    <b class="btn btn-xs btn-danger" onclick="quitar_curso('<?php echo $rqdc2['id']; ?>');">Quitar</b>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="3" class="text-center">
                <b class="btn btn-lg btn-success" onclick="proceder('<?php echo $id_curso; ?>');">PROCEDER</b>
            </td>
        </tr>
    </table>
</div>

<script>
function quitar_curso(id_curso){
    var index = cursos_selecionados.indexOf(id_curso);
    if (index > -1) {
        cursos_selecionados.splice(index, 1);
    }
    $.ajax({
        type: 'POST',
        url: 'pages/ajax/ajax.cursos-listar.promocionar_whatsapp.showseleccionados.php',
        data: {cursos_selecionados: cursos_selecionados.join(), id_curso: '<?php echo $id_curso; ?>'},
        success: function(data) {
            $("#cont-showseleccionados").html(data);
        }
    });
}
</script>

<script>
function proceder(id_curso){
    $("#AJAXCONTENT-modgeneral").html("PROCESANDO...");
    $.ajax({
        type: 'POST',
        url: 'pages/ajax/ajax.cursos-listar.promocionar_whatsapp.proceder.php',
        data: {cursos_selecionados: cursos_selecionados.join(), id_curso: '<?php echo $id_curso; ?>'},
        success: function(data) {
            $("#AJAXCONTENT-modgeneral").html(data);
        }
    });
}
</script>