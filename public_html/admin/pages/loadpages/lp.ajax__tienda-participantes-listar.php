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
$id_curso = $get[2];

$resultado1 = query("SELECT cp.id, cp.nombres, cp.apellidos, cp.id_usuario, cpr.monto_deposito FROM cursos_participantes AS cp INNER JOIN cursos_proceso_registro AS cpr ON cp.id_proceso_registro = cpr.id WHERE cp.id_curso = '$id_curso' AND cp.estado = 1 ORDER BY cp.id DESC");
$cnt = 1;
?>
<!-- -------------------------------------inicio--------------------------------------- -->
<?php
/* curso */
$id_curso_tienda_admin = $get[2];
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso_tienda_admin' ORDER BY id DESC limit 1 ");
if (num_rows($rqdc1) == 0) {
    echo "<script>alert('Curso no encontrado');location.href='$dominio';</script>";
    exit;
}
$curso = fetch($rqdc1);

$id_curso = $curso['id'];
$titulo_curso = $curso['titulo'];
$costo_curso = $curso['costo'];
$duracion_curso = $curso['horarios'];
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

        <h3 class="page-header"> PARTICIPANTES DEL CURSO <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        <a class="btn btn-info btn-sm pull-right" <?php echo loadpage('tienda-cursos-listar'); ?>>VOLVER A CURSOS</a></h3>
    </div>
</div>

<div class="jumbotron">
    <div class="container" style="font-size: 19px;">
        <div>
            <b class="text-secondary">TITULO:</b> <?= $titulo_curso ?><br>
            <b class="text-secondary">COSTO:</b> <?= $costo_curso ?><br>
            <b class="text-secondary">CURSO VIRTUAL:</b> Curso Ley Nº 348<br>
        </div>
    </div>
</div>

<button class="btn btn-xs btn-success btn-block" onclick="registra_participante();"><i class="fa fa-plus"></i> AGREGAR PARTICIPANTE</button>

<br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de participantes
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda del participante..." value="<?php echo '' ?>" autocomplete="off" onkeyup="busca_tags();" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_tags();"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <br />
                <?php
                if(num_rows($resultado1)==0){
                    ?>
                    <div class='alert alert-info'>
                        <strong>INFO</strong> No hay participantes inscritos en este curso.
                    </div>
                    <?php
                }
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMBRE</th>
                                <th>APELLIDO</th>
                                <th>PAGO</th>
                                <th>HABILITACION</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($datos_participante = fetch($resultado1)) {
                                $id_enlace = $datos_participante['id'];
                            ?>
                                <tr>
                                    <td><?php echo $cnt++; ?>
                                        <b title="Historial Participante" class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $datos_participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                        <i class="fa fa-list" style="color:#8f8f8f;"></i></b>
                                    </td>
                                    <td>
                                        <b style="font-size: 14pt;color:#73b123;"><?php echo $datos_participante['nombres']; ?></b>
                                    </td>
                                    <td>
                                        <b style="font-size: 14pt;color:#73b123;"><?php echo $datos_participante['apellidos']; ?> </b>
                                    </td>
                                    <td class="simple-td text-center">
                                        <b style="color:#884908;font-size:9pt;">PAGO EN EFECTIVO</b><br><b style="font-size:12pt;color:#107fc7;"><?php echo $datos_participante['monto_deposito']; ?> BS</b> <br>
                                        <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('61945');" class="btn btn-xs btn-default">
                                            <i class="fa fa-info"></i> INFO PAGO
                                        </a>
                                        &nbsp;
                                        <span class="btn btn-xs btn-default" onclick="fecha_hora_pago('61945');"><i class="fa fa-clock-o"></i> Fecha Hora</span>
                                        <div id="ajaxcont-fecha_hora_pago-61945"></div>
                                        <br><br><b>Factura:</b> &nbsp; <i class="btn btn-xs btn-warning" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(61945);">No solicitada</i><br><br>
                                        <div style="background: #26c526;padding: 5px;margin-bottom: 15px;color: white;"><i class="fa fa-check"></i> VERIFICADO</div>
                                    </td>
                                    <td class="text-center">    
                                        <?php
                                        if($datos_participante['id_usuario']!=0){
                                            ?>
                                            <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                                                Des-habilitar: <b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_cvirtual_p1(<?php echo $datos_participante['id']; ?>);">X</b>
                                            </div>
                                            <a onclick="usuario_data('<?php echo $datos_participante['id_usuario']; ?>');" data-toggle="modal" data-target="#MODAL-usuario_data" style="text-decoration:none !important" title="USUARIO">
                                                <button type="button" class="btn btn-primary active"><i class="fa fa-user"></i>Usuario</button>
                                            </a>
                                        <?php
                                            }else{
                                        ?>
                                                <div style="padding: 5px;text-align: center;border: 1px solid #EEE;" id="ajaxcont-habilitacion-part-<?=      $datos_participante['id'] ?>">
                                                     Habilitar: &nbsp; <b class="btn btn-warning btn-xs" onclick="habilita_participante_cvirtual_p1(<?php echo      $datos_participante['id']; ?>);"><i class="fa fa-check"></i></b>
                                                </div>
                                        <?php
                                            }  
                                        ?>   
                                    </td>
                                    <td>                 
                                        <b class="btn btn-default btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $datos_participante['id']; ?>);">
                                            PANEL C-vir
                                        </b>
                                        <a onclick="emite_certificado_p1(<?=$datos_participante['id']?>, 0);" class="btn btn-primary btn-block" style="text-decoration:none !important" title="CERTIFICADOS">
                                            <i class="fa fa-group"></i> Certificados
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-editar-enlace-<?php echo $datos_participante['id']; ?>" class="btn btn-info btn-block" title="EDICION">
                                            <i class="fa fa-edit"></i> Editar
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-editar-enlace-<?php echo $datos_participante['id']; ?>" class="btn btn-info btn-block" title="PAGO">
                                            <i class="fa fa-edit"></i> Pago
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_p1(<?php echo $datos_participante['id']; ?>);" class="btn btn-danger btn-block">
                                            <i class="fa fa-trash-o"></i> Eliminar
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

<!-- Modal usuario_data -->
<div id="MODAL-usuario_data" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE USUARIO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-usuario_data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--usuario_data-->

<!-- Modal Elimina participante -->
<div id="MODAL-elimina-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content panel-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: ELIMINA PARTICIPANTE P1 -->
                <div id="ajaxloading-elimina_participante_p1"></div>
                <div id="ajaxbox-elimina_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal avance-cvirtual -->
<div id="MODAL-avance-cvirtual" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PANEL DE CURSO VIRTUAL</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-avance_cvirtual"></div>
                <div id="ajaxbox-avance_cvirtual">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal avance-cvirtual -->
<script>
    function usuario_data(id_usuario) {
        $("#AJAXCONTENT-usuario_data").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.usuario_data.php',
            data: {
                id_usuario: id_usuario,
                id_curso: '<?php echo $id_curso; ?>'
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-usuario_data").html(data);
            }
        });
    }
</script>

<!-- registra_participante -->
<script>
    function registra_participante() {
        $("#TITLE-modgeneral").html('AGREGA NUEVO PARTICIPANTE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.registra_participante.php',
            data: {
                id_curso: '<?php echo $id_curso; ?>'
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }

    function habilita_participante_cvirtual_p1(id_participante) {
        if (confirm('DESEA REALIZAR LA ACTIVACION ?')) {
            habilita_participante_cvirtual_p2(id_participante, 0);
        }
    }

    function habilita_participante_cvirtual_p2(id_participante) {
        $("#ajaxcont-habilitacion-part-" + id_participante).html('Procesando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p2.php',
            data: {
                id_participante: id_participante
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxcont-habilitacion-part-" + id_participante).html(data);
            }
        });
    }

    function emite_certificado_p1(id_participante, nro_certificado) {
        console.log('entro emite certificado')
        $("#TITLE-modgeneral").html('EMISION DE CERTIFICADO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
            data: {
                id_participante: id_participante,
                nro_certificado: nro_certificado
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }

    function elimina_participante_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p1.php',
            data: {
                id_participante: id_participante
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }

    function avance_cvirtual(id_participante) {
                $("#ajaxbox-avance_cvirtual").html("");
                $("#ajaxloading-avance_cvirtual").html('Cargando...');
                $.ajax({
                    url: 'pages/ajax/ajax.cursos-participantes.avance_cvirtual.php',
                    data: {id_participante: id_participante},
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        $("#ajaxloading-avance_cvirtual").html("");
                        $("#ajaxbox-avance_cvirtual").html(data);
                    }
                });
            }

            // INHABILITAR PARTICIPANTE
    function elimina_participante_cvirtual_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_cvirtual_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html('FUNCION NO IMPLEMENTADA');
        /*
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
            }
        });
        */
    }
</script>

<!-- MODAL historial_participante -->
<div id="MODAL-historial_participante" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_participante"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- historial_participante -->
<script>
    function historial_participante(id_participante) {
        $("#AJAXCONTENT-historial_participante").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.historial_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_participante").html(data);
            }
        });
    }
</script>
