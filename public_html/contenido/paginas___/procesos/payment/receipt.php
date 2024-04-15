<?php
include 'security.php';

$signature = $_REQUEST['signature'];
$request_token = $_REQUEST['request_token'];
$req_transaction_uuid = $_REQUEST['req_transaction_uuid'];
$decision = $_REQUEST['decision'];

$sw_pagocorrecto = false;
if ($signature == 'ACCEPT') {
    $sw_pagocorrecto = true;
}
?>

<html>
    <head>
        <title>Payment Return</title>
        <link rel="stylesheet" type="text/css" href="payment.css"/>
    </head>
    <body>
        <?php if ($sw_pagocorrecto) { ?>
            <div style="text-align: center;font-family: arial;padding: 50px;">
                <b style="
                   color: #177a90;
                   font-size: 20pt;
                   ">PAGO REALIZADO</b>
                <hr>
                <p>El pago fue completado correctamente.</p>
            </div>
        <?php } else { ?>
            <div style="text-align: center;font-family: arial;padding: 50px;">
                <b style="
                   color: #177a90;
                   font-size: 20pt;
                   ">ERROR</b>
                <hr>
                <p>No se completo el pago.</p>
            </div>
        <?php } ?>
    </body>
</html>
