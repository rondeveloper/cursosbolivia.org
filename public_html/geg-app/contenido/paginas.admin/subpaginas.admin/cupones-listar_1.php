<?php
/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$buscar = "";
$qr_busqueda = "";

$resultado1 = query("SELECT *,(select nombre from administradores where id=cec.id_administrador)administrador FROM cursos_emisiones_cupones cec WHERE estado IN ('1','2') $qr_busqueda ORDER BY estado ASC, id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_emisiones_cupones WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="docentes-listar.adm">Docentes</a></li>
            <li class="active">Listado de docentes</li>
        </ul>

        <div class="form-group pull-right">

        </div>
        <h3 class="page-header"> CUPONES DE DESCUENTO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de docentes registrados.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                VALIDADOR DE CUPONES
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese el c&oacute;digo de cup&oacute;n..." value="<?php echo $buscar; ?>" autocomplete="off"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                if (isset_post('buscar')) {
                    $cod_buscar = post('buscar');
                    $rqvc1 = query("SELECT * FROM cursos_emisiones_cupones WHERE estado IN ('1','2') AND codigo='$cod_buscar' ");
                    if (mysql_num_rows($rqvc1) > 0) {
                        ?>
                        <h3 style="color:green;">C&Oacute;DIGO DE CUP&Oacute;N VALIDO</h3>
                        <?php
                    }else{
                        ?>
                        <h3 style="color:red;">C&Oacute;DIGO DE CUP&Oacute;N INCORRECTO</h3>
                        <?php
                    }
                }
                ?>

            </div>

        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                LISTADO DE CUPONES EMITIDOS
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CODIGO</th>
                                <th>CUP&Oacute;N</th>
                                <th>ADMINISTRADOR</th>
                                <th>FECHA CREACI&Oacute;N</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {

                                /* cupon */
                                $rcdc1 = query("SELECT * FROM cursos_cupones WHERE id='" . $producto['id_cupon'] . "' ORDER BY id DESC limit 1 ");
                                $rcdc2 = mysql_fetch_array($rcdc1);

                                /* estado */
                                $txt_estado = '<span style="color:red;">DESACTIVADO</span>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<span style="color:green;">VIGENTE</span>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td>
                                        <span style="font-size:17pt;">
                                            <?php echo substr($producto['codigo'], 0, 4) . '****'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        echo $rcdc2['detalle_cupon'];
                                        echo "<br/>";
                                        echo "<b>" . $rcdc2['porcentaje_descuento'] . " % DESCUENTO</b>";
                                        echo "<br/>";
                                        echo $rcdc2['fecha_expiracion'];
                                        ?>
                                    </td>
                                    <td><?php echo $producto['administrador']; ?></td>
                                    <td><?php echo $producto['fecha_registro']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
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



        <div class="row">
            <div class="col-md-12">
                <ul class="pagination">
                    <?php
                    $urlget3 = '';
                    if (isset($get[3])) {
                        $urlget3 = '/' . $get[3];
                    }
                    $urlget4 = '';
                    if (isset($get[4])) {
                        $urlget4 = '/' . $get[4];
                    }
                    $urlget5 = '';
                    if (isset($buscar)) {
                        if ($urlget3 == '') {
                            $urlget3 = '/--';
                        }
                        if ($urlget4 == '') {
                            $urlget4 = '/--';
                        }
                        $urlget5 = '/' . $buscar;
                    }
                    ?>

                    <li><a href="docentes-listar/1.adm">Primero</a></li>                           
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
                                echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="docentes-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="docentes-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

