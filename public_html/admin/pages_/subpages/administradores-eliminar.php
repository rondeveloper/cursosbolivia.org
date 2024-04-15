<?php
/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

/* mensaje */
$mensaje = '';

/* data */
$id_administrador = $get[2];

/* eliminar */
$sw_eliminacion = false;
if (isset_post('eliminar')) {
    $nombre = post('nombre');
    query("UPDATE administradores SET estado='0',sw_infosicoes='0',password='".md5(rand(9999,999999))."' WHERE id='$id_administrador' AND sw_infosicoes='1' ");
    movimiento('Eliminacion de administrador - [' . $nombre . '][' . $id_administrador . ']', 'eliminacion-administrador', 'administrador', $id_administrador);
    $sw_eliminacion = true;
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro eliminado exitosamente.
</div>';
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
        <h3 class="page-header"> Eliminaci&oacute;n de administrador <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Opci&acute;n a eliminaci&oacute;n de Administrador.
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-comments"></i> ADMINISTRADOR
                    <span class="pull-right">
                        <a ><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <?php
                if (!$sw_eliminacion) {
                    ?>

                    <table class="table users-table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="visible-lg">&#191;Desea eliminar el siguiente administrador?</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resultado1 = query("SELECT * FROM administradores WHERE id='$id_administrador' ");
                            $producto = fetch($resultado1);
                            ?>
                            <tr>                               
                                <td class="visible-lg">
                                    <p class="text-success">
                                        <b>Administrador:</b> <?php echo $producto['nombre']; ?> <br/>
                                    </p>
                                    <p>
                                        <b>Nombre: </b> <?php echo $producto['nombre']; ?> <br/>
                                        <b>Nickname: </b> <?php echo $producto['nick']; ?><br/>
                                        <b>Email: </b> <?php echo $producto['email']; ?><br/>
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

                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>