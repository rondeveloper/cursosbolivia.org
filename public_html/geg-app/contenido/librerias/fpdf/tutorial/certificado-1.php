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


require('../fpdf.php');

class PDF extends FPDF {

// Cabecera de página
    function Header() {
        /* Logo */
        //$this->Image('marco-1.png', 0,0,-127);
        //$this->Image('marco-1.png', 1, 0, -103);

        /* imagen logotipo 1 */
        //$this->Image('logo_infosicoes.png', 45, 30, -90);

        /* imagen logotipo 2 */
        //$this->Image('logo_nemabol.jpg', 155, 30, -145);

        /* Arial bold 15 */
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        /* Título */
        //*$this->Cell(30, 10, 'Title', 1, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

/* Pie de página */
//    function Footer() {
//        // Posición: a 1,5 cm del final
//        $this->SetY(-15);
//        // Arial italic 8
//        $this->SetFont('Arial', 'I', 12);
//        // Número de página
//        //$this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
//        $this->Cell(0, 14, 'ID de certificado: 1100121515141 ', 0, 0, 'C');
//    }

}

/* Creación del objeto de la clase heredada */
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

//DATOS
$pdf->SetTextColor(32, 84, 147);
// Arial 12
$pdf->SetFont('Arial', 'B', 22);
// Título
$pdf->Cell(0, 70, 'CERTIFICADO DE PARTICIPACION', 0, 0, 'C');
// Salto de línea
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);

//datos participante
$pdf->SetFont('Arial', '', 21);
$pdf->Cell(0, 100, 'LUCIA ESTRADA ARTEAGA', 0, 0, 'C');
$pdf->Ln(4);

//datos certificado
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);
$pdf->Cell(0, 130, 'Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:', 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 140, '"Curso taller especializado del SABS, SICOES e INFOSICOES orientado a', 0, 0, 'C');
$pdf->Ln(4);
$pdf->Cell(0, 141, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');
$pdf->Ln(1);
$pdf->Cell(0, 145, 'Realizado en Potosí, Bolivia a los 26 dias del mes de Abril de 2017', 0, 0, 'C');

/* codigo QR */
$pdf->Image('jr-qrcode.png', 255, 170, -145);



//for ($i = 1; $i <= 7; $i++) {
//    $pdf->Cell(0, 10, 'Imprimiendo línea número ' . $i, 0, 1);
//}

$pdf->Output();
?>
