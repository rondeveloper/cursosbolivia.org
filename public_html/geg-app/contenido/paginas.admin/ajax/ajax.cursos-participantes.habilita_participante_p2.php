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

/* datos recibidos */
$id_participante = post('id_participante');
$apartar = (int) post('apartar');

if ($apartar == 1) {
    query("DELETE FROM cursos_part_apartados WHERE id_participante='$id_participante' ");
    logcursos('Quita participante de lista de apartados', 'partipante-apartado', 'participante', $id_participante);
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> participante quitado de lista de apartados.
    </div>
    <?php
} else {

    /* obtencion de datos del participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdp2 = mysql_fetch_array($rqdp1);

    /* nuevo registro */
    $id_curso = $rqdp2['id_curso'];
    $id_proceso_registro = $rqdp2['id_proceso_registro'];
    $nombres = addslashes($rqdp2['nombres']);
    $apellidos = addslashes($rqdp2['apellidos']);
    $prefijo = $rqdp2['prefijo'];
    $ci = $rqdp2['ci'];
    $ci_expedido = $rqdp2['ci_expedido'];
    $correo = $rqdp2['correo'];
    $celular = $rqdp2['celular'];
    $institucion = $rqdp2['institucion'];
    $tel_institucion = $rqdp2['tel_institucion'];
    $id_emision_certificado = $rqdp2['id_emision_certificado'];
    $id_emision_certificado_2 = $rqdp2['id_emision_certificado_2'];
    $observacion = $rqdp2['observacion'];
    $numeracion = $rqdp2['numeracion'];
    $cnt_impresion_certificados = $rqdp2['cnt_impresion_certificados'];
    $ultima_impresion_certificado = $rqdp2['ultima_impresion_certificado'];
    $id_turno = $rqdp2['id_turno'];
    $orden = $rqdp2['orden'];
    $sw_pago = $rqdp2['sw_pago'];
    $modo_pago = $rqdp2['modo_pago'];
    $id_usuario = $rqdp2['id_usuario'];

    /* creacion del nuevo participante */
    query("INSERT INTO cursos_participantes(
           id_curso, 
           id_proceso_registro, 
           nombres, 
           apellidos, 
           prefijo, 
           ci, 
           ci_expedido, 
           correo, 
           celular, 
           institucion, 
           tel_institucion, 
           id_emision_certificado, 
           id_emision_certificado_2, 
           observacion, 
           numeracion, 
           cnt_impresion_certificados, 
           ultima_impresion_certificado, 
           id_turno, 
           orden, 
           sw_pago, 
           modo_pago, 
           id_usuario, 
           estado
           ) VALUES (
           '$id_curso',
           '$id_proceso_registro',
           '$nombres',
           '$apellidos',
           '$prefijo',
           '$ci',
           '$ci_expedido',
           '$correo',
           '$celular',
           '$institucion',
           '$tel_institucion',
           '$id_emision_certificado',
           '$id_emision_certificado_2',
           '$observacion',
           '$numeracion',
           '$cnt_impresion_certificados',
           '$ultima_impresion_certificado',
           '$id_turno',
           '$orden',
           '$sw_pago',
           '$modo_pago',
           '$id_usuario',
           '1'
           )");

    /* obtension de id de nuevo participante */
    $id_nuevo = mysql_insert_id();

    /* reasignacion de id de certificados a nuevo participante */
    query("UPDATE cursos_emisiones_certificados SET id_participante='$id_nuevo' WHERE id_participante='$id_participante' LIMIT 2 ");

    /* registro en log de movimientos */
//movimiento('Habilitacion de participante de curso [' . $id_participante . '->' . $id_nuevo . ']', 'eliminacion-curso-partipante', 'participante', $id_nuevo);
    query("UPDATE cursos_log SET id_objeto='$id_nuevo' WHERE id_objeto='$id_participante' ");
    logcursos('Habilitacion de participante', 'partipante-deshabilitacion', 'participante', $id_nuevo);

    /* eliminacion de anterior registro */
    query("DELETE FROM cursos_participantes WHERE id='$id_participante' LIMIT 1 ");

    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* datos curso */
    $rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = mysql_fetch_array($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        $nombre_participante = trim($nombres . ' ' . $apellidos);
        logcursos('Habilitacion de participante [fuera de fecha][' . $nombre_participante . ']', 'curso-edicion', 'curso', $id_curso);
    }
    ?>
    <div class="alert alert-success">
        <strong>Exito!</strong> Participante habilitado!
    </div>
    <?php
}
