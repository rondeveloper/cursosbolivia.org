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

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

/* post search */
$postjson = json_encode($_POST);
if ($postjson == '[]') {
    $post_search_data = 'no-search';
} else {
    $post_search_data = base64_encode($postjson);
}

/* get search */
if (isset($get[3]) && $get[3] !== 'no-search') {
    $_POST = json_decode(base64_decode($get[3]), true);
}

/* busqueda */
$busqueda = "";
$id_departamento = "0";
$fecha_busqueda = "";
$fecha_final_busqueda = "";
$txt_estado = "";
$htm_titlepage = 'REFERENCIAS';
$qr_busqueda = "";
$qr_fecha = "";
$qr_estado = " AND c.estado IN (0,1,2) AND c.sw_suspendido='0'  ";
$qr_departamento = "";
$qr_docente = "";
$qr_lugar = "";
$qr_modalidad = " AND id_modalidad IN (1) ";


/* agregar-referencia */
if (isset_post('agregar-referencia')) {
    $referencia = post('referencia');
    $id_tipo_movimiento = post('id_tipo_movimiento');
    query("INSERT INTO contabilidad_referencias (id_tipo_movimiento,titulo,estado) VALUES ('$id_tipo_movimiento','$referencia','1') ");
    $id_referencia = insert_id();
    logcursos('Creacion de referencia I [' . $referencia . ']', 'creacion-referencia', 'referencia', $id_referencia);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro fue agregado correctamente.
</div>';
}

/* editar-referencia */
if (isset_post('editar-referencia')) {
    $id_referencia = post('id_referencia');
    $referencia = post('referencia');
    $estado = post('estado');
    query("UPDATE contabilidad_referencias SET titulo='$referencia',estado='$estado' WHERE id='$id_referencia' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de referencia I/E [a:' . $referencia . ']', 'edicion-referencia', 'referencia', $id_referencia);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro fue modificado correctamente.
</div>';
}


/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];

/* registros */
$data_required = "
ie.*,(rf.titulo)dr_referencia
";

$resultado1 = query("SELECT $data_required FROM contabilidad ie INNER JOIN contabilidad_referencias rf ON ie.id_referencia=rf.id WHERE ie.estado='1' AND ie.id_tipo_movimiento='1' AND fecha=CURDATE() ORDER BY ie.fecha DESC,ie.id DESC ");
$total_registros = num_rows($resultado1);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
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
                    REFERENCIAS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
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
    .tr_curso_suspendido td{
        background: #ebefdd !important;
    }
    .tr_curso_cerrado td{
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }
    .tr_curso_cerrado:hover td{
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }
    .tr_curso_eliminado td{
        background: #f3e3e3 !important;
    }
</style>

<div class="row">
<div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">REFERENCIAS DE INGRESOS<b class="pull-right btn btn-xs btn-success" onclick="agregar_referencia_ingreso();">+ AGREGAR</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="" style="font-size:10pt;">#</th>
                                <th class="" style="font-size:10pt;">Referencia</th>
                                <th class="" style="font-size:10pt;">Estado</th>
                                <th class="" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rqrf1 = query("SELECT * FROM contabilidad_referencias WHERE id_tipo_movimiento='1' AND estado IN (1,2) ");
                            $cnt = 1;
                            while ($row = fetch($rqrf1)) {
                                ?>
                                <tr>
                                    <td class="">
                                        <?php echo $cnt++; ?>
                                    </td>
                                    <td class="">
                                        <?php echo $row['titulo']; ?>
                                    </td>
                                    <td class="">
                                        <?php 
                                        if($row['estado']=='1'){
                                            echo '<i class="label label-success">Habilitado</i>';
                                        }else{
                                            echo '<i class="label label-default">Des-Habilitado</i>';
                                        }
                                        ?>
                                    </td>
                                    <td class="">
                                    <?php 
                                        if($row['sw_edit']=='1'){
                                            ?>
                                            <b class="btn btn-xs btn-default" onclick="edita_referencia('<?php echo $row['id']; ?>');">Editar</b> 
                                            <?php 
                                        }else{
                                            echo "------";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">REFERENCIAS DE SALIDAS<b class="pull-right btn btn-xs btn-success" onclick="agregar_referencia_salida();">+ AGREGAR</b></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="" style="font-size:10pt;">#</th>
                                <th class="" style="font-size:10pt;">Referencia</th>
                                <th class="" style="font-size:10pt;">Estado</th>
                                <th class="" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rqrf1 = query("SELECT * FROM contabilidad_referencias WHERE id_tipo_movimiento='2' AND estado IN (1,2) ");
                            $cnt = 1;
                            while ($row = fetch($rqrf1)) {
                                ?>
                                <tr>
                                    <td class="">
                                        <?php echo $cnt++; ?>
                                    </td>
                                    <td class="">
                                        <?php echo $row['titulo']; ?>
                                    </td>
                                    <td class="">
                                        <?php 
                                        if($row['estado']=='1'){
                                            echo '<i class="label label-success">Habilitado</i>';
                                        }else{
                                            echo '<i class="label label-default">Des-Habilitado</i>';
                                        }
                                        ?>
                                    </td>
                                    <td class="">
                                        <?php 
                                        if($row['sw_edit']=='1'){
                                            ?>
                                            <b class="btn btn-xs btn-default" onclick="edita_referencia('<?php echo $row['id']; ?>');">Editar</b> 
                                            <?php 
                                        }else{
                                            echo "------";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- agregar_referencia_ingreso -->
<script>
    function agregar_referencia_ingreso() {
        $("#TITLE-modgeneral").html('AGREGAR REFERENCIA INGRESO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-referencias.agregar_referencia.php',
            data: {id_tipo_movimiento: '1'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- agregar_referencia_salida -->
<script>
    function agregar_referencia_salida() {
        $("#TITLE-modgeneral").html('AGREGAR REFERENCIA SALIDA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-referencias.agregar_referencia.php',
            data: {id_tipo_movimiento: '2'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- edita_referencia -->
<script>
    function edita_referencia(id_referencia) {
        $("#TITLE-modgeneral").html('EDITA REFERENCIA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-referencias.edita_referencia.php',
            data: {id_referencia: id_referencia},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
