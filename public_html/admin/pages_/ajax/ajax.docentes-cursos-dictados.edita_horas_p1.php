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

$rqdc1 = query("SELECT titulo,duracion FROM cursos WHERE id='$id_curso' ");
$rqdc2 = fetch($rqdc1);

$titulo_curso = $rqdc2['titulo'];
$duracion_curso = $rqdc2['duracion'];
?>

<h2><?php echo $titulo_curso; ?></h2>

<h4>Duraci&oacute;n: <?php echo $duracion_curso; ?> horas</h4>

<hr>

<div id="AJAXCONTENT-edita_horas_p2">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Duraci&oacute;n:</b></td>
            <td><input type="numeral" value="<?php echo $duracion_curso; ?>" id="input-duracion" class="form-control"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <b class="btn btn-success" onclick="edita_horas_p2();">ACTUALIZAR DURACI&Oacute;N</b>
            </td>
        </tr>
    </table>
</div>


<script>
    function edita_horas_p2() {
        var duracion = $("#input-duracion").val();
        $("#AJAXCONTENT-edita_horas_p2").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.docentes-cursos-dictados.edita_horas_p2.php',
            data: {id_curso: '<?php echo $id_curso; ?>', duracion: duracion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_horas_p2").html(data);
                $("#MODAL-edita_horas").modal('hide');
                //$('#FORM-principal').submit();
                //document.getElementById("FORM-principal").submit();
            }
        });
    }
</script>

