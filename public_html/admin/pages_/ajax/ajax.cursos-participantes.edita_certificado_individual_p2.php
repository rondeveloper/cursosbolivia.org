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
$id_emision_certificado = post('id_emision_certificado');
$cont_titulo = post('cont_titulo');
$cont_uno = post('cont_uno');
$cont_dos = post('cont_dos');
$cont_tres = post('cont_tres');
$texto_qr = post('texto_qr');
$fecha_qr = post('fecha_qr');
$fecha2_qr = post('fecha2_qr');
$id_fondo_digital = post('id_fondo_digital');
$id_fondo_fisico = post('id_fondo_fisico');
$sw_solo_nombre = post('sw_solo_nombre');

/* query */
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
WHERE id='$id_emision_certificado' LIMIT 1 ");

$rqdp1 = query("SELECT id_participante FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' ");
$rqdp2 = fetch($rqdp1);
$id_participante = $rqdp2['id_participante'];
logcursos('Edicion de datos de certificado [Emision:' . $id_emision_certificado . ']', 'partipante-edicion', 'participante', $id_participante);
?>
<div class="alert alert-success">
    <strong>EXITO</strong> el registro fue actualizado correctamente.
</div>
<hr>
<div class="text-center">
    <p>Presione el siguiente boton para administrar los certificados:</p>
    <b onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 0);" class="btn btn-md btn-warning">CERT.</b>
</div>
