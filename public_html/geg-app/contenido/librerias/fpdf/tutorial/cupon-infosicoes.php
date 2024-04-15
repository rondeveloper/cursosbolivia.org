<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/* datos */
$id_cupon = '0';
if (isset_get('id_cupon')) {
    $id_cupon = get('id_cupon');
}
$id_curso = '0';
if (isset_get('id_curso')) {
    $id_curso = get('id_curso');
}
$id_participante = '0';
if (isset_get('id_participante')) {
    $id_participante = get('id_participante');
}

/* generad de QR */
include_once '../../phpqrcode/qrlib.php';

/* proceso */
require('../fpdf.php');
session_start();
$name = $_SESSION['username'];
$id = $_SESSION['userid'];
$fullname = $_SESSION['full'];

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-27);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'This certificate has been ©  © produced by Desteco SRL', 0, 0, 'R');
    }

}

$pdf = new FPDF('P', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));



/* registro */
if ($id_participante == '0') {
    $rqce1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' ORDER BY id_participante ASC limit 300 ");
} else {
    $rqce1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_participante='$id_participante' ORDER BY id_participante ASC limit 300 ");
}
$cnt_counter = mysql_num_rows($rqce1);
if ($cnt_counter == 0) {
    echo "<br/><hr/><h3>No se encontraron emisiones de certificados para los participantes seleccionados.</h3><hr/><br/>";
    exit;
}

$cnt_counter_aux = 0;
while ($rqce2 = mysql_fetch_array($rqce1)) {

    /* certificado id */
    $codigo = $rqce2['codigo'];

    /* verficacion de cupon */
    $rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id='$id_cupon' LIMIT 1 ");
    $rqdcd2 = mysql_fetch_array($rqdcd1);
    $id_paquete = $rqdcd2['id_paquete'];
    $fecha_expiracion_cupon = $rqdcd2['fecha_expiracion'];
    $duracion = $rqdcd2['duracion'];
    
    /* participante */
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE id='".$rqce2['id_participante']."' ORDER BY id DESC LIMIT 1 ");
    $rqcp2 = mysql_fetch_array($rqcp1);
    $nombre_participante = strtoupper(trim($rqcp2['nombres'] . ' ' . $rqcp2['apellidos']));

    switch ($id_paquete) {
        case '2':
            $txt_nompaq = "PAQUETE PyME";
            break;
        case '3':
            $txt_nompaq = "PAQUETE BASICO";
            break;
        case '4':
            $txt_nompaq = "PAQUETE MEDIO";
            break;
        case '5':
            $txt_nompaq = "PAQUETE INTERMEDIO";
            break;
        case '6':
            $txt_nompaq = "PAQUETE EMPRESARIAL";
            break;
        case '7':
            $txt_nompaq = "PAQUETE COORPORATIVO";
            break;
        case '10':
            $txt_nompaq = "PAQUETE Consultor - BASICO";
            break;
        case '11':
            $txt_nompaq = "PAQUETE Consultor - GOLD";
            break;
        case '12':
            $txt_nompaq = "PAQUETE Consultor - PREMIUM";
            break;
        default:
            $txt_nompaq = '';
            break;
    }



    $codigo_certificado = $codigo;
    $cont_titulo = $txt_nompaq;
    $cont_uno = 'Duración del paquete: ' . $duracion . ' MESES';
    $cont_dos = 'CODIGO DE CUPÓN: ' . $codigo;
    $cont_tres = 'Este cupón tiene validez hasta: ' . date("d / m / Y", strtotime($fecha_expiracion_cupon));

    /* generad de QR */
    $file = 'qrcode-codigo-cupon-descuento-' . $codigo . '.png';
    $file_qr_certificado = '../../../imagenes/qrcode/' . $file;
    if (!is_file($file_qr_certificado)) {
        copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
    }
    /* nombre del curso */
    $rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);
    $data = $codigo . ' | ' . $txt_nompaq . ' | ' . $duracion . ' MESES';
    QRcode::png($data, $file_qr_certificado);


//Loading data 
    $pdf->SetTopMargin(20);
//$pdf->SetLeftMargin(70);
    $pdf->SetLeftMargin(0);
    $pdf->SetRightMargin(10);
    $pdf->AddPage();

    $pdf->SetAutoPageBreak(false);

    $pdf->Ln(1);

//DATOS
    $pdf->SetTextColor(16, 43, 75);
// Arial 12
    $pdf->SetFont('Arial', 'B', 17);
// Título

    $pdf->SetY(90);
    $pdf->SetX(185);
    $pdf->MultiCell(400, 20, $cont_titulo, 50, 'C');
// Salto de línea
    $pdf->SetTextColor(0, 0, 0);

//datos participante
    /* $pdf->SetFont('Arial', '', 21); */
    //*$pdf->SetFont('Arial', '', 25);
    //*$pdf->Cell(0, 450, $receptor_de_certificado, 0, 0, 'C');
//datos certificado
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(30, 30, 30);


    $pdf->SetY(0);
    $pdf->SetX(160);
    $pdf->Cell(0, 130, $cont_uno, 0, 0, 'C');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 22);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetY(0);
//*$pdf->Cell(0, 0, $cont_dos, 0, 0, 'C');
    $pdf->SetX(160);
    $pdf->SetTextColor(13, 81, 96);
    $pdf->MultiCell(0, 80, $cont_dos, 0, 'C');
    $pdf->SetTextColor(0, 0, 0);
//$pdf->Ln(1);
//$pdf->Cell(0, 30, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

    /* $pdf->SetY(-180); */
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetY(-0);
    $pdf->SetX(160);
    $pdf->Cell(0, 350, $cont_tres, 0, 0, 'C');



    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetY(-17);
    $pdf->SetX(590);
    $pdf->Cell(200, 0, 'ID de certificado: ' . $codigo, 0, 0, 'C');

    $pdf->SetY(-100);
    /* codigo QR */
    /* $pdf->Image($file_qr_certificado, 690, 510, 95, 95); */
    //$pdf->Image($file_qr_certificado, 350, 40, 100, 100);
    $pdf->Image($file_qr_certificado, 20, 70, 120, 115);



    /* logo */
    $file_qr_certificado = '../../../alt/logotipo-v2.png';
    $pdf->Image($file_qr_certificado, 20, 20, 150, 50);


    $pdf->SetFont('Arial', '', 11);
    $pdf->SetY(190);
    $pdf->SetX(25);
    $pdf->MultiCell(550, 14, 'Este cupón fue emitido para '.$nombre_participante.' y constituye la adquisición de un paquete de servicios de la plataforma Infosicoes de monitoreo de licitaciones de Bolivia.', 0, 'L');


    /* logo infosicoes */
    $pdf->Image('../../../imagenes/images/logotipo_infosicoes.png', 324, 119, 120, 37);



    /* texto solo en impresion multiple */
    //$pdf->SetY(-17);
    //$pdf->SetX(50);
    //$pdf->Cell(100, 0, 'L-' . ++$cnt_counter_aux, 0, 200, 'R');
}


$pdf->Output();

