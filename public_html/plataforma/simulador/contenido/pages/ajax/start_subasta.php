<?php

//echo "123test";
?>

<div class="text-right">
    <em _ngcontent-hmx-c4="" class="icon-refresh" style="font-size: 30px;
    background: #ececec;
    padding: 10px;
    border-radius: 10px;" onclick="actualizar_estado_subasta();"></em>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <div id="panel-estado-subasta">
            <b style="font-size: 27pt;">Bs 500000</b>
            <br>
            <div style="display: flex;justify-content: center;margin-top: 10px;margin-bottom: 10px;">
                <div style="background: red;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div style="background: gray;width: 70px;height: 70px;border-radius: 50%;">&nbsp;</div>
            </div>
        </div>
        <br>
        <div style="background: #FFF;text-align: left;border:1px solid #c5c5c5;border-radius: 5px;padding: 20px;font-size:15pt;">

            <b>ENVIAR PROPUESTA:</b>
            <hr>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <b>Monto:</b>
                    <br>
                    <input type="number" value="500000" class="form-control" placeholder="" id="monto-propuesta" style="font-size: 20pt;
    padding: 10px 20px;
    height: auto;
    border: 1px solid #c5d1f9;
    box-shadow: inset 0px 0px 5px #BBB !important;" />
                    <br>
                    <div class="text-center">
                        <b class="btn btn-success btn-lg" onclick="enviar_propuesta();" id="btn_envprop">ENVIAR PROPUESTA</b>
                    </div>
                </div>
            </div>

            <br>
            <br>


        </div>
    </div>
</div>