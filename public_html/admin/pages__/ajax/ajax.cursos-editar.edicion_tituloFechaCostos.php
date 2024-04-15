<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

$titulo = post('titulo');
$titulo_identificador = limpiar_enlace($titulo);
$titulo_formal = post('titulo_formal');

$fecha = verificador_fecha(post('fecha'));
$costo = post('costo');
$fecha2 = verificador_fecha(post('fecha2')) . ' ' . verificador_hora(post('fecha2_hour'));
$costo2 = post('costo2');
$fecha3 = verificador_fecha(post('fecha3')) . ' ' . verificador_hora(post('fecha3_hour'));
$costo3 = post('costo3');
$fecha_e = verificador_fecha(post('fecha_e'));
$costo_e = post('costo_e');
$fecha_e2 = verificador_fecha(post('fecha_e2')) . ' ' . verificador_hora(post('fecha_e2_hour'));
$costo_e2 = post('costo_e2');
$horarios = post('horarios');
$duracion = post('duracion');
$id_abreviacion = post('id_abreviacion');
$id_docente = post('id_docente_asignado');
$rec_limitdesc = post('rec_limitdesc');

$sw_fecha2 = '0';
if (isset_post('sw_fecha2')) {
    $sw_fecha2 = '1';
}
$sw_fecha3 = '0';
if (isset_post('sw_fecha3')) {
    $sw_fecha3 = '1';
}
$sw_fecha_e = '0';
if (isset_post('sw_fecha_e')) {
    $sw_fecha_e = '1';
}
$sw_fecha_e2 = '0';
if (isset_post('sw_fecha_e2')) {
    $sw_fecha_e2 = '1';
}
$sw_fecha = '1';
if (isset_post('false_sw_fecha')) {
    $sw_fecha = '0';
}
$sw_recomendaciones = '0';
if (isset_post('sw_recomendaciones')) {
    $sw_recomendaciones = '1';
}

/* current data curso */
$rqdcc1 = query("SELECT fecha,numero FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcc2 = fetch($rqdcc1);
$current_fecha = $rqdcc2['fecha'];
$current_numero = $rqdcc2['numero'];

/* adminsitrador */
$rqdadm1 = query("SELECT nivel FROM administradores WHERE id='".administrador('id')."' LIMIT 1 ");
$rqdadm2 = fetch($rqdadm1);
$nivel_administrador = $rqdadm2['nivel'];


//*if ((strtotime($current_fecha) == strtotime(date("Y-m-d"))) && (strtotime($fecha) > strtotime(date("Y-m-d")))) {
if ((strtotime($current_fecha) == strtotime(date("Y-m-d"))) && (strtotime($fecha) > strtotime(date("Y-m-d"))) && false) {
    /* currentfecha==hoy && fecha posterior */
    if (isset_post('sw_accept_cdate') && post('sw_accept_cdate') == '1') {

        /* duplicacion de curso */
        $rqc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        $rqc2 = fetch($rqc1);
        $titulo_curso = $rqc2['titulo'];
        $fecha_registro = date("Y-m-d H:i");
        query("INSERT INTO cursos(
           id_ciudad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_abreviacion, 
           id_modalidad, 
           numero, 
           fecha, 
           titulo_identificador, 
           titulo, 
           titulo_formal, 
           contenido, 
           lugar, 
           horarios, 
           duracion, 
           imagen, 
           imagen2, 
           imagen3, 
           imagen4, 
           imagen_gif, 
           google_maps, 
           expositor, 
           colaborador, 
           gastos, 
           observaciones, 
           incrustacion, 
           seccion, 
           pixelcode, 
           texto_qr, 
           rec_limitdesc, 
           sw_recomendaciones, 
           sw_siguiente_semana, 
           short_link, 
           sw_suspendido, 
           fecha_registro, 
           estado
           ) VALUES (
           '" . $rqc2['id_ciudad'] . "',
           '" . $rqc2['id_organizador'] . "',
           '" . $rqc2['id_lugar'] . "',
           '" . $rqc2['id_docente'] . "',
           '" . $rqc2['id_abreviacion'] . "',
           '" . $rqc2['id_modalidad'] . "',
           '" . $current_numero . "',
           '" . date("Y-m-d") . "',
           '" . str_replace('-tmp', '', $rqc2['titulo_identificador']) . '-tmp' . "',
           '" . $rqc2['titulo'] . "',
           '" . $rqc2['titulo_formal'] . "',
           '" . $rqc2['contenido'] . "',
           '" . $rqc2['lugar'] . "',
           '" . $rqc2['horarios'] . "',
           '" . $rqc2['duracion'] . "',
           '" . $rqc2['imagen'] . "',
           '" . $rqc2['imagen2'] . "',
           '" . $rqc2['imagen3'] . "',
           '" . $rqc2['imagen4'] . "',
           '" . $rqc2['imagen_gif'] . "',
           '" . $rqc2['google_maps'] . "',
           '" . $rqc2['expositor'] . "',
           '" . $rqc2['colaborador'] . "',
           '" . $rqc2['gastos'] . "',
           '" . $rqc2['observaciones'] . "',
           '" . $rqc2['incrustacion'] . "',
           '" . $rqc2['seccion'] . "',
           '" . addslashes($rqc2['pixelcode']) . "',
           '" . $rqc2['texto_qr'] . "',
           '" . $rqc2['rec_limitdesc'] . "',
           '" . $rqc2['sw_recomendaciones'] . "',
           '" . $rqc2['sw_siguiente_semana'] . "',
           '" . $rqc2['short_link'] . "',
           '1',
           '" . $fecha_registro . "',
           '2'
           )");
        $id_curso_nuevo = insert_id();
        /* tags */
        $rqdcct1 = query("SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso' ");
        while ($rqdcct2 = fetch($rqdcct1)) {
            $id_tag = $rqdcct2['id_tag'];
            $rqverif1 = query("SELECT COUNT(1) AS total FROM cursos_rel_cursostags WHERE id_curso='$id_curso_nuevo' AND id_tag='$id_tag' ");
            $rqverif2 = fetch($rqverif1);
            if ($rqverif2['total'] == 0) {
                query("INSERT INTO cursos_rel_cursostags (id_curso,id_tag) VALUES ('$id_curso_nuevo','$id_tag') ");
            }
        }
        logcursos('Creacion de curso por duplicacion[' . $id_curso . ':' . $titulo_curso . ']', 'curso-creacion', 'curso', $id_curso_nuevo);
        /* END duplicacion de curso */

        /* registro */
        query("UPDATE cursos SET 
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              titulo_formal='$titulo_formal',
              horarios='$horarios',
              duracion='$duracion',
              id_abreviacion='$id_abreviacion',
              id_docente='$id_docente',
              fecha='$fecha',
              costo='$costo',
              sw_fecha='$sw_fecha',
              fecha2='$fecha2',
              costo2='$costo2',
              sw_fecha2='$sw_fecha2',
              fecha3='$fecha3',
              costo3='$costo3',
              sw_fecha3='$sw_fecha3',
              fecha_e='$fecha_e',
              costo_e='$costo_e',
              sw_fecha_e='$sw_fecha_e',
              fecha_e2='$fecha_e2',
              costo_e2='$costo_e2',
              sw_fecha_e2='$sw_fecha_e2', 
              rec_limitdesc='$rec_limitdesc', 
              sw_recomendaciones='$sw_recomendaciones', 
              numero='0' 
               WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de datos de curso [TITULO/FECHA/COSTOS]', 'curso-edicion', 'curso', $id_curso);

        /* actualizacion html curso */
        file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=curso-individual&id_curso=".$id_curso);
        file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=inicio");
        ?>
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>OPERACION EXITOSA</strong> datos de curso actualizados correctamente.
        </div>
        <script>
            $('#input_accept_cdate').remove();
        </script>
        <?php
    } else {
        ?>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>AVISO</strong>
            <br/>
            El cambio de fecha en un curso en ejecuci&oacute;n, procede con las operaciones:
            <br/>
            - Se creara un curso duplicado en estado suspendido con la numeraci&oacute;n del curso que desea modificar.
            <br/>
            - Se modificara el curso actual a la fecha '<?php echo $fecha; ?>', este curso conservara todas las caracteristicas y participantes excepto la numeraci&oacute;n.
            <br/>
            <hr/>
            <b class="btn btn-success" onclick="accept_cdate();">ACEPTAR</b> &nbsp;&nbsp;&nbsp; <b class="btn btn-default" data-dismiss="alert" aria-label="close">CANCELAR</b>
        </div>
        <script>
            function accept_cdate() {
                $('#FORM-edicion_tituloFechaCostos').append('<input type="hidden" name="sw_accept_cdate" id="input_accept_cdate" value="1"/>');
                edicion_tituloFechaCostos();
            }
        </script>
        <?php
    }
} elseif ((strtotime($fecha) < strtotime(date("Y-m-d"))) && $nivel_administrador!='1') {
    /* fecha pasada */
    ?>

    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>ERROR!</strong> no se permite asignar una fecha anterior a la actual.
    </div>

    <?php
} elseif (strtotime($fecha) >= strtotime(date("Y-m-d")) || $nivel_administrador=='1') {
    /* fecha posterior */

    /* registro */
    query("UPDATE cursos SET 
              titulo='$titulo',
              titulo_identificador='$titulo_identificador',
              titulo_formal='$titulo_formal',
              horarios='$horarios',
              duracion='$duracion',
              id_abreviacion='$id_abreviacion',
              id_docente='$id_docente',
              fecha='$fecha',
              costo='$costo',
              sw_fecha='$sw_fecha',
              fecha2='$fecha2',
              costo2='$costo2',
              sw_fecha2='$sw_fecha2',
              fecha3='$fecha3',
              costo3='$costo3',
              sw_fecha3='$sw_fecha3',
              fecha_e='$fecha_e',
              costo_e='$costo_e',
              sw_fecha_e='$sw_fecha_e',
              fecha_e2='$fecha_e2',
              costo_e2='$costo_e2',
              sw_fecha_e2='$sw_fecha_e2',
              rec_limitdesc='$rec_limitdesc', 
              sw_recomendaciones='$sw_recomendaciones' 
               WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de datos de curso [TITULO/FECHA/COSTOS]', 'curso-edicion', 'curso', $id_curso);

    /* actualizacion html curso */
    file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=curso-individual&id_curso=".$id_curso);
    file_get_contents($dominio_procesamiento."admin/process.cron.genera_htmls.php?page=inicio");
    ?>

    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>EXITO!</strong> datos de curso actualizados correctamente.
    </div>

    <?php
} else {
    ?>
    <div class="alert alert-default">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>ERROR</strong> sin datos encontrados.
    </div>
    <?php
}

function verificador_fecha($dat) {
    if ($dat == '') {
        return "0000-00-00";
    } else {
        return $dat;
    }
}

function verificador_hora($dat) {
    if ($dat == '') {
        return "00:00";
    } else {
        return $dat;
    }
}
