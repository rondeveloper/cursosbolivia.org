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

/* curso */
$rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];
$fecha_curso = $rqdc2['fecha'];
?>
<style>
    .label-msj { 
        background: #f3f3f3;
        text-align: center;
        padding: 10px;
        border: 1px solid #7bc7f7;
    }
</style>


<table class="table table-striped table-bordered">
    <tr>
        <td><b>Curso:</b></td>
        <td colspan="3"><?php echo $titulo_curso; ?></td>
    </tr>
    <tr>
        <td><b>ID:</b></td>
        <td colspan="3"><?php echo $id_curso; ?></td>
    </tr>
</table>

<?php echo $mensaje; ?>

<div style="background: #c5e6fd;padding: 10px;">

    <table class="table table-striped table-bordered">
        <tr>
            <td colspan="2" class="text-center">
                <b>MENSAJE</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
<textarea class="form-control" style="height: 150px;" id="txt_mensaje">
Saludos, le queremos informar sobre nuestro próximo curso: 
*<?php echo $titulo_curso; ?>*
para más detalles puede ingresar al siguiente link:
<?php echo $dominio.numIDcurso($id_curso).'/'; ?>
</textarea>
            </td>
        </tr>
    </table>

    <h3 class="label-msj">LISTADO DE N&Uacute;MEROS</h3>

    <?php
    $rqln1 = query("SELECT * FROM cursos_interesados_wap WHERE estado=1 AND id_curso IN ($cursos_selecionados) AND numero_whatsapp<>'0' AND numero_whatsapp NOT IN (select celular from cursos_participantes where id_curso in ($cursos_selecionados) and estado='1'  and sw_pago='1' ) ORDER BY id DESC ");
    $cnt = num_rows($rqln1);
    if ($cnt == 0) {
        echo '<div class="alert alert-info">
        <strong>AVISO</strong> no se han realizado registros.
    </div>';
    }
    ?>
    <table class="table table-striped table-bordered table-hover" style="background: #FFF;">
        <?php
        while ($rqln2 = fetch($rqln1)) {
        ?>
            <tr>
                <td><?php echo $cnt--; ?></td>
                <td><?php echo $rqln2['numero_whatsapp']; ?></td>
                <td>
                    <span onclick="enviar_mensaje('<?php echo $rqln2['numero_whatsapp']; ?>');" style="cursor: pointer;">
                        <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;curor:pointer;">
                    </span>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>

<script>
function promocionar_whatsapp(id_curso){
    $("#TITLE-modgeneral").html('PROMOCIONAR CURSO POR WHATSAPP');
    $("#AJAXCONTENT-modgeneral").html('Procesando...');
    $.ajax({
        type: 'POST',
        url: 'pages/ajax/ajax.cursos-listar.promocionar_whatsapp.php',
        data: {id_curso: id_curso},
        success: function(data) {
            $("#AJAXCONTENT-modgeneral").html(data);
        }
    });
}
</script>

<script>
function enviar_mensaje(numero){
    var txt_mensaje = $("#txt_mensaje").val().replace(/\n/g,"%0A");
    alert(txt_mensaje);
    window.open('https://api.whatsapp.com/send?phone=591'+numero+'&text='+txt_mensaje, '_blank');
}
</script>

