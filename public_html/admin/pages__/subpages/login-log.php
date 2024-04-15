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
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li class="active">LOGIN LOG</li>
        </ul>
        <div class="form-group pull-right">
            <!--            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-categoria">
                            <i class="fa fa-plus"></i> 
                            AGREGAR CATEGORIA
                        </button> &nbsp;&nbsp;-->
        </div>
        <h3 class="page-header"> LOGIN LOG <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de login.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Ingresos al sistema
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ADMINISTRADOR</th>
                                <th>LOGIN</th>
                                <th>DURACI&Oacute;N</th>
                                <th>INICIO LOGIN</th>
                                <th>FINAL LOGIN</th>
                                <th style="width: 400px;">DISPOSITIVO</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resultado1 = query("SELECT *,(select nombre from administradores ad where l.id_administrador=ad.id limit 1)administrador FROM login_log l ORDER BY id DESC ");
                            $cnt = num_rows($resultado1);
                            while ($producto = fetch($resultado1)) {
                                
                                $time_login = ((strtotime($producto['fecha_final']))-(strtotime($producto['fecha_inicio'])));
                                if($time_login<60){
                                    $tiempo_login = $time_login.' seg';
                                }elseif($time_login<3600){
                                    $tiempo_login = (int)($time_login/60).' min '.($time_login%60).' seg';
                                }else{
                                    $tiempo_login = (int)($time_login/3600).' horas '.(int)(($time_login%3600)/60).' min '.(($time_login%3600)%60).' seg';
                                }
                                
                                
                                $Hacetime_login = (time()-(strtotime($producto['fecha_inicio'])));
                                if($Hacetime_login<60){
                                    $txtHace_login = 'Hace '.$Hacetime_login.' seg';
                                }elseif($Hacetime_login<3600){
                                    $txtHace_login = 'Hace '.((int)($Hacetime_login/60)).' min ';
                                }elseif($Hacetime_login<86400){
                                    $txtHace_login = 'Hace '.((int)($Hacetime_login/3600)).' horas '.(int)(($Hacetime_login%3600)/60).' min ';
                                }else{
                                    $txtHace_login = 'Hace '.((int)($Hacetime_login/86400)).' dias '.((int)(($Hacetime_login%86400)/3600)).' horas ';
                                }
                                                                
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $producto['administrador']; ?></td>
                                    <td><?php echo $txtHace_login; ?></td>
                                    <td><?php echo $tiempo_login; ?></td>
                                    <td><?php echo show_date_uno($producto['fecha_inicio']); ?></td>
                                    <td>
                                        <?php 
                                        if(strtotime($producto['fecha_final'])<(time()-60)){
                                            echo show_date_uno($producto['fecha_final']); 
                                        }else{
                                            echo " <span class='btn btn-xs btn-success active'>LOGUEADO</span>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $producto['user_agent']; ?></td>
                                    <td><?php echo $producto['ip']; ?></td>
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

                    <li><a href="categorias-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="categorias-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="categorias-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-categoria" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA CATEGORIA</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva categoria.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE DE LA CATEGORIA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Nombre de la categoria..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripci&oacute;n de la categoria..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="estado">
                                            <option value="1">ACTIVADO</option>
                                            <option value="2">DESACTIVADO</option>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-categoria" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR CATEGORIA"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>


<?php

function show_date_uno($dat){
    return date("d/M H:i",  strtotime($dat));
}