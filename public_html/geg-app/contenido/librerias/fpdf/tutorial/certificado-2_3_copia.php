<?php

/*
  define('FPDF_FONTPATH', '.');
  require('../fpdf.php');


  $pdf = new FPDF('L', 'mm', 'A4');
  $pdf->AddFont('Calligrapher', '', 'calligra.php');
  $pdf->AddPage();
  $pdf->SetFont('Calligrapher', '', 35);
  $pdf->Cell(0, 10, 'Enjoy new fonts with FPDF!');
  //$pdf->Output();


  //data


  $registro = "Registro 1";
  $Asignatura = "lo que sea";
  $pdf->SetXY(10, 20);
  $pdf->Cell(20, 15, "Registre: " . $registro, 0, 0, 'R');
  $pdf->SetXY(28, 20);
  $pdf->Cell(20, 15, "sdgsdg");

  $pdf->SetXY(120, 20);
  $pdf->Cell(20, 15, "Signatura: " . $Asignatura, 0, 0, 'R');
  $pdf->SetXY(138, 20);
  $pdf->Cell(20, 15, "sdgsdgsgd");

  $pdf->Output();

 */

/* coneccion */

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos */
$certificado_id = get('id_certificado');

/* datos de emision */
$rqe1 = query("SELECT * FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' ORDER BY id DESC limit 1 ");
$rqe2 = mysql_fetch_array($rqe1);

//$receptor_de_certificado = utf8_decode($rqe2['receptor_de_certificado']);

/* datos de receptor de certificado */
$rqdpc1 = query("SELECT nombres,apellidos,prefijo FROM cursos_participantes WHERE id='".$rqe2['id_participante']."' ORDER BY id DESC limit 1 ");
$rqdpc2 = mysql_fetch_array($rqdpc1);
$receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']).' '.trim($rqdpc2['nombres']).' '.trim($rqdpc2['apellidos'])));

/* id de modelo de certificado */
$id_certificado = $rqe2['id_certificado'];

/* datos de certificado */
$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$codigo_certificado = $rqc2['codigo'];
$cont_titulo = utf8_decode($rqc2['cont_titulo']);
$cont_uno = utf8_decode($rqc2['cont_uno']);
$cont_dos = utf8_decode($rqc2['cont_dos']);
$cont_tres = utf8_decode($rqc2['cont_tres']);

$firma1_nombre = utf8_decode($rqc2['firma1_nombre']);
$firma1_cargo = utf8_decode($rqc2['firma1_cargo']);
$firma2_nombre = utf8_decode($rqc2['firma2_nombre']);
$firma2_cargo = utf8_decode($rqc2['firma2_cargo']);

/* generad de QR */
include_once '../../phpqrcode/qrlib.php';
$file = 'qrcode-codigo-certificado-' . $certificado_id . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
$data = $codigo_certificado.' | '.$certificado_id.' | '.$receptor_de_certificado;
QRcode::png($data, $file_qr_certificado);


/* proceso de impresion solo nombre */
$sw_solo_nombre = $rqc2['sw_solo_nombre'];
if($sw_solo_nombre=='1'){
    $cont_titulo = '';
    $cont_uno = '';
    $cont_dos = '';
    $firma1_nombre = '';
    $firma1_cargo = '';
    $firma2_nombre = '';
    $firma2_cargo = '';
}

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
        $this->Cell(0, 10, 'This certificate has been �  � produced by Desteco SRL', 0, 0, 'R');
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


$pdf->Ln(1);

//DATOS
$pdf->SetTextColor(32, 84, 147);
// Arial 12
$pdf->SetFont('Arial', 'B', 24);
// T�tulo
$pdf->Cell(0, 300, $cont_titulo, 0, 0, 'C');
// Salto de l�nea
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);

//datos participante
$pdf->SetFont('Arial', '', 21);
$pdf->Cell(0, 450, $receptor_de_certificado, 0, 0, 'C');
$pdf->Ln(4);

//datos certificado
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);


$pdf->SetY(-275);
$pdf->Cell(0, 0, $cont_uno, 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-245);
//*$pdf->Cell(0, 0, $cont_dos, 0, 0, 'C');
$pdf->SetX(150);
$pdf->MultiCell(500, 17, $cont_dos, 0, 'C');
//$pdf->Ln(1);
//$pdf->Cell(0, 30, 'Profesionales, T�cnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

$pdf->SetY(-180);
$pdf->Cell(0, -20, $cont_tres, 0, 0, 'C');

/* firma 1 */
if($sw_solo_nombre!=='1'){
$pdf->Line(260,540,390,540);
}
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-70);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, $firma1_nombre, 0, 'C');
$pdf->SetY(-55);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, $firma1_cargo, 0, 'C');

/* firma 2 */
if($sw_solo_nombre!=='1'){
$pdf->Line(480,540,610,540);
}
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-70);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, $firma2_nombre, 0, 'C');
$pdf->SetY(-55);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, $firma2_cargo, 0, 'C');

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-20);
$pdf->Cell(350, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

$pdf->SetY(-100);
/* codigo QR */
$pdf->Image($file_qr_certificado, 685, 510, -80);

$pdf->Output();
?>