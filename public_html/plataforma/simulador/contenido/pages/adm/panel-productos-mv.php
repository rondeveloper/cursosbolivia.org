<router-outlet _ngcontent-jbq-c1=""></router-outlet>



<br>

<div _ngcontent-jbq-c12="" class="row">
    <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <div _ngcontent-jbq-c12="" class="card card-default">
            <div _ngcontent-jbq-c12="" class="card-header">
                <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">
                    PRODUCTOS MERCADO VIRTUAL
                    <span class="pull-right" style="float: right;margin-top: -10px;">
                        <a class="btn btn-warning btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-productos-mv">TODOS LOS ENVIOS</a>
                        &nbsp;&nbsp;&nbsp;
                        <a class="btn btn-info btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-productos-mv&filter=hoy">ENVIADOS EL DIA DE HOY</a>
                    </span>
                </div>
            </div>
            <div _ngcontent-jbq-c12="" class="card-body">

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th>
                        <th>USUARIO</th>
                        <th>IMG</th>
                        <th>ITEM</th>
                        <th>PRODUCTO</th>
                        <th>PAIS</th>
                        <th>PRECIOS</th>
                        <th>FECHA REGISTRO</th>
                        <th>ESTADO</th>
                    </tr>
                    <?php
                    $qr_hoy = "";
                    if (isset_get('filter')) {
                        $qr_hoy = " AND DATE(d.fecha_registro)=CURDATE() ";
                    }
                    $cnt = 1;
                    $rqde1 = query("SELECT u.nombres,u.apellidos,p.* FROM simulador_prods p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE 1 $qr_hoy ORDER BY p.id ASC ");
                    while ($rqde2 = fetch($rqde1)) {
                        $rqimg1 = query("SELECT * FROM simulador_files WHERE id_usuario='" . $rqde2['id_usuario'] . "' AND cuce='prod-" . $rqde2['id'] . "' LIMIT 1 ");
                        $rqimg2 = fetch($rqimg1);
                        $url_imagen = 'https://www.trinomusic.com/sites/default/files/default_images/imagen-no-disponible.gif';
                        if($rqimg2['nombre']!=''){
                            $url_imagen = $dominio_www.'contenido/imagenes/doc-usuarios/'.$rqimg2['nombre'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo $rqde2['nombres'].' '.$rqde2['apellidos']; ?></td>
                            <td style="text-align: center;"><img src="<?php echo $url_imagen; ?>" style="height: 120px;"/></td>
                            <td>42203605 Software de sistema de archivo de pelÃicula de rayos x para usos medicos</td>
                            <td><?php echo $rqde2['descripcion_corta']; ?></td>
                            <td><?php echo $rqde2['pais_origen']; ?></td>
                            <td>
                                <table class="table table-responsive">
                                    <tr>
                                        <th>Ubicaci&oacute;n</th>
                                        <th>Precio</th>
                                        <th>Cnt. Stock</th>
                                        <th>Fecha Vigencia</th>
                                    </tr>
                                    <?php
                                    $total_ofertado = 0;
                                    $rqdit1 = query("SELECT * FROM simulador_prods_precios WHERE id_prod='" . $rqde2['id'] . "' AND id_usuario='" . $rqde2['id_usuario'] . "' ");
                                    while ($rqdit2 = fetch($rqdit1)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $rqdit2['ubicacion']; ?></td>
                                            <td><?php echo $rqdit2['precio']; ?></td>
                                            <td><?php echo $rqdit2['cnt_stock']; ?></td>
                                            <td><?php echo $rqdit2['fecha_vigencia']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </td>
                            <td><?php echo $rqde2['fecha_registro']; ?></td>
                            <td>
                                <?php
                                if ($rqde2['estado'] == '0') {
                                    echo 'ELABORADO';
                                } elseif ($rqde2['estado'] == '1') {
                                    echo 'VERIFICADO';
                                } elseif ($rqde2['estado'] == '2') {
                                    echo 'ENVIADO';
                                }
                                ?>
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
</procesos-subasta-list-screen>