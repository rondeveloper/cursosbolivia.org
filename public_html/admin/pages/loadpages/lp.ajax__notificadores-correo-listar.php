<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$resultado1 = query("SELECT *
FROM notificadores_de_correo
ORDER BY id DESC ");

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
        <h3 class="page-header"> NOTIFICADORES DE CORREO <i class="fa fa-info-circle animated bounceInDown show-info"></i> <a class="btn btn-success btn-sm active pull-right" onclick="agregar_correo_notificador()">AGREGAR CORREO NOTIFICADOR</a></h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de notificadores de correo
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CORREO</th>
                                <th>DESCRIPCION</th>
                                <th>CIFRADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($notificadores_correo = fetch($resultado1)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?>
                                    </td>
                                    <td>
                                        <div class="notificadores-correo-listar">
                                                <b>Correo:</b>
                                                <div><?= $notificadores_correo['correo'] ? $notificadores_correo['correo'] : 'no hay dato' ?></div>
                                                <b>User:</b>
                                                <div><?= $notificadores_correo['user'] ? $notificadores_correo['user'] : 'no hay dato'?></div>
                                                <b>Nombre Remitente:</b>
                                                <div><?= $notificadores_correo['nombre_remitente'] ? $notificadores_correo['nombre_remitente'] :'no hay dato'?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= $notificadores_correo['descripcion'] ?>
                                    </td>
                                    <td>
                                        <div class="notificadores-correo-listar">
                                                <b>Cifrado:</b>
                                                <div><?= $notificadores_correo['cifrado'] ? $notificadores_correo['cifrado'] : 'no hay dato' ?></div>
                                                <b>Puerto:</b>
                                                <div><?= $notificadores_correo['puerto'] ? $notificadores_correo['puerto'] : 'no hay dato'?></div>
                                                <b>Host:</b>
                                                <div><?= $notificadores_correo['host'] ? $notificadores_correo['host'] :'no hay dato'?></div>
                                        </div>
                                    </td>
                                    <td>                               
                                        <a onclick="editar_correo_notificador(<?= $notificadores_correo['id'] ?>)" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a onclick="eliminar_correo_notificador(<?= $notificadores_correo['id'] ?>)" title="Eliminar">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>
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

<!-- agregar_correo_notificador -->
<script>
    function agregar_correo_notificador() {
        $("#TITLE-modgeneral").html('AGREGAR CORREO NOTIFICADOR');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.notificadores-correo-listar.agregar_correo_notificador.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- editar_correo_notificador -->
<script>
    function editar_correo_notificador(id_correo_notificador) {
        $("#TITLE-modgeneral").html('EDITAR CORREO NOTIFICADOR');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.notificadores-correo-listar.editar_correo_notificador.php',
            data: {'id_correo_notificador':id_correo_notificador},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- eliminar_correo_notificador -->
<script>
    function eliminar_correo_notificador(id_correo_notificador) {
        $("#TITLE-modgeneral").html('ELIMINAR CORREO NOTIFICADOR');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.notificadores-correo-listar.eliminar_correo_notificador.php',
            data: {'id_correo_notificador':id_correo_notificador},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

