<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$prefijo = post('prefijo');
$nombres = ucfirst(trim(post('nombres')));
$apellidos = ucfirst(trim(post('apellidos')));
$celular = post('celular');
$correo = post('correo');
$razon_social = post('razon_social');
$nit = post('nit');
$monto_pago = post('monto_pago');
$id_curso = post('id_curso');
$id_participante = post('id_participante');
$id_turno = post('id_turno');

/* edicion de datos de participante */
query("UPDATE cursos_participantes SET 
            prefijo='$prefijo',
            nombres='$nombres',
            apellidos='$apellidos',
            celular='$celular',
            correo='$correo',
            id_turno='$id_turno' 
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* edicion de datos de registro */
$rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);
$id_proceso_registro = $rqdr2['id_proceso_registro'];

query("UPDATE cursos_proceso_registro SET 
            razon_social='$razon_social',
            nit='$nit',
            monto_deposito='$monto_pago',
            id_turno='$id_turno' 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
?>

<div class="alert alert-success">
    <strong>Exito!</strong> Participante editado correctamente.
</div>