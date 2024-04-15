<?php
/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">listar</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <span class="pull-right">
                <a href="administradores-crear.adm" class="btn btn-success active"><i class="fa fa-plus-square"></i> Agregar administrador </a>
            </span>
        </div>
        <h3 class="page-header"> Administradores <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                En esta seccion se muestran los administradores registrados.
            </p>
        </blockquote>
    </div>
</div>

<?php
if (isset($get[3])) {
    $vista = $get[3];
} else {
    $vista = 1;
}

$resultado_administradores = query("SELECT * FROM administradores WHERE estado='1' AND sw_cursosbo='1' ORDER BY id ASC ");
$cantidad_administradores = num_rows($resultado_administradores);
?>



<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    LISTADO DE ADMINISTRADORES
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">


                <table width="95%"  class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>CARGO</th>
                        <th>CAJA</th>
                        <th>NICK</th>
                        <th>ACCION</th>
                    </tr>
                    <?php
                    $num = $cantidad_administradores + 1;
                    $cnt = 0;
                    $clase = "par";
                    while ($datos = fetch($resultado_administradores)) {

                        $cnt++;
                        $num--;
                        ?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td>
                            <h3><?php echo $datos['nombre']; ?></h3>
                            ( <?php echo $datos['email']; ?> )
                            </td>
                            <td><?php echo $datos['ocupacion']; ?></td>
                            <td>
                            <?php 
                            if($datos['sw_cierreapertura_caja']=='1'){
                                echo "<i class='label label-xs btn-success'>HABILITADO</i><br>";
                                echo "<i class='label label-xs btn-info'>APERTURA / CIERRE</i>";
                            }else{
                                echo "<i class='label label-xs btn-default'>SIN HABILITACI&Oacute;N</i>";
                            }
                            ?>
                            </td>
                            <td><?php echo $datos['nick']; ?></td>
                            <td>
                                <a href="administradores-monitoreo/<?php echo $datos['id']; ?>.adm" class="btn btn-sm btn-block btn-warning">
                                    <i class="fa fa-building"></i> 
                                    Monitoreo
                                </a><br/>
                                <a href="administradores-editar/<?php echo $datos['id']; ?>.adm" class="btn btn-sm btn-block btn-info">
                                    <i class="fa fa-edit"></i> 
                                    Edicion
                                </a><br/>
                                <a href="administradores-eliminar/<?php echo $datos['id']; ?>.adm" class="btn btn-sm btn-block btn-danger">
                                    <i class="fa fa-trash-o"></i> 
                                    Eliminacion
                                </a><br/>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>

            </div>
        </div>
    </div>
</div>







