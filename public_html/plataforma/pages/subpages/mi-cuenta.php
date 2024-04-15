<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                <div class="TituloArea">
                    <h3>DATOS DE USUARIO</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n encontraras los datos relacionados a tu cuenta.
                    </p>
                </div>

                <?php echo $mensaje; ?>


                <div class="boxForm ajusta_form_contacto">
                    <h5><?php echo $nombre_usuario; ?></h5>
                    <hr/>
                    <div class="row">

                        <div style="background:#FFF;padding: 5px;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td>NOMBRE</td>
                                    <td><?php echo $nombre_usuario; ?></td>
                                </tr>
                                <tr>
                                    <td>EMAIL</td>
                                    <td>
                                        <?php
                                        if ($email_usuario !== '') {
                                            echo $email_usuario;
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CELULAR</td>
                                    <td>
                                        <?php
                                        if ($celular_usuario !== '') {
                                            echo $celular_usuario;
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPARTAMENTO</td>
                                    <td>
                                        <?php
                                        if ($id_departamento_usuario !== '0') {
                                            $rqdd1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_usuario' LIMIT 1 ");
                                            $rqdd2 = fetch($rqdd1);
                                            echo $rqdd2['nombre'];
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TIPO DE USUARIO</td>
                                    <td>PARTICIPANTE</td>
                                </tr>
                                <tr>
                                    <td>TIPO DE CUENTA</td>
                                    <td>FREE</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
                <br/>
                <hr/>                
                <?php
                /* registro */
                if (isset_post('id_curso_free')) {
                    $id_curso_free = post('id_curso_free');

                    $rqda1 = query("SELECT p.*,pr.razon_social,pr.nit,pr.id_turno FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id INNER JOIN cursos_rel_usuariocurfreecur ru ON ru.id_participante=p.id INNER JOIN cursos_rel_cursofreecur rc ON ru.id_curso=rc.id_curso INNER JOIN cursos c ON rc.id_curso_free=c.id WHERE ru.id_usuario='$id_usuario' AND c.estado IN (1,2) AND rc.id_curso_free='$id_curso_free' GROUP BY c.id ");
                    if (num_rows($rqda1) > 0) {
                        $rqda2 = fetch($rqda1);


                        /* add part */

                        /* datos recibidos */
                        $id_curso = $id_curso_free;
                        $id_participante_pre = $rqda2['id'];
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
                        $id_administrador = 0;


                        /* verificacion de existencia */
                        $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                        if (num_rows($rqpcv1) > 0) {
                            echo '<div class="alert alert-info">
                <strong>AVISO</strong> nombre ya existe como participante en este curso.
            </div>';
                        } else {
                            $cod_reg = substr("RM-$id_curso-" . str_replace(" ", "-", $nombres), 0, 14);
                            $fecha_registro = date("Y-m-d H:i:s");
                            query("INSERT INTO cursos_proceso_registro(
                      id_curso, 
                      id_modo_de_registro,
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
                      '$id_curso',
                      '2',
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
                      '$id_curso',
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
                            $id_participante = insert_id();

                            logcursos('Registro de participante [FREE][' . $codigo_registro . '][ADM]', 'participante-registro', 'participante', $id_participante);
                            query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");


                            echo '<div class="alert alert-success">
            <strong>Exito!</strong> participante agregado correctamente.
        </div>';
                            query("UPDATE cursos_rel_usuariocurfreecur SET estado='2',id_curso_free='$id_curso' WHERE id_usuario='$id_usuario' AND id_participante='$id_participante_pre' ORDER BY id DESC limit 1 ");
                        }
                        /* END add part */
                    }
                }

                /* curso gratuito */
                $rqcu1 = query("SELECT c.titulo,c.titulo_identificador,c.id FROM cursos_rel_usuariocurfreecur ru INNER JOIN cursos_rel_cursofreecur rc ON ru.id_curso=rc.id_curso INNER JOIN cursos c ON rc.id_curso_free=c.id WHERE ru.estado='1' AND ru.id_usuario='$id_usuario' AND c.estado IN (1,2) GROUP BY c.id ");
                if (num_rows($rqcu1) > 0) {
                    ?>
                    <div class="TituloArea">
                        <h3>CURSO GRATUITO</h3>
                    </div>
                    <div class="alert alert-success">
                        <strong>FELICIDADES!</strong> puedes seleccionar y obtener un curso de forma gratuita.
                    </div>
                    <div class="Titulo_texto1">
                        <p>
                            Por favor selecciona cuidadosamente en cual de los siguientes cursos deseas participar. (solamente es posible seleccionar una opci&oacute;n)
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                CURSO
                            </th>
                            <th>
                                DETALLES
                            </th>
                            <th>
                                ASIGNACI&Oacute;N
                            </th>
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcu1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['titulo']; ?>
                                </td>
                                <td>
                                    <a href="<?php echo $rqcu2['titulo_identificador']; ?>.html" target="_blank" style="text-decoration: underline">Ver detalles</a>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="submit" class="btn btn-sm btn-primary btn-block" value="SELECCIONAR" name="seleccionar-curso"/>
                                        <input type="hidden" value="<?php echo $rqcu2['id']; ?>" name="id_curso_free"/>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <br>
                    <?php
                }
                ?>

                <hr>

                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
            </div>
        </div>
    </section>
</div>

<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
