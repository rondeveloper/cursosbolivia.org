<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
$id_fondo_digital = post('id_fondo_digital');
$id_fondo_fisico = post('id_fondo_fisico');
$texto_qr = post('texto_qr');
$fecha_qr = post('fecha_qr');
$fecha2_qr = post('fecha2_qr');
$sw_update_emitidos = post('sw_update_emitidos');

/* validacion fechas */
if(strtotime($fecha_qr)>strtotime($fecha2_qr)){
    echo '<div class="alert alert-danger">
    <strong>ERROR</strong> la fecha inicial no puede ser mayor a la fecha final.
  </div>';
    exit;
}

/* imagen firma */
if (is_uploaded_file(archivo('firma1_imagen'))) {
    $firma1_imagen = time() . archivoName('firma1_imagen');
    move_uploaded_file(archivo('firma1_imagen'), $___path_raiz."contenido/imagenes/firmas/$firma1_imagen");
}
if (is_uploaded_file(archivo('firma2_imagen'))) {
    $firma2_imagen = time() . archivoName('firma2_imagen');
    move_uploaded_file(archivo('firma2_imagen'), $___path_raiz."contenido/imagenes/firmas/$firma2_imagen");
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
           fecha2_qr='$fecha2_qr', 
           id_fondo_digital='$id_fondo_digital',  
           id_fondo_fisico='$id_fondo_fisico',  
           sw_solo_nombre='$sw_solo_nombre'  
           WHERE id='$id_cert' LIMIT 1 ");

if($sw_update_emitidos=='1'){
    query("UPDATE cursos_emisiones_certificados SET 
           cont_titulo='$cont_titulo',  
           cont_uno='$cont_uno',  
           cont_dos='$cont_dos',  
           cont_tres='$cont_tres', 
           texto_qr='$texto_qr', 
           fecha_qr='$fecha_qr', 
           fecha2_qr='$fecha2_qr', 
           id_fondo_digital='$id_fondo_digital',  
           id_fondo_fisico='$id_fondo_fisico',  
           sw_solo_nombre='$sw_solo_nombre'  
           WHERE id_certificado='$id_cert' ");
    echo '<div class="alert alert-info">
            EMISIONES DE CERTIFICADO actualizados.
         </div>';
}

$rqdcc1 = query("SELECT codigo,id_curso FROM cursos_certificados WHERE id='$id_cert' ORDER BY id DESC limit 1 ");
$rqdcc2 = fetch($rqdcc1);

logcursos('Edicion de certificado ['.$rqdcc2['codigo'].']', 'certificado-curso-edicion', 'certificado-curso', $id_cert);
logcursos('Edicion de certificado ['.$rqdcc2['codigo'].']', 'curso-edicion', 'curso', $rqdcc2['id_curso']);

echo '<div class="alert alert-success">
  <strong>Exito!</strong> El certificado fue editado exitosamente. 
</div>';
