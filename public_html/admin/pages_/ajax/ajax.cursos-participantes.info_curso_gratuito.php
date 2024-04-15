<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}


/* datos recibidos */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($rqdp1);
$id_curso = $participante['id_curso'];
$id_usuario = $participante['id_usuario'] == '0' ? '1' : $participante['id_usuario'];
$nom_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
$ci_participante = $participante['ci'];
$nombres_participante = $participante['nombres'];
$apellidos_participante = $participante['apellidos'];
$email_participante = $participante['correo'];
$celular_participante = $participante['celular'];
$id_departamento_participante = $participante['id_departamento'];
$id_pais = $participante['id_pais'];
$id_administrador = administrador('id');

/* codigo pais */
$rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
$rqdcw2 = fetch($rqdcw1);
$codigo_pais = $rqdcw2['codigo'];

/* asigancion de curso */
if (isset_post('id_curso_asign')) {
    $id_participante;
    $id_curso_asign = post('id_curso_asign');
    
    /* creacion de usuario */
    if ($id_usuario == '1' || $id_usuario == '0') {
        if(strlen($ci_participante)>4){
            $password = substr($ci_participante,0,4).strtolower(substr("ABCDEFGHJKLMNPQRSTUVWXYZ",rand(0,23),1));
        }else{
            $password = substr(md5(rand(9, 999)), 2, 5);
        }
        $fecha_registro = date("Y-m-d");
        $rqvpc1 = query("SELECT id,password FROM cursos_usuarios WHERE ci='$ci_participante' AND email='$email_participante' ");
        if (num_rows($rqvpc1) == 0) {
            query("INSERT INTO cursos_usuarios(
                           nombres, 
                           apellidos, 
                           ci, 
                           email, 
                           celular, 
                           password, 
                           id_departamento, 
                           sw_docente, 
                           fecha_registro, 
                           estado
                           ) VALUES (
                           '$nombres_participante',
                           '$apellidos_participante',
                           '$ci_participante',
                           '$email_participante',
                           '$celular_participante',
                           '$password',
                           '$id_departamento_participante',
                           '0',
                           '$fecha_registro',
                           '1'
                           )");
            $id_usuario = insert_id();
            logcursos('Creacion y asignacion de usuario [U:' . $id_usuario . ']', 'partipante-edicion', 'participante', $id_participante);
            echo '<div class="alert alert-info">
            <strong>USUARIO CREADO</strong> Exito, el usuario se creo correctamente.
        </div>';
        } else {
            $rqvpc2 = fetch($rqvpc1);
            $id_usuario = $rqvpc2['id'];
        }
        query("UPDATE cursos_participantes SET id_usuario='$id_usuario' WHERE id='$id_participante' ORDER BY id DESC limit 1");
    }

    /* reg */
    $rqvcgauduax1 = query("SELECT id FROM cursos_rel_usuariocurfreecur WHERE id_participante='$id_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if(num_rows($rqvcgauduax1)==0){
        query("INSERT INTO cursos_rel_usuariocurfreecur(id_usuario, id_curso, id_participante, id_curso_free, estado) VALUES ('$id_usuario','$id_curso','$id_participante','0','1')");
        $id_reg = insert_id();
    }else{
        $rqvcgauduax2 = fetch($rqvcgauduax1);
        $id_reg = $rqvcgauduax2['id'];
    }
    

    /* registro de participante en nuevo curso */
    $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres_participante' AND apellidos='$apellidos_participante' AND id_curso='$id_curso_asign' ORDER BY id DESC limit 1 ");
    if (num_rows($rqpcv1) == 0) {
        $rqda1 = query("SELECT p.*,pr.razon_social,pr.nit,pr.id_turno FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE p.id='$id_participante' ORDER BY id DESC limit 1 ");
        $rqda2 = fetch($rqda1);
        $id_participante_pre = $id_participante;
        $prefijo = $rqda2['prefijo'];
        $nombres = $rqda2['nombres'];
        $apellidos = $rqda2['apellidos'];
        $ci = $rqda2['ci'];
        $celular = $rqda2['celular'];
        $correo = $rqda2['correo'];
        $observacion = $rqda2['observaciones'];
        $razon_social = $rqda2['razon_social'];
        $nit = $rqda2['nit'];
        $monto_pago = 0;
        $id_turno = $rqda2['id_turno'];
        $ci_expedido = $rqda2['ci_expedido'];
        $numeracion = 0;
        $id_modo_pago = '10';
        

        $cod_reg = substr("RM-$id_curso_asign-" . str_replace(" ", "-", $nombres), 0, 14);
        $fecha_registro = date("Y-m-d H:i:s");
        query("INSERT INTO cursos_proceso_registro(
                      id_curso, 
                      id_turno,
                      id_administrador,
                      cod_reg, 
                      id_modo_pago, 
                      sw_pago_enviado, 
                      paydata_id_administrador, 
                      paydata_fecha, 
                      cnt_participantes, 
                      razon_social, 
                      nit, 
                      monto_deposito, 
                      fecha_registro, 
                      estado
                      ) VALUES (
                      '$id_curso_asign',
                      '$id_turno',
                      '$id_administrador',
                      '$cod_reg',
                      '$id_modo_pago',
                      '1',
                      '$id_administrador',
                      '$fecha_registro',
                      '1',
                      '$razon_social',
                      '$nit',
                      '$monto_pago',
                      '$fecha_registro',
                      '1'
                      )");
        $id_proceso_registro = insert_id();
        $codigo_registro = "RM00" . $id_proceso_registro;
        query("UPDATE cursos_proceso_registro SET codigo='$codigo_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

        query("INSERT INTO cursos_participantes (
                      id_curso,
                      id_usuario,
                      id_proceso_registro,
                      id_turno,
                      prefijo,
                      nombres,
                      apellidos,
                      ci,
                      ci_expedido,
                      numeracion, 
                      id_modo_pago, 
                      celular,
                      correo,
                      observacion
                      ) VALUES (
                      '$id_curso_asign',
                      '$id_usuario',
                      '$id_proceso_registro',
                      '$id_turno',
                      '$prefijo',
                      '$nombres',
                      '$apellidos',
                      '$ci',
                      '$ci_expedido',
                      '$numeracion',
                      '$id_modo_pago',
                      '$celular',
                      '$correo',
                      '$observacion'
                      ) ");
        $id_participante_new = insert_id();

        logcursos('Registro de participante [C-FREE][' . $codigo_registro . ']', 'participante-registro', 'participante', $id_participante_new);
        query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso_asign' ORDER BY id DESC limit 1 ");

        echo '<div class="alert alert-info">
            <strong>PARTICIPANTE REGISTRADO</strong> Exito, el participante se agrego correctamente.
        </div>';
    } else {
        $rqpcv2 = fetch($rqpcv1);
        $id_participante_new = $rqpcv2['id'];
        query("UPDATE cursos_participantes SET estado='1',id_usuario='$id_usuario' WHERE id='$id_participante_new' ORDER BY id DESC limit 1 ");
    }


    /* activacion de cursos virtuales */
    $id_curso_asign;
    $id_usuario;
    $id_participante_new;
    $rqdcvas1 = query("SELECT oc.id,oc.titulo FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=oc.id WHERE r.id_curso='$id_curso_asign' AND r.estado='1' AND oc.id_tipo_curso_virtual='1' ");
    while ($rqdcvas2 = fetch($rqdcvas1)) {
        $id_onlinecourse = $rqdcvas2['id'];
        $titulo_onlinecourse = $rqdcvas2['titulo'];
        $fecha_final_curso = date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d'))));
        /* creacion de registro de acceso */
        $rqvacc1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='$id_onlinecourse' AND id_usuario='$id_usuario' ");
        if (num_rows($rqvacc1) == 0) {
            query("INSERT INTO cursos_onlinecourse_acceso(
                        id_onlinecourse, 
                        id_usuario, 
                        sw_acceso, 
                        fecha_inicio, 
                        fecha_final, 
                        fecha_activacion, 
                        id_administrador_activacion, 
                        estado
                        ) VALUES (
                        '$id_onlinecourse',
                        '$id_usuario',
                        '1',
                        CURDATE(),
                        '$fecha_final_curso',
                        NOW(),
                        '$id_administrador',
                        '1'
                        )");
            query("UPDATE cursos_participantes SET sw_cvirtual='1' WHERE id='$id_participante_new' ORDER BY id DESC limit 1 ");
            logcursos('HABILITACION A CURSO VIRTUAL', 'partipante-cvirtual', 'participante', $id_participante_new);
            echo '<div class="alert alert-warning">
            <strong>CURSO VIRTUAL ACTIVADO</strong> ' . $titulo_onlinecourse . '.
        </div>';
        }
    }


    query("UPDATE cursos_rel_usuariocurfreecur SET id_usuario='$id_usuario',id_curso_free='$id_curso_asign',estado='2' WHERE id='$id_reg' ORDER BY id DESC limit 1 ");
    echo '<div class="alert alert-success">
            <strong>PROCESO FINALIZADO</strong> el proceso de asignacion de cursos gratuitos virtuales se completo correctamente.
        </div>';
}

/* usuario */
$rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario' ");
$rqddu2 = fetch($rqddu1);
$user_usuario = $rqddu2['email'];
$password_usuario = $rqddu2['password'];


$rqvcgau1 = query("SELECT r.*,c.titulo FROM cursos_rel_usuariocurfreecur r INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id_participante='$id_participante' AND r.id_curso='$id_curso' ORDER BY r.id DESC limit 1 ");
if (num_rows($rqvcgau1) == 0) {
    $rqdcaux1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcaux2 = fetch($rqdcaux1);
    ?>
    <div class="alert alert-danger">
        <strong>AVISO</strong> el participante no seleccion&oacute; un curso gratuito al momento de inscripci&oacute;n.
    </div>
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">
                CURSO REGISTRADO
            </th>
        </tr>
        <tr>
            <td>
                Curso:
            </td>
            <td>
                <?php echo $rqdcaux2['titulo']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Participante:
            </td>
            <td>
                <?php echo $nom_participante; ?>
            </td>
        </tr>
        <tr>
            <td>
                Asignaci&oacute;n:
            </td>
            <td>
                    <div class="alert alert-danger">
                        <strong>SIN ASIGNACION</strong> no se asignaron los cursos.
                    </div>
            </td>
        </tr>
    </table>
    <hr>
    <button class="btn btn-xs btn-default btn-block" data-toggle="collapse" data-target="#COLLAPSE-cursos-ofrecidos"><i class="fa fa-list"></i> CURSO GRATUITOS OFRECIDOS</button>
    <div id="COLLAPSE-cursos-ofrecidos">
        <?php
        /* cursos gratuitos */
        $rqdcg1 = query("SELECT r.id,c.titulo,(c.id)dr_id_curso_selec FROM cursos_rel_cursofreecur r INNER JOIN cursos c ON r.id_curso_free=c.id WHERE r.id_curso='$id_curso' AND r.estado='1' ");
        ?>
        <table class="table table-striped table-bordered">
            <?php
            $data_seleccion_sw = false;
            $data_seleccion_ids_cvir = '0';
            $data_seleccion_id_participante = 0;
            while ($rqdcg2 = fetch($rqdcg1)) {
                $list__id_curso = (int) $rqdcg2['dr_id_curso_selec'];
                $sw_selecionado = false;
                ?>
                <tr>
                    <td>
                        <?php echo $rqdcg2['titulo']; ?>
                        <br>
                        ID: <?php echo $list__id_curso; ?>
                        <br>
                        <?php
                        $rqdaccup1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$list__id_curso' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
                        if (num_rows($rqdaccup1) == 0) {
                            echo "<label class='label label-default'>No registrado</label>";
                        } else {
                            echo "<label class='label label-primary'>REGISTRADO</label>";
                        }
                        ?>
                    </td>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $rqdcva1 = query("SELECT oc.id,oc.titulo FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=oc.id WHERE r.id_curso='$list__id_curso' AND r.estado='1' AND oc.id_tipo_curso_virtual='1' ");
                            while ($rqdcva2 = fetch($rqdcva1)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $rqdcva2['titulo']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $rqdaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='" . $rqdcva2['id'] . "' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
                                        if (num_rows($rqdaccv1) == 0) {
                                            echo "<label class='label label-danger'>No activado</label>";
                                        } else {
                                            echo "<label class='label label-primary'>ACTIVADO</label>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <?php
                        echo "<label class='label label-default'>No seleccionado</label>";
                        echo "<br><br><b class='btn btn-success' onclick='activacion($list__id_curso);'>ASIGNAR</b>";
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
} else {
    $rqvcgau2 = fetch($rqvcgau1);
    $id_curso_free = $rqvcgau2['id_curso_free'];
    $id_reg = $rqvcgau2['id'];
    $estado_reg = $rqvcgau2['estado'];
    ?>
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">
                CURSO REGISTRADO
            </th>
        </tr>
        <tr>
            <td>
                Curso:
            </td>
            <td>
                <?php echo $rqvcgau2['titulo']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Participante:
            </td>
            <td>
                <?php echo $nom_participante; ?>
            </td>
        </tr>
        <tr>
            <td>
                Asignaci&oacute;n:
            </td>
            <td>
                <?php
                if ($estado_reg != '2') {
                    $aux_class_collapse = '';
                    ?>
                    <div class="alert alert-danger">
                        <strong>SIN ASIGNACION</strong> no se asignaron los cursos.
                    </div>
                    <?php 
                    if($id_curso_free!='0'){ 
                        $aux_class_collapse = 'collapse';
                        ?>
                        <div class="text-center">
                            <b class='btn btn-success' onclick="activacion('<?php echo $id_curso_free; ?>');">ASIGNAR CURSOS</b>
                            <br>
                            &nbsp;
                        </div>
                    <?php 
                    } 
                    ?>
                    <?php
                } else {
                    $aux_class_collapse = 'collapse';
                    ?>
                    <div class="alert alert-success">
                        CURSOS GRATUTITOS ASIGNADOS.
                    </div>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Usuario:
            </td>
            <td>
                <?php echo $rqvcgau2['id_usuario']; ?>  &nbsp;&nbsp;&nbsp;&nbsp; ID reg: <?php echo $id_reg; ?>
            </td>
        </tr>
    </table>

    <hr>
    <button class="btn btn-xs btn-default btn-block" data-toggle="collapse" data-target="#COLLAPSE-cursos-ofrecidos"><i class="fa fa-list"></i> CURSO GRATUITOS OFRECIDOS</button>
    <div id="COLLAPSE-cursos-ofrecidos" class="<?php echo $aux_class_collapse; ?>">
        <?php
        /* cursos gratuitos */
        $rqdcg1 = query("SELECT r.id,c.titulo,(c.id)dr_id_curso_selec FROM cursos_rel_cursofreecur r INNER JOIN cursos c ON r.id_curso_free=c.id WHERE r.id_curso='$id_curso' AND r.estado='1' ");
        ?>
        <table class="table table-striped table-bordered">
            <?php
            $data_seleccion_sw = false;
            $data_seleccion_ids_cvir = '0';
            $data_seleccion_id_participante = 0;
            while ($rqdcg2 = fetch($rqdcg1)) {
                $list__id_curso = (int) $rqdcg2['dr_id_curso_selec'];
                $sw_selecionado = false;
                if ($id_curso_free == $list__id_curso) {
                    $sw_selecionado = true;
                }
                ?>
                <tr>
                    <td>
                        <?php echo $rqdcg2['titulo']; ?>
                        <br>
                        ID: <?php echo $list__id_curso; ?>
                        <br>
                        <?php
                        $rqdaccup1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$list__id_curso' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
                        if (num_rows($rqdaccup1) == 0) {
                            echo "<label class='label label-default'>No registrado</label>";
                        } else {
                            echo "<label class='label label-primary'>REGISTRADO</label>";
                            if($sw_selecionado){
                                $rqdaccup2 = fetch($rqdaccup1);
                                $data_seleccion_id_participante = (int)$rqdaccup2['id'];
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <table class="table table-bordered">
                            <?php
                            $rqdcva1 = query("SELECT oc.id,oc.titulo FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_onlinecourse=oc.id WHERE r.id_curso='$list__id_curso' AND r.estado='1' AND oc.id_tipo_curso_virtual='1' ");
                            while ($rqdcva2 = fetch($rqdcva1)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $rqdcva2['titulo']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $rqdaccv1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_onlinecourse='" . $rqdcva2['id'] . "' AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
                                        if (num_rows($rqdaccv1) == 0) {
                                            echo "<label class='label label-danger'>No activado</label>";
                                            if ($sw_selecionado) {
                                                echo "<br><br>";
                                                echo "<b class='btn btn-xs btn-success' onclick='alert(\"EN DESARROLLO\");'>Activar</b>";
                                            }
                                        } else {
                                            echo "<label class='label label-primary'>ACTIVADO</label>";
                                            if ($sw_selecionado) {
                                                $data_seleccion_ids_cvir .= ','.(int)$rqdcva2['id'];
                                                $data_seleccion_sw = true;
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <?php
                        if ($sw_selecionado) {
                            echo "<label class='label label-primary'>SELECCIONADO</label>";
                        } else {
                            echo "<label class='label label-default'>No seleccionado</label>";
                        }
                        if ( ( ($estado_reg != '2' && $sw_selecionado) || $id_curso_free == '0') || false) {
                            echo "<br><br><b class='btn btn-success' onclick='activacion($list__id_curso);'>ASIGNAR</b>";
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <hr>
    <?php
    if($data_seleccion_sw){
        
        $rqccg1 = query("SELECT oc.*,(r.fecha_final)dr_fecha_final FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON oc.id=r.id_onlinecourse WHERE oc.id IN ($data_seleccion_ids_cvir) GROUP BY oc.id ");
        $cnt_certs_validos = 0;
        $cnt_certs_ya_emitidos = 0;
        $ids_participantes_ya_emitidos = '';
        $contenido_textarea = '';
        $contenido_whatsapp = '';
        $contenido_div_copy = '';

        while ($curso = fetch($rqccg1)) {
            
            $hash_iduser = $id_usuario . substr(md5('rtc' . $id_usuario . '-754'), 19, 3);
            
            /* curso virtual */
            $id_onlinecourse = $curso['id'];
            $nombre_curso_virtual = $curso['titulo'];
            $urltag_curso_virtual = $curso['urltag'];
            $url_ingreso_cv = $dominio_plataforma.'ingreso/' . $urltag_curso_virtual . '/'.$hash_iduser.'.html';
            $fecha_final_curso_virtual = $curso['dr_fecha_final'];
            
            $txt_mensajecursopregrabado = ('El curso está activo y puede pasar en sus tiempos libres 24/7 tiene hasta el '.date("d/m/Y",strtotime($fecha_final_curso_virtual)).' para repetir el curso las veces que usted considere, una vez finalizado cada curso puede descargar el certificado Digital de nuestra plataforma.');

            $contenido_textarea .= '
*' . $nombre_curso_virtual . '*
*LINK DE INGRESO:*
' . $url_ingreso_cv . '
*USUARIO:* ' . $user_usuario . '
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '

';
            $contenido_div_copy .= '
<br>
*' . $nombre_curso_virtual . '*
<br>
*LINK DE INGRESO:*
<br>
' . $url_ingreso_cv . '
<br>
*USUARIO:*  ' . $user_usuario . '
<br>
*CONTRASE&Ntilde;A:*  ' . $password_usuario . '
<br>
<br>
';
            $contenido_whatsapp .= ' __-------------------------------__*' . ($nombre_curso_virtual) . '*__ __*Link de ingreso:*__' . $url_ingreso_cv . '__ __*Usuario:* ' . $user_usuario . '__*Contraseña:*  ' . $password_usuario . '__';
        }
        
        if($sw_ipelc=='1'){
            $txt_mensajecursopregrabado = $txt_mensajecursoipelc = 'Estos son los datos de acceso a nuestra plataforma, con el podrá enviar sus tareas, podrá dar Examen en Linea, podrá enviar los documentos para la certificación de la IPELC, podrá hacer seguimiento a la certificación IPELC.';
        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">ACCESO A CURSOS POR WHATSAPP</div>
                    <div class="panel-body text-center">
                        <?php
                        if (strlen(trim($celular_participante)) == 8) {
                            $txt_whatsapp = 'Hola ' . ($nombres_participante . ' ' . $apellidos_participante) . '__te hacemos el envío de los datos de acceso a sus cursos virtuales:__ __'.$txt_mensajecursopregrabado.'__'.$contenido_whatsapp;
                            $txt_whatsapp .= '__ __-------------------------------------------------------------__Ayúdanos a superar los 100 mil likes en nuestra página en facebook__https://www.facebook.com/cursoswebbolivia__ __Únete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia';
                            $txt_whatsapp = (str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                            ?>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo $codigo_pais.trim($celular_participante);    ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/wap1.jpg" style="height: 40px;border-radius: 5px;"/>
                            </a>
                            <?php
                        } else {
                            echo "Celular incorrecto!";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">ACCESO A CURSOS POR CORREO</div>
                    <div class="panel-body text-center" id="ajaxbox-cvirtual_enviar_correo_accesos">
                        <b class="btn btn-success btn-lg" onclick="cvirtual_enviar_correo_accesos('<?php echo $data_seleccion_id_participante; ?>');"><i class="fa fa-send"></i> ENVIAR</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">ACCESO A CURSO EN FORMATO TEXTO <b class="btn btn-default btn-xs pull-right" onclick="copyToClipboard('cont-accesos')">COPY</b></div>
            <div class="panel-body">
                <textarea class="form-control" style="height: 620px;"><?php echo ($txt_mensajecursopregrabado).'

'.$contenido_textarea; ?></textarea>
            </div>
            <div class="form-control" style="display:none;" id="cont-accesos"><?php echo $txt_mensajecursopregrabado.'<br>'.$contenido_div_copy; ?></div>
        </div>
        <?php
    }
}
?>
<!-- INFO CURSO GRATUTITO p2 -->
<script>
    function activacion(id_curso_asign) {
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.info_curso_gratuito.php',
            data: {id_participante: '<?php echo $id_participante; ?>', id_curso_asign: id_curso_asign},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- enviar_correo_accesos -->
<script>
    function cvirtual_enviar_correo_accesos(id_participante) {
        $("#ajaxbox-cvirtual_enviar_correo_accesos").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cvirtual_enviar_correo_accesos.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxbox-cvirtual_enviar_correo_accesos").html(data);
            }
        });
    }
</script>