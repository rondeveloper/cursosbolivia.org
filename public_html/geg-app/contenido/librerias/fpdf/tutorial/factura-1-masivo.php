<?php

session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/* datos */
$id_participantes = get('id_participantes');
if($id_participantes==''){
    $id_participantes = '0';
}
/* limpia datos de id participante*/
$ar_exp_aux = explode(",",$id_participantes);
$id_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $id_participantes .= ",".(int)$value;
}

/* generad de QR */
include_once '../../phpqrcode/qrlib.php';

/* Llamada al script fpdf */
require('../fpdf.php');

/* creacion de pdf */
$pdf = new FPDF('P', 'pt', 'Letter');  //crea el objeto
//$pdf = new FPDF('L', 'pt', array(612,385));  //crea el objeto


//$rqce1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id IN (select id_emision_factura from cursos_participantes where id in($id_participantes) ) ORDER BY id DESC limit 100 ");
$rqce1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id IN (select id_emision_factura from cursos_proceso_registro where id in( select id_proceso_registro from cursos_participantes where id in($id_participantes) ) ) ORDER BY id DESC limit 100 ");

if(mysql_num_rows($rqce1)==0){
    echo "<br/><hr/><h3>No se encontraron emisiones de facturas para los participantes seleccionados.</h3><hr/><br/>";
    exit;
}

while ($rqce2 = mysql_fetch_array($rqce1)) {

    /* datos factura individual */
    $nro_factura = $rqce2['nro_factura'];

$rqef1 = query("SELECT * FROM facturas_emisiones WHERE nro_factura='$nro_factura' ORDER BY id DESC limit 1 ");
$rqef2 = mysql_fetch_array($rqef1);

$nro_autorizacion = $rqef2['nro_autorizacion'];
$nit_emisor = $rqef2['nit_emisor'];
$fecha_limite_emision = $rqef2['fecha_limite_emision'];
$codigo_de_control = $rqef2['codigo_de_control'];
$nombre_receptor = $rqef2['nombre_receptor'];
$nit_receptor = $rqef2['nit_receptor'];
$total = $rqef2['total'];
$concepto = $rqef2['concepto'];
$fecha_emision = $rqef2['fecha_emision'];
$ciudad_emision = $rqef2['ciudad_emision'];
$fecha_registro = $rqef2['fecha_registro'];
$estado = $rqef2['estado'];


$pdf->AddPage();  //a�adimos una p�gina. Origen coordenadas, esquina superior izquierda, posici�n por defeto a 1 cm de los bordes.

$pdf->SetAutoPageBreak(false);

/* marco principal */
$pdf->Rect(20,20,570,345,"D");

/* logo */
$pdf->Image('logo_nemabol.jpg', 40, 30, 150, 50, 'JPG');

// Encabezado de la factura
$pdf->SetFont('Arial', 'B', 19);
$pdf->Cell(520, 170, "FACTURA", 0, 2, "C");

/* marco lateral derecho */
$pdf->Rect(370,40,200,50,"D");


/* data 1 */
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(380, 50);
$pdf->Cell(0, 0, "NIT:", 0, 0, "J");
$pdf->SetXY(380, 65);
$pdf->Cell(0, 0, "No FACTURA:", 0, 0, "J");
$pdf->SetXY(380, 80);
$pdf->Cell(0, 0, "No AUTORIZACION:", 0, 0, "J");

/* data 2 */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(470, 50);
$pdf->Cell(0, 0, $nit_emisor, 0, 0, "J");
$pdf->SetXY(470, 65);
$pdf->Cell(0, 0, str_pad($nro_factura, 5, "0", STR_PAD_LEFT), 0, 0, "J");
$pdf->SetXY(470, 80);
$pdf->Cell(0, 0, $nro_autorizacion, 0, 0, "J");

/* data 3 */
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(420, 105);
$pdf->Cell(0, 0, "ORIGINAL CLIENTE", 0, 0, "J");

/* data 4 */
$pdf->SetFont('Arial', '', 7.5);
$pdf->SetXY(400, 112);
$pdf->MultiCell(140, 8, ("Otras actividades de inform�tica - Ense�anza superior"), 0, "C");

/* data 5a */
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->SetXY(15, 80);
$pdf->MultiCell(200, 10, "De: Rodolfo Reynaldo Aliaga Quenta", 0, "C");

/* data 5b */
$pdf->SetFont('Arial', '', 7.5);
$pdf->SetXY(15, 92);
$pdf->MultiCell(200, 10, "Calle Ca�ada Strongest Nro. 1469
Piso 2 Depto. Of. 1 Tel. 69714008
La Paz - Bolivia
", 0, "C");

/* data 6 - fecha */
$d_emision = date("d",  strtotime($fecha_emision));
$m_emision = date("m",  strtotime($fecha_emision));
$y_emision = date("Y",  strtotime($fecha_emision));
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 130);
$pdf->Cell(0, 0, "$ciudad_emision / $d_emision / $m_emision / $y_emision", 0, 0, "J");

/* data 7 - label nombre */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 150);
$pdf->Cell(0, 0, "NOMBRE:", 0, 0, "J");

/* data 8 - nombre */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(80, 150);
$pdf->Cell(0, 0, strtoupper(utf8_decode($nombre_receptor)), 0, 0, "J");

/* data 9 - label nit */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(420, 150);
$pdf->Cell(0, 0, "NIT:", 0, 0, "J");

/* data 10 - nit */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(445, 150);
$pdf->Cell(0, 0, $nit_receptor, 0, 0, "J");

/* linea media 1 */
$pdf->Line(30, 160, 570, 160);

/* linea media 2 */
$pdf->Line(30, 180, 570, 180);

/* data 11 */
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(175, 171);
$pdf->Cell(0, 0, "D E T A L L E    D E    L A    F A C T U R A", 0, 0, "J");


/* data 12 */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(30, 190);
$pdf->Cell(0, 0, "CONCEPTO", 0, 0, "J");

/* data 13 */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(390, 190);
$pdf->Cell(0, 0, "CANT.", 0, 0, "J");

/* data 14 */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(440, 190);
$pdf->Cell(0, 0, "P. UNITARIO", 0, 0, "J");

/* data 15 */
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(520, 190);
$pdf->Cell(0, 0, "SUBTOTAL", 0, 0, "J");


/* data 16 */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(30, 200);
$pdf->MultiCell(340, 12, utf8_decode($concepto), 0, "L");

/* data 17 */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(390, 200);
$pdf->MultiCell(30, 12, "1", 0, "C");

/* data 18 */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(440, 200);
$pdf->MultiCell(60, 12, number_format($total, 2, '.', ','), 0, "C");
        
/* data 19 */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(512, 200);
$pdf->MultiCell(60, 12, number_format($total, 2, '.', ','), 0, "R");


/* linea media 3 */
$pdf->Line(30, 240, 570, 240);

/* data 20 */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(30, 250);
$pdf->Cell(0, 0, "IMPORTE TOTAL DE LA FACTURA: .................................................................................................................................. BS.", 0, 0, "J");

/* data 21 */
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(500, 244);
$pdf->MultiCell(72, 12, number_format($total, 2, '.', ','), 0, "R");

/* data 22 */
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(30, 265);
$pdf->Cell(0, 0, "SON: ".numtoletras($total), 0, 0, "J");


/* recuadro inferior 1 */
$pdf->Rect(30,275,210,20,"D");

/* recuadro inferior 2 */
$pdf->Rect(250,275,210,20,"D");

/* recuadro inferior 3 */
$pdf->Rect(30,300,430,50,"D");


/* codigo QR */
$file = 'qrcode-factura-' . $nro_factura . '.png';
$file_qr_certificado = '../../../imagenes/qrcode/' . $file;
if (!is_file($file_qr_certificado)) {
    copy('../../../imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
}
$data = $nit_emisor . '|' . $nro_factura . '|' . $nro_autorizacion . '|' . date("d/m/Y",strtotime($fecha_emision)) . '|' . $total . '|' . $total . '|' . $codigo_de_control . '|' . $nit_receptor . '|0|0|0|0';
QRcode::png($data, $file_qr_certificado);

//$pdf->Image('jr-qrcode.png', 475, 265, -95);
$pdf->Image($file_qr_certificado, 475, 265, -95);

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(30, 285);
$pdf->Cell(0, 0, "FECHA LIMITE DE EMISION: ".date("d/m/Y",strtotime($fecha_limite_emision)), 0, 0, "J");

/* fecha limite de emision */
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(250, 285);
$pdf->Cell(0, 0, "CODIGO DE CONTROL: ".$codigo_de_control, 0, 0, "J");

/* mensaje bottom */
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(35, 305);
$pdf->MultiCell(420, 11, '"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS, EL USO ILICITO SERA SANCIONADO DE ACUERDO A LA LEY"
Ley No. 453 "El proveedor debe brindar atenci�n sin discriminaci�n, con respeto, calidez y coordialidad a los usuarios consumidores"', 0, "L");


//Salto de l�nea
$pdf->Ln(2);



// extracci�n de los datos de los productos a trav�s de la funci�n explode
$e_productos = explode(",", $productos);
$e_unidades = explode(",", $unidades);
$e_precio_unidad = explode(",", $precio_unidad);


}


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
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
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

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
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
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
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
{ // esta función regresa un subfijo para la cifra
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
