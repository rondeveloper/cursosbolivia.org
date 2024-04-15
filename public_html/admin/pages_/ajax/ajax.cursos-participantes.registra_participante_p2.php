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


$array_respuesta = array();

$array_respuesta['mensaje'] = '';
$array_respuesta['url_ficha'] = '';
$array_respuesta['estado'] = 0;

/* registra participante */
if (isset_post('registrar-participante')) {

    /* datos recibidos */
    $id_curso = (int)post('id_curso');
    $prefijo = post('prefijo');
    $nombres = post('nombres');
    $apellidos = post('apellidos');
    $ci = post('ci');
    $celular = post('celular');
    $correo = post('correo');
    $observacion = post('observaciones');
    $razon_social = post('razon_social');
    $nit = post('nit');
    $monto_pago = post('monto_pago');
    $id_turno = (int)post('id_turno');
    $ci_expedido = post('ci_expedido');
    $numeracion = post('numeracion');
    $id_modo_pago = post('id_modo_pago');
    $id_departamento = post('id_departamento');
    $id_banco = post('id_banco');
    $id_administrador = administrador('id');

    /* monto en gratuito */
    if($id_modo_pago=='10'){
        $monto_pago = '0';
    }

    if ((strlen(post('nombres')) >= 2) && (strlen(post('apellidos')) >= 2)) {

        /* verificacion de existencia */
        $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        if (num_rows($rqpcv1) > 0) {
            $rqpcv2 = fetch($rqpcv1);
            if ($rqpcv2['estado'] == 0) {
                $array_respuesta['mensaje'] = '<br/><div class="alert alert-info">
                    <strong>PARTICIPANTE ELIMINADO!</strong> Desea habilitar al participante?.
                    <br/>
                    <b class="btn btn-xs btn-default" onclick="habilitar_participante('.((int) $rqpcv2['id']).');">HABILITAR PARTICIPANTE</b>
                </div>';
            } else {
                $array_respuesta['mensaje'] = '<div class="alert alert-info">
                    <strong>Alerta!</strong> nombre ya existe como participante en este curso.
                </div>';
            }
        } else {

            /* imagen deposito */
            $imagen_deposito = '';
            $name_input_file = 'comprobante_pago';
            if (is_uploaded_file(archivo($name_input_file))) {
                $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName($name_input_file)), (strlen(archivoName($name_input_file)) - 7));
                move_uploaded_file(archivo($name_input_file), $___path_raiz.'contenido/imagenes/depositos/' . $imagen_deposito);
            }

            $cod_reg = substr("RM-$id_curso-" . str_replace(" ", "-", $nombres), 0, 14);
            $fecha_registro = date("Y-m-d H:i:s");
            $sw_pago = 1;

            query("INSERT INTO cursos_proceso_registro(
                        id_curso, 
                        id_modo_pago, 
                        id_turno,
                        id_administrador,
                        id_banco, 
                        cod_reg, 
                        sw_pago_enviado, 
                        imagen_deposito, 
                        paydata_id_administrador, 
                        paydata_fecha, 
                        cnt_participantes, 
                        razon_social, 
                        nit, 
                        monto_deposito, 
                        fecha_registro, 
                        estado
                        ) VALUES (
                        '$id_curso',
                        '$id_modo_pago',
                        '$id_turno',
                        '$id_administrador',
                        '$id_banco',
                        '$cod_reg',
                        '$sw_pago',
                        '$imagen_deposito',
                        '$id_administrador',
                        '$fecha_registro',
                        '1',
                        '$razon_social',
                        '$nit',
                        '$monto_pago',
                        '$fecha_registro',
                        '1'
                        )");
            $rqcr1 = query("SELECT id FROM cursos_proceso_registro WHERE cod_reg='$cod_reg' ORDER BY id DESC limit 1 ");
            $rqcr2 = fetch($rqcr1);
            $id_proceso_registro = (int)$rqcr2['id'];
            $codigo_registro = "RM00" . $id_proceso_registro;
            query("UPDATE cursos_proceso_registro SET codigo='$codigo_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

            query("INSERT INTO cursos_participantes (
                        id_curso,
                        id_proceso_registro,
                        id_modo_pago,
                        id_turno,
                        id_departamento,
                        sw_pago,
                        prefijo,
                        nombres,
                        apellidos,
                        ci,
                        ci_expedido,
                        numeracion, 
                        celular,
                        correo,
                        observacion
                        ) VALUES (
                        '$id_curso',
                        '$id_proceso_registro',
                        '$id_modo_pago', 
                        '$id_turno',
                        '$id_departamento',
                        '$sw_pago',
                        '$prefijo',
                        '$nombres',
                        '$apellidos',
                        '$ci',
                        '$ci_expedido',
                        '$numeracion',
                        '$celular',
                        '$correo',
                        '$observacion'
                        ) ");
            $id_participante = insert_id();

            /* registro de ingreso */
            if($monto_pago>0){
                $ringreso_monto = $monto_pago;
                $ringreso_id_tipo_movimiento = 1;
                $ringreso_id_modo_pago = $id_modo_pago;
                $ringreso_id_referencia = 1;
                $ringreso_detalle = 'Registro a curso ['.$id_curso.'] de participante: '.$nombres.' '.$apellidos.' ['.$id_participante.']';
                $ringreso_id_administrador = administrador('id');
                /* id sucursal */
                $rqdds1 = query("SELECT id_sucursal FROM administradores WHERE id='$ringreso_id_administrador' LIMIT 1 ");
                $rqdds2 = fetch($rqdds1);
                $id_sucursal = $rqdds2['id_sucursal'];
                query("INSERT INTO contabilidad (
                    id_tipo_movimiento, 
                    id_modo_pago, 
                    id_referencia, 
                    id_sucursal, 
                    monto, 
                    fecha, 
                    detalle, 
                    id_administrador, 
                    fecha_registro, 
                    estado
                    ) VALUES (
                        '$ringreso_id_tipo_movimiento',
                        '$ringreso_id_modo_pago',
                        '$ringreso_id_referencia',
                        '$id_sucursal',
                        '$ringreso_monto',
                        CURDATE(),
                        '$ringreso_detalle',
                        '$ringreso_id_administrador',
                        NOW(),
                        '1'
                        ) ");
                $id_contabilidad = insert_id();
                query("INSERT INTO contabilidad_rel_data (
                    id_contabilidad,
                    id_participante
                    ) VALUES (
                        '$id_contabilidad',
                        '$id_participante'
                        )");
                $array_respuesta['mensaje'] .= '<div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>$ INGRESO AGREGADO</strong> registro de ingreso agregado correctamente.
            </div>';
            }
            
            logcursos('Registro de participante [' . $codigo_registro . '][ADM]', 'participante-registro', 'participante', $id_participante);
            query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

            $array_respuesta['estado'] = 1;
            $array_respuesta['url_ficha'] = $dominio . encrypt('registro-participantes-curso/' . $id_proceso_registro . '') . ".impresion";
            $array_respuesta['mensaje'] .= '<div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>EXITO</strong> participante agregado correctamente.
            </div>
            <button type="submit" class="btn btn-sm btn-success btn-block"><i class="fa fa-plus"></i> REGISTRAR PARTICIPANTE</b>';
        }
    } else {
        $array_respuesta['mensaje'] = '<div class="alert alert-danger">
            <strong>Error!</strong> se debe agregar nombre y apellidos.
        </div>';
    }

}else{
    $array_respuesta['mensaje'] = 'ERROR';
}


echo json_encode($array_respuesta);
