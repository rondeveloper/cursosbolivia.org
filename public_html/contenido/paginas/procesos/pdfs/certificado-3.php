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
if (num_rows($rqe1) == 0) {
    echo "ACCESO DENEGADO";
    exit;
}
$rqe2 = fetch($rqe1);
$id_emision_certificado = $rqe2['id'];

//$receptor_de_certificado = utf8_decode($rqe2['receptor_de_certificado']);

/* datos de receptor de certificado */
$rqdpc1 = query("SELECT nombres,apellidos,ci,ci_expedido,prefijo,cnt_impresion_certificados,id_curso,id_departamento FROM cursos_participantes WHERE id='" . $rqe2['id_participante'] . "' ORDER BY id DESC limit 1 ");
$rqdpc2 = fetch($rqdpc1);
$receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']) . ' ' . trim($rqdpc2['nombres']) . ' ' . trim($rqdpc2['apellidos'])));
$ci_participante = $rqdpc2['ci'];
$ci_expedido_participante = $rqdpc2['ci_expedido'];
$id_departamento_participante = $rqdpc2['id_departamento'];

/* incremento de numero de impresion cert participante */
$cnt_impresion_certificados = $rqdpc2['cnt_impresion_certificados'] + 1;
query("UPDATE cursos_participantes SET cnt_impresion_certificados='$cnt_impresion_certificados',ultima_impresion_certificado='" . date("Y-m-d H:i") . "' WHERE id='" . $rqe2['id_participante'] . "' LIMIT 1 ");

/* id de modelo de certificado */
$id_certificado = $rqe2['id_certificado'];
$fecha_qr = utf8_decode($rqe2['fecha_qr']);
$fecha2_qr = utf8_decode($rqe2['fecha2_qr']);

/* datos de certificado */
$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$codigo_certificado = $rqc2['codigo'];

/* fechas_inicio_final */
$dia_curso = date("d", strtotime($fecha_qr));
$mes_curso = date("m", strtotime($fecha_qr));
$anio_curso = date("Y", strtotime($fecha_qr));
$array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
if($fecha2_qr=='0000-00-00' || $fecha2_qr==$fecha_qr){
    $fechas_inicio_final = ('a los '.$dia_curso.' días del mes de '.$array_meses[(int) $mes_curso].' de '.$anio_curso);
}else{
    $dia2_curso = date("d", strtotime($fecha2_qr));
    $mes2_curso = date("m", strtotime($fecha2_qr));
    $anio2_curso = date("Y", strtotime($fecha2_qr));
    if($mes_curso==$mes2_curso){
        $fechas_inicio_final = 'del '.$dia_curso.' al '.$dia2_curso.' de '.$array_meses[(int) $mes_curso].' de '.$anio_curso;
    }else{
        $fechas_inicio_final = 'del '.$dia_curso.' de '.$array_meses[(int) $mes_curso].' al '.$dia2_curso.' de '.$array_meses[(int)$mes2_curso].' de '.$anio2_curso;
    }
}

/* nota */
$nota_participante = 100;
$rqdnm1 = query("SELECT nota FROM participantes_notas_manuales WHERE id_participante='".$rqe2['id_participante']."' ORDER BY id DESC limit 1 ");
if(num_rows($rqdnm1)>0){
    $rqdnm2 = fetch($rqdnm1);
    $nota_participante = (int)$rqdnm2['nota'];
}

$cont_titulo = utf8_decode($rqe2['cont_titulo']);
$cont_uno = utf8_decode($rqe2['cont_uno']);
$cont_dos = utf8_decode(str_replace('[NOTA-PARTICIPANTE]',$nota_participante.'/100',$rqe2['cont_dos']));
$cont_tres = utf8_decode(str_replace('[FECHAS-INICIO-FINAL]',$fechas_inicio_final,$rqe2['cont_tres']));
$texto_qr = utf8_decode($rqe2['texto_qr']);

/* departamento de participante -> para certificado digital */
if($id_departamento_participante!='0'){
    $rqddp1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_participante' LIMIT 1 ");
    $rqddp2 = fetch($rqddp1);
    $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]',$rqddp2['nombre'].', Bolivia', $cont_tres);
}else{
    $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]','Bolivia',$cont_tres);
}

/* $codnum_c SIMULADO */
$codnum_c = rand(0, 9);
$rqce1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id IN (select id_emision_certificado from cursos_participantes where id_curso='" . $rqdpc2['id_curso'] . "' and estado='1' ) ORDER BY id_participante ASC limit 300 ");
$cnt_counter_aux = 0;
while ($rqce2 = fetch($rqce1)) {
    ++$cnt_counter_aux;
    if ($rqce2['certificado_id'] == $certificado_id) {
        $codnum_c = $cnt_counter_aux;
    }
}
/* END $codnum_c SIMULADO */


/* sw_ignorar_firmas */
$sw_ignorar_firmas = false;
if (isset_get('sw_ignorar_firmas') && get('sw_ignorar_firmas')=='1') {
    $sw_ignorar_firmas = true;
}


/* generad de QR */
include_once '../../../librerias/phpqrcode/qrlib.php';
$file = 'qrcode-codigo-certificado-' . $certificado_id . '.png';
$certificado_id = str_replace('CD','CB',$certificado_id);
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
/* data del curso */
$id_curso = $rqe2['id_curso'];
$rqdc1 = query("SELECT titulo,fecha,numero FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$numeracion_curso = $rqdc2['numero'];
if ($fecha_qr == '0000-00-00') {
    $fecha_qr = $rqdc2['fecha'];
}
$data = $certificado_id . ' | ' . $texto_qr . ' | ' . $receptor_de_certificado . ' | CI ' . $ci_participante . ' | ' . $fecha_qr;
QRcode::png($data, $file_qr_certificado);


/* proceso de impresion solo nombre */
$sw_solo_nombre = $rqe2['sw_solo_nombre'];
if ($sw_solo_nombre == '1') {
    $cont_titulo = '';
    $cont_uno = '';
    $cont_dos = '';
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

// imagen con o sin R.M.
$fecha_emision = $rqe2['fecha_emision'];
$imagen_certificado = 'imagen';
$f_limite = '2024-11-20';
$date = $fecha_emision;
if(strtotime($date) < strtotime($f_limite)){
$imagen_certificado = 'imagen_sin_rm';
}
/* IMAGEN BACKGROUND PLANTILLA FONDO FISICO */
$id_fondo_fisico = $rqe2['id_fondo_fisico'];
if($id_fondo_fisico<>0 && $sw_ignorar_firmas==false){
    $rqdfc1 = query("SELECT $imagen_certificado FROM certificados_imgfondo WHERE id='$id_fondo_fisico' LIMIT 1 ");
    $rqdfc2 = fetch($rqdfc1);
    $file_imagen_background = '../../../imagenes/cursos/certificados/'.$rqdfc2[$imagen_certificado];
    $pdf->Image($file_imagen_background, 0, 0, 800, 620, 'PNG');
}


$pdf->Ln(1);

//DATOS
$pdf->SetTextColor(16, 43, 75);
// Arial 12
$pdf->SetFont('Arial', 'B', 24);
/* Título */
$pdf->Cell(0, 300, $cont_titulo, 0, 0, 'C');
/* Salto de linea */
$pdf->Ln(4);
$pdf->SetTextColor(0, 0, 0);

//datos participante
if ($ci_participante !== '' && $ci_participante !== '0') {
    //ci participante
    $array_expedido_base = array('LP', 'OR', 'PS', 'PT', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH');
    $array_expedido_short = array('LP', 'OR', 'PT', 'PS', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
    $array_expedido_literal = array('La Paz', 'Oruro', 'Potosi', 'Potosi', 'Cochabamba', 'Santa Cruz', 'Beni', 'Pando', 'Tarija', 'Chuquisaca');
    if ($ci_expedido_participante == 'Cod. QR') {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante $ci_expedido_participante";
    } elseif ($ci_expedido_participante !== '') {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante expedido en " . strtoupper(str_replace($array_expedido_base, $array_expedido_literal, $ci_expedido_participante));
    } else {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante";
    }

    //nombre participante
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 440, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(1);
    //$pdf->Ln(4);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 515, utf8_decode($texto_cedula_identidad), 0, 0, 'C');
    //$pdf->Cell(0, 545, utf8_decode($texto_cedula_identidad), 0, 0, 'C');
} else {
    //nombre participante
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 460, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(4);
}

//datos certificado
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(30, 30, 30);

/* texto - cont uno */
$pdf->SetY(-275);
$pdf->Cell(0, 0, $cont_uno, 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);

/* texto - cont dos */
$l_plus_Y = 0;
if (strlen($cont_dos) > 240) {
    $pdf->SetY(-250);
    $pdf->SetX(65);
    $pdf->MultiCell(650, 17, $cont_dos, 0, 'C');
    $l_plus_Y = 25;
} else if (strlen($cont_dos) > 95) {
    $ar_aux_tl = explode('con una carga horaria', $cont_dos);
    if (count($ar_aux_tl) == 2) {
        $cont_dos_A = trim($ar_aux_tl[0]);
        $cont_dos_B = 'con una carga horaria'.$ar_aux_tl[1];
        $pdf->SetY(-250);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $cont_dos_A, 0, 'C');
        $pdf->SetY(-225);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $cont_dos_B, 0, 'C');
        $l_plus_Y = 15;
    } else {
        $pdf->SetY(-250);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $cont_dos, 0, 'C');
    }
} else {
    $pdf->SetY(-250);
    $pdf->SetX(65);
    $pdf->MultiCell(650, 17, $cont_dos, 0, 'C');
}

/* texto - cont tres */
$pdf->SetX(150);
$pdf->SetY(-195 + $l_plus_Y);
$pdf->Cell(0, -20, $cont_tres, 0, 0, 'C');




$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(-27);
$pdf->SetX(590);
$pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

$pdf->SetY(-100);
/* codigo QR */
//$pdf->Image($file_qr_certificado, 685, 510, -80);
//$pdf->Image($file_qr_certificado, 690, 510, 95, 95);
//$pdf->Image($file_qr_certificado, 350, 40, 100, 100);
$pdf->Image($file_qr_certificado, 637, 460, 115, 115);


/* texto solo en impresion multiple (**) */
$pdf->SetY(-27);
$pdf->SetX(50);
$pdf->Cell(100, 0, 'C-' . $codnum_c . '-' .$numeracion_curso, 0, 200, 'R');

//$pdf->Output();


/* adminsitrador */
$administrador = 'sin-nombre';
$id_administrador = '0';
if (isset_administrador()) {
    $id_administrador = administrador('id');
    $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1");
    $rqda2 = fetch($rqda1);
    $administrador = limpiar_enlace($rqda2['nombre']);
}
if(isset_get('id_administrador') && (get('hash')==md5(get('id_administrador').'hash'))){
    $id_administrador = get('id_administrador');
    $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1");
    $rqda2 = fetch($rqda1);
    $administrador = limpiar_enlace($rqda2['nombre']);
}


/* registro pdf generado (discontinuado) */
query("INSERT INTO cursos_pdf_generados(id_administrador, id_curso, nombre, fecha) VALUES ('$id_administrador','$id_curso','discontinuado',NOW()) ");
//*logcursos('Generado de PDF [' . $nombre_archivo . ']', 'curso-pdf', 'curso', $id_curso);

/* output */
$pdf->Output();
