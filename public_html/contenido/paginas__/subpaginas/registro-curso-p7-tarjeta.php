<?php
/* REQUERIDO PHP MAILER */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* datos de formulario post */
$id_proceso_registro = post('id_proceso_registro');

/* datos de registro */
$rqdr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$registro_curso = fetch($rqdr1);

$id_curso = $registro_curso['id_curso'];
$nro_participantes = $registro_curso['cnt_participantes'];
$id_turno = $registro_curso['id_turno'];

$cnt_participantes = $nro_participantes;
$nit = $registro_curso['nit'];
$razon_social = $registro_curso['razon_social'];
$fecha_registro = date("d/m/Y H:i",strtotime($registro_curso['fecha_registro']));
$correo_proceso_registro = $registro_curso['correo_contacto'];
$celular_proceso_registro = $registro_curso['celular_contacto'];
$codigo_de_registro = $registro_curso['codigo'];

/* datos del curso */
$rq1 = query("SELECT *,(select titulo from departamentos where id=c.id_ciudad)ciudad,(select nombre from cursos_lugares where id=c.id_lugar limit 1)lugar_curso FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
$titulo_identificador_curso = $curso['titulo_identificador'];

$titulo_curso = $curso['titulo'];
$url_curso = $curso['titulo_identificador'];
$fecha_curso = date("d/m/Y",strtotime($curso['fecha']));
$ciudad_curso = $curso['ciudad'];
$lugar_curso = $curso['lugar_curso'];
$horario_curso = $curso['horarios'];


/* registro de participantes */
$sw_inscripcion = false;
if (isset_post('inscripcion')) {

    $monto_deposito = (int) post('monto_deposito');

    $datos_formulario_de_inscripcion = "<table style='width:100%;'>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Codigo de registro:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$codigo_de_registro</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Nro de participantes:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$cnt_participantes participante(s)</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$titulo_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Url del curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$url_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Fecha:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$fecha_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    
    /* PARTICIPANTES DEL CURSO */
    $rqpic1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ");
    $i = 0;
    while ($rqpc2 = fetch($rqpic1)) {
        $i++;
        $nombres = $rqpc2['nombres'];
        $apellidos = $rqpc2['apellidos'];
        $prefijo = $rqpc2['prefijo'];
        $correo = $rqpc2['correo'];
        $celular = $rqpc2['celular'];
        $id_participante = $rqpc2['id'];

        $sw_inscripcion = true;

        $datos_formulario_de_inscripcion .= "<tr>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Participante $i:</td>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$nombres $apellidos<br/>$correo<br/>$celular</td>";
        $datos_formulario_de_inscripcion .= "</tr>";
        
        logcursos('Eleccion pago por KHIPU', 'participante-registro', 'participante', $id_participante);
    }

    $datos_formulario_de_inscripcion .= "</table>";

        
        /* REGISTRO DE COBRO KHIPU */
        //*error_reporting(1);

        /* cargado de App Khipu */
        require 'contenido/librerias/khipu-api-php-client-master/autoload.php';

        $c = new Khipu\Configuration();
        $c->setSecret("e5b6e2baf2062298a89c93efde60ab34b615c0cf");
        $c->setReceiverId(148527);
        $c->setDebug(false);

        $cl = new Khipu\ApiClient($c);

        $expires_date = new DateTime();
        $aux_0_d = date('d', strtotime("+5 days"));
        $aux_0_m = date('m', strtotime("+5 days"));
        $aux_0_Y = date('Y', strtotime("+5 days"));
        $expires_date->setDate($aux_0_Y, $aux_0_m, $aux_0_d);

        $kh = new Khipu\Client\PaymentsApi($cl);

        $title = "Curso C00" . $curso['id'];

        $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $aux_dia = date("d", strtotime($curso['fecha']));
        $aux_mes = $array_meses[(int) date("m", strtotime($curso['fecha']))];
        $aux_anio = date("Y", strtotime($curso['fecha']));

        $body = $curso['titulo'] . " | Pago de " . $monto_deposito . " bs. por concepto de inscripcion a curso | curso a realizarse el dia " . $aux_dia . " de " . $aux_mes . " de " . $aux_anio . " | Codigo de curso: C00" . $curso['id'];
        $monto = $monto_deposito;

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
            $resp = $kh->paymentsPost($title, "BOB", $monto, $opts);
            //echo "<hr/>";
            //print_r($resp);
            //echo "<hr/>";
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
            /* solicitud de id de cobro khipu */
            $rqck1 = query("SELECT id FROM khipu_cobros WHERE payment_id='$payment_id' AND transaction_id='$transaction_id' ORDER BY id DESC limit 1 ");
            $rqck2 = fetch($rqck1);
            $id_cobro_khipu = $rqck2['id'];

            /* actualizacion de id de cobro khipu a registro */
            /* proceso registro */
            query("UPDATE cursos_proceso_registro SET id_cobro_khipu='$id_cobro_khipu',id_modo_pago='6',monto_deposito='$monto_deposito' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
            query("UPDATE cursos_participantes SET id_modo_pago='6' WHERE id_proceso_registro='$id_proceso_registro' ");

            envio_email("brayan.desteco@gmail.com", "Registro de inicio de pago con Khipu", "------>" . print_r($r2, true));

            $url_de_pago = "https://khipu.com/payment/info/" . $payment_id;
        } catch (Exception $e) {
            //echo $e->getMessage();
            envio_email("brayan.desteco@gmail.com", "Error de inicio de pago con Khipu", "------>" . $e->getMessage());
        }
        /* END REGISTRO DE COBRO KHIPU */


        if ($id_turno == '0') {
            $tr_turno = "";
        } else {
            $rqdt1 = query("SELECT titulo,descripcion FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
            $rqdt2 = fetch($rqdt1);

            $tr_turno = "<tr><td style='padding:5px;'>Turno:</td><td style='padding:5px;'>" . $rqdt2['titulo'] . " | " . $rqdt2['descripcion'] . "</td></tr>";
        }
}
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

<div class="box_seccion_a" style="width:100%;">
    <div class="seccion_a">
        <div class="contenido_seccion white-content-one">


                <div class='row'>
                    <div class='panel'>
                        <div class='panel-body'>

                            <div class="areaRegistro1 ftb-registro-5">

                                <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>

                                <div class="row">
                                    <?php
                                    include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                    ?>
                                </div>

                                <div>
                                    <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">PAGO DE CURSO CON PLATAFORMA KHIPU</h3>

                                    <div>
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

                                            <div>
                                                <br/>
                                                <p style="text-align: left;">
                                                    Para que el registro se complete correctamente es necesario realizar el pago con tarjeta utilizando la plataforma Khipu, 
                                                    puedes hacerlo a traves del siguiente formulario, primeramente ingresa el correo donde deseas que se te envie el comprobante de pago: 
                                                </p>

                                                <div style="width:100%;height:800px;border:0px;overflow:hidden;">
                                                    <iframe src="<?php echo $url_de_pago; ?>" style="width: 100%;height:820px;border:0px;"></iframe>
                                                </div>
                                                <br/>

    <!--                                                <p class="text-center">
                                                        <a href="<?php echo $url_de_pago; ?>" style="background: #483774;color:#FFF;padding:8px 17px;font-size:11pt;border-radius:4px;">PAGAR CURSO CON TARJETA MEDIANTE KHIPU</a>
                                                    </p>-->

                                                <br/>

                                            </div>
                                        </div>



                                    </div>

                                </div>

                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <?php echo $___nombre_del_sitio; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                    </div>
                </div>
    
        </div>
    </div>
</div>
        
        
                    
 </section>
</div>     

<script>
    function habilitar_participantes(nro) {

        if (nro > 1) {
            $("#correo_part").css("display", "block");
            $("#cel_part").css("display", "block");
        } else {
            $("#correo_part").css("display", "none");
            $("#cel_part").css("display", "none");
        }

        for (var i = 1; i <= 7; i++) {

            if (i <= nro) {
                $("#box-participante-" + i).css("display", "block");
            } else {
                $("#box-participante-" + i).css("display", "none");
            }
        }
    }
</script>


<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
?>