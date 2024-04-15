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
$nom_file_participante = str_replace(' ','-',trim(explode(' ',$rqdpc2['nombres'])[0].substr($rqdpc2['apellidos'],0,1).'-'.$rqdpc2['id_curso']));

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

$id_fondo_digital = $rqe2['id_fondo_digital'];
//$cont_titulo = utf8_decode($rqe2['cont_titulo']);
$cont_titulo = utf8_decode($rqe2['cont_titulo']);
$cont_uno = utf8_decode($rqe2['cont_uno']);
$cont_dos = utf8_decode(str_replace('[NOTA-PARTICIPANTE]',$nota_participante.'/100',$rqe2['cont_dos']));
$cont_tres = utf8_decode(str_replace('[FECHAS-INICIO-FINAL]',$fechas_inicio_final,$rqe2['cont_tres']));
$texto_qr = utf8_decode($rqe2['texto_qr']);

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

/* departamento de participante -> para certificado digital */
if($id_departamento_participante!='0'){
    $rqddp1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_participante' LIMIT 1 ");
    $rqddp2 = fetch($rqddp1);
    $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]',$rqddp2['nombre'].', Bolivia', $cont_tres);
}else{
    $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]','BOLIVIA',$cont_tres);
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


/* firmas precreadas */
$id_firma1 = $rqc2['id_firma1'];
$sw_incluir_nombres = false;
if ($id_firma1 !== '0' && $id_firma1 !== '4' && false) {
    $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma1' ");
    $rqfp2 = fetch($rqfp1);
    $firma1_nombre = utf8_decode($rqfp2['nombre']);
    $firma1_cargo = utf8_decode($rqfp2['cargo']);
    $firma1_imagen = utf8_decode($rqfp2['imagen']);
    $firma1_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
    $firma1_top_pixeles = 20;
}
$id_firma2 = $rqc2['id_firma2'];
if ($id_firma2 !== '0' && $id_firma1 !== '4' && false) {
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
$id_curso = $rqe2['id_curso'];
$rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$aux__fecha2_qr = $fecha2_qr;
if ($fecha2_qr == '0000-00-00' || $fecha2_qr==$fecha_qr) {
    $aux__fecha2_qr = '';
}
if ($fecha_qr == '0000-00-00') {
    $fecha_qr = $rqdc2['fecha'];
}
$data = $certificado_id . ' | ' . $texto_qr . ' | ' . $receptor_de_certificado . ' | CI ' . $ci_participante . ' | ' . trim($fecha_qr.' '.$aux__fecha2_qr);
QRcode::png($data, $file_qr_certificado);


/* proceso de impresion solo nombre */
$sw_solo_nombre = $rqe2['sw_solo_nombre'];
if ($sw_solo_nombre == '1') {
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

/* IMAGEN BACKGROUND */
$rqdfc1 = query("SELECT imagen FROM certificados_imgfondo WHERE id='$id_fondo_digital' LIMIT 1 ");
$rqdfc2 = fetch($rqdfc1);
$file_imagen_background = '../../../imagenes/cursos/certificados/'.$rqdfc2['imagen'];
$pdf->Image($file_imagen_background, 0, 0, 800, 620);



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

/* datos participante */
if ($ci_participante !== '' && $ci_participante !== '0') {
    /* ci participante */
    $array_expedido_base = array('LP', 'OR', 'PS', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH', 'PT');
    $array_expedido_short = array('LP', 'OR', 'PT', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
    $array_expedido_literal = array('La Paz', 'Oruro', 'Potosi', 'Cochabamba', 'Santa Cruz', 'Beni', 'Pando', 'Tarija', 'Chuquisaca',  'Potosi');
    if ($ci_expedido_participante == 'Cod. QR') {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante $ci_expedido_participante";
    } elseif ($ci_expedido_participante !== '') {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante expedido en " . strtoupper(str_replace($array_expedido_base, $array_expedido_literal, $ci_expedido_participante));
    } else {
        $texto_cedula_identidad = "Cédula de identidad número: $ci_participante";
    }

    /* nombre participante */
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 440, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(4);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 545, utf8_decode($texto_cedula_identidad), 0, 0, 'C');
} else {
    /* nombre participante */
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


if ($sw_solo_nombre !== '1') {

    /* firma 1 */
    if ($firma1_sw_incluir_nombres == '1' && false) {
//$pdf->Line(260,540,390,540);
        $pdf->Line(260, 525, 390, 525);
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
    if (file_exists($file_imagen_firma_1) && ($firma1_imagen !== '')) {
        $pdf->Image($file_imagen_firma_1, 170, (440 + $firma1_top_pixeles), 150, 100);
    }


    /* firma 2 */
    if ($firma2_sw_incluir_nombres == '1' && false) {
//$pdf->Line(480,540,610,540);
        $pdf->Line(480, 525, 610, 525);
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
    if (file_exists($file_imagen_firma_2) && ($firma2_imagen !== '')) {
        $pdf->Image($file_imagen_firma_2, 470, (440 + $firma1_top_pixeles), 150, 100);
    }
}

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
$pdf->Image($file_qr_certificado, 637, 465, 115, 115);


/* texto solo en impresion multiple (**) */
$pdf->SetY(-17);
$pdf->SetX(50);
$pdf->Cell(100, 0, 'C-' . $codnum_c, 0, 200, 'R');

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

/* output */
$nombre_archivo = strtolower($nom_file_participante.'-'.date("HidmY")."-CERTIFICADO-$certificado_id.pdf");
if(isset_get('download') && get('download')=='true'){
    $modo = "D";
    $txt_modo = "DESCARGA-DIGITAL";
    $pdf->Output($nombre_archivo, $modo);

    /* log de impresiones */
    query("INSERT INTO certsgenimp_log 
    (id_emision_certificado, id_administrador, modo, fecha)
    VALUES 
    ('$id_emision_certificado','$id_administrador','$txt_modo',NOW())");
}else{
    $modo = "I";
    query("INSERT INTO cursos_pdf_generados(id_administrador, id_curso, nombre, fecha) VALUES ('$id_administrador','$id_curso','discontinuado',NOW()) ");
    //*logcursos('Generado de PDF [' . $nombre_archivo . ']', 'curso-pdf', 'curso', $id_curso);
    $pdf->Output($nombre_archivo, $modo);
}


