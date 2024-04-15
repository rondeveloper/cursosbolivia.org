<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* mensaje */
$mensaje = "";
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            
        </div>

        <h3 class="page-header" style="padding: 5px 15px;margin: 0px;font-size: 12pt;">
            CERTIFICADOS DE PARTICIPANTE <i class="fa fa-cert animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>



<?php echo $mensaje; ?>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: '<?php echo $dominio_procesamiento; ?>admin/process.inicio.data_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-datapart-" + id_curso).html(data);
            }
        });
    }
</script>

<div class="row">

    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" style="margin-top: 12px;">
                <table class="table table-bordered" style="background: #f9f9f9;">
                    <tr>
                        <td style="width: 110px;vertical-align: middle;font-weight: bold;font-size: 11pt;color: #3885b7;">CI DE PARTICIPANTE:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" id="input-busca-participante" style="height: 70px;font-size: 25pt;" autocomplete="off"/></td>
                    </tr>
                    <tr>
                        <td><b class="btn btn-block btn-info" onclick="buscar_certificados();" style="font-size: 18pt;padding: 11px 0px;">BUSCAR</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="AJAXCONTENT-buscar_certificados"></div>
    </div>

    <hr>

</div>

<hr/>

<script>
    function buscar_certificados() {
        $("#AJAXCONTENT-buscar_certificados").html("Cargando...");
        let dat = $("#input-busca-participante").val();
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.certificados-participante.buscar_certificados.php',
            data: {ci_participante: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-buscar_certificados").html(data);
            }
        });
    }
</script>

