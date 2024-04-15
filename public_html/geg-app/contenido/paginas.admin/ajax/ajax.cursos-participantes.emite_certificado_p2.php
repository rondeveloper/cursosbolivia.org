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

$nro_certificado = post('nro_certificado');

$receptor_de_certificado = post('receptor_de_certificado');
$id_certificado = post('id_certificado');
$id_curso = post('id_curso');
$id_participante = post('id_participante');
$id_administrador = administrador('id');
$fecha_emision = date("Y-m-d H:i:s");

$cont_tres = post('cont_tres');
$fecha_qr = post('fecha_qr');
$cont_dos = post('cont_dos');
$texto_qr = post('texto_qr');

/* para dos certificacos simultaneos */
if (strpos("---" . $id_certificado, "|AND|") > 0) {
    $arraux1 = explode("|AND|", $id_certificado);
    $id_certificado = $arraux1[0];
    $id_certificado_2b = $arraux1[1];
    $id_certificado_3b = $arraux1[2];
}

/* replicacion de contenido de certificado para certificado virtual */
if ($cont_tres !== '' && $fecha_qr !== '' && $cont_dos !== '' && $texto_qr !== '') {
    $rcfrc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rcfrc2 = mysql_fetch_array($rcfrc1);
    query("INSERT INTO cursos_certificados(
           id_curso, 
           codigo, 
           modelo, 
           cont_titulo, 
           cont_uno, 
           cont_dos, 
           cont_tres, 
           texto_qr, 
           fecha_qr, 
           id_firma1, 
           firma1_nombre, 
           firma1_cargo, 
           firma1_imagen, 
           id_firma2, 
           firma2_nombre, 
           firma2_cargo, 
           firma2_imagen, 
           sw_solo_nombre, 
           formato, 
           inicio_numeracion, 
           estado
           ) VALUES (
           '".$rcfrc2['id_curso']."',
           '".$rcfrc2['codigo']."',
           '".$rcfrc2['modelo']."',
           '".$rcfrc2['cont_titulo']."',
           '".$rcfrc2['cont_uno']."',
           '".$cont_dos."',
           '".$cont_tres."',
           '".$texto_qr."',
           '".$fecha_qr."',
           '".$rcfrc2['id_firma1']."',
           '".$rcfrc2['firma1_nombre']."',
           '".$rcfrc2['firma1_cargo']."',
           '".$rcfrc2['firma1_imagen']."',
           '".$rcfrc2['id_firma2']."',
           '".$rcfrc2['firma2_nombre']."',
           '".$rcfrc2['firma2_cargo']."',
           '".$rcfrc2['firma2_imagen']."',
           '".$rcfrc2['sw_solo_nombre']."',
           '".$rcfrc2['formato']."',
           '".$rcfrc2['inicio_numeracion']."',
           '".$rcfrc2['estado']."'
           )");
    $id_new_fcert = mysql_insert_id();
    query("UPDATE cursos_certificados SET codigo='CERT-V-$id_new_fcert' WHERE id='$id_new_fcert' ORDER BY id DESC limit 1 ");
    $id_certificado = $id_new_fcert;
}
/* END replicacion de contenido de certificado para certificado virtual */



$limit_certificado = 1;
if ((int) $nro_certificado == 3 || (int) $nro_certificado == 123) {
    $limit_certificado = 3;
}
if ((int) $nro_certificado == 2 || (int) $nro_certificado == 12) {
    $limit_certificado = 2;
}
if ((int) $nro_certificado>100) {
    $limit_certificado = 100;
}
/* formato de certificado */
$rqdfc1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqdfc2 = mysql_fetch_array($rqdfc1);
$formato_certificado = $rqdfc2['formato'];

/* datos curso */
$rqdcf1 = query("SELECT fecha,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcf2 = mysql_fetch_array($rqdcf1);
$fecha_curso = $rqdcf2['fecha'];
$id_ciudad = $rqdcf2['id_ciudad'];

/* datos ciudad */
$rqdcd1 = query("SELECT cod FROM ciudades WHERE id='$id_ciudad' ORDER BY id DESC limit 1 ");
$rqdcd2 = mysql_fetch_array($rqdcd1);
$cod_i_ciudad = $rqdcd2['cod'];

/* verificacion de emision anterior */
$rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado'  ORDER BY id DESC limit 1 ");
if (mysql_num_rows($rqve1) >= $limit_certificado && false) {
    echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente.
    </div>';
    exit;
}

$certificado_id = getIDcert($cod_i_ciudad);

query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado',
           '$id_curso',
           '$id_participante',
           '$certificado_id',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
$id_emision_certificado = mysql_insert_id();
//$certificado_id = "$cod_i_ciudad" . str_pad($id_emision_certificado, 7, '0', STR_PAD_LEFT);
//query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id' WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");

if ((int) $nro_certificado == 12) {
    $certificado_id_2b = getIDcert($cod_i_ciudad);
    query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado_2b',
           '$id_curso',
           '$id_participante',
           '$certificado_id_2b',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
    $id_emision_certificado_2b = mysql_insert_id();
    //$certificado_id_2b = "11000" . str_pad($id_emision_certificado_2b, 7, '0', STR_PAD_LEFT);
    //query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id_2b' WHERE id='$id_emision_certificado_2b' ORDER BY id DESC limit 1 ");
}

if ((int) $nro_certificado == 123) {
    $certificado_id_2b = getIDcert($cod_i_ciudad);
    query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado_2b',
           '$id_curso',
           '$id_participante',
           '$certificado_id_2b',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
    $id_emision_certificado_2b = mysql_insert_id();
    //$certificado_id_2b = "11000" . str_pad($id_emision_certificado_2b, 7, '0', STR_PAD_LEFT);
    //query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id_2b' WHERE id='$id_emision_certificado_2b' ORDER BY id DESC limit 1 ");
    
    $certificado_id_3b = getIDcert($cod_i_ciudad);
    query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado_3b',
           '$id_curso',
           '$id_participante',
           '$certificado_id_3b',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
    $id_emision_certificado_3b = mysql_insert_id();
}


/* sw cierre */
query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

/* actualizacion de participante */
if ((int) $nro_certificado == 123) {
    query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado',id_emision_certificado_2='$id_emision_certificado_2b',id_emision_certificado_3='$id_emision_certificado_3b' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 1er, 2do y 3er certificado [' . $id_emision_certificado . ',' . $id_emision_certificado_2b . ',' . $id_emision_certificado_3b . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 1er, 2do y 3er certificado [fuera de fecha][' . $id_emision_certificado . ',' . $id_emision_certificado_2b . ',' . $id_emision_certificado_3b . ']', 'curso-edicion', 'curso', $id_curso);
    }
} elseif ((int) $nro_certificado == 12) {
    query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado',id_emision_certificado_2='$id_emision_certificado_2b' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 1er y 2do certificado [' . $id_emision_certificado . ',' . $id_emision_certificado_2b . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 1er y 2do certificado [fuera de fecha][' . $id_emision_certificado . ',' . $id_emision_certificado_2b . ']', 'curso-edicion', 'curso', $id_curso);
    }
} elseif ((int) $nro_certificado == 1) {
    query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 1er certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 1er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
    }
} elseif ((int) $nro_certificado == 3) {
    query("UPDATE cursos_participantes SET id_emision_certificado_3='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 3er certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 3er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
    }
} elseif ((int)$id_certificado == (int) $nro_certificado) {
    query("INSERT INTO cursos_rel_partcertadicional (id_participante,id_certificado,id_emision_certificado) VALUES ('$id_participante','$id_certificado','$id_emision_certificado') ");
    logcursos('Emision de certificado adicional [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de certificado adicional [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
    }
} else {
    query("UPDATE cursos_participantes SET id_emision_certificado_2='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 2do certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 2do certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
    }
}
?>
<div class="alert alert-success">
    <strong>Exito!</strong> Certificado emitido exitosamente.
</div>

<table class="table table-striped">
    <tr>
        <td>ID de certificado: </td>
        <td><?php echo $certificado_id; ?></td>
    </tr>
    <?php
    if ((int) $nro_certificado == 12) {
        ?>
        <tr>
            <td>ID de certificado 2: </td>
            <td><?php echo $certificado_id_2b; ?></td>
        </tr>
        <?php
    }elseif ((int) $nro_certificado == 123) {
        ?>
        <tr>
            <td>ID de certificado 2: </td>
            <td><?php echo $certificado_id_2b; ?></td>
        </tr>
        <tr>
            <td>ID de certificado 3: </td>
            <td><?php echo $certificado_id_3b; ?></td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td>Emitido a: </td>
        <td><?php echo $receptor_de_certificado; ?></td>
    </tr>
    <tr>
        <td>Fecha de emision: </td>
        <td><?php echo $fecha_emision; ?></td>
    </tr>
    <tr>
        <td colspan='2'>
            <br/>
            <br/>
            <?php
            if ((int) $nro_certificado == 123) {
                ?>
                <b>Visualizaci&oacute;n / Impresi&oacute;n -> </b> <button onclick="imprimir_tres_certificados('<?php echo $id_emision_certificado; ?>,<?php echo $id_emision_certificado_2b; ?>,<?php echo $id_emision_certificado_3b; ?>');" class="btn btn-default btn-xs">VISUALIZAR CERTIFICADOS</button>
                <?php
            }elseif ((int) $nro_certificado == 12) {
                ?>
                <b>Visualizaci&oacute;n / Impresi&oacute;n -> </b> <button onclick="imprimir_dos_certificados('<?php echo $id_emision_certificado; ?>,<?php echo $id_emision_certificado_2b; ?>');" class="btn btn-default btn-xs">VISUALIZAR CERTIFICADOS</button>
                <?php
            } else {
                ?>
                <b>Visualizaci&oacute;n / Impresi&oacute;n -> </b> <button onclick="window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-<?php echo $formato_certificado; ?>.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">VISUALIZAR CERTIFICADO</button>
                <?php
            }
            ?>
            <br/>
            <br/>
        </td>
    </tr>
</table>
