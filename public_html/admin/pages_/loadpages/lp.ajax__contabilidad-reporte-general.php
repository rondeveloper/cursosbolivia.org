<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

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
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
/* datos de control de consulta */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = real_escape_string($_SERVER['HTTP_USER_AGENT']);
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* mensaje */
$mensaje = '';

/* acceso */
if (!acceso_cod('adm-contable-adm')) {
    echo "DENEGADO";
    exit;
}
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="row" style="padding: 10px 0px;">
            <div class="col-md-5">
                <b style="font-size: 15pt;color: #3283ca;">
                    REPORTE GENERAL <i class="fa fa-info-circle animated bounceInDown show-info"></i>
                </b>
            </div>
            <div class="col-md-7 text-right">
                <?php include '../items/item.enlaces_contabilidad.php';?>
            </div>
        </div>
    </div>
</div>

<?php echo $mensaje; ?>

<!-- Estilos -->
<style>
    .tr_curso_suspendido td {
        background: #ebefdd !important;
    }

    .tr_curso_cerrado td {
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }

    .tr_curso_cerrado:hover td {
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }

    .tr_curso_eliminado td {
        background: #f3e3e3 !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">MODULO DE REPORTE GENERAL</div>
            <div class="panel-body">
                <div style="background: #f7f7f7;
             padding: 15px 10px;
             border-radius: 10px;
             border: 1px solid #ffffff;
             box-shadow: 0px 1px 3px 0px #b5b5b5;">
                    <form action="" method="post" id="FORM-listado">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Desde: </span>
                                    <input type="date" name="fecha_inicio" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio..." />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Hasta: </span>
                                    <input type="date" name="fecha_fin" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio..." />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Referencia: </span>
                                    <select class="form-control" name="id_referencia">
                                        <option value='0'>TODOS</option>
                                        <?php
                                        $rqdr1 = query("SELECT * FROM contabilidad_referencias WHERE estado=1 ");
                                        while($rqdr2 = fetch($rqdr1)){
                                            ?>
                                            <option value='<?php echo $rqdr2['id']; ?>'>
                                            <?php echo $rqdr2['titulo']; ?> &nbsp;&nbsp;|&nbsp;&nbsp; 
                                            <?php 
                                            if($rqdr2['id_tipo_movimiento']=='1'){
                                                echo "[ INGRESO ]";
                                            }else{
                                                echo "[ SALIDA ]";
                                            }
                                            ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Administrador: </span>
                                    <select class="form-control" name="id_administrador">
                                        <option value='0'>TODOS</option>
                                        <?php
                                        $rqdr1 = query("SELECT * FROM administradores WHERE estado=1 ");
                                        while($rqdr2 = fetch($rqdr1)){
                                            ?>
                                            <option value='<?php echo $rqdr2['id']; ?>'><?php echo $rqdr2['nombre']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Modalidad de transacci&oacute;n: </span>
                                    <select class="form-control" name="id_modo_pago">
                                        <option value='0'>TODOS</option>
                                        <?php
                                        $rqdrmp1 = query("SELECT * FROM modos_de_pago WHERE estado=1 AND id<>10 ");
                                        while($rqdrmp2 = fetch($rqdrmp1)){
                                            ?>
                                            <option value='<?php echo $rqdrmp2['id']; ?>'><?php echo $rqdrmp2['titulo']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Sucursal: </span>
                                    <select class="form-control" name="id_sucursal">
                                        <option value='0'>TODOS</option>
                                        <?php
                                        $rqdrmp1 = query("SELECT * FROM sucursales WHERE estado=1 ");
                                        while($rqdrmp2 = fetch($rqdrmp1)){
                                            ?>
                                            <option value='<?php echo $rqdrmp2['id']; ?>'><?php echo $rqdrmp2['nombre']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>          
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 2px;">
                                <b class="btn btn-primary btn-block" onclick="listado(0);">EFECTUAR BUSQUEDA</b>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="table-responsive" id="AJAXCONTENT-listado">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- listado -->
<script>
    function listado() {
        $("#AJAXCONTENT-listado").html("<hr/><div style='text-align:center;'><div class='loader' style='margin:auto;'></div><b>CARGANDO...</b></div><hr/><p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos.</p><hr/>");
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-reporte-general.listado.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-listado").html(data);
            }
        });
    }
</script>
<script>
    listado();
</script>
<!-- eliminar_mov_contabilidad -->
<script>
    function eliminar_mov_contabilidad(id_ingresoegreso) {
        if (confirm('ESTA SEGURO QUE DESEA ELIMINAR EL MOVIMIENTO ?')) {
            $("#eliminar_mov_contabilidad__" + id_ingresoegreso).html('Eliminando...');
            $.ajax({
                url: 'pages/ajax/ajax.contabilidad-reporte-general.eliminar_mov_contabilidad.php',
                data: {
                    id_ingresoegreso: id_ingresoegreso
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    location.reload();
                }
            });
        }
    }
</script>

<!-- edita_referencia -->
<script>
    function edita_referencia(id_referencia) {
        $("#TITLE-modgeneral").html('EDITA REFERENCIA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad.edita_referencia.php',
            data: {
                id_referencia: id_referencia
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- factura -->
<script>
    function factura(id_contabilidad) {
        $("#TITLE-modgeneral").html('FACTURA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-ingresos.factura.php',
            data: {
                id_contabilidad: id_contabilidad
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>