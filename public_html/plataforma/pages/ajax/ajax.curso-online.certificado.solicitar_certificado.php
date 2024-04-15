<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_usuario()) {
    echo "DENEGADO";
    exit;
}

/* recepcion de datos POST */
$id_usuario = post('id_usuario');
$id_onlinecourse = post('id_onlinecourse');
$nro_cert = post('nro_cert');

/* evaluacion */
$rqdcv1 = query("SELECT sw_examen FROM cursos_onlinecourse WHERE id='$id_onlinecourse' ORDER BY id DESC limit 1 ");
$onlinecourse = fetch($rqdcv1);
$sw_examen_aprobado = false;
$rqdael1 = query("SELECT * FROM cursos_onlinecourse_evaluaciones WHERE id_usuario='$id_usuario' AND id_onlinecourse='$id_onlinecourse' ORDER BY total_correctas DESC limit 1 ");
if (num_rows($rqdael1)>0) {
    $rqdael2 = fetch($rqdael1);
    $nota_examen = round(($rqdael2['total_correctas']/$rqdael2['total_preguntas'])*100);
    if($nota_examen>50){
        $sw_examen_aprobado = true;
    }
}

/* sw_finalizacion */
$sw_finalizacion = true;
$rqlcv1 = query("SELECT id,titulo,minutos FROM cursos_onlinecourse_lecciones WHERE estado='1' AND id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
while ($rqlcv2 = fetch($rqlcv1)) {
    $id_leccion = $rqlcv2['id'];
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
            $sw_finalizacion = true;
        }
        $tt_leccion = round(($rqdavl2['segundos']) / 60, 2) . '/' . $rqdavl2['minutos'];
    }
    if ($p <= 90) {
        $sw_finalizacion = false;
    }
}

/* contenido */
if (!$sw_finalizacion) {
    if($onlinecourse['sw_examen']=='1'){
        ?>
        <p>Para la emisi&oacute;n del certificado es necesario que se complete todos los modulos del curso y se apruebe el examen de evaluaci&oacute;n con mas del 50%.</p>
        <div class="alert alert-warning">
            <strong>AVISO</strong> no se han completado los modulos necesarios.
        </div>
        <?php
    }else{
        ?>
        <p>Para la emisi&oacute;n del certificado es necesario que se complete todos los modulos del curso.</p>
        <div class="alert alert-warning">
            <strong>AVISO</strong> no se han completado los modulos necesarios.
        </div>
        <?php
    }
} else {
    
    if($onlinecourse['sw_examen']=='1' && (!$sw_examen_aprobado) ){
        ?>
        <p>Para la emisi&oacute;n del certificado es necesario que se complete todos los modulos del curso y se apruebe el examen de evaluaci&oacute;n con mas del 50%.</p>
        <div class="alert alert-warning">
            <strong>AVISO</strong> no se han completado y aprobado el examen.
        </div>
        <?php
    }else{
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
        if (num_rows($qrdp1) == 0) {
            ?>
            <div class="alert alert-danger">
                <strong>ERROR</strong> no se encontraron los registros.
            </div>
            <?php
        } else {
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
            $prefijo_participante = $qrdp2['dr_prefijo_participante'];
            $nom_participante = $qrdp2['dr_nom_participante'];
            $ape_participante = $qrdp2['dr_ape_participante'];

            /* certificado */
            ?>
            <div class="alert alert-success">
                <strong>PARTICIPANTE HABILITADO</strong>
            </div>

            <table class="table table-striped table-bordered">
                <tr>
                    <td><b>USUARIO:</b></td>
                    <td><?php echo 'U000' . $id_usuario; ?></td>
                </tr>
                <tr>
                    <td><b>REGISTRADO EN CURSO:</b></td>
                    <td><?php echo $nombre_curso; ?></td>
                </tr>
                <tr>
                    <td><b>PARTICIPANTE:</b></td>
                    <td><?php echo $nombre_participante; ?></td>
                </tr>
                <tr>
                    <td><b>CERTIFICADO:</b></td>
                    <td>
                        <?php
                        $sw_certificado_asigando = false;
                        if ($id_certificado == '0') {
                            echo "SIN CERTIFICADO ASIGNADO";
                        } else {
                            $sw_certificado_asigando = true;
                            /* data certificado */
                            $rqddcacv1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ");
                            if (num_rows($rqddcacv1) > 0) {
                                $sw_certificado_asigando = true;
                                $certificado = fetch($rqddcacv1);
                                echo $certificado['codigo'];
                                echo "<br>";
                                echo $certificado['texto_qr'];
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <hr>
            <?php
            if (!$sw_certificado_asigando) {
                ?>
                <div class="alert alert-info">
                    <strong>AVISO</strong> este curso a&uacute;n no tiene asignado un certificado.<br>Favor de contactarse con nosotros e indicar que el curso <b>ID: <?php echo $id_curso; ?></b> no tiene asignado el certificado correspondiente.
                </div>
                <?php
            } else {
                /* emision de certificado */
                $rqedc1 = query("SELECT * FROM cursos_emisiones_certificados WHERE id_participante='$id_participante' AND id_certificado='$id_certificado' ");
                if (num_rows($rqedc1) == 0) {
                    ?>
                    <div class="alert alert-warning">
                        <strong>CERTIFICADO NO EMITIDO</strong><br>El certificado correspondiente a este curso a&uacute;n no fue emitido a '<?php echo $nombre_participante; ?>'.
                    </div>
                    <p>Para realizar la emisi&oacute;n del certificado favor de verificar los datos que aparecen en el siguiente formulario (modificar en caso de ser necesario) y posteriormente presionar el bot&oacute;n 'EMITIR CERTIFICADO'.</p>
                    <hr>
                    <b>DATOS DE EMISI&Oacute;N DE CERTIFICADO</b>
                    <br>
                    <form action="" method="post">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td><b>PREFIJO:</b><br>(opcional)</td>
                                <td><input type="text" name="prefijo_participante" value="<?php echo $prefijo_participante; ?>" class="form-control" placeholder="Abreviatura de profesion... (opcional)"/></td>
                            </tr>
                            <tr>
                                <td><b>NOMBRES:</b></td>
                                <td><input type="text" name="nombres_participante" value="<?php echo $nom_participante; ?>" required="" class="form-control"/></td>
                            </tr>
                            <tr>
                                <td><b>APELLIDOS:</b></td>
                                <td><input type="text" name="apellidos_participante" value="<?php echo $ape_participante; ?>" required="" class="form-control"/></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br>
                                    <input type="hidden" name="nro_cert" value="<?php echo $nro_cert; ?>"/>
                                    <input type="submit" class="btn btn-block btn-success" name="proceso-emision-certificado" value="EMITIR CERTIFICADO"/>
                                    <br>&nbsp;
                                </td>
                            </tr>
                        </table>
                    </form>
                    <hr>
                    <?php
                }else{
                    ?>
                    <div class="alert alert-success">
                        <strong>CERTIFICADO EMITIDO</strong><br>El certificado correspondiente a este curso ya fue emitido a '<?php echo $nombre_participante; ?>'.
                    </div>
                    <?php
                }
            }
        }
    }
}

