<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

class PDF extends FPDF {

    function Footer() {
        $this->SetY(-27);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'This certificate has been produced by Desteco SRL', 0, 0, 'R');
    }

}

$pdf = new FPDF('P', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));

/* registros */
$rqcev1 = query("SELECT p.* FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro INNER JOIN cursos c ON c.id=p.id_curso WHERE r.sw_pago_enviado='1' AND YEAR(c.fecha)=2020 AND (c.titulo LIKE '%aymara%' OR c.titulo LIKE '%quechua%') ORDER BY id DESC limit 2 ");

$cnt_counter_aux = 0;
$auxdata_receptor_de_certificado = 'auxdata';
while ($rqce2 = fetch($rqcev1)) {

    /* certificado id */
    $certificado_id = 0;


    $receptor_de_certificado = utf8_decode(trim(trim($rqce2['prefijo']) . ' ' . trim($rqce2['nombres']) . ' ' . trim($rqce2['apellidos'])));
    $ci_participante = $rqce2['ci'];
    $ci_expedido_participante = $rqce2['ci_expedido'];
    $id_departamento_participante = $rqce2['id_departamento'];


    /* id de modelo de certificado */
    $id_certificado = 0;

    /* datos de certificado */
    $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqc2 = fetch($rqc1);
    $codigo_certificado = '5413216513';
    $cont_tres = 'we6t84w3dg5s16d5g1s3d5g1sd';
    $texto_qr = 'w68reg41s35g1s6d3g13s2dg1';
    $fecha_qr = 'a35f413as21g3';




    //Loading data 
    $pdf->SetTopMargin(20);
    //$pdf->SetLeftMargin(70);
    $pdf->SetLeftMargin(0);
    $pdf->SetRightMargin(10);
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);
    $pdf->Ln(1);

    
    $pdf->SetXY(150, 150);
    $pdf->SetFont('Arial','B',30);
    $pdf->Write(0.1,"CERTIFICADO DE ESTUDIO");
    $pdf->Ln();
    
    
    $pdf->SetXY(200, 200);
    $pdf->setFillColor(230,230,230);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->MultiCell(365,25,'EL INSTITUTO SUPERIOR DE FORMACIÓN SUPERIOR INTERCULTURAL "KHANA MARKA"',0,'J',1);
    
    
    $pdf->SetXY(200, 255);
    $pdf->setFillColor(240,240,240);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(365,5,'precedida por el Msc. Lic. Santiago Condori Apaza con el cargo',0,'J',1);
    
    
    
    //$pdf->Rect($pdf->GetX(),$pdf->GetY(),2,0.1);
    $pdf->SetXY(200, 275);
    $pdf->SetFont('Arial','',12);
    $pdf->Write(0.1,"de ");
    $pdf->SetFont('Arial','B',14);
    $pdf->Write(0.1,"RECTOR. Y  POR  CUANTO EL DERECHO LE");
    $pdf->SetFont('Arial','',12);
    $pdf->Ln();
    
    
    $pdf->SetXY(200, 295);
    $pdf->SetFont('Arial','B',14);
    $pdf->Write(0.1,"FACULTA:");
    $pdf->SetFont('Arial','',12);
    $pdf->Ln();
    

    $pdf->SetXY(120, 370);
    $pdf->SetFont('Arial','B',25);
    $pdf->Write(0.1,"CERTIFICA QUE:");
    $pdf->Ln();
    
    
    
    
    
    $pdf->SetXY(120, 400);
    $pdf->setFillColor(240,40,230);
    $pdf->SetFont('Arial', '', 12);
    $text = '        Que: MARISOL MAMANI CONDORI, con C.I. 7387811  Or. Esta legalmente inscrito en los programas de capacitación: lengua originaria QUECHUA, NIVEL BASICO con matrícula Nº 00001, Registro Nº 00001, el interesado ha concluido satisfactoriamente el curso; el certificado de  APROBACIÓN se otorgara  con 300 horas académicas por la IPELC y el INSTITUTO con una duración de tres meses.';
    $pdf->MultiCell(365,15,$text,0,'J',1);
    
    
    $pdf->SetXY(120, 510);
    $pdf->setFillColor(240,40,230);
    $pdf->SetFont('Arial', '', 12);
    $text = '        Es cuanto certifico en honor a la verdad para fines que convenga al interesado/a.';
    $pdf->MultiCell(365,15,$text,0,'J',1);
    
    
    
    $pdf->Line(120, 550, 470, 550);
    
    
    


    //nombre participante
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 460, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(4);

        
    //datos certificado
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(30, 30, 30);


    /* texto - cont tres */
    $pdf->SetX(150);
    $pdf->SetY(-195 + $l_plus_Y);
    $pdf->Cell(0, -20, $cont_tres, 0, 0, 'C');


    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetY(-17);
    $pdf->SetX(590);
    $pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

    $pdf->SetY(-100);
    

    /* texto solo en impresion multiple (**) */
    $pdf->SetY(-17);
    $pdf->SetX(50);
    $pdf->Cell(100, 0, 'C-' . ++$cnt_counter_aux, 0, 200, 'R');

}

//$pdf->Output();
$modo = "I";
$nombre_archivo = "certs-aux.pdf";
//*$pdf->Output("../../../archivos/pdfcursos/$nombre_archivo", "F");
$pdf->Output($nombre_archivo, $modo);
