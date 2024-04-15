<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$id_banco = post('data');
$id_curso = post('cod');
$id_proceso_registro = post('id_proceso_registro');
$__valor_dolar = 6.97;

/* curso */
$rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_identificador_curso = $rqdc2['titulo_identificador'];

/* registro */
$rqdr1 = query("SELECT p.celular,c.costo,c.sw_fecha2,c.fecha2,c.costo2,c.sw_fecha3,c.fecha3,c.costo3 FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON r.id=p.id_proceso_registro INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id='$id_proceso_registro' ORDER BY r.id DESC limit 1");
$rqdr2 = fetch($rqdr1);
$celular_participante = $rqdr2['celular'];
$monto_a_pagar = $rqdr2['costo'];
if ($rqdr2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqdr2['fecha2'])) {
    $monto_a_pagar = $rqdr2['costo2'];
}
if ($rqdr2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqdr2['fecha3'])) {
    $monto_a_pagar = $rqdr2['costo3'];
}
$wap_codigo_pais = '591';

$url_proc_registro = $dominio . 'registro-curso-p5c/' . md5('idr-' . $id_proceso_registro) . '/' . $id_proceso_registro . '.html';
?>
<style>
    .img-det-fpay{
        width: 90%;border: 1px solid #d2d2d2;padding: 4px;border-radius: 5px;
    }
    .titl-det-fpay{
        font-size: 17pt;background: #f7f7f7;padding: 10px;border: 1px solid gainsboro;
    }
    .num-det-fpay{
        text-align: center;font-size: 30pt;margin-bottom: 10px;padding: 15px 0px;color: #0951bd;font-weight: bold;
    }
    .link-set-fpay{
        cursor: pointer;
        color: #ffffff;
        text-decoration: underline;
        background: #2369ea;
        padding: 15px 25px;
        font-size: 15pt;
        border-radius: 7px;
        border: 2px solid #70cce4;
    }
    .link-set-fpay:hover{
        color: #ffffff;
        background: #12b12c;
        border: 2px solid #17c8f7;
    }
    .tabla-det-fpay th{
        font-size: 8pt !important;
        font-weight: bold !important;
    }
    .tabla-det-fpay td{
        padding: 10px !important;
    }
    .bt-save-info{
        font-size: 9pt;
        padding: 5px 8px;
        background: #1b81ff;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .td-numcuenta{
        font-size: 14pt;
    }
    @media (max-width: 500px){
        .titl-det-fpay {
            font-size: 12pt;
            line-height: 1.1;
        }
        .td-numcuenta{
            font-size: 12pt;
        }
    }
</style>
<?php
if ($id_banco == 'tigomoney') {
    $txt_cont_wap = 'El monto a pagar por el curso es de: ' . $monto_a_pagar . ' BS . Puede realizar el pago mediante TIGO MONEY al siguiente n&uacute;mero:__ __69714008__ __El costo es sin recargo (Titular Edgar Aliaga) . Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:__ __' . $url_proc_registro;
    $txt_cont_wap = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $txt_cont_wap)));
    ?>
    <div id="info-pago" style="display:none;">
        <p>El monto a pagar por el curso es de: <?php echo $monto_a_pagar; ?> BS . Puede realizar el pago mediante TIGO MONEY al siguiente n&uacute;mero: </p>
        <h2>69714008</h2>
        <p>El costo es sin recargo (Titular Edgar Aliaga) . Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:</p>
        <a href="<?php echo $url_proc_registro; ?>"><?php echo $url_proc_registro; ?></a>
    </div>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs hidden-sm">
                <img src="contenido/imagenes/bancos/tigo-money.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO POR TIGOMONEY
                </div>
                <br>
                <p>El monto a pagar es de: <i><?php echo $monto_a_pagar; ?> BS</i> . Puede realizar el pago mediante TIGO MONEY al siguiente n&uacute;mero: </p>
                <div class="num-det-fpay">69714008</div>
                <p>El costo es sin recargo (Titular Edgar Aliaga) . Luego de haber realizado el pago debe subir una imagen del comprobante presionando el siguiente bot&oacute;n:</p>
                <br>
                <b onclick="subir_comprobante();" class="link-set-fpay">REPORTAR PAGO</b>
                <br>
                <br>
                <hr>
                <b class="btn btn-xs btn-info bt-save-info" onclick="copyToClipboard('info-pago');"><i class="icon-copy text-contrast"></i> Copiar esta Info al portapapeles</b>
                <a class="btn btn-xs btn-info bt-save-info" href="https://api.whatsapp.com/send?phone=<?php echo $wap_codigo_pais.$celular_participante; ?>&text=<?php echo $txt_cont_wap; ?>" target="_blank">WHATSAPP enviarme esta Info</a>
                <b class="btn btn-xs btn-info bt-save-info" onclick="alert('EN DESARROLLO');"><i class="fa fa-envelope"></i> enviarme esta Info por correo</b>
            </div>
        </div>
    </div>
    <?php
} elseif (($id_banco == 'visa' || $id_banco == 'mastercard') && false) {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs hidden-sm">
                <img src="contenido/imagenes/bancos/<?php echo $id_banco; ?>.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO POR TARJETA DE DEBITO / CREDITO
                </div>
                <br>
                <p>
                    El monto a pagar es de: <i><?php echo $monto_a_pagar; ?> BS</i> . Puede realizar el pago mediante tarjeta de credito o debito, su tarjeta debe estar habilitada para compras por internet.
                    <br>
                    <i>SOLICITE A SU ENTIDAD FINANCIERA LA HABILITACION DE BANCA POR INTERNET</i>
                </p>
                <br>
                <div class="row" id="ajaxbox-pagotarjeta">
                    <div class="col-md-12">
                        <div style="padding: 20px 7px 35px 7px;background: #f7f7f7;border-radius: 10px;border: 1px solid #dcdcdc;margin-bottom: 30px;">
                            <div class="input-group" style="margin-bottom: 25px;">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> BS</span>
                                <input type="number" name="" id="monto-BS" min="1" value="<?php echo $monto_a_pagar; ?>" class="form-control"/>
                            </div>
                            <b onclick="pago_tarjeta('bs');" class="link-set-fpay" style="font-size: 12pt;padding: 8px 15px;text-decoration: none;">REALIZAR PAGO</b>
                        </div>
                    </div>
                </div>
                <br>
                &nbsp;
            </div>
        </div>
    </div>
    <?php
} elseif ($id_banco == 'paypal' && true) {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs hidden-sm">
                <img src="contenido/imagenes/bancos/paypal.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO MEDIANTE PAYPAL
                </div>
                <br>
                <p>
                    El monto a pagar es de: <i><?php echo $monto_a_pagar; ?> BS</i> . Puede realizar el pago mediante paypal, presione el siguiente bot&oacute;n para proceder con el pago.
                </p>
                <hr>
                <?php
                if ($monto_a_pagar == 0) {
                    echo "ERROR no hay monto que pagar";
                } else {
                    ?>
                    <a href="https://www.paypal.me/nemabol/<?php echo round($monto_a_pagar / $__valor_dolar, 1) ; ?>" target="_blank">
                        <div style="background: #ffc439;padding: 10px;cursor: pointer;border-radius: 5px;border: 1px solid #4b9fab;">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAzMiIgcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pbllNaW4gbWVldCIgeG1sbnM9Imh0dHA6JiN4MkY7JiN4MkY7d3d3LnczLm9yZyYjeDJGOzIwMDAmI3gyRjtzdmciPjxwYXRoIGZpbGw9IiMwMDljZGUiIG9wYWNpdHk9IjEiIGQ9Ik0gMjAuOTI0IDcuMTU3IEMgMjEuMjA0IDUuMDU3IDIwLjkyNCAzLjY1NyAxOS44MDEgMi4zNTcgQyAxOC41ODMgMC45NTcgMTYuNDMgMC4yNTcgMTMuNzE2IDAuMjU3IEwgNS43NTggMC4yNTcgQyA1LjI5IDAuMjU3IDQuNzI5IDAuNzU3IDQuNjM0IDEuMjU3IEwgMS4zNTggMjMuNDU3IEMgMS4zNTggMjMuODU3IDEuNjM5IDI0LjM1NyAyLjEwNyAyNC4zNTcgTCA2Ljk3NSAyNC4zNTcgTCA2LjY5NCAyNi41NTcgQyA2LjYgMjYuOTU3IDYuODgxIDI3LjI1NyA3LjI1NSAyNy4yNTcgTCAxMS4zNzUgMjcuMjU3IEMgMTEuODQ0IDI3LjI1NyAxMi4zMTEgMjYuOTU3IDEyLjQwNSAyNi40NTcgTCAxMi40MDUgMjYuMTU3IEwgMTMuMjQ3IDIwLjk1NyBMIDEzLjI0NyAyMC43NTcgQyAxMy4zNDEgMjAuMjU3IDEzLjgwOSAxOS44NTcgMTQuMjc3IDE5Ljg1NyBMIDE0Ljg0IDE5Ljg1NyBDIDE4Ljg2NCAxOS44NTcgMjEuOTU0IDE4LjE1NyAyMi44OSAxMy4xNTcgQyAyMy4zNTggMTEuMDU3IDIzLjE3MiA5LjM1NyAyMi4wNDggOC4xNTcgQyAyMS43NjcgNy43NTcgMjEuMjk4IDcuNDU3IDIwLjkyNCA3LjE1NyBMIDIwLjkyNCA3LjE1NyI+PC9wYXRoPjxwYXRoIGZpbGw9IiMwMTIxNjkiIG9wYWNpdHk9IjEiIGQ9Ik0gMjAuOTI0IDcuMTU3IEMgMjEuMjA0IDUuMDU3IDIwLjkyNCAzLjY1NyAxOS44MDEgMi4zNTcgQyAxOC41ODMgMC45NTcgMTYuNDMgMC4yNTcgMTMuNzE2IDAuMjU3IEwgNS43NTggMC4yNTcgQyA1LjI5IDAuMjU3IDQuNzI5IDAuNzU3IDQuNjM0IDEuMjU3IEwgMS4zNTggMjMuNDU3IEMgMS4zNTggMjMuODU3IDEuNjM5IDI0LjM1NyAyLjEwNyAyNC4zNTcgTCA2Ljk3NSAyNC4zNTcgTCA4LjI4NiAxNi4wNTcgTCA4LjE5MiAxNi4zNTcgQyA4LjI4NiAxNS43NTcgOC43NTQgMTUuMzU3IDkuMzE1IDE1LjM1NyBMIDExLjY1NSAxNS4zNTcgQyAxNi4yNDMgMTUuMzU3IDE5LjgwMSAxMy4zNTcgMjAuOTI0IDcuNzU3IEMgMjAuODMxIDcuNDU3IDIwLjkyNCA3LjM1NyAyMC45MjQgNy4xNTciPjwvcGF0aD48cGF0aCBmaWxsPSIjMDAzMDg3IiBvcGFjaXR5PSIxIiBkPSJNIDkuNTA0IDcuMTU3IEMgOS41OTYgNi44NTcgOS43ODQgNi41NTcgMTAuMDY1IDYuMzU3IEMgMTAuMjUxIDYuMzU3IDEwLjM0NSA2LjI1NyAxMC41MzIgNi4yNTcgTCAxNi43MTEgNi4yNTcgQyAxNy40NjEgNi4yNTcgMTguMjA4IDYuMzU3IDE4Ljc3MiA2LjQ1NyBDIDE4Ljk1OCA2LjQ1NyAxOS4xNDYgNi40NTcgMTkuMzMzIDYuNTU3IEMgMTkuNTIgNi42NTcgMTkuNzA3IDYuNjU3IDE5LjgwMSA2Ljc1NyBDIDE5Ljg5NCA2Ljc1NyAxOS45ODcgNi43NTcgMjAuMDgyIDYuNzU3IEMgMjAuMzYyIDYuODU3IDIwLjY0MyA3LjA1NyAyMC45MjQgNy4xNTcgQyAyMS4yMDQgNS4wNTcgMjAuOTI0IDMuNjU3IDE5LjgwMSAyLjI1NyBDIDE4LjY3NyAwLjg1NyAxNi41MjUgMC4yNTcgMTMuODA5IDAuMjU3IEwgNS43NTggMC4yNTcgQyA1LjI5IDAuMjU3IDQuNzI5IDAuNjU3IDQuNjM0IDEuMjU3IEwgMS4zNTggMjMuNDU3IEMgMS4zNTggMjMuODU3IDEuNjM5IDI0LjM1NyAyLjEwNyAyNC4zNTcgTCA2Ljk3NSAyNC4zNTcgTCA4LjI4NiAxNi4wNTcgTCA5LjUwNCA3LjE1NyBaIj48L3BhdGg+PC9zdmc+" data-v-3716e015="" alt="" class="paypal-logo paypal-logo-pp paypal-logo-color-blue"><span class="paypal-button-space"> </span><img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMTAwIDMyIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWluWU1pbiBtZWV0IiB4bWxucz0iaHR0cDomI3gyRjsmI3gyRjt3d3cudzMub3JnJiN4MkY7MjAwMCYjeDJGO3N2ZyI+PHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSAxMi4yMzcgMi40NDQgTCA0LjQzNyAyLjQ0NCBDIDMuOTM3IDIuNDQ0IDMuNDM3IDIuODQ0IDMuMzM3IDMuMzQ0IEwgMC4yMzcgMjMuMzQ0IEMgMC4xMzcgMjMuNzQ0IDAuNDM3IDI0LjA0NCAwLjgzNyAyNC4wNDQgTCA0LjUzNyAyNC4wNDQgQyA1LjAzNyAyNC4wNDQgNS41MzcgMjMuNjQ0IDUuNjM3IDIzLjE0NCBMIDYuNDM3IDE3Ljc0NCBDIDYuNTM3IDE3LjI0NCA2LjkzNyAxNi44NDQgNy41MzcgMTYuODQ0IEwgMTAuMDM3IDE2Ljg0NCBDIDE1LjEzNyAxNi44NDQgMTguMTM3IDE0LjM0NCAxOC45MzcgOS40NDQgQyAxOS4yMzcgNy4zNDQgMTguOTM3IDUuNjQ0IDE3LjkzNyA0LjQ0NCBDIDE2LjgzNyAzLjE0NCAxNC44MzcgMi40NDQgMTIuMjM3IDIuNDQ0IFogTSAxMy4xMzcgOS43NDQgQyAxMi43MzcgMTIuNTQ0IDEwLjUzNyAxMi41NDQgOC41MzcgMTIuNTQ0IEwgNy4zMzcgMTIuNTQ0IEwgOC4xMzcgNy4zNDQgQyA4LjEzNyA3LjA0NCA4LjQzNyA2Ljg0NCA4LjczNyA2Ljg0NCBMIDkuMjM3IDYuODQ0IEMgMTAuNjM3IDYuODQ0IDExLjkzNyA2Ljg0NCAxMi42MzcgNy42NDQgQyAxMy4xMzcgOC4wNDQgMTMuMzM3IDguNzQ0IDEzLjEzNyA5Ljc0NCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSAzNS40MzcgOS42NDQgTCAzMS43MzcgOS42NDQgQyAzMS40MzcgOS42NDQgMzEuMTM3IDkuODQ0IDMxLjEzNyAxMC4xNDQgTCAzMC45MzcgMTEuMTQ0IEwgMzAuNjM3IDEwLjc0NCBDIDI5LjgzNyA5LjU0NCAyOC4wMzcgOS4xNDQgMjYuMjM3IDkuMTQ0IEMgMjIuMTM3IDkuMTQ0IDE4LjYzNyAxMi4yNDQgMTcuOTM3IDE2LjY0NCBDIDE3LjUzNyAxOC44NDQgMTguMDM3IDIwLjk0NCAxOS4zMzcgMjIuMzQ0IEMgMjAuNDM3IDIzLjY0NCAyMi4xMzcgMjQuMjQ0IDI0LjAzNyAyNC4yNDQgQyAyNy4zMzcgMjQuMjQ0IDI5LjIzNyAyMi4xNDQgMjkuMjM3IDIyLjE0NCBMIDI5LjAzNyAyMy4xNDQgQyAyOC45MzcgMjMuNTQ0IDI5LjIzNyAyMy45NDQgMjkuNjM3IDIzLjk0NCBMIDMzLjAzNyAyMy45NDQgQyAzMy41MzcgMjMuOTQ0IDM0LjAzNyAyMy41NDQgMzQuMTM3IDIzLjA0NCBMIDM2LjEzNyAxMC4yNDQgQyAzNi4yMzcgMTAuMDQ0IDM1LjgzNyA5LjY0NCAzNS40MzcgOS42NDQgWiBNIDMwLjMzNyAxNi44NDQgQyAyOS45MzcgMTguOTQ0IDI4LjMzNyAyMC40NDQgMjYuMTM3IDIwLjQ0NCBDIDI1LjAzNyAyMC40NDQgMjQuMjM3IDIwLjE0NCAyMy42MzcgMTkuNDQ0IEMgMjMuMDM3IDE4Ljc0NCAyMi44MzcgMTcuODQ0IDIzLjAzNyAxNi44NDQgQyAyMy4zMzcgMTQuNzQ0IDI1LjEzNyAxMy4yNDQgMjcuMjM3IDEzLjI0NCBDIDI4LjMzNyAxMy4yNDQgMjkuMTM3IDEzLjY0NCAyOS43MzcgMTQuMjQ0IEMgMzAuMjM3IDE0Ljk0NCAzMC40MzcgMTUuODQ0IDMwLjMzNyAxNi44NDQgWiI+PC9wYXRoPjxwYXRoIGZpbGw9IiMwMDMwODciIGQ9Ik0gNTUuMzM3IDkuNjQ0IEwgNTEuNjM3IDkuNjQ0IEMgNTEuMjM3IDkuNjQ0IDUwLjkzNyA5Ljg0NCA1MC43MzcgMTAuMTQ0IEwgNDUuNTM3IDE3Ljc0NCBMIDQzLjMzNyAxMC40NDQgQyA0My4yMzcgOS45NDQgNDIuNzM3IDkuNjQ0IDQyLjMzNyA5LjY0NCBMIDM4LjYzNyA5LjY0NCBDIDM4LjIzNyA5LjY0NCAzNy44MzcgMTAuMDQ0IDM4LjAzNyAxMC41NDQgTCA0Mi4xMzcgMjIuNjQ0IEwgMzguMjM3IDI4LjA0NCBDIDM3LjkzNyAyOC40NDQgMzguMjM3IDI5LjA0NCAzOC43MzcgMjkuMDQ0IEwgNDIuNDM3IDI5LjA0NCBDIDQyLjgzNyAyOS4wNDQgNDMuMTM3IDI4Ljg0NCA0My4zMzcgMjguNTQ0IEwgNTUuODM3IDEwLjU0NCBDIDU2LjEzNyAxMC4yNDQgNTUuODM3IDkuNjQ0IDU1LjMzNyA5LjY0NCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny43MzcgMi40NDQgTCA1OS45MzcgMi40NDQgQyA1OS40MzcgMi40NDQgNTguOTM3IDIuODQ0IDU4LjgzNyAzLjM0NCBMIDU1LjczNyAyMy4yNDQgQyA1NS42MzcgMjMuNjQ0IDU1LjkzNyAyMy45NDQgNTYuMzM3IDIzLjk0NCBMIDYwLjMzNyAyMy45NDQgQyA2MC43MzcgMjMuOTQ0IDYxLjAzNyAyMy42NDQgNjEuMDM3IDIzLjM0NCBMIDYxLjkzNyAxNy42NDQgQyA2Mi4wMzcgMTcuMTQ0IDYyLjQzNyAxNi43NDQgNjMuMDM3IDE2Ljc0NCBMIDY1LjUzNyAxNi43NDQgQyA3MC42MzcgMTYuNzQ0IDczLjYzNyAxNC4yNDQgNzQuNDM3IDkuMzQ0IEMgNzQuNzM3IDcuMjQ0IDc0LjQzNyA1LjU0NCA3My40MzcgNC4zNDQgQyA3Mi4yMzcgMy4xNDQgNzAuMzM3IDIuNDQ0IDY3LjczNyAyLjQ0NCBaIE0gNjguNjM3IDkuNzQ0IEMgNjguMjM3IDEyLjU0NCA2Ni4wMzcgMTIuNTQ0IDY0LjAzNyAxMi41NDQgTCA2Mi44MzcgMTIuNTQ0IEwgNjMuNjM3IDcuMzQ0IEMgNjMuNjM3IDcuMDQ0IDYzLjkzNyA2Ljg0NCA2NC4yMzcgNi44NDQgTCA2NC43MzcgNi44NDQgQyA2Ni4xMzcgNi44NDQgNjcuNDM3IDYuODQ0IDY4LjEzNyA3LjY0NCBDIDY4LjYzNyA4LjA0NCA2OC43MzcgOC43NDQgNjguNjM3IDkuNzQ0IFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDA5Y2RlIiBkPSJNIDkwLjkzNyA5LjY0NCBMIDg3LjIzNyA5LjY0NCBDIDg2LjkzNyA5LjY0NCA4Ni42MzcgOS44NDQgODYuNjM3IDEwLjE0NCBMIDg2LjQzNyAxMS4xNDQgTCA4Ni4xMzcgMTAuNzQ0IEMgODUuMzM3IDkuNTQ0IDgzLjUzNyA5LjE0NCA4MS43MzcgOS4xNDQgQyA3Ny42MzcgOS4xNDQgNzQuMTM3IDEyLjI0NCA3My40MzcgMTYuNjQ0IEMgNzMuMDM3IDE4Ljg0NCA3My41MzcgMjAuOTQ0IDc0LjgzNyAyMi4zNDQgQyA3NS45MzcgMjMuNjQ0IDc3LjYzNyAyNC4yNDQgNzkuNTM3IDI0LjI0NCBDIDgyLjgzNyAyNC4yNDQgODQuNzM3IDIyLjE0NCA4NC43MzcgMjIuMTQ0IEwgODQuNTM3IDIzLjE0NCBDIDg0LjQzNyAyMy41NDQgODQuNzM3IDIzLjk0NCA4NS4xMzcgMjMuOTQ0IEwgODguNTM3IDIzLjk0NCBDIDg5LjAzNyAyMy45NDQgODkuNTM3IDIzLjU0NCA4OS42MzcgMjMuMDQ0IEwgOTEuNjM3IDEwLjI0NCBDIDkxLjYzNyAxMC4wNDQgOTEuMzM3IDkuNjQ0IDkwLjkzNyA5LjY0NCBaIE0gODUuNzM3IDE2Ljg0NCBDIDg1LjMzNyAxOC45NDQgODMuNzM3IDIwLjQ0NCA4MS41MzcgMjAuNDQ0IEMgODAuNDM3IDIwLjQ0NCA3OS42MzcgMjAuMTQ0IDc5LjAzNyAxOS40NDQgQyA3OC40MzcgMTguNzQ0IDc4LjIzNyAxNy44NDQgNzguNDM3IDE2Ljg0NCBDIDc4LjczNyAxNC43NDQgODAuNTM3IDEzLjI0NCA4Mi42MzcgMTMuMjQ0IEMgODMuNzM3IDEzLjI0NCA4NC41MzcgMTMuNjQ0IDg1LjEzNyAxNC4yNDQgQyA4NS43MzcgMTQuOTQ0IDg1LjkzNyAxNS44NDQgODUuNzM3IDE2Ljg0NCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5NS4zMzcgMi45NDQgTCA5Mi4xMzcgMjMuMjQ0IEMgOTIuMDM3IDIzLjY0NCA5Mi4zMzcgMjMuOTQ0IDkyLjczNyAyMy45NDQgTCA5NS45MzcgMjMuOTQ0IEMgOTYuNDM3IDIzLjk0NCA5Ni45MzcgMjMuNTQ0IDk3LjAzNyAyMy4wNDQgTCAxMDAuMjM3IDMuMTQ0IEMgMTAwLjMzNyAyLjc0NCAxMDAuMDM3IDIuNDQ0IDk5LjYzNyAyLjQ0NCBMIDk2LjAzNyAyLjQ0NCBDIDk1LjYzNyAyLjQ0NCA5NS40MzcgMi42NDQgOTUuMzM3IDIuOTQ0IFoiPjwvcGF0aD48L3N2Zz4=" data-v-3716e015="" alt="" class="paypal-logo paypal-logo-paypal paypal-logo-color-blue"/>
                        </div>
                    </a>
                    <br>
                    <br>
                    <p>Luego de haber realizado el pago debe subir una imagen del comprobante presionando el siguiente bot&oacute;n:</p>
                    <br>
                    <b onclick="subir_comprobante();" class="link-set-fpay" style="padding: 5px 15px;font-size: 14pt;text-decoration: none;">REPORTAR PAGO</b>
                    <br>
                    <br>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} elseif ($id_banco == 'paypal' && false) {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs hidden-sm">
                <img src="contenido/imagenes/bancos/paypal.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO MEDIANTE PAYPAL
                </div>
                <br>
                <p>
                    El monto a pagar es de: <i><?php echo $monto_a_pagar; ?> BS</i> . Puede realizar el pago mediante paypal, presione el siguiente bot&oacute;n para proceder con el pago.
                </p>
                <hr>
                <div id="paypal-button-container"></div>
                <script>
                    paypal.Buttons({
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                        amount: {
                                            value: '<?php echo $monto_a_pagar; ?>'
                                        }
                                    }]
                            });
                        },
                        onApprove: function (data, actions) {
                            return actions.order.capture().then(function (details) {
                                paypal_payment_confirmed(details);
                                //alert('Transaction completed by ' + details.payer.name.given_name);
                            });
                        }
                    }).render('#paypal-button-container');
                </script>
                <script>
                    function paypal_payment_confirmed(details) {
                        $.ajax({
                            url: 'contenido/paginas/ajax/ajax.registro-p5c.paypal_payment_confirmed.php',
                            data: {id_proceso_registro: '<?php echo $id_proceso_registro; ?>', status: details.status, id: details.id},
                            type: 'POST',
                            dataType: 'html',
                            success: function (data) {
                                $('#paypal-button-container').html(data);
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
    <?php
} else {
    /* banco */
    $rqdb1 = query("SELECT imagen,nombre FROM bancos WHERE id='$id_banco' AND estado=1 LIMIT 1 ");
    if(num_rows($rqdb1)==0){
        echo "<h2>INHABILITADO</h2>";
        exit;
    }
    $rqdb2 = fetch($rqdb1);
    $txt_cont_wap = 'El monto a pagar por el curso es de: ' . $monto_a_pagar . ' BS . Puede realizar el pago a trav&eacute;s de ' . $rqdb2['nombre'] . ' a cualquiera de las siguientes cuentas:__ __';
    ?>
    <div id="info-pago" style="display:none;">
        <p>El monto a pagar por el curso es de: <?php echo $monto_a_pagar; ?> BS . Puede realizar el pago a trav&eacute;s de <?php echo $rqdb2['nombre']; ?> a cualquiera de las siguientes cuentas: </p>
        <table class="table table-bordered tabla-det-fpay">
            <tr>
                <th>N&uacute;mero de cuenta</th>
                <th>Titular</th>
            </tr>
            <?php
            $rqcp1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND etsado=1 ");
            while ($rqc2 = fetch($rqcp1)) {
                ?>
                <tr>
                    <td><?php echo $rqc2['numero_cuenta']; ?></td>
                    <td><?php echo $rqc2['titular']; ?></td>
                </tr>
                <?php
                $txt_cont_wap .= $rqc2['numero_cuenta'] . ' &nbsp; | &nbsp; Titular: ' . $rqc2['titular'] . '__ __';
            }
            $txt_cont_wap .= 'Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:__ __' . $url_proc_registro;
            $txt_cont_wap = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $txt_cont_wap)));
            ?>
        </table>
        <br>
        <p>Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:</p>
        <a href="<?php echo $url_proc_registro; ?>"><?php echo $url_proc_registro; ?></a>
    </div>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs hidden-sm">
                <img src="contenido/imagenes/bancos/<?php echo $rqdb2['imagen']; ?>" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    <?php echo strtoupper($rqdb2['nombre']); ?>
                </div>
                <br>
                <p>El monto a pagar es de: <i><?php echo $monto_a_pagar; ?> BS</i> . Puede realizar el pago a trav&eacute;s de <?php echo $rqdb2['nombre']; ?> a cualquiera de las siguientes cuentas:</p>
                <table class="table table-bordered tabla-det-fpay">
                    <tr>
                        <th>N&uacute;mero de cuenta</th>
                        <th>Titular</th>
                    </tr>
                    <?php
                    $rqc1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
                    while ($rqc2 = fetch($rqc1)) {
                        ?>
                        <tr>
                            <td class="td-numcuenta"><?php echo $rqc2['numero_cuenta']; ?></td>
                            <td><?php echo $rqc2['titular']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br>
                <p>Luego de haber realizado el pago debe subir una imagen del comprobante presionando el siguiente bot&oacute;n:</p>
                <br>
                <b onclick="subir_comprobante();" class="link-set-fpay">REPORTAR PAGO</b>
                <br>
                <br>
                <hr>
                <b class="btn btn-xs btn-info bt-save-info" onclick="copyToClipboard('info-pago');"><i class="icon-copy text-contrast"></i> Copiar esta Info al portapapeles</b>
                <a class="btn btn-xs btn-info bt-save-info" href="https://api.whatsapp.com/send?phone=<?php echo $wap_codigo_pais.$celular_participante; ?>&text=<?php echo $txt_cont_wap; ?>" target="_blank">WHATSAPP enviarme esta Info</a>
                <b class="btn btn-xs btn-info bt-save-info" onclick="alert('EN DESARROLLO');"><i class="fa fa-envelope"></i> enviarme esta Info por correo</b>
            </div>
        </div>
    </div>
    <?php
}
?>


<script>
    function pago_tarjeta(moneda) {
        var monto = 0;
        if (moneda === 'usd') {
            monto = $("#monto-USD").val();
        } else {
            monto = $("#monto-BS").val();
        }
        if (monto <= 0 || monto > 10000) {
            alert('El monto de pago es incorrecto');
        } else {
            //$("#ajaxbox-pagotarjeta").html('Cargando...');            
            $("#MODAL-general").modal('show');
            $("#title-MODAL-general").html('PAGO MEDIANTE TARJETA');
            $("#body-MODAL-general").html('Cargando...');
            $.ajax({
                url: 'contenido/paginas/ajax/ajax.registro-p5c.pago_tarjeta.php',
                data: {id_proceso_registro: '<?php echo $id_proceso_registro; ?>', monto: monto, moneda: moneda},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $('#body-MODAL-general').html(data);
                }
            });
        }
    }
</script>
<script>
    function subir_comprobante() {
        $("#MODAL-general").modal('show');
        $("#title-MODAL-general").html('REPORTAR PAGO');
        $("#body-MODAL-general").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-p5c.subir_comprobante.php',
            data: {cod: '<?php echo $id_curso; ?>', data: '<?php echo $id_banco; ?>', id_proceso_registro: '<?php echo $id_proceso_registro; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('#body-MODAL-general').html(data);
            }
        });
    }
</script>
<script>
    function copyToClipboard(divid) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById(divid).innerHTML;
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
        alert('Informacion copiada al portapapeles ( control + C )');
    }
</script>