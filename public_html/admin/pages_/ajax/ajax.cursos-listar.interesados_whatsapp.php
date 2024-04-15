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

<?php
if (isset_post('registrar-numero')) {
    $numero = post('numero');
    if(strlen($numero)==8){

        $rvrf1 = query("SELECT id FROM cursos_interesados_wap WHERE numero_whatsapp='$numero' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        if(num_rows($rvrf1)==0){
            query("INSERT INTO cursos_interesados_wap 
            (id_curso, numero_whatsapp) 
            VALUES 
            ('$id_curso','$numero')
            ");
            $mensaje = '<div class="alert alert-success">
            <strong>EXITO</strong> registro agregado correctamente.
        </div>';
        }else{
            $mensaje = '<div class="alert alert-info">
            <strong>NUMERO YA EXISTENTE</strong><br>ya se registro este numero anteriormente.
        </div>';
        }
    }else{
        $mensaje = '<div class="alert alert-danger">
        <strong>ERROR</strong> el numero debe ser de 8 digitos.
    </div>';
    }
    
}
?>
<table class="table table-striped table-bordered">
    <tr>
        <td><b>Curso:</b></td>
        <td colspan="3"><?php echo $titulo_curso; ?></td>
    </tr>
    <tr>
        <td><b>ID:</b></td>
        <td colspan="2"><?php echo $id_curso; ?></td>
        <td><b class="btn btn-info btn-xs btn-block" onclick="promocionar_whatsapp('<?php echo $id_curso; ?>');">PROMOCIONAR</b></td>
    </tr>
</table>

<?php echo $mensaje; ?>

<form id="FORM-registrar-numero" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td colspan="2" class="text-center">
                <b>NUEVO REGISTRO</b>
            </td>
        </tr>
        <tr>
            <td><b>N&uacute;mero:</b></td>
            <td><input type="number" name="numero" value="" class="form-control" id="num-value"/></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <input type="hidden" value="<?php echo $id_curso; ?>" name="id_curso" />
                <input type="submit" value="REGISTRAR" class="btn btn-success btn-sm" />
            </td>
        </tr>
    </table>
</form>

<h3 class="label-msj">LISTADO DE N&Uacute;MEROS</h3>

<?php
$rqln1 = query("SELECT * FROM cursos_interesados_wap WHERE estado=1 AND id_curso='$id_curso' ORDER BY id DESC ");
$cnt = num_rows($rqln1);
if ($cnt == 0) {
    echo '<div class="alert alert-info">
    <strong>AVISO</strong> no se han realizado registros.
  </div>';
}
?>
<table class="table table-striped table-bordered">
    <?php
    while ($rqln2 = fetch($rqln1)) {
    ?>
        <tr>
            <td><?php echo $cnt--; ?></td>
            <td><?php echo $rqln2['numero_whatsapp']; ?></td>
            <td>
                <a href="https://api.whatsapp.com/send?phone=591<?php echo $rqln2['numero_whatsapp']; ?>&text=" target="_blank">
                    <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;curor:pointer;">
                </a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>

<script>
    $('#FORM-registrar-numero').on('submit', function(e) {
        e.preventDefault();
        let num_value = $("#num-value").val();
        if(num_value.length!=8){
            alert('EL NUMERO NO ES DE 8 DIGITOS');
        } else if(!num_value.match(/^\d+$/)) {
            alert('EL VALOR INGRESADO SOLO DEBE CONTENER DIGITOS NUMERICOS');
        }else{
            $("#AJAXCONTENT-modgeneral").html('Procesando...');
            var formData = new FormData(this);
            formData.append('registrar-numero', 1);
            $.ajax({
                type: 'POST',
                url: 'pages/ajax/ajax.cursos-listar.interesados_whatsapp.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    });
</script>

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

