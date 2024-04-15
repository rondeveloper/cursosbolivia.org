<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


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
$rqdprt1 = query("SELECT id,id_curso,prefijo,nombres,apellidos,cnt_impresion_certificados,celular FROM cursos_participantes WHERE id='$id_participante' ");
$participante = fetch($rqdprt1);
$receptor_de_certificado = $participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos'];
$id_curso = $participante['id_curso'];

$ids_emisiones_certificados = '';

$rqmc1 = query("SELECT * FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ");
while ($rqmc2 = fetch($rqmc1)) {
    $rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='" . $rqmc2['id_certificado'] . "' LIMIT 1 ");
    if (num_rows($rqdcrt1) == 0) {
        echo "Error cert";
        continue;
    }
    $rqdcpem2 = fetch($rqdcrt1);
    $id_certificado = $rqdcpem2['id'];
    $formato_certificado = $rqdcpem2['formato'];
    $texto_qr_certificado = $rqdcpem2['texto_qr'];
    $codigo_certificado = $rqdcpem2['codigo'];

    /* datos curso */
    $rqdcf1 = query("SELECT fecha,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = fetch($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    $id_ciudad = $rqdcf2['id_ciudad'];

    /* datos ciudad */
    $rqdcd1 = query("SELECT cod FROM ciudades WHERE id='$id_ciudad' ORDER BY id DESC limit 1 ");
    $rqdcd2 = fetch($rqdcd1);
    $cod_i_ciudad = $rqdcd2['cod'];

    /* verificacion de emision anterior */
    $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado='$id_certificado' LIMIT 1 ");
    if (num_rows($rqvpca1) > 0) {
        echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente para [' . $texto_qr_certificado . '].
    </div>';
        continue;
    }

    $certificado_id = getIDcert($cod_i_ciudad);

    /* data de certificado */
    $rqddcpe1 = query("SELECT c.cont_titulo,c.cont_uno,c.cont_dos,c.cont_tres,c.texto_qr,c.fecha_qr,c.fecha2_qr,c.id_fondo_digital,c.id_fondo_fisico,c.sw_solo_nombre FROM cursos_certificados c WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqddcpe2 = fetch($rqddcpe1);
    $dc_cont_titulo = $rqddcpe2['cont_titulo'];
    $dc_cont_uno = $rqddcpe2['cont_uno'];
    $dc_cont_dos = $rqddcpe2['cont_dos'];
    $dc_cont_tres = $rqddcpe2['cont_tres'];
    $dc_texto_qr = $rqddcpe2['texto_qr'];
    $dc_fecha_qr = $rqddcpe2['fecha_qr'];
    $dc_fecha2_qr = $rqddcpe2['fecha2_qr'];
    $dc_id_fondo_digital = $rqddcpe2['id_fondo_digital'];
    $dc_id_fondo_fisico = $rqddcpe2['id_fondo_fisico'];
    $dc_sw_solo_nombre = $rqddcpe2['sw_solo_nombre'];

    query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           cont_titulo, 
           cont_uno, 
           cont_dos, 
           cont_tres, 
           texto_qr, 
           fecha_qr, 
           fecha2_qr, 
           id_fondo_digital, 
           id_fondo_fisico, 
           sw_solo_nombre, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado',
           '$id_curso',
           '$id_participante',
           '$certificado_id',
           '$receptor_de_certificado',
           '$dc_cont_titulo',
           '$dc_cont_uno',
           '$dc_cont_dos',
           '$dc_cont_tres',
           '$dc_texto_qr',
           '$dc_fecha_qr',
           '$dc_fecha2_qr',
           '$dc_id_fondo_digital',
           '$dc_id_fondo_fisico',
           '$dc_sw_solo_nombre',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
    $id_emision_certificado = insert_id();
    $ids_emisiones_certificados .= ',' . $id_emision_certificado;

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

<b class="btn btn-success btn-lg" onclick="imprimir_certificados_adicionales('<?php echo trim($ids_emisiones_certificados, ','); ?>');">IMPRIMIR TODOS</b>

<hr>

<?php
$cont_enlaces_descarga = '*DESCARGA DE CERTIFICADOS: '.trim($participante['nombres'] . ' ' . $participante['apellidos']).'*<br><br>';
$rqmcemcrt1 = query("SELECT certificado_id,texto_qr FROM cursos_emisiones_certificados WHERE id_participante='".$participante['id']."' AND id_curso='$id_curso' ");
$num_emisiones = num_rows($rqmcemcrt1);
while ($rqmcemcrt2 = fetch($rqmcemcrt1)) {
    $certificado_id = $rqmcemcrt2['certificado_id'];
    $texto_qr = $rqmcemcrt2['texto_qr'];
    $cont_enlaces_descarga .= '===========================<br>*'.$certificado_id.':* '.$dominio.'C/'.$certificado_id.'/<br>'.$texto_qr.'<br><br>';
}
?>
<span style="font-size: 8pt;">[Emisiones: <b><?php echo $num_emisiones; ?></b> ]</span>&nbsp;
<span style="font-size: 8pt;">[Impresiones: <b><?php echo $participante['cnt_impresion_certificados']; ?></b> ]</span>
<?php if ($num_emisiones > 0) { ?>
    <br>
    <b class="btn btn-success btn-xs" onclick="enviar_emitidos_por_correo_fromlist(<?php echo $participante['id']; ?>);alert('PROCESO DE ENVIO DE CORREO REALIZADO CORRECTAMENTE \n La confirmacion se mostrara en el listado.');"><i class="fa fa-send"></i> Enviar por correo</b>&nbsp;
    Descarga: <b class="btn btn-default" onclick="copyToClipboard('cont-enlaces-descarga-cert-<?php echo $participante['id']; ?>');"><i class="fa fa-copy"></i></b> &nbsp;&nbsp;&nbsp; <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 35px;border-radius: 20%;cursor: pointer;border: 1px solid #d4d4d4;" onclick="enviar_enlaces_por_wap_fromlist(<?php echo $participante['id']; ?>,'<?php echo $participante['celular']; ?>');">
    <div id="AJAXCONTENT-enviar_emitidos_por_correo_fromlist-<?php echo $participante['id']; ?>"></div>
<?php
}
?>

<hr>