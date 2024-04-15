<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* id curso */
$id_curso = post('id_curso');

/* datos del curso */
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,fecha2,fecha3,imagen,costo,costo2,costo3,sw_fecha2,sw_fecha3,costo_e,fecha_e,sw_fecha_e,costo_e2,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,inicio_numeracion,id_modalidad,c.horarios FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$inicio_numeracion = $rqc2['inicio_numeracion'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$url_imagen_del_curso = $dominio_www . "contenido/imagenes/paginas/" . $rqc2['imagen'];

$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];
$id_modalidad_curso = $rqc2['id_modalidad'];

/* modalidad */
$rqdmldc1 = query("SELECT nombre FROM cursos_modalidades WHERE id='$id_modalidad_curso' ORDER BY id DESC limit 1 ");
$rqdmldc2 = fetch($rqdmldc1);
$modalidad_del_curso = $rqdmldc2['nombre'];

if ($numeracion_por_participantes > $inicio_numeracion) {
    $inicio_numeracion = (int) $numeracion_por_participantes + 1;
}

/* numero tigomoney */
$qrtm1 = query("SELECT n.numero FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros n ON r.id_numtigomoney=n.id WHERE r.id_curso='$id_curso' AND r.sw_numprin=1 AND n.estado=1 ");
$numero_tigomoney = "69714008";
if(num_rows($qrtm1)>0){
    $qrtm2 = fetch($qrtm1);
    $numero_tigomoney = $qrtm2['numero'];
}

$cnt = num_rows($resultado1);
?>

<?php
/* costo */
$f_h = date("H:i", strtotime($rqc2['fecha2']));
if ($f_h !== '00:00') {
    $f_actual = strtotime(date("Y-m-d H:i"));
    $f_limite = strtotime($rqc2['fecha2']);
} else {
    $f_actual = strtotime(date("Y-m-d"));
    $f_limite = strtotime(substr($rqc2['fecha2'], 0, 10));
}
$rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $id_curso . "' ORDER BY r.id ASC LIMIT 1 ");
if (num_rows($rqdwn1) == 0) {
    $num_whatsapp = "69714008";
} else {
    $rqdwn2 = fetch($rqdwn1);
    $num_whatsapp = $rqdwn2['numero'];
}
?>
<div>*<?php echo $nombre_del_curso; ?>*</div>
<div><br></div>
<?php if ($rqc2['id_modalidad'] !== '2') { ?>
    <div>Fecha: &nbsp; <?php echo date("d/m/Y", strtotime($fecha_del_curso)); ?></div>
    <div><br></div>
<?php } ?>
<div>*Duraci&oacute;n:* &nbsp; <?php echo $rqc2['horarios']; ?></div>
<div><br></div>
<div>*Modalidad:* &nbsp;<?php echo $rqc2['id_modalidad'] == '1' ? 'PRESENCIAL' : 'VIRTUAL'; ?></div>
<div><br></div>
<div>*Detalle completo del curso:* &nbsp; <?php echo $dominio . numIDcurso($id_curso) . '/'; ?></div>
<div><br></div>
<?php if ($rqc2['estado'] !== '0') { ?>
    <div>
        <?php if ((int) $rqc2['costo'] > 0) { ?>
            *Inversi&oacute;n:* &nbsp; <?php echo $rqc2['costo']; ?> Bs.
            <div><br></div>
        <?php } else { ?>
            *Ingreso:* GRATUITO con c&eacute;dula de identidad
            <div><br></div>
        <?php } ?>
    </div>
    <?php if ($rqc2['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) { ?>
        <div>*DESCUENTO POR PAGO ANTICIPADO:*</div>
        <div><br></div>
        <div>*Inversi&oacute;n:* <?php echo $rqc2['costo2']; ?> Bs. hasta el <?php echo date("d/m", strtotime($rqc2['fecha2'])); ?></div>
        <div><br></div>
        <?php if ($rqc2['sw_fecha3'] == '1' && ( date("Y-m-d") <= $rqc2['fecha3'])) { ?>
            <div>*Inversi&oacute;n:* <?php echo $rqc2['costo3']; ?> Bs. hasta el <?php echo date("d/m", strtotime($rqc2['fecha3'])); ?></div>
            <div><br></div>
        <?php } ?>
    <?php } ?>
    <?php if ($rqc2['sw_fecha_e'] == '1' && ( date("Y-m-d") <= $rqc2['fecha_e'])) { ?>
        <div>*Estudiantes:* <?php echo $rqc2['costo_e']; ?> Bs. presentando carnet universitario</div>
        <div><br></div>
    <?php } ?>
    <div>*Whatsapp:* &nbsp; <?php echo $wap_shortlink; ?></div>
    <div><br></div>
    <?php if ((int) $rqc2['costo'] > 0) { ?>
        <?php
        $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
        $rqdtcb2 = fetch($rqdtcb1);
        ?>
        <div>*PAGOS:*</div>
        <div><br></div>
        <div><?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?></div>
        <div><br></div>
        <div>Pago por TigoMoney <?php echo $numero_tigomoney; ?> (sin recargo)</div>
        <div><br></div>
        <div>*Otras formas de pago:* <?php echo $dominio; ?>formas-de-pago.html</div>
        <div><br></div>
        <div>Una vez realizado el pago, tiene que registrarse en: <?php echo $dominio; ?>R/<?php echo $id_curso; ?>/</div>
        <div><br></div>
    <?php } ?>
<?php } ?>
<div><br></div>
