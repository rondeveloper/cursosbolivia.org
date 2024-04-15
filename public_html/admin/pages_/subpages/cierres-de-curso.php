<?php
/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 80;
$start = ($vista - 1) * $registros_a_mostrar;

$buscar = "";
$qr_busqueda = "";
if (isset_post('buscar') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
    $qr_busqueda = " AND (titulo LIKE '%$buscar%' OR descripcion LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


$resultado1 = query("SELECT * FROM cursos_cierres WHERE estado IN ('1') $qr_busqueda ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_cierres WHERE estado IN ('1') $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="cierres-de-curso.adm">Clientes</a></li>
            <li class="active">Listado de categorias</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-categoria">
                <i class="fa fa-plus"></i> 
                AGREGAR CATEGORIA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> CIERRES DE CURSO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de categorias registradas.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de cierres
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMBRE DE CATEGORIA</th>
                                <th>DESCRIPCI&Oacute;N</th>
                                <th>TOTAL CURSOS</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = 'ACTIVADO';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td>CIERRE <?php echo $producto['numeracion']; ?></td>
                                    <td><?php echo $producto['id_curso']; ?></td>
                                    <td><?php echo $producto['id_administrador']; ?></td>
                                    <td><?php echo $producto['fecha']; ?></td>
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

                    <li><a href="cierres-de-curso/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="cierres-de-curso/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="cierres-de-curso/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>
