<router-outlet _ngcontent-jbq-c1=""></router-outlet>



<br>

<div _ngcontent-jbq-c12="" class="row">
    <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <div _ngcontent-jbq-c12="" class="card card-default">
            <div _ngcontent-jbq-c12="" class="card-header">
                <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">
                    FORMULARIOS ENVIADOS
                    <span class="pull-right" style="float: right;margin-top: -10px;">
                        <a class="btn btn-warning btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-formularios">TODOS LOS ENVIOS</a>
                        &nbsp;&nbsp;&nbsp;
                        <a class="btn btn-info btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-formularios&filter=hoy">ENVIADOS EL DIA DE HOY</a>
                    </span>
                </div>
            </div>
            <div _ngcontent-jbq-c12="" class="card-body">

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th>
                        <th>USUARIO</th>
                        <th>Formulario A1</th>
                        <th>Formulario A2B</th>
                        <th>Formulario B1</th>
                        <th>Formulario C1</th>
                        <th>Formulario C2</th>
                    </tr>
                    <?php
                    $qr_hoy = " 1 ";
                    if(isset_get('filter')){
                        $qr_hoy = " DATE(fecha_upload)=CURDATE() ";
                    }
                    $cnt = 1;
                    $rqde1 = query("SELECT u.nombres,u.apellidos,u.id FROM cursos_usuarios u WHERE u.id IN (select id_usuario from simulador_docs where $qr_hoy ) ORDER BY u.id ASC ");
                    while ($rqde2 = fetch($rqde1)) {

                        $url_form_1 = '';
                        $fecha_form_1 = '';
                        $url_form_2 = '';
                        $fecha_form_2 = '';
                        $url_form_3 = '';
                        $fecha_form_3 = '';
                        $url_form_4 = '';
                        $fecha_form_4 = '';
                        $url_form_5 = '';
                        $fecha_form_5 = '';


                        $rqdefr1 = query("SELECT d.codigo,d.nombre,d.fecha_upload FROM simulador_docs d WHERE d.id_usuario='" . $rqde2['id'] . "' AND $qr_hoy ORDER BY d.id ASC ");
                        while ($rqdefr2 = fetch($rqdefr1)) {
                            $url_img = $dominio_www . 'contenido/imagenes/doc-usuarios/' . $rqdefr2['nombre'];
                            switch ($rqdefr2['codigo']) {
                                case 'form-1':
                                    $url_form_1 = $url_img;
                                    $fecha_form_1 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                    break;
                                case 'form-2':
                                    $url_form_2 = $url_img;
                                    $fecha_form_2 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                    break;
                                case 'form-3':
                                    $url_form_3 = $url_img;
                                    $fecha_form_3 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                    break;
                                case 'form-4':
                                    $url_form_4 = $url_img;
                                    $fecha_form_4 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                    break;
                                case 'form-5':
                                    $url_form_5 = $url_img;
                                    $fecha_form_5 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                    break;
                            }
                        }


                        $url_img = $dominio_www . 'contenido/imagenes/doc-usuarios/' . $rqde2['nombre'];
                    ?>
                        <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                            <td><?php if ($url_form_1 != '') { ?><a href="<?php echo $url_form_1; ?>" target="_blank">VISUALIZAR</a><?php } else {
                                                                                                                                    echo 'Sin archivo';
                                                                                                                                } ?></td>
                            <td><?php if ($url_form_2 != '') { ?><a href="<?php echo $url_form_2; ?>" target="_blank">VISUALIZAR</a><?php } else {
                                                                                                                                    echo 'Sin archivo';
                                                                                                                                } ?></td>
                            <td><?php if ($url_form_3 != '') { ?><a href="<?php echo $url_form_3; ?>" target="_blank">VISUALIZAR</a><?php } else {
                                                                                                                                    echo 'Sin archivo';
                                                                                                                                } ?></td>
                            <td><?php if ($url_form_4 != '') { ?><a href="<?php echo $url_form_4; ?>" target="_blank">VISUALIZAR</a><?php } else {
                                                                                                                                    echo 'Sin archivo';
                                                                                                                                } ?></td>
                            <td><?php if ($url_form_5 != '') { ?><a href="<?php echo $url_form_5; ?>" target="_blank">VISUALIZAR</a><?php } else {
                                                                                                                                    echo 'Sin archivo';
                                                                                                                                } ?></td>

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