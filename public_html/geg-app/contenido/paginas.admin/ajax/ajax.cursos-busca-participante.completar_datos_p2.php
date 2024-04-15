<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* proceso de edicion */
if (isset_post('update-proceso-2')) {
    
    /* datos recibidos */
    $id_participante = post('id_participante');
    $celular = post('celular');
    $correo = post('correo');
    $monto_pago = post('monto_pago');
    $id_curso = post('id_curso');
    $modo_pago = post('modo_pago');
    $id_administrador = administrador('id');

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = mysql_fetch_array($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    $sw_pago_enviado = '1';
    if ($modo_pago == '') {
        $sw_pago_enviado = '0';
    }

    /* imagen deposito */
    $imagen_deposito = post('actual_imagen_deposito');
    if (is_uploaded_file(archivo('imagen_deposito'))) {
        if ($imagen_deposito !== '') {
            logcursos('Sube imagen respaldo de pago[prev:' . $imagen_deposito . ']', 'partipante-edicion', 'participante', $id_participante);
        }
        $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
        move_uploaded_file(archivo('imagen_deposito'), '../../imagenes/depositos/' . $imagen_deposito);
    }
    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            sw_pago='$sw_pago_enviado',  
            modo_pago='$modo_pago',  
            correo='$correo',  
            celular='$celular' 
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    query("UPDATE cursos_proceso_registro SET 
            correo_contacto='$correo',
            celular_contacto='$celular',
            metodo_de_pago='$modo_pago',
            monto_deposito='$monto_pago',
            imagen_deposito='$imagen_deposito',
            sw_pago_enviado='$sw_pago_enviado',
            paydata_id_administrador='$id_administrador',
            paydata_fecha=NOW() 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    logcursos('Completado de datos [correo,celular,pago]', 'partipante-edicion', 'participante', $id_participante);
    ?>
    <div class="alert alert-success">
        <strong>Exito!</strong> Participante editado correctamente.
    </div>
    <?php
}