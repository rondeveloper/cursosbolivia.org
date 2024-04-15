<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos */
$id_participante = get('id_participante');

$rqef1 = query("SELECT p.nombres,p.apellidos,c.titulo,r.monto_deposito FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON p.id_curso=c.id WHERE p.id='$id_participante' ORDER BY p.id DESC limit 1 ");
$rqef2 = mysql_fetch_array($rqef1);

$nro_recibo = abs($id_participante-34000);
$nombre_receptor = $rqef2['nombres'].' '.$rqef2['apellidos'];
$total = (int)$rqef2['monto_deposito'];
$a_cuenta = $total;
$saldo = 0;
$concepto = $rqef2['titulo'];
$fecha_emision = date("Y-m-d");

logcursos('Visualizacion de recibo de participante', 'participante-recibo', 'participante', $id_participante);

//Llamada al script fpdf
require('../fpdf.php');

$pdf = new FPDF('P', 'pt', 'Letter');  //crea el objeto
//$pdf = new FPDF('L', 'pt', array(612,385));  //crea el objeto

$pdf->AddPage();  //añadimos una página. Origen coordenadas, esquina superior izquierda, posición por defeto a 1 cm de los bordes.

$pdf->SetAutoPageBreak(false);

/* marco principal */
$pdf->Rect(20,20,570,325,"D");

/* logo */
$pdf->Image('logo_nemabol.jpg', 40, 30, 150, 50, 'JPG');

/* Encabezado de la factura */
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(520, 90, "RECIBO", 0, 2, "C");

/* Encabezado de la factura */
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(236, 70);
$pdf->Cell(100, 50, "Nro. ".str_pad($nro_recibo, 5, "0", STR_PAD_LEFT), 0, 2, "C");

/* marco lateral derecho */
$pdf->Rect(370,30,200,50,"D");


/* data 1 */
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(380, 40);
$pdf->Cell(0, 0, "TOTAL:", 0, 0, "J");
$pdf->SetXY(380, 55);
$pdf->Cell(0, 0, "No RECIBO:", 0, 0, "J");
$pdf->SetXY(380, 70);
$pdf->Cell(0, 0, "FECHA:", 0, 0, "J");

/* fecha aux */
$d_emision = date("d",  strtotime($fecha_emision));
$m_emision = date("m",  strtotime($fecha_emision));
$y_emision = date("Y",  strtotime($fecha_emision));

/* data 2 */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(470, 40);
$pdf->Cell(0, 0, $total.".00 BS.", 0, 0, "J");
$pdf->SetXY(470, 55);
$pdf->Cell(0, 0, str_pad($nro_recibo, 5, "0", STR_PAD_LEFT), 0, 0, "J");
$pdf->SetXY(470, 70);
$pdf->Cell(0, 0, "$d_emision / $m_emision / $y_emision", 0, 0, "J");

/* data 3 */
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(405, 95);
$pdf->Cell(0, 0, "ORIGINAL PARTICIPANTE", 0, 0, "J");

/* data 4 */
$pdf->SetFont('Arial', '', 7.5);
$pdf->SetXY(400, 102);
$pdf->MultiCell(140, 8, ("Otras actividades de informática - Enseñanza superior"), 0, "C");

/* data 5a */
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->SetXY(15, 80);
$pdf->MultiCell(200, 10, "De: Rodolfo Reynaldo Aliaga Quenta", 0, "C");

/* data 5b */
$pdf->SetFont('Arial', '', 7.5);
$pdf->SetXY(15, 92);
$pdf->MultiCell(200, 10, "Calle Cañada Strongest Nro. 1469 
Piso 2 Depto. Of. 1 Tel. 69714008
La Paz - Bolivia
", 0, "C");

/* linea media 1 */
$pdf->Line(30, 130, 570, 130);

/* linea media 2 */
$pdf->Line(30, 150, 570, 150);

/* data 11 */
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(190, 141);
$pdf->Cell(0, 0, "D E T A L L E    D E L     R E C I B O", 0, 0, "J");

/* data 7 - label nombre */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 170);
$pdf->Cell(0, 0, "RECIBÍ DE:", 0, 0, "J");

/* data 8 - nombre */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(160, 170);
$pdf->Cell(0, 0, strtoupper(utf8_decode($nombre_receptor)), 0, 0, "J");

/* data 7 - label nombre */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 190);
$pdf->Cell(0, 0, "POR CONCEPTO DE:", 0, 0, "J");

/* data 8 - nombre */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(160, 190);
$pdf->Cell(0, 0, strtoupper(utf8_decode($concepto)), 0, 0, "J");

/* data 7 - label nombre */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 210);
$pdf->Cell(0, 0, "A CUENTA:", 0, 0, "J");

/* data 8 - nombre */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(160, 210);
$pdf->Cell(0, 0, $a_cuenta.".00 BS.", 0, 0, "J");

/* data 7 - label nombre */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(330, 210);
$pdf->Cell(0, 0, "SALDO:", 0, 0, "J");

/* data 8 - nombre */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(370, 210);
$pdf->Cell(0, 0, $saldo.".00 BS.", 0, 0, "J");



/* linea media 3 */
$pdf->Line(30, 230, 570, 230);

/* data 20 */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(30, 240);
$pdf->Cell(0, 0, "IMPORTE TOTAL A PAGAR: ........................................................................................................................................... BS.", 0, 0, "J");

/* data 21 */
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(500, 234);
$pdf->MultiCell(72, 12, number_format($total, 2, '.', ','), 0, "R");


/* recuadro inferior 1 */
$pdf->Rect(30,255,210,80,"D");

/* recuadro inferior 2 */
$pdf->Rect(250,255,210,80,"D");


/* codigo QR */

/* generad de QR */
include_once '../../phpqrcode/qrlib.php';
$file = 'qrcode-factura-' . $nro_recibo . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
$data =  'RECIBO|NRO'.$nro_recibo . '|' . date("d/m/Y",strtotime($fecha_emision)) . '|BS' . $total ;
QRcode::png($data, $file_qr_certificado);

//$pdf->Image('jr-qrcode.png', 475, 265, -95);
$pdf->Image($file_qr_certificado, 475, 250, 90, 90);

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(30, 315);
$pdf->Cell(0, 0, "...........................................................................................", 0, 0, "J");

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(30, 325);
$pdf->Cell(0, 0, "Recibi conforme", 0, 0, "J");

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(250, 315);
$pdf->Cell(0, 0, "...........................................................................................", 0, 0, "J");

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(250, 325);
$pdf->Cell(0, 0, "Entregue conforme ", 0, 0, "J");



//Salto de línea
$pdf->Ln(2);



$pdf->Output();















//numeros a letras
function numtoletras($xcifra){
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el lÃ­mite a 6 dÃ­gitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegÃ³ al lÃ­mite mÃ¡ximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dÃ­gitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dÃ­gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es nÃºmero redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (MillÃ³n, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquÃ­ si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lÃ³gica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";

        // ----------- esta lÃ­nea la puedes cambiar de acuerdo a tus necesidades o a tu paÃ­s -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO   $xdecimales/100 BOLIVIANOS";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 BOLIVIANOS ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= "   $xdecimales/100 BOLIVIANOS "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para MÃ©xico se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}
function subfijo($xx)
{ // esta funciÃ³n regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}
?>
