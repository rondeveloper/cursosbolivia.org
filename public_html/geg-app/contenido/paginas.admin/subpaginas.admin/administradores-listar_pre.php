
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">listar todos</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Administradores en Infosicoes <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                En esta seccion se muestran los administradores de Infosicoes, como tambien su desempe&ntilde;o en la empresa.
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

$resultado_administradores = mysql_query("SELECT * FROM administradores ORDER BY id DESC LIMIT 0,30");
$cantidad_administradores = mysql_num_rows($resultado_administradores);
?>



<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-heading">
                <h3 class="panel-title">
                    En esta seccion se muestran los administradores de Infosicoes, como tambien su desempe&ntilde;o en la empresa.
                    <span class="pull-right">
                        <a href="administradores-crear.adm"><i class="fa fa-plus-square"></i> Agregar administrador </a>
                    </span>
                </h3>
            </div>

            <div class="panel-body">
                <table width="95%"  class="table users-table table-condensed table-hover ">
                    <tr>
                        <th>Nro</th>
                        <th>NOMBRE</th>
                        <th>OCUPACION</th>
                        <th>EMAIL</th>
                        <th>NICK</th>
                        <th>CONTRASE&Ntilde;A</th>
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
                            <td><?php echo $datos['nombre']; ?></td>
                            <td><?php echo $datos['ocupacion']; ?></td>
                            <td><?php echo $datos['email']; ?></td>
                            <td><?php echo $datos['nick']; ?></td>
                            <td><?php echo str_repeat('*', strlen($datos['password'])); ?></td>
                            <td>
                                <a href="movimiento/1/administrador/<?php echo $datos['id']; ?>.adm">
                                    <i class="fa fa-building"></i> 
                                    Historial
                                </a><br/>
                                <a href="administradores-editar/<?php echo $datos['id']; ?>.adm">
                                    <i class="fa fa-edit"></i> 
                                    Edicion
                                </a><br/>
                                <a href="administradores-eliminar/<?php echo $datos['id']; ?>.adm">
                                    <i class="fa fa-trash-o"></i> 
                                    Eliminacion
                                </a><br/>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">

                            <?php
                            //
                            $j_vista = 1;
                            if ($vista > 5) {
                                $j_vista = $vista - 4;
                            }
                            if ((($vista + 1) * 7) > ($cantidad_administradores - 15)) {
                                $j_vista = $vista - 10;
                            }
                            if ($cantidad_administradores < 20) {
                                $j_vista = 1;
                            }

                            echo "<li><a href='administradores-listar.adm'><span class=''>  Inicio  </span></a></li>";
                            for ($j = $j_vista; $j <= ($j_vista + 10); $j++) {
                                if ($j > (($cantidad_administradores / 25)) + 1) {
                                    break;
                                }
                                if ($vista == $j) {
                                    echo "<li class='active'><a href='administradores-listar/vista/" . $j . ".adm'>" . $j . "</a></li>";
                                } else {
                                    echo "<li><a href='administradores-listar/vista/" . $j . ".adm'><span class=''>" . $j . "</span></a></li>";
                                }
                            }
                            if ($j <= (($cantidad_administradores / 25)) + 1) {
                                echo "<li><a href='administradores-listar/vista/" . ($vista + 1) . ".adm'><span class=''>  >  </span></a></li>";
                            }
                            ?>


                        </ul>							
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>







