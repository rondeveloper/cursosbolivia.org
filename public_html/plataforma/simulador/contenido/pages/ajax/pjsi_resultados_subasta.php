<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');

?>

<div style="border: 7px solid #dada00;margin-top: 40px;padding: 40px;">

    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">
                        GANADORES EN TIEMPO REAL
                        <?php 
                        $rqdv1 = query("SELECT id FROM simulador_sigep_propuestas WHERE item<>0 AND fecha>=(DATE_SUB(NOW(), INTERVAL 1 HOUR)) AND id_usuario<>'$id_usuario' ");
                        if(num_rows($rqdv1)==0){
                        ?>
                        <b class="btn btn-danger" style="float: right;margin-top: -10px;" onclick="reiniciar_subasta('items');">REINICIAR SUBASTA</b>
                        <?php 
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div _ngcontent-jbq-c12="" class="card-body">
                            <div style="background: #20aeda;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">TRAJES DE BIOSEGURIDAD 1 PIEZA</div>
                            <?php
                            $cnt = 1;
                            $rqde1 = query("SELECT p.monto,u.nombres,u.apellidos,p.fecha FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=1 ORDER BY p.monto ASC,p.id ASC limit 1 ");
                            if (num_rows($rqde1) == 0) {
                                echo "NO SE ENVIARON PROPUESTAS";
                            } else {
                                $rqde2 = fetch($rqde1);
                            ?>
                                <div class="text-center">
                                    <b style="font-size: 27pt;">Bs <?php echo $rqde2['monto']; ?></b>
                                    <br>
                                    <div style="display: flex;justify-content: center;margin-top: 10px;margin-bottom: 10px;">
                                        <div style="background: green;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div style="background: gray;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                    </div>
                                    <br>
                                    <b style="font-size: 20pt;"><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></b>
                                    <br>
                                    <b style="font-size: 15pt;color:gray;"><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></b>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div _ngcontent-jbq-c12="" class="card-body">
                            <div style="background: #20aeda;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">TRAJES DE BIOSEGURIDAD 2 PIEZAS</div>
                            <?php
                            $cnt = 1;
                            $rqde1 = query("SELECT p.monto,u.nombres,u.apellidos,p.fecha FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=2 ORDER BY p.monto ASC,p.id ASC limit 1 ");
                            if (num_rows($rqde1) == 0) {
                                echo "NO SE ENVIARON PROPUESTAS";
                            } else {
                                $rqde2 = fetch($rqde1);
                            ?>
                                <div class="text-center">
                                    <b style="font-size: 27pt;">Bs <?php echo $rqde2['monto']; ?></b>
                                    <br>
                                    <div style="display: flex;justify-content: center;margin-top: 10px;margin-bottom: 10px;">
                                        <div style="background: green;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div style="background: gray;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                    </div>
                                    <br>
                                    <b style="font-size: 20pt;"><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></b>
                                    <br>
                                    <b style="font-size: 15pt;color:gray;"><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></b>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div _ngcontent-jbq-c12="" class="card-body">
                            <div style="background: #20aeda;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">GUANTES LATEX</div>
                            <?php
                            $cnt = 1;
                            $rqde1 = query("SELECT p.monto,u.nombres,u.apellidos,p.fecha FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=3 ORDER BY p.monto ASC,p.id ASC limit 1 ");
                            if (num_rows($rqde1) == 0) {
                                echo "NO SE ENVIARON PROPUESTAS";
                            } else {
                                $rqde2 = fetch($rqde1);
                            ?>
                                <div class="text-center">
                                    <b style="font-size: 27pt;">Bs <?php echo $rqde2['monto']; ?></b>
                                    <br>
                                    <div style="display: flex;justify-content: center;margin-top: 10px;margin-bottom: 10px;">
                                        <div style="background: green;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div style="background: gray;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                    </div>
                                    <br>
                                    <b style="font-size: 20pt;"><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></b>
                                    <br>
                                    <b style="font-size: 15pt;color:gray;"><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></b>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div _ngcontent-jbq-c12="" class="card-body">
                            <div style="background: #20aeda;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">ALCOHOL EN GEL</div>
                            <?php
                            $cnt = 1;
                            $rqde1 = query("SELECT p.monto,u.nombres,u.apellidos,p.fecha FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=4 ORDER BY p.monto ASC,p.id ASC limit 1 ");
                            if (num_rows($rqde1) == 0) {
                                echo "NO SE ENVIARON PROPUESTAS";
                            } else {
                                $rqde2 = fetch($rqde1);
                            ?>
                                <div class="text-center">
                                    <b style="font-size: 27pt;">Bs <?php echo $rqde2['monto']; ?></b>
                                    <br>
                                    <div style="display: flex;justify-content: center;margin-top: 10px;margin-bottom: 10px;">
                                        <div style="background: green;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <div style="background: gray;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                                    </div>
                                    <br>
                                    <b style="font-size: 20pt;"><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></b>
                                    <br>
                                    <b style="font-size: 15pt;color:gray;"><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></b>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="alert alert-warning">
        <strong>NOTA</strong> Esta seccion encuadrada en color amarillo solo es visible en el simulador y no as&iacute; en el sistema oficial.
    </div>



    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">PROPUESTAS ENVIADAS EN TIEMPO REAL</div>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body">


                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-cont1" onclick="selec_tab(1);">
                                TRAJES DE BIOSEGURIDAD 1 PIEZA
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-cont2" onclick="selec_tab(2);">
                                TRAJES DE BIOSEGURIDAD 2 PIEZAS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-cont3" onclick="selec_tab(3);">
                                GUANTES LATEX
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-cont4" onclick="selec_tab(4);">
                                ALCOHOL EN GEL
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="cont1" role="tabpanel" aria-labelledby="home-tab">
                            <div style="background: #4d8a65;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">TRAJES DE BIOSEGURIDAD 1 PIEZA</div>
                            <table class="table table-bordered table-striped table-responsive">
                                <tr>
                                    <th>#</th>
                                    <th>USUARIO</th>
                                    <th>MONTO</th>
                                    <th>FECHA</th>
                                    <th>ITEM</th>
                                </tr>
                                <?php
                                $cnt = 1;
                                $rqde1 = query("SELECT (u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha,p.item FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=1 ORDER BY p.id ASC ");
                                while ($rqde2 = fetch($rqde1)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                                        <td><?php echo $rqde2['monto']; ?> BS</td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></td>
                                        <td>ITEM <?php echo $rqde2['item']; ?> - TRAJES DE BIOSEGURIDAD 1 PIEZA</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="cont2" role="tabpanel" aria-labelledby="home-tab">
                            <div style="background: #4d8a65;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">TRAJES DE BIOSEGURIDAD 2 PIEZAS</div>
                            <table class="table table-bordered table-striped table-responsive">
                                <tr>
                                    <th>#</th>
                                    <th>USUARIO</th>
                                    <th>MONTO</th>
                                    <th>FECHA</th>
                                    <th>ITEM</th>
                                </tr>
                                <?php
                                $cnt = 1;
                                $rqde1 = query("SELECT (u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha,p.item FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=2 ORDER BY p.id ASC ");
                                while ($rqde2 = fetch($rqde1)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                                        <td><?php echo $rqde2['monto']; ?> BS</td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></td>
                                        <td>ITEM <?php echo $rqde2['item']; ?> - TRAJES DE BIOSEGURIDAD 2 PIEZAS</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="cont3" role="tabpanel" aria-labelledby="home-tab">
                            <div style="background: #4d8a65;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">GUANTES LATEX</div>
                            <table class="table table-bordered table-striped table-responsive">
                                <tr>
                                    <th>#</th>
                                    <th>USUARIO</th>
                                    <th>MONTO</th>
                                    <th>FECHA</th>
                                    <th>ITEM</th>
                                </tr>
                                <?php
                                $cnt = 1;
                                $rqde1 = query("SELECT (u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha,p.item FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=3 ORDER BY p.id ASC ");
                                while ($rqde2 = fetch($rqde1)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                                        <td><?php echo $rqde2['monto']; ?> BS</td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></td>
                                        <td>ITEM <?php echo $rqde2['item']; ?> - GUANTES LATEX</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="cont4" role="tabpanel" aria-labelledby="home-tab">
                            <div style="background: #4d8a65;text-align: center;color: #FFF;margin: 0px 10px;padding: 7px;border-radius: 5px;">ALCOHOL EN GEL</div>
                            <table class="table table-bordered table-striped table-responsive">
                                <tr>
                                    <th>#</th>
                                    <th>USUARIO</th>
                                    <th>MONTO</th>
                                    <th>FECHA</th>
                                    <th>ITEM</th>
                                </tr>
                                <?php
                                $cnt = 1;
                                $rqde1 = query("SELECT (u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha,p.item FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item=4 ORDER BY p.id ASC ");
                                while ($rqde2 = fetch($rqde1)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                                        <td><?php echo $rqde2['monto']; ?> BS</td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></td>
                                        <td>ITEM <?php echo $rqde2['item']; ?> - ALCOHOL EN GEL</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


</div>