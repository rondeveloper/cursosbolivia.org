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
$id_curso = (int) post('id_curso');
$id_administrador = administrador('id');

$file = 'plantilla';
if (!is_uploaded_file($_FILES[$file]['tmp_name'])) {
    echo "[$id_curso] NO Se subio el archivo";
    exit;
}

/* fecha curso */
$rqdfc1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdfc2 = mysql_fetch_array($rqdfc1);
$fecha_curso = $rqdfc2['fecha'];

$contents = file_get_contents($_FILES[$file]['tmp_name']);
$cnt_part_agregados = 0;

$filas = explode("\n", $contents);
echo "<table class='table table-striped table-bordered'>";
foreach ($filas as $fila) {
    $celdas = explode(";", $fila);

    $ci = limpiar_data($celdas[0]);
    $ci_expedido = strtoupper(limpiar_data($celdas[1]));
    $prefijo = limpiar_data($celdas[2]);
    $nombres = strtoupper(limpiar_data($celdas[3]));
    $apellidos = strtoupper(limpiar_data($celdas[4]));
    $monto_pago = limpiar_data($celdas[5]);
    $razon_social = strtoupper(limpiar_data($celdas[6]));
    $nit = limpiar_data($celdas[7]);
    $celular = limpiar_data($celdas[8]);
    $correo = strtolower(limpiar_data($celdas[9]));
    $observacion = limpiar_data($celdas[10]);
    $id_turno = 0;
    $numeracion = limpiar_data($celdas[11]);
    $modo_pago = 'hoja_excel';

    if (strlen(trim($nombres . $apellidos . $ci)) < 3 || (($ci == 'CI' && $nombres == 'NOMBRES'))) {
        continue;
    }


    echo "<tr>";
    if (($ci !== 'CI' && $nombres !== 'NOMBRES') && (strlen($nombres) >= 3 && strlen($apellidos) >= 3)) {

        /* verificacion de existencia */
        $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        if (mysql_num_rows($rqpcv1) > 0) {
            echo "<td><b style='color:blue;'>YA EXISTENTE:</b></td>";
            echo "<td>$ci | $nombres | $apellidos </td>";
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
                      'pago-en-oficina',
                      '1',
                      '$razon_social',
                      '$nit',
                      '$monto_pago',
                      '$fecha_registro',
                      '1'
                      )");
            $id_proceso_registro = mysql_insert_id();
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

            logcursos('Registro de participante a curso [' . $codigo_registro . '][ADM][csv]', 'participante-registro', 'participante', $id_participante);
            if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                logcursos('Registro de participante FUERA DE FECHA [' . $id_participante . ':' . $nombres . ' ' . $apellidos . ']', 'curso-add-participante', 'curso', $id_curso);
            }

            $cnt_part_agregados++;
            echo "<td><b style='color:green;'>SE AGREGO:</b></td>";
            echo "<td>$ci | $nombres | $apellidos </td>";
        }
    } else {
        echo "<td><b style='color:red;'>NO SE AGREGO:</b></td>";
        echo "<td>$ci | $nombres | $apellidos </td>";
    }
    echo "</tr>";
}
echo "</table>";

if ($cnt_part_agregados > 0) {
    logcursos('Registro de [' . $cnt_part_agregados . '] participantes a curso [curso: ' . $id_curso . '][CSV]', 'add-participantes-csv', 'curso', $id_curso);
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
}

function limpiar_data($dat) {
    return trim(str_replace("'", '', str_replace('"', '', str_replace('´', '', $dat))));
}
