<?php

/*
  define('FPDF_FONTPATH', '.');
  require('../fpdf.php');


  $pdf = new FPDF('L', 'mm', 'A4');
  $pdf->AddFont('Calligrapher', '', 'calligra.php');
  $pdf->AddPage();
  $pdf->SetFont('Calligrapher', '', 35);
  $pdf->Cell(0, 10, 'Enjoy new fonts with FPDF!');
  //$pdf->Output();


  //data


  $registro = "Registro 1";
  $Asignatura = "lo que sea";
  $pdf->SetXY(10, 20);
  $pdf->Cell(20, 15, "Registre: " . $registro, 0, 0, 'R');
  $pdf->SetXY(28, 20);
  $pdf->Cell(20, 15, "sdgsdg");

  $pdf->SetXY(120, 20);
  $pdf->Cell(20, 15, "Signatura: " . $Asignatura, 0, 0, 'R');
  $pdf->SetXY(138, 20);
  $pdf->Cell(20, 15, "sdgsdgsgd");

  $pdf->Output();

 */

/* coneccion */

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos */
$id_participantes = get('id_participantes');
if ($id_participantes == '') {
    $id_participantes = '0';
}

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $id_participantes);
$id_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $id_participantes .= "," . (int) $value;
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

$pdf = new FPDF('L', 'pt', 'Letter');
//$pdf = new FPDF('L', 'pt', array(595.28,871.89));


/* seleccion de primer / segundo certificado */
$txt_id_emision_certificado = 'id_emision_certificado';
if (isset_get('cert2')) {
    $txt_id_emision_certificado = 'id_emision_certificado_2';
}elseif (isset_get('cert3')) {
    $txt_id_emision_certificado = 'id_emision_certificado_3';
}

if(isset_get('cert_adicional') && get('cert_adicional')=='true'){
    $id_cert_adicional = get('id_cert_adicional');
    $rqce1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id IN (select id_emision_certificado from cursos_rel_partcertadicional where id_participante in ($id_participantes) and id_certificado='$id_cert_adicional' ) ORDER BY id_participante ASC limit 300 ");
}else{
    $rqce1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id IN (select $txt_id_emision_certificado from cursos_participantes where id in($id_participantes) ) ORDER BY id_participante ASC limit 300 ");
}

/* aux: imp dos cert */
if (isset_get('imp2cert')) {
    $rqce1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id IN ($id_participantes) ORDER BY id_participante ASC limit 300 ");
}

$cnt_counter = mysql_num_rows($rqce1);

if ($cnt_counter == 0) {
    echo "<br/><hr/><h3>No se encontraron emisiones de certificados para los participantes seleccionados.</h3><hr/><br/>";
    exit;
}

$cnt_counter_aux = 0;
while ($rqce2 = mysql_fetch_array($rqce1)) {

    /* certificado id */
    $certificado_id = $rqce2['certificado_id'];

    /* datos de emision */
    $rqe1 = query("SELECT * FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' ORDER BY id DESC limit 1 ");
    $rqe2 = mysql_fetch_array($rqe1);
    //$receptor_de_certificado = utf8_decode($rqe2['receptor_de_certificado']);

    /* datos de receptor de certificado */
    $rqdpc1 = query("SELECT nombres,apellidos,ci,ci_expedido,prefijo,cnt_impresion_certificados FROM cursos_participantes WHERE id='" . $rqe2['id_participante'] . "' ORDER BY id DESC limit 1 ");
    $rqdpc2 = mysql_fetch_array($rqdpc1);
    $receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']) . ' ' . trim($rqdpc2['nombres']) . ' ' . trim($rqdpc2['apellidos'])));
    $ci_participante = $rqdpc2['ci'];
    $ci_expedido_participante = $rqdpc2['ci_expedido'];
    
    /* incremento de numero de impresion cert participante */
    $cnt_impresion_certificados = $rqdpc2['cnt_impresion_certificados']+1;
    query("UPDATE cursos_participantes SET cnt_impresion_certificados='$cnt_impresion_certificados',ultima_impresion_certificado='".date("Y-m-d H:i")."' WHERE id='".$rqe2['id_participante']."' LIMIT 1 ");

    /* id de modelo de certificado */
    $id_certificado = $rqe2['id_certificado'];

    /* datos de certificado */
    $rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);
    $codigo_certificado = $rqc2['codigo'];
    $cont_titulo = utf8_decode($rqc2['cont_titulo']);
    $cont_uno = utf8_decode($rqc2['cont_uno']);
    $cont_dos = utf8_decode($rqc2['cont_dos']);
    $cont_tres = utf8_decode($rqc2['cont_tres']);
    $texto_qr = utf8_decode($rqc2['texto_qr']);
    $fecha_qr = utf8_decode($rqc2['fecha_qr']);

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
    if ($id_firma1 !== '0') {
        $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma1' ");
        $rqfp2 = mysql_fetch_array($rqfp1);
        $firma1_nombre = utf8_decode($rqfp2['nombre']);
        $firma1_cargo = utf8_decode($rqfp2['cargo']);
        $firma1_imagen = utf8_decode($rqfp2['imagen']);
        $firma1_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
        $firma1_top_pixeles = 20;
    }
    $id_firma2 = $rqc2['id_firma2'];
    if ($id_firma2 !== '0') {
        $rqfp1 = query("SELECT * FROM cursos_certificados_firmas WHERE id='$id_firma2' ");
        $rqfp2 = mysql_fetch_array($rqfp1);
        $firma2_nombre = utf8_decode($rqfp2['nombre']);
        $firma2_cargo = utf8_decode($rqfp2['cargo']);
        $firma2_imagen = utf8_decode($rqfp2['imagen']);
        $firma2_sw_incluir_nombres = $rqfp2['sw_incluir_nombres'];
        $firma2_top_pixeles = 20;
    }


    /* generad de QR */
    $file = 'qrcode-codigo-certificado-' . $certificado_id . '.png';
    $file_qr_certificado = '../../../imagenes/qrcode/' . $file;
    if (!is_file($file_qr_certificado)) {
        copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
    }
    /* nombre del curso */
    $id_curso = $rqe2['id_curso'];
    $rqdc1 = query("SELECT titulo,fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);
    if($fecha_qr=='0000-00-00'){
        $fecha_qr = $rqdc2['fecha'];
    }
    $data = $certificado_id . ' | ' . $texto_qr . ' | ' . $receptor_de_certificado .' | CI '.$ci_participante. ' | ' . $fecha_qr;
    QRcode::png($data, $file_qr_certificado);


    /* proceso de impresion solo nombre */
    $sw_solo_nombre = $rqc2['sw_solo_nombre'];
    if ($sw_solo_nombre == '1') {
        $cont_titulo = '';
        $cont_uno = '';
        $cont_dos = '';
        $firma1_nombre = '';
        $firma1_cargo = '';
        $firma2_nombre = '';
        $firma2_cargo = '';
    }


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
// T�tulo
    $pdf->Cell(0, 300, $cont_titulo, 0, 0, 'C');
// Salto de l�nea
    $pdf->Ln(4);
    $pdf->SetTextColor(0, 0, 0);

    
//datos participante
if ($ci_participante !== '' && $ci_participante !== '0') {
    //ci participante
    $array_expedido_base = array('LP', 'OR', 'PS', 'PT', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH');
    $array_expedido_short = array('LP', 'OR', 'PT', 'PS', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
    $array_expedido_literal = array('La Paz','Oruro','Potosi','Potosi','Cochabamba','Santa Cruz','Beni','Pando','Tarija','Chuquisaca');
    if($ci_expedido_participante!==''){
        $texto_cedula_identidad = "C�dula de identidad n�mero: $ci_participante expedido en ".strtoupper(str_replace($array_expedido_base, $array_expedido_literal, $ci_expedido_participante));
    }else{
        $texto_cedula_identidad = "C�dula de identidad n�mero: $ci_participante";
    }

    //nombre participante
    $pdf->SetFont('Arial', '', 21);
    $pdf->Cell(0, 435, $receptor_de_certificado, 0, 0, 'C');
    $pdf->Ln(4);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 545, $texto_cedula_identidad, 0, 0, 'C');
} else {
    //nombre participante
    $pdf->SetFont('Arial', '', 25);
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
    if (strlen($cont_dos) > 95) {
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
    }else{
        $pdf->SetY(-250);
        $pdf->SetX(65);
        $pdf->MultiCell(650, 17, $cont_dos, 0, 'C');
    }
    

    /* texto - cont tres */
    $pdf->SetY(-195 + $l_plus_Y);
    $pdf->Cell(0, -20, $cont_tres, 0, 0, 'C');


    if ($sw_solo_nombre !== '1') {

        /* firma 1 */
        if ($firma1_sw_incluir_nombres == '1') {
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
            $pdf->Image($file_imagen_firma_1, 247, (440 + $firma1_top_pixeles), 150, 100);
        }

        /* firma 2 */
        if ($firma2_sw_incluir_nombres == '1') {
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

    $pdf->SetY(-17);
    $pdf->SetX(590);
    $pdf->Cell(200, 0, 'ID de certificado: ' . $certificado_id, 0, 0, 'C');

    $pdf->SetY(-100);
    /* codigo QR */
    /* $pdf->Image($file_qr_certificado, 690, 510, 95, 95); */
    //$pdf->Image($file_qr_certificado, 350, 40, 100, 100);
    $pdf->Image($file_qr_certificado, 637, 470, 115, 115);



    /* texto solo en impresion multiple (**) */
    $pdf->SetY(-17);
    $pdf->SetX(50);
    $pdf->Cell(100, 0, 'C-' . ++$cnt_counter_aux, 0, 200, 'R');
}

/* adminsitrador */
$administrador = 'sin-nombre';
if(isset_administrador()){
    $id_administrador = administrador('id');
    $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1");
    $rqda2 = mysql_fetch_array($rqda1);
    $administrador = limpiar_enlace($rqda2['nombre']);
}

//$pdf->Output();
$modo = "I";
$nombre_archivo = limpiar_enlace($rqdc2['titulo'])."__F_".date("d-M-Y",strtotime($rqdc2['fecha']))."__".$cnt_counter_aux."P__G_".date("d-M")."_H_".date("H-i")."__".$administrador."__".time().".pdf"; 
$fecha_current = date("Y-m-d H:i");
query("INSERT INTO cursos_pdf_generados(id_administrador, id_curso, nombre, fecha) VALUES ('$id_administrador','$id_curso','$nombre_archivo','$fecha_current') ");
logcursos('Generado de PDF [' . $nombre_archivo . ']', 'curso-pdf', 'curso', $id_curso);
$pdf->Output("../../../archivos/pdfcursos/$nombre_archivo","F");
$pdf->Output($nombre_archivo,$modo);
