<?php

/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */


/* vista */
$vista = 1;
if (isset($get[3])) {
    $vista = $get[3];
}

$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

$id_administrador = $get[2];

/* adminsitrador */
$rqdadm1 = query("SELECT * FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
$rqdadm2 = fetch($rqdadm1);
$nombre_administrador = $rqdadm2['nombre'];

$resultado = query("SELECT * FROM cursos_log WHERE id_usuario='$id_administrador' AND usuario='administrador' ORDER BY id DESC LIMIT $start,$registros_a_mostrar ");


$rescount1 = query("SELECT COUNT(*) AS total FROM cursos_log WHERE id_usuario='$id_administrador' AND usuario='administrador' ");
$rescount2 = fetch($rescount1);
$total_registros = $rescount2['total'];
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> MONITOREO DE ADMINISTRADOR <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>





<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php echo strtoupper($nombre_administrador); ?>
                </h3>
            </div>
            <div class="panel-body">


                <table width="95%" class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>#</th>
                        <th>ACCION REALIZADA</th>
                        <th>OBJETO</th>
                        <th>IP</th>
                        <th>FECHA</th>
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
                                <h3><?php echo $datos['movimiento']; ?></h3>
                            </td>
                            <td>
                                <?php
                                $objeto = $datos['objeto'];
                                $id_objeto = $datos['id_objeto'];
                                echo "<b style='font-size: 11pt;
                            text-transform: uppercase;
                            color: #4CAF50;'>$objeto</b><br>";
                                if ($objeto == 'participante') {
                                    $rqdp1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                                    $rqdp2 = fetch($rqdp1);
                                    $nombre_participante = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
                                    echo $nombre_participante;
                                } elseif ($objeto == 'curso') {
                                    $rqdcr1 = query("SELECT titulo FROM cursos WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                                    $rqdcr2 = fetch($rqdcr1);
                                    $nombre_curso = $rqdcr2['titulo'];
                                    echo "<b>$id_objeto</b><br>";
                                    echo $nombre_curso;
                                } elseif ($objeto == 'curso-virtual') {
                                    $rqdcrv1 = query("SELECT titulo FROM cursos_onlinecourse WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                                    $rqdcrv2 = fetch($rqdcrv1);
                                    $nombre_curso = $rqdcrv2['titulo'];
                                    echo "<b>$id_objeto</b><br>";
                                    echo $nombre_curso;
                                } elseif ($objeto == 'certificado-curso') {
                                    $rqdcrt1 = query("SELECT texto_qr FROM cursos_certificados WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                                    $rqdcrt2 = fetch($rqdcrt1);
                                    $nombre_cert = $rqdcrt2['texto_qr'];
                                    echo "<b>$id_objeto</b><br>";
                                    echo $nombre_cert;
                                }elseif ($objeto == 'administrador') {
                                    $rqadm1 = query("SELECT nombre FROM administradores WHERE id='$id_objeto' ORDER BY id DESC limit 1 ");
                                    $rqadm2 = fetch($rqadm1);
                                    $nombre_administrador = $rqadm2['nombre'];
                                    echo $nombre_administrador;
                                }else{
                                    echo "<b>$id_objeto</b><br>";
                                }
                                ?>
                            </td>
                            <td><?php echo $datos['ip']; ?></td>
                            <td>
                            <?php 
                            echo date("d M Y",strtotime($datos['fecha'])); 
                            echo "<br><br>";
                            echo date("H:i",strtotime($datos['fecha'])); 
                            ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>



                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <li><a href="administradores-monitoreo/<?php echo $id_administrador; ?>/1.adm">Primero</a></li>
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
                                        echo '<li class="active"><a href="administradores-monitoreo/'.$id_administrador.'/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="administradores-monitoreo/'.$id_administrador.'/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>
                            <li><a href="administradores-monitoreo/<?php echo $id_administrador; ?>/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->
                </div>

            </div>
        </div>
    </div>
</div>