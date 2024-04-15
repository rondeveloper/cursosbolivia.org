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
$modo_pago = post('modo_pago');
$id_administrador = administrador('id');

$array_respuesta = array();

$array_respuesta['mensaje'] = '';
$array_respuesta['url_ficha'] = '';
$array_respuesta['estado'] = 0;

if ((strlen(post('nombres')) >= 2) && (strlen(post('apellidos')) >= 2)) {

    /* verificacion de existencia */
    $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqpcv1) > 0) {
        $rqpcv2 = mysql_fetch_array($rqpcv1);
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

        $cod_reg = substr("RM-$id_curso-" . str_replace(" ", "-", $nombres), 0, 14);
        $fecha_registro = date("Y-m-d H:i:s");

        query("INSERT INTO cursos_proceso_registro(
                      id_curso, 
                      id_modo_de_registro,
                      id_turno,
                      id_administrador,
                      cod_reg, 
                      metodo_de_pago, 
                      sw_pago_enviado, 
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
                      '2',
                      '$id_turno',
                      '$id_administrador',
                      '$cod_reg',
                      '$modo_pago',
                      '1',
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
        $rqcr2 = mysql_fetch_array($rqcr1);
        $id_proceso_registro = (int)$rqcr2['id'];
        $codigo_registro = "RM00" . $id_proceso_registro;
        query("UPDATE cursos_proceso_registro SET codigo='$codigo_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

        query("INSERT INTO cursos_participantes (
                      id_curso,
                      id_proceso_registro,
                      id_turno,
                      prefijo,
                      nombres,
                      apellidos,
                      ci,
                      ci_expedido,
                      numeracion, 
                      modo_pago, 
                      celular,
                      correo,
                      observacion
                      ) VALUES (
                      '$id_curso',
                      '$id_proceso_registro',
                      '$id_turno',
                      '$prefijo',
                      '$nombres',
                      '$apellidos',
                      '$ci',
                      '$ci_expedido',
                      '$numeracion',
                      '$modo_pago',
                      '$celular',
                      '$correo',
                      '$observacion'
                      ) ");
        $id_participante = mysql_insert_id();
        
        if($modo_pago=='fuera_de_fecha'){
            logcursos('Registro de participante FUERA DE FECHA ['.$id_participante.':'.$nombres.' '.$apellidos.']', 'curso-add-participante', 'curso', $id_curso);
        }
        logcursos('Registro de participante [' . $codigo_registro . '][ADM]', 'participante-registro', 'participante', $id_participante);
        query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

        $array_respuesta['estado'] = 1;
        $array_respuesta['url_ficha'] = "https://cursos.bo/" . encrypt('registro-participantes-curso/' . $id_proceso_registro . '') . ".impresion";
//        if (isset_post('imprimir-ficha')) {
//            $mensaje .= "<script>window.open('https://cursos.bo/" . encrypt('registro-participantes-curso/' . $id_proceso_registro . '') . ".impresion', 'popup', 'width=700,height=500');</script>";
//        }
        $array_respuesta['mensaje'] = '<div class="alert alert-success">
            <strong>Exito!</strong> participante agregado correctamente.
        </div>';
    }
} else {
    $array_respuesta['mensaje'] = '<div class="alert alert-danger">
        <strong>Error!</strong> se debe agregar nombre y apellidos.
    </div>';
}

echo json_encode($array_respuesta);
