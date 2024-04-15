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

        <?php if(false){ ?>
        <div class="text-center hidden-lg" style="border-bottom: 1px solid #dedede;padding-bottom: 10px;margin-bottom: 5px;padding-top: 5px;">
            <a href="cursos-infoact/1/no-search/todos/3.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/3', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">LP</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/1.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CB</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/4.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">SC</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/6.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CH</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/2.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PT</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/8.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">OR</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/7.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PD</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/9.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">BN</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/5.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">TJ</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/10.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/10', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #d6dae2;">VIR</a>
        </div>
        <span class="pull-right hidden-sm">
            <a href="cursos-infoact/1/no-search/todos/3.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/3', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">LP</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/1.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CB</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/4.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">SC</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/6.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CH</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/2.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PT</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/8.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">OR</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/7.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PD</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/9.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">BN</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/5.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">TJ</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/10.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/10', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #d6dae2;">VIR</a>
        </span>
        <?php } ?>

        <h3 class="page-header" style="padding: 5px 15px;margin: 0px;font-size: 12pt;"> PANEL DE ADMINISTRACI&Oacute;N | <?php echo $___nombre_del_sitio; ?> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Bienvenido al panel de administraci&oacute;n
            </p>
        </blockquote>
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
    <?php if (acceso_cod('adm-visibilidad-cursos') && false) { ?>
        <div class="col-md-12 text-center">
            <form id="FORM-actualiza_departamentos_visibles">
                <div style="padding:10px 0px;">
                    <?php
                    $rqddv1 = query("SELECT ids_departamentos_visibles FROM cursos_webdata WHERE id='1' ");
                    $rqddv2 = fetch($rqddv1);
                    $ids_departamentos_visibles = $rqddv2['ids_departamentos_visibles'];
                    $checked = '';
                    if ($ids_departamentos_visibles == '') {
                        $checked = ' checked="checked" ';
                    }
                    ?>
                    <label class="btn btn-xs btn-success">
                        <input type="checkbox" name="d-0" id="tcheck" onchange="actualiza_departamentos_visibles(0);" <?php echo $checked; ?>/>&nbsp;Todos
                    </label>
                    <?php
                    $rqddac1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                    while ($rqddac2 = fetch($rqddac1)) {
                        $checked = '';
                        if ($ids_departamentos_visibles == '' || (strpos(",$ids_departamentos_visibles,", "," . $rqddac2['id'] . ",") > 0)) {
                            $checked = ' checked="checked" ';
                        }
                        ?>
                        <label class="btn btn-xs btn-success">
                            <input type="checkbox" name="d-<?php echo $rqddac2['id']; ?>" class="dcheck" onchange="actualiza_departamentos_visibles(1);" <?php echo $checked; ?>/>&nbsp;
                            <?php echo $rqddac2['nombre']; ?>
                        </label>
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    <?php } ?>

    <br>
    <div class="alert alert-success">
        <strong>BIENVENIDO</strong>
        <br>
        Al panel de administraci&oacute;n.
    </div>


    <div>
        <?php 
        /* $aux_sw_virt = 'normal'; */
        $aux_sw_virt = 'virtual';
        ?>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" style="margin-top: 12px;">
                <table class="table table-bordered" style="background: #f9f9f9;">
                    <tr>
                        <td style="width: 110px;vertical-align: middle;font-weight: bold;font-size: 11pt;color: #3885b7;">PARTICIPANTE:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" id="input-busca-participante" style="height: 70px;font-size: 25pt;" autocomplete="off"/></td>
                    </tr>
                    <tr>
                        <td><b class="btn btn-block btn-info" onclick="buscar_participante('<?php echo $aux_sw_virt; ?>');" style="font-size: 18pt;padding: 11px 0px;">BUSCAR</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="AJAXCONTENT-buscar_participante"></div>
    </div>

    <hr>

    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" style="margin-top: 12px;">
                <table class="table table-bordered" style="background: #f9f9f9;">
                    <tr>
                        <td style="width: 110px;vertical-align: middle;font-weight: bold;font-size: 11pt;color: #3885b7;">CURSO:</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" id="input-busca-curso" style="height: 70px;font-size: 25pt;" placeholder="" autocomplete="off"/></td>
                    </tr>
                    <tr>
                        <td><b class="btn btn-block btn-warning" onclick="buscar_curso();" style="font-size: 18pt;padding: 11px 0px;">BUSCAR</b></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="AJAXCONTENT-buscar_curso"></div>
    </div>

    <hr>

    <div>
        <div class="row">
            <div class="col-md-12">
                <b>APERTURA Y CIERRE DE CAJA</b>
                <br>
                <a class="btn btn-sm btn-success" onclick="apertura_caja();">APERTURA DE CAJA</a>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-success" onclick="cierre_caja();">CIERRE DE CAJA</a>
            </div>
        </div>
    </div>

</div>

<hr/>

<!-- actualiza_departamentos_visibles -->
<script>
    function actualiza_departamentos_visibles(dat) {
        if (dat === 0) {
            $(".dcheck").prop("checked", true);
        } else if (dat === 1) {
            $("#tcheck").prop("checked", false);
        }
        var form = $("#FORM-actualiza_departamentos_visibles").serialize();
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.inicio.actualiza_departamentos_visibles.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                /*$("#TR-AJAXBOX-reprogramacion_de_curso").html(data);*/
            }
        });
    }
</script>

<script>
    function buscar_participante(modcourse) {
        $("#AJAXCONTENT-buscar_participante").html("Cargando...");
        let dat = $("#input-busca-participante").val();
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.cursos-listar.buscar_participante.php',
            data: {dat: dat, modcourse: modcourse},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-buscar_participante").html(data);
            }
        });
    }
</script>

<script>
    function buscar_curso() {
        $("#AJAXCONTENT-buscar_curso").html("Cargando...");
        let dat = $("#input-busca-curso").val();
        $.ajax({
            url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.inicio.buscar_curso.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-buscar_curso").html(data);
            }
        });
    }
</script>

<!-- apertura_caja -->
<script>
    function apertura_caja() {
        $("#TITLE-modgeneral").html('APERTURA DE CAJA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-caja.apertura_caja.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- cierre_caja -->
<script>
    function cierre_caja() {
        $("#TITLE-modgeneral").html('CIERRE DE CAJA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-caja.cierre_caja.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<?php

function mydatefechacurso($dat) {
    $day = date("w", strtotime($dat));
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    return $array_dias[(int) $day] . " " . $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}
