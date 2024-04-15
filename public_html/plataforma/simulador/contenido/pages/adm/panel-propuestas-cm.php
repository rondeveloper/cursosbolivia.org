<router-outlet _ngcontent-jbq-c1=""></router-outlet>



<br>

<div _ngcontent-jbq-c12="" class="row">
    <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <div _ngcontent-jbq-c12="" class="card card-default">
            <div _ngcontent-jbq-c12="" class="card-header">
                <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">
                    PROPUESTAS - Proceso de contrataci&oacute;n CM
                    <span class="pull-right" style="float: right;margin-top: -10px;">
                        <a class="btn btn-warning btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-propuestas-cm">TODOS LOS ENVIOS</a>
                        &nbsp;&nbsp;&nbsp;
                        <a class="btn btn-info btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-propuestas-cm&filter=hoy">ENVIADOS EL DIA DE HOY</a>
                    </span>
                </div>
            </div>
            <div _ngcontent-jbq-c12="" class="card-body">

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th>
                        <th>USUARIO</th>
                        <th>CUCE</th>
                        <th>OBJETO</th>
                        <th>MODALIDAD</th>
                        <th>PRECIO REFERECIAL</th>
                        <th>VALOR OFERTADO</th>
                        <th>FECHA REGISTRO</th>
                        <th>ESTADO</th>
                    </tr>
                    <?php
                    $qr_hoy = "";
                    if (isset_get('filter')) {
                        $qr_hoy = " AND DATE(d.fecha_registro)=CURDATE() ";
                    }
                    $cnt = 1;
                    $rqde1 = query("SELECT u.nombres,u.apellidos,d.* FROM simulador_documentos d INNER JOIN cursos_usuarios u ON d.id_usuario=u.id WHERE modalidad='CM' $qr_hoy ORDER BY d.id ASC ");
                    while ($rqde2 = fetch($rqde1)) {
                    ?>
                        <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo $rqde2['nombres'].' '.$rqde2['apellidos']; ?></td>
                            <td><?php echo $rqde2['cuce']; ?></td>
                            <td><?php echo $rqde2['objeto']; ?></td>
                            <td><?php echo $rqde2['modalidad']; ?></td>
                            <td><?php echo $rqde2['precio_referencial']; ?></td>
                            <td><?php echo $rqde2['valor_ofertado']; ?></td>
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