<?php
session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos */
$id_modelo_certificado = get('id_modelo');

$id_certificado = get('id_modelo');



/* datos de certificado */
$rqc1 = query("SELECT * FROM cursos_modelos_certificados WHERE id='$id_modelo_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);

$codigo_certificado = "";

$cont_titulo = utf8_decode($rqc2['cont_titulo']);
$cont_uno = utf8_decode($rqc2['cont_uno']);
$cont_dos = utf8_decode($rqc2['cont_dos']);
$cont_tres = utf8_decode($rqc2['cont_tres']);

$codigo_modelo = utf8_decode($rqc2['codigo']);

$firma1_nombre = utf8_decode($rqc2['firma1_nombre']);
$firma1_cargo = utf8_decode($rqc2['firma1_cargo']);
$firma1_imagen = utf8_decode($rqc2['firma1_imagen']);
$firma1_sw_incluir_nombres = "1";
$firma1_top_pixeles = 0;
$firma2_nombre = utf8_decode($rqc2['firma2_nombre']);
$firma2_cargo = utf8_decode($rqc2['firma2_cargo']);
$firma2_imagen = utf8_decode($rqc2['firma2_imagen']);
$firma2_sw_incluir_nombres = "1";
$firma2_top_pixeles = 0;


/* firmas precreadas */
$id_firma1 = $rqc2['id_firma1'];
$sw_incluir_nombres = false;
if($id_firma1!=='0'){
    $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma1' ");
    $rqfp2 = mysql_fetch_array($rqfp1);
    $firma1_nombre = utf8_decode($rqfp2['nombre']);
    $firma1_cargo = utf8_decode($rqfp2['cargo']);
    $firma1_imagen = utf8_decode($rqfp2['imagen']);
    $firma1_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
    $firma1_top_pixeles = 20;
}
$id_firma2 = $rqc2['id_firma2'];
if($id_firma2!=='0'){
    $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma2' ");
    $rqfp2 = mysql_fetch_array($rqfp1);
    $firma2_nombre = utf8_decode($rqfp2['nombre']);
    $firma2_cargo = utf8_decode($rqfp2['cargo']);
    $firma2_imagen = utf8_decode($rqfp2['imagen']);
    $firma2_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
    $firma2_top_pixeles = 20;
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
        $this->Cell(0, 10, 'This certificate has been ©  © produced by Desteco SRL', 0, 0, 'R');
    }

}

$pdf = new FPDF('L', 'pt', 'Letter');
//Loading data 
$pdf->SetTopMargin(20);
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
$pdf->SetTextColor(16, 43, 75);
// Arial 12
$pdf->SetFont('Arial', 'B', 24);
// Título
$pdf->Cell(0, 300, $cont_titulo, 0, 0, 'C');
// Salto de línea
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
//$pdf->Cell(0, 30, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

$pdf->SetY(-180);
$pdf->Cell(0, -20, '', 0, 0, 'C');

/* firma 1 */
if($firma1_sw_incluir_nombres=='1'){
//$pdf->Line(260,540,390,540);
$pdf->Line(260,525,390,525);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-85);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, $firma1_nombre, 0, 'C');
$pdf->SetY(-70);
$pdf->SetX(200);
$pdf->MultiCell(250, 17, $firma1_cargo, 0, 'C');
}
/* imagen de firma 1 */
$file_imagen_firma_1 = '../../../imagenes/firmas/' . $firma1_imagen;
if(file_exists($file_imagen_firma_1)&&($firma1_imagen!=='')){
$pdf->Image($file_imagen_firma_1, 247, (440+$firma1_top_pixeles), 150 ,100);
}

/* firma 2 */
if($firma2_sw_incluir_nombres=='1'){
//$pdf->Line(480,540,610,540);
$pdf->Line(480,525,610,525);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(-85);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, $firma2_nombre, 0, 'C');
$pdf->SetY(-70);
$pdf->SetX(420);
$pdf->MultiCell(250, 17, $firma2_cargo, 0, 'C');
}
/* imagen de firma 2 */
$file_imagen_firma_2 = '../../../imagenes/firmas/' . $firma2_imagen;
if(file_exists($file_imagen_firma_2)&&($firma2_imagen!=='')){
$pdf->Image($file_imagen_firma_2, 470, (440+$firma1_top_pixeles), 150 ,100);
}


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-20);
$pdf->Cell(350, 0, '', 0, 0, 'C');

$pdf->SetY(-9);
$pdf->Cell(245, 0, $codigo_modelo, 0, 0, 'C');


$pdf->Output();
?>
