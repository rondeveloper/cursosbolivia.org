<?php
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

if (isset_post('ingresar')) {
    $ci = post('ci');

    $usuario = trim(post('usuario'));
    $password = trim(post('password'));
    //*$cod_registro = post('cod_registro');

    /*
      $sw_participante_encontrado = false;
      $rqvdu1 = query("SELECT id FROM cursos_proceso_registro WHERE id_curso='$id_curso' AND codigo='$cod_registro' ");
      if (num_rows($rqvdu1) > 0) {
      $rqvdu2 = fetch($rqvdu1);
      $id_proceso_registro = $rqvdu2['id'];
      $rqvpc1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id_proceso_registro='$id_proceso_registro' AND nombres LIKE '$nombres' AND apellidos LIKE '$apellidos' ");
      if (num_rows($rqvpc1) > 0) {
      $sw_participante_encontrado = true;
      }
      }
     */

    /* ingreso por datos */
    $sw_participante_encontrado = false;

    $rqvpc1 = query("SELECT * FROM cursos_usuarios WHERE estado='1' AND email='$usuario' AND password='$password' ");
    if (num_rows($rqvpc1) > 0) {
        $rqvpc2 = fetch($rqvpc1);
        $id_usuario = $rqvpc2['id'];

        //***$rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' AND fecha_inicio<=CURDATE() AND fecha_final>=CURDATE()) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and ((fecha_inicio<=CURDATE() and fecha_final>=CURDATE()) OR estado='0') and sw_acceso='1')>0 ");
        $rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' AND (select count(*) from cursos_onlinecourse_acceso where id_usuario='$id_usuario' and id_onlinecourse='$id_onlinecourse' and sw_acceso='1')>0 ");
        //$rqdcp1 = query("SELECT id FROM cursos_participantes WHERE id_curso IN (SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ) AND id_usuario='$id_usuario' ");
        if (num_rows($rqdcp1) > 0) {
            //echo "<br/><br/><hr/> --> $id_usuario ";exit;
            usuarioSet('id', $id_usuario);
            $sw_participante_encontrado = true;
            /* primer ingreso participante */
            $rqdvpi1 = query("SELECT id,estado FROM cursos_onlinecourse_acceso WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ");
            $rqdvpi2 = fetch($rqdvpi1);
            if ($rqdvpi2['estado'] == '0') {
                $fecha_inicio_cv = date("Y-m-d");
                $fecha_final_cv = date("Y-m-d", strtotime("+1 week"));
                query("UPDATE cursos_onlinecourse_acceso SET estado='1', fecha_inicio='$fecha_inicio_cv', fecha_final='$fecha_final_cv' WHERE id='" . $rqdvpi2['id'] . "' ");
            }
        } else {
            $mensaje .= '<br/><div class="alert alert-info">
  <strong>USUARIO SIN ACCESO A CURSO VIRTUAL</strong> el usuario ingresado no tiene acceso a este curso virtual, ya sea por limite de acceso al curso o por deshabilitacion de participante.
</div>';
        }
    }

    if (!$sw_participante_encontrado) {
        $mensaje .= '<div class="alert alert-info">
  <strong>ACCESO DENEGADO</strong> los datos que ingresaste no corresponden a alg&uacute;n participante inscrito y habilitado para este curso.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-success">
  <strong>EXECELENTE</strong> participante encontrado como inscrito en este curso.
</div>
';
        $_SESSION['participante-inscrito'] = 'true';
        echo "<script>location.href='curso-online/$urltag_onlinecourse.html';</script>";
        exit;
    }
}



/* enviar-tarea */
if (isset_post('enviar-tarea')) {
    $id_tarea = post('id_tarea');
    $tag_image = 'tarea';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz . 'contenido/archivos/tareas/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt'))) {
            $nombre_imagen = rand(999, 99999) . str_replace(' ', '-', str_replace("'", "", $_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            query("INSERT INTO cursos_onlinecourse_tareasenvios (id_usuario,id_tarea,archivo) VALUES ('$id_usuario','$id_tarea','$nombre_imagen') ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> la tarea se subio correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
}

/* re-enviar-tarea */
if (isset_post('re-enviar-tarea')) {
    $id_tarea = post('id_tarea');
    $id_envio = post('id_envio');
    $tag_image = 'tarea';
    if (is_uploaded_file($_FILES[$tag_image]['tmp_name'])) {
        $carpeta_destino = $___path_raiz . 'contenido/archivos/tareas/';
        $ext = strtolower(pathinfo($_FILES[$tag_image]['name'], PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'tif', 'tiff', 'raw', 'bmp', 'svg', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt'))) {
            $nombre_imagen = rand(999, 99999) . str_replace(' ', '-', str_replace("'", "", $_FILES[$tag_image]['name']));
            move_uploaded_file($_FILES[$tag_image]['tmp_name'], $carpeta_destino . $nombre_imagen);
            $rqdprsend1 = query("SELECT archivo FROM cursos_onlinecourse_tareasenvios WHERE id='$id_envio' ORDER BY id DESC limit 1 ");
            $rqdprsend2 = fetch($rqdprsend1);
            unlink($carpeta_destino . $rqdprsend2['archivo']);
            query("UPDATE cursos_onlinecourse_tareasenvios SET archivo='$nombre_imagen' WHERE id='$id_envio' ORDER BY id DESC limit 1 ");
            $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> la tarea se volvio a subir correctamente.
</div>';
        } else {
            $mensaje .= '<br><div class="alert alert-danger">
  <strong>ERROR</strong> el formato de archivo no esta permitido [' . $ext . '].
</div>';
        }
    }
}

$correo_get = '';
if (isset($get[3])) {
    $id_us = substr($get[3], 0, (count($get[3]) - 4));
    $hash_id = substr($get[3], (count($get[3]) - 4));
    //echo "<br><br><br><br><br> $id_us = $hash_id ";exit;
    if ($hash_id == substr(md5('rtc' . $id_us . '-754'), 19, 3)) {
        $rqdcu1 = query("SELECT email FROM cursos_usuarios WHERE id='$id_us' ORDER BY id DESC limit 1 ");
        $rqdcu2 = fetch($rqdcu1);
        $correo_get = $rqdcu2['email'];
    }
}


/* rel curso onlinecourse */
if (isset_usuario()) {
    $rqdrccv1 = query("SELECT r.*,(p.id)dr_id_participante,(CONCAT(p.nombres,' ',p.apellidos))dr_nombre_participante FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON p.id_curso=r.id_curso WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' ORDER BY id DESC limit 1 ");
    $rqdrccv2 = fetch($rqdrccv1);
    $sw_cod_asistencia_onlinecourse = $rqdrccv2['sw_cod_asistencia'];
    $id_rel_cursoonlinecourse = $rqdrccv2['id'];
    $id_participante = $rqdrccv2['dr_id_participante'];
    $nombre_participante = $rqdrccv2['dr_nombre_participante'];
}

?>

<!--<script src="<?php echo $dominio_www; ?>contenido/librerias/SlickQuiz-master/js/jquery.js"></script>-->

<!--<link type="text/css" rel="stylesheet" href="<?php echo $dominio_www; ?>contenido/css/style-chat-course-vr.css"/>-->

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

                        <hr />
                        <p>
                            Para poder tomar el curso y tener acceso a todos los recursos ofrecidos debes ingresar a tu <b>cuenta de usuario</b>, para ello ingresa a continuaci&oacute;n
                            los datos de acceso que se te proporcion&oacute; para el curso.
                        </p>
                        <hr />

                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="boxForm ajusta_form_contacto">
                                    <h5>INGRESA A TU CUENTA</h5>
                                    <hr />
                                    <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                                        <div class="form-group">
                                            <!--                                            <div class="col-sm-12">
                                                                                            <input class="form-control required string" type="text" name="ci" placeholder="N&uacute;mero de CI..." required="">
                                                                                        </div>-->
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="text" name="usuario" placeholder="Usuario..." value="<?php echo trim($correo_get); ?>" required="">
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 text-center">
                                                <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR" />
                                            </div>
                                        </div>
                                        <hr />
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

                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <hr />
                        <br />


                    <?php
                    } else {
                    ?>

                        <?php
                        if ($sw_cod_asistencia_onlinecourse == '1' && isset_usuario()) {
                        ?>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="border: 1px solid #8fa1b9;
                                         box-shadow: 2px 1px 9px 0px #c5c5c5;
                                         border-radius: 10px 10px 0px 0px;
                                         padding: 10px 15px;
                                         background: #1b5b77;">
                                        <h2 style="margin-top: 0px;color: #FFF;">C&Oacute;DIGO DE ASISTENCIA</h2>
                                    </div>
                                    <div style="background: #FFF;
                                         border: 1px solid #8fa1b9;
                                         box-shadow: 2px 1px 9px 0px #c5c5c5;
                                         border-radius: 0px 0px 10px 10px;
                                         padding: 10px 15px;">
                                        Saludos, puedes generar el c&oacute;digo de asistencia correspondiente a tu cuenta desde el siguiente cuadro:
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <?php
                                                /*
                                                $rqp1 = query("SELECT (p.id)dr_id_participante,(r.id)dr_id_rel FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON p.id_curso=r.id_curso WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='$id_onlinecourse' ");
                                                $rqp2 = fetch($rqp1);
                                                $id_rel_cursoonlinecourse = $rqp2['dr_id_rel'];
                                                $id_participante = $rqp2['dr_id_participante'];
                                                */

                                                if (isset_post('generar-codigo-asistencia')) {
                                                    $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                                                    $cod_asist = $alphabet[rand(0, 25)] . rand(1, 9) . $alphabet[rand(0, 25)];
                                                    query("INSERT INTO cursos_onlinecourse_asistencia(id_rel_cursoonlinecourse, id_usuario, id_participante, cod, fecha) VALUES ('$id_rel_cursoonlinecourse','$id_usuario','$id_participante','$cod_asist',CURDATE())");
                                                    echo '<div class="alert alert-success">
  <strong>EXITO</strong> c&oacute;digo generado correctamente.
</div>';
                                                }

                                                $rqvas1 = query("SELECT cod FROM cursos_onlinecourse_asistencia WHERE fecha=CURDATE() AND id_usuario='$id_usuario' AND id_rel_cursoonlinecourse='$id_rel_cursoonlinecourse' ");
                                                if (num_rows($rqvas1) == 0) {
                                                ?>
                                                    <form action="" method="POST">
                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <td class="text-center">
                                                                    Genera tu c&oacute;digo y envialo al chat de la sesi&oacute;n en vivo.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;padding: 20px;font-size: 17pt;"><b>C&oacute;digo de asistencia para hoy <?php echo date("d/m/Y"); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;padding: 15px;">
                                                                    <input type="submit" class="btn btn-lg btn-info" value="GENERAR C&Oacute;DIGO" name="generar-codigo-asistencia" style="width: auto;border-radius: 7px;background: #aa2cca;" />
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </form>
                                                <?php
                                                } else {
                                                    $rqvas2 = fetch($rqvas1);
                                                    $cod_asist = $rqvas2['cod'];
                                                ?>
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <td style="text-align: center;padding: 10px;font-size: 12pt;"><b>C&oacute;digo de asistencia para hoy <?php echo date("d/m/Y"); ?></b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: center;padding: 20px;"><b style="font-weight: bold;font-size: 40pt;color: #1b5b77;"><?php echo $cod_asist; ?></b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                Envia tu c&oacute;digo y nombre al chat de la sesi&oacute;n en vivo.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;padding: 10px;">
                                                                <b class="pull-right" onclick="copyToClipboard('dat-cod-asit');alert('Copiado al porta papeles (Crtl + C)');" title="COPIAR AL PORTA PAPELES (CRTL + C)">
                                                                    <i class="fa fa-copy" style="padding: 10px;border: 1px solid #1e5d79;font-size: 20pt;border-radius: 8px;background: #f9f9f9;color: #1b5b77;box-shadow: 1px 1px 3px 0px #bdbdbd;cursor: pointer;margin-top: 15px;"></i>
                                                                </b>
                                                                <div style="padding: 10px;font-size: 15pt;" id="dat-cod-asit">
                                                                    C&Oacute;DIGO ASISTENCIA: <?php echo $cod_asist; ?>
                                                                    <br>
                                                                    <?php echo $nombre_participante; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr />

                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <hr />

                        <div class="row">
                            <div class="col-md-12">

                                <div style="border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 10px 10px 0px 0px;
                                     padding: 10px 15px;
                                     background: #1b5b77;">
                                    <h2 style="margin-top: 0px;color: #FFF;"><?php echo $titulo_onlinecourse; ?></h2>
                                </div>
                                <div style="background: #FFF;
                                     border: 1px solid #8fa1b9;
                                     box-shadow: 2px 1px 9px 0px #c5c5c5;
                                     border-radius: 0px 0px 10px 10px;
                                     padding: 10px 15px;">


                                    <?php echo $contenido_onlinecourse; ?>

                                    <div class="text-center">
                                        <?php
                                        $rqdlu1 = query("SELECT urltag FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ORDER BY id ASC limit 1 ");
                                        if (num_rows($rqdlu1) > 0) {
                                            $rqdlu2 = fetch($rqdlu1);
                                            $url_leccion_uno = 'curso-online.leccion/' . $rqdlu2['urltag'] . '/video.html'
                                        ?>
                                            <hr />
                                            <a href="<?php echo $url_leccion_uno; ?>" class="btn btn-lg btn-success" style="border-radius: 15px;
                                               padding: 10px 30px;
                                               border: 1px solid #c5edff;
                                               box-shadow: 2px 3px 3px 0px #c7c7c7;
                                               font-size: 17pt;">
                                                <i class="fa fa-caret-square-o-right"></i> &nbsp; COMENZAR CON EL CURSO
                                            </a>
                                            <hr />
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <?php
                                    $rqmc1 = query("SELECT * FROM cursos_onlinecourse_material WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' AND id_leccion='0' ");
                                    if (num_rows($rqmc1) > 0) {
                                    ?>
                                        <div>
                                            <h4 style="background: #318cb8;
                                                color: #FFF;
                                                padding: 7px 15px;
                                                border-radius: 5px;
                                                border-bottom: 4px solid #1b5b77;">MATERIAL DESCARGABLE PRINCIPAL</h4>
                                            <p>A continuaci&oacute;n se listan los materiales de apoyo principales para este curso, los cuales deben ser estudiados a su plenitud para una mejor asimilaci&oacute;n de los contenidos del curso.</p>
                                            <table class="table table-bordered table-striped table-hover">
                                                <?php
                                                $cnt = 1;
                                                while ($producto = fetch($rqmc1)) {
                                                ?>
                                                    <tr>
                                                        <td class=""><?php echo $cnt++; ?></td>
                                                        <td class="">
                                                            <?php
                                                            echo $producto['nombre'];
                                                            ?>
                                                        </td>
                                                        <td style="width:30px;">
                                                            <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/icon-pdf.ico" style="height:15px;" />
                                                        </td>
                                                        <td class="">
                                                            <?php
                                                            echo $producto['formato_archivo'];
                                                            ?>
                                                        </td>
                                                        <td class="">
                                                            <?php
                                                            echo $producto['nombre_fisico'];
                                                            ?>
                                                        </td>
                                                        <td class="" style="width:120px;">
                                                            <a href="<?php echo $dominio_www; ?>contenido/archivos/cursos/<?php echo $producto['nombre_fisico']; ?>" target="_blank" class="btn btn-xs btn-warning active"><i class='fa fa-eye'></i> ver/descargar</a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <br>
                                    <hr>

                                    <?php
                                    $rqmc1 = query("SELECT * FROM cursos_onlinecourse_tareas WHERE id_onlinecourse='$id_onlinecourse' AND estado='1' ");
                                    if (num_rows($rqmc1) > 0) {
                                    ?>
                                        <div>
                                            <h4 style="background: #318cb8;
                                                color: #FFF;
                                                padding: 7px 15px;
                                                border-radius: 5px;
                                                border-bottom: 4px solid #1b5b77;">TAREAS SOLICITADAS</h4>
                                            <p>A continuaci&oacute;n se listan las tareas solicitadas para este curso, los cuales deben ser completados y subidos al sistema para una mejor asimilaci&oacute;n de los contenidos del curso.</p>
                                            <table class="table table-bordered table-striped table-hover">
                                                <!--                                                    <div class="alert alert-info">
                                                        <strong>NOTA</strong> a&uacute;n no se registraron tareas pendientes para este curso.
                                                    </div>-->
                                                <?php
                                                $cnt = 1;
                                                while ($producto = fetch($rqmc1)) {
                                                    $qrdtr1 = query("SELECT id,archivo FROM cursos_onlinecourse_tareasenvios WHERE id_tarea='" . $producto['id'] . "' AND id_usuario='$id_usuario' ");
                                                    $sw_enviado = true;
                                                    if (num_rows($qrdtr1) == 0) {
                                                        $sw_enviado = false;
                                                    } else {
                                                        $qrdtr2 = fetch($qrdtr1);
                                                        $archivo_tarea = $qrdtr2['archivo'];
                                                        $id_envio_tarea = $qrdtr2['id'];
                                                    }
                                                ?>
                                                    <tr>
                                                        <td class=""><?php echo $cnt++; ?></td>
                                                        <td class="">
                                                            <?php echo 'CODT' . $producto['id']; ?>
                                                        </td>
                                                        <td class="">
                                                            <b><?php echo $producto['tarea']; ?></b>
                                                            <br>
                                                            <br>
                                                            <?php
                                                            echo $producto['descripcion'];
                                                            ?>
                                                        </td>
                                                        <td style="width:30px;">
                                                            <?php if (!$sw_enviado) { ?>
                                                                <i class="label label-danger">Sin env&iacute;o</i>
                                                            <?php } else { ?>
                                                                <i class="label label-success">TAREA ENVIADA</i>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center" style="padding: 15px;">
                                                            <?php if (!$sw_enviado) { ?>
                                                                <form action="" method="post" enctype="multipart/form-data">
                                                                    <input type="file" class="form-control" name="tarea" required="" />
                                                                    <input type="hidden" name="id_tarea" value="<?php echo $producto['id']; ?>" />
                                                                    <input type="submit" value="ENVIAR TAREA" class="btn btn-success btn-sm" name="enviar-tarea" />
                                                                </form>
                                                            <?php } else { ?>
                                                                <a onclick='window.open("<?php echo $dominio_www . 'contenido/archivos/tareas/' . $archivo_tarea; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=20,left=20,width=1000,height=800");' style="color: #0064ff;text-decoration: underline;cursor:pointer;">
                                                                    [ VISUALIZAR ENV&Iacute;O ]
                                                                </a>
                                                                <br>
                                                                <br>
                                                                <i style="color: gray;cursor: pointer;text-decoration: underline;" data-toggle="collapse" data-target="#tarea-<?php echo $producto['id']; ?>">Volver a enviar</i>
                                                                <div id="tarea-<?php echo $producto['id']; ?>" class="collapse">
                                                                    <br>
                                                                    <form action="" method="post" enctype="multipart/form-data">
                                                                        <input type="file" class="form-control" name="tarea" required="" />
                                                                        <input type="hidden" name="id_tarea" value="<?php echo $producto['id']; ?>" />
                                                                        <input type="hidden" name="id_envio" value="<?php echo $id_envio_tarea; ?>" />
                                                                        <input type="submit" value="VOLVER A ENVIAR" class="btn btn-info btn-sm" name="re-enviar-tarea" />
                                                                    </form>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <!-- 
                                    <br>
                                    <br>
                                    <hr/>
                                    <p>
                                        En la parte lateral izquierda se muestra el listado con cada una de las lecciones registradas para este curso, 
                                        debe ingresar a cada una de esas lecciones y revisar todo su contenido.
                                    </p>
                                       <p>
                                        En la parte lateral izquierda se muestra el listado con cada una de las lecciones registradas para este curso, 
                                        debe ingresar a cada una de esas lecciones y revisar todo su contenido, 
                                        tambi&eacute;n en la parte inferior derecha se encuentra el 'chat interactivo' con el docente asignado al curso, con el cual podr&aacute;s comunicarte y hacer las preguntas relacionadas a este curso directamente con el docente.
                                    </p>-->

                                </div>
                            </div>
                        </div>

                        <hr />

                        <?php
                        /* chat de curso */
                        //*include_once 'pages/items/item.b.chat_content_data.php';
                        ?>

                        <br />
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div style="height:10px"></div>
        </section>
    </div>

</div>


<script>
    function copyToClipboard(idbox) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById(idbox).innerHTML;
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
    }
</script>