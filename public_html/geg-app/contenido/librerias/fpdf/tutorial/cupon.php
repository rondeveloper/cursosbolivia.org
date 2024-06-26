<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/* datos */
$id_cupon = '1';

$codigo_cupon = '0';
if (isset_get('data')) {
    $codigo_cupon = get('data');
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
        $this->Cell(0, 10, 'This certificate has been �  � produced by Desteco SRL', 0, 0, 'R');
    }

}

//$pdf = new FPDF('P', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));
$pdf = new FPDF('L','pt',array(260,600));



/* registro */
$rqce1 = query("SELECT * FROM emisiones_cupones WHERE codigo='$codigo_cupon' ORDER BY id DESC limit 1 ");
$cnt_counter = mysql_num_rows($rqce1);
if ($cnt_counter == 0) {
    //echo "<br/><hr/><h3>ACCESO DENEGADO</h3><hr/><br/>";
    echo "<br/><hr/><h3>CUP&Oacute;N NO VALIDO</h3><hr/><br/>";
    exit;
}

$cnt_counter_aux = 0;
while ($rqce2 = mysql_fetch_array($rqce1)) {

    /* certificado id */
    $codigo = $rqce2['codigo'];
    
    
    /* verficacion de cupon */
    //$rqdcd1 = query("SELECT * FROM cursos_cupones WHERE id='$id_cupon' LIMIT 1 ");
    //$rqdcd2 = mysql_fetch_array($rqdcd1);
    
    $detalle_cupon = '50% de descuento para cursos virtuales en Cursos.bo';
    $fecha_expiracion_cupon = '2020-05-31';
    $porcentaje_descuento_cupon = 50;
  
    /* usuario */
    $rqddupc1 = query("SELECT nombre,ci FROM usuarios WHERE id='".$rqce2['id_usuario']."' LIMIT 1 ");
    $rqddupc2 = mysql_fetch_array($rqddupc1);

    $codigo_certificado = $codigo;
    $cont_titulo = $detalle_cupon;
    $cont_uno = 'Descuento por el '.$porcentaje_descuento_cupon.' %';
    $cont_dos =  'CODIGO DE CUP�N: '.$codigo;
    $cont_tres =  'Este cup�n tiene validez hasta: '. date("d / m / Y",strtotime($fecha_expiracion_cupon));
    $cont_nomuser =  $rqddupc2['nombre'].' | '.$rqddupc2['ci'];

    $firma1_nombre ='Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma1_cargo = 'Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma1_imagen = 'Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma1_sw_incluir_nombres = "1";
    $firma1_top_pixeles = 0;
    $firma2_nombre = 'Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma2_cargo = 'Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma2_imagen = 'Texto Texto Texto Texto Texto Texto Texto Texto Texto';
    $firma2_sw_incluir_nombres = "1";
    $firma2_top_pixeles = 0;


    /* generad de QR */
    $file = 'qrcode-codigo-cupon-descuento-' . $codigo . '.png';
    $file_qr_certificado = '../../../imagenes/qrcode/' . $file;
    if (!is_file($file_qr_certificado)) {
        copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
    }
    /* nombre del curso */
    $rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);
    $data = $codigo.'|50% de descuento|cursos.bo';
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
// T�tulo
    
    $pdf->SetY(90);
    $pdf->SetX(185);
    $pdf->MultiCell(400, 20, $cont_titulo, 50, 'C');
// Salto de l�nea
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
//$pdf->Cell(0, 30, 'Profesionales, T�cnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

    /* $pdf->SetY(-180); */
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetY(-0);
    $pdf->SetX(160);
    $pdf->Cell(0, 350, $cont_tres, 0, 0, 'C');
    
    
    $pdf->SetFont('Arial', '', 14);
    $pdf->SetY(-0);
    $pdf->SetX(160);
    $pdf->Cell(0, 300, $cont_nomuser, 0, 0, 'C');


    

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
    $file_qr_certificado = '../../../imagenes/images/logotipo.PNG';
    $pdf->Image($file_qr_certificado, 20, 20, 150, 50);
    
    
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetY(190);
    $pdf->SetX(25);
    $pdf->MultiCell(550, 14, 'Este cup�n constituye un porcentaje de descuento para '.ucwords(strtolower($cont_nomuser)).' en alg�n curso habilitado a realizarse en pr�ximas fechas en la plataforma cursos.bo', 0, 'L');
    
    
    $pdf->Line(20,230, 580, 230);



    /* texto solo en impresion multiple */
    //$pdf->SetY(-17);
    //$pdf->SetX(50);
    //$pdf->Cell(100, 0, 'L-' . ++$cnt_counter_aux, 0, 200, 'R');
}


$pdf->Output();

