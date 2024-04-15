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
?>

<!-- CONTENIDO DE PAGINA -->

<?php

$resultado1 = query("SELECT * FROM tienda_registros ORDER BY id DESC");
$con_o_sin_pago = $get[2];
if ($con_o_sin_pago == 'con-pago') {
    $resultado1 = query("SELECT * FROM tienda_registros WHERE estado = 1 ORDER BY id DESC");
} else if ($con_o_sin_pago == 'sin-pago') {
    $resultado1 = query("SELECT * FROM tienda_registros WHERE estado = 0 ORDER BY id DESC");
}
?>
<style>
    .participante_b b{
        font-size: 14px;
    }
    .participante_b{
        font-family: math;
    }
</style>

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
        <h3 class="page-header"> REGISTROS DE LA TIENDA <i class="fa fa-info-circle animated bounceInDown show-info"></i>
            <div class="pull-right">
                <a href="tienda-cursos-registros-listar.adm" class="btn btn-sm btn-info active"><i class="fa fa-group"></i> TODOS</a>
                &nbsp;|&nbsp;
                <a href="tienda-cursos-registros-listar/con-pago.adm" class="btn btn-sm btn-info active"><i class="fa fa-bars"></i> CON PAGO</a>
                &nbsp;|&nbsp;
                <a href="tienda-cursos-registros-listar/sin-pago.adm" class="btn btn-sm btn-info active"><i class="fa fa-money"></i> SIN PAGO</a>
                &nbsp;
            </div>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de cursos mostrados en la tienda
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PARTICIPANTE</th>
                                <th>REGISTRO</th>
                                <th>IMAGEN PAGO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;

                            while ($tienda_registro = fetch($resultado1)) {
                                $id_registro = $tienda_registro['id'];
                                $estado_registro = $tienda_registro['estado'];
                                $hash = HashUtil::hashIdRegistroTienda($id_registro);
                                $url_de_pago = $dominio.'registro-cursos-tienda-completado/'.$id_registro.'/'.$hash.'.html';

                                if ($estado_registro == 0) {
                                    $txt_estado = 'A espera del pago.';
                                } else if ($estado_registro == 1) {
                                    $txt_estado = 'Pago enviado, a espera de revisión.';
                                } else {
                                    $txt_estado = 'En espera';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $cnt++; ?>
                                        <b title="Historial De Registro" class="btn btn-default btn-xs" onclick="historial_registro('<?php echo $tienda_registro['id']; ?>');">
                                            <i class="fa fa-list" style="color:#8f8f8f;"></i></b>
                                    </td>
                                    <td class="participante_b">
                                        <div>
                                            <b>Nombre:</b><br><?= $tienda_registro['nombre'] ?>
                                        </div>
                                        <div>
                                            <b>Celular:</b><br><a target="_blank" href="https://api.whatsapp.com/send?phone=<?= $tienda_registro['celular'] ?>"><?= $tienda_registro['celular'] ?></a>
                                        </div>
                                        <div>
                                            <b>Correo:</b><br><a href="mailto:<?= $tienda_registro['correo'] ?>"><?= $tienda_registro['correo'] ?></a>
                                        </div>
                                    </td>

                                    <td class="participante_b">
                                        <div>
                                            <b>Total Cursos:</b><br><?= $tienda_registro['cnt_cursos'] ?>
                                        </div>
                                        <div>
                                            <b>Total Cotizado:</b><br><?= $tienda_registro['total_costo'] ?>
                                        </div>
                                        <div>
                                            <b>Fecha Registro:</b><br><?php echo $tienda_registro['fecha_registro']; ?>
                                            <br>
                                            <strong>Url de pago:</strong>
                                            <br>
                                            <a href="<?= $url_de_pago ?>" target="_blank">Ver la url de pago</a>
                                        </div>
                                    </td>
                                    <?php
                                    if ($tienda_registro['imagen_deposito'] != '') {
                                    ?>
                                        <td style='padding:5px;'>
                                            <img src="<?= $dominio ?>contenido/imagenes/depositos/<?= $tienda_registro['imagen_deposito'] ?>" alt="Foto Comprobante" width="130px" height="80px" onclick="open_image_blank(`<?= $dominio ?>contenido/imagenes/depositos/<?= $tienda_registro['imagen_deposito'] ?>`)" />
                                        </td>
                                    <?php
                                    } else {
                                    ?>
                                        <td>
                                            <b>No hay imagen</b>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                    <td>
                                        <b style="font-size: 11pt;color:#73b123;"><?= $txt_estado ?></b>
                                    </td>
                                    <td>
                                        <a id="pagar-tienda-registros" data-toggle="modal" data-target="#MODAL-pago-participante" onclick="verRegistrosTienda('<?php echo $tienda_registro['id']; ?>');" class="btn btn-xs btn-default">
                                            PAGAR
                                        </a>
                                        <br>
                                        <b class="btn btn-default btn-xs" onclick="showDetalleReportepago(<?= $tienda_registro['id']?>)">
                                            DETALLE
                                        </b>
                                        <br>
                                        <b class="btn btn-default btn-xs" onclick="openContacto(<?= $tienda_registro['id']?>)">
                                            CONTACTO
                                        </b>
                                        <br>
                                        <b class="btn btn-default btn-xs" onclick="enviarUrlPago(<?= $tienda_registro['id']?>)">
                                            ENVIAR URL PAGO
                                        </b>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
</div>

<!-- Modal pago-participante -->
<div id="MODAL-pago-participante" class="modal fade" role="dialog">
    <div class="modal-dialog modal-large">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PAGO CORRESPONDIENTE AL PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-pago_participante"></div>
                <div id="ajaxbox-pago_participante">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal edicion de participante -->

<!-- tienda registro -->
<script>
    function verRegistrosTienda(id_registro) {
        $("#ajaxbox-pago_participante").html('<h4>Procesando...</h4>');
        $.ajax({
            url: 'pages/ajax/ajax.tienda-cursos-registros-listar.verRegistrosTienda.php',
            data: {
                id_registro: id_registro
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxbox-pago_participante").html(data);
            }
        });
    }
</script>

<script>
    function open_image_blank(url) {
        window.open(url, '_blank', "width=800,height=800");
    }
</script>


<!-- historial registro-->
<script>
    function historial_registro(id_tienda_registro) {
        $("#TITLE-modgeneral").html('LOG DE MOVIMIENTOS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.registro-listar.historial_registro.php',
            data: {
                id_tienda_registro: id_tienda_registro
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
   
<!-- detalle reporte pago -->
<script>
    function showDetalleReportepago(id_tienda_registro) {
        $("#TITLE-modgeneral").html('DETALLE DEL PAGO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.tienda-registros-detalle.showDetalleReportepago.php',
            data: {id_tienda_registro: id_tienda_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- open contacto -->
<script>
    function openContacto(id_tienda_registro) {
        $("#TITLE-modgeneral").html('CONTACTO WHATSAPP');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contacto-registros.openContacto.php',
            data: {id_tienda_registro: id_tienda_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- enviarUrlPago -->
<script>
    function enviarUrlPago(id_tienda_registro) {
        $("#TITLE-modgeneral").html('ENVIAR URL DE PAGO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.tienda-cursos-registros-listar.enviarUrlPago.php',
            data: {id_tienda_registro: id_tienda_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
