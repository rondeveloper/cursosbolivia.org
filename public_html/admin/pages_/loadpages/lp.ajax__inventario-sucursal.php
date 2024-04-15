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
if($postdata!==''){
    $_POST = json_decode(base64_decode($postdata),true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php

/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[3])) {
    $vista = $get[3];
}

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$id_sucursal = $get[2];


/* agregar item */
if(isset_post('agregar-item')){
    $cantidad = post('cantidad');
    $item = post('item');
    $descripcion = post('descripcion');
    for($i=1;$i<=$cantidad;$i++){
        query("INSERT INTO items_sucursal (id_sucursal,item,descripcion) VALUES ('$id_sucursal','$item','$descripcion') ");
    }
    $mensaje .= '<div class="alert alert-success">
    <strong>EXITO</strong> Item registrado.
  </div>';
}



/* adminsitrador */
$rqdadm1 = query("SELECT * FROM sucursales WHERE id='$id_sucursal' LIMIT 1 ");
$rqdadm2 = fetch($rqdadm1);
$nombre_sucursal = $rqdadm2['nombre'];

$resultado = query("SELECT * FROM items_sucursal WHERE id_sucursal='$id_sucursal' AND estado='1' ORDER BY id DESC LIMIT $start,$registros_a_mostrar ");

$rescount1 = query("SELECT COUNT(*) AS total FROM items_sucursal WHERE id_sucursal='$id_sucursal' AND estado='1' ");
$rescount2 = fetch($rescount1);
$total_registros = $rescount2['total'];
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
            <button class="btn btn-success" onclick="agregar_item();">
                <i class="fa fa-plus"></i> 
                AGREGAR ITEM
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> INVENTARIO DE SUCURSAL <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo strtoupper($nombre_sucursal); ?>
                </h3>
            </div>
            <div class="panel-body">


                <table width="95%" class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>#</th>
                        <th>ACTIVO</th>
                        <th>CODIGO</th>
                        <th>QR</th>
                        <th>DESCRIPCI&Oacute;N</th>
                    </tr>
                    <?php
                    $num = $total_registros-( ($vista-1)*$registros_a_mostrar );
                    $cnt = 0;
                    $clase = "par";
                    while ($datos = fetch($resultado)) {
                        $cnt++;
                    ?>
                        <tr>
                            <td><?php echo $num--; ?></td>
                            <td>
                                <h3><?php echo $datos['item']; ?></h3>
                            </td>
                            <td>COD00<?php echo $datos['id']; ?></td>
                            <td>QR</td>
                            <td><?php echo $datos['descripcion']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>



                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <li><a href="administradores-monitoreo/<?php echo $id_sucursal; ?>/1.adm">Primero</a></li>
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_cursos = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_cursos) {
                                $fin_paginador = $total_cursos;
                            }

                            if ($total_cursos > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="administradores-monitoreo/'.$id_sucursal.'/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="administradores-monitoreo/'.$id_sucursal.'/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>
                            <li><a href="administradores-monitoreo/<?php echo $id_sucursal; ?>/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->
                </div>

            </div>
        </div>
    </div>
</div>



<!-- agregar_item -->
<script>
    function agregar_item() {
        $("#TITLE-modgeneral").html('AGREGAR ITEM SUCURSAL');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.inventario-sucursal.agregar_item.php',
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>