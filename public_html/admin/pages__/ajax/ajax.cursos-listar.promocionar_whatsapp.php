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
<script>
var cursos_selecionados = [];
</script>
<style>
    .label-msj {
        background: #f3f3f3;
        text-align: center;
        padding: 5px;
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
        <td colspan="3"><?php echo $id_curso; ?></td>
    </tr>
</table>

<?php echo $mensaje; ?>

<h4 class="label-msj">CURSOS SELECCIONADOS</h4>
<div id="cont-showseleccionados">
    <div class="alert alert-default">
        <strong>SIN REGISTROS</strong> no se han seleccionado cursos.
    </div>
</div>

<hr>

<h4 class="label-msj">BUSCAR</h4>
<form id="FORM-registrar-numero" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><input type="text" value="" class="form-control" id="nom-cur" placeholder="Nombre de curso / ID" onkeyup="busca_curso(this.value);" autocomplete="off"/></td>
            <td><b class="btn btn-sm btn-default btn-block" onclick="busca_curso(document.getElementById('nom-cur').value);">Buscar</b></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" id="cont-busca_curso">
                
            </td>
        </tr>
    </table>
</form>


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
function busca_curso(nom){
    $.ajax({
        type: 'POST',
        url: 'pages/ajax/ajax.cursos-listar.promocionar_whatsapp.busca_curso.php',
        data: {nom: nom, id_curso: '<?php echo $id_curso; ?>'},
        success: function(data) {
            $("#cont-busca_curso").html(data);
        }
    });
}
</script>
