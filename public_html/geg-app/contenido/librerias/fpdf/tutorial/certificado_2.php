<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos */
$certificado_id = get('data');

/* datos de emision */
$rqe1 = query("SELECT * FROM emisiones_certificados WHERE certificado_id='$certificado_id' ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqe1) == 0) {
    echo "ACCESO DENEGADO";
    exit;
}
$rqe2 = mysql_fetch_array($rqe1);
$receptor_de_certificado = utf8_decode($rqe2['receptor_de_certificado']);
/* id de certificado */
$id_certificado = $rqe2['id_certificado'];
$fecha_emision_certificado = $rqe2['fecha_emision'];

/* datos de certificado */

$rqc1 = query("SELECT * FROM certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$txt_certificado_participacion = ($rqc2['cont_titulo']);
$txt_titulo_curso = ($rqc2['cont_uno']);
$texto_qr = ($rqc2['texto_qr']);
$fecha_qr = ($rqc2['fecha_qr']);

$txt_id_de_certificado = ('ID de certificado: '.$certificado_id);
$txt_emitido_el = ('Emitido el ').fecha_literal($fecha_emision_certificado);

function fecha_literal($dat){
    $d = date("d",strtotime($dat));
    $m = date("m",strtotime($dat));
    $y = date("Y",strtotime($dat));
    $mes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    return $d.' de '.$mes[(int)$m].' de '.$y;
}

/*
$txt_certificado_participacion = ('CERTIFICADO DE PARTICIPACIÓN');
$txt_titulo_curso = ('GSuite para la Creatividad y Colaboración global (Drive y Docs)');
$txt_id_de_certificado = ('ID de certificado: '.$certificado_id);
$txt_emitido_el = utf8_decode('Emitido el 13 de Mayo de 2020');
$texto_qr = ('GSuite para la Creatividad y Colaboración global');
$fecha_qr = utf8_decode('2020-05-13');
*/


/* generad de QR */
include_once '../../phpqrcode/qrlib.php';
$file = 'qrcode-codigo-certificado-' . $certificado_id . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}

$data = $certificado_id . '|' . $texto_qr . '|' . $receptor_de_certificado  . '|'.$fecha_qr;
QRcode::png($data, $file_qr_certificado);


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

$pdf = new FPDF('L', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));
//Loading data 
$pdf->SetTopMargin(20);
//$pdf->SetLeftMargin(70);
$pdf->SetLeftMargin(0);
$pdf->SetRightMargin(10);
$pdf->AddPage();

$pdf->SetAutoPageBreak(false);

//  Print the edge of
//$pdf->Image("cert2.png", 20, 20, 780);
// Print the certificate logo  
//$pdf->Image("tt1.png", 140, 180, 240);
// Print the title of the certificate  
//$pdf->SetFont('times', 'B', 80);
//$pdf->Cell(720 + 10, 200, "CERTIFICATE", 0, 0, 'C');
//$pdf->SetFont('Arial', 'I', 34);
//$pdf->SetXY(370, 220);
//$pdf->Cell(350, 25, $fullname, "B", 0, 'C', 0);
//$pdf->SetFont('Arial', 'I', 14);
//$pdf->SetXY(370, 280);
//$message = "ON COMPLETION OF";
//$pdf->MultiCell(350, 14, $message, 0, 'C', 0);
//$pdf->SetFont('Arial', 'B', 16);
//$pdf->SetXY(370, 470);
//$signataire = "TheTUTOR";
//$pdf->Cell(350, 19, $signataire, "T", 0, 'C');

/* IMAGEN BACKGROUND */
$file_imagen_background = '../../../imagenes/certificados/background-certificate.jpg';
$pdf->Image($file_imagen_background, 0, 0, 800, 620);



$pdf->Ln(1);

//DATOS
$pdf->SetTextColor(50, 93, 173);
// Arial 12
$pdf->SetFont('Arial', 'B', 27);
// Título
$pdf->Cell(0, 340, $txt_certificado_participacion, 0, 0, 'C');
// Salto de línea
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);

//datos participante
if ($ci_participante !== '' && $ci_participante !== '0') {
    //ci participante
    $array_expedido_base = array('LP', 'OR', 'PS', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH', 'PT');
    $array_expedido_short = array('LP', 'OR', 'PT', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
    $array_expedido_literal = array('La Paz', 'Oruro', 'Potosi', 'Cochabamba', 'Santa Cruz', 'Beni', 'Pando', 'Tarija', 'Chuquisaca',  'Potosi');
//    if ($ci_expedido_participante !== '') {
//        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante expedido en " . strtoupper(str_replace($array_expedido_base, $array_expedido_literal, $ci_expedido_participante));
//    } else {
//        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante";
//    }
    $texto_cedula_identidad = "Otorgado satisfactoriamente a:";

    //nombre participante
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 420, $texto_cedula_identidad, 0, 0, 'C');
    $pdf->Ln(4);

    
    $pdf->SetY(0);
    $pdf->SetX(0);
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 535, $receptor_de_certificado, 0, 0, 'C');
} else {
    //nombre participante
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 460, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(4);
}

$pdf->SetDrawColor(50, 93, 173);
$pdf->Line(200, 290, 600, 290);
$pdf->SetDrawColor(0, 0, 0);

//datos certificado
$pdf->SetFont('Arial', 'B', 17);
$pdf->SetTextColor(30, 30, 30);

/* texto - cont uno */
$pdf->SetY(-295);
$pdf->Cell(0, 0, $txt_titulo_curso, 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);



/* texto - cont uno */
$pdf->SetY(-290);
$pdf->Cell(0, 60, 'Por cuanto se reconoce que completó', 200, 200, 'C');
$pdf->Cell(0, -20, 'satisfactoriamente el curso de capacitación', 200, 200, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);


$pdf->SetTextColor(45, 85, 158);

/* texto - cont dos */
$l_plus_Y = 0;
if (strlen($txt_id_de_certificado) > 95) {
    $ar_aux_tl = explode('con una carga horaria', $txt_id_de_certificado);
    if (count($ar_aux_tl) == 2) {
        $txt_id_de_certificado_A = trim($ar_aux_tl[0]);
        $txt_id_de_certificado_B = 'con una carga horaria'.$ar_aux_tl[1];
        $pdf->SetY(-250);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $txt_id_de_certificado_A, 0, 'C');
        $pdf->SetY(-225);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $txt_id_de_certificado_B, 0, 'C');
        $l_plus_Y = 15;
    } else {
        $pdf->SetY(-250);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $txt_id_de_certificado, 0, 'C');
    }
} else {
    $pdf->SetY(-250);
    $pdf->SetX(65);
    $pdf->MultiCell(650, 80, $txt_id_de_certificado, 0, 'C');
}

/* texto - cont tres */
$pdf->SetX(150);
$pdf->SetY(-195 + $l_plus_Y);
$pdf->Cell(0, 20, $txt_emitido_el, 0, 0, 'C');


$pdf->SetTextColor(0, 0, 0);

/* texto - validar */
$pdf->SetX(150);
$pdf->SetY(-195 + $l_plus_Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 80, 'Verifique la autenticidad de este certificado en https://gegbolivia.cursos.bo/validar-certificado/', 0, 0, 'C');


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-17);
$pdf->SetX(590);
$pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

$pdf->SetY(-100);
/* codigo QR */
//$pdf->Image($file_qr_certificado, 685, 510, -80);
//$pdf->Image($file_qr_certificado, 690, 510, 95, 95);
//$pdf->Image($file_qr_certificado, 350, 40, 100, 100);
$pdf->Image($file_qr_certificado, 150, 330, 115, 115);


/* texto solo en impresion multiple (**) */
//$pdf->SetY(-17);
//$pdf->SetX(50);
//$pdf->Cell(100, 0, 'C-' . $codnum_c, 0, 200, 'R');

//$pdf->Output();


/* adminsitrador */
/*
$administrador = 'sin-nombre';
$id_administrador = '0';
if (isset_administrador()) {
    $id_administrador = administrador('id');
    $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1");
    $rqda2 = mysql_fetch_array($rqda1);
    $administrador = limpiar_enlace($rqda2['nombre']);
}
*/

//$pdf->Output();
$modo = "I";
$nombre_archivo = limpiar_enlace($rqdc2['titulo']) . "__F_" . date("d-M-Y", strtotime($rqdc2['fecha'])) . "__1P__G_" . date("d-M") . "_H_" . date("H-i") . "__" . $administrador . "__" . time() . ".pdf";
$fecha_current = date("Y-m-d H:i");
/*
query("INSERT INTO cursos_pdf_generados(id_administrador, id_curso, nombre, fecha) VALUES ('$id_administrador','$id_curso','$nombre_archivo','$fecha_current') ");
logcursos('Generado de PDF [' . $nombre_archivo . ']', 'curso-pdf', 'curso', $id_curso);
*/
//echo "yep";exit;

//$pdf->Output("../../../archivos/pdfcursos/$nombre_archivo", "F");
$pdf->Output($nombre_archivo, $modo);

