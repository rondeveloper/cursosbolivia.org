<?php
//error_reporting(E_ALL);

session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$moneda = post('moneda');
$monto = post('monto');
$id_proceso_registro = post('id_proceso_registro');

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
$url_imagen = $dominio.'contenido/imagenes/paginas/' . str_replace('+','%20',urlencode($curso['imagen']));

/* registro de participantes */
$sw_inscripcion = false;
//$monto_pago = (int)$aux_monto_a_pagar;
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


/* PARTICIPANTES DEL EVENTO */
$rqpic1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ");
$i = 0;
while ($rqpc2 = fetch($rqpic1)) {
    $i++;
    $id_participante = $rqpc2['id'];
    $sw_inscripcion = true;
    logcursos('Eleccion pago por KHIPU', 'participante-registro', 'participante', $id_participante);
}

if(!$sw_inscripcion){
    echo "ERROR";exit;
}


/* REGISTRO DE COBRO KHIPU */

/* cargado de App Khipu */
require '../../librerias/khipu-api-php-client-master/autoload.php';

$c = new Khipu\Configuration();
$c->setSecret($khipu__secret);
$c->setReceiverId($khipu__receiverid);
$c->setDebug(false);

$cl = new Khipu\ApiClient($c);

$expires_date = new DateTime();
$aux_0_d = date('d', strtotime("+1 days"));
$aux_0_m = date('m', strtotime("+1 days"));
$aux_0_Y = date('Y', strtotime("+1 days"));
$expires_date->setDate($aux_0_Y, $aux_0_m, $aux_0_d);

$kh = new Khipu\Client\PaymentsApi($cl);

$title = "" . $codigo_de_registro;

$array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$aux_dia = date("d", strtotime($curso['fecha']));
$aux_mes = $array_meses[(int) date("m", strtotime($curso['fecha']))];
$aux_anio = date("Y", strtotime($curso['fecha']));

//*$body = $curso['titulo'] . " | Pago de " . $monto_pago . " bs. por concepto de inscripcion a curso | curso a realizarse el dia " . $aux_dia . " de " . $aux_mes . " de " . $aux_anio . " | Codigo de curso: C00" . $curso['id'];
$body = $curso['titulo'] . " | Pago de " . $monto_pago . " bs. por concepto de inscripcion a curso | Codigo de curso: C00" . $curso['id'];

$picture_url = "$url_imagen";
$receipt_url = $dominio;
$return_url = $dominio."respuesta-khipu/" . encrypt(rand(0, 99999) . "-transaccion-aprobado-id" . $id_proceso_registro . "-" . rand(0, 99999)) . ".html";
$cancel_url = $dominio."respuesta-khipu/" . encrypt(rand(0, 99999) . "-transaccion-cancelado-id" . $id_proceso_registro . "-" . rand(0, 99999)) . ".html";

$notify_url = "";
$notify_api_version = "";
$transaction_id = "1000" . $id_proceso_registro;

try {
    $opts = array(
        "expires_date" => $expires_date,
        "body" => $body,
        "payer_email" => $correo_proceso_registro,
        "picture_url" => $picture_url,
        "receipt_url" => $receipt_url,
        "return_url" => $return_url,
        "cancel_url" => $cancel_url,
        "notify_url" => $notify_url,
        "notify_api_version" => $notify_api_version,
        "transaction_id" => $transaction_id
    );
    $resp = $kh->paymentsPost($title, $khipu_moneda, $monto_pago, $opts);
    $r2 = $kh->paymentsIdGet($resp->getPaymentId());

    $payment_id = $r2['payment_id'];
    $payment_url = $r2['payment_url'];
    $simplified_transfer_url = $r2['simplified_transfer_url'];
    $transfer_url = $r2['transfer_url'];
    $app_url = $r2['app_url'];
    $ready_for_terminal = $r2['ready_for_terminal'];
    $notification_token = $r2['notification_token'];
    $receiver_id = $r2['receiver_id'];
    $conciliation_date = $r2['conciliation_date'];
    $subject = $r2['subject'];
    $amount = $r2['amount'];
    $currency = $r2['currency'];
    $status = $r2['status'];
    $status_detail = $r2['status_detail'];
    $body = $r2['body'];
    $picture_url = $r2['picture_url'];
    $receipt_url = $r2['receipt_url'];
    $return_url = $r2['return_url'];
    $cancel_url = $r2['cancel_url'];
    $notify_url = $r2['notify_url'];
    $notify_api_version = $r2['notify_api_version'];
    $expires_date = $r2['expires_date'];
    $attachment_urls = $r2['attachment_urls'];
    $bank = $r2['bank'];
    $bank_id = $r2['bank_id'];
    $payer_name = $r2['payer_name'];
    $payer_email = $r2['payer_email'];
    $personal_identifier = $r2['personal_identifier'];
    $bank_account_number = $r2['bank_account_number'];
    $out_of_date_conciliation = $r2['out_of_date_conciliation'];
    $transaction_id = $r2['transaction_id'];
    $custom = $r2['custom'];
    $responsible_user_email = $r2['responsible_user_email'];
    $send_reminders = $r2['send_reminders'];
    $send_email = $r2['send_email'];
    $payment_method = $r2['payment_method'];

    /* insercion a la base de datos */
    query("INSERT INTO khipu_cobros(
           payment_id, 
           amount, 
           notification_token, 
           transaction_id, 
           estado
           ) VALUES (
           '$payment_id',
           '$amount',
           '$notification_token',
           '$transaction_id',
           '0'
           )");
    $id_cobro_khipu = insert_id();

    /* proceso registro */
    $sw_pago_enviado = '0';

    query("UPDATE cursos_proceso_registro SET id_cobro_khipu='$id_cobro_khipu' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    envio_email("brayan.desteco@gmail.com", "Registro de inicio de pago con Khipu [$___nombre_del_sitio]", "------>" . print_r($r2, true));
    
    $url_de_pago = "https://khipu.com/payment/info/" . $payment_id;
} catch (Exception $e) {
    //echo $e->getMessage();
    envio_email("brayan.desteco@gmail.com", "Error de inicio de pago con Khipu [$___nombre_del_sitio]", "------>" . $e->getMessage());
}
/* END REGISTRO DE COBRO KHIPU */
?>

<style>
    .aux-css-static-1{
        padding:10px 30px;
    }
    @media screen and (max-width: 650px){
        .aux-css-static-1{
            padding:5px 0px;
        }
        .areaRegistro1 td {
            float: left;
            width: 50%;
            text-align: left !important;
        }
    }
</style>

<div style="border:1px dashed #DDD;overflow: hidden;">
    <div style="width:100%;height:800px;border:0px;overflow:hidden;">
        <iframe src="<?php echo $url_de_pago; ?>" style="width: 100%;height:820px;border:0px;"></iframe>
    </div>
</div>
