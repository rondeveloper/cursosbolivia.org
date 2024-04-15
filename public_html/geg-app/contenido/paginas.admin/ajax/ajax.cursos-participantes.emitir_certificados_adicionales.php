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
$id_participante = post('id_participante');
$id_administrador = administrador('id');
$fecha_emision = date("Y-m-d H:i:s");

/* participante */
$rqdprt1 = query("SELECT id_curso,prefijo,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ");
$rqdprt2 = mysql_fetch_array($rqdprt1);
$receptor_de_certificado = $rqdprt2['prefijo'] . ' ' . $rqdprt2['nombres'] . ' ' . $rqdprt2['apellidos'];
$id_curso = $rqdprt2['id_curso'];

$ids_emisiones_certificados = '';

$rqmc1 = query("SELECT * FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ");
while ($rqmc2 = mysql_fetch_array($rqmc1)) {
    $rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='" . $rqmc2['id_certificado'] . "' LIMIT 1 ");
    if (mysql_num_rows($rqdcrt1) == 0) {
        echo "Error cert";
        continue;
    }
    $rqdcpem2 = mysql_fetch_array($rqdcrt1);
    $id_certificado = $rqdcpem2['id'];
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
    $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado='$id_certificado' LIMIT 1 ");
    if (mysql_num_rows($rqvpca1) > 0) {
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
    $ids_emisiones_certificados .= ','.$id_emision_certificado;
    
    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* actualizacion de participante */
    query("INSERT INTO cursos_rel_partcertadicional (id_participante,id_certificado,id_emision_certificado) VALUES ('$id_participante','$id_certificado','$id_emision_certificado') ");
    logcursos('Emision de certificado adicional [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
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

<b class="btn btn-success btn-lg" onclick="imprimir_certificados_adicionales('<?php echo trim($ids_emisiones_certificados,','); ?>');">IMPRIMIR TODOS</b>

