<?php
session_start();
include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* carga composer autoload */
require_once $___path_raiz."../vendor/autoload.php";

/* datos */
$ids_participante = get('ids_participante');

$pdf = new FPDF('P', 'pt', 'Letter');  //crea el objeto
//$pdf = new FPDF('L', 'pt', array(612,385));  //crea el objeto

$rqef1 = query("SELECT p.nombres,p.apellidos,p.ci,p.celular,c.titulo,r.monto_deposito,(d.nombre)dr_departamento 
FROM cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id 
INNER JOIN departamentos d ON d.id=p.id_departamento 
INNER JOIN cursos c ON p.id_curso=c.id
 WHERE p.id IN ($ids_participante) 
 ORDER BY p.id DESC ");

 $cnt = 0;
 //$pdf->AddPage();
 $pos_top = 0;

while($rqef2 = fetch($rqef1)){

$nro_recibo = abs($id_participante-35000);
$nombre_receptor = utf8_decode($rqef2['nombres'].' '.$rqef2['apellidos']);
$ci_receptor = $rqef2['ci'];
$celular_receptor = $rqef2['celular'];
$departamento = strtoupper($rqef2['dr_departamento']);
//$total = (int)$rqef2['monto_deposito'];
$total = 0;
$a_cuenta = 0;
$saldo = 0;
$concepto = $rqef2['titulo'];
if(strpos('---'.strtolower($concepto), 'curso')==0){
    $concepto = 'Curso '.$rqef2['titulo'];
}
$concepto = 'RECEPCION DOCUMENTACION IPELC 4 fotos, deposito IPELC, fotocopia CI, Fotocopia Titulo Profesional ';
$fecha_emision = date("Y-m-d");

if(($cnt%4)==0){
    $pdf->AddPage();  //añadimos una página. Origen coordenadas, esquina superior izquierda, posición por defeto a 1 cm de los bordes.
    $pos_top = 0;
}
$cnt++;


$pdf->SetAutoPageBreak(false);

/* marco principal */
$pdf->Rect(20,$pos_top+20,570,145,"D");

/* dato 1 */
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetXY(236, $pos_top+20);
$pdf->Cell(100, 50, $departamento, 0, 2, "C");

/* dato 2 */
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(236, $pos_top+50);
$pdf->Cell(100, 50,$nombre_receptor, 0, 2, "C");

/* dato 3 */
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(236, $pos_top+75);
$pdf->Cell(100, 50, 'C.I.:  '.$ci_receptor, 0, 2, "C");

/* dato 4 */
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(236,$pos_top+100);
$pdf->Cell(100, 50,'Celular:  '.$celular_receptor, 0, 2, "C");

/* dato 5 */
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetXY(236, $pos_top+125);
$pdf->Cell(100, 50,'Remitente:  NEMABOL 69713008', 0, 2, "C");

$pos_top += 200;

}


$pdf->Output();



