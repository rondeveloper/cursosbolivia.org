<?php
session_start();

include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_docente()) {
    echo "ACCESO DENEGADO";
    exit;
}


/* recepcion de datos POST */
$id_tarea = post('id_tarea');
$id_rel_cursoonlinecourse = post('id_rel_cursoonlinecourse');
$id_docente = docente('id');


/* tareas */
$rqt1 = query("SELECT u.apellidos,u.nombres,e.id,e.archivo,e.calificacion FROM cursos_onlinecourse_tareasenvios e INNER JOIN cursos_usuarios u ON e.id_usuario=u.id WHERE e.id_tarea='$id_tarea' ORDER BY u.nombres ASC ");
if (num_rows($rqt1) == 0) {
    echo '<div class="alert alert-success">
<strong>NOTA</strong> no se realizo ning&uacute;n env&iacute;o para esta tarea.
</div>';
} else {
    $cnt = 0;
    ?>
    <style>
        .btn-cal{
            background: #b9b9b9;
            color: #FFF;
            padding: 4px 10px;
            border-radius: 50%;
            cursor: pointer;
            border: 1px solid gray;
        }
        .btn-cal:hover{
            background: gray;
        }
        .btn-cal-a{
            background: #40c714;
        }
        .btn-cal-a:hover{
            background: #39a716;
        }
        .btn-cal-b{
            background: #a1b11f;
        }
        .btn-cal-b:hover{
            background: #b5c71e;
        }
        .btn-cal-c{
            background: #18a0a7;
        }
        .btn-cal-c:hover{
            background: #18888e;
        }
        .nota-cal{
            font-size: 16pt;
        }
    </style>
    <table class='table table-striped table-bordered table-hover'>
        <tr>
            <th class="th-order">#</th>
            <th class="th-order">Nombres</th>
            <th class="th-order">Apellidos</th>
            <th class="th-order">Archivo</th>
            <th class="th-order text-center">Calificador</th>
            <th class="th-order text-center">Nota</th>
        </tr>
        <?php
        while ($rqt2 = fetch($rqt1)) {
            $url_tarea = $dominio.'contenido/archivos/tareas/' . $rqt2['archivo'];
            $id_envio = $rqt2['id'];
            $calificacion = $rqt2['calificacion'];
            if ($calificacion == '0') {
                $txt_color = 'red';
            } else {
                $txt_color = 'gray';
            }
            ?>
            <tr>
                <td>
                    <?php echo ++$cnt; ?>
                </td>
                <td>
                    <?php echo $rqt2['nombres']; ?>
                </td>
                <td>
                    <?php echo $rqt2['apellidos']; ?>
                </td>
                <td>
                    <a onclick='window.open("<?php echo $url_tarea; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
                        [ Visualizar archivo ]
                    </a>
                </td>
                <td class="text-center" style="padding:12px 0px;">
                    <b class="btn-cal" onclick="calificar('0', '<?php echo $id_envio; ?>');">0</b>
                    &nbsp;
                    <b class="btn-cal btn-cal-c" onclick="calificar('50', '<?php echo $id_envio; ?>');">50</b>
                    &nbsp;
                    <b class="btn-cal btn-cal-b" onclick="calificar('80', '<?php echo $id_envio; ?>');">80</b>
                    &nbsp;
                    <b class="btn-cal btn-cal-a" onclick="calificar('100', '<?php echo $id_envio; ?>');">100</b>
                </td>
                <td class="text-center" id="ajaxbox-cal-<?php echo $id_envio; ?>">
                    <b class="nota-cal" style="color:<?php echo $txt_color; ?>;"><?php echo $calificacion; ?></b>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <hr>
    <p>Puede descargar todos los archivos enviados de esta tarea en un solo archivo.</p>
    <div id="AJAXBOX-comprimir_tareas">
        <b class="btn btn-success" onclick="comprimir_tareas();">DESCARGAR ARCHIVOS</b>
    </div>
    <?php
}
?>

<script>
    function calificar(calificacion, id_envio) {
        $("#ajaxbox-cal-" + id_envio).html('-');
        $.ajax({
            url: 'pages/ajax/ajax.acount-docente.tareas.calificar.php',
            data: {id_tarea: '<?php echo $id_tarea; ?>', calificacion: calificacion, id_envio: id_envio},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxbox-cal-" + id_envio).html(data);
            }
        });
    }
</script>


<script>
    function comprimir_tareas() {
        $("#AJAXBOX-comprimir_tareas").html('PROCESANDO...');
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ap/app.comprimir.tareas.php',
            data: {id_tarea: '<?php echo $id_tarea; ?>', id_rel_cursoonlinecourse: '<?php echo $id_rel_cursoonlinecourse; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-comprimir_tareas").html(data);
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