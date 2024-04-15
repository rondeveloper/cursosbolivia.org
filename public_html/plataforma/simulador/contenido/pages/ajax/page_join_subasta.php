<?php

$mod = $_GET['mod'];
?>

<router-outlet _ngcontent-jbq-c1=""></router-outlet>
<procesos-subasta-list-screen _nghost-jbq-c12="">
    <div _ngcontent-lkh-c21="" class="content-heading p5">
        <div _ngcontent-lkh-c21="" class="row w-100">
            <div _ngcontent-lkh-c21="" class="row pt-5 col-12 d-md-none"></div>
            <div _ngcontent-lkh-c21="" class="col-lg-5 col-12 pt10">
                <div _ngcontent-lkh-c21="" class="row">
                    <div _ngcontent-lkh-c21="" class="col-12"> Sala de Subasta </div>
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
                    <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">Datos Generales</div>
                    <span style="font-size: 20px;text-transform: uppercase;">
                    <?php 
                    if($mod=='mod2'){
                        echo 'ADQUISICION INSUMOS DE BIOSEGURIDAD';
                    }else{
                        echo 'ADQUISICION DE 1.600 PIEZAS DE TRAJES DE BIOSEGURIDAD SOLICITADO POR LA JEFATURA REGIONAL DE ENFERMERIA';
                    }
                    ?>
                    </span>
                </div>
                <div _ngcontent-jbq-c12="" class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <span style="font-size: 11pt;">
                            Cuce: 
                            <?php 
                            if($mod=='mod2'){
                                echo '21-0293-00-1120509-1-1';
                            }else{
                                echo '21-0417-06-1121995-2-1';
                            }
                            ?>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                            <?php 
                            if($mod=='mod2'){
                                echo 'Forma de Adjudicaci&oacute;n: Por Items';
                            }else{
                                echo 'Forma de Adjudicaci&oacute;n: Por el Total';
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</procesos-subasta-list-screen>

<br>
<br>

<div style="background: #d4d4d4;
    padding: 15px 25px;
    font-size: 12pt;
    color: #000;
    border: 1px solid #b5b5b5;
    border-radius: 5px;">
    <i class="fa fa-exclamation"></i> &nbsp; La subasta iniciar&aacute; el <?php echo date("d/m/Y H:i"); ?>
</div>

<br>
<br>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8" id="cont-subasta">
        Tiempo restante para iniciar los lances.
        <br>
        <br>
        <div class="row">
            <div class="col-md-3 col-xs-3 col-sm-3 text-center">
                <b style="font-size: 15pt;">DIAS</b>
                <br>
                <div style="background: #FFF;text-align: center;color:#005aff;font-size: 35pt; border:1px solid #c5c5c5;border-radius: 5px;">0</div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3 text-center">
                <b style="font-size: 15pt;">HORAS</b>
                <br>
                <div style="background: #FFF;text-align: center;color:#005aff;font-size: 35pt; border:1px solid #c5c5c5;border-radius: 5px;">0</div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3 text-center">
                <b style="font-size: 15pt;">MINUTOS</b>
                <br>
                <div style="background: #FFF;text-align: center;color:#005aff;font-size: 35pt; border:1px solid #c5c5c5;border-radius: 5px;">0</div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3 text-center">
                <b style="font-size: 15pt;">SEGUNDOS</b>
                <br>
                <div style="background: #FFF;text-align: center;color:#005aff;font-size: 35pt; border:1px solid #c5c5c5;border-radius: 5px;" id="counter">7</div>
            </div>
        </div>
    </div>
</div>

<div id="panel-historial-subsata"></div>

<br>
<br>
<br>
<br>
<br>
<br>