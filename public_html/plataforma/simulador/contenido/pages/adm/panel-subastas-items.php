<router-outlet _ngcontent-jbq-c1=""></router-outlet>
<procesos-subasta-list-screen _nghost-jbq-c12="">
    <div _ngcontent-lkh-c21="" class="content-heading p5">
        <div _ngcontent-lkh-c21="" class="row w-100">
            <div _ngcontent-lkh-c21="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-lkh-c21="" class="col-lg-5 col-12 pt10">
                <div _ngcontent-lkh-c21="" class="row">
                    <div _ngcontent-lkh-c21="" class="col-12">
                        <form action="" method="post">
                            <input type="submit" value="RESETEAR PROPUESTAS" name="resetear-items" class="btn btn-warning btn-sx" />
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-info btn-sx" href="https://plataforma.cursosbolivia.org/simulador/admin.php?page=panel-subastas-items">ACTUALIZAR DATOS</a>
                        </form>
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
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">GANADORES</div>
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
</procesos-subasta-list-screen>


<br>

<div _ngcontent-jbq-c12="" class="row">
    <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <div _ngcontent-jbq-c12="" class="card card-default">
            <div _ngcontent-jbq-c12="" class="card-header">
                <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">PROPUESTAS ENVIADAS</div>
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
                            <th></th>
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
                                <td><b class="btn btn-xs btn-info" onclick="comportamiento_subasta(<?php echo $rqde2['dr_id_usuario']; ?>,1);">Comportamiento</b></td>
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
                            <th></th>
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
                                <td><b class="btn btn-xs btn-info" onclick="comportamiento_subasta(<?php echo $rqde2['dr_id_usuario']; ?>,2);">Comportamiento</b></td>
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
                            <th></th>
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
                                <td><b class="btn btn-xs btn-info" onclick="comportamiento_subasta(<?php echo $rqde2['dr_id_usuario']; ?>,3);">Comportamiento</b></td>
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
                            <th></th>
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
                                <td><b class="btn btn-xs btn-info" onclick="comportamiento_subasta(<?php echo $rqde2['dr_id_usuario']; ?>,4);">Comportamiento</b></td>
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
</procesos-subasta-list-screen>

<sigep-mensaje _ngcontent-hmx-c1="" _nghost-hmx-c7="">
    <div _ngcontent-hmx-c7="">
        <div id="modal-box" _ngcontent-hmx-c7="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="”static”" data-keyboard="”false”" role="dialog" tabindex="-1">
            <div _ngcontent-hmx-c7="" class="modal-dialog modal-lg" style="margin-top: 100px;">
                <div _ngcontent-hmx-c7="" class="modal-content">
                    <div _ngcontent-hmx-c7="" class="modal-header">
                        <h4 _ngcontent-hmx-c7="" id="modal-title"><em _ngcontent-hmx-c7="" class="fa fa-exclamation-triangle " style="color:#cc0000;"></em> ERROR</h4><button onclick="close_modal();" _ngcontent-hmx-c7="" aria-label="Close" class="close" type="button"><span _ngcontent-hmx-c7="" aria-hidden="true">×</span></button>
                        <!---->
                    </div>
                    <div id="cont-modal-body"></div>
                </div>
            </div>
        </div>
    </div>
</sigep-mensaje>
<bs-modal-backdrop class="modal-backdrop fade in show" id="back-modal" style="display: none;"></bs-modal-backdrop>


<script>
    function open_modal(title_modal) {
        var modal_box = document.getElementById("modal-box");
        var back_modal = document.getElementById("back-modal");
        var modal_title = document.getElementById("modal-title");

        modal_title.innerHTML = title_modal;
        modal_box.style.display = 'block';
        modal_box.classList.add("in");
        modal_box.classList.add("show");
        back_modal.style.display = 'block';
    }

    function close_modal() {
        var modal_box = document.getElementById("modal-box");
        var back_modal = document.getElementById("back-modal");

        modal_box.style.display = 'none';
        modal_box.classList.remove("in");
        modal_box.classList.remove("show");
        back_modal.style.display = 'none';

        document.getElementById("cont-modal-body").innerHTML = '';
    }

    function comportamiento_subasta(id_usuario,item) {
        open_modal('COMPORTAMIENTO DE USUARIO');
        document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
        fetch('contenido/pages/ajax/adm.panel-subastas.comportamiento_subasta.php?items=true&id_usuario=' + id_usuario + '&item='+item)
            .then(function(response) {
                return response.text();
            })
            .then(function(text) {
                document.getElementById("cont-modal-body").innerHTML = text;
            })
            .catch(function(error) {
                log('Request failed', error)
            });
    }
</script>
<script>
function selec_tab(num){
    for (let index = 1; index <=4; index++) {
        document.getElementById("tab-cont"+index).classList.remove('active');
        document.getElementById("cont"+index).classList.remove('show');
        document.getElementById("cont"+index).classList.remove('active');
        if(index==num){
            document.getElementById("tab-cont"+index).classList.add('active');
            document.getElementById("cont"+index).classList.add('show');
            document.getElementById("cont"+index).classList.add('active');
        }
    }
}
</script>

