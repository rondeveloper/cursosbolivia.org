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
$nom = post('nom');

if ($nom == '') {
    exit;
}

$rqdc1 = query("SELECT titulo,id,fecha FROM cursos WHERE (id='$nom' OR titulo LIKE '%$nom%') AND id<>'$id_curso' ORDER BY fecha DESC,id DESC LIMIT 15 ");
if (num_rows($rqdc1) == 0) {
    echo "Sin resultados";
}
?>
<table class="table table-bordered table-striped table-hover">
    <tr>
    <th>Curso</th>
    <th>#</th>
    <th>.</th>
    </tr>
    <?php
    while ($rqdc2 = fetch($rqdc1)) {
        $rqcn1 = query("SELECT count(*) AS total FROM cursos_interesados_wap WHERE id_curso='".$rqdc2['id']."' ");
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
                <b class="btn btn-xs btn-success" onclick="selecionar_curso('<?php echo $rqdc2['id']; ?>');">Seleccionar</b>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<script>
function selecionar_curso(id_curso){
    cursos_selecionados.push(id_curso);
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
