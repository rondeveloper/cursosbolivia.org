<?php
phpinfo();
exit;

error_reporting(1);

//header('Content-type: image/jpeg');
$image = new Imagick('/home/hcurso/public_html/contenido/imagenes/paginas/15773987470001.jpg');
// If 0 is provided as a width or height parameter,
// aspect ratio is maintained
$image->thumbnailImage(100, 0);

echo $image;



/*
$imagick = new Imagick();
echo "wh";
exit;
$imagick->readImage('/home/hcurso/public_html/contenido/librerias/fpdf/tutorial/certificado-CD74866.pdf');
$imagick->writeImages('/home/hcurso/public_html/contenido/librerias/fpdf/tutorial/myimage.jpg', false);
*/

/*
$pdf_first_page = '/home/hcurso/public_html/contenido/librerias/fpdf/tutorial/certificado-CD74866.pdf';
$jpg = '/home/hcurso/public_html/contenido/librerias/fpdf/tutorial/myimage.jpg';

$pdf_escaped = escapeshellarg($pdf_first_page);
$jpg_escaped = escapeshellarg($jpg);
exec("convert $pdf_escaped $jpg_escaped");
