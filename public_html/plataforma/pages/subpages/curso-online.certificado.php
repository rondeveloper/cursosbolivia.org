<?php
/* REQUERIDO PHP MAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Load Composer's autoloader */
require '../contenido/librerias/phpmailer/vendor/autoload.php';

/* mensaje */
$mensaje = '';

/* URLTAG de curso */
$urltag_onlinecourse = $get[2];

$rqd1 = query("SELECT * FROM cursos_onlinecourse WHERE urltag='$urltag_onlinecourse' AND estado IN (1,5) ORDER BY id DESC limit 1 ");
if (num_rows($rqd1) == 0) {
    echo "<script>alert('No se encontro resultados.');location.href='$dominio';</script>";
    exit;
}
$onlinecourse = fetch($rqd1);
$id_onlinecourse = $onlinecourse['id'];
$titulo_onlinecourse = $onlinecourse['titulo'];
$sw_cert_onlinecourse = $onlinecourse['sw_cert'];
$contenido_onlinecourse = $onlinecourse['contenido'];
$imagen_onlinecourse = $dominio . "cursos/" . $onlinecourse['imagen'] . ".size=6.img";
$id_onlinecourse_leccion = 0;

/* DOCENTE */
if (isset_docente() && !isset($_SESSION['participante-inscrito'])) {
    $_SESSION['participante-inscrito'] = 'true';
}

/* chat */
$roomcod = '0';

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
    //$rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE estado='1' AND id_curso IN (select id_curso from cursos_rel_cursoonlinecourse where id_onlinecourse='$id_onlinecourse' and estado='1') AND id_usuario='$id_usuario' ");
    //**$rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND fecha_inicio<=CURDATE() AND fecha_final>=CURDATE()) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and ((fecha_inicio<=CURDATE() and fecha_final>=CURDATE()) OR estado='0') and sw_acceso='1')>0 ");
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and sw_acceso='1')>0 ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
    }
} elseif (isset_docente()) {
    $id_docente = docente('id');
    $rqvpcv1 = query("SELECT id,id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_docente='$id_docente' ");
    if (num_rows($rqvpcv1) > 0) {
        $sw_acceso_a_curso = true;
        $rqvpcv2 = fetch($rqvpcv1);
        $roomcod = 'C' . $rqvpcv2['id_curso'];
    }
}
?>

<script src="<?php echo $dominio_www; ?>contenido/librerias/SlickQuiz-master/js/jquery.js"></script>

<link type="text/css" rel="stylesheet" href="<?php echo $dominio_www; ?>contenido/css/style-chat-course-vr.css"/>

<div style="height:50px;"></div>
<div class="boxcontent-curso-online">
    <div class="bar-left-curso-online">
        <?php
        include_once 'pages/items/item.d.curso_online.bar_left.php';
        ?>
    </div>
    <div class="wrapsemibox">
        <section class="containerXX" style="padding: 2px 5px;">
            <div style="height:10px"></div>
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <?php
                        include_once 'pages/items/item.m.datos_onlinecourse.php';
                        ?>
                    </div>

                    <?php echo $mensaje; ?>

                    <?php
                    if (!$sw_acceso_a_curso) {
                        ?>

                        <hr/>
                        <p>
                            Para poder tomar el curso y tener acceso a todos los recursos ofrecidos debes ingresar a tu <b>cuenta de usuario</b>, para ello ingresa a continuaci&oacute;n 
                            los datos de acceso que se te proporcion&oacute; para el curso.
                        </p>
                        <hr/>

                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="boxForm ajusta_form_contacto">
                                    <h5>INGRESA A TU CUENTA</h5>
                                    <hr/>
                                    <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                                        <div class="form-group">
                                            <!--                                            <div class="col-sm-12">
                                                                                            <input class="form-control required string" type="text" name="ci" placeholder="N&uacute;mero de CI..." required="">
                                                                                        </div>-->
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="text" name="usuario" placeholder="Usuario..." required="">
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                            </div>
                                        </div>                    					
                                        <div class="form-group">
                                            <div class="col-md-12 text-center">
                                                <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR"/>
                                            </div>
                                        </div>
                                        <hr/>
                                        <!--                                        <div class="form-group">
                                                                                    <span><b style="font-weight:bold;">&iquest; Ya tienes tu cuenta ?</b> ingresa con el siguiente enlace:</span>
                                                                                    <br/>
                                                                                    <br/>
                                                                                    <div class="col-md-12 text-center">
                                                                                        <a href="ingreso-de-usuarios.html" type="submit" class="btn btn-primary">INGRESAR POR DATOS DE USUARIO</a>
                                                                                    </div>
                                                                                </div>-->
                                    </form>
                                </div>
                            </div>
                        </div>

                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <hr/>
                        <br/>


                        <?php
                    } else {
                        ?>

                        <hr/>

                        <div class="row">
                            <div class="col-md-12">

                                <div style="border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 10px 10px 0px 0px;
                                     padding: 10px 15px;
                                     background: #1b5b77;">
                                    <h2 style="margin-top: 0px;color: #FFF;">CERTIFICADO: <?php echo $titulo_onlinecourse; ?></h2>
                                </div>
                                <div style="background: #FFF;
                                     border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 0px 0px 10px 10px;
                                     padding: 10px 15px;">


                                    <?php
                                    if (isset_post('proceso-emision-certificado')) {
                                        $prefijo_participante = post('prefijo_participante');
                                        $nombres_participante = post('nombres_participante');
                                        $apellidos_participante = post('apellidos_participante');
                                        $nro_cert = post('nro_cert');

                                        /* emite cert */

                                        /* sw_finalizacion */
                                        $sw_finalizacion = true;
                                        $rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                                        while ($rqlcv2 = fetch($rqlcv1)) {
                                            $id_leccion = $rqlcv2['id'];
                                            $titulo_leccion = $rqlcv2['titulo'];
                                            $minutos_leccion = $rqlcv2['minutos'];
                                            $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' AND l.id_onlinecourse='$id_onlinecourse' ");
                                            $tt_leccion = '0/' . $minutos_leccion;

                                            $p = 0;
                                            if (num_rows($rqdavl1) > 0) {
                                                $rqdavl2 = fetch($rqdavl1);
                                                $t = $rqdavl2['minutos'] * 60;
                                                $s = $rqdavl2['segundos'];
                                                $p = round($s * 100 / $t);
                                                if ($p > 100) {
                                                    $p = 100;
                                                    $rqdavl2['segundos'] = $t;
                                                    //*$sw_finalizacion = true;
                                                }
                                                /* excepcion para leccion de 1 minuto */
                                                if($minutos_leccion==1){
                                                    $p = 100;
                                                }
                                                $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
                                            }
                                            if ($p <= 90) {
                                                $sw_finalizacion = false;
                                            }
                                        }

                                        /* contenido */
                                        if ($sw_finalizacion) {
                                            /* participante */
                                            $data_required = "(c.id)dr_id_curso,"
                                                    . "(c.titulo)dr_nombre_curso,"
                                                    . "(c.numero)dr_numero_curso,"
                                                    . "(r.id_certificado)dr_id_certificado,"
                                                    . "(r.id_certificado_2)dr_id_certificado_2,"
                                                    . "(p.id)dr_id_participante,"
                                                    . "(p.prefijo)dr_prefijo_participante,"
                                                    . "(p.nombres)dr_nom_participante,"
                                                    . "(p.apellidos)dr_ape_participante,"
                                                    . "(concat(p.nombres,' ',p.apellidos))dr_nombre_participante"
                                                    . "";
                                            $qrdp1 = query("SELECT $data_required FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=p.id_curso INNER JOIN cursos c ON c.id=p.id_curso WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' AND p.estado='1' ");
                                            if (num_rows($qrdp1) > 0) {
                                                $qrdp2 = fetch($qrdp1);
                                                $id_curso = $qrdp2['dr_id_curso'];
                                                $numero_curso = $qrdp2['dr_numero_curso'];
                                                if($nro_cert=='2'){
                                                    $id_certificado = $qrdp2['dr_id_certificado_2'];
                                                }else{
                                                    $id_certificado = $qrdp2['dr_id_certificado'];
                                                }
                                                $nombre_curso = $qrdp2['dr_nombre_curso'];
                                                $nombre_participante = $qrdp2['dr_nombre_participante'];
                                                $id_participante = $qrdp2['dr_id_participante'];
                                                $current_prefijo_participante = $qrdp2['dr_prefijo_participante'];
                                                $current_nom_participante = $qrdp2['dr_nom_participante'];
                                                $current_ape_participante = $qrdp2['dr_ape_participante'];

                                                /* update de datos de participante */
                                                if (($current_prefijo_participante != $prefijo_participante) || ($current_nom_participante != $nombres_participante) || ($current_ape_participante != $apellidos_participante)) {
                                                    query("UPDATE cursos_participantes SET prefijo='$prefijo_participante',nombres='$nombres_participante',apellidos='$apellidos_participante' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                                                    logcursos('Edicion de datos de participante [por-usuario]', 'partipante-edicion', 'participante', $id_participante);
                                                }

                                                /* emision de certificado */
                                                $rqedc1 = query("SELECT * FROM cursos_emisiones_certificados WHERE id_participante='$id_participante' AND id_certificado='$id_certificado' ");
                                                if (num_rows($rqedc1) == 0) {
                                                    $receptor_de_certificado = $prefijo_participante . ' ' . $nombres_participante . ' ' . $apellidos_participante;
                                                    $id_administrador = '0';
                                                    $fecha_emision = date("Y-m-d H:i:s");

                                                    $limit_certificado = 10;
                                                    /* formato de certificado */
                                                    $rqdfc1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
                                                    $rqdfc2 = fetch($rqdfc1);
                                                    $formato_certificado = $rqdfc2['formato'];

                                                    /* datos curso */
                                                    $rqdcf1 = query("SELECT fecha,id_ciudad,id_certificado,id_certificado_2,id_certificado_3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                                                    $rqdcf2 = fetch($rqdcf1);
                                                    $fecha_curso = $rqdcf2['fecha'];
                                                    $id_ciudad = $rqdcf2['id_ciudad'];
                                                    $aux_id_emision_certificado = $rqdcf2['id_certificado'];
                                                    $aux_id_emision_certificado_2 = $rqdcf2['id_certificado_2'];
                                                    $aux_id_emision_certificado_3 = $rqdcf2['id_certificado_3'];

                                                    /* datos ciudad */
                                                    $rqdcd1 = query("SELECT cod FROM ciudades WHERE id='$id_ciudad' ORDER BY id DESC limit 1 ");
                                                    $rqdcd2 = fetch($rqdcd1);
                                                    $cod_i_ciudad = $rqdcd2['cod'];

                                                    /* verificacion de emision anterior */
                                                    $rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado'  ORDER BY id DESC limit 1 ");
                                                    if (num_rows($rqve1) >= $limit_certificado && false) {
                                                        echo '<div class="alert alert-danger">
                                                            <strong>Error!</strong> receptor de certificado ya existente.
                                                        </div>';
                                                        exit;
                                                    }

                                                    /* id cert */
                                                    $certificado_id = getIDcert($cod_i_ciudad);
                                                    
                                                    /* data de certificado */
                                                    $rqddcpe1 = query("SELECT c.cont_titulo,c.cont_uno,c.cont_dos,c.cont_tres,c.texto_qr,c.fecha_qr,c.fecha2_qr,c.id_fondo_digital,c.sw_solo_nombre FROM cursos_certificados c WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
                                                    $rqddcpe2 = fetch($rqddcpe1);
                                                    $dc_cont_titulo = $rqddcpe2['cont_titulo'];
                                                    $dc_cont_uno = $rqddcpe2['cont_uno'];
                                                    $dc_cont_dos = $rqddcpe2['cont_dos'];
                                                    $dc_cont_tres = $rqddcpe2['cont_tres'];
                                                    $dc_texto_qr = $rqddcpe2['texto_qr'];
                                                    $dc_fecha_qr = $rqddcpe2['fecha_qr'];
                                                    $dc_fecha2_qr = $rqddcpe2['fecha2_qr'];
                                                    $dc_id_fondo_digital = $rqddcpe2['id_fondo_digital'];
                                                    $dc_sw_solo_nombre = $rqddcpe2['sw_solo_nombre'];

                                                    /* registro */
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
                                                        '$dc_sw_solo_nombre',
                                                        '$id_administrador',
                                                        '$fecha_emision',
                                                        '1'
                                                        )");
                                                    $id_emision_certificado = insert_id();

                                                    /* sw cierre */
                                                    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

                                                    /* actualizacion de participante */
                                                    switch ($id_certificado) {
                                                        case $aux_id_emision_certificado:
                                                            query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                                                            logcursos('Emision de 1er certificado [por-usuario][' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                                                            if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                                                                logcursos('Emision de 1er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                                                            }
                                                            break;
                                                        case $aux_id_emision_certificado_2:
                                                            query("UPDATE cursos_participantes SET id_emision_certificado_2='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                                                            logcursos('Emision de 2do certificado [por-usuario][' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                                                            if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                                                                logcursos('Emision de 2do certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                                                            }
                                                            break;
                                                        case $aux_id_emision_certificado_3:
                                                            query("UPDATE cursos_participantes SET id_emision_certificado_3='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                                                            logcursos('Emision de 3er certificado [por-usuario][' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                                                            if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                                                                logcursos('Emision de 3er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                                                            }
                                                            break;
                                                        default:
                                                            query("INSERT INTO cursos_rel_partcertadicional (id_participante,id_certificado,id_emision_certificado) VALUES ('$id_participante','$id_certificado','$id_emision_certificado') ");
                                                            logcursos('Emision de certificado adicional [por-usuario][' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                                                            if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                                                                logcursos('Emision de certificado adicional [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                                                            }
                                                            break;
                                                    }
                                                    ?>
                                                    <div class="alert alert-success">
                                                        <strong>EXITO!</strong> certificado emitido correctamente.
                                                    </div>
                                                    <hr>
                                                    <?php
                                                    /* envio de correo de certificado */
                                                    enviar_correo_emision_certificado($id_emision_certificado);
                                                }
                                            }
                                        }
                                    }
                                    ?>

                                    <?php
                                    /* sw_finalizacion */
                                        $sw_finalizacion = true;
                                        $rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                                        while ($rqlcv2 = fetch($rqlcv1)) {
                                            $id_leccion = $rqlcv2['id'];
                                            $titulo_leccion = $rqlcv2['titulo'];
                                            $minutos_leccion = $rqlcv2['minutos'];
                                            $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' AND l.id_onlinecourse='$id_onlinecourse' ");
                                            $tt_leccion = '0/' . $minutos_leccion;

                                            $p = 0;
                                            if (num_rows($rqdavl1) > 0) {
                                                $rqdavl2 = fetch($rqdavl1);
                                                $t = $rqdavl2['minutos'] * 60;
                                                $s = $rqdavl2['segundos'];
                                                $p = round($s * 100 / $t);
                                                if ($p > 100) {
                                                    $p = 100;
                                                    $rqdavl2['segundos'] = $t;
                                                    //*$sw_finalizacion = true;
                                                }
                                                $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
                                            }
                                            if ($p <= 90) {
                                                $sw_finalizacion = false;
                                            }
                                        }
                                    ?>
                                    <h3 class="">EMISI&Oacute;N DE CERTIFICADO</h3>
                                    <?php
                                    if($sw_finalizacion){
                                        ?>
                                        <p>
                                            A continuaci&oacute;n le mostramos los datos del certificado virtual que se le emiti&oacute; para este curso, el cual debe imprimirlo en cartulina desde una impresora a color de alta definici&oacute;n o descargarlo en forma de archivo PDF, 
                                            en los datos visualizados podr&aacute; ver un ID de certificado con el cual podr&aacute; comprobar la valid&eacute;z del certificado en cualquier momento desde el portal <b><?php echo $___nombre_del_sitio; ?></b>. 
                                            <br>
                                            <br>
                                            Tambi&eacute;n puede optar por legalizar f&iacute;sicamente la impresi&oacute;n de su certificado acercandose a nuestras oficinas o al lugar donde brindemos cursos presenciales en su ciudad llevando con usted la impresion que desea legalizar.
                                        </p>
                                        <hr>
                                        <?php
                                    }else if($onlinecourse['sw_examen']=='1'){
                                        ?> 
                                        <div class="">
                                            Para la emisi&oacute;n e impresi&oacute;n del certificado correspondiente a este curso es necesario completar todos los modulos asignados a &eacute;ste y aprobar el examen virtual de evaluaci&oacute;n de aprendizaje con una nota mayor al 50%, puede ver su avance de los modulos en la parte inferior de esta p&aacute;gina. 
                                            <br><br>
                                            Una vez complete el curso y apruebe la evaluaci&oacute;n puede hacer clic en el bot&oacute;n 'SOLICITAR CERTIFICADO' de esta p&aacute;gina y de esta forma se le emitir&aacute; un certificado digital el cual debe imprimirlo en cartulina desde una impresora a color de alta definici&oacute;n o descargarlo en forma de archivo PDF, 
                                            en los datos visualizados podr&aacute; ver el ID de certificado con el cual podr&aacute; comprobar la valid&eacute;z del certificado en cualquier momento desde el portal <b><?php echo $___nombre_del_sitio; ?></b>.
                                        </div>
                                        <hr>
                                        <?php
                                    }else{
                                        ?> 
                                        <div class="">
                                            Para la emisi&oacute;n e impresi&oacute;n del certificado correspondiente a este curso es necesario completar todos los modulos asignados a &eacute;ste, puede ver su avance de los modulos en la parte inferior de esta p&aacute;gina. 
                                            <br><br>
                                            Una vez complete el curso puede hacer clic en el bot&oacute;n 'SOLICITAR CERTIFICADO' de esta p&aacute;gina y de esta forma se le emitir&aacute; un certificado digital el cual debe imprimirlo en cartulina desde una impresora a color de alta definici&oacute;n o descargarlo en forma de archivo PDF, 
                                            en los datos visualizados podr&aacute; ver el ID de certificado con el cual podr&aacute; comprobar la valid&eacute;z del certificado en cualquier momento desde el portal <b><?php echo $___nombre_del_sitio; ?></b>.
                                        </div>
                                        <hr>
                                        <?php
                                    }
                                    
                                    
                                    /* primer certificado */
                                    $rqvedce1 = query("SELECT cr.texto_qr,e.certificado_id,e.fecha_emision,p.prefijo,p.nombres,p.apellidos FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=p.id_curso INNER JOIN cursos c ON c.id=p.id_curso INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados cr ON cr.id=r.id_certificado WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' AND e.id_certificado=cr.id AND p.estado='1' ORDER BY e.id DESC limit 1 ");
                                    if (num_rows($rqvedce1) > 0) {
                                        $rqvedce2 = fetch($rqvedce1);
                                        ?>
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td>ID de certificado: </td>
                                                <td><?php echo $rqvedce2['certificado_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Certificado: </td>
                                                <td><?php echo $rqvedce2['texto_qr']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Emitido a: </td>
                                                <td><?php echo $rqvedce2['prefijo'] . ' ' . $rqvedce2['nombres'] . ' ' . $rqvedce2['apellidos']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Fecha de emisi&oacute;n: </td>
                                                <td><?php echo date("d/m/Y H:i", strtotime($rqvedce2['fecha_emision'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Visualizaci&oacute;n / Impresi&oacute;n: </td>
                                                <td style="padding: 20px;">
                                                    <button onclick="window.open('<?php echo $dominio_www; ?>contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=<?php echo $rqvedce2['certificado_id']; ?>', 'popup', 'width=700,height=500');" class="btn btn-success btn-block">
                                                        VISUALIZAR CERTIFICADO
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                        <hr/>
                                        <?php
                                    } else {
                                        ?> 
                                        <div class="text-center">
                                            <a data-toggle="modal" data-target="#MODAL-solicitar_certificado" onclick="solicitar_certificado('1');" class="btn btn-lg btn-success" style="border-radius: 15px;
                                               padding: 10px 30px;
                                               border: 1px solid #c5edff;
                                               box-shadow: 2px 3px 3px 0px #c7c7c7;
                                               font-size: 17pt;">
                                                <i class="fa fa-certificate"></i> &nbsp; SOLICITAR CERTIFICADO
                                            </a>
                                            <hr/>
                                        </div>
                                        <?php
                                    }                                        
                                        
                                    /* segundo certificado */
                                    $rqvexdsc1 = query("SELECT r.id_certificado_2 FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=p.id_curso INNER JOIN cursos c ON c.id=p.id_curso INNER JOIN cursos_certificados cr ON cr.id=r.id_certificado_2 WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' AND p.estado='1' LIMIT 1 ");
                                    if(num_rows($rqvexdsc1)>0){
                                        $rqvedce1 = query("SELECT cr.texto_qr,e.certificado_id,e.fecha_emision,p.prefijo,p.nombres,p.apellidos FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=p.id_curso INNER JOIN cursos c ON c.id=p.id_curso INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados cr ON cr.id=r.id_certificado_2 WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' AND e.id_certificado=cr.id AND p.estado='1' ORDER BY e.id DESC limit 1 ");
                                        if (num_rows($rqvedce1) > 0) {
                                            $rqvedce2 = fetch($rqvedce1);
                                            ?>
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <td>ID de certificado: </td>
                                                    <td><?php echo $rqvedce2['certificado_id']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Certificado: </td>
                                                    <td><?php echo $rqvedce2['texto_qr']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Emitido a: </td>
                                                    <td><?php echo $rqvedce2['prefijo'] . ' ' . $rqvedce2['nombres'] . ' ' . $rqvedce2['apellidos']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Fecha de emisi&oacute;n: </td>
                                                    <td><?php echo date("d/m/Y H:i", strtotime($rqvedce2['fecha_emision'])); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Visualizaci&oacute;n / Impresi&oacute;n: </td>
                                                    <td style="padding: 20px;">
                                                        <button onclick="window.open('<?php echo $dominio_www; ?>contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=<?php echo $rqvedce2['certificado_id']; ?>', 'popup', 'width=700,height=500');" class="btn btn-success btn-block">
                                                            VISUALIZAR CERTIFICADO
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                            <hr/>
                                            <?php
                                        } else {
                                            ?> 
                                            <div class="text-center">
                                                <b>SEGUNDO CERTIFICADO:</b>
                                                <hr/>
                                                <a data-toggle="modal" data-target="#MODAL-solicitar_certificado" onclick="solicitar_certificado('2');" class="btn btn-lg btn-success" style="border-radius: 15px;
                                                   padding: 10px 30px;
                                                   border: 1px solid #c5edff;
                                                   box-shadow: 2px 3px 3px 0px #c7c7c7;
                                                   font-size: 17pt;">
                                                    <i class="fa fa-certificate"></i> &nbsp; SOLICITAR 2DO CERTIFICADO
                                                </a>
                                                <hr/>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <div>
                                        <?php
                                        $rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
                                        if(num_rows($rqlcv1)>0){
                                            ?>
                                            <h4 style="background: #318cb8;
                                                color: #FFF;
                                                padding: 7px 15px;
                                                border-radius: 5px;
                                                border-bottom: 4px solid #1b5b77;">TU AVANCE EN EL CURSO</h4>
                                            <p>A continuaci&oacute;n te mostramos el avance que tienes en cada uno de los modulos de este curso.</p>
                                            <div>
                                                <?php
                                                while ($rqlcv2 = fetch($rqlcv1)) {
                                                    $id_leccion = $rqlcv2['id'];
                                                    $titulo_leccion = $rqlcv2['titulo'];
                                                    $minutos_leccion = $rqlcv2['minutos'];
                                                    $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='$id_usuario' AND l.id='$id_leccion' ");
                                                    $tt_leccion = '0/' . $minutos_leccion;
                                                    $p = 0;
                                                    if (num_rows($rqdavl1) > 0) {
                                                        $rqdavl2 = fetch($rqdavl1);
                                                        $t = $rqdavl2['minutos'] * 60;
                                                        $s = $rqdavl2['segundos'];
                                                        $p = round($s * 100 / $t);
                                                        if ($p > 100) {
                                                            $p = 100;
                                                            $rqdavl2['segundos'] = $t;
                                                        }

                                                        $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
                                                        $segundos_avanzados = $rqdavl2['segundos'];
                                                        /* excepcion para leccion de 1 minuto */
                                                        if($minutos_leccion==1){
                                                            $segundos_avanzados += 60;
                                                            $p = 100;
                                                        }
                                                    }else{
                                                        $segundos_avanzados = 0;
                                                    }
                                                    ?>
                                                    <b><?php echo $titulo_leccion; ?></b>
                                                    <span class="pull-right"><?php echo round(($segundos_avanzados) / 60, 2); ?>/<?php echo $minutos_leccion; ?> minutos</span>
                                                    <br/>
                                                    <div class="progress" style="background: #d2d8dc;">
                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $p; ?>%;">
                                                            <?php echo $p; ?>% Completo (terminado)
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        
                                        $rqepe1 = query("SELECT id FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='$id_onlinecourse' LIMIT 1 ");
                                        if (num_rows($rqepe1) > 0 && $onlinecourse['sw_examen']=='1') {
                                            ?>
                                            <hr/>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">EVALUACIONES REALIZADAS</div>
                                                <div class="panel-body">
                                                    <?php
                                                    $rqdael1 = query("SELECT * FROM cursos_onlinecourse_evaluaciones WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ");
                                                    if (num_rows($rqdael1) == 0) {
                                                        echo "<p>No se registraron evaluaciones</p>";
                                                    } else {
                                                        ?>
                                                        <table class="table table-striped table-bordered">
                                                            <?php
                                                            while ($rqdavl2 = fetch($rqdael1)) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <b class="btn btn-sm btn-success active"><?php echo round(($rqdavl2['total_correctas'] * 100) / $rqdavl2['total_preguntas'], 1); ?>%</b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="label label-primary"><?php echo $rqdavl2['total_correctas'] . '/' . $rqdavl2['total_preguntas']; ?> respuestas correctas</b>
                                                                    </td>
                                                                    <td>
                                                                        Fecha: <?php echo date("d/m/Y H:i", strtotime($rqdavl2['fecha'])); ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <?php
                        /* chat de curso */
                        include_once 'pages/items/item.b.chat_content_data.php';
                        ?>

                        <br/>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div style="height:10px"></div>
        </section>
    </div>   

</div>

<?php
$id_usuario = usuario('id');
?>
<!-- AJAX solicitar_certificado -->
<script>
    function solicitar_certificado(nro_cert) {
        $("#AJAXCONTENT-solicitar_certificado").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.curso-online.certificado.solicitar_certificado.php',
            type: 'POST',
            data: {id_usuario: '<?php echo $id_usuario; ?>', id_onlinecourse: '<?php echo $id_onlinecourse; ?>', nro_cert: nro_cert},
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-solicitar_certificado").html(data);
            }
        });
    }
</script>

<!-- Modal -->
<div id="MODAL-solicitar_certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISI&Oacute;N DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-solicitar_certificado"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php

function enviar_correo_emision_certificado($id_emision_certificado){
    global $___path_raiz,$dominio_www,$dominio;
    /* registros */
    $rqdr1 = query("SELECT p.correo,p.id,e.certificado_id,e.receptor_de_certificado,e.fecha_emision,c.texto_qr FROM cursos_participantes p INNER JOIN cursos_emisiones_certificados e ON e.id_participante=p.id INNER JOIN cursos_certificados c ON e.id_certificado=c.id WHERE e.id='$id_emision_certificado' ORDER BY e.id DESC limit 1 ");
    $rqdr2 = fetch($rqdr1);

    $id_participante = $rqdr2['id'];
    $correo_participante = $rqdr2['correo'];
    $certificado_id = $rqdr2['certificado_id'];
    $receptor_de_certificado = $rqdr2['receptor_de_certificado'];
    $fecha_emision_certificado = $rqdr2['fecha_emision'];
    $texto_qr_certificado = $rqdr2['texto_qr'];

    $htm = '
    <p>
    Saludos cordiales
    <br/>
    Se le hace el env&iacute;o del certificado ' . $certificado_id . ' emitido por NEMABOL el d&iacute;a ' . date("d", strtotime($fecha_emision_certificado)) . ' de ' . date("M", strtotime($fecha_emision_certificado)) . ' de ' . date("Y", strtotime($fecha_emision_certificado)) . ' en formato PDF adjuntado en este correo, 
    a continuaci&oacute;n los datos del certificado correspondiente. 
    <br/>
    </p>

    <table>
    <tr>
    <td><b>ID de certificado:</b></td>
    <td>' . $certificado_id . '</td>
    </tr>
    <tr>
    <tr>
    <td><b>Certificado:</b></td>
    <td>' . utf8_decode($texto_qr_certificado) . '</td>
    </tr>
    <tr>
    <td><b>Receptor del certificado:</b></td>
    <td>' . utf8_decode($receptor_de_certificado) . '</td>
    </tr>
    <tr>
    <td><b>Fecha de emisi&oacute;n:</b></td>
    <td>' . date("d / M / Y", strtotime($fecha_emision_certificado)) . '</td>
    </tr>
    </table>
    <br/>
    ';


    $asunto = utf8_decode('CERTIFICADO DIGITAL '.$certificado_id.' - ' . $texto_qr_certificado);
    $subasunto = utf8_decode($texto_qr_certificado);

    $contenido_correo = platillaEmailUno($htm,$subasunto,$correo_participante,urlUnsubscribe($correo_participante),$receptor_de_certificado);
    
    /* variables para los datos del archivo */
    $nombrearchivo = "certificado-$certificado_id.pdf";
    $url_archivo = $dominio_www."contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=$certificado_id";

    $archivo_cont = file_get_contents($url_archivo);

    $subject = $asunto;
    $body = $contenido_correo;

    $nuevoarchivo = fopen($___path_raiz.'contenido/archivos/'.$nombrearchivo, "w+");
    fwrite($nuevoarchivo, $archivo_cont);
    fclose($nuevoarchivo);
    
    /* envio de correo */
    SISTsendEmailFULL($correo_participante,$subject,$body,'',array(array($___path_raiz.'contenido/archivos/'.$nombrearchivo, $receptor_de_certificado)));
    
    unlink($___path_raiz.'contenido/archivos/'.$nombrearchivo);
    logcursos('Envio digital de certificado [' . $certificado_id . '] [' . $correo_participante . ']', 'participante-envio', 'participante', $id_participante);
}

