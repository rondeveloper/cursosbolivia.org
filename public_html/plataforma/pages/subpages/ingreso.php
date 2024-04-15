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

/* EXISTENCIA DE USUARIO */
if (isset_usuario()) {
    echo "<script>location.href='".$dominio_plataforma."curso-online/".$urltag_onlinecourse.".html';</script>";
    exit;
}

/* chat */
$roomcod = '0';

/* sw acceso */
$sw_acceso_a_curso = false;
if (isset_usuario()) {
    $id_usuario = usuario('id');
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
        $mensaje .= '<div class="alert alert-danger">
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

$correo_get = '';
if(isset($get[3])){
    $id_us = substr($get[3],0,(count($get[3])-4));
    $hash_id = substr($get[3],(count($get[3])-4));
    if($hash_id==substr(md5('rtc'.$id_us.'-754'),19,3)){
        $rqdcu1 = query("SELECT email FROM cursos_usuarios WHERE id='$id_us' ORDER BY id DESC limit 1 ");
        $rqdcu2 = fetch($rqdcu1);
        $correo_get = $rqdcu2['email'];
    }
}
?>

<div style="height:50px;"></div>
<div class="boxcontent-curso-online">
    <div class="wrapsemibox" style="margin-top: 10px;margin-bottom: 10px;">
        <section class="containerXX" style="padding: 2px 5px;">
            <div style="height:10px"></div>

            <div class="row">
                <?php
                include_once 'pages/items/item.m.datos_onlinecourse.php';
                ?>
            </div>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6" style="margin-bottom:150px;">
                    <div class="">
                        <h3 class="text-center" style="background: #2897c7;color: #FFF;margin-bottom: 0px;padding: 20px;box-shadow: inset 1px 0px 8px 5px #10b2e4;border: 1px solid #37a9da;border-radius: 5px;">PLATAFORMA VIRTUAL</h3>
                    </div>
                    <div class="text-center" style="margin:30px 0px 70px 0px;">
                        Bienvenid@ a nuestra plataforma virtual de aprendizaje, donde podr&aacute;s acceder a los materiales, videos, evaluaci&oacute;nes, certificados y otros contenidos de los cursos a los cuales te registraste en <?php echo $___nombre_del_sitio; ?>, esperamos que adquieras mucho conocimiento.
                    </div>

                    <?php echo $mensaje; ?>

                    <div class="boxForm ajusta_form_contacto" style="background: #e8e8e8;box-shadow: 0px 1px 10px 6px #d6d8f1;border: 1px solid white;margin: 0px;">
                        <h5>INGRESA A TU CUENTA</h5>
                        <hr>
                        <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1" style="background:#fbfbfb;"><i class="fa fa-user"></i> Usuario: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <input class="form-control required string" type="text" name="usuario" placeholder="Usuario..." value="<?php echo trim($correo_get); ?>" required="">
                                </div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1" style="background:#fbfbfb;"><i class="fa fa-user"></i> Contrase&ntilde;a:</span>
                                    <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                </div>
                            </div>
                            <br>                  					
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR" style="padding: 10px;border-radius: 5px;"/>
                                </div>
                            </div>
                            <hr>
                        </form>
                    </div>

                </div>
            </div>
        
            <div style="height:10px"></div>
        </section>
    </div>   
</div>

