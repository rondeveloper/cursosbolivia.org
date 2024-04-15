<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* datos */
$id_certificado = get('id_certificado');

/* datos de certificado */
$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$codigo_certificado = $rqc2['codigo'];
$cont_titulo = $rqc2['cont_titulo'];
$cont_uno = utf8_decode($rqc2['cont_uno']);
$cont_dos = utf8_decode($rqc2['cont_dos']);
$cont_tres = utf8_decode($rqc2['cont_tres']);

session_start();
$name = $_SESSION['username'];
$id = $_SESSION['userid'];
$fullname = $_SESSION['full'];

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-27);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'This certificate has been produced by Desteco SRL', 0, 0, 'R');
    }

}

$pdf = new FPDF('L', 'pt', 'Letter');
//Loading data 
$pdf->SetTopMargin(20);
$pdf->SetLeftMargin(70);
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
/* titulo */
$pdf->Cell(0, 300, $cont_titulo, 0, 0, 'C');
/* Salto de linea */
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);

//datos participante
$pdf->SetFont('Arial', '', 21);
$pdf->Cell(0, 450, $receptor_de_certificado, 0, 0, 'C');
$pdf->Ln(4);

//datos certificado
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);


$pdf->SetY(-280);
$pdf->Cell(0, 0, $cont_uno, 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-240);
//*$pdf->Cell(0, 0, $cont_dos, 0, 0, 'C');
$pdf->SetX(150);
$pdf->MultiCell(550, 17, $cont_dos, 0, 'C');
//$pdf->Ln(1);
//$pdf->Cell(0, 30, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

$pdf->SetY(-170);
$pdf->Cell(0, -20, '', 0, 0, 'C');


/* firma 1 */
$pdf->Line(260,540,390,540);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-70);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, 'Ing. Edgar Aliaga Chipana', 0, 'C');
$pdf->SetY(-55);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, ' Gerente Ejecutivo', 0, 'C');

/* firma 2 */
$pdf->Line(485,540,605,540);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-70);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, 'Lic. Alain Estévez Sandi', 0, 'C');
$pdf->SetY(-55);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, ' Facilitador', 0, 'C');



$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-20);
$pdf->Cell(350, 0, '', 0, 0, 'C');


$pdf->Output();
?>
