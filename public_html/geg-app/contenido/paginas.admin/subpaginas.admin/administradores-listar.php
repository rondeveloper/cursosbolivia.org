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
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
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

$resultado_administradores = query("SELECT * FROM administradores WHERE estado='1' AND sw_cursosbo='1' ORDER BY id DESC ");
$cantidad_administradores = mysql_num_rows($resultado_administradores);
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
                        <th>EMAIL</th>
                        <th>NICK</th>
                        <th>ACCION</th>
                    </tr>
                    <?php
                    $num = $cantidad_administradores + 1;
                    $cnt = 0;
                    $clase = "par";
                    while ($datos = mysql_fetch_array($resultado_administradores)) {

                        $cnt++;
                        $num--;
                        ?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><h3><?php echo $datos['nombre']; ?></h3></td>
                            <td><?php echo $datos['ocupacion']; ?></td>
                            <td><?php echo $datos['email']; ?></td>
                            <td><?php echo $datos['nick']; ?></td>
                            <td>
                                <a href="movimiento/1/administrador/<?php echo $datos['id']; ?>.adm" class="btn btn-xs btn-block btn-warning">
                                    <i class="fa fa-building"></i> 
                                    Historial
                                </a><br/>
                                <a href="administradores-editar/<?php echo $datos['id']; ?>.adm" class="btn btn-xs btn-block btn-info">
                                    <i class="fa fa-edit"></i> 
                                    Edicion
                                </a><br/>
                                <a href="administradores-eliminar/<?php echo $datos['id']; ?>.adm" class="btn btn-xs btn-block btn-danger">
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







