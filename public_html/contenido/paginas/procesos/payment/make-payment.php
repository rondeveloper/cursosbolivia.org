<?php 
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* data pasarela */
include 'security.php';
$url_payment_secureacceptance = 'https://testsecureacceptance.cybersource.com/pay';

/* recepcion de datos POST */
$moneda = get('moneda');
$monto = get('monto');
$id_proceso_registro = get('id_proceso_registro');

/* datos de registro */
$rqdr1 = query("SELECT r.*,(p.correo)dr_correo_participante FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON p.id_proceso_registro=r.id WHERE r.id='$id_proceso_registro' ORDER BY r.id DESC limit 1 ");
$registro_curso = fetch($rqdr1);

$aux_monto_a_pagar = $registro_curso['aux_monto_a_pagar'];
$id_curso = $registro_curso['id_curso'];
$correo_proceso_registro = $registro_curso['dr_correo_participante'];
$codigo_de_registro = $registro_curso['codigo'];

/* datos del evento */
$rq1 = query("SELECT * FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
$url_imagen = 'https://eventos.bo/contenido/imagenes/paginas/' . str_replace('+','%20',urlencode($curso['imagen']));

/* registro de participantes */
$sw_inscripcion = false;
if($moneda=='usd'){
    $txt_moneda = 'USD';
    $khipu_moneda = "USD";
    $monto_pago = $monto;
    $khipu__secret = "f8c7e79fc46ded62b71143b3394bb5ef89079c48";
    $khipu__receiverid = 194883;
}else{
    $txt_moneda = 'BS';
    $khipu_moneda = "BOB";
    $monto_pago = $monto;
    $khipu__secret = "e5b6e2baf2062298a89c93efde60ab34b615c0cf";
    $khipu__receiverid = 148527;
}
?>
<html>
<head>
    <title>PAYMENT</title>
</head>
<body>
    <div style="padding: 50px;text-align:center;font-size:17pt;font-weight:bold;font-family: arial;">
        Procesando...
        <br>
        <br>
        <span style="font-size: 12pt;color:gray;">Por favor espere</span>
    </div>
    <form id="payment_confirmation" action="<?php echo $url_payment_secureacceptance; ?>" method="post"/>
<?php
/* parametros */
$transaction_uuid = uniqid();
$customer_ip_address = '192.168.30.15';
$signed_date_time = gmdate("Y-m-d\TH:i:s\Z");
$params = array();
$params['access_key'] = '76626d46eb4739c885e5afa3666360bc';
$params['profile_id'] = '89073A34-594B-493C-8988-618E9C816880';
$params['transaction_uuid'] = $transaction_uuid;
/* Parámetros de la compra - DEBEN SER CAMBIADOS POR LOS DATOS QUE TU SISTEMA GENERA - INICIO */
/* Datos de compra */
$params['tax_amount'] = '0.00';
$params['billing_state'] = 'N/A';
$params['billing_zip'] = 'N/A';
$params['bill_to_forename'] = 'Andres';
$params['bill_to_surname'] = 'Quintanilla';
$params['bill_to_email'] = 'jraymondad@gmail.com';
$params['bill_to_phone'] = '2110909';
$params['bill_to_address_line1'] = 'Av. 20 de Octubre esq. Plaza Avaroa';
$params['bill_to_address_city'] = 'La Paz';
$params['bill_to_address_postal_code'] = '5496';
$params['bill_to_address_country'] = 'BO';
$params['bill_address1'] = 'Av. 20 de Octubre esq. Plaza Avaroa 2';
$params['bill_city'] = 'La Paz';
$params['bill_country'] = 'BO';
$params['bill_to_address_state'] = 'BOL';
/* Datos de Envío (pueden ser los mismos que la compra) */
$params['ship_to_forename'] = 'Andres';
$params['ship_to_surname'] = 'Quintanilla';
$params['ship_to_email'] = 'jraymondad@gmail.com';
$params['ship_to_address_line1'] = 'Av. 20 de Octubre esq. Plaza Avaroa';
$params['ship_to_address_city'] = 'La Paz';
$params['ship_to_address_postal_code'] = '5496';
$params['ship_to_address_country'] = 'BO';
$params['ship_to_address_state'] = 'BOL';
/* Datos del Item */
$params['customer_ip_address'] = $customer_ip_address;
$params['item_0_name'] = 'NOMBRE DEL ITEM COMPRADO';
$params['item_0_sku'] = 'NOMBRE DEL ITEM COMPRADO';
$params['item_0_quantity'] = '1';
$params['item_0_unit_price'] = '100.00';
$params['item_0_tax_amount'] = '0.00';
$params['line_item_count'] = '1';
$params['customer_email'] = 'jraymondad@gmail.com';
$params['developer_id'] = 'default';
/* INDIQUE LAS URLS QUE SERVIRAN PARA REDIRECCIONAR (si no se define se tomará el configurado por defecto) */
$params['override_custom_receipt_page'] = $dominio.'contenido/paginas/procesos/payment/receipt.php';
$params['override_custom_cancel_page'] = $dominio.'contenido/paginas/procesos/payment/cancel.php';
/* *** Parámetros de la compra FIN *** */
$params['signed_field_names'] = 'override_custom_receipt_page,override_custom_cancel_page,access_key,profile_id,transaction_uuid,tax_amount,billing_state,billing_zip,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_postal_code,bill_to_address_country,bill_address1,bill_city,bill_country,ship_to_forename,ship_to_surname,ship_to_email,ship_to_address_line1,ship_to_address_city,ship_to_address_postal_code,ship_to_address_country,customer_ip_address,item_0_name,item_0_sku,item_0_quantity,item_0_unit_price,item_0_tax_amount,line_item_count,customer_email,developer_id,bill_to_address_state,ship_to_address_state,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency';
$params['unsigned_field_names'] = '';
$params['signed_date_time'] = $signed_date_time;
$params['locale'] = 'es';
/* detalles del pago */
$params['transaction_type'] = 'authorization';
$params['reference_number'] = '1597762039442';
$params['amount'] = '100.00';
$params['currency'] = 'USD';
?>
    <?php
        foreach($params as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }
        echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
    ?>
</form>
    <script>document.getElementById('payment_confirmation').submit();</script>
</body>
</html>
