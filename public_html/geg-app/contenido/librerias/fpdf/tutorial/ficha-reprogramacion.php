<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

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


/* data */
$codigo_reprogramacion = '0';
if (isset_get('codigo')) {
    $codigo_reprogramacion = get('codigo');
}

/* start */

$pdf = new FPDF('P', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));

/* helpers */
$meses = array('None','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');

/* PRIMERA PAGINA */

/* certificado id */
$codigo = "CD23";

/* registro reprogramacion */
$rqdrpp1 = query("SELECT * FROM cursos_reprogramacion_participantes WHERE codigo='$codigo_reprogramacion' LIMIT 1 ");
if(mysql_num_rows($rqdrpp1)==0){
    echo "REGISTRO NO ENCONTRADO";
    exit;
}
$rqdrpp2 = mysql_fetch_array($rqdrpp1);
$id_reprogramacion = $rqdrpp2['id'];
$id_curso = $rqdrpp2['id_curso'];
$id_participante = $rqdrpp2['id_participante'];
$d_fecha_limite = date("d", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));
$m_fecha_limite = date("m", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));
$Y_fecha_limite = date("Y", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));

$fecha_limite = $d_fecha_limite." / ". ucfirst($meses[(int)($m_fecha_limite)])." / ".$Y_fecha_limite;

/* curso */
$rqdc1 = query("SELECT titulo,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = mysql_fetch_array($rqdc1);
$titulo_curso = $rqdc2['titulo'];
$id_ciudad_curso = $rqdc2['id_ciudad'];

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = mysql_fetch_array($rqdp1);
$nombre_participante = $rqdp2['nombres'].' '.$rqdp2['apellidos'];
$ci_participante = $rqdp2['ci'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];

/* proceso regsitro */
$rqdprp1 = query("SELECT codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$rqdprp2 = mysql_fetch_array($rqdprp1);
$codigo_registro = $rqdprp2['codigo'];

/* ciudad */ 
$rqddc1 = query("SELECT nombre FROM ciudades WHERE id='$id_ciudad_curso' LIMIT 1 ");
$rqddc2 = mysql_fetch_array($rqddc1);
$nombre_ciudad = $rqddc2['nombre'];

/* departamento */ 
$rqdd1 = query("SELECT nombre FROM departamentos WHERE id=(SELECT id_departamento FROM ciudades WHERE id='$id_ciudad_curso' ) LIMIT 1 ");
$rqdd2 = mysql_fetch_array($rqdd1);
$nombre_departamento = $rqdd2['nombre'];



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
$pdf->SetFont('Arial', 'B', 17);


$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);


/* TEXTO FIRMA */
$pdf->SetY(450);
$pdf->SetX(0);
$pdf->Cell(0, 130, $nombre_participante, 0, 0, 'C');
$pdf->Ln(20);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 130, $ci_participante, 0, 0, 'C');
$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(0, 0, 0);

/* TEXTO */
$pdf->SetY(0);
$pdf->SetX(150);
$pdf->SetTextColor(13, 81, 96);
$pdf->MultiCell(0, 70, "DOC/REP/00$id_reprogramacion", 0, 'R');
$pdf->SetTextColor(0, 0, 0);


/* TEXTO */
$pdf->SetY(55);
$pdf->SetX(0);
$pdf->SetTextColor(13, 81, 96);
$pdf->SetFont('Arial', '', 17);
$pdf->MultiCell(0, 85, "COMPROMISO DE ASISTENCIA A CURSO", 0, 'C');
$pdf->SetTextColor(0, 0, 0);


/* TEXTO */
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY(10);
$pdf->SetX(0);
$pdf->Cell(0, 240, $titulo_curso, 0, 0, 'C');


/* logo */
$file_qr_certificado = '../../../alt/logotipo-v2.png';
$pdf->Image($file_qr_certificado, 20, 20, 150, 50);

/* TEXTO COMPROMISO */
$texto_compromiso = 'El presente documento establece una obligación por parte de el ciudadano '.$nombre_participante.' identificándose con el documento nacional de identidad número '.$ci_participante.', el cual se compromete a realizar el curso "'.$titulo_curso.'" de forma presencial en alguno de los cursos organizados por NEMABOL en próximas fechas a partir del día que se sucita este documento, dando como tiempo límite dos meses impostergablemente.
    
Asimismo, se responsabiliza de realizar la inscripción correspondiente al curso, como también quedar al pendiente y estar enterado de las fechas cuando se realiza el curso. También acepta que se tomen las medidas pertinentes del caso, si no cumple con la correspondiente asistencia en el tiempo y forma estipulado. 

Para constancia se establece la firma correspondiente en la ciudad de '.$nombre_ciudad.', a los '.((int)date('d')).' días del més de '.$meses[(int)date('m')].' del '.date('Y').'.';
$pdf->SetFont('Arial', '', 11);
$pdf->SetY(160);
$pdf->SetX(25);
$pdf->MultiCell(550, 14, $texto_compromiso, 0, 'L');



/* SEGUNDA PAGINA */

/* generad de QR */
$file = 'qrcode-reprogramacion-' . $codigo_reprogramacion . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
/* nombre del curso */
$data = $codigo_reprogramacion . ' | ' . $id_participante . ' | ' . $titulo_curso . '|V:' . $fecha_limite;
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
$pdf->MultiCell(400, 20, $titulo_curso, 50, 'C');
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
$pdf->Cell(0, 130, "$codigo_reprogramacion | $nombre_participante", 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(0);
$pdf->SetX(160);
$pdf->SetTextColor(13, 81, 96);
$pdf->MultiCell(0, 80, "FICHA DE INGRESO", 0, 'C');
$pdf->SetTextColor(0, 0, 0);
//$pdf->Ln(1);
//$pdf->Cell(0, 30, 'Profesionales, Técnicos y Consultores", con una carga horaria de 8 horas.', 0, 0, 'C');

/* $pdf->SetY(-180); */
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY(-0);
$pdf->SetX(160);
$pdf->Cell(0, 350, 'Esta ficha tiene validez hasta: '.$fecha_limite, 0, 0, 'C');



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
$pdf->MultiCell(550, 14, 'Esta ficha constituye el ingreso a algún curso relacionado al mencionado en la parte superior, ingreso libre correspondiente a la reprogramación de asistencia del registro '.$codigo_registro.'.', 0, 'L');




/* SALIDA */
$pdf->Output();

