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

/* data */
$ids_certpart = post('ids_certpart');
$receptor_de_certificado = post('receptor_de_certificado');
$id_administrador = administrador('id');
$fecha_emision = date("Y-m-d H:i:s");

$ids_participantes = '';

$bloques = explode(',', $ids_certpart);
foreach ($bloques as $bloque) {
    $bloq = explode('__', $bloque);
    $id_certificado = (int) $bloq[0];
    $id_participante = (int) $bloq[1];

    /* certificados */
    $rqdcpem1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ");
    if (mysql_num_rows($rqdcpem1) == 0) {
        echo "Error";
        exit;
    }
    $rqdcpem2 = mysql_fetch_array($rqdcpem1);
    $id_curso = $rqdcpem2['id_curso'];
    $formato_certificado = $rqdcpem2['formato'];
    $texto_qr_certificado = $rqdcpem2['texto_qr'];
    $codigo_certificado = $rqdcpem2['codigo'];

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
    $rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND id_certificado='$id_certificado'  ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqve1) >0) {
        echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente para [' . $texto_qr_certificado . '].
    </div>';
        continue;
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
    $ids_participantes .= ','.$id_participante;

    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* actualizacion de participante */
    query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    logcursos('Emision de 1er certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Emision de 1er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
    }
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> certificado <?php echo $codigo_certificado; ?> emitido.
    </div>
    <?php
}
?>
<br/>
<br/>

<b class="btn btn-success btn-lg" onclick="imprimir_certificados_grupo('<?php echo trim($ids_participantes,','); ?>');">IMPRIMIR TODOS</b>

