<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* datos */
$certificado_id = get('id_certificado');

/* datos de emision */
$rqe1 = query("SELECT * FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' ORDER BY id DESC limit 1 ");
if(num_rows($rqe1)==0){
    echo "ACCESO DENEGADO";
    exit;
}
$rqe2 = fetch($rqe1);

//$receptor_de_certificado = utf8_decode($rqe2['receptor_de_certificado']);

/* datos de receptor de certificado */
$rqdpc1 = query("SELECT nombres,apellidos,prefijo FROM cursos_participantes WHERE id='".$rqe2['id_participante']."' ORDER BY id DESC limit 1 ");
$rqdpc2 = fetch($rqdpc1);
$receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']).' '.trim($rqdpc2['nombres']).' '.trim($rqdpc2['apellidos'])));

/* id de modelo de certificado */
$id_certificado = $rqe2['id_certificado'];

/* datos de certificado */
$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$codigo_certificado = $rqc2['codigo'];

$cont_titulo = utf8_decode($rqe2['cont_titulo']);
$cont_uno = utf8_decode($rqe2['cont_uno']);
$cont_dos = utf8_decode($rqe2['cont_dos']);
$cont_tres = utf8_decode($rqe2['cont_tres']);

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
    $rqfp2 = fetch($rqfp1);
    $firma1_nombre = utf8_decode($rqfp2['nombre']);
    $firma1_cargo = utf8_decode($rqfp2['cargo']);
    $firma1_imagen = utf8_decode($rqfp2['imagen']);
    $firma1_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
    $firma1_top_pixeles = 20;
}
$id_firma2 = $rqc2['id_firma2'];
if($id_firma2!=='0'){
    $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma2' ");
    $rqfp2 = fetch($rqfp1);
    $firma2_nombre = utf8_decode($rqfp2['nombre']);
    $firma2_cargo = utf8_decode($rqfp2['cargo']);
    $firma2_imagen = utf8_decode($rqfp2['imagen']);
    $firma2_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
    $firma2_top_pixeles = 20;
}


/* generad de QR */
include_once '../../../librerias/phpqrcode/qrlib.php';
$file = 'qrcode-codigo-certificado-' . $certificado_id . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
/* nombre del curso */
$rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='".$rqe2['id_curso']."' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$data = $certificado_id.' | '.$rqdc2['titulo'].' | '.$receptor_de_certificado.' | '.$rqdc2['fecha'];
QRcode::png($data, $file_qr_certificado);


/* proceso de impresion solo nombre */
$sw_solo_nombre = $rqe2['sw_solo_nombre'];
if($sw_solo_nombre=='1'){
    $cont_titulo = '';
    $cont_uno = '';
    $cont_dos = '';
    $firma1_nombre = '';
    $firma1_cargo = '';
    $firma2_nombre = '';
    $firma2_cargo = '';
}

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
$pdf->SetTextColor(16, 43, 75);
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


$pdf->SetY(-275);
$pdf->Cell(0, 0, $cont_uno, 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-245);
//*$pdf->Cell(0, 0, $cont_dos, 0, 0, 'C');
$pdf->SetX(115);
//$pdf->MultiCell(500, 17, $cont_dos, 0, 'C');
$pdf->MultiCell(550, 17, $cont_dos, 0, 'C');
$pdf->SetX(150);
//$pdf->Ln(1);
//$pdf->Cell(0, 30, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

//$pdf->SetY(-180);
$pdf->SetY(-195);
$pdf->Cell(0, -20, $cont_tres, 0, 0, 'C');


if($sw_solo_nombre!=='1'){

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

}

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-17);
$pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

$pdf->SetY(-100);
/* codigo QR */
//$pdf->Image($file_qr_certificado, 685, 510, -80);
//$pdf->Image($file_qr_certificado, 690, 510, 95, 95);
$pdf->Image($file_qr_certificado, 350, 40, 100, 100);


$pdf->Output();


?>
