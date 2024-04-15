<?php

/* inicio de coneccion */
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* cargado de App Khipu */
require 'autoload.php';

//$receiver_id = 148631;
//$secret = '4ca3f92e7c99f8935c5e49c44128a147a4c1fa50';
$receiver_id = 148527;
$secret = 'e5b6e2baf2062298a89c93efde60ab34b615c0cf';

if(isset_post('post__api_version') && isset_post('post__notification_token')){
    /* from infosicoes */
    $api_version = post('post__api_version');  // Parámetro api_version
    $notification_token = post('post__notification_token'); //Parámetro notification_token
}else{
    $api_version = $_REQUEST['api_version'];  // Parámetro api_version
    $notification_token = mysql_real_escape_string($_REQUEST['notification_token']); //Parámetro notification_token
}


/* Busqueda de cobro */ 
$rqc1 = query("SELECT * FROM khipu_cobros WHERE notification_token='$notification_token' ORDER BY id DESC limit 1 ");
if(mysql_num_rows($rqc1)==0){
    mail("brayan.desteco@gmail.com","Khipu alert [0] -- no se encontro notification_token","--->".  print_r($_REQUEST,true));
    exit;
}
$rqc2 = mysql_fetch_array($rqc1);

$amount = $rqc2['amount'];
$id_cobro = $rqc2['id'];

try {
    if ($api_version == '1.3') {
        $configuration = new Khipu\Configuration();
        $configuration->setSecret($secret);
        $configuration->setReceiverId($receiver_id);
        //$configuration->setDebug(true);

        $client = new Khipu\ApiClient($configuration);
        $payments = new Khipu\Client\PaymentsApi($client);

        $response = $payments->paymentsGet($notification_token);
        if ($response->getReceiverId() == $receiver_id) {
            if ($response->getStatus() == 'done' && $response->getAmount() == $amount) {
                // marcar el pago como completo y entregar el bien o servicio
                
                mail("brayan.desteco@gmail.com","Khipu alert [0] -- pago correcto","--->".  print_r($_REQUEST,true));
                
                /* cambio de estado en la base de datos */
                query("UPDATE khipu_cobros SET estado='1' WHERE id='$id_cobro' ORDER BY id DESC limit 1 ");
                query("UPDATE cursos_proceso_registro SET sw_pago_enviado='1',paydata_fecha=NOW(),paydata_id_administrador='1' WHERE id_cobro_khipu='$id_cobro' ");
            }else{
                mail("brayan.desteco@gmail.com","Khipu alert [0] -- monto no coincide","--->".  print_r($_REQUEST,true));
            }
        } else {
            // receiver_id no coincide
            
            mail("brayan.desteco@gmail.com","Khipu alert [0] -- receiver_id no coincide","--->".  print_r($_REQUEST,true));
            
        }
    } else {
        // Usar versión anterior de la API de notificación
        mail("brayan.desteco@gmail.com","Khipu alert [0] -- version de api no coincide","--->".  print_r($_REQUEST,true));
    }
} catch (\Khipu\ApiException $exception) {
    print_r($exception->getResponseObject());
}
        


