<?php
$id_a_procesar = $get[2];

if (!isset($get[2])) {
    echo "<script>alert('Error en ingreso de Datos);location.href='$dominio" . "admin';</script>";
    exit;
}

if (isset_post('eliminar')) {

    $nombre = post('nombre');

    $r1 = query("DELETE FROM administradores WHERE id='$id_a_procesar' ");
    if ($r1) {
        movimiento('Eliminacion de administrador - [' . $nombre . ']', 'eliminacion-administrador', 'administrador', $id);
        echo "<script>alert('Proceso de eliminacion exitoso!');location.href='administradores-listar.adm';</script>";
        exit;
    }
}
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">Eliminaci&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <input type="text" class="form-control form-cascade-control nav-input-search" size="20" placeholder="Search through site">
            <span class="input-icon fui-search"></span>
        </div>
        <h3 class="page-header"> Eliminaci&oacute;n de Administrador <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Opci&acute;n a eliminaci&oacute;n de Administrador.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading text-primary">
                <h3 class="panel-title"><i class="fa fa-comments"></i> ADMINISTRADOR
                    <span class="pull-right">
                        <div class="btn-group code">
                           <!-- <a href="administradores-crear.adm" class="add-button" style="margin: 20px"><i class="fa fa-plus-square"></i> Nuevo </a>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Classes used"><i class="fa fa-code"></i></a>-->
                            <ul class="dropdown-menu pull-right list-group" role="menu">
                                <li class="list-group-item"><code>.table-condensed</code></li>
                                <li class="list-group-item"><code>.table-hover</code></li>
                            </ul>
                        </div>
                        <a class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                        <a class="panel-close"><i class="fa fa-times"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg">&#191;Desea eliminar el siguiente administrador?</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultado1 = query("SELECT * FROM administradores WHERE id='$id_a_procesar' ");
                        $producto = mysql_fetch_array($resultado1);
                        ?>
                        <tr>                               
                            <td class="visible-lg">
                                <p class="text-success">
                                    <b>Nivel de Administrador:</b> <?php echo $producto['nivel']; ?> <br/>
                                </p>
                                <p>
                                    <b>Nombre: </b> <?php echo $producto['nombre']; ?> <br/>
                                    <b>Nickname: </b> <?php echo $producto['nick']; ?><br/>
                                    <b>Password: </b> <?php echo $producto['password']; ?><br/>
                                    <b>Fecha de creaci&oacute;n de usuario:</b> <?php echo $producto['fecha_registro']; ?><br/>

                                </p>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <form action="" method="post">
                                    <input type="hidden" name="nombre" value="<?php echo $producto['nombre']; ?>"/>
                                    <input type="submit" name="eliminar" value="Eliminar" class="btn btn-danger btn-lg btn-animate-demo"/>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="administradores.adm">
                                        <input type="button" value="Cancelar" class="btn btn-primary btn-lg btn-animate-demo"/>
                                    </a>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="none">
    <br/>
    <i class="fa fa-plus-square"></i><a href="adminitradores.adm" style="padding:10px;">Listar Administradores</a>
    <br/>
    <br/>
</div>