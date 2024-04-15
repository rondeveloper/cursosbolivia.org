<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* datos */
$id_emision = get('id_emision');
$hash = get('hash');

/* verificacion de hash */
if ($hash != md5(md5($id_emision . 'cce5616'))) {
    echo "DENEGADO";
    exit;
}

/* datos de emision */
$rqe1 = query("SELECT * FROM certificados_culminacion_emisiones WHERE id='$id_emision' ORDER BY id DESC limit 1 ");
$rqe2 = fetch($rqe1);
$id_participante = $rqe2['id_participante'];

/* datos de receptor de certificado */
$rqdpc1 = query("SELECT p.nombres,p.apellidos,p.ci,p.ci_expedido,p.prefijo,p.cnt_impresion_certificados,p.id_curso,(pr.id)dr_id_proceso_registro,(pr.codigo)dr_codigo_proceso_registro FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE p.id='$id_participante' ORDER BY p.id DESC limit 1 ");
$rqdpc2 = fetch($rqdpc1);
$nombre_participante = trim($rqdpc2['nombres']) . ' ' . trim($rqdpc2['apellidos']);
$ci_participante = $rqdpc2['ci'];
$ci_expedido_participante = $rqdpc2['ci_expedido'];
$id_curso = $rqdpc2['id_curso'];
$dr_id_proceso_registro = $rqdpc2['dr_id_proceso_registro'];
$dr_codigo_proceso_registro = $rqdpc2['dr_codigo_proceso_registro'];

/* incremento de numero de impresion cert participante */
$cnt_impresion_certificados = $rqdpc2['cnt_impresion_certificados'] + 1;
query("UPDATE cursos_participantes SET cnt_impresion_certificados='$cnt_impresion_certificados',ultima_impresion_certificado='" . date("Y-m-d H:i") . "' WHERE id='" . $rqe2['id_participante'] . "' LIMIT 1 ");

/* id de modelo de certificado */
$id_certificado_culminacion = $rqe2['id_certificado_culminacion'];

/* nota */
$txt_nota = '';
$rqdn1 = query("SELECT nota FROM participantes_notas_manuales WHERE id_participante='$id_participante' ");
if(num_rows($rqdn1)>0){
    $rqdn2 = fetch($rqdn1);
    if((int)$rqdn2['nota']>0){
        $txt_nota = 'con una nota de '.$rqdn2['nota'].' / 100 , ';
    }
}

/* $txt_matricula_registro */
$txt_matricula_registro = utf8_encode('con matricula Nº '.$dr_id_proceso_registro.', Registro '.$dr_codigo_proceso_registro.', ');

/* datos de certificado */
$rqc1 = query("SELECT * FROM certificados_culminacion WHERE id='$id_certificado_culminacion' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$texto_a = utf8_decode($rqc2['texto_a']);
$busc = array('[NOMBRE-PARTICIPANTE]','[CI-PARTICIPANTE]','[NOTA]','[MATRICULA-REGISTRO]');
$remm = array($nombre_participante,trim($ci_participante.' '.$ci_expedido_participante),$txt_nota,$txt_matricula_registro);
$texto_b = utf8_decode(str_replace($busc,$remm,$rqc2['texto_b']));
$texto_c = utf8_decode($rqc2['texto_c']);
$texto_qr = utf8_decode($rqc2['texto_qr']);
$fecha_certificado = $rqc2['fecha'];

/* generad de QR */
include_once '../../../librerias/phpqrcode/qrlib.php';
$file = 'qrcode-codigo-certificado-' . $id_emision . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
/* data del curso */
$rqdc1 = query("SELECT titulo,fecha,numero FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$numeracion_curso = $rqdc2['numero'];
if ($fecha_qr == '0000-00-00') {
    $fecha_qr = $rqdc2['fecha'];
}
$data = $dr_codigo_proceso_registro . ' | CERTIFICADO CULMINACION' . ' | ' . $id_emision . ' | ' . $nombre_participante . ' | CI ' . $ci_participante;
QRcode::png($data, $file_qr_certificado);

/* margin left */
$cnt_margin_left = 50;

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-27);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'This certificate has been produced by Desteco SRL', 0, 0, 'R');
    }

}

$pdf = new FPDF('P', 'pt', 'Letter');
$pdf->SetTopMargin(20);
$pdf->SetLeftMargin(0+($cnt_margin_left*2));
$pdf->SetRightMargin(10);
$pdf->AddPage();

$pdf->SetAutoPageBreak(false);


$pdf->Ln(1);

/* Título 1 */
$pdf->SetTextColor(16, 43, 75);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 300, "CERTIFICADO DE ESTUDIO", 0, 0, 'C');
/* Salto de linea */
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);



/* $texto_a */
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetY(200);
$pdf->SetX(100+$cnt_margin_left );
$pdf->MultiCell(400, 17, $texto_a, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);


/* titulo 2 */
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetY(120);
$pdf->SetX(0+ ($cnt_margin_left*2) );
$pdf->Cell(0, 460, "CERTIFICA QUE:", 0, 0, 'C');
$pdf->Ln(4);


/* $texto_b */
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetY(400);
$pdf->SetX(100+$cnt_margin_left);
$pdf->MultiCell(400, 17, $texto_b, 0, 'J');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);


/* $texto_c */
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetX(150);
$pdf->SetY(-195);
$pdf->Cell(0, -20, $texto_c, 0, 0, 'C');
$pdf->Ln(1);
$pdf->Cell(0, 20, "La Paz, ".fechaLiteral($fecha_certificado), 0, 0, 'C');


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-27);
$pdf->SetX(590);
$pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

$pdf->SetY(-100);
/* codigo QR */
$pdf->Image($file_qr_certificado, 470, 650, 130, 130);


/* texto solo en impresion multiple (**) */
$pdf->SetY(-27);
$pdf->SetX(50);
$pdf->Cell(100, 0, 'ID: 00000' . $id_emision, 0, 200, 'R');


//$pdf->Output();
$modo = "I";
$nombre_archivo = "cert-culminacion-$id_emision.pdf";
$pdf->Output($nombre_archivo, $modo);



function fechaLiteral($fecha){
    $d = date("d",strtotime($fecha));
    $m = date("m",strtotime($fecha));
    $y = date("Y",strtotime($fecha));
    $ar_m = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    /* return $d.' de '.$ar_m[(int)$m].' de '.$y; */
    return $ar_m[(int)$m].' de '.$y;
}