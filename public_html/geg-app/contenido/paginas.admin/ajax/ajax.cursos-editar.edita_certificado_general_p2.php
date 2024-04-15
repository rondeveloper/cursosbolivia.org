<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_cert = post('id_certificado');

$cont_titulo = post('titulo_certificado');
$cont_uno = post('contenido_uno_certificado');
$cont_dos = post('contenido_dos_certificado');
$cont_tres = post('contenido_tres_certificado');

$firma1_nombre = post('firma1_nombre');
$firma1_cargo = post('firma1_cargo');
$firma1_imagen = post('firma1_imagen_previo');
$id_firma1 = post('firma1');

$firma2_nombre = post('firma2_nombre');
$firma2_cargo = post('firma2_cargo');
$firma2_imagen = post('firma2_imagen_previo');
$id_firma2 = post('firma2');

$texto_qr = post('texto_qr');
$fecha_qr = post('fecha_qr');

/* imagen firma */
if (is_uploaded_file(archivo('firma1_imagen'))) {
    $firma1_imagen = time() . archivoName('firma1_imagen');
    move_uploaded_file(archivo('firma1_imagen'), "contenido/imagenes/firmas/$firma1_imagen");
}
if (is_uploaded_file(archivo('firma2_imagen'))) {
    $firma2_imagen = time() . archivoName('firma2_imagen');
    move_uploaded_file(archivo('firma2_imagen'), "contenido/imagenes/firmas/$firma2_imagen");
}

$sw_solo_nombre = post('sw_solo_nombre');
$formato = post('formato');


query("UPDATE cursos_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           texto_qr='$texto_qr', 
           fecha_qr='$fecha_qr', 
           id_firma1='$id_firma1',  
           firma1_nombre='$firma1_nombre',  
           firma1_cargo='$firma1_cargo',  
           firma1_imagen='$firma1_imagen',  
           id_firma2='$id_firma2',  
           firma2_nombre='$firma2_nombre',  
           firma2_cargo='$firma2_cargo', 
           firma2_imagen='$firma2_imagen', 
           sw_solo_nombre='$sw_solo_nombre', 
           formato='$formato' 
           WHERE id='$id_cert' LIMIT 1 ");

$rqdcc1 = query("SELECT codigo,id_curso FROM cursos_certificados WHERE id='$id_cert' ORDER BY id DESC limit 1 ");
$rqdcc2 = mysql_fetch_array($rqdcc1);

logcursos('Edicion de certificado ['.$rqdcc2['codigo'].']', 'certificado-curso-edicion', 'certificado-curso', $id_cert);
logcursos('Edicion de certificado ['.$rqdcc2['codigo'].']', 'curso-edicion', 'curso', $rqdcc2['id_curso']);

echo '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
