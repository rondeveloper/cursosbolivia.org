<router-outlet _ngcontent-jbq-c1=""></router-outlet>
<procesos-subasta-list-screen _nghost-jbq-c12="">
    <div _ngcontent-lkh-c21="" class="content-heading p5">
        <div _ngcontent-lkh-c21="" class="row w-100">
            <div _ngcontent-lkh-c21="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-lkh-c21="" class="col-lg-5 col-12 pt10">
                <div _ngcontent-lkh-c21="" class="row">
                    <div _ngcontent-lkh-c21="" class="col-12">
                        <b>PANEL DE ADMINISTRACI&Oacute;N</b>
                    </div>
                </div>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-4 col-12 pt10 h30">
                <spinner-http _ngcontent-lkh-c21="" _nghost-lkh-c18="">
                    <!---->
                </spinner-http>
            </div>
            <div _ngcontent-lkh-c21="" class="col-lg-3 col-12">
                <reloj-fragment _ngcontent-lkh-c21="" _nghost-lkh-c22="">
                    <div _ngcontent-lkh-c22="" class="card flex-row align-items-center align-items-stretch border-0 mb-0">
                        <div _ngcontent-lkh-c22="" class="col-4 d-flex align-items-center bg-primary-light justify-content-center rounded-left">
                            <div _ngcontent-lkh-c22="" class="text-center">
                                <div _ngcontent-lkh-c22="" class="text-sm">Febrero</div>
                                <div _ngcontent-lkh-c22="" class="h4 mt-0">19</div>
                            </div>
                        </div>
                        <div _ngcontent-lkh-c22="" class="col-8 rounded-right"><span _ngcontent-lkh-c22="" class="text-uppercase h5 m0">Viernes</span><br _ngcontent-lkh-c22="">
                            <div _ngcontent-lkh-c22="" class="h4 mt-0">14:09:18</div>
                        </div>
                    </div>
                </reloj-fragment>
            </div>
        </div>
    </div>
    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">CUENTAS DE USUARIO ALEATORIOS</div>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body">

                    <table class="table table-bordered table-striped table-responsive">
                        <tr>
                            <th>#</th>
                            <th>NOMBRE</th>
                            <th>USUARIO</th>
                            <th>PASSWORD</th>
                        </tr>
                        <?php
                        $cnt = 1;
                        $rqde1 = query("SELECT u.* FROM cursos_usuarios u INNER JOIN cursos_participantes p ON u.id=p.id_usuario WHERE p.id_curso='2470' ORDER BY RAND() LIMIT 5 ");
                        while ($rqde2 = fetch($rqde1)) {
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td><?php echo $rqde2['nombres']; ?></td>
                                <td><?php echo $rqde2['email']; ?></td>
                                <td><?php echo $rqde2['password']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <br>
    <div _ngcontent-jbq-c12="" class="row">
        <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
            <div _ngcontent-jbq-c12="" class="card card-default">
                <div _ngcontent-jbq-c12="" class="card-header">
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">ENLACE DE INGRESO DE USUARIOS</div>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body" style="padding-bottom: 50px;text-align: center;">
                    <a href="https://plataforma.cursosbolivia.org/simulador/" target="_blank" style="font-size: 20pt;">https://plataforma.cursosbolivia.org/simulador/</a>
                </div>
            </div>
        </div>
    </div>
</procesos-subasta-list-screen>


<br>


</procesos-subasta-list-screen>