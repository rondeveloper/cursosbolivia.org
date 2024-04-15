<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    
    
    $id_participante = post('dat');
        
    /* obtencion de datos del participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdp2 = fetch($rqdp1);
    
    /* eliminacion de anterior registro */
    query("DELETE FROM cursos_participantes WHERE id='$id_participante' LIMIT 1 ");
        
    $id_curso = $rqdp2['id_curso'];
    $id_proceso_registro = $rqdp2['id_proceso_registro'];
    $id_usuario = $rqdp2['id_usuario'];
    $nombres = $rqdp2['nombres'];
    $apellidos = $rqdp2['apellidos'];
    $ci = $rqdp2['ci'];
    $ci_expedido = $rqdp2['ci_expedido'];
    $prefijo = $rqdp2['prefijo'];
    $correo = $rqdp2['correo'];
    $celular = $rqdp2['celular'];
    $institucion = $rqdp2['institucion'];
    $tel_institucion = $rqdp2['tel_institucion'];
    $numeracion = $rqdp2['numeracion'];
    $id_emision_certificado = $rqdp2['id_emision_certificado'];
    $id_emision_certificado_2 = $rqdp2['id_emision_certificado_2'];
    $cnt_impresion_certificados = $rqdp2['cnt_impresion_certificados'];
    $ultima_impresion_certificado = $rqdp2['ultima_impresion_certificado'];
    $id_turno = $rqdp2['id_turno'];
    $orden = $rqdp2['orden'];
    $sw_pago = $rqdp2['sw_pago'];
    $id_modo_pago = $rqdp2['id_modo_pago'];
    $sw_cvirtual = $rqdp2['sw_cvirtual'];
    $sw_notif = $rqdp2['sw_notif'];
    $flag1 = $rqdp2['flag1'];
    $observacion = $rqdp2['observacion'];
    
    /* creacion del nuevo participante */
    query("INSERT INTO cursos_participantes(
           id_curso, 
           id_proceso_registro, 
           id_usuario, 
           nombres, 
           apellidos, 
           ci, 
           ci_expedido, 
           prefijo, 
           correo, 
           celular, 
           institucion, 
           tel_institucion, 
           numeracion, 
           id_emision_certificado, 
           id_emision_certificado_2, 
           cnt_impresion_certificados, 
           ultima_impresion_certificado, 
           id_turno, 
           orden, 
           sw_pago, 
           id_modo_pago, 
           sw_cvirtual, 
           sw_notif, 
           flag1, 
           observacion, 
           estado
           ) VALUES (
           '$id_curso',
           '$id_proceso_registro',
           '$id_usuario',
           '$nombres',
           '$apellidos',
           '$ci',
           '$ci_expedido',
           '$prefijo',
           '$correo',
           '$celular',
           '$institucion',
           '$tel_institucion',
           '$numeracion',
           '$id_emision_certificado',
           '$id_emision_certificado_2',
           '$cnt_impresion_certificados',
           '$ultima_impresion_certificado',
           '$id_turno',
           '$orden',
           '$sw_pago',
           '$id_modo_pago',
           '$sw_cvirtual',
           '$sw_notif',
           '$flag1',
           '$observacion',
           '1'
           )");
    
    /* obtension de id de nuevo participante */
    $rqnid1 = query("SELECT id FROM cursos_participantes WHERE nombres LIKE '%$nombres%' AND apellidos LIKE '%$apellidos%' ORDER BY id DESC limit 1 ");
    $rqnid2 = fetch($rqnid1);
    $id_nuevo = $rqnid2['id'];
    
    /* reasignacion de id de certificados a nuevo participante */
    query("UPDATE cursos_emisiones_certificados SET id_participante='$id_nuevo' WHERE id_participante='$id_participante' LIMIT 2 ");
    
    movimiento('Habilitacion de participante de curso [' . $id_participante . '->'.$id_nuevo.']', 'eliminacion-curso-partipante', 'participante', $id_nuevo);

    echo "<td colspan='35' style='text-align:center;color:gray;'>Participante habilitado!</td>";
    
} else {
    echo "Denegado!";
}
