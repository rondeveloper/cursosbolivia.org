<?php
//require __DIR__ . '/vendor/autoload.php';
require 'autoload.php';

$receiver_id = 148631;
$secret = '4ca3f92e7c99f8935c5e49c44128a147a4c1fa50';

$api_version = $_POST['api_version'];  // Parámetro api_version
$notification_token = $_POST['notification_token']; //Parámetro notification_token

$amount = 21;

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
                
                mail("brayan.desteco@gmail.com","Khipu alert 0 -- pago correcto","--->".  print_r($_POST,true));
                
            }else{
                mail("brayan.desteco@gmail.com","Khipu alert 0 -- monto no coincide","--->".  print_r($_POST,true));
            }
        } else {
            // receiver_id no coincide
            
            mail("brayan.desteco@gmail.com","Khipu alert 0 -- receiver_id no coincide","--->".  print_r($_POST,true));
            
        }
    } else {
        // Usar versión anterior de la API de notificación
        mail("brayan.desteco@gmail.com","Khipu alert 0 -- version de api no coincide","--->".  print_r($_POST,true));
    }
} catch (\Khipu\ApiException $exception) {
    print_r($exception->getResponseObject());
}
        








//***





$c = new Khipu\Configuration();
$c->setSecret("4ca3f92e7c99f8935c5e49c44128a147a4c1fa50");
$c->setReceiverId(148631);
$c->setDebug(true);

$cl = new Khipu\ApiClient($c);

$expires_date = new DateTime();
$expires_date->setDate(2017, 10, 31);

$kh = new Khipu\Client\PaymentsApi($cl);

$title = "Curso Infosicoes C1872";
$body = "Curso SABS publicacion de DBCs al SICOES dirigido a Servidores Publicos en La Paz | Pago de 400 bs. por concepto de inscripcion | curso a realizarse el dia 12 de Octubre | Codigo de curso: C01027B";
$monto = 370;


$picture_url = "https://www.infosicoes.com/paginas/1508758758safco3.jpg.size=5.img";
$receipt_url = "https://www.infosicoes.com/cursos.html";
$return_url = "https://www.infosicoes.com/sdk98ysa9uw498yd9hw49tys98dy9s8e4y9837y48w7ef8w39ywf980sdfusw.html";
$cancel_url = "https://www.infosicoes.com/cancelar-transaccion.html";

$notify_url = "";
$notify_api_version = "";
$transaction_id = "10000029";

try {
    $opts = array(
    	"expires_date" => $expires_date,
    	"body" => $body,
        "picture_url" => $picture_url,
        "receipt_url" => $receipt_url,
        "return_url" => $return_url,
        "cancel_url" => $cancel_url,
        "notify_url" => $notify_url,
        "notify_api_version" => $notify_api_version,
        "transaction_id" => $transaction_id
    );
    $resp = $kh->paymentsPost($title , "BOB", $monto, $opts);
    echo "<hr/>";
    print_r($resp);
    echo "<hr/>";
    $r2 = $kh->paymentsIdGet($resp->getPaymentId());
    print_r($r2);
    echo "<hr/>";
} catch(Exception $e) {
    echo $e->getMessage();
}





/*
[DEBUG] HTTP Request body ~BEGIN~ ~END~ [DEBUG] HTTP Response body ~BEGIN~ { "payment_id": "jeuspdgzmf0n", "payment_url": "https://khipu.com/payment/info/jeuspdgzmf0n", "simplified_transfer_url": "https://app.khipu.com/payment/simplified/jeuspdgzmf0n", "transfer_url": "https://khipu.com/payment/manual/jeuspdgzmf0n", "app_url": "khipu:///pos/jeuspdgzmf0n", "ready_for_terminal": false, "receiver_id": 148631, "notification_token": "bd3eadd64bf42bb0c50ebd0e8bdfd6595bf1aa800d4502f39d8e134f82f0ad19", "subject": "Pago Curso Infosicoes - C01027B", "amount": "350.0000", "discount": "0.0000", "currency": "BOB", "status": "pending", "status_detail": "pending", "body": "<br/><br/>Pago de <b>350 bs</b> por concepto de inscripcion al Curso SABS publicacion de DBCs al SICOES dirigido a Servidores Publicos en La Paz a realizarse el dia 12 de Octubre - Codigo de curso: C01027B", "picture_url": "", "receipt_url": "", "return_url": "", "cancel_url": "", "notify_url": "", "notify_api_version": "", "expires_date": "2017-10-31T18:37:17.000Z", "attachment_urls": [ ], "bank": "", "bank_id": "", "payer_name": "", "payer_email": "", "personal_identifier": "", "bank_account_number": "", "out_of_date_conciliation": false, "transaction_id": "", "custom": "", "responsible_user_email": "desteco@gmail.com", "send_reminders": false, "send_email": false, "payment_method": "not_available" } ~END~ * Hostname was found in DNS cache * Trying 50.22.89.18... * Connected to khipu.com (50.22.89.18) port 443 (#0) * successfully set certificate verify locations: * CAfile: /etc/pki/tls/certs/ca-bundle.crt CApath: none * SSL connection using TLSv1.2 / ECDHE-RSA-AES128-GCM-SHA256 * Server certificate: * subject: serialNumber=76187287-7; 1.3.6.1.4.1.311.60.2.1.3=CL; businessCategory=Private Organization; C=CL; postalCode=7510093; ST=RM; L=Santiago; street=Las Urbinas 53 of 132; O=Khipu SpA; OU=Hosted by MACROSEGURIDAD.ORG CORPORATION; OU=COMODO EV SGC SSL; CN=khipu.com * start date: 2017-01-12 00:00:00 GMT * expire date: 2019-03-23 23:59:59 GMT * subjectAltName: khipu.com matched * issuer: C=GB; ST=Greater Manchester; L=Salford; O=COMODO CA Limited; CN=COMODO RSA Extended Validation Secure Server CA * SSL certificate verify ok. > GET /api/2.0/payments/jeuspdgzmf0n HTTP/1.1 User-Agent: khipu-api-php-client/2.7.1 Host: khipu.com Accept: application/json Content-Type: application/x-www-form-urlencoded Authorization: 148631:3bbeddde7d46c194a0c470d17c6b64a5b31f817203e2d1ac38265485feb8f0d3 < HTTP/1.1 200 OK < Set-Cookie: JSESSIONID=s3~03C8E28E9C317CFC25999119E655128B; Path=/; Secure; HttpOnly < Content-Type: application/json;charset=utf-8 < Transfer-Encoding: chunked < Date: Tue, 24 Oct 2017 18:37:18 GMT < Strict-Transport-Security: max-age=15552001; includeSubDomains; preload < Public-Key-Pins: pin-sha256="hfRJH9Kjsrp2Y5Rv63cwMdx5rejA+NhvHxbBYQg1HN8="; pin-sha256="I9Zj6V9noMj6raXcyE/E3vqP7tnFvm4zA8sJk3xjXUU="; pin-sha256="jM7I1YvUMRSTP3UoqGULbh0AIQwwfZLG2QGBTkAYs/4="; max-age=5184000 < X-XSS-Protection: 1; mode=block < X-Content-Type-Options: nosniff < * Connection #0 to host khipu.com left intact Khipu\Model\PaymentsResponse Object ( [payment_id:protected] => jeuspdgzmf0n [payment_url:protected] => https://khipu.com/payment/info/jeuspdgzmf0n [simplified_transfer_url:protected] => https://app.khipu.com/payment/simplified/jeuspdgzmf0n [transfer_url:protected] => https://khipu.com/payment/manual/jeuspdgzmf0n [app_url:protected] => khipu:///pos/jeuspdgzmf0n [ready_for_terminal:protected] => [notification_token:protected] => bd3eadd64bf42bb0c50ebd0e8bdfd6595bf1aa800d4502f39d8e134f82f0ad19 [receiver_id:protected] => 148631 [conciliation_date:protected] => [subject:protected] => Pago Curso Infosicoes - C01027B [amount:protected] => 350 [currency:protected] => BOB [status:protected] => pending [status_detail:protected] => pending [body:protected] => <br/><br/>Pago de <b>350 bs</b> por concepto de inscripcion al Curso SABS publicacion de DBCs al SICOES dirigido a Servidores Publicos en La Paz a realizarse el dia 12 de Octubre - Codigo de curso: C01027B [picture_url:protected] => [receipt_url:protected] => [return_url:protected] => [cancel_url:protected] => [notify_url:protected] => [notify_api_version:protected] => [expires_date:protected] => DateTime Object ( [date] => 2017-10-31 18:37:17.000000 [timezone_type] => 2 [timezone] => Z ) [attachment_urls:protected] => Array ( ) [bank:protected] => [bank_id:protected] => [payer_name:protected] => [payer_email:protected] => [personal_identifier:protected] => [bank_account_number:protected] => [out_of_date_conciliation:protected] => [transaction_id:protected] => [custom:protected] => [responsible_user_email:protected] => desteco@gmail.com [send_reminders:protected] => [send_email:protected] => [payment_method:protected] => not_available ) 
 */
?>


