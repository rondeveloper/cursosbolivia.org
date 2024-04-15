<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* participantes */
if(isset_get('aprobados') && get('aprobados') == 'true'){
    //$rqe1 = query("SELECT i.*,p.nombres,p.apellidos FROM cursos_examenes_generales_intentos i INNER JOIN cursos_participantes p ON p.id=i.id_participante WHERE 1 ");

    /* PARTICIPACION */
    /* todos (participacion) */
    //$rqe1 = query("SELECT p.nombres,p.apellidos FROM cursos_participantes p WHERE p.id_curso IN (3340,3339,3338,3337) AND p.estado=1 ");

    /* CERT APROBACION*/
    /* solo aprobados, con nota */
    $rqe1 = query("SELECT p.nombres,p.apellidos,ROUND(i.total_correctas/i.total_preguntas*100) AS nota FROM cursos_participantes p LEFT JOIN cursos_examenes_generales_intentos i ON p.id=i.id_participante WHERE p.estado=1 HAVING nota>=80 ");
    //$rqe1 = query("SELECT p.nombres,p.apellidos,ROUND(i.total_correctas/i.total_preguntas*100) AS nota FROM cursos_participantes p LEFT JOIN cursos_examenes_generales_intentos i ON p.id=i.id_participante WHERE p.estado=1 HAVING nota>0 AND nota<80 ");

    /* solo aprobados y no aprobados, sin nota */
    //$rqe1 = query("SELECT p.nombres,p.apellidos,ROUND(i.total_correctas/i.total_preguntas*100) AS nota FROM cursos_participantes p LEFT JOIN cursos_examenes_generales_intentos i ON p.id=i.id_participante WHERE p.id_curso IN (3340,3339,3338,3337) AND p.estado=1 HAVING nota<70 OR nota IS NULL ");
}else{
    $id_intento = get('id_intento');
    /* datos de emision */
    $rqe1 = query("SELECT i.*,p.nombres,p.apellidos FROM cursos_examenes_generales_intentos i INNER JOIN cursos_participantes p ON p.id=i.id_participante WHERE i.id='$id_intento' ");
    if (num_rows($rqe1) == 0) {
        echo "ACCESO DENEGADO";
        exit;
    }
}

$pdf = new FPDF('P', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));
//Loading data 
$pdf->SetTopMargin(20);
//$pdf->SetLeftMargin(70);
$pdf->SetLeftMargin(0);
$pdf->SetRightMargin(10);

while($rqe2 = fetch($rqe1)){
    $nombre_participante = utf8_decode($rqe2['nombres'].' '.$rqe2['apellidos']);

    $pdf->AddPage();

    $pdf->SetAutoPageBreak(false);

    /* IMAGEN BACKGROUND PLANTILLA FONDO FISICO */
    $id_fondo_fisico = $rqe2['id_fondo_fisico'];
    $file_imagen_background = '../../../imagenes/cursos/certificados/certificado-camara-senadores-2.png';
    //$file_imagen_background = '../../../imagenes/cursos/certificados/certificado-camara-senadores.png';
    $pdf->Image($file_imagen_background, -3, 0, 623, 810, 'PNG');


    $pdf->Ln(1);

    //DATOS
    //$pdf->SetTextColor(16, 43, 75);
    $pdf->SetTextColor(0, 0, 0);
    // Arial 12
    $pdf->SetFont('Arial', 'B', 20);
    /* Título */
    $pdf->Cell(0, 595, "        ".$nombre_participante, 20, 0, 'C');
    /* Salto de linea */
    $pdf->Ln(4);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetY(365);
    $pdf->SetX(55);
    $text = "Por haber aprobado el curso de RELACIONES HUMANAS Y PUBLICAS con una nota de ".$rqe2['nota']." puntos sobre 100, curso organizado por la Unidad de Desarrollo, Evaluación y Capacitación de Personal de la Cámara de Senadores y la Consultora de Capacitación NEMABOL, del 29 de agosto al 02 de septiembre del presente año. Con una duración de 5 horas académicas.";
    //$text = "Por haber aprobado el curso de RELACIONES HUMANAS Y PUBLICAS, curso organizado por la Unidad de Desarrollo, Evaluación y Capacitación de Personal de la Cámara de Senadores y la Consultora de Capacitación NEMABOL, del 29 de agosto al 02 de septiembre del presente año. Con una duración de 5 horas académicas.";
    $text = utf8_decode($text);
    $pdf->SetTextColor(0, 0, 0);
    // Arial 12
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(500,17,$text, '', 'L', 0);
    $pdf->Ln(4);
    $pdf->SetTextColor(0, 0, 0);
    
}

/* output */
$pdf->Output();
