<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* generad de QR */
include_once '../../../librerias/phpqrcode/qrlib.php';

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


/* data */
$id_compromiso = get('id_compromiso');
$hash = get('hash');

if($hash!=md5(md5($id_compromiso."0012151"))){
    echo "DENEGADO";
    exit;
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
$rqdrpp1 = query("SELECT * FROM compromisos_finalizacion WHERE id='$id_compromiso' LIMIT 1 ");
if(num_rows($rqdrpp1)==0){
    echo "REGISTRO NO ENCONTRADO";
    exit;
}
$rqdrpp2 = fetch($rqdrpp1);
$id_reprogramacion = $rqdrpp2['id'];
$id_curso = $rqdrpp2['id_curso'];
$id_participante = $rqdrpp2['id_participante'];
$d_fecha_limite = date("d", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));
$m_fecha_limite = date("m", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));
$Y_fecha_limite = date("Y", strtotime("+2 month",strtotime($rqdrpp2['fecha_registro'])));

$fecha_limite = $d_fecha_limite." / ". ucfirst($meses[(int)($m_fecha_limite)])." / ".$Y_fecha_limite;

/* curso */
$rqdc1 = query("SELECT titulo,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];
$id_ciudad_curso = $rqdc2['id_ciudad'];

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
$nombre_participante = $rqdp2['nombres'].' '.$rqdp2['apellidos'];
$ci_participante = $rqdp2['ci'];
$id_proceso_registro_participante = $rqdp2['id_proceso_registro'];
$id_departamento_participante = $rqdp2['id_departamento'];

/* proceso regsitro */
$rqdprp1 = query("SELECT codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
$rqdprp2 = fetch($rqdprp1);
$codigo_registro = $rqdprp2['codigo'];

/* departamento */ 
$rqdd1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_participante' LIMIT 1 ");
$rqdd2 = fetch($rqdd1);
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
$pdf->MultiCell(0, 70, "DOC/CFN/00$id_reprogramacion", 0, 'R');
$pdf->SetTextColor(0, 0, 0);


/* TEXTO */
$pdf->SetY(55);
$pdf->SetX(0);
$pdf->SetTextColor(13, 81, 96);
$pdf->SetFont('Arial', '', 17);
$pdf->MultiCell(0, 85, utf8_decode("COMPROMISO DE FINALIZACIÓN DE CURSO"), 0, 'C');
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
$texto_compromiso = 'El presente documento establece una obligación por parte de el ciudadano '.$nombre_participante.' identificándose con el documento nacional de identidad Nº '.$ci_participante.', el cual se compromete a finalizar el curso "'.$titulo_curso.'" de forma online en el curso virtual asignado a su cuenta o presencial en alguno de los cursos organizados por NEMABOL en próximas fechas a partir del día que se sucita este documento, dando como tiempo límite tres meses impostergablemente.
    
Así mismo, se responsabiliza a finalizar todos los modulos asignados al curso en modalidad virtual. También acepta que se tomen las medidas pertinentes del caso, si no cumple con la correspondiente finalización en el tiempo y forma estipulado. 

Para constancia se establece la firma correspondiente en la ciudad de '.$nombre_departamento.', a los '.((int)date('d')).' días del mes de '.$meses[(int)date('m')].' del '.date('Y').'.';
$pdf->SetFont('Arial', '', 11);
$pdf->SetY(160);
$pdf->SetX(25);
$pdf->MultiCell(550, 14, utf8_decode($texto_compromiso), 0, 'L');




/* SALIDA */
$pdf->Output();

